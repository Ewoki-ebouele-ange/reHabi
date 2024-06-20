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
        <form action="{{route('poste.destroy', $poste->id)}}" method="post" class="m-0">
            @csrf
            <button type="submit" class="btn btn-danger waves-effect waves-light">
                <i class="fe-trash-2"></i>
            </button>
        </form> 
    </td>
</tr>
@endforeach