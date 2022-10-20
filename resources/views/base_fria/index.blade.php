@extends('adminlte::page')

@section('title', 'Base fría')

@section('content_header')
  <h1>Base fría
    @can('base_fria.create')
      <a href="{{ route('clientes.createbf') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
    @can('base_fria.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('basefriaExcel') }}" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Base fría - Total</a>
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Base fría por asesor</a>
      </div>
    </div>
    
    @endcan
  </h1>
  
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <table class="table table-striped basefria_table" style="width: 100%;">
      <thead>
            <tr>
              <th scope="col">COD.</th>
              <th scope="col">Nombre</th>
              <th scope="col">Celular</th>
              <th scope="col">Estado</th>
              <th scope="col">User</th>
              <th scope="col">Identificador</th>              
            </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
  <style>
    .bg-4{
      background: linear-gradient(to right, rgb(240, 152, 25), rgb(237, 222, 93));
    }

    .t-stroke {
        color: transparent;
        -moz-text-stroke-width: 2px;
        -webkit-text-stroke-width: 2px;
        -moz-text-stroke-color: #000000;
        -webkit-text-stroke-color: #ffffff;
    }

    .t-shadow-halftone2 {
        position: relative;
    }

    .t-shadow-halftone2::after {
        content: "AWESOME TEXT";
        font-size: 10rem;
        letter-spacing: 0px;
        background-size: 100%;
        -webkit-text-fill-color: transparent;
        -moz-text-fill-color: transparent;
        -webkit-background-clip: text;
        -moz-background-clip: text;
        -moz-text-stroke-width: 0;
        -webkit-text-stroke-width: 0;
        position: absolute;
        text-align: center;
        left: 0px;
        right: 0;
        top: 0px;
        z-index: -1;
        background-color: #ff4c00;
        transition: all 0.5s ease;
        text-shadow: 10px 2px #6ac7c2;
    }

  </style>
@stop

@section('scripts')

<script>
    $(document).ready(function() {
        $('.basefria_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{route('basefriadatatable.index')}}",
            dataType: 'json',
            type: "POST",
            columns: [{
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name',
                },
                {
                    data: 'hire_date',
                    name: 'hire_date'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number',
                },
                {
                    data: 'salary',
                    name: 'salary',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    searchable: false,
                    orderable: false
                }
            ],
        })
    })
</script>
@endsection