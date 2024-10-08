<?php

namespace Database\Factories;

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
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'middle' => $this->faker->randomLetter(),
            'suffix' => $this->faker->optional()->suffix(),
            'age' => $this->faker->numberBetween(18, 65),
            'address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'role' => $this->faker->randomElement(['manager', 'cashier']),
            'username' => $this->faker->unique()->userName(),
            'password' => 'password123',
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_at' => now(),
        ];
    }
}
