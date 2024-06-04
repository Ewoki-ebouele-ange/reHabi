@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('employe.side_emp')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Modifier un employé
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="nom">Entrer le nom</label>
                                <input id="nom" name="nom" type="text" class="rounded-xl w-50" value="{{old('nom', $employe->nom)}}">
                                @error('nom')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="matricule">Entrer le matricule</label>
                                <input id="matricule" name="matricule" type="text" class="rounded-xl w-50" value="{{old('matricule', $employe->matricule)}}"> 
                                @error('matricule')
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