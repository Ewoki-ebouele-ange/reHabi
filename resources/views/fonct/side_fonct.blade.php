@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-fonct.nav-side_fonct href="{{ route('fonct') }}" :active="request()->routeIs('fonct')">
                {{ __('Liste des fonctionnalités') }}
            </x-fonct.nav-side_fonct>
            <x-fonct.nav-side_fonct href="{{ route('fonct.add') }}" :active="request()->routeIs('fonct.add')">
                {{ __('Ajouter un fonct') }}
            </x-fonct.nav-side_fonct>
    </ul>
</div>