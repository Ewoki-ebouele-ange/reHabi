@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('fonct.side_fonct')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Modifier un fonctionnalité
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="code_fonct">Entrer le code de le fonctionnalité</label>
                                <input id="code_fonct" name="code_fonct" type="text" class="rounded-xl w-50" value="{{old('code_fonct', $fonct->code_fonct)}}">
                                @error('code_fonct')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="libelle_fonct">Entrer le libelle</label>
                                <input id="libelle_fonct" name="libelle_fonct" type="text" class="rounded-xl w-50" value="{{old('libelle_fonct', $fonct->libelle_fonct)}}"> 
                                @error('libelle_fonct')
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