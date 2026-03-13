<div>

    <!-- HEADER -->
    <x-header title="{{ __('users.index.title') }}" separator progress-indicator>

        <x-slot:actions>

            <x-input placeholder="{{ __('users.index.search_placeholder') }}" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />

            <x-button label="{{ __('users.index.create_button') }}" link="{{ route('pages.users.create') }}" responsive icon="o-plus" class="btn-primary" />   

        </x-slot:actions>

    </x-header>

    <!-- TABLE  -->
    <x-card shadow>

        <x-table :headers="$headers" :rows="$rows" :sort-by="$sortBy" with-pagination :per-page-values="[5, 10, 25]" :link="route('pages.users.edit', ['user' => '[id]'])">

            @scope('cell_admin', $user)

                @if ($user->admin)
                    <x-heroicon-c-check-circle :key="uniqid()" class="h-6 w-6" />
                @else
                    <x-heroicon-c-no-symbol :key="uniqid()" class="h-6 w-6" />
                @endif

            @endscope

            @scope('cell_created_at', $user)

                {{ $user->created_at->format('Y-m-d') }}

            @endscope

            @scope('actions', $user)

                <x-button icon="o-trash" wire:click="delete('{{ $user['id'] }}')" wire:confirm="{{ __('users.index.confirm_delete') }}" spinner class="btn-ghost btn-sm text-error" />

            @endscope

        </x-table>

    </x-card>

</div>