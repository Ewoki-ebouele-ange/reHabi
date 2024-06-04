@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('entite.side_ent')
                <div class="add w-full">
                    <h3>Exporter</h3>
                    <p>Exporter la table "entite" en Excel</p>
                    <form method="POST" action="{{ route('entite.export') }}" >
                        @csrf
                        <input type="text" name="name" placeholder="Nom de fichier" >
                        <select name="extension" >
                            <option value="xlsx" >.xlsx</option>
                            <option value="csv" >.csv</option>
                        </select>
                        <button type="submit" >Exporter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>