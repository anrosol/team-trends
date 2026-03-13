<div>
    <x-header title="{{ __('profile.title') }}" separator />

    <x-form wire:submit="save"> 

        <x-input label="{{ __('profile.name') }}" wire:model="name" />

        <x-input label="{{ __('profile.email') }}" wire:model="email" />

        <x-password label="{{ __('profile.password') }}" wire:model="password" hint="{{ __('profile.password_hint') }}" />

        <x-slot:actions>

            <x-button label="{{ __('profile.cancel') }}" link="{{ route('pages.users.index') }}" />

            <x-button label="{{ __('profile.save') }}" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />

        </x-slot:actions>

    </x-form>

</div>