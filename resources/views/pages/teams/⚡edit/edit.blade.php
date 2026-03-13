<div>

    <x-header title="{{ __('teams.edit.title', ['name' => $team->name]) }}" separator />

    <x-form wire:submit="save"> 

        <x-input label="{{ __('teams.name') }}" wire:model="name" class="grow" hint="{{ __('teams.name_hint') }}" required />

        <x-input label="{{ __('teams.max_respondents') }}" wire:model="max_respondents" :hint="__('teams.edit.max_respondents_hint', ['respondents' => $team->respondents->count(), 'available' => $team->available()])" required />

        <x-toggle label="{{ __('teams.active') }}" wire:model="active" hint="{{ __('teams.active_hint') }}"/>

        <livewire:app.questions wire:model="questions" :$team />

        <x-slot:actions>

            <x-button label="{{ __('teams.cancel') }}" link="{{ route('pages.teams.index') }}" />

            <x-button label="{{ __('teams.save') }}" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />

        </x-slot:actions>

    </x-form>

</div>