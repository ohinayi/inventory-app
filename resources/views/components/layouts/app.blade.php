<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name', 'HansKeeper') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/filament/admin/theme.css'])
    @filamentStyles


</head>

<body class="font-sans antialiased">

    {{-- The navbar with `sticky` and `full-width` --}}
    <x-mary-nav sticky full-width>

        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="mr-3 lg:hidden">
                <x-mary-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            {{-- Brand --}}
            <div>
                <a href="{{ route('keeper.dashboard') }}" class="flex ms-2 md:me-24">
                    <img src="{{ Storage::url('logo.png') }}" class="h-8 me-3" alt="" />
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Store keeper Dashboard</span>
                </a>
            </div>
        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>

            <x-mary-button label="User Dashboard" no-wire-navigate icon="o-user-group" link="{{ route('dashboard') }}" class="btn-ghost btn-sm" responsive />
            <x-mary-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive />
            <x-mary-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
        </x-slot:actions>
    </x-mary-nav>

    {{-- The main content with `full-width` --}}
    <x-main with-nav full-width>

        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">

            {{-- User --}}
            @if($user = auth()->user())
            <x-mary-list-item avatar='avatar_url' :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">
                <x-slot:actions>
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf   
                    <x-mary-button type="submit" icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff"  />

                       
                    </form>
                </x-slot:actions>
            </x-mary-list-item>

            <x-mary-menu-separator />
            @endif

            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-mary-menu activate-by-route>
                <x-mary-menu-item title="Home" exact icon="o-home" link="{{ route('keeper.dashboard') }}" wire:navigate />
                <x-mary-menu-item title="Items" icon="o-rectangle-group" link="{{route('keeper.items')}}" wire:navigate />
                <x-mary-menu-item title="Users" icon="o-user-group" link="{{route('keeper.users')}}" wire:navigate />
                <x-mary-menu-item title="Consumptions" icon="o-building-storefront" link="{{route('keeper.consumptions')}}" wire:navigate />
                <x-mary-menu-item title="Consumptions Requests" icon="o-building-storefront" link="{{route('keeper.consumption-requests')}}" wire:navigate />
                <x-mary-menu-item title="Procurements" icon="o-information-circle" link="{{route('keeper.procurements')}}" wire:navigate />
                {{-- <x-mary-menu-sub title="Procurements" icon="o-speaker-wave">
                </x-mary-menu-sub>--}}
                <x-mary-menu-sub title="Settings" icon="o-cog-6-tooth">
                    <x-mary-menu-item title="Wifi" icon="o-wifi" link="####" />
                    <x-mary-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-mary-menu-sub>
            </x-mary-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content class="min-h-screen">

            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    <x-mary-toast />
    @filamentScripts

</body>

</html>