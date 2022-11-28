<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->numberBetween(1500, 6000),
            'bedrooms' => fake()->numberBetween(1, 9),
            'bathrooms' => fake()->numberBetween(1, 9),
            'stories' => fake()->numberBetween(1, 9),
            'garages' => fake()->numberBetween(1, 9),
        ];
    }
}
