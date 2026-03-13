<?php

use App\Models\Team;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public Team $team;

    public string $name = '';

    public string $max_respondents = '';

    public bool $active = true;

    public array $questions = [];

    public function mount(Team $team): void
    {
        $this->authorize('view', $team);

        $this->team = $team;

        $this->fill($this->team);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100'],
            'max_respondents' => ['required', 'integer', 'gte:'.$this->team->respondents->count()],
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
        $this->authorize('update', $this->team);

        $data = $this->validate();

        $this->team->update($data);

        $this->success(__('teams.edit.success'), redirectTo: route('pages.teams.index'));

    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('teams.edit.title', ['name' => $this->team->name]));
    }
};
