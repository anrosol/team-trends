<div>
    <fieldset class="fieldset py-0">
        {{-- STANDARD LABEL --}}
        <legend class="fieldset-legend mb-0.5">
            {{ __('questions.title') }}
            <span class="text-error">*</span>
        </legend>

        <div class="border border-stone-300 rounded p-2 px-4">
            @foreach ($questions as $i => $question)
                <div class="flex gap-2 mb-2 items-start" wire:key="questions-{{ $i }}">
                    <div class="w-15">
                        <x-input wire:model="questions.{{ $i }}.color" wire:change="notify()" label="{{ $loop->first ? 'Color' : '' }}" type="color" error="questions.{{ $i }}.color" />
                    </div>

                    <div class="flex-none">
                        <x-input wire:model="questions.{{ $i }}.name" wire:change="notify()" label="{{ $loop->first ? 'Name' : '' }}" error="questions.{{ $i }}.name" />
                    </div>

                    <div class="flex-auto">
                        <x-input wire:model="questions.{{ $i }}.definition" wire:change="notify()" label="{{ $loop->first ? 'Definition' : '' }}" class="w-50 flex-auto" error="questions.{{ $i }}.definition" />
                    </div>

                    <div class="flex-none">
                        <x-button icon="o-trash" class="btn-square" wire:click.prevent="remove({{ $i }})" @class(['w-10 flex-none', 'mt-9' => $loop->first]) />
                    </div>
                </div>
            @endforeach
        </div>

        <x-button icon="o-plus" wire:click.prevent="add" />

        {{-- ERROR --}}
        @foreach($errors->get('questions') as $message)
            @foreach(Arr::wrap($message) as $question)
                <div class="text-error" x-class="text-error">{{ $question }}</div>
            @endforeach
        @endforeach

        {{-- HINT --}}
        <div class="fieldset-label" x-classes="fieldset-label">{{ __('questions.hint', ['max_questions' => config('appex.max_questions')]) }}</div>
    </fieldset>
</div>
