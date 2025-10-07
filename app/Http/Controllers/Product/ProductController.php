<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('prices')->get();
        return view('product.product-list', compact('products'));
    }


    public function create(){
        $locations = Location::all();
        return view('product.create-product', compact('locations'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'count_usage' => 'required|integer|min:1',
            'type' => 'required|in:one_time,monthly,yearly',
            'description' => 'required|string',
            'locations' => 'nullable|array',
            'locations.*' => 'exists:locations,id',
        ]);

        $product = Product::create([
            'id' => Str::uuid(),
            'title' => $validated['title'],
            'duration_days' => $validated['duration_days'],
            'count_usage' => $validated['count_usage'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'enabled' => true,
        ]);

        if (!empty($validated['locations'])) {
            $product->locations()->sync($validated['locations']);
        }

        return redirect()->route('products.index')->with('success', 'Success product create!');
    }

    public function destroy(Request $request, $id){
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Success product destroy!');
    }
}