@extends("layouts.index")

@section('content')
    <div class="content-fluid">
        <div class="row">
            <h1>Rapport des écarts</h1>
        @foreach ($employes as $employe)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th colspan="4" style="font-size: 30px"> <strong>{{$employe->nom}}</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">Poste actuel</th>
                                            <th colspan="2">Poste précédent</th>
                                        </tr>
                                        <tr>
                                            {{-- {{$employe->postes()->first()->libelle_poste ?? null}} ({{$employe->postes()->first()->code_poste ?? null}}) --}}
                                            <td colspan="2"> {{$employe->posteActuel()->libelle_poste ?? 'Aucun poste assigné'}} ({{$employe->posteActuel()->code_poste ?? 'Aucun poste assigné'}}) </td>
                                            <td colspan="2"> {{$employe->postePrecedent()->libelle_poste ?? $employe->posteActuel()->libelle_poste ?? 'Aucun poste assigné' }} ({{$employe->postePrecedent()->code_poste ?? $employe->posteActuel()->code_poste ?? 'Aucun poste assigné'}}) </td>
                                        </tr>

                                        {{-- @foreach ($employe->profils()->where('fonctionnalites.created_at', '>', $date)->get() as $empProfil)
        
                                        @endforeach --}}
            @foreach ($employe->profils()->get() as $empProfil)

                                        <tr>
                                            <th scope="row" colspan="4" style="background-color:rgb(199, 199, 201)">Application : {{$empProfil->application->libelle_application  ?? null}} ({{$empProfil->application->code_application ?? null}}) </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" style="background-color: {{$employe->posteActuel()->pivot->date_fin_fonction ? 'rgb(255, 230, 0)' : ''}} {{$empProfil->employes()->where('employe_id', $employe->id)->first()->pivot->date_assignation >= $date ? 'rgb(99, 73, 245)' : ''}};">Profil : {{$empProfil->libelle_profil ?? null}} avec pour code {{$empProfil->code_profil ?? null}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Fonctionnalités totales</th>
                                            <th colspan="2">Fonctionnalités ajoutées récemment</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                @foreach ($empProfil->fonctionnalites()->get() as $empFonct)
                                                    <li>{{$empFonct->libelle_fonct ?? null}} ({{$empFonct->code_fonct ?? null}}) sur le module {{$empFonct->module->libelle_module ?? null}} ({{$empFonct->module->code_module ?? null}}) </li>
                                                @endforeach
                                            </td>
                                            <td colspan="2">
                                                @foreach ($empProfil->fonctionnalites()->where('fonctionnalites.created_at', '>', $date)->get() as $pop)
                                                <li style="background-color: rgb(255, 230, 0)">{{$pop->libelle_fonct ?? "Aucune nouvelle fonctionnalité"}}</li>
                                                @endforeach
                                            </td>
                                        </tr>
            @endforeach

                                    </tbody>
                                </table>
                            </div>
                            
                        </div>

                    </div>
                
                </div>
        @endforeach
    </div>  
    </div>
@endsection

