<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [

            'name' => ucfirst($name),

            'slug' => Str::slug($name),

            'description' => fake()->sentence(),

            'price' => fake()->randomFloat(2, 100, 5000),

            'stock' => fake()->numberBetween(1, 100),

            'quantity' => fake()->numberBetween(1, 20),

            'discount' => fake()->numberBetween(0, 50),

        ];
    }
}