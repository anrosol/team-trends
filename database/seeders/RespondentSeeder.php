<?php

namespace Database\Seeders;

use App\Models\Respondent;
use App\Models\Team;
use Illuminate\Database\Seeder;

class RespondentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::all()->each(function ($team) {
            Respondent::factory()
                ->count(5)
                ->create([
                    'team_id' => $team->id,
                ]);
        });
    }
}
