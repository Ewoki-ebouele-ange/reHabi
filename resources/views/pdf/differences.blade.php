<!DOCTYPE html>
<html>
<head>
    <title>Différences entre Excel et la Base de Données</title>
    <link href="/assets/css/config/default/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="/assets/css/config/default/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

<link href="/assets/css/config/default/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled="disabled" />
<link href="/assets/css/config/default/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled="disabled" />

<!-- icons -->
<link rel="shortcut icon" href="{{asset("/assets/images/logo-scb-vide.png")}}">
<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <h1>Différences entre Excel et la Base de Données</h1>
    
    {{-- <h2>Données présentes dans le fichier Excel mais pas dans la Base de Données</h2>
    <ul>
        @foreach ($dataInExcelNotInDB as $item)
            <li> {{ json_encode($item) }}</li>
        @endforeach
    </ul>

    <h2>Données présentes dans la Base de Données mais pas dans le fichier Excel</h2>
    <ul>
        @foreach ($dataInDBNotInExcel as $item)
            <li> {{ json_encode($item) }} </li>
        @endforeach
    </ul> --}}
    @foreach ($employes as $employe)

        @foreach ($employe->profils()->get() as $empProfil)
        
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th colspan="4">{{$employe->nom}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th colspan="2">Poste actuel</th>
                                        <th colspan="2">Poste précédent</th>
                                    </tr>
                                    <tr>
                                        {{-- {{$employe->postes()->first()->libelle_poste ?? null}} ({{$employe->postes()->first()->code_poste ?? null}}) --}}
                                        <td colspan="2"> {{$employe->posteActuel()->libelle_poste}} ({{$employe->posteActuel()->code_poste}}) </td>
                                        <td colspan="2"> {{$employe->postePrecedent()->libelle_poste ?? $employe->posteActuel()->libelle_poste }} ({{$employe->postePrecedent()->code_poste ?? $employe->posteActuel()->code_poste}}) </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4">{{$empProfil->application->libelle_application  ?? null}} ({{$empProfil->application->code_application ?? null}}) </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Profil actuel</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4">{{$empProfil->libelle_profil ?? null}} avec pour code {{$empProfil->code_profil ?? null}} </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Fonctionnalités récentes</th>
                                        <th colspan="1">Fonctionnalités ajoutées</th>
                                        <th colspan="1">Fonctionnalités à suspendre</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            @foreach ($empProfil->fonctionnalites()->get() as $empFonct)
                                                <li>{{$empFonct->libelle_fonct ?? null}} ({{$empFonct->code_fonct ?? null}}) sur le module {{$empFonct->module->libelle_module ?? null}} ({{$empFonct->module->code_module ?? null}}) </li>
                                            @endforeach
                                        </td>
                                        <td colspan="1">
                                            
                                            @foreach ($empProfil->fonctionnalites()->where('fonctionnalites.created_at', '>=', $periodTimestamp)->get() as $pop)
                                            <li style="background-color: rgb(255, 230, 0)">{{$pop->libelle_fonct ?? "Aucune nouvelle fonctionnalité"}}</li>
                                            @endforeach

                                        </td>
                                        <td colspan="1">
                                            
                                            @foreach ($empProfil->fonctionnalites()->where('fonctionnalites.created_at', '>=', $periodTimestamp)->get() as $pop)
                                            <li style="background-color: rgb(255, 230, 0)">{{$pop->libelle_fonct ?? "Aucune nouvelle fonctionnalité"}}</li>
                                            @endforeach

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>

                </div>
            
            </div>
        @endforeach
    @endforeach
    
</body>
</html>
