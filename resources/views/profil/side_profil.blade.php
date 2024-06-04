@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-profil.nav-side_profil href="{{ route('profil') }}" :active="request()->routeIs('profil')">
                {{ __('Liste des profils') }}
            </x-profil.nav-side_profil>
            <x-profil.nav-side_profil href="{{ route('profil.add') }}" :active="request()->routeIs('profil.add')">
                {{ __('Ajouter un profil') }}
            </x-profil.nav-side_profil>
    </ul>
</div>