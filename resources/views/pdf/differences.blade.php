<!DOCTYPE html>
<html>
<head>
    <title>Différences entre Excel et la Base de Données</title>
    <link href="/assets/css/config/default/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="/assets/css/config/default/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

<link href="/assets/css/config/default/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled="disabled" />
<link href="/assets/css/config/default/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled="disabled" />

<!-- icons -->
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
                                        <th scope="row" colspan="4">Application</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Poste récent</th>
                                        <th colspan="2">Poste actuel</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{$employe->postes()->first()->libelle_poste}} ({{$employe->postes()->first()->code_poste}}) </td>
                                        <td colspan="2">...</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Profil récent</th>
                                        <th colspan="2">Profil actuel</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{$empProfil->libelle_profil}} avec pour code {{$empProfil->code_profil}} </td>
                                        <td colspan="2">...</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Fonctionnalités récentes</th>
                                        <th colspan="2">Fonctionnalités actuelles</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            @foreach ($empProfil->fonctionnalites()->get() as $empFonct )
                                                <li>{{$empFonct->libelle_fonct}} avec pour code {{$empFonct->code_fonct}} sur le module {{$empFonct->module->libelle_module}} ({{$empFonct->module->code_module}}) </li>
                                            @endforeach
                                        </td>
                                        <td colspan="2">.....</td>
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
