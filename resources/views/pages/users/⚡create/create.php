<?php

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $admin = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $phone = '';

    public string $company = '';

    public ?string $timezone = '';

    public function rules(): array
    {
        return [
            'admin' => ['boolean'],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)],
            'phone' => ['required', 'numeric'],
            'company' => ['required'],
            'timezone' => ['nullable', 'timezone:all'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', new User);

        $data = $this->validate();

        User::create([
            'admin' => $this->admin,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'company' => $this->company,
            'timezone' => $this->timezone,
        ]);

        $this->success(__('uers.create.success'), redirectTo: route('pages.users.index'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('users.create.title'));
    }
};
