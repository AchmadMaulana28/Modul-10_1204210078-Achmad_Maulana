<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        Position::factory()->count(5)->create();

        return [
            'code' => fake()->stateAbbr(),
            'name' => fake()->jobTitle(),
            'description' => fake()->sentence(),
        ];
    }
}
