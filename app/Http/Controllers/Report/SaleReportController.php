<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_till' => $request->get('date_till'),
            'product_id' => $request->get('product_id'),
            'user_id' => $request->get('user_id'),
            'location_id' => $request->get('location_id'),
        ];

        $query = SubscriptionPayment::with([
            'subscription.client',
            'subscription.card',
            'price.product',
            'createdBy'
        ]);

        if ($filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if ($filters['date_till']) {
            $query->whereDate('created_at', '<=', $filters['date_till']);
        }

        if ($filters['product_id']) {
            $query->whereHas('price.product', function ($q) use ($filters) {
                $q->where('id', $filters['product_id']);
            });
        }

        if ($filters['user_id']) {
            $query->where('created_by_id', $filters['user_id']);
        }

        if ($filters['location_id']) {
            $query->whereHas('subscription.cardAssignments.location', function ($q) use ($filters) {
                $q->where('id', $filters['location_id']);
            });
        }

        $payments = $query->get();

        return view('reports.sale.index', [
            'payments' => $payments,
            'filters' => $filters,
            'products' => Product::all(),
            'users' => User::all(),
            'locations' => Location::all(),
            'totalAmount' => $payments->sum('paid_amount'),
            'totalSold' => $payments->count(),
        ]);
    }
}
