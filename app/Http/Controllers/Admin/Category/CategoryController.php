<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('products')->get();

        return response()->json([
            'message' => 'Category List',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest  $request)
{
    $category = Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
    ]);

    return response()->json([
        'message' => 'Category Created Successfully',
        'data' => $category
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('products')->findOrFail($id);

        return response()->json([
            'message' => 'Category Found',
            'data' => $category
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
        'data' => $category
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
            'message' => 'Category Deleted Successfully',
            'data' => $category
        ]);
    }
}