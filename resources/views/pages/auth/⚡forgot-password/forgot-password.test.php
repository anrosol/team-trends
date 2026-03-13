<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::auth.forgot-password')
        ->assertStatus(200);
});
