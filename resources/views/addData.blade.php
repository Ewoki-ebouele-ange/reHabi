@extends("layouts.index")

@section('link')
    
    <link href="{{asset("/assets/libs/sweetalert2/sweetalert2.min.css")}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        @if(session('session'))
            <div>
                {{session('success')}}   
            </div>                       
        @endif

        <div class="row">
            <form role="form" id="dataForm" action="{{route('addData.importEmploye')}}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="mb-3">
                    <label for="fileinput" class="form-label">Veuillez entrer le fichier des employés</label>
                    <input type="file" id="fileinput" name="fichier" class="form-control">
                    <button type="submit" class="btn btn-success mt-2">Soumettre</button>
                </div>
            </form>
        </div>
        <!-- end row -->  

        <p>Entrer les fichiers des extractions du trimestre</p>
            <form id="extract" class="row" action="{{route('addData.importExtractions')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-6">
                    <div class="mb-3">
                        <input class="form-control" name="fichier1" type="file" id="inputGroupFile04">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" name="fichier2" type="file" id="inputGroupFile04">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" name="fichier3" type="file" id="inputGroupFile04">
                    </div>
                </div> <!-- end col -->
                <div class="col-lg-6">
                    <div class="mb-3">
                        <input class="form-control" name="fichier4"  type="file" id="inputGroupFile04">
                    </div>

                    <div class="mb-3">
                        <input class="form-control" name="fichier5" type="file" id="inputGroupFile04">
                    </div>

                    <div class="mb-3">
                        <input class="form-control" name="fichier6" type="file" id="inputGroupFile04">
                    </div>
                </div> <!-- end col -->
            <button type="submit" class="btn btn-success">Soumettre</button>
        </form>
    </div> 
    <!-- container -->


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

@section('script')
{{-- <script src="{{asset("/assets/js/addData.js")}}"></script> --}}

    <!-- Plugins js -->
    <script src="{{asset("/assets/libs/dropzone/min/dropzone.min.js")}}"></script>
    <script src="{{asset("/assets/libs/dropify/js/dropify.min.js")}}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{asset("/assets/libs/sweetalert2/sweetalert2.all.min.js")}}"></script>
    <!-- Init js-->
    <script src="{{asset("/assets/js/pages/form-fileuploads.init.js")}}"></script>


@endsection
