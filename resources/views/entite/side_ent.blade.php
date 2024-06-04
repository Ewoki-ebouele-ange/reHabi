@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-entite.nav-side_ent href="{{ route('entite') }}" :active="request()->routeIs('entite')">
                {{ __('Liste des entités') }}
            </x-entite.nav-side_ent>
            <x-entite.nav-side_ent href="{{ route('entite.add') }}" :active="request()->routeIs('entite.add')">
                {{ __('Ajouter une entité') }}
            </x-entite.nav-side_ent>
    </ul>
</div>