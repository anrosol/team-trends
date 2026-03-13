<?php

use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $this->actingAs($user);
});

it('renders successfully', function () {
    Livewire::test('pages::email-verification.notice')
        ->assertStatus(200);
});
