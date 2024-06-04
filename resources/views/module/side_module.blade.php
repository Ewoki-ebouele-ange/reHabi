@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-module.nav-side_module href="{{ route('module') }}" :active="request()->routeIs('module')">
                {{ __('Liste des modules') }}
            </x-module.nav-side_module>
            <x-module.nav-side_module href="{{ route('module.add') }}" :active="request()->routeIs('module.add')">
                {{ __('Ajouter un module') }}
            </x-module.nav-side_module>
    </ul>
</div>