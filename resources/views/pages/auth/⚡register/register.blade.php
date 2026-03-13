@use('\App\Models\User')
<div class="md:w-96 mx-auto mt-20">

    <x-form wire:submit="register">

        <x-honeypot />

        @php
            $hasUsers = \App\Models\User::count() !== 0;
        @endphp

        <x-header title="{{ __('register.title') }}" subtitle="{{ $hasUsers ? '' : __('register.first_user_admin') }}" separator />

        <x-input placeholder="{{ __('register.name') }}" wire:model="name" icon="o-user" required />

        <x-input placeholder="{{ __('register.email') }}" wire:model="email" icon="o-envelope" required />

        <x-input placeholder="{{ __('register.password') }}" wire:model="password" type="password" icon="o-key" required />

        <x-input placeholder="{{ __('register.password_confirm') }}" wire:model="password_confirmation" type="password" icon="o-key" required />

        <x-input placeholder="{{ __('register.phone') }}" wire:model="phone" required />

        <x-input placeholder="{{ __('register.company') }}" wire:model="company" required />

        <x-select placeholder="{{ __('users.timezone') }}" wire:model="timezone" :options="array_map(fn ($item) => ['id' => $item, 'name' => $item], [null] + DateTimeZone::listIdentifiers())" x-init="$wire.set('timezone', Intl.DateTimeFormat().resolvedOptions().timeZone);" />

        <x-slot:actions>

            @if ($hasUsers)

                <x-button label="{{ __('register.already_registered') }}" class="btn-ghost" link="/login" />

            @endif

            <x-button label="{{ __('register.register') }}" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="register" />

        </x-slot:actions>

    </x-form>

</div>