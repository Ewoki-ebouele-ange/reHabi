@extends('layouts.index')

@section('link')
    
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_role" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Libelle du rôle</th>
                                <th>Date d'assignation</th>
                                <th>Application</th>
                                <th>Etat</th>
                                <th>Suspendre</th>
                            </tr>
                            </thead>
                            <tbody id="listRole">
                                @foreach ($foncts as $fonct)
                                <tr>
                                    <td>{{ $fonct->id }}</td>
                                    <td>{{ $fonct->code_fonct }}</td>
                                    <td>{{ $fonct->libelle_fonct }}</td>
                                    <td>{{ $fonct->profils()->first()->pivot->date_assignation }}</td>
                                    <td>{{ $app->libelle_application }}</td>
                                    @if (!$fonct->profils()->first()->pivot->date_suspension)
                                    <td><span class="badge bg-success">Non suspendu</span></td>
                                    @else
                                    <td><span class="badge bg-danger">Suspendu</span></td>
                                    @endif
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#custom-modal-six" data-id="{{$prof}}" data-fonct="{{$fonct->id}}"
                                            class="btn btn-xs waves-effect waves-light p-0 openModal_tree" data-animation="fadein"
                                            data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                                            <i class="mdi mdi-minus border border-danger bg-danger text-white h4 rounded-circle p-0 m-0"></i>
                                        </a>
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

    <!-- Modal assign poste -->
    <div class="modal fade" id="custom-modal-six" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Suspendre ce rôle de "<span id="prfLib"></span> (<span id="prfCode"></span>)"</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="susRole" method="POST" class="d-flex flex-column aligns-items-center">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="dt_sus">Entrer la date de suspension</label>
                            <input class="form-control" type="date" name="dt_sus" id="dt_sus">
                        </div>
                        {{-- <div class="mb-3">
                            <input class="form-control" type="text" name="fonct" id="fonct">
                        </div> --}}
                        <div class="d-flex flex-row justify-content-between">
                            <button class="btn btn-success" type="submit" data-bs-dismiss="modal">Suspendre</button>
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

@section('script')
    <script src="{{asset("/assets/js/role.js")}}"></script>
@endsection