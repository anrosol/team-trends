<?php

namespace Database\Seeders;

use App\Models\Respondent;
use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Respondent::all()->each(function ($respondent) {
            Survey::factory()
                ->count(5)
                ->create([
                    'respondent_id' => $respondent->id,
                ]);
        });
    }
}
