<?php

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public User $user;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function mount(): void
    {
        $this->user = auth()->user();

        $this->fill($this->user);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['sometimes', Password::min(8)],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $this->user->update($data);

        $this->success(__('profile.updated'), redirectTo: route('pages.auth.profile'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('profile.title'));
    }
};
