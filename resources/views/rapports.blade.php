@extends("layouts.index")

@section("link")
    
@endsection

@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="mt-0 header-title">Listes des rapports</h4>
                        
                    </div>
                    

                    <table id="datatable" class="table table-sm table-striped table-bordered table-hover dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Auteur</th>
                            <th>Rapport du</th>
                            <th>Aperçu</th>
                            <th>Télécharger</th>
                        </tr>
                        </thead>


                        <tbody id="employesList">
                            @foreach ($rapports as $rapport)
                        <tr>
                            <td>{{ $rapport->id }}</td>
                            <td>{{ $rapport->user->name }}</td>
                            <td class="text-truncate" style="max-width: 100px;">{{ $rapport->created_at }}</td>
                            <td class="text-truncate" style="max-width: 100px;">
                                <a href="{{route('rapports.show')}}" class="btn btn-xs waves-effect waves-light">
                                    <i class="fe-eye"></i>
                                </a>
                            </td>
                            <td class="text-truncate" style="max-width: 100px;">
                                <a href="{{route('rapports.download', $rapport->id)}}" class="btn btn-xs waves-effect waves-light">
                                    <i class="fe-download"></i>
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
@endsection

@section("script")
    
@endsection