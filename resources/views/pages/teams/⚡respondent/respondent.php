<?php

use App\Enums\PeriodEnum;
use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;
use App\Traits\HasBotProtection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use HasBotProtection;
    use Toast;

    public string $passphrase = '';

    public ?Team $team = null;

    public ?Respondent $respondent = null;

    // Survey
    public array $responses = [];

    // Chart
    public ?string $begin_at = null;

    public ?string $end_at = null;

    public ?int $question = 0;

    public ?string $period = null;

    public bool $weighted = false;

    public bool $me = false;

    public array $chart = [];

    public function rules(): array
    {
        $max = $this->team ? $this->team->likert_scale - 1 : 6;

        return [
            'passphrase' => ['required'],
            'responses' => ['required', 'array', 'size:'.($this->team ? count($this->team->questions) : 1)],
            'responses.*' => ['required', 'integer', 'min:0', "max:{$max}"],
        ];
    }

    public function submitPassphrase(): void
    {
        if ($this->isHoneypotFilled()) {
            // Let's fake success.
            $this->success(__('teams.respondent.errors.fake_success'), redirectTo: route('pages.index'));

            return;
        }

        if ($this->isRateLimited()) {
            $this->error(__('teams.respondent.errors.throttle', ['seconds' => $this->rateLimitAvailableIn()]));

            return;
        }

        $this->validate(['passphrase' => ['required']]);

        $this->respondent = Respondent::findByPassphrase($this->passphrase);

        if ($this->respondent === null) {
            $this->error(__('teams.respondent.invalid_passphrase'));

            return;
        }

        $this->team = $this->respondent->team;

        $this->loadChart();
    }

    public function submitSurvey(): void
    {
        $this->authorize('create', new Survey);

        if (! $this->team->active) {
            $this->error(__('teams.respondent.errors.team_inactive'));

            return;
        }

        $this->validate();

        if ($this->respondent->surveySubmitted()) {
            $this->error(__('teams.respondent.errors.already_submitted'));

            return;
        }

        Survey::create([
            'respondent_id' => $this->respondent->id,
            'responses' => $this->responses,
        ]);

        $this->success(__('teams.respondent.success.submitted'));

        $this->loadChart();
    }

    public function updated(string $name): void
    {
        $chartProperties = ['begin_at', 'end_at', 'question', 'period', 'weighted', 'me'];

        if (in_array($name, $chartProperties) && $this->team) {
            $this->loadChart();
        }
    }

    public function loadChart(): void
    {
        $this->begin_at ??= now()->startOfYear()->format('Y-m-d');
        $this->end_at ??= now()->endOfYear()->format('Y-m-d');

        $this->chart = ($this->me ? $this->respondent : $this->team)->chart(
            $this->begin_at,
            $this->end_at,
            PeriodEnum::tryFrom($this->period) ?? PeriodEnum::WEEK,
            $this->question ?? 0,
            $this->weighted ?? false,
        )->toArray();
    }

    #[Computed]
    public function likertScaleOptions(): array
    {
        return collect(config("likert.{$this->team->likert_scale}"))
            ->map(fn ($item, $key) => ['id' => $key, 'name' => __($item)])
            ->toArray();
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('teams.respondent.title'));
    }
};
