<?php

namespace App\Http\Controllers\Price;
use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function store(Request $request){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::CREATE_PRODUCT)) {
            abort(403, 'У вас немає доступу для створення ціни');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'amount_in_uah' => 'required|integer|min:1',
        ]);

        Price::create([
            'product_id' => $validated['product_id'],
            'title' => $validated['title'],
            'amount_in_uah' => $validated['amount_in_uah'],
        ]);


        return redirect()->route('products.index')->with('success', 'Success price create!');
    }

    public function destroy(Request $request, $id){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'У вас немає доступу для видалення');
        }

        $price = Price::findOrFail($id);
        $price->enabled = false;
        $price->save();

        return redirect()->back()->with('success', 'Success, price deleted!');
    }
}