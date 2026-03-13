<?php

use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);
});

it('renders successfully', function () {
    Livewire::test('pages::teams.index')
        ->assertStatus(200);
});
