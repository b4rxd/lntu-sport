<?php

namespace App\Http\Controllers\Card;
use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function info(Request $request){
        return view('card.info');
    }

   public function indexInfo(Request $request, $barcodeValue) {
        $card = Card::where('barcode', $barcodeValue)->with([
            'cardAssignments' => function ($query) {
                $query->whereNull('returned_date')
                    ->with('client', 'subscription.price.product.locations');
            },'subscription.price.product.locations'
        ])->first();

        if (!$card) {
            return response()->json(['error' => 'Карту не знайдено'], 404);
        }

        $activeAssignment = $card->cardAssignments->first();
        $client = $activeAssignment?->client;
        $subscription = $activeAssignment?->subscription ?? $card->subscription;
        $price = $subscription?->price;
        $product = $price?->product;
        $locations = $product?->locations;

        $lastPayment = $subscription?->subscriptionsPayments()->latest('created_at')->first();

        return response()->json([
            'id' => $card->id,
            'barcode' => $card->barcode,
            'status' => $card->status,
            'created_at' => $card->created_at,
            'client' => $client ? [
                'id' => $client->id,
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'phone' => $client->phone,
                'assigned_date' => $activeAssignment->assigned_date,
            ] : null,
            'subscription' => $subscription ? [
                'id' => $subscription->id, 
                'end_date' => $subscription->end_date,
                'count_usage' => $subscription->count_usage,
                'product' => $product ? [
                    'id' => $product->id,
                    'title' => $product->title,
                    'infinite' => $product->infinite
                ] : null,
                'price' => $price ? [
                    'id' => $price->id,
                    'title' => $price->title,
                    'amount_in_uah' => $price->amount_in_uah,
                ] : null,
                'last_payment' => $lastPayment ? [
                    'id' => $lastPayment->id,
                    'paid_amount' => $lastPayment->paid_amount,
                    'paid_at' => $lastPayment->created_at,
                ] : null,
            ] : null,
            'locations' => $locations ? $locations->map(fn($loc) => [
                'id' => $loc->id,
                'title' => $loc->title,
                'description' => $loc->description
            ]) : null,
        ]);
    }

    public function search(Request $request, string $barcode)
    {
        if (!$request->expectsJson()) {
            abort(404);
        }

        $card = Card::where('barcode', $barcode)->with([
            'cardAssignments' => function ($query) {
                $query->whereNull('returned_date')
                    ->with('subscription.price.product.locations');
            },
            'subscription.price.product.locations'
        ])->first();

        if (!$card) {
            return response()->json(['error' => 'Карту не знайдено'], 404);
        }

        $assignment = $card->cardAssignments->first();
        $subscription = $assignment?->subscription ?? $card->subscription;
        $product = $subscription?->price?->product;
        $locations = $product?->locations;

        return response()->json([
            'barcode' => $card->barcode,
            'status' => $card->status,
            'created_at' => $card->created_at,

            'subscription' => $subscription ? [
                'end_date' => $subscription->end_date,
                'product' => $product ? [
                    'title' => $product->title,
                ] : null,
            ] : null,

            'locations' => $locations
                ? $locations->map(fn ($loc) => [
                    'title' => $loc->title,
                ])
                : [],
        ]);
    }

    public function returnCard(Request $request, $id){
        $card = Card::where('id', $id)->with([
            'cardAssignments' => function ($query) {
                $query->whereNull('returned_date')->limit(1);
            },'subscription'
        ])->first();

        $assignment = $card->cardAssignments->first();

        if ($assignment) {
            $assignment->update([
                'returned_date' => now()
            ]);
        }

        if ($card->subscription) {
            $card->subscription->update([
                'card_id' => null
            ]);
        }

        return response()->json([
            'message' => 'Card returned successfully'
        ]);
    }
}