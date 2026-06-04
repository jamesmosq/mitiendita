<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status'  => fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'total'   => fake()->numberBetween(19900, 999900),
            'notes'   => fake()->optional()->sentence(),
        ];
    }

    public function pending(): static   { return $this->state(['status' => 'pending']); }
    public function completed(): static { return $this->state(['status' => 'completed']); }
    public function cancelled(): static { return $this->state(['status' => 'cancelled']); }
}