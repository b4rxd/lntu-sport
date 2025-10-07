<?php

namespace App\Http\Controllers\Price;
use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class PriceController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'amount_in_uah' => 'required|integer|min:1',
            'enabled' => 'sometimes|boolean',
        ]);

        Price::create([
            'id' => Str::uuid(),
            'product_id' => $validated['product_id'],
            'title' => $validated['title'],
            'amount_in_uah' => $validated['amount_in_uah'],
            'enabled' => $validated['enabled'] ?? 0,
        ]);


        return redirect()->route('products.index')->with('success', 'Success price create!');
    }

    public function toggle(Request $request, $id){
        $price = Price::findOrFail($id);
        $price->update([
            'enabled' => !$price->enabled
        ]);

        return redirect()->back()->with('success', 'Success price toggle!');
    }
}