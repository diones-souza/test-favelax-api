<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class PointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'register' => now(),
            'latitude' => rand(-90, 90),
            'longitude' => rand(-180, 180),
            'user_id' => rand(User::first()->id, User::latest()->first()->id),
        ];
    }
}
