@extends('adminlte::page')

@section('title', 'Base fría')

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection


@section('content')

<div class="container">
   
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                     
                    </div>
                    
                    <table class="table table-bordered data-table" id="tablaserverside">
                        <thead>
                            <tr>
                                <th>COD.</th>
                                <th>Nombre de cliente</th>
                                <th>Celular</th>
                                <th width="100px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {

    $('#tablaserverside').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('basefriatabla') }}",
        columns: [
        {data: 'id', name: 'id'},
        {data: 'nombre', name: 'nombre'},
        {data: 'celular', name: 'celular'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>


@stop
