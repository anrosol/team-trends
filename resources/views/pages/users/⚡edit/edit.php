<?php

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public User $user;

    public bool $admin = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $phone = '';

    public string $company = '';

    public ?string $timezone = '';

    public function mount(User $user): void
    {
        $this->authorize('view', $user);

        $this->user = $user;

        $this->fill($this->user);
    }

    public function rules(): array
    {
        return [
            'admin' => ['boolean'],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['sometimes', Password::min(8)],
            'phone' => ['required', 'numeric'],
            'company' => ['required'],
            'timezone' => ['nullable', 'timezone:all'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->user);

        $data = $this->validate();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $this->user->update($data);

        $this->success(__('users.edit.success'), redirectTo: route('pages.users.index'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('users.edit.title', ['name' => $this->user->name]));
    }
};
