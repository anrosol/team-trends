<div>

    <x-header title="{{ __('teams.create.title') }}" separator />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="flex gap-4">
            <div class="flex-none flex items-center justify-center size-10 rounded-full bg-primary/10 text-primary font-bold text-sm">1</div>
            <div>
                <div class="font-semibold text-sm">{{ __('teams.create.instructions.step_1_title') }}</div>
                <div class="text-sm text-base-content/60 mt-0.5">{{ __('teams.create.instructions.step_1_body') }}</div>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-none flex items-center justify-center size-10 rounded-full bg-primary/10 text-primary font-bold text-sm">2</div>
            <div>
                <div class="font-semibold text-sm">{{ __('teams.create.instructions.step_2_title') }}</div>
                <div class="text-sm text-base-content/60 mt-0.5">{{ __('teams.create.instructions.step_2_body') }}</div>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-none flex items-center justify-center size-10 rounded-full bg-primary/10 text-primary font-bold text-sm">3</div>
            <div>
                <div class="font-semibold text-sm">{{ __('teams.create.instructions.step_3_title') }}</div>
                <div class="text-sm text-base-content/60 mt-0.5">{{ __('teams.create.instructions.step_3_body') }}</div>
            </div>
        </div>

    </div>

    <x-form wire:submit="save">


        <x-input label="{{ __('teams.name') }}" wire:model="name" class="grow" hint="{{ __('teams.name_hint') }}" required />

        <x-input label="{{ __('teams.max_respondents') }}" wire:model="max_respondents" hint="{{ __('teams.create.max_respondents_hint') }}" required />

        <x-toggle label="{{ __('teams.active') }}" wire:model="active" hint="{{ __('teams.active_hint') }}"/>

        <livewire:app.questions wire:model="questions" />

        <x-slot:actions>

            <x-button label="{{ __('teams.cancel') }}" link="{{ route('pages.teams.index') }}" />

            <x-button label="{{ __('teams.save') }}" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />

        </x-slot:actions>

    </x-form>

</div>