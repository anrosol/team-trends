<?php

use App\Enums\PeriodEnum;
use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;
use App\Support\Charts\Chart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Helpers

function makeChart(Team|Respondent $model, $begin_at = null, $end_at = null): Chart
{
    return new Chart($model, $begin_at, $end_at, PeriodEnum::WEEK, question: 0, weighted: false);
}

// Constructor — begin_at / end_at defaults

it('defaults begin_at to DEFAULT_BEGIN_DATE when null is passed', function () {
    $team = Team::factory()->create();
    $chart = makeChart($team, null);

    expect($chart->getBeginAt()->eq(Carbon::parse(Chart::DEFAULT_BEGIN_DATE)))->toBeTrue();
});

it('defaults end_at to now when null is passed', function () {
    $team = Team::factory()->create();
    $chart = makeChart($team, null, null);

    expect($chart->getEndAt()->diffInSeconds(now()))->toBeLessThan(5);
});

it('parses begin_at from a string', function () {
    $team = Team::factory()->create();
    $chart = makeChart($team, '2026-01-01 00:00:00');

    expect($chart->getBeginAt()->format('Y-m-d'))->toBe('2026-01-01');
});

it('parses end_at from a string', function () {
    $team = Team::factory()->create();
    $chart = makeChart($team, null, '2026-06-01 00:00:00');

    expect($chart->getEndAt()->format('Y-m-d'))->toBe('2026-06-01');
});

it('accepts Carbon instances for begin_at and end_at directly', function () {
    $team = Team::factory()->create();
    $begin = Carbon::parse('2026-01-01');
    $end = Carbon::parse('2026-12-31');

    $chart = makeChart($team, $begin, $end);

    expect($chart->getBeginAt()->eq($begin))->toBeTrue()
        ->and($chart->getEndAt()->eq($end))->toBeTrue();
});

// Constructor — model type

it('resolves the team when constructed with a Team model', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    $chart = makeChart($team);

    expect($chart->respondents()->pluck('id'))->toContain($respondent->id);
});

it('resolves the team via the respondent when constructed with a Respondent model', function () {
    $team = Team::factory()->create();
    $respondentA = Respondent::factory()->create(['team_id' => $team->id]);
    $respondentB = Respondent::factory()->create(['team_id' => $team->id]);

    $chart = makeChart($respondentA);

    // respondents() reflects the full team, not just the single respondent passed
    expect($chart->respondents()->pluck('id'))
        ->toContain($respondentA->id)
        ->toContain($respondentB->id);
});

// getBeginAt() / getEndAt()

it('getBeginAt() returns a Carbon instance', function () {
    $team = Team::factory()->create();

    expect(makeChart($team, '2026-01-01')->getBeginAt())->toBeInstanceOf(Carbon::class);
});

it('getEndAt() returns a Carbon instance', function () {
    $team = Team::factory()->create();

    expect(makeChart($team, null, '2026-12-31')->getEndAt())->toBeInstanceOf(Carbon::class);
});

// respondents()

it('returns all respondents belonging to the team', function () {
    $team = Team::factory()->create();
    Respondent::factory()->count(3)->create(['team_id' => $team->id]);

    $chart = makeChart($team);

    expect($chart->respondents())->toHaveCount(3);
});

// surveys()

it('returns surveys within the date range', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    $survey = Survey::factory()->create(['respondent_id' => $respondent->id]);

    $chart = makeChart($team, now()->subDay(), now()->addDay());

    expect($chart->surveys()->pluck('id'))->toContain($survey->id);
});

it('does not return surveys outside the date range', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    $old = Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'created_at' => now()->subDays(10),
    ]);

    $chart = makeChart($team, now()->subDays(5), now()->addDay());

    expect($chart->surveys()->pluck('id'))->not->toContain($old->id);
});

it('caches surveys and does not query the database on subsequent calls', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);
    Survey::factory()->create(['respondent_id' => $respondent->id]);

    $chart = makeChart($team, now()->subDay(), now()->addDay());

    $chart->surveys(); // warm the cache

    DB::enableQueryLog();
    $chart->surveys(); // should use cached result
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    expect($queries)->toBeEmpty();
});

// DEFAULT_BEGIN_DATE

it('DEFAULT_BEGIN_DATE is a parseable date string', function () {
    expect(Carbon::parse(Chart::DEFAULT_BEGIN_DATE))->toBeInstanceOf(Carbon::class);
});
