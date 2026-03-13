<?php

use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->team = Team::factory()->create([
        'token' => str()->ulid(),
        'user_id' => $user->id,
    ]);
});

it('renders successfully', function () {
    Livewire::test('pages::teams.passphrase', ['token' => $this->team->token])
        ->assertStatus(200);
});
