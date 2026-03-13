<?php

use App\Models\Respondent;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

// Relations

it('belongs to a user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect($team->user->id)->toBe($user->id);
});

it('has many respondents', function () {
    $team = Team::factory()->create(['max_respondents' => 3]);
    Respondent::factory()->count(3)->create(['team_id' => $team->id]);

    expect($team->respondents)->toHaveCount(3);
});

// Casts

it('casts active to a boolean', function () {
    $team = Team::factory()->create(['active' => 1]);

    expect($team->active)->toBeTrue()->toBeBool();
});

it('casts questions to an array', function () {
    $team = Team::factory()->create();

    expect($team->questions)->toBeArray();
});

// complete()

it('reports complete when respondent count equals max_respondents', function () {
    $team = Team::factory()->create(['max_respondents' => 2]);
    Respondent::factory()->count(2)->create(['team_id' => $team->id]);

    expect($team->complete())->toBeTrue();
});

it('reports not complete when respondent count is less than max_respondents', function () {
    $team = Team::factory()->create(['max_respondents' => 3]);
    Respondent::factory()->count(2)->create(['team_id' => $team->id]);

    expect($team->complete())->toBeFalse();
});

it('reports not complete when team has no respondents', function () {
    $team = Team::factory()->create(['max_respondents' => 2]);

    expect($team->complete())->toBeFalse();
});

// available()

it('returns zero available when the team is complete', function () {
    $team = Team::factory()->create(['max_respondents' => 2]);
    Respondent::factory()->count(2)->create(['team_id' => $team->id]);

    expect($team->available())->toBe(0);
});

it('returns the number of unfilled respondent slots', function () {
    $team = Team::factory()->create(['max_respondents' => 5]);
    Respondent::factory()->count(2)->create(['team_id' => $team->id]);

    expect($team->available())->toBe(3);
});

it('returns max_respondents as available when team has no respondents', function () {
    $team = Team::factory()->create(['max_respondents' => 4]);

    expect($team->available())->toBe(4);
});

// nextSurveyDate()

it('returns 7 days from now when the team was just created', function () {
    $now = Carbon::parse('2026-03-01 09:00:00');
    $team = Team::factory()->create(['created_at' => $now->copy()]);

    $next = $team->nextSurveyDate($now->copy());

    expect($next->format('Y-m-d'))->toBe('2026-03-08');
});

it('returns the correct next survey date mid-cycle', function () {
    $created = Carbon::parse('2026-02-26 09:00:00'); // Thursday
    $now = Carbon::parse('2026-03-01 09:00:00');     // 3 days into cycle
    $team = Team::factory()->create(['created_at' => $created]);

    // 3 days since last cycle, 4 days remaining
    $next = $team->nextSurveyDate($now->copy());

    expect($next->format('Y-m-d'))->toBe('2026-03-05');
});

it('resets to 7 days when exactly on a cycle boundary', function () {
    $created = Carbon::parse('2026-02-22 09:00:00');
    $now = Carbon::parse('2026-03-01 09:00:00'); // exactly 7 days later
    $team = Team::factory()->create(['created_at' => $created]);

    $next = $team->nextSurveyDate($now->copy());

    expect($next->format('Y-m-d'))->toBe('2026-03-08');
});

it('returns correctly after multiple full cycles', function () {
    $created = Carbon::parse('2026-01-01 09:00:00');
    $now = Carbon::parse('2026-03-01 09:00:00'); // 59 days = 8 full weeks + 3 days
    $team = Team::factory()->create(['created_at' => $created]);

    $next = $team->nextSurveyDate($now->copy());

    // 3 days into cycle, 4 days remaining
    expect($next->format('Y-m-d'))->toBe('2026-03-05');
});

// nextSurveyBeginDate() and nextSurveyEndDate()

it('begin date is 7 days before the next survey date', function () {
    $now = Carbon::parse('2026-03-01 09:00:00');
    $team = Team::factory()->create(['created_at' => $now->copy()]);

    $end = $team->nextSurveyEndDate($now->copy());
    $begin = $team->nextSurveyBeginDate($now->copy());

    expect($begin->format('Y-m-d'))->toBe($end->subDays(7)->format('Y-m-d'));
});

it('end date equals the next survey date', function () {
    $now = Carbon::parse('2026-03-01 09:00:00');
    $team = Team::factory()->create(['created_at' => $now->copy()]);

    expect($team->nextSurveyEndDate($now->copy())->format('Y-m-d'))
        ->toBe($team->nextSurveyDate($now->copy())->format('Y-m-d'));
});
