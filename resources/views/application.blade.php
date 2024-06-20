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
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h4 class="mt-0 header-title">Listes des applications</h4>
                                                <div class="d-flex gap-2">
                                                    <div class="fileupload add-new-plus">
                                                            <span data-bs-toggle="modal" data-bs-target="#custom-modal-tree"
                                                            data-animation="fadein"
                                                            data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                                                <i class="fe-download"></i>
                                                            </span>
                                                    </div>
                                                    {{-- <div class="fileupload add-new-plus">
                                                        <span data-bs-toggle="modal" data-bs-target="#custom-modal-four" data-animation="fadein"
                                                            data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a" data-bs-dismiss="modal">
                                                            <i class="fe-upload"></i>
                                                        </span>
                                                    </div> --}}
                                                    <div class="fileupload add-new-plus text-center">
                                                        <span>
                                                            <a data-bs-toggle="modal" data-bs-target="#custom-modal-two" data-animation="fadein"
                                                                data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                                                <i class="fe-plus-square"></i>
                                                            </a>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
        
                                            <table id="datatable" class="table table-striped table-bordered table-hover dt-responsive nowrap">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Libelle</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
        
        
                                                <tbody id="appsList">
                                                    @foreach ($applications as $app)
                                                <tr>
                                                    <td>{{ $app->id }}</td>
                                                    <td class="text-truncate" style="max-width: 100px;">{{ $app->code_application }}</td>
                                                    <td class="text-truncate" style="max-width: 100px;">{{ $app->libelle_application }}</td>
                                                    <td class="d-flex">
                                                        <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$app->id}}"
                                                            class="btn waves-effect waves-light openModal" data-animation="fadein"
                                                            data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                                            <i class="fe-edit"></i>
                                                        </a>
                                                        <button type="button" id="sa-warning" data-id="{{ $app->id }}" class="btn btn-danger waves-effect waves-light delete-button">
                                                            <i class="fe-trash-2"></i>
                                                        </button>
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
                    <h4 class="modal-title" id="myCenterModalLabel">Modifier l'application</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form role="form" id="editForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="code_application">Nom de l'employé</label>
                            <input id="code_application" name="code_application" type="text" class="form-control">
                            @error('code_application')
                                {{$message}}
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="libelle_application">Matricule de l'employé</label>
                            <input id="libelle_application" name="libelle_application" type="text" class="form-control">
                            @error('libelle_application')
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
    
                        <!-- Modal Ajouter-->
        <div class="modal fade" id="custom-modal-two" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h4 class="modal-title" id="myCenterModalLabel">Ajouter une application</h4>
                        <div class="d-flex gap-2">
                            <div class="fileupload add-new-plus">
                                <form role="form" id='upload-form' action="{{ route('application.import') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <span><i class="fe-upload"></i></span>
                                    <input type="file" name="fichier" id="file-input1" class="upload">
                                </form>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="addForm" action="{{ route('application.add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="code_application">Code</label>
                                <input id="code_application" name="code_application" type="text" class="form-control" value="{{old('code_application')}}">
                                @error('code_application')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="libelle_application">libelle</label>
                                <input id="libelle_application" name="libelle_application" type="text" class="form-control" value="{{old('libelle_application')}}">
                                @error('libelle_application')
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
    
        <!-- Modal export -->
        <div class="modal fade" id="custom-modal-tree" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myCenterModalLabel">Exporter</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('application.export') }}" method="POST" class="d-flex flex-column aligns-items-center" enctype="multipart/form-data" >
                            @csrf
                            <div class="d-flex flex-row mb-2">
                                <input type="text" class="form-control col-auto w-75" name="name" placeholder="Nom de fichier" >
                                <select name="extension" class="form-select col-auto w-25" aria-label="Default select example" >
                                    <option value="xlsx" >.xlsx</option>
                                    <option value="csv" >.csv</option>
                                </select>
                            </div>
                            <div class="d-flex flex-row justify-content-between">
                                <button class="btn btn-success" type="submit" data-bs-dismiss="modal">Exporter</button>
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
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
    <!-- third party js -->
    <script src="{{asset("/assets/libs/jquery/jquery.min.js")}}"></script>
    <script src="{{asset("/assets/libs/bootstrap/js/bootstrap.min.js")}}"></script>
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
    
    <!-- third party js ends -->

    <!-- Sweet Alerts js -->
    <script src="{{asset("/assets/libs/sweetalert2/sweetalert2.all.min.js")}}"></script>

    <!-- Datatables init -->
    <script src='{{asset("../assets/js/pages/datatables.init.js")}}'></script>

    <script src="{{asset("/assets/js/application.js")}}"></script>

    
@endsection
