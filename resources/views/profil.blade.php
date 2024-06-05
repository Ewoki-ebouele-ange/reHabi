@vite(['resources/css/global.css'])

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-1 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 w-full sidebar_acc">
                @include('profil.side_profil')
                <div class="add w-full">
                    @if(session()->get('success'))
                        <div class="mess">
                            {{session()->get('success')}}   
                        </div>                       
                    @endif
                    @if(session()->get('info'))
                        <div class="inf">
                            {{session()->get('info')}}   
                        </div>                       
                    @endif
                    <div class="head_emp">
                        <span>Liste des profils</span>
                        <div class="import_export">
                            <a href="{{ route('profil.import') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <a href="{{ route('profil.export') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Id
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Code
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Libelle
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Actions 
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profils as $profil)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <th scope="row" class="text-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $profil->id }}
                                        </th>
                                        <th scope="row" class="text-center px-6 py-4 font-medium text-gray-900 overflow-hidden whitespace-nowrap max-w-[100px] text-ellipsis dark:text-white">
                                            {{ $profil->code_profil }}
                                        </th>
                                        <td class="px-6 py-4 text-center overflow-hidden whitespace-nowrap max-w-[150px] text-ellipsis">
                                            {{ $profil->libelle_profil }} 
                                        </td>
                                        <td class="px-6 py-4 flex gap-2 items-center justify-center">
                                            <a href="/profil/{{$profil->id}}/edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>

                                            <form action="{{route('profil.destroy', $profil->id)}}" method="post" class="m-0">
                                                @csrf
                                                {{-- @method('DELETE') --}}
                                                <button type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            {{$profils->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>