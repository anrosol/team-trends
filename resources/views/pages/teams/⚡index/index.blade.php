<div>

    <!-- HEADER -->
    <x-header title="{{ __('teams.index.title') }}" separator progress-indicator>

        <x-slot:actions>

            @if ($rows->count() !== 0)

                <x-input placeholder="{{ __('teams.index.search_placeholder') }}" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />

                <x-button label="{{ __('teams.index.create_button') }}" link="{{ route('pages.teams.create') }}" responsive icon="o-plus" class="btn-primary" />   

            @endif

        </x-slot:actions>

    </x-header>

    <!-- TABLE  -->
    <x-card shadow>

        @if ($rows->count() === 0)

            <div class="flex items-center justify-center h-50">

                <div class="text-center">

                    <div class="mb-5">{{ __('teams.index.no_teams') }}</div>

                    <x-button label="{{ __('teams.index.create_button') }}" link="{{ route('pages.teams.create') }}" responsive icon="o-plus" class="btn-primary" />   

                </div>

            </div>

        @else

            <x-table :headers="$headers" :rows="$rows" :sort-by="$sortBy" with-pagination :link="route('pages.teams.edit', ['team' => '[id]'])" class="mb-50">

                @scope('cell_respondents', $team)

                    {{ $team->respondents->count() }}

                @endscope

                @scope('cell_available', $team)

                    {{ $team->available() }}

                @endscope

                @scope('cell_active', $team)

                    @if ($team->active)
                        <x-heroicon-c-check-circle :key="uniqid()" class="h-6 w-6" />
                    @else
                        <x-heroicon-c-no-symbol :key="uniqid()" class="h-6 w-6" />
                    @endif

                @endscope

                @scope('actions', $team)

                    <x-dropdown :key="uniqid()">
                        <x-slot:trigger>
                            <x-button icon="c-ellipsis-vertical" class="btn-circle" />
                        </x-slot:trigger>

                        <x-menu-item title="{{ __('teams.index.new_passphrase_link') }}" wire:click="newPassphraseLink('{{ $team['id'] }}')" wire:confirm="{{ __('teams.index.new_passphrase_link_confirm') }}" spinner icon="o-link" class="btn-ghost btn-sm"/>

                        <x-menu-item title="{{ __('teams.index.passphrase_page') }}" link="{{ route('pages.teams.passphrase', $team->token) }}" icon="c-key" class="btn-ghost btn-sm" external />

                        <x-menu-item title="{{ __('teams.index.delete') }}" icon="o-trash" wire:click="delete('{{ $team['id'] }}')" wire:confirm="{{ __('teams.index.confirm_delete') }}" spinner class="btn-ghost btn-sm text-error" />
                    </x-dropdown>

                @endscope

            </x-table>


        @endif

    </x-card>

</div>