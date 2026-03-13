<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="/logo.svg" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased bg-base-200">

    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav sticky full-width>

        <x-slot:brand>

            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">

                <x-icon name="o-bars-3" class="cursor-pointer" />

            </label>

            {{-- Brand --}}
            <div>
                <x-app-brand />
            </div>

        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>

            <x-button label="{{ __('app.nav.administrators') }}" icon="o-lock-closed" link="{{ route('login') }}" class="btn-ghost btn-sm" responsive />

            <x-button label="{{ __('app.nav.respondents') }}" icon="o-clipboard-document-list" link="{{ route('pages.teams.respondent') }}" class="btn-ghost btn-sm" responsive external />

            <x-button label="{{ __('app.nav.github') }}" icon="o-code-bracket" link="{{ config('appex.github') }}" class="btn-ghost btn-sm" responsive external />

        </x-slot:actions>

    </x-nav>

    {{-- The main content with `full-width` --}}
    <x-main with-nav full-width>

        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible @class(['bg-base-200', 'sm:hidden' => ! ($sidebar ?? true)])>

            {{-- User --}}
            @if($user = auth()->user())

                <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">

                    <x-slot:actions>

                        <x-button icon="o-user" class="btn-circle btn-ghost btn-xs" tooltip-left="{{ __('app.profile') }}" no-wire-navigate link="{{ route('pages.auth.profile') }}" />

                        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="{{ __('app.logoff') }}" no-wire-navigate link="{{ route('pages.auth.logout') }}" />

                    </x-slot:actions>

                </x-list-item>

                <x-menu-separator />

            @endif

            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-menu activate-by-route>
                @auth

                    <x-menu-item title="{{ __('app.dashboard') }}" icon="o-sparkles" link="{{ route('pages.dashboard') }}" /> 

                    @if (auth()?->user()?->admin)

                        <x-menu-item title="{{ __('app.users') }}" icon="o-users" link="{{ route('pages.users.index') }}" /> 

                    @endif

                    <x-menu-item title="{{ __('app.teams') }}" icon="o-users" link="{{ route('pages.teams.index') }}" /> 

                    <x-menu-separator />

                @endauth

                @guest
                    <x-menu-item title="{{ __('app.nav.administrators') }}" icon="o-lock-closed" link="{{ route('login') }}" />
                @endguest

                <x-menu-item title="{{ __('app.nav.respondents') }}" icon="o-clipboard-document-list" link="{{ route('pages.teams.respondent') }}" external />

                <x-menu-item title="{{ __('app.nav.github') }}" icon="o-code-bracket" link="{{ config('appex.github') }}" external />
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>

        <x-slot:footer class="text-center py-4 text-xs text-base-content/40">

            <a href="{{ config('appex.github') }}/releases/tag/{{ trim(`git describe --tags`) }}" target="_blank" class="hover:underline">

                {{ __('index.verify', ['rev' => trim(`git describe --tags`)]) }}

            </a>

        </x-slot:footer>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

</body>

</html>
