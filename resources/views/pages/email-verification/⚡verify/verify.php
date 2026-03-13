<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Livewire\Component;

new class extends Component
{
    public function mount(EmailVerificationRequest $request): void
    {
        $request->fulfill();
    }
};
