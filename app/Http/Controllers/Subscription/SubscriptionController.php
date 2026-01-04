<?php

namespace App\Http\Controllers\Subscription;
use App\Enums\CardStatus;
use App\Enums\ProductType;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardAssignment;
use App\Models\Client;
use App\Models\Price;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller{
    public function create(Request $request, $id){
        $product = Product::with('prices')->where('id', $id)->firstOrFail();
        
        return view('subscription.create', compact('product'));
    }

    public function store(Request $request){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::SELL)) {
            abort(403, 'У вас немає доступу для створення продукту');
        }

        $validated = $request->validate([
            'price_id' => 'required|exists:prices,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'barcode' => 'required'
        ]);

        $price = Price::findOrFail($request->price_id);
        $product = $price->product;

        if($request->client_id){
            $client = Client::findOrFail($request->client_id);
        }
        else{
            $client = Client::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone']
            ]);
        }

        $card = Card::updateOrCreate(
            ['barcode' => $validated['barcode']],
            [
                'status' => CardStatus::ACTIVE,
                'created_by_id' => Auth::id()
            ]
        );

        $endDate = match ($product->type) {
            ProductType::MONTHLY => Carbon::now()->addMonth(),
            ProductType::YEARLY => Carbon::now()->addYear(),
            ProductType::ONETIME => Carbon::now()->addDays($product->duration_days ?? 0)
        };

        $subscription = Subscription::create([
            'client_id' => $client->id,
            'card_id' => $card->id,
            'price_id' => $price->id,
            'created_by_id' => Auth::user()->id,
            'end_date' => $endDate,
            'count_usage' => $product->count_usage,
            'status' => SubscriptionStatus::ACTIVE
        ]);

        CardAssignment::create([
            'client_id' => $client->id,
            'card_id' => $card->id,
            'subscription_id' => $subscription->id,
            'assigned_date' => Carbon::now(),
            'created_by_id' => Auth::user()->id
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'price_id' => $price->id,
            'paid_amount' => $price->amount_in_uah
        ]);

        return redirect("/")->with('success', 'Card created!');
    }

    public function prolongation(Request $request, $id){
        $card = Card::where('id', $id)
            ->with(['subscription.price.product'])
            ->firstOrFail();
        
        $subscription = $card->subscription;
        $price = $subscription->price;

        if (!$subscription) {
            return response()->json(['message' => "Картка не прив'язана"], 400);
        }

        $product = $subscription->price->product;

        $end = $subscription->end_date;

        if (!$end || $end->toDateString() < Carbon::today()->toDateString()) {
            $currentEnd = Carbon::today();
        } else {
            $currentEnd = $end->copy();
        }

        switch ($product->type) {
            case ProductType::MONTHLY:
                $newEnd = $currentEnd->copy()->addMonth();
                break;

            case ProductType::YEARLY:
                $newEnd = $currentEnd->copy()->addYear();
                break;

            case ProductType::ONETIME:
                return response()->json([
                    'message' => 'Одноразовий абонемент не можна продовжити'
                ], 400);
        }

        $newCountUsage = $subscription->count_usage;

        if (!$product->infinite) {
            $newCountUsage = $product->count_usage;
        }

        $subscription->update([
            'end_date'     => $newEnd,
            'count_usage'  => $newCountUsage
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'price_id'        => $price->id,
            'paid_amount'     => $price->amount_in_uah,
        ]);

        return response()->json([
            'message' => 'Абонемент продовжено',
        ]);
    }
}