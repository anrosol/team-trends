<?php

namespace Database\Seeders;

use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class TestSeeder extends Seeder
{
    const YAML_DATA = 'database/seeders/TestSeederData.yaml';

    private array $yaml;

    private User $user;

    private Team $team;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->yaml = self::getYamlData();

        $this->user = $this->seedUser();

        $this->team = $this->seedTeam();

        $this->seedRespondents();

        $this->seedSurveys();
    }

    private function seedUser(): User
    {
        if (in_array(config('app.env'), ['local', 'testing'])) {
            User::query()->delete();
        }

        return User::factory()->create();
    }

    private function seedTeam(): Team
    {
        return Team::factory()->create([
            'user_id' => $this->user->id,
            'max_respondents' => count($this->yaml['respondents']),
            'active' => true,
        ]);
    }

    private function seedRespondents(): void
    {
        foreach ($this->yaml['respondents'] as $respondent) {
            Respondent::factory()->create([
                'team_id' => $this->team->id,
                'passphrase' => $respondent['passphrase'],
            ]);
        }
    }

    private function seedSurveys(): void
    {
        foreach ($this->yaml['surveys'] as $survey) {
            Survey::factory()->create([
                'respondent_id' => Respondent::findByPassphrase($survey['passphrase']),
                'responses' => $survey['responses'],
                'created_at' => Carbon::parse($survey['date']),
            ]);
        }
    }

    public static function getYamlData(): array
    {
        return Yaml::parseFile(base_path(self::YAML_DATA));
    }
}
