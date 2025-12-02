<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(){
        return view('client.client');
    }

    public function info($id)
{
    try {
        $client = Client::with([
            'cardAssignments.card',
            'cardAssignments.subscription.price.product.locations',
            'cardAssignments.subscription.subscriptionsPayments'
        ])->findOrFail($id);

        return response()->json($client);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




    public function search(Request $request){
        $query = $request->get('q');
        $clients = Client::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'phone']);

        return response()->json($clients);
    }
}