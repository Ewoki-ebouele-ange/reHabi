@extends("layouts.index")

@section('link')
    <!-- Plugins css -->
    <link href="{{asset("/assets/libs/dropzone/min/dropzone.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/assets/libs/dropify/css/dropify.min.css")}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Fichier des employés</h4>
                        <p class="sub-header">
                            Le fichier suivant doit contenir les informations des employés (fichier reçu du capital humain)
                        </p>

                        <form role="form" id="dataForm" method="POST" action="{{route('addData.importEmploye')}}" enctype="multipart/form-data">
                            @csrf
                            <div action='/' class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                            data-upload-preview-template="#uploadPreviewTemplate">
                            <div class="fallback">
                                    <input name="fichier" type="file"/>
                                </div>

                                <div class="dz-message needsclick">
                                    <i class="h1 text-muted dripicons-cloud-upload"></i>
                                    <h3>Déposez le fichier ici ou cliquez pour télécharger.</h3>
                                    <span class="text-muted font-13">(This is just a demo dropzone. Selected files are
                                        <strong>not</strong> actually uploaded.)</span>
                                </div>
                            </div>
                        

                        <!-- Preview -->
                        <div class="dropzone-previews mt-3 mb-3" id="file-previews"></div>  
                        <button type="submit" class="btn-success waves-effect waves-light">Soumettre</button>

                        </form>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div><!-- end col -->
        </div>
        <!-- end row -->  

        <!-- file preview template -->
        <div class="d-none" id="uploadPreviewTemplate">
            <div class="card mt-1 mb-0 shadow-none border">
                <div class="p-2">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                        </div>
                        <div class="col ps-0">
                            <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                            <p class="mb-0" data-dz-size></p>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                <i class="dripicons-cross"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Dropify File Upload</h4>
                        <p class="sub-header">
                            Override your input files with style. Your so fresh input file — Default version.
                        </p>

                        <input type="file" data-plugins="dropify" data-height="300" />

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mt-3">
                                    <input type="file" data-plugins="dropify" data-default-file="../assets/images/small/img-2.jpg"  />
                                    <p class="text-muted text-center mt-2 mb-0">Default File</p>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mt-3">
                                    <input type="file" data-plugins="dropify" disabled="disabled"  />
                                    <p class="text-muted text-center mt-2 mb-0">Disabled the input</p>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mt-3">
                                    <input type="file" data-plugins="dropify" data-max-file-size="1M" />
                                    <p class="text-muted text-center mt-2 mb-0">Max File size</p>
                                </div>
                            </div>
                        </div> <!-- end row -->

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div><!-- end col -->
        </div>
        <!-- end row -->  
        
    </div> 
    <!-- container -->
@endsection

@section('script')
<script src="{{asset("/assets/js/addData.js")}}"></script>

    <!-- Plugins js -->
    <script src="{{asset("/assets/libs/dropzone/min/dropzone.min.js")}}"></script>
    <script src="{{asset("/assets/libs/dropify/js/dropify.min.js")}}"></script>

    <!-- Init js-->
    <script src="{{asset("/assets/js/pages/form-fileuploads.init.js")}}"></script>
@endsection
