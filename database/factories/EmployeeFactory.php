<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Employee::factory()->count(10)->create();

        // return [
        //     'firstname' => faker()->firstName(),
        //     'lastname' => faker()->lastName(),
        //     'email' => faker()->email(),
        //     'age' => faker()->numberBetween(25, 50),
        //     'position_id' => Position::factory()
        // ];

        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'age' => $this->faker->numberBetween(25, 50),
            'position_id' => $this->faker->numberBetween(1, 3)
        ];
    }
}
