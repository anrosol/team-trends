<?php

use App\Models\User;
use Livewire\Livewire;

it('redirects successfully', function () {
    Livewire::test('pages::auth.login')
        ->assertStatus(302);
});

it('renders successfully', function () {
    User::factory()->create();

    Livewire::test('pages::auth.login')
        ->assertStatus(200);
});
