<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;

class ProductController extends Controller
{
    public function index(){
        $products = Product::where('enabled', true)
            ->with(['prices' => function ($q) {
                $q->where('enabled', true);
            }])
            ->get();

        return view('product.product-list', compact('products'));
    }

    public function create(){
        $locations = Location::all();
        return view('product.create-product', compact('locations'));
    }

    public function store(Request $request){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::CREATE_PRODUCT)) {
            abort(403, 'У вас немає доступу для створення продукту');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:one_time,monthly,yearly',
            'description' => 'required|string',
            'infinite' => 'boolean',
            'count_usage' => 'required_without:infinite|integer|min:1|nullable',
            'locations' => 'nullable|array',
            'locations.*' => 'exists:locations,id',
        ]);

        $product = Product::create([
            'title' => $validated['title'],
            'count_usage' => $request->boolean('infinite') ? null : $validated['count_usage'],
            'infinite' => $request->boolean('infinite'),
            'type' => $validated['type'],
            'description' => $validated['description'],
        ]);


        if (!empty($validated['locations'])) {
            $product->locations()->sync($validated['locations']);
        }

        return redirect()->route('products.index')->with('success', 'Success product create!');
    }


    public function destroy(Request $request, $id){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'У вас немає доступу для видалення');
        }
        
        $product = Product::findOrFail($id);
        $product->enabled = false;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Success product destroy!');
    }
}