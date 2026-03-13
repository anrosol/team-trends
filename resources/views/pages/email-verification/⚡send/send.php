<?php

use Livewire\Component;

new class extends Component
{
    public function mount(): void
    {
        auth()->user()->sendEmailVerificationNotification();

        redirect()->back()->with('message', __('send.link_sent'));
    }
};
