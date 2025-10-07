<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Product;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SaleReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from') 
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : now()->startOfMonth();

        $dateTill = $request->input('date_till') 
            ? Carbon::parse($request->input('date_till'))->endOfDay()
            : now()->endOfDay();

        $productId  = $request->input('product_id');
        $userId     = $request->input('user_id');
        $locationId = $request->input('location_id');

        $query = Card::query()
            ->with(['price.product', 'createdBy'])
            ->whereBetween('created_at', [$dateFrom, $dateTill]);

        if ($productId) {
            $query->whereHas('price', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        if ($userId) {
            $query->where('created_by_id', $userId);
        }

        if ($locationId) {
            $query->whereHas('price.product.locations', function ($q) use ($locationId) {
                $q->where('locations.id', $locationId);
            });
        }

        $cards = $query->get();
        
        $totalSold = $cards->count();
        $totalAmount = $cards->sum(fn($card) => $card->price?->amount_in_uah ?? 0);

        return view('reports.sale.index', [
            'cards' => $cards,
            'totalSold' => $totalSold,
            'totalAmount' => $totalAmount,
            'filters' => [
                'date_from' => $dateFrom->format('Y-m-d'),
                'date_till' => $dateTill->format('Y-m-d'),
                'product_id' => $productId,
                'user_id' => $userId,
                'location_id' => $locationId,
            ],
            'products' => Product::where('enabled', true)->get(),
            'users' => User::where('enabled', true)->get(),
            'locations' => Location::where('enabled', true)->get(),
        ]);
    }
}
