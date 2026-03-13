<?php

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;

it('allows the owner to view their own team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect((new TeamPolicy)->view($user, $team))->toBeTrue();
});

it('prevents a user from viewing another user\'s team', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->id]);

    expect((new TeamPolicy)->view($other, $team))->toBeFalse();
});

it('allows the owner to update their own team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect((new TeamPolicy)->update($user, $team))->toBeTrue();
});

it('prevents a user from updating another user\'s team', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->id]);

    expect((new TeamPolicy)->update($other, $team))->toBeFalse();
});

it('allows the owner to delete their own team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect((new TeamPolicy)->delete($user, $team))->toBeTrue();
});

it('prevents a user from deleting another user\'s team', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->id]);

    expect((new TeamPolicy)->delete($other, $team))->toBeFalse();
});

it('allows any authenticated user to create a team', function () {
    $user = User::factory()->create();

    expect((new TeamPolicy)->create($user))->toBeTrue();
});

it('allows any authenticated user to list teams', function () {
    $user = User::factory()->create();

    expect((new TeamPolicy)->viewAny($user))->toBeTrue();
});

it('allows the owner to restore their own team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect((new TeamPolicy)->restore($user, $team))->toBeTrue();
});

it('prevents a user from restoring another user\'s team', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->id]);

    expect((new TeamPolicy)->restore($other, $team))->toBeFalse();
});

it('allows the owner to force delete their own team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect((new TeamPolicy)->forceDelete($user, $team))->toBeTrue();
});

it('prevents a user from force deleting another user\'s team', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->id]);

    expect((new TeamPolicy)->forceDelete($other, $team))->toBeFalse();
});
