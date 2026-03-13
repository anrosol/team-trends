<?php

use App\Models\Survey;
use App\Models\User;
use App\Policies\SurveyPolicy;

it('allows unauthenticated respondents to create a survey', function () {
    expect((new SurveyPolicy)->create())->toBeTrue();
});

it('allows authenticated users to create a survey', function () {
    $user = User::factory()->create();

    expect((new SurveyPolicy)->create($user))->toBeTrue();
});

it('prevents any user from listing all surveys', function () {
    $user = User::factory()->create();

    expect((new SurveyPolicy)->viewAny($user))->toBeFalse();
});

it('prevents any user from viewing a specific survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    expect((new SurveyPolicy)->view($user, $survey))->toBeFalse();
});

it('prevents any user from updating a survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    expect((new SurveyPolicy)->update($user, $survey))->toBeFalse();
});

it('prevents any user from deleting a survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    expect((new SurveyPolicy)->delete($user, $survey))->toBeFalse();
});

it('prevents any user from restoring a survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    expect((new SurveyPolicy)->restore($user, $survey))->toBeFalse();
});

it('prevents any user from force deleting a survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    expect((new SurveyPolicy)->forceDelete($user, $survey))->toBeFalse();
});
