<?php

use App\Models\User;
use App\Policies\UserPolicy;

// UserPolicy has three distinct access tiers:
//   1. Admins — bypass all checks via before(), full access
//   2. Regular users — self-only access for view/update/delete
//   3. Nobody — create is always denied

// before()

it('grants admins all abilities via the before hook', function () {
    $admin = User::factory()->create(['admin' => true]);
    $other = User::factory()->create();
    $policy = new UserPolicy;

    expect($policy->before($admin, 'view'))->toBeTrue()
        ->and($policy->before($admin, 'update'))->toBeTrue()
        ->and($policy->before($admin, 'delete'))->toBeTrue()
        ->and($policy->before($admin, 'create'))->toBeTrue();
});

it('returns null from before() for non-admins so other checks proceed', function () {
    $user = User::factory()->create(['admin' => false]);

    expect((new UserPolicy)->before($user, 'view'))->toBeNull();
});

// viewAny()

it('allows admins to list all users', function () {
    $admin = User::factory()->create(['admin' => true]);

    expect((new UserPolicy)->viewAny($admin))->toBeTrue();
});

it('prevents non-admins from listing all users', function () {
    $user = User::factory()->create(['admin' => false]);

    expect((new UserPolicy)->viewAny($user))->toBeFalse();
});

// view()

it('allows a user to view their own profile', function () {
    $user = User::factory()->create();

    expect((new UserPolicy)->view($user, $user))->toBeTrue();
});

it('prevents a user from viewing another user\'s profile', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect((new UserPolicy)->view($user, $other))->toBeFalse();
});

// create()

it('prevents anyone from creating a user directly', function () {
    $user = User::factory()->create();

    expect((new UserPolicy)->create($user))->toBeFalse();
});

// update()

it('allows a user to update their own profile', function () {
    $user = User::factory()->create();

    expect((new UserPolicy)->update($user, $user))->toBeTrue();
});

it('prevents a user from updating another user\'s profile', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect((new UserPolicy)->update($user, $other))->toBeFalse();
});

// delete()

it('allows a user to delete their own account', function () {
    $user = User::factory()->create();

    expect((new UserPolicy)->delete($user, $user))->toBeTrue();
});

it('prevents a user from deleting another user\'s account', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect((new UserPolicy)->delete($user, $other))->toBeFalse();
});

// restore()

it('allows a user to restore their own account', function () {
    $user = User::factory()->create();

    expect((new UserPolicy)->restore($user, $user))->toBeTrue();
});

it('prevents a user from restoring another user\'s account', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect((new UserPolicy)->restore($user, $other))->toBeFalse();
});

// forceDelete()

it('allows a user to force delete their own account', function () {
    $user = User::factory()->create();

    expect((new UserPolicy)->forceDelete($user, $user))->toBeTrue();
});

it('prevents a user from force deleting another user\'s account', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect((new UserPolicy)->forceDelete($user, $other))->toBeFalse();
});
