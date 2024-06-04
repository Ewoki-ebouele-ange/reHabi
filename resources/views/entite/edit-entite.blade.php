@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('entite.side_ent')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Modifier une entité
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="code_entite">Entrer le code de l'entité</label>
                                <input id="code_entite" name="code_entite" type="text" class="rounded-xl w-50" value="{{old('code_entite', $entite->code_entite)}}">
                                @error('code_entite')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="libelle_entite">Entrer le libelle</label>
                                <input id="libelle_entite" name="libelle_entite" type="text" class="rounded-xl w-50" value="{{old('libelle_entite', $entite->libelle_entite)}}"> 
                                @error('libelle_entite')
                                    {{$message}}
                                @enderror
                            </div>
                            <button>
                                <span class="bg-green-400 p-2 rounded-xl text-xl"> Enregistrer </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>