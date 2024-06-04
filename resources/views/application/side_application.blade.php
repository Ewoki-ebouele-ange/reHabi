@vite(['resources/css/global.css'])

<div class="sidebar w-1/4">
    <ul class="link_ul py-3">
            <x-application.nav-side_application href="{{ route('application') }}" :active="request()->routeIs('application')">
                {{ __('Liste des applications') }}
            </x-application.nav-side_application>
            <x-application.nav-side_application href="{{ route('application.add') }}" :active="request()->routeIs('application.add')">
                {{ __('Ajouter une application') }}
            </x-application.nav-side_application>
    </ul>
</div>