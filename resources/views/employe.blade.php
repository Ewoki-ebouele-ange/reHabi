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
                                        <h4 class="mt-0 header-title">Buttons example</h4>
                                        <p class="text-muted font-14 mb-3">
                                            The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
                                        </p>
    
                                        <table id="datatable" class="table table-striped table-bordered table-hover dt-responsive nowrap">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
    
    
                                            <tbody>
                                                @foreach ($employes as $employe)
                                            <tr>
                                                <td>{{ $employe->id }}</td>
                                                <td>{{ $employe->nom }}</td>
                                                <td>{{ $employe->matricule }}</td>
                                                <td>
                                                    
                                                        <div class="text-center pt-2">
                                                            <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$employe->id}}" id="openModal"
                                                                class="btn btn-primary waves-effect waves-light" data-animation="fadein"
                                                                data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                                                <i class="mdi mdi-plus"></i> Add New
                                                            </a>
                                                        </div>
                                                
                                                    <form action="{{route('employe.destroy', $employe->id)}}" method="post" class="m-0">
                                                        @csrf
                                                        
                                                        {{-- <i class="fa-light fa-delete-left"></i>
                                                        <i class="fa-duotone fa-pen-to-square"></i> --}}
                                                        <button type="submit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                        </button>
                                                    </form> 
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                          {{$employes->links()}}
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

        var employeId = null;

        $(document).ready(function() {
  $(document).on('click','#openModal', function (event) {
    event.preventDefault();
    employeId = $(this).data('id');

    console.log(employeId);

    // Fetch item data
    $.get('/employe/' + employeId + '/edit', function(data) {
      $('#editForm').attr('action', '/employe/' + employeId);
      $('#nom').val(data.nom);
      $('#matricule').val(data.matricule)
    });
  });
});
    </script>

    
@endsection

