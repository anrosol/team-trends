<?php

use App\Enums\PeriodEnum;
use App\Models\Team;
use App\Models\User;
use App\Support\Charts\Chart;
use Carbon\Carbon;
use Database\Seeders\TestSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->team = Team::factory()->create([
        'token' => str()->ulid(),
        'user_id' => $user->id,
    ]);

    $this->seed(TestSeeder::class);

    $this->yaml = TestSeeder::getYamlData();

    $this->begin_at = Carbon::parse(Chart::DEFAULT_BEGIN_DATE);

    $this->end_at = now();

    $this->chart = Team::first()->chart(
        $this->begin_at,
        $this->end_at,
        PeriodEnum::WEEK,
        question: 0,
        weighted: false
    );
});

it('renders successfully', function () {
    Livewire::test('pages::teams.respondent')
        ->assertStatus(200);
});

test('begin_at', function () {
    expect($this->chart->getBeginAt()->eq($this->begin_at))->toBeTrue();
});

test('end_at', function () {
    expect($this->chart->getEndAt()->eq($this->end_at))->toBeTrue();
});

test('chart', function () {
    $chart = collect($this->chart->toArray())
        ->toJson();

    $yamlChart = collect($this->yaml['expect']['chart'])
        ->toJson();

    expect($chart)->toBe($yamlChart);
});

test('weighted_chart', function () {
    $weightedChart = collect(
        Team::first()->chart(
            $this->begin_at,
            $this->end_at,
            PeriodEnum::WEEK,
            question: 0,
            weighted: true,
        )
            ->toArray()
    )
        ->toJson();

    $yamlWeightedChart = collect($this->yaml['expect']['weighted_chart'])
        ->toJson();

    expect($weightedChart)->toBe($yamlWeightedChart);
});
