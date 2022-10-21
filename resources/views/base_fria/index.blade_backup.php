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
      <table id="tablaPrincipal" class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th scope="col">COD.</th>
            <th scope="col">Nombre</th>
            <th scope="col">Celular</th>
            <th scope="col">Asesor asignado</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($clientes as $cliente)
            <tr>
              @if ($cliente->id < 10)
                <td>BF{{ $cliente->identificador }}000{{ $cliente->id }}</td>
              @elseif($cliente->id < 100)
                <td>BF{{ $cliente->identificador }}00{{ $cliente->id }}</td>
              @elseif($cliente->id < 1000)
                <td>BF{{ $cliente->identificador }}0{{ $cliente->id }}</td>
              @else
                <td>BF{{ $cliente->identificador }}{{ $cliente->id }}</td>
              @endif
              <td>{{ $cliente->nombre }}</td>
              <td>{{ $cliente->celular }}</td>
              <td>{{ $cliente->user }}</td>
              <td>
                @can('base_fria.updatebf')
                  <a href="" data-target="#modal-convertir-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-info btn-sm">Convertir a cliente</button></a>
                @endcan
                @can('base_fria.edit')
                  <a href="{{ route('clientes.editbf', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                @endcan
                @can('clientes.destroy')
                  <a href="" data-target="#modal-delete-{{ $cliente->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('base_fria.modal')
            @include('base_fria.modal.convertir')
          @endforeach
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

  <script src="{{ asset('js/datatables.js') }}">
  </script>

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
    <script>
      Swal.fire(
        'Base fría {{ session('info') }} correctamente',
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
  </script>

  <script>
  //VALIDAR CAMPOS ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });

    function validarFormulario(evento) {
      evento.preventDefault();
      var nombre = document.getElementById('nombre').value;
      var dni = document.getElementById('dni').value;
      var celular = document.getElementById('celular').value;
      var provincia = document.getElementById('provincia').value;
      var distrito = document.getElementById('distrito').value;
      var direccion = document.getElementById('direccion').value;
      var referencia = document.getElementById('referencia').value;
      var porcentaje1 = document.getElementById('porcentaje1').value;
      var porcentaje2 = document.getElementById('porcentaje2').value;
      var porcentaje3 = document.getElementById('porcentaje3').value;
      var porcentaje4 = document.getElementById('porcentaje4').value;

        if (nombre == '') {
          Swal.fire(
            'Error',
            'Ingrese nombre de cliente',
            'warning'
          )
        }
        else if (celular == ''){
          Swal.fire(
            'Error',
            'Agregue número celular del cliente',
            'warning'
          )
        }
        else if (celular.length != 9){
          Swal.fire(
            'Error',
            'Número celular del cliente debe tener 9 dígitos',
            'warning'
          )
        }
        else if (provincia == ''){
          Swal.fire(
            'Error',
            'Registre la provincia del cliente',
            'warning'
          )
        }
        else if (distrito == ''){
          Swal.fire(
            'Error',
            'Registre el distrito del cliente',
            'warning'
          )
        }
        else if (direccion == ''){
          Swal.fire(
            'Error',
            'Registre la direccion del cliente',
            'warning'
          )
        }
        else if (referencia == ''){
          Swal.fire(
            'Error',
            'Registre la referencia del cliente',
            'warning'
          )
        }
        else if (dni.length == 0){//provincia.toUpperCase() != ('lima').toUpperCase() &&
          Swal.fire(
            'Error',
            'Clientes de provincia necesitan registrar el DNI',
            'warning'
          )
        }
        else if (dni.length != 8){//provincia.toUpperCase() != ('lima').toUpperCase() && 
          Swal.fire(
            'Error',
            'El DNI debe tener 8 dígitos',
            'warning'
          )
        }
        else if (porcentaje1 == '0' || porcentaje1 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: FISIO - sin banca',
            'warning'
          )
        }
        else if (porcentaje2 == '0' || porcentaje2 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: ELECTRONICA - sin banca',
            'warning'
          )
        }
        else if (porcentaje3 == '0' || porcentaje3 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: FISICO - banca',
            'warning'
          )
        }
        else if (porcentaje4 == '0' || porcentaje4 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: ELECTRONICA - banca',
            'warning'
          )
        }
        else {
          this.submit();
        }      
    }    
  </script>
@stop
