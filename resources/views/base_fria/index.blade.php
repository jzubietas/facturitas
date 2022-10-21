@extends('adminlte::page')

@section('title', 'Base fría')

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection

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
    @include('base_fria.modal.exportar')
    @endcan
  </h1>
  @if($superasesor > 0)
  <br>
  <div class="bg-4">
    <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
      asesores con privilegios superiores: {{ $superasesor }}
    </h1>
  </div>
  @endif
@stop

@section('content')


            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                     
                    </div>
                    
                    <table class="table table-striped data-table" id="tablaserverside" style="width:100%">
                        <thead>
                            <tr>
                                <th>COD.</th>
                                <th>Nombre de cliente</th>
                                <th>Celular</th>
                                <th>Asesor</th>
                                <th width="100px">Acciones</th>
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
        {
            data: 'id', 
            name: 'id',
            render: function ( data, type, row, meta ) {
                if(row.id<10){
                    if(row.identificador==null){
                        return 'BF'+'000'+row.id;
                    }else{
                        return 'BF'+row.identificador+'000'+row.id;
                    }
                }else if(row.id<100){
                    if(row.identificador==null){
                        return 'BF'+'00'+row.id;
                    }else{
                        return 'BF'+row.identificador+'00'+row.id;
                    }
                }else if(row.id<1000){
                    if(row.identificador==null){
                        return 'BF'+'0'+row.id;
                    }else{
                        return 'BF'+row.identificador+'0'+row.id;
                    }
                }else{
                    return 'BF'+row.identificador+'000'+row.id;
                }
                //return row.id+' aa';
                //return ''+data+'';
            }},
        {
            data: 'nombre', 
            name: 'nombre',            
        },
        {data: 'celular', name: 'celular'},
        {
            data: 'identificador', 
            name: 'Asesor',            
        },
        {data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},
        ],
        language: {
        "decimal": "",
        "emptyTable": "No hay informaciÃ³n",
        "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      },

    });
});
</script>


@stop
