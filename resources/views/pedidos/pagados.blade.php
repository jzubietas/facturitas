@extends('adminlte::page')

@section('title', 'Lista de pedidos pagados')

@section('content_header')
  <h1>Lista de pedidos pagados
    {{-- @can('pedidos.create')
      <a href="{{ route('pedidos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan --}}
    @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidospagadosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
      </div>
    </div>
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
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Cliente</th>
            <th scope="col">Razón social</th>
            <th scope="col">Asesor</th>
            <th scope="col">Fecha de registro</th>
            <th scope="col">Total (S/)</th>
            <th scope="col">Estado de pedido</th>
            <th scope="col">Estado de pago</th>
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
              <td>{{ $pedido->celulares }} - {{ $pedido->nombres }}</td>
              <td>{{ $pedido->empresas }}</td>
              <td>{{ $pedido->users }}</td>              
              <td>{{ $pedido->fecha }}</td>
              <td>@php echo number_format($pedido->total,2) @endphp</td>
              <td>{{ $pedido->condiciones }}</td>
              <td>{{ $pedido->condicion_pa }}</td>
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
                  <a href="" data-target="#modal-delete-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('pedidos.modal')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="../css/admin_custom.css">
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

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
    <script>
      Swal.fire(
        'Pedido {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
