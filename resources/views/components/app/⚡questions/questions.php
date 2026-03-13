<?php

use App\Models\Team;
use Livewire\Component;

new class extends Component
{
    public array $questions = [];

    public function mount(Team $team): void
    {
        if (count($this->questions) === 0) {
            $this->questions = $team->questions ?? config('questions');
        }

        $this->notify();
    }

    public function add()
    {
        if (count($this->questions) >= config('appex.max_questions')) {
            return;
        }

        $this->questions[] = [
            'color' => null,
            'name' => null,
            'definition' => null,
        ];
    }

    public function remove($i)
    {
        if (count($this->questions) > 1) {
            unset($this->questions[$i]);

            $this->questions = array_values($this->questions);
        }
    }

    // Did not work: https://livewire.laravel.com/docs/4.x/lifecycle-hooks#update
    // Plan B:
    public function notify()
    {
        $this->dispatch('questions.update', questions: $this->questions);
    }
};
