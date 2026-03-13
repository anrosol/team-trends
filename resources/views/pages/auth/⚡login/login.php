<?php

use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public string $password = '';

    public function mount()
    {
        if (User::count() == 0) {
            return redirect()->route('pages.auth.register');
        }

        if (auth()->user()) {
            return redirect()->route('pages.teams.index');
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => 'required',
        ];
    }

    public function login()
    {
        $credentials = $this->validate();

        if (auth()->attempt($credentials)) {
            request()->session()->regenerate();

            return redirect()->intended(route('pages.teams.index'));
        }

        $this->addError('email', __('login.no_match'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('login.title'));
    }
};
