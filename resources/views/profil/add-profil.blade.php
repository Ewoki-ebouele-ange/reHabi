@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('profil.side_profil')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Ajouter un profil
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="code_profil">Entrer le code du profil</label>
                                <input id="code_profil" name="code_profil" type="text" class="rounded-xl w-50" value="{{old('code_profil')}}">
                                @error('code_profil')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="libelle_profil">Entrer le libellé</label>
                                <input id="libelle_profil" name="libelle_profil" type="text" class="rounded-xl w-50" value="{{old('libelle_profil')}}"> 
                                @error('libelle_profil')
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