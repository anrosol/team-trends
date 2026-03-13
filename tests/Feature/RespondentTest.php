<?php

use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;

it('stores the passphrase as a hash, not as plaintext', function () {
    $respondent = Respondent::factory()->create(['passphrase' => 'correct-horse-battery-staple']);

    expect($respondent->getRawOriginal('passphrase'))
        ->not->toBe('correct-horse-battery-staple')
        ->toBe(Respondent::hashPassphrase('correct-horse-battery-staple'));
});

it('finds a respondent by their plaintext passphrase', function () {
    $respondent = Respondent::factory()->create(['passphrase' => 'correct-horse-battery-staple']);

    $found = Respondent::findByPassphrase('correct-horse-battery-staple');

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($respondent->id);
});

it('returns null when no respondent matches the passphrase', function () {
    Respondent::factory()->create(['passphrase' => 'correct-horse-battery-staple']);

    $found = Respondent::findByPassphrase('wrong-passphrase');

    expect($found)->toBeNull();
});

it('cannot retrieve a respondent from a different team using a valid passphrase', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    Respondent::factory()->create(['team_id' => $teamA->id, 'passphrase' => 'passphrase-for-team-a']);
    $respondentB = Respondent::factory()->create(['team_id' => $teamB->id, 'passphrase' => 'passphrase-for-team-b']);

    $found = Respondent::findByPassphrase('passphrase-for-team-b');

    expect($found->id)->toBe($respondentB->id)
        ->and($found->team_id)->toBe($teamB->id)
        ->and($found->team_id)->not->toBe($teamA->id);
});

it('reports no survey submitted when the respondent has no surveys this week', function () {
    $respondent = Respondent::factory()->create();

    expect($respondent->surveySubmitted())->toBeFalse();
});

it('reports survey submitted when the respondent has a survey this week', function () {
    $respondent = Respondent::factory()->create();
    Survey::factory()->create(['respondent_id' => $respondent->id]);

    expect($respondent->surveySubmitted())->toBeTrue();
});

it('does not count surveys submitted in a previous week', function () {
    $respondent = Respondent::factory()->create();
    Survey::factory()->create([
        'respondent_id' => $respondent->id,
        'created_at' => now()->subWeek(),
    ]);

    expect($respondent->surveySubmitted())->toBeFalse();
});
