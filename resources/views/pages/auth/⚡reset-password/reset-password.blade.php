<div class="md:w-96 mx-auto mt-20">

    <x-header title="{{ __('reset_password.title') }}" separator progress-indicator>

    </x-header>

    <x-form wire:submit="updatePassword">

        <x-input wire:model="token" type="hidden" class="hidden"/>

        <x-input label="{{ __('reset_password.email') }}" icon="o-envelope" wire:model="email" hint="{{ __('reset_password.email_hint') }}" />

        <x-input label="{{ __('reset_password.new_password') }}" wire:model="password" icon="o-key" type="password" hint="{{ __('reset_password.new_password_hint') }}" />

        <x-input label="{{ __('reset_password.confirm_new_password') }}" wire:model="password_confirmation" icon="o-key" type="password" />

        <x-slot:actions>

            <x-button class="btn-primary" label="{{ __('reset_password.reset_button') }}" type="submit" spinner="save" />

        </x-slot:actions>

    </x-form>

</div>