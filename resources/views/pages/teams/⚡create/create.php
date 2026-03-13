<?php

use App\Models\Team;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public string $name = '';

    public string $max_respondents = '';

    public bool $active = true;

    public array $questions = [];

    public function mount(): void
    {
        $this->authorize('create', new Team);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100', 'unique:teams,name'],
            'max_respondents' => ['required', 'integer', 'gt:1'],
            'active' => ['boolean'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.color' => ['required', 'hex_color'],
            'questions.*.name' => ['required'],
            'questions.*.definition' => ['required'],
        ];
    }

    #[On('questions.update')]
    public function questionsUpdate($questions): void
    {
        $this->questions = $questions;
    }

    public function save(): void
    {
        $this->authorize('create', new Team);

        $data = $this->validate();

        Team::create([
            'token' => str()->ulid(),
            'user_id' => auth()->user()->id,
            'name' => $this->name,
            'max_respondents' => $this->max_respondents,
            'active' => $this->active,
            'likert_scale' => config('appex.default_likert_scale'),
            'questions' => $this->questions,
        ]);

        $this->success(__('teams.create.success'), redirectTo: route('pages.teams.index'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('teams.create.title'));
    }
};
