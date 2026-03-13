<div class="md:w-96 mx-auto mt-20">

    <x-header title="{{ __('notice.title') }}" separator progress-indicator />

    <x-form wire:submit="resendVerification">

        <x-alert title="{{ __('notice.check_email') }}" icon="o-exclamation-triangle" />

        <x-slot:actions>

            <x-button class="btn-primary" label="{{ __('notice.resend_link') }}" type="submit" spinner="save" />

        </x-slot:actions>

    </x-form>

</div>