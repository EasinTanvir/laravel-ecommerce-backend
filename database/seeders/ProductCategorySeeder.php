<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = Category::factory(10)->create();

        // Create products
        $products = Product::factory(30)->create();

        // Attach random categories to products
        foreach ($products as $product) {
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')
            );
        }
    }
}