@extends('adminlte::page')

@section('title', 'Lista de pedidos')

@section('content_header')
  <h1>Lista de pedidos
    @can('pedidos.create')
      <a href="{{ route('pedidos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
      {{-- <a href="" data-target="#modal-add-ruc" data-toggle="modal">(Agregar +)</a> --}}
    @endcan
    {{-- @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar Lista de pedidos', 'key' => '3'])    
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
      <table cellspacing="5" cellpadding="5" class="table-responsive">
        <tbody>
          <tr>
            <td>Fecha Minima:</td>
            <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
            <td> </td>
            <td>Fecha Máxima:</td>
            <td><input type="text" value={{ $dateMax }} id="max" name="max"  class="form-control"></td>
          </tr>
        </tbody>
      </table><br>
      <table id="tablaPrincipal" class="table table-striped table-responsive">{{-- display nowrap  --}}
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Cliente</th>
            <th scope="col">Razón social</th>
            <th scope="col">Asesor</th>
            <th scope="col">Fecha de registro</th>
            <th scope="col">Total (S/)</th>
            <th scope="col">Estado de pedido</th>
            <th scope="col">Estado de pago</th>
            <th scope="col">Estado de envío</th>
            <th scope="col">Estado</th>
            <th scope="col">Diferencia</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pedidos as $pedido)
            @if($pedido->estado == 1)
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
                <td>{{ $pedido->celulares }} - {{ $pedido->nombres }}</td>
                <td>{{ $pedido->empresas }}</td>
                <td>{{ $pedido->users }}</td>              
                <td>{{ $pedido->fecha }}</td>
                <td>@php echo number_format($pedido->total,2) @endphp</td>
                <td>{{ $pedido->condiciones }}</td>
                <td>{{ $pedido->condicion_pa }}</td>
                <td>{{ $pedido->condicion_envio }}</td>
                <td>@php echo '<span class="badge badge-success">Activo</span>';@endphp</td>
                @if($pedido->diferencia>0)<td style="background: #ca3a3a; color:#ffffff; text-align: center;font-weight: bold;">{{ $pedido->diferencia }}</td>
                @else<td style="background: #44c24b; text-align: center;font-weight: bold;">{{ $pedido->diferencia }}</td>
                @endif
                <td>
                  @can('pedidos.pedidosPDF')
                    <a href="{{ route('pedidosPDF', $pedido) }}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                  @endcan
                  @can('pedidos.show')
                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                  @endcan
                  @can('pedidos.edit')
                    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-sm">Editar</a>
                  @endcan
                  @can('pedidos.destroy')
                    <a href="" data-target="#modal-delete-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>
                  @endcan
                </td>
              </tr>
            @else
              <tr style="color:#fcd00e">
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
                <td>{{ $pedido->celulares }} - {{ $pedido->nombres }}</td>
                <td>{{ $pedido->empresas }}</td>
                <td>{{ $pedido->users }}</td>              
                <td>{{ $pedido->fecha }}</td>
                <td>@php echo number_format($pedido->total,2) @endphp</td>
                <td>Motivo: {{ $pedido->motivo }}</td>
                <td>Responsable: {{ $pedido->responsable }}</td>
                <td>{{ $pedido->condicion_envio }}</td>
                <td>@php echo '<span class="badge badge-danger">Anulado</span>';@endphp</td>
                @if($pedido->diferencia>0)<td style="background: #ca3a3a; color:#ffffff; text-align: center;font-weight: bold;">{{ $pedido->diferencia }}</td>
                @else<td style="background: #44c24b; text-align: center;font-weight: bold;">{{ $pedido->diferencia }}</td>
                @endif
                <td>
                  @can('pedidos.pedidosPDF')
                    <a href="{{ route('pedidosPDF', $pedido) }}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                  @endcan
                  @can('pedidos.show')
                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                  @endcan
                  @can('pedidos.edit')
                    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-sm">Editar</a>
                  @endcan
                  @can('pedidos.destroy')
                    <a href="" data-target="#modal-restaurar-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Restaurar</button></a>
                  @endcan
                </td>
              </tr>
            @endif
            @include('pedidos.modal')
            @include('pedidos.modal.restaurar')
          @endforeach
          @foreach ($pedidos2 as $pedido)
            @if($pedido->estado == 1)
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
                <td>{{ $pedido->celulares }} - {{ $pedido->nombres }}</td>
                <td>{{ $pedido->empresas }}</td>
                <td>{{ $pedido->users }}</td>              
                <td>{{ $pedido->fecha }}</td>
                <td>@php echo number_format($pedido->total,2) @endphp</td>
                <td>{{ $pedido->condiciones }}</td>
                <td>SIN PAGOS REGISTRADOS</td>
                <td>{{ $pedido->condicion_envio }}</td>
                <td>@php echo '<span class="badge badge-success">Activo</span>';@endphp</td>
                <td style="background: #ca3a3a; color: white; text-align: center;font-weight: bold;">@php echo number_format($pedido->total,2) @endphp</td>
                <td>
                  @can('pedidos.pedidosPDF')
                    <a href="{{ route('pedidosPDF', $pedido) }}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                  @endcan
                  @can('pedidos.show')
                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                  @endcan
                  @can('pedidos.edit')
                    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-sm">Editar</a>
                  @endcan
                  @can('pedidos.destroy')
                    <a href="" data-target="#modal-delete-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>
                  @endcan
                </td>
              </tr>
            @else
              <tr style="color:#fcd00e">
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
                <td>{{ $pedido->celulares }} - {{ $pedido->nombres }}</td>
                <td>{{ $pedido->empresas }}</td>
                <td>{{ $pedido->users }}</td>              
                <td>{{ $pedido->fecha }}</td>
                <td>@php echo number_format($pedido->total,2) @endphp</td>
                <td>Motivo: {{ $pedido->motivo }}</td>
                <td>Responsable: {{ $pedido->responsable }}</td>
                <td>{{ $pedido->condicion_envio }}</td>
                <td>@php echo '<span class="badge badge-danger">Anulado</span>';@endphp</td>
                <td style="background: #ca3a3a; color: white; text-align: center;font-weight: bold;">@php echo number_format($pedido->total,2) @endphp</td>
                <td>
                  @can('pedidos.pedidosPDF')
                    <a href="{{ route('pedidosPDF', $pedido) }}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                  @endcan
                  @can('pedidos.show')
                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                  @endcan
                  @can('pedidos.edit')
                    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-sm">Editar</a>
                  @endcan
                  @can('pedidos.destroy')
                    <a href="" data-target="#modal-restaurar-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Restaurar</button></a>
                  @endcan
                </td>
              </tr>
            @endif
            @include('pedidos.modal')
            @include('pedidos.modal.restaurar')
          @endforeach          
        </tbody>
      </table>
    </div>
  </div>
@stop

@section('css')
  {{-- <link rel="stylesheet" href="../css/admin_custom.css"> --}}
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
  <script src="{{ asset('js/datatables.js') }}"></script>

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado' || session('info') == 'restaurado')
    <script>
      Swal.fire(
        'Pedido {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  <script>
    //VALIDAR ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });

    function validarFormulario(evento) {
      evento.preventDefault();
      var motivo = document.getElementById('motivo').value;
      var responsable = document.getElementById('responsable').value;
   
      if (motivo.val().length < 1) {
        Swal.fire(
          'Error',
          'Ingrese el motivo para anular el pedido',
          'warning'
        )
      }
      else if (responsable == ''){
        Swal.fire(
          'Error',
          'Ingrese el responsable de la anulación',
          'warning'
        )
      }
      else {
      this.submit();
      }     
    }
  </script>

  <script>
    //VALIDAR CAMPO RUC
    function maxLengthCheck(object)
      {
        if (object.value.length > object.maxLength)
          object.value = object.value.slice(0, object.maxLength)
      }
      
    //VALIDAR ANTES DE ENVIAR 2
    document.addEventListener("DOMContentLoaded", function() {    
    var form = document.getElementById("formulario2")
      if(form)
      {
        form.addEventListener('submit', validarFormulario2); 
      }    
    });

    function validarFormulario2(evento) {
      evento.preventDefault();
      var agregarruc = document.getElementById('agregarruc').value;

      if (agregarruc == '') {
          Swal.fire(
            'Error',
            'Debe ingresar el número de RUC',
            'warning'
          )
      }
      else if (agregarruc.length < 11){
        Swal.fire(
            'Error',
            'El número de RUC debe tener 11 dígitos',
            'warning'
          )
      }
      else {
        this.submit();
      }
    }
  </script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>
    window.onload = function () {      
      $('#tablaPrincipal').DataTable().draw();
    }
  </script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () { 
        
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");
                    // need to change str order before making  date obect since it uses a new Date("mm/dd/yyyy") format for short date.
                    var d = data[5].split("/");
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
