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
                        <h4 class="mt-0 header-title">Listes des fonctionnalités</h4>
                        <div class="d-flex gap-2">
                            <div class="fileupload add-new-plus">
                                    <span data-bs-toggle="modal" data-bs-target="#custom-modal-tree" data-animation="fadein"
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


                        <tbody id="fonctsList">
                            @foreach ($foncts as $fonct)
                        <tr>
                            <td>{{ $fonct->id }}</td>
                            <td class="text-truncate" style="max-width: 100px;">{{ $fonct->code_fonct }}</td>
                            <td class="text-truncate" style="max-width: 100px;">{{ $fonct->libelle_fonct }}</td>
                            <td class="d-flex justify-content-between align-items-center">
                                <div class="options">
                                    <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$fonct->id}}"
                                        class="btn waves-effect waves-light openModal" data-animation="fadein"
                                        data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                        <i class="fe-edit"></i>
                                    </a>
                                    <button type="button" id="sa-warning" data-id="{{ $fonct->id }}" class="btn btn-danger waves-effect waves-light delete-button">
                                        <i class="fe-trash-2"></i>
                                    </button>
                                </div>
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <!-- item-->
                                        <a href="{{route('fonct.module', $fonct->id)}}" class="dropdown-item">
                                            Module
                                        </a>
                                        <!-- item-->
                                        <a href="{{route('fonct.profils', $fonct->id)}}" class="dropdown-item">
                                            Profils
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
    
     <!-- Modal Modifier-->
     <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Modifier la fonctionnalité</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form role="form" id="editForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="code_fonct">Code</label>
                            <input id="code_fonct" name="code_fonct" type="text" class="form-control">
                            @error('code_fonct')
                                {{$message}}
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="libelle_fonct">Libelle</label>
                            <input id="libelle_fonct" name="libelle_fonct" type="text" class="form-control">
                            @error('libelle_fonct')
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
                        <h4 class="modal-title" id="myCenterModalLabel">Ajouter une fonctionnalité</h4>
                        <div class="d-flex gap-2">
                            <div class="fileupload add-new-plus">
                                <form role="form" id='upload-form' action="{{ route('fonct.import') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <span><i class="fe-upload"></i></span>
                                    <input type="file" name="fichier" id="file-input1" class="upload">
                                </form>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="addForm" action="{{ route('fonct.add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="code_fonct">Code</label>
                                <input id="code_fonct" name="code_fonct" type="text" class="form-control" value="{{old('code_fonct')}}">
                                @error('code_fonct')
                                    {{$message}}
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="libelle_fonct">libelle</label>
                                <input id="libelle_fonct" name="libelle_fonct" type="text" class="form-control" value="{{old('libelle_fonct')}}">
                                @error('libelle_fonct')
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
    
        {{-- <!-- Modal importer-->
        <div class="modal fade" id="custom-modal-four" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myCenterModalLabel">Ajouter une/plusieurs fonctionnalité(s)</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form method='POST' action="{{ route('fonct.import') }}" class="d-flex flex-column aligns-items-center">
                            @csrf
                            <input type="file" name="fichier" class="form-control col-auto w-100 mb-2">
                            <div class="d-flex flex-row justify-content-between">
                                <button class="btn btn-success" type="submit">Importer</button>
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->        --}}
    
        <!-- Modal 3 -->
        <div class="modal fade" id="custom-modal-tree" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myCenterModalLabel">Exporter</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('fonct.export') }}" method="POST" class="d-flex flex-column aligns-items-center">
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
    <script src="{{asset("/assets/js/fonct.js")}}"></script>
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
    
    <!-- third party js ends -->

    <!-- Sweet Alerts js -->
    <script src="{{asset("/assets/libs/sweetalert2/sweetalert2.all.min.js")}}"></script>

    <!-- Datatables init -->
    <script src="{{asset("../assets/js/pages/datatables.init.js")}}"></script>
    
@endsection
