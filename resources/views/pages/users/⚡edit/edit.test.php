<?php

use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create([
        'admin' => true,
    ]);

    $this->actingAs($user);
});

it('renders successfully', function () {
    Livewire::test('pages::users.edit')
        ->assertStatus(200);
});
