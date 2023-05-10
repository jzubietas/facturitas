@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
  <h1>Lista de pedidos por enviar - ENVIOS
    {{-- <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosporenviarExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos POR ENVIAR', 'key' => '1'])
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
      {{-- <table cellspacing="5" cellpadding="5">
        <tbody>
          <tr>
            <td>Destino:</td>
            <td>
              <select name="destino" id="destino" class="form-control">
                <option value="LIMA">LIMA</option>
                <option value="PROVINCIA">PROVINCIA</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table><br> --}}
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Item</th>
            <th scope="col" style="vertical-align: middle">Código</th>
            <th scope="col" style="vertical-align: middle">Asesor</th>
            <th scope="col" style="vertical-align: middle">Cliente</th>
            <th scope="col" style="vertical-align: middle">Razón social</th>
            <th scope="col" style="vertical-align: middle">Fecha de registro</th>
            <th scope="col" style="vertical-align: middle">Fecha de envio</th>
            <th scope="col" style="vertical-align: middle">Fecha de entrega</th>
            <th scope="col" style="vertical-align: middle">Destino</th>
            <th scope="col" style="vertical-align: middle">Dirección de envío</th>
            <th scope="col" style="vertical-align: middle">Estado de envio</th>
            <th scope="col" style="vertical-align: middle">Estado de sobre</th>
            <th scope="col" style="vertical-align: middle">Acciones</th>
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
              <td>{{ $pedido->destino }}</td>
              <td>
                {{-- @if($pedido->destino == 'LIMA') --}}
                  @if($pedido->direccion == '0')
                    <span class="badge badge-danger">REGISTRE DIRECCION</span>
                  @elseif($pedido->destino == 'LIMA')
                  <a href="" data-target="#modal-verdireccion-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>
                  {{-- <a href="" data-target="#modal-editdireccion-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-dark btn-sm"><i class="fas fa-pen"></i> Editar</button></a> --}}
                  @elseif($pedido->destino == 'PROVINCIA')
                    <span class="badge badge-info">ENVIO A PROVINCIA</span>
                  @else
                    <span class="badge badge-info">PROBLEMAS CON REGISTRO DE DESTINO</span>
                  @endif
                {{-- @else
                  <span class="badge badge-info">ENVIO A PROVINCIA</span>
                @endif --}}
              </td>
              <td>{{ $pedido->condicion_envio }}</td>
              <td>
                @if ($pedido->envio == '1')
                  <span class="badge badge-danger">Por confirmar recepcion</span>
                @else
                  <span class="badge badge-info">Recibido</span>
                @endif
              </td>
              <td>

                @if($ver_botones_accion > 0)
                  @can('envios.enviar')
                    <a href="" data-target="#modal-enviar-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-success btn-sm"><i class="fas fa-envelope"></i> Entregado</button></a>
                    @if($pedido->envio == '1')
                      <a href="" data-target="#modal-recibir-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-warning btn-sm"><i class="fas fa-check-circle"></i> Recibido</button></a>
                    @endif
                  @endcan
                @endif

                @if($pedido->destino == null && $pedido->direccion == '0' && ($pedido->envio)*1 > 0)
                  {{-- <a href="" data-target="#modal-destino-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-outline-dark btn-sm"><i class="fas fa-map"></i> Destino</button></a> --}}
                  <a href="{{ route('envios.createdireccion', $pedido) }}" class="btn btn-dark btn-sm"><i class="fas fa-map"></i> Destino</a>
                @endif
                {{-- @if($pedido->destino == 'LIMA' && $pedido->direccion == '0')
                  <a href="{{ route('envios.createdireccion', $pedido) }}" class="btn btn-dark btn-sm"><i class="fas fa-motorcycle"></i> Direccion</a>
                @endif
                @if($pedido->destino == 'PROVINCIA' && $pedido->direccion == '0')
                  <a href="#" class="btn btn-secondary btn-sm"><i class="fas fa-bus"></i> Provincia</a>
                @endif --}}
              </td>
            </tr>
            @include('pedidos.modal.enviar')
            @include('pedidos.modal.recibir')
            @include('pedidos.modal.direccion')
            @include('pedidos.modal.verdireccion')
            @include('pedidos.modal.editdireccion')
            @include('pedidos.modal.destino')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
  <style>
    img:hover{
      transform: scale(1.2)
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

  <script>

    //VALIDAR CAMPO CELULAR
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

    //VALIDAR ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario);
    });

    function validarFormulario(evento) {
      evento.preventDefault();
      var condicion = document.getElementById('condicion').value;
      var foto1 = document.getElementById('foto1').value;
      var pfoto1 = document.getElementById('pfoto1').value;
      var foto2 = document.getElementById('foto2').value;
      var pfoto2 = document.getElementById('pfoto2').value;

      if (condicion == 3) {
        if (foto1 == '' && pfoto1 == '') {
          Swal.fire(
            'Error',
            'Para dar por ENTREGADO debe registrar la foto 1',
            'warning'
          )
        }
        else if (foto2 == '' && pfoto2 == ''){
          Swal.fire(
            'Error',
            'Para dar por ENTREGADO debe registrar la foto 2',
            'warning'
          )
        }
        else {
        this.submit();
        }
      }
      else {
        this.submit();
      }
    }
  </script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {


            $("#destino", this).on( 'keyup change', function () {
              if ( table.column(i).search() !== this.value ) {
                  table
                      .column(8)
                      .search( this.value )
                      .draw();
                }
            } );

        });
  </script>

@stop
