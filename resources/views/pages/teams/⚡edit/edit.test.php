<?php

use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->team = Team::factory()->create([
        'user_id' => $user->id,
    ]);
});

it('renders successfully', function () {
    Livewire::test('pages::teams.edit', [$this->team])
        ->assertStatus(200);
});
