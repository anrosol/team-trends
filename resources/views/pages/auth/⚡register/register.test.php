<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::auth.register')
        ->assertStatus(200);
});
