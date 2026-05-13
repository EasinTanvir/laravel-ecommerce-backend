<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('categories')->get();
        return response()->json([
            'message'=>'Product List',
            'data'=>$products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
{
    $validated = $request->validated();

    $product = Product::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'price' => $validated['price'],
        'stock' => $validated['stock'],
        'quantity' => $validated['quantity'] ?? 0,
    ]);

    $product->categories()->attach($validated['categories']);

    return response()->json([
        'message' => 'Product Created Successfully',
        'data' => $product->load('categories')
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $product = Product::with('categories')->findOrFail($id);

        return response()->json([
            'message'=>'Product Found',
            'data'=>$product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
{
    $product = Product::findOrFail($id);

    $validated = $request->validated();

    $product->update([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'price' => $validated['price'],
        'stock' => $validated['stock'],
        'quantity' => $validated['quantity'] ?? 0,
    ]);

    $product->categories()->sync($validated['categories']);

    return response()->json([
        'message' => 'Product Updated Successfully',
        'data' => $product->load('categories')
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message'=>'Product Deleted Successfully',
            'data'=>$product
        ]);
    }
}