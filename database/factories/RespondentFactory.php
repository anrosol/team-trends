<?php

namespace Database\Factories;

use App\Models\Respondent;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespondentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'passphrase' => Respondent::newPassphrase(),
        ];
    }
}
