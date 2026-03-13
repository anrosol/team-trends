<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'token' => (string) Str::ulid(),
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'max_respondents' => fake()->numberBetween(2, 10),
            'active' => fake()->boolean(),
            'likert_scale' => config('appex.default_likert_scale'),
            'questions' => config('questions'),
        ];
    }
}
