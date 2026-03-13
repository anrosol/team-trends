<?php

use Livewire\Component;

new class extends Component
{
    public function render()
    {
        return $this->view()
            ->layout('layouts::app', ['sidebar' => false])
            ->title(__('index.title'));
    }
};
