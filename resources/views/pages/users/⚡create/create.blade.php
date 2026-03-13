<div>
    <x-header title="{{ __('users.create.title') }}" separator />

    <x-form wire:submit="save"> 

        <x-input label="{{ __('users.name') }}" wire:model="name" required />

        <x-toggle label="{{ __('users.admin') }}" wire:model="admin" />

        <x-input label="{{ __('users.email') }}" wire:model="email" required />

        <x-password label="{{ __('users.password') }}" wire:model="password" required />

        <x-input label="{{ __('users.phone') }}" wire:model="phone" required />

        <x-input label="{{ __('users.company') }}" wire:model="company" required />

        <x-select label="{{ __('users.timezone') }}" wire:model="timezone" :options="array_map(fn ($item) => ['id' => $item, 'name' => $item], [null] + DateTimeZone::listIdentifiers())" x-init="$wire.set('timezone', Intl.DateTimeFormat().resolvedOptions().timeZone);" />

        <x-slot:actions>

            <x-button label="{{ __('users.cancel') }}" link="{{ route('pages.users.index') }}" />

            <x-button label="{{ __('users.save') }}" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />

        </x-slot:actions>

    </x-form>
</div>