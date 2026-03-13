<?php

use App\Models\Respondent;
use App\Models\User;
use App\Policies\RespondentPolicy;

// RespondentPolicy locks down all access to respondent records.
// Administrators must not be able to view, create, update, or delete respondents
// through standard authorization — respondent data is managed only through
// controlled team workflows to protect respondent anonymity.

it('prevents any user from listing respondents', function () {
    $user = User::factory()->create();

    expect((new RespondentPolicy)->viewAny($user))->toBeFalse();
});

it('prevents any user from viewing a specific respondent', function () {
    $user = User::factory()->create();
    $respondent = Respondent::factory()->create();

    expect((new RespondentPolicy)->view($user, $respondent))->toBeFalse();
});

it('prevents any user from creating a respondent directly', function () {
    $user = User::factory()->create();

    expect((new RespondentPolicy)->create($user))->toBeFalse();
});

it('prevents any user from updating a respondent', function () {
    $user = User::factory()->create();
    $respondent = Respondent::factory()->create();

    expect((new RespondentPolicy)->update($user, $respondent))->toBeFalse();
});

it('prevents any user from deleting a respondent', function () {
    $user = User::factory()->create();
    $respondent = Respondent::factory()->create();

    expect((new RespondentPolicy)->delete($user, $respondent))->toBeFalse();
});

it('prevents any user from restoring a respondent', function () {
    $user = User::factory()->create();
    $respondent = Respondent::factory()->create();

    expect((new RespondentPolicy)->restore($user, $respondent))->toBeFalse();
});

it('prevents any user from force deleting a respondent', function () {
    $user = User::factory()->create();
    $respondent = Respondent::factory()->create();

    expect((new RespondentPolicy)->forceDelete($user, $respondent))->toBeFalse();
});
