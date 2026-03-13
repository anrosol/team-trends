<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::auth.reset-password')
        ->assertStatus(200);
});
