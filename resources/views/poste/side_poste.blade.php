@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-poste.nav-side_poste href="{{ route('poste') }}" :active="request()->routeIs('poste')">
                {{ __('Liste des postes') }}
            </x-poste.nav-side_poste>
            <x-poste.nav-side_poste href="{{ route('poste.add') }}" :active="request()->routeIs('poste.add')">
                {{ __('Ajouter un poste') }}
            </x-poste.nav-side_poste>
    </ul>
</div>