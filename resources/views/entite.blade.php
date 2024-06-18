@extends("layouts.index")

@section('link')
     <!-- third party css -->
     <link href="/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
     <link href="/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
     <link href="/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
     <link href="/assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
     <!-- third party css end -->
@endsection

@section('content')
<div class="container-fluid">
<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mt-0 header-title">Listes des entités</h4>
    
                                        <table id="datatable" class="table table-striped table-bordered table-hover dt-responsive nowrap">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Libelle</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
    
    
                                            <tbody>
                                                @foreach ($entites as $entite)
                                            <tr>
                                                <td>{{ $entite->id }}</td>
                                                <td>{{ $entite->code_entite }}</td>
                                                <td>{{ $entite->libelle_entite }}</td>
                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$entite->id}}" id="openModal"
                                                        class="btn btn-primary-danger waves-effect waves-light" data-animation="fadein"
                                                        data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                                        <i class="fe-edit"></i>
                                                    </a>
                                                    <form action="{{route('entite.destroy', $entite->id)}}" method="post" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger waves-effect waves-light">
                                                            <i class="fe-trash-2"></i>
                                                        </button>
                                                    </form> 
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                          {{$entites->links()}}
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
    <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Add New</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form role="form" id="editForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="code_entite">Code de l'entite</label>
                            <input id="code_entite" name="code_entite" type="text" class="form-control">
                            @error('code_entite')
                                {{$message}}
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="libelle_entite">Matricule de l'employé</label>
                            <input id="libelle_entite" name="libelle_entite" type="text" class="form-control">
                            @error('libelle_entite')
                                {{$message}}
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success waves-effect waves-light me-1" data-bs-toggle="modal" data-bs-target="#success-alert-modal">Save</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    @if(session()->get('success'))
        <!-- Success Alert Modal -->
        <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content modal-filled bg-success">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-checkmark h1 text-white"></i>
                            <h4 class="mt-2 text-white">Well Done!</h4>
                            <p class="mt-3 text-white">{{session()->get('success')}}</p>
                            <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->                       
    @endif

    
    
@endsection

@section("script")
    <!-- third party js -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    
    <!-- third party js ends -->

    <!-- Datatables init -->
    <script src="../assets/js/pages/datatables.init.js"></script>

    <script>

        var entiteId = null;

        $(document).ready(function() {
  $(document).on('click','#openModal', function (event) {
    event.preventDefault();
    entiteId = $(this).data('id');


    // Fetch item data
    $.get('/entite/' + entiteId + '/edit', function(data) {
      $('#editForm').attr('action', '/entite/' + entiteId);
      $('#code_entite').val(data.code_entite);
      $('#libelle_entite').val(data.libelle_entite)
    });
  });
});
    </script>

    
@endsection
