<div class="max-w-4xl mx-auto">

    @use('Illuminate\Support\Facades\Cache')

    <x-header title="{{ __('teams.passphrase.title', ['team' => $team->name, 'company' => $team->user->company]) }}" separator />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Privacy --}}
        <x-card title="{{ __('teams.passphrase.privacy.title') }}" subtitle="{{ __('teams.passphrase.privacy.subtitle') }}" shadow separator>

            <h3 class="font-bold mb-5">{{ __('teams.passphrase.privacy.your_passphrase') }}</h3>

            <ul class="space-y-3 text-sm">

                <li class="flex gap-5">
                    <x-heroicon-o-check-circle class="size-5 text-success shrink-0 mt-0.5" />
                    <span>{{ __('teams.passphrase.privacy.point.represents_respondent') }}</span>
                </li>

                <li class="flex gap-5">
                    <x-heroicon-o-check-circle class="size-5 text-success shrink-0 mt-0.5" />
                    <span>{{ __('teams.passphrase.privacy.point.groups_responses') }}</span>
                </li>

                <li class="flex gap-5">
                    <x-heroicon-o-check-circle class="size-5 text-success shrink-0 mt-0.5" />
                    <span>{{ __('teams.passphrase.privacy.point.prevents_multiple') }}</span>
                </li>

                <li class="flex gap-5">
                    <x-heroicon-o-check-circle class="size-5 text-success shrink-0 mt-0.5" />
                    <span>{{ __('teams.passphrase.privacy.point.does_not_identify') }}</span>
                </li>

                <li class="flex gap-4">
                    <x-heroicon-o-lock-closed class="size-5 text-success shrink-0 mt-0.5" />
                    <span>{{ __('teams.passphrase.privacy.point.irreversible') }}</span>
                </li>

            </ul>

            <x-alert icon="o-exclamation-triangle" class="alert-warning mt-4 text-sm text-white font-bold">
                {{ __('teams.passphrase.privacy.point.cannot_recover') }}
            </x-alert>

        </x-card>

        {{-- Claim --}}
        <x-card title="{{ __('teams.passphrase.claim_passphrase.title') }}" subtitle="{{ __('teams.passphrase.claim_passphrase.subtitle') }}" shadow separator>

            @if ($team->available() > 0)

                <div class="font-mono text-lg text-center tracking-wide bg-base-200 rounded p-3 mb-4 select-all">
                    {{ $passphrase }}
                </div>

                <div class="flex gap-2 justify-center mb-6">
                    <x-button icon="o-clipboard" class="btn-sm" :tooltip="__('teams.passphrase.claim_passphrase.copy')" @click="navigator.clipboard.writeText('{{ $passphrase }}')" />
                    <x-button icon="c-arrow-path" class="btn-sm" :tooltip="__('teams.passphrase.claim_passphrase.generate')" wire:click="refreshPassphrase" />
                </div>

                <x-form wire:submit="save" wire:confirm="{{ __('teams.passphrase.claim_passphrase.claim_confirm') }}">

                    <input type="hidden" name="submission_token" value="{{ Cache::get($this->cacheKey) }}">

                    <x-honeypot />

                    <x-button label="{{ __('teams.passphrase.claim_passphrase.claim') }}" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary w-full" />

                </x-form>

            @else

                <x-alert icon="o-exclamation-circle" class="alert-error text-white font-bold">
                    {{ __('teams.passphrase.claim_passphrase.complete') }}
                </x-alert>

            @endif

        </x-card>

        {{-- Open Source --}}
        <x-card title="{{ __('teams.passphrase.open_source.title') }}" subtitle="{{ __('teams.passphrase.open_source.subtitle') }}" shadow separator>

            <p class="text-sm mb-4">
                <a class="link" href="{{ config('appex.github') }}" target="_blank">{{ config('app.name') }}</a>
                {{ __('teams.passphrase.open_source.description') }}
            </p>

            <p class="text-sm">
                {{ __('teams.passphrase.open_source.privacy-first') }}
            </p>

        </x-card>

    </div>

</div>
