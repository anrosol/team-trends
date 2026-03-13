<?php

use App\Models\User;
use App\Traits\HasBotProtection;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

new class extends Component
{
    use HasBotProtection;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $phone = '';

    public string $company = '';

    public ?string $timezone = '';

    public function mount()
    {
        if (auth()->user()) {
            return redirect()->route('pages.teams.index');
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => 'required',
            'phone' => ['required', 'numeric'],
            'company' => ['required'],
            'timezone' => ['nullable', 'timezone:all'],
        ];
    }

    public function register()
    {
        if ($this->isHoneypotFilled()) {
            // Let's fake success.
            $this->success(__('register.errors.fake_success'), redirectTo: route('pages.index'));

            return;
        }

        if ($this->isRateLimited()) {
            $this->error(__('register.errors.throttle', ['seconds' => $this->rateLimitAvailableIn()]));

            return;
        }

        $data = $this->validate();

        $data['password'] = Hash::make($data['password']);

        if (User::count() == 0) {
            $data['admin'] = true;
        }

        $user = User::create($data);

        auth()->login($user);

        request()->session()->regenerate();

        event(new Registered($user));

        return redirect()->route('pages.teams.index');
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('register.title'));
    }
};
