@extends('adminlte::page')

@section('title', 'Lista de Clientes')

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
        <a href="{{ route('clientesExcel') }}" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes</a>
        {{-- <a href="{{ route('clientespedidosExcel') }}" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Pedidos</a> --}}
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Pedidos</a>
      </div>
    </div>
    @include('clientes.modal.exportar')
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
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($clientes1 as $cliente)
            {{-- @if($cliente->cantidad > 0 && (($dateM*1)-($cliente->mes*1)) == 0 && (($dateY*1)-($cliente->anio*1)) == 0) --}}
            @if($cliente->cantidad > 0 && $dateM == $cliente->mes && $dateY == $cliente->anio)
            {{-- @if($cliente->cantidad > 0) --}}
              <tr style="background: #4ac4e2; color:#ffffff">
                @if ($cliente->id < 10)
                  <td>CL{{ $cliente->identificador }}000{{ $cliente->id }}</td>
                @elseif($cliente->id < 100)
                  <td>CL{{ $cliente->identificador }}00{{ $cliente->id }}</td>
                @elseif($cliente->id < 1000)
                  <td>CL{{ $cliente->identificador }}0{{ $cliente->id }}</td>
                @else
                  <td>CL{{ $cliente->identificador }}{{ $cliente->id }}</td>
                @endif
                <td>{{ $cliente->nombre }} - {{($dateM*1)-($cliente->mes*1)}}</td>
                <td>{{ $cliente->celular }}</td>
                <td>{{ $cliente->direccion }} - {{ $cliente->provincia }} - {{ $cliente->distrito }}</td>
                <td>{{ $cliente->user }}</td>
                <td>
                  @can('clientes.edit')
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                  @endcan
                  @can('clientes.destroy')
                    <a href="" data-target="#modal-delete-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                  @endcan
                </td>
              </tr>
            @elseif($cliente->cantidad > 0 && (($dateM*1)-($cliente->mes*1)) > 0 && (($dateY*1)-($cliente->anio*1)) == 0)
              {{-- <td style="background: #e73d3d">ABANDONO</td> --}}
              <tr style="background: #e73d3d">
                @if ($cliente->id < 10)
                  <td>CL{{ $cliente->identificador }}000{{ $cliente->id }}</td>
                @elseif($cliente->id < 100)
                  <td>CL{{ $cliente->identificador }}00{{ $cliente->id }}</td>
                @elseif($cliente->id < 1000)
                  <td>CL{{ $cliente->identificador }}0{{ $cliente->id }}</td>
                @else
                  <td>CL{{ $cliente->identificador }}{{ $cliente->id }}</td>
                @endif
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->celular }}</td>
                <td>{{ $cliente->direccion }} - {{ $cliente->provincia }} - {{ $cliente->distrito }}</td>
                <td>{{ $cliente->user }}</td>
                <td>
                  @can('clientes.edit')
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                  @endcan
                  @can('clientes.destroy')
                    <a href="" data-target="#modal-delete-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                  @endcan
                </td>
              </tr>
            @else
              <tr>
                @if ($cliente->id < 10)
                  <td>CL{{ $cliente->identificador }}000{{ $cliente->id }}</td>
                @elseif($cliente->id < 100)
                  <td>CL{{ $cliente->identificador }}00{{ $cliente->id }}</td>
                @elseif($cliente->id < 1000)
                  <td>CL{{ $cliente->identificador }}0{{ $cliente->id }}</td>
                @else
                  <td>CL{{ $cliente->identificador }}{{ $cliente->id }}</td>
                @endif
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->celular }}</td>
                <td>{{ $cliente->direccion }} - {{ $cliente->provincia }} - {{ $cliente->distrito }}</td>
                <td>{{ $cliente->user }}</td>
                <td>
                  @can('clientes.edit')
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                  @endcan
                  @can('clientes.destroy')
                    <a href="" data-target="#modal-delete-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                  @endcan
                </td>
              </tr>
            @endif            
            @include('clientes.modal')
          @endforeach
          @foreach ($clientes2 as $cliente)
            <tr>
              @if ($cliente->id < 10)
                <td>CL{{ $cliente->identificador }}000{{ $cliente->id }}</td>
              @elseif($cliente->id < 100)
                <td>CL{{ $cliente->identificador }}00{{ $cliente->id }}</td>
              @elseif($cliente->id < 1000)
                <td>CL{{ $cliente->identificador }}0{{ $cliente->id }}</td>
              @else
                <td>CL{{ $cliente->identificador }}{{ $cliente->id }}</td>
              @endif
              <td>{{ $cliente->nombre }}</td>
              <td>{{ $cliente->celular }}</td>
              <td>{{ $cliente->direccion }} - {{ $cliente->provincia }} - {{ $cliente->distrito }}</td>
              <td>{{ $cliente->user }}</td>
              <td>
                @can('clientes.edit')
                  <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                @endcan
                @can('clientes.destroy')
                  <a href="" data-target="#modal-delete-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('clientes.modal')
          @endforeach
          @foreach ($clientes3 as $cliente) {{-- CLIENTES SIN PEDIDOS --}}
            <tr>
              @if ($cliente->id < 10)
                <td>CL{{ $cliente->identificador }}000{{ $cliente->id }}</td>
              @elseif($cliente->id < 100)
                <td>CL{{ $cliente->identificador }}00{{ $cliente->id }}</td>
              @elseif($cliente->id < 1000)
                <td>CL{{ $cliente->identificador }}0{{ $cliente->id }}</td>
              @else
                <td>CL{{ $cliente->identificador }}{{ $cliente->id }}</td>
              @endif
              <td>{{ $cliente->nombre }}</td>
              <td>{{ $cliente->celular }}</td>
              <td>{{ $cliente->direccion }} - {{ $cliente->provincia }} - {{ $cliente->distrito }}</td>
              <td>{{ $cliente->user }}</td>
              <td>
                @can('clientes.edit')
                  <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                @endcan
                @can('clientes.destroy')
                  <a href="" data-target="#modal-delete-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('clientes.modal')
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
        'Cliente {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
