<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
{
    $search = request('search');

    $perPage = request(
        'per_page',
        8
    );

    $minPrice = request(
        'min_price'
    );

    $maxPrice = request(
        'max_price'
    );

    $sort = request(
        'sort',
        'latest'
    );

    $categories = request(
        'categories'
    );

    $products = Product::with([
            'categories:id,name,slug'
        ])

        // search
        ->when($search, function (
            $query
        ) use ($search) {

            $query->where(
                function ($q) use (
                    $search
                ) {

                    $q->where(
                        'name',
                        'LIKE',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'description',
                        'LIKE',
                        "%{$search}%"
                    );
                });
        })

        // categories

        ->when($categories, function (
    $query
) use ($categories) {

    $slugs = explode(
        ',',
        (string) $categories
    );

    $query->whereHas(
        'categories',
        function ($q) use (
            $slugs
        ) {

            $q->whereIn(
                'slug',
                $slugs
            );
        }
    );
})

        // min price
        ->when($minPrice, function (
            $query
        ) use ($minPrice) {

            $query->where(
                'price',
                '>=',
                $minPrice
            );
        })

        // max price
        ->when($maxPrice, function (
            $query
        ) use ($maxPrice) {

            $query->where(
                'price',
                '<=',
                $maxPrice
            );
        })

        // latest
        ->when($sort === 'latest',
            function ($query) {

            $query->latest();
        })

        // oldest
        ->when($sort === 'oldest',
            function ($query) {

            $query->oldest();
        })

        ->paginate($perPage);

    return response()->json([

        'message' => 'Product List',

        'products' =>
            ProductResource::collection(
                $products->items()
            ),

        'pagination' => [

            'current_page' =>
                $products->currentPage(),

            'last_page' =>
                $products->lastPage(),

            'per_page' =>
                $products->perPage(),

            'total' =>
                $products->total(),
        ]
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
            'discount' => $validated['discount'] ?? 0,
        ]);

        $product->categories()->attach($validated['categories']);

        $product->load('categories:id,name,slug');

        return response()->json([
            'message' => 'Product Created Successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with([
            'categories:id,name,slug'
        ])->findOrFail($id);

        return response()->json([
            'message' => 'Product Found',
            'data' => new ProductResource($product)
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
            'discount' => $validated['discount'] ?? 0,
        ]);

        $product->categories()->sync($validated['categories']);

        $product->load('categories:id,name,slug');

        return response()->json([
            'message' => 'Product Updated Successfully',
            'data' => new ProductResource($product)
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
            'message' => 'Product Deleted Successfully'
        ]);
    }
}