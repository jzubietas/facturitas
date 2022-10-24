@extends('adminlte::page')

@section('title', 'Lista de Clientes')

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection

@section('content_header')
  <h1>Lista de clientes
    @can('clientes.create')
      <a href="{{ route('clientes.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
    @can('clientes.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        {{-- <a href="{{ route('clientesExcel') }}" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes</a> --}}
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes</a>
        {{-- <a href="{{ route('clientespedidosExcel') }}" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Pedidos</a> --}}
        <a href="" data-target="#modal-exportar2" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Pedidos</a>
      </div>
    </div>
    @include('clientes.modal.exportar') {{-- Modal Clientes - Pedidos --}}
    @include('clientes.modal.exportar2') {{-- Modal Clientes --}}
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
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">COD.</th>
            <th scope="col">Nombre</th>
            <th scope="col">Celular</th>
            <th scope="col">Direccion</th>
            <th scope="col">Asesor asignado</th>
            {{--<th scope="col">Cantidad</th>--}}
            {{--<th scope="col">Año actual</th>--}}
            {{--<th scope="col">Mes actual</th>--}}
            {{--<th scope="col">anio pedido</th>--}}
            {{--<th scope="col">mes pedido</th>--}}
            {{--<th scope="col">Deuda</th>--}}
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
  <style>

  .red {
    background-color: red !important;
  }
  
  .white {
    background-color: white !important;
  }
  
  .lighblue {
    background-color: #4ac4e2 !important;
  }
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

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {

    $('#tablaPrincipal').DataTable({
        processing: true,
        responsive:true,
        autowidth:true,
        serverSide: true,
        ajax: "{{ route('clientestabla') }}",
        columns: [
        {
            data: 'id', 
            name: 'id',
            render: function ( data, type, row, meta ) {
                if(row.id<10){
                    return 'CL'+row.identificador+'000'+row.id;
                }else if(row.id<100){
                    return 'CL'+row.identificador+'00'+row.id;
                }else if(row.id<1000){
                    return 'CL'+row.identificador+'00'+row.id;
                }else{
                    return 'CL'+row.identificador+''+row.id;
                }
            }
        },
        {data: 'nombre', name: 'nombre'},
        {data: 'celular', name: 'celular'},
        //{data: 'estado', name: 'estado'},
        //{data: 'user', name: 'user'},
        //{data: 'identificador', name: 'identificador'},
        //{data: 'provincia', name: 'provincia'},
        {
          data: 'direccion', 
          name: 'direccion',
          render: function ( data, type, row, meta ) {
            return row.direccion+' - '+row.provincia+' ('+row.distrito+')';
          }
        },
        //{data: 'direccion', name: 'direccion'},
        {data: 'identificador', name: 'identificador'},
        //{data: 'cantidad', name: 'cantidad'},
        //{data: 'dateY', name: 'dateY'},
        //{data: 'dateM', name: 'dateM'},
        //{data: 'anio', name: 'anio'},
        //{data: 'mes', name: 'mes'},
        //{data: 'deuda', name: 'deuda'},
        {data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},
        ],
        "createdRow": function( row, data, dataIndex){
            if(data["deuda"] == "0")
            {
                //sin deuda
                $(row).addClass('white');
            }else{
                if(data["dateY"] == data["anio"])
                {
                    if(data["dateM"] == data["mes"])
                    {
                        $(row).addClass('lighblue'); 
                    }else{
                        $(row).addClass('red'); 
                    }
                }else{
                     $(row).addClass('red'); 
                }
            }
            
            
        },
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

  <!--<script src="{{ asset('js/datatables.js') }}"></script>-->

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
    <script>
      Swal.fire(
        'Cliente {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
