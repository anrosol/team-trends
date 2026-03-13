<?php

namespace Database\Factories;

use App\Models\Respondent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'respondent_id' => Respondent::factory(),
            'responses' => collect(config('questions'))
                ->map(fn () => random_int(1, config('appex.default_likert_scale')))
                ->toArray(),
        ];
    }
}
