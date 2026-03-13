<?php

use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

it('encrypts responses at rest', function () {
    $respondent = Respondent::factory()->create();
    $responses = [3, 5, 2, 4, 1];

    Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'responses' => $responses,
    ]);

    $raw = DB::table('surveys')->first()->responses;

    // The raw database value must not be the plaintext JSON representation
    expect($raw)->not->toBe(json_encode($responses));

    // The raw value must not be JSON-decodable as an array (it is encrypted)
    expect(json_decode($raw, true))->not->toBeArray();
});

it('decrypts responses correctly after retrieval', function () {
    $respondent = Respondent::factory()->create();
    $responses = [3, 5, 2, 4, 1];

    $survey = Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'responses' => $responses,
    ]);

    expect(Survey::find($survey->id)->responses)->toBe($responses);
});

it('decrypts responses as a PHP array, not another type', function () {
    $respondent = Respondent::factory()->create();
    $survey = Survey::factory()->create(['respondent_id' => $respondent->id]);

    expect(Survey::find($survey->id)->responses)->toBeArray();
});

it('produces different ciphertext for identical responses', function () {
    // Encryption must be non-deterministic (random IV) to prevent
    // pattern analysis on the raw database values.
    $respondent = Respondent::factory()->create();
    $responses = [3, 3, 3, 3, 3];

    $surveyA = Survey::factory()->create(['respondent_id' => $respondent->id, 'responses' => $responses]);
    $surveyB = Survey::factory()->create(['respondent_id' => $respondent->id, 'responses' => $responses]);

    $rawA = DB::table('surveys')->where('id', $surveyA->id)->value('responses');
    $rawB = DB::table('surveys')->where('id', $surveyB->id)->value('responses');

    expect($rawA)->not->toBe($rawB);
});

// respondent()

it('belongs to a respondent', function () {
    $respondent = Respondent::factory()->create();
    $survey = Survey::factory()->create(['respondent_id' => $respondent->id]);

    expect($survey->respondent->id)->toBe($respondent->id);
});

// between()

it('returns surveys for all team respondents when passed a Team', function () {
    $team = Team::factory()->create();
    $respondentA = Respondent::factory()->create(['team_id' => $team->id]);
    $respondentB = Respondent::factory()->create(['team_id' => $team->id]);

    $surveyA = Survey::factory()->create(['respondent_id' => $respondentA->id]);
    $surveyB = Survey::factory()->create(['respondent_id' => $respondentB->id]);

    $results = Survey::between($team, now()->subDay(), now()->addDay());

    expect($results->pluck('id'))
        ->toContain($surveyA->id)
        ->toContain($surveyB->id);
});

it('does not return surveys from other teams when passed a Team', function () {
    $team = Team::factory()->create();
    $otherTeam = Team::factory()->create();

    $respondent = Respondent::factory()->create(['team_id' => $team->id]);
    $otherRespondent = Respondent::factory()->create(['team_id' => $otherTeam->id]);

    Survey::factory()->create(['respondent_id' => $respondent->id]);
    $otherSurvey = Survey::factory()->create(['respondent_id' => $otherRespondent->id]);

    $results = Survey::between($team, now()->subDay(), now()->addDay());

    expect($results->pluck('id'))->not->toContain($otherSurvey->id);
});

it("returns only the respondent's surveys when passed a Respondent", function () {
    $team = Team::factory()->create();
    $respondentA = Respondent::factory()->create(['team_id' => $team->id]);
    $respondentB = Respondent::factory()->create(['team_id' => $team->id]);

    $surveyA = Survey::factory()->create(['respondent_id' => $respondentA->id]);
    $surveyB = Survey::factory()->create(['respondent_id' => $respondentB->id]);

    $results = Survey::between($respondentA, now()->subDay(), now()->addDay());

    expect($results->pluck('id'))
        ->toContain($surveyA->id)
        ->not->toContain($surveyB->id);
});

it('excludes surveys before the begin date', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    $old = Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'created_at' => now()->subDays(10),
    ]);

    $results = Survey::between($team, now()->subDays(5), now()->addDay());

    expect($results->pluck('id'))->not->toContain($old->id);
});

it('excludes surveys after the end date', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    $future = Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'created_at' => now()->addDays(10),
    ]);

    $results = Survey::between($team, now()->subDay(), now()->addDays(5));

    expect($results->pluck('id'))->not->toContain($future->id);
});

it('returns results ordered by id ascending', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    $first = Survey::factory()->create(['respondent_id' => $respondent->id]);
    $second = Survey::factory()->create(['respondent_id' => $respondent->id]);
    $third = Survey::factory()->create(['respondent_id' => $respondent->id]);

    $results = Survey::between($team, now()->subDay(), now()->addDay());

    expect($results->pluck('id')->all())->toBe([$first->id, $second->id, $third->id]);
});

it('returns an empty collection when no surveys fall within the date range', function () {
    $team = Team::factory()->create();
    $respondent = Respondent::factory()->create(['team_id' => $team->id]);

    Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'created_at' => now()->subDays(10),
    ]);

    $results = Survey::between($team, now()->subDays(5), now()->subDays(3));

    expect($results)->toBeEmpty();
});

it('returns a collection instance from between()', function () {
    $team = Team::factory()->create();

    $results = Survey::between($team, now()->subDay(), now()->addDay());

    expect($results)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

it('returns all surveys across all teams when passed null', function () {
    // When null is passed, between() sets $team = null and $respondent = null,
    // so neither filter condition is applied — only the date range is enforced.
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $respondentA = Respondent::factory()->create(['team_id' => $teamA->id]);
    $respondentB = Respondent::factory()->create(['team_id' => $teamB->id]);

    $surveyA = Survey::factory()->create(['respondent_id' => $respondentA->id]);
    $surveyB = Survey::factory()->create(['respondent_id' => $respondentB->id]);

    $results = Survey::between(null, now()->subDay(), now()->addDay());

    expect($results->pluck('id'))
        ->toContain($surveyA->id)
        ->toContain($surveyB->id);
});

// IDs

it('uses ULID format for primary keys', function () {
    $survey = Survey::factory()->create();

    // ULIDs are 26-character uppercase alphanumeric strings
    expect($survey->id)
        ->toBeString()
        ->toHaveLength(26)
        ->toMatch('/^[0-9a-z]+$/');
});
