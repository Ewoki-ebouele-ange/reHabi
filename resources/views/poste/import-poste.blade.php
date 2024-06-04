@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('poste.side_poste')
                <div class="add w-full">
                    <h3>Importer</h3>
                    <p>Sélectionnez un fichier Excel (.xlsx) pour importer les données dans la table "postes".<br><strong>Les colonnes : </strong>code_poste, libelle_poste</p>
                    <form method="POST" action="{{ route('poste.import') }}" enctype="multipart/form-data" >
                        <!-- CSRF Token -->
                        @csrf
                        <input type="file" name="fichier" >
                        <button type="submit" >Importer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
