<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public ?string $email;

    public function sendVerificationMail()
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return route('pages.auth.reset-password', [$token, 'email' => $user->email]);
        });

        $status = Password::sendResetLink(['email' => $this->email]);

        $status === Password::RESET_LINK_SENT ? $this->success(__($status)) : $this->error(__($status));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('forgot_password.title'));
    }
};
