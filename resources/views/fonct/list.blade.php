<table id="datatable" class="table table-sm table-striped table-bordered table-hover dt-responsive nowrap">
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
                    class="btn btn-xs waves-effect waves-light openModal" data-animation="fadein"
                    data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
                    <i class="fe-edit"></i>
                </a>
                <button type="button" id="sa-warning" data-id="{{ $fonct->id }}" class="btn btn-xs btn-danger waves-effect waves-light delete-button">
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