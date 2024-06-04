@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-employe.nav-side_emp href="{{ route('employe') }}" :active="request()->routeIs('employe')">
                {{ __('Liste des employés') }}
            </x-employe.nav-side_emp>
            <x-employe.nav-side_emp href="{{ route('employe.add') }}" :active="request()->routeIs('employe.add')">
                {{ __('Ajouter employé') }}
            </x-employe.nav-side_emp>
    </ul>
</div>