<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public function mount(): void
    {
        if (auth()->user()->hasVerifiedEmail()) {
            redirect()->route('pages.dashboard');
        }
    }

    public function resendVerification()
    {
        auth()->user()->sendEmailVerificationNotification();

        $this->success(__('notice.link_sent'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('notice.title'));
    }
};
