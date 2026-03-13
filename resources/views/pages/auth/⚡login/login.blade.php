<div class="md:w-96 mx-auto mt-20">

    <x-header title="{{ __('login.title') }}" subtitle="" separator />

    <x-form wire:submit="login">

        <x-input placeholder="{{ __('login.email') }}" wire:model="email" icon="o-envelope" />

        <x-input placeholder="{{ __('login.password') }}" wire:model="password" type="password" icon="o-key" />

        <x-slot:actions>

            <x-button label="{{ __('login.forgot_password') }}" class="btn-ghost" link="{{ route('pages.auth.forgot-password') }}" />

            <x-button label="{{ __('login.register') }}" class="btn-ghost" link="{{ route('pages.auth.register') }}" />

            <x-button label="{{ __('login.login') }}" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />

        </x-slot:actions>

    </x-form>

</div>