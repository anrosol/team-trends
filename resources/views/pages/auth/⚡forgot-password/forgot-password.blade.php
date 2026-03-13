<div class="md:w-96 mx-auto mt-20">

    <x-header title="{{ __('forgot_password.title')  }}" separator progress-indicator>

    </x-header>

    <x-form wire:submit="sendVerificationMail">

        <x-input label="{{ __('forgot_password.email') }}" icon="o-envelope" wire:model="email" hint="{{ __('forgot_password.email_hint') }}" />

        <x-slot:actions>

            <x-button label="{{ __('forgot_password.back_to_login') }}" class="btn-ghost" link="{{ route('login') }}" />

            <x-button class="btn-primary" label="{{ __('forgot_password.send') }}" type="submit" spinner="save" />

        </x-slot:actions>

    </x-form>

</div>