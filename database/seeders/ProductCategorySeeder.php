<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 categories
        $categories = Category::factory(10)->create();

        foreach ($categories as $category) {

            // Create 16 products for this category
            $products = Product::factory(16)->create();

            // Attach products to category
            $category->products()->attach(
                $products->pluck('id')
            );
        }
    }
}