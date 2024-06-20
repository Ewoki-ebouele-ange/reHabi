@foreach ($foncts as $fonct)
<tr>
    <td>{{ $fonct->id }}</td>
    <td class="text-truncate" style="max-width: 100px;">{{ $fonct->code_fonct }}</td>
    <td class="text-truncate" style="max-width: 100px;">{{ $fonct->libelle_fonct }}</td>
    <td class="d-flex">
        <a data-bs-toggle="modal" data-bs-target="#custom-modal" data-id="{{$fonct->id}}"
            class="btn waves-effect waves-light openModal" data-animation="fadein"
            data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">
            <i class="fe-edit"></i>
        </a>
        <form action="{{route('fonct.destroy', $fonct->id)}}" method="post" class="m-0">
            @csrf
            <button type="submit" class="btn btn-danger waves-effect waves-light">
                <i class="fe-trash-2"></i>
            </button>
        </form> 
    </td>
</tr>
@endforeach