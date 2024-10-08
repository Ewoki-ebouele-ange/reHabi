@extends("layouts.index")

@section('link')
     <!-- third party css -->
     <link href="{{asset("/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css")}}" rel="stylesheet" type="text/css" />
     <link href="{{asset("/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css")}}" rel="stylesheet" type="text/css" />
     <link href="{{asset("/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css")}}" rel="stylesheet" type="text/css" />
     <link href="{{asset("/assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css")}}" rel="stylesheet" type="text/css" />
     <!-- third party css end -->
     <!-- Sweet Alert-->
        <link href="{{asset("/assets/libs/sweetalert2/sweetalert2.min.css")}}" rel="stylesheet" type="text/css" />
        <!-- Plugins css -->
        <link href="{{asset("/assets/libs/mohithg-switchery/switchery.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{asset("/assets/libs/multiselect/css/multi-select.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{asset("/assets/libs/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{asset("/assets/libs/selectize/css/selectize.bootstrap3.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{asset("/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css")}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="mt-0 header-title">Listes des employés</h4>
                        <div class="d-flex gap-2">
                            <div class="fileupload add-new-plus">
                                    <span data-bs-toggle="modal" data-bs-target="#custom-modal-tree"
                                    data-animation="fadein"
                                    data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                        <i class="fe-download"></i>
                                    </span>
                            </div>
                            <div class="fileupload add-new-plus text-center">
                                <span>
                                    <a data-bs-toggle="modal" data-bs-target="#custom-modal-two" data-animation="fadein"
                                        data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                        <i class="fe-user-plus"></i>
                                    </a>
                            </span>
                            </div>
                        </div>
                    </div>
                    

                    <table id="datatable" class="table table-sm table-striped table-bordered table-hover dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Matricule</th>
                            <th>Actions</th>
                        </tr>
                        </thead>


                        <tbody id="employesList">
                            @foreach ($employes as $employe)
                        <tr>
                            <td>{{ $employe->id }}</td>
                            <td class="text-truncate" style="max-width: 100px;">{{ $employe->nom }}</td>
                            <td class="text-truncate" style="max-width: 100px;">{{ $employe->matricule }}</td>
                            <td class="d-flex justify-content-between align-items-center">
                                <div class="options">
                                    <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$employe->id}}"
                                        class="btn btn-xs waves-effect waves-light openModal" data-animation="fadein"
                                        data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                        <i class="fe-edit"></i>
                                    </a>
                                    <a data-bs-toggle="modal" data-bs-target="#custom-modal-four" data-id="{{$employe->id}}"
                                        class="btn btn-xs waves-effect waves-light openModal_one" data-animation="fadein"
                                        data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                        <i class="fe-paperclip"></i>
                                    </a>
                                    <a data-bs-toggle="modal" data-bs-target="#custom-modal-five" data-id="{{$employe->id}}"
                                        class="btn btn-xs waves-effect waves-light openModal_two" data-animation="fadein"
                                        data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                        <i class="fe-link"></i>
                                    </a>
                                    <button type="button" id="sa-warning" data-id="{{ $employe->id }}" class="btn btn-xs btn-danger waves-effect waves-light delete-button">
                                        <i class="fe-trash-2"></i>
                                    </button>
                                </div>
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-meu-start">
                                        <!-- item-->
                                        <a href="{{route('employe.profils', $employe->id)}}" class="dropdown-item">
                                            Profils
                                        </a>
                                        <!-- item-->
                                        <a href="{{route('employe.postes', $employe->id)}}" class="dropdown-item">
                                            Postes occupés
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Modal modifier -->
 <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Modifier</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form role="form" id="editForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="nom">Nom de l'employé</label>
                        <input id="nom" name="nom" type="text" class="form-control">
                        @error('nom')
                            {{$message}}
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="matricule">Matricule de l'employé</label>
                        <input id="matricule" name="matricule" type="text" class="form-control">
                        @error('matricule')
                            {{$message}}
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success waves-effect waves-light me-1" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light"
                        data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

                    <!-- Modal ajouter-->
    <div class="modal fade" id="custom-modal-two" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title" id="myCenterModalLabel">Ajouter un employé</h4>
                    <div class="d-flex gap-2">
                         <div class="fileupload add-new-plus">
                            <form role="form" id='upload-form' action="{{ route('employe.import') }}"  method="POST" enctype="multipart/form-data">
                                @csrf
                                <span><i class="fe-upload"></i></span>
                                <input type="file" name="fichier" id="file-input1" class="upload">
                            </form>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <form role="form" id="addForm" action="{{ route('employe.add') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="nom">Nom de l'employé</label>
                            <input id="nom" name="nom" type="text" class="form-control" value="{{old('nom')}}">
                            @error('nom')
                                {{$message}}
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="matricule">Matricule de l'employé</label>
                            <input id="matricule" name="matricule" type="text" class="form-control" value="{{old('matricule')}}">
                            @error('matricule')
                                {{$message}}
                            @enderror
                        </div>
                        {{-- <div class="mb-3">
                            <select class="form-control" data-toggle="select2" data-width="100%">
                                @foreach ($entite as $ent)
                                <option>Selectionner un poste</option>
                                <optgroup label="{{$ent->libelle_entite}}">
                                    @foreach ($ent->postes()->get() as $poste)
                                    <option value="AK">{{$poste->libelle_poste}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div> --}}
                        <button type="submit" class="btn btn-success waves-effect waves-light me-1" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->  

    <!-- Modal export -->
    <div class="modal fade" id="custom-modal-tree" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Exporter</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employe.export') }}" method="POST" class="d-flex flex-column aligns-items-center">
                        @csrf
                        <div class="d-flex flex-row mb-2">
                            <input type="text" class="form-control col-auto w-75" name="name" placeholder="Nom de fichier" >
                            <select name="extension" class="form-select col-auto w-25" aria-label="Default select example" >
                                <option value="xlsx" >.xlsx</option>
                                <option value="csv" >.csv</option>
                            </select>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <button class="btn btn-success" type="submit">Exporter</button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal assign poste -->
    <div class="modal fade" id="custom-modal-four" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Assigner un poste à <span id="empNom"></span> (<span id="empMat"></span>)</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="assignPostForm" method="POST" class="d-flex flex-column aligns-items-center">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="fin_fonct">Entrer la date d'arret de dernière fonction</label>
                            <input class="form-control" type="date" name="fin_fonct" id="fin_fonct">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="poste_input">Sélectionner un poste</label>
                            <select id="poste_input" class="form-control" data-toggle="select2" data-width="100%">
                                <option>--poste--</option>
                                @foreach ($entite as $ent)
                                <optgroup label="{{$ent->libelle_entite}}">
                                    @foreach ($ent->postes()->get() as $poste)
                                    <option value="{{$poste->id}}">{{$poste->libelle_poste}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="deb_fonct">Entrer la date de debut de sa fonction</label>
                            <input class="form-control" type="date" name="deb_fonct" id="deb_fonct">
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <button class="btn btn-success" type="submit" data-bs-dismiss="modal">Assigner</button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal assign profil -->
    <div class="modal fade" id="custom-modal-five" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Assigner profil à <span id="empName"></span> (<span id="empMatr"></span>)</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="assignProfForm" method="POST" class="d-flex flex-column aligns-items-center">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="profil_input">Sélectionner un profil</label>
                            <select id="profil_input" class="form-control" data-width="100%">
                                <option>--profils--</option>
                                @foreach ($apps as $app)
                                <optgroup label="{{$app->libelle_application}}">
                                    @foreach ($app->profils()->get() as $profil)
                                    <option value="{{$profil->id}}">{{$profil->libelle_profil}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ass_profil">Entrer la date d'assignation du profil</label>
                            <input class="form-control" type="date" name="ass_profil" id="ass_profil">
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <button class="btn btn-success" type="submit" data-bs-dismiss="modal">Assigner</button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Success Alert Modal -->
    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-success">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="dripicons-checkmark h1 text-white"></i>
                        <h4 class="mt-2 text-white">Reussi!</h4>
                        <p class="mt-3 text-white" id="success-alert-modal-message"></p>
                        <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continuer</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->                       

    <!-- Warning Alert Modal -->
    <div id="warning-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="dripicons-warning h1 text-warning"></i>
                        <h4 class="mt-2">Information incorrecte</h4>
                        <p class="mt-3" id="warning-alert-modal-message"></p>
                        <button type="button" class="btn btn-warning my-2" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Danger Alert Modal -->
    <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-danger">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="dripicons-wrong h1 text-white"></i>
                        <h4 class="mt-2 text-white">Erreur</h4>
                        <p class="mt-3 text-white" id="danger-alert-modal-message"></p>
                        <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    
@endsection

@section("script")
    <script src="{{asset("/assets/js/employe.js")}}"></script>
    <!-- third party js -->
    <script src="{{asset("/assets/libs/datatables.net/js/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-buttons/js/buttons.html5.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-buttons/js/buttons.flash.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-buttons/js/buttons.print.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js")}}"></script>
    <script src="{{asset("/assets/libs/datatables.net-select/js/dataTables.select.min.js")}}"></script>
    <script src="{{asset("/assets/libs/pdfmake/build/pdfmake.min.js")}}"></script>
    <script src="{{asset("/assets/libs/pdfmake/build/vfs_fonts.js")}}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{asset("/assets/libs/sweetalert2/sweetalert2.all.min.js")}}"></script>
    
    <!-- third party js ends -->

    <!-- Datatables init -->
    <script src="{{asset("/assets/js/pages/datatables.init.js")}}"></script>

    
@endsection

