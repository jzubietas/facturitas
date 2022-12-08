@extends('adminlte::page')

@section('title', 'Lista de Pagos')

@section('content_header')
  <h1>Lista de pagos APROBADOS
    @can('pagos.create')
      <a href="{{ route('pagos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
    {{-- @can('pagos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pagosaprobadosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @endcan --}}
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @include('pagos.modals.exportar', ['title' => 'Exportar Lista de pagos aprobados', 'key' => '5'])       
  </h1>

  <div class="form-group col-lg-6">
    
      <select name="asesores_aprobado" class="border form-control selectpicker border-secondary" id="asesores_aprobado" data-live-search="true">
        <option value="">---- SELECCIONE ASESOR ----</option>         
      </select>
  </div>

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
            <th scope="col">Codigo pedido</th>
            <th scope="col">Asesor</th>
            <th scope="col">Observacion</th>
            <th scope="col">Total cobro</th>
            <th scope="col">Total pagado</th>
            <th scope="col">Estado</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pagos as $pago)
            <tr>
              <td>PAG000{{ $pago->id }}</td>
              <td>{{ $pago->codigos }}</td>
              <td>{{ $pago->users }}</td>
              <td>{{ $pago->observacion }}</td>
              <td>@php echo number_format($pago->total_deuda,2) @endphp</td>
              <td>@php echo number_format($pago->total_pago,2) @endphp</td>
              <td>{{ $pago->condicion }}</td>
              <td>
                @can('administracion.show')
                  <a href="{{ route('pagos.show', $pago) }}" class="btn btn-info btn-sm">Ver</a>
                @endcan
                @can('administracion.editpago')
                <a href="{{ route('administracion.revisar', $pago) }}" class="btn btn-success btn-sm">Editar</a>
                @endcan
                @can('administracion.destroy')
                  <a href="" data-target="#modal-delete-{{ $pago->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm">Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('pagos.modals.modalDelete')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
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

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

{{--<script src="{{ asset('js/datatables.js') }}"></script>--}}

<script>
  $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      url: "{{ route('asesorespago') }}",
      method: 'GET',
      success: function(data) {
        console.log(data.html);
        $('#asesores_aprobado').html(data.html);
        $('#asesores_aprobado').selectpicker('refresh');
      }
    });

    $(document).on("change","#asesores_pago",function(){

      $('#tablaPrincipal').DataTable().ajax.reload();

    });

    $('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: {
          url: "{{ route('administracion.aprobadostabla') }}",
          data: function (d) {
            d.asesores = $("#asesores_aprobado").val();           
          },
        },
        columns: [
          {
              data: 'id', 
              name: 'id',
              render: function ( data, type, row, meta ) {             
                if(row.id<10){
                  return 'PAG000'+row.id;
                }else if(row.id<100){
                  return 'PAG00'+row.id;
                }else if(row.id<1000){
                  return 'PAG0'+row.id;
                }else{
                  return 'PAG'+row.id;
                } 
              }
          },
          {data: 'celular', name: 'celular'},
          {
            data: 'codigos'
            , name: 'codigos' 
            , render: function ( data, type, row, meta ) {
              var returndata='';
              var jsonArray=data.split(",");
              $.each(jsonArray, function(i, item) {
                  returndata+=item+'<br>';
              });
              return returndata;
            }
          },
          { data: 'fecha', name: 'fecha' },////asesor
          { data: 'users', name: 'users' },////asesor
          { data: 'observacion', name: 'observacion'},//observacion
          { data: 'total_deuda', name: 'total_deuda'},//total_deuda
          { data: 'total_pago', name: 'total_pago'},//total_pago
          {
            data: 'condicion', 
            name: 'condicion', 
            render: function ( data, type, row, meta ) {            
              return data;             
            }
          },//estado
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

  });

  @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
    <script>
      Swal.fire(
        'Pago {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
