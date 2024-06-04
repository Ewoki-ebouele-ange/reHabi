@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-user.nav-side_user href="{{ route('admin.user') }}" :active="request()->routeIs('admin.user')">
                {{ __('Liste des Utilisateurs') }}
            </x-user.nav-side_user>
            <x-user.nav-side_user href="{{ route('admin.user.add') }}" :active="request()->routeIs('admin.user.add')">
                {{ __('Ajouter un utilisateur') }}
            </x-user.nav-side_user>
    </ul>
</div>