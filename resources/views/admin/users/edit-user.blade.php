@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('admin.users.side_user')
                <div class="add w-full">
                    
                    <div class="tit_add">
                        Modifier un user
                    </div>
                    <div class="formular">
                        <form method="POST" action="" class="flex flex-col gap-4">
                            @csrf
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="name">Entrer le nom</label>
                                <input id="name" name="name" type="text" class="rounded-xl w-50" value="{{old('name', $user->name)}}">
                                @error('name')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="lab_inp flex flex-col gap-2">
                                <label for="email">Entrer le matricule</label>
                                <input id="email" name="email" type="email" class="rounded-xl w-50" value="{{old('email', $user->email)}}"> 
                                @error('email')
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