<?php

namespace App\Http\Controllers\Admin\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select('id', 'name', 'slug')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Category List',
            'data' => CategoryResource::collection($categories)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'message' => 'Category Created Successfully',
            'data' => new CategoryResource($category)
        ], 201);
    }

    /**
     * Display the specified resource.
     */

    public function show(Category $category)
{
    $perPage = request('per_page', 8);

    $minPrice = request('min_price');

    $maxPrice = request('max_price');

    $sort = request('sort', 'latest');

    $products = $category->products()

        ->when($minPrice, function ($query) use ($minPrice) {

            $query->where('price', '>=', $minPrice);

        })

        ->when($maxPrice, function ($query) use ($maxPrice) {

            $query->where('price', '<=', $maxPrice);

        })

        ->when($sort === 'latest', function ($query) {

            $query->latest();

        })

        ->when($sort === 'oldest', function ($query) {

            $query->oldest();

        })

        ->paginate($perPage);

    return response()->json([

        'message' => 'Category Found',

        'category' => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ],

        'products' => ProductResource::collection(
            $products->items()
        ),

        'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ]
    ]);
}






    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'message' => 'Category Updated Successfully',
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json([
            'message' => 'Category Deleted Successfully'
        ]);
    }
}