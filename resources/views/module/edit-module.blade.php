@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('module.side_module')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Modifier un module
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="code_module">Entrer le code du module</label>
                                <input id="code_module" name="code_module" type="text" class="rounded-xl w-50" value="{{old('code_module', $module->code_module)}}">
                                @error('code_module')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="libelle_module">Entrer le libelle</label>
                                <input id="libelle_module" name="libelle_module" type="text" class="rounded-xl w-50" value="{{old('libelle_module', $module->libelle_module)}}"> 
                                @error('libelle_module')
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