<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Ugali', 'Chicken', 'Fish', 'Fries', 'Kebab', 'Bread', 'Uji', 'Milk', 'Bone-Soup', 'Beef',]),
            'price' => fake()->randomFloat(2, 50, 35000),
            'description' => fake()->sentence(10),
            'food_code'=>fake()->regexify('FD[A-Z'),
            'category_id'=>random_int(1, 3),
            'restaurant_id'=>random_int(1, 5),
        ];
    }
}
