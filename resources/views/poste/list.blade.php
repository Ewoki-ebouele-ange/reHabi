@foreach ($postes as $poste)
<tr>
    <td>{{ $poste->id }}</td>
    <td class="text-truncate" style="max-width: 100px;">{{ $poste->code_poste }}</td>
    <td class="text-truncate" style="max-width: 100px;">{{ $poste->libelle_poste }}</td>
    <td class="d-flex">
        <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$poste->id}}"
            class="btn waves-effect waves-light openModal" data-animation="fadein"
            data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
            <i class="fe-edit"></i>
        </a>
        <button type="button" id="sa-warning" data-id="{{ $poste->id }}" class="btn btn-danger waves-effect waves-light delete-button">
            <i class="fe-trash-2"></i>
        </button>
    </td>
</tr>
@endforeach

<script src="{{asset("/assets/js/poste.js")}}"></script>
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
