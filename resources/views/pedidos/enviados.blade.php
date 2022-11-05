@extends('adminlte::page')

@section('title', 'Lista de pedidos entregados')

@section('content_header')
  <h1>Lista de pedidos entregados - ENVIOS
    {{-- <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosporatenderExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
      </div>
    </div> --}}
    {{-- @can('clientes.exportar') --}}
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos ENTREGADOS', 'key' => '2'])
    {{-- @endcan --}}
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
      <table cellspacing="5" cellpadding="5">
        <tbody>
          <tr>
            <td>Minimum date:</td>
            <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
            <td> </td>
            <td>Maximum date:</td>
            <td><input type="text" value={{ $dateMax }} id="max" name="max"  class="form-control"></td>
          </tr>
        </tbody>
      </table><br>
      <table id="tablaPrincipal" class="table table-striped table-responsive">
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Asesor</th>
            <th scope="col">Cliente</th>
            <th scope="col">Razón social</th>            
            <th scope="col">Fecha de registro</th>
            <th scope="col">Fecha de envio</th>
            <th scope="col">Fecha de entrega</th>
            <th scope="col">Foto 1</th>
            <th scope="col">Foto 2</th>
            <th scope="col">Estado de envio</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pedidos as $pedido)
            <tr>
              @if ($pedido->id < 10)
                <td>PED000{{ $pedido->id }}</td>
              @elseif($pedido->id < 100)
                <td>PED00{{ $pedido->id }}</td>
              @elseif($pedido->id < 1000)
                <td>PED0{{ $pedido->id }}</td>
              @else
                <td>PED{{ $pedido->id }}</td>
              @endif
              <td>{{ $pedido->codigos }}</td>
              <td>{{ $pedido->users }}</td> 
              <td>{{ $pedido->celulares }} - {{ $pedido->nombres }}</td>
              <td>{{ $pedido->empresas }}</td>                           
              <td>{{ $pedido->fecha_envio_doc }}</td>
              <td>{{ $pedido->fecha_envio_doc_fis }}</td>
              <td>{{ $pedido->fecha_recepcion }}</td>
              <td>
                @if($pedido->foto1 != null)
                  <a href="" data-target="#modal-imagen-{{ $pedido->id }}" data-toggle="modal">
                    <img src="{{ asset('storage/entregas/' . $pedido->foto1) }}" alt="{{ $pedido->foto1 }}" height="200px" width="200px" class="img-thumbnail">
                  </a>                
                  <p>

                    <a href="{{ route('envios.descargarimagen', $pedido->foto1) }}">Descargar </a>

                    @if (Auth::user()->rol == "Asesor")
                      <a href="" data-target="#modal-delete-foto1-{{ $pedido->id }}" data-toggle="modal">  <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></a>
                    @endif
                  </p>
                @elseif($pedido->envio == '3')
                  <span class="badge badge-dark">Sin envio</span>
                @else
                  <span class="badge badge-danger">Sin foto</span>
                @endif
              </td>
              <td>
                @if($pedido->foto2 != null)
                  <a href="" data-target="#modal-imagen2-{{ $pedido->id }}" data-toggle="modal">
                    <img src="{{ asset('storage/entregas/' . $pedido->foto2) }}" alt="{{ $pedido->foto2 }}" height="200px" width="200px" class="img-thumbnail">                
                  </a>
                  
                  
                  <p>
                    <a href="{{ route('envios.descargarimagen', $pedido->foto2) }}">Descargar </a>
                    @if (Auth::user()->rol == "Asesor")
                      <a href="" data-target="#modal-delete-foto2-{{ $pedido->id }}" data-toggle="modal">  <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></a>
                    @endif
                  </p>
                @elseif($pedido->envio == '3')
                  <span class="badge badge-dark">Sin envio</span>
                @else
                  <span class="badge badge-danger">Sin foto</span>
                @endif
              </td>
              <td>{{ $pedido->condicion_envio }}</td>
              <td>
                @can('envios.verenvio')
                  <a href="" data-target="#modal-verenvio-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-primary btn-sm">Ver</button></a>
                @endcan
                @can('envios.enviar')
                  @if($pedido->envio <> '3')
                  <a href="" data-target="#modal-editenviar-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-warning btn-sm">Editar Entrega</button></a>
                  @endif
                @endcan
              </td>
            </tr>
            @include('pedidos.modal.editenviar')
            {{-- @include('pedidos.modal.atender') --}}
            @include('pedidos.modal.verenvio')
            @include('pedidos.modal.imagen')
            @include('pedidos.modal.imagen2')
            @include('pedidos.modal.DeleteFoto1')
            @include('pedidos.modal.DeleteFoto2')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="../css/admin_custom.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">  

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
<script>
  $("#penvio_doc").change(mostrarValores1);

  function mostrarValores1() {
    $("#envio_doc").val($("#penvio_doc option:selected").text());
  }

  $("#pcondicion").change(mostrarValores2);

  function mostrarValores2() {
    $("#condicion").val($("#pcondicion option:selected").text());
  }
</script>

  <script src="{{ asset('js/datatables.js') }}"></script>

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
    <script>
      Swal.fire(
        'Pedido {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () { 
        
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");
                    // need to change str order before making  date obect since it uses a new Date("mm/dd/yyyy") format for short date.
                    var d = data[6].split("/");
                    var startDate = new Date(d[1]+ "/" +  d[0] +"/" + d[2]);

                    if (min == null && max == null) { return true; }
                    if (min == null && startDate <= max) { return true;}
                    if(max == null && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );

      
            $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"});
            $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy" });
            var table = $('#tablaPrincipal').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });
  </script>
  
@stop
