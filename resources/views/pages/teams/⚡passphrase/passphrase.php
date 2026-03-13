<?php

use App\Models\Respondent;
use App\Models\Team;
use App\Traits\HasBotProtection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use HasBotProtection;
    use Toast;

    public ?Team $team;

    public string $passphrase = '';

    public function mount(string $token): void
    {
        $this->team = Team::whereToken($token)?->first();

        if ($this->team === null) {
            abort(404);
        }

        // Reduce accidental multiple passphrase submissions with:
        if (! Cache::has($this->cacheKey())) {
            Cache::put($this->cacheKey(), false, now()->addDays(1));
        }

        $this->refreshPassphrase();
    }

    public function refreshPassphrase(): void
    {
        $this->passphrase = Respondent::newPassphrase();
    }

    #[Computed]
    public function cacheKey(): string
    {
        return session()->getId().'.passphrase.submission.token';
    }

    public function rules(): array
    {
        return [
            'passphrase' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Respondent::passphraseExists($value)) {
                        $fail(__('teams.passphrase.validation.passphrase_exists'));
                    }
                },
            ],
        ];
    }

    public function save(): void
    {
        $back = route('pages.teams.passphrase', $this->team->token);

        if ($this->isHoneypotFilled()) {
            // Let's fake success.
            $this->success(__('teams.passphrase.success.created'), redirectTo: route('pages.index'));

            return;
        }

        if ($this->isRateLimited(maxAttempts: 3)) {
            $this->error(__('teams.passphrase.errors.rate_limited'), redirectTo: $back);

            return;
        }

        if (Cache::get($this->cacheKey()) === true) {
            $this->error(__('teams.passphrase.errors.already_claimed'), redirectTo: $back);
        }

        if ($this->team->complete()) {
            $this->error(__('teams.passphrase.errors.no_additional'), redirectTo: $back);

            return;
        }

        Cache::put($this->cacheKey(), true, now()->addDays(1));

        $data = $this->validate();

        Respondent::create([
            'team_id' => $this->team->id,
            'passphrase' => $data['passphrase'],
        ]);

        $this->success(__('teams.passphrase.success.created'), redirectTo: route('pages.teams.respondent'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('teams.passphrase.title', ['team' => $this->team->name, 'company' => $this->team->user->company]));
    }
};
