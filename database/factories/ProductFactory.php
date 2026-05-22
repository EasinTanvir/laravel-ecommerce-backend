<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 5000),
            'stock' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(1, 20),
        ];
    }
}