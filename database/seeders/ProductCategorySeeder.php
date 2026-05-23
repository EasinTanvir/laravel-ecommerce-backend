<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            'Electronics' => [
                'iPhone 15 Pro',
                'Samsung Galaxy S24',
                'Google Pixel 8',
                'MacBook Air M3',
                'Dell XPS 15',
                'HP Pavilion',
                'Asus ROG Laptop',
                'Apple Watch Ultra',
                'Samsung Smart TV',
                'Sony Headphones',
                'AirPods Pro',
                'JBL Speaker',
                'iPad Pro',
                'Gaming Monitor',
                'Mechanical Keyboard',
                'Logitech Mouse',
            ],

            'Fashion' => [
                'Nike Air Max',
                'Adidas Hoodie',
                'Leather Jacket',
                'Slim Fit Jeans',
                'Casual T-Shirt',
                'Formal Shirt',
                'Running Shoes',
                'Sports Cap',
                'Sunglasses',
                'Winter Sweater',
                'Polo Shirt',
                'Cargo Pants',
                'Sneakers',
                'Backpack',
                'Wrist Watch',
                'Denim Jacket',
            ],

            'Gaming' => [
                'PlayStation 5',
                'Xbox Series X',
                'Gaming Chair',
                'Gaming Desk',
                'RGB Mouse Pad',
                'Gaming Keyboard',
                'Gaming Headset',
                'RTX 4090 GPU',
                'Gaming PC',
                'Nintendo Switch',
                'VR Headset',
                'Gaming Mouse',
                'Streaming Mic',
                '4K Gaming Monitor',
                'Controller',
                'Webcam',
            ],

        ];

        foreach ($data as $categoryName => $products) {

            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($products as $productName) {

                $product = Product::create([

                    'name' => $productName,

                    'slug' => Str::slug($productName),

                    'description' => fake()->sentence(),

                    'price' => fake()->numberBetween(100, 5000),

                    'stock' => fake()->numberBetween(1, 100),

                    'quantity' => fake()->numberBetween(1, 20),

                    'discount' => fake()->numberBetween(0, 50),

                ]);

                $category->products()->attach($product->id);
            }
        }
    }
}