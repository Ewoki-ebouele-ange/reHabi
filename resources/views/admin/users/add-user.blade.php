@vite(['resources/css/global.css'])

<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Users
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('admin.users.side_user')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Ajouter un utilisateur
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="name">Entrer le nom</label>
                                <input id="name" name="name" type="text" class="rounded-xl w-50" value="{{old('name')}}" required autofocus>
                                @error('name')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="email">Entrer le matricule</label>
                                <input id="email" name="email" type="text" class="rounded-xl w-50" value="{{old('email')}}" required> 
                                @error('email')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="password">Entrer un mot de passe</label>
                                <input id="password" name="password" type="password" class="rounded-xl w-50" value="{{old('password')}}" required> 
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="password_confirmation">Confirmer mot de passe</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="rounded-xl w-50" value="{{old('password_confirmation')}}" required> 
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
</x-admin-layout>