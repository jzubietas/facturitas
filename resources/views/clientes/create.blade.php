@extends('adminlte::page')

@section('title', 'Agregar Cliente')

@section('content_header')
  <h1>Agregar clientes</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::open(['route' => 'clientes.store', 'id' => 'formulario']) !!}
    @include('clientes.partials.form')
    <div class="card-footer" id="guardar">
      <button id="crear" type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
    </div>
    {!! Form::close() !!}
  </div>

@stop

@section('css')

@stop

@section('js')
  <script>
    //VALIDAR CAMPO CELULAR
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

    //VALIDAR CAMPOS ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });

    function validarFormulario(evento) {
      evento.preventDefault();
      var usuario = document.getElementById('user_id').value;
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
      
      if (usuario == '') {
          Swal.fire(
            'Error',
            'Seleccione asesor para el cliente',
            'warning'
          )
        }
        else if (nombre == '') {
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
        else if (provincia.toUpperCase() != ('lima').toUpperCase() && dni.length == 0){
          Swal.fire(
            'Error',
            'Clientes de provincia necesitan registrar el DNI',
            'warning'
          )
        }
        else if (provincia.toUpperCase() != ('lima').toUpperCase() && dni.length != 8){
          Swal.fire(
            'Error',
            'El DNI debe tener 8 dígitos',
            'warning'
          )
        }
        else if (porcentaje1 == '0' || porcentaje1 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: FISICO - sin banca',
            'warning'
          )
        }
        else if (porcentaje2 == '0' || porcentaje2 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: FISICO - sin banca',
            'warning'
          )
        }
        else if (porcentaje3 == '0' || porcentaje3 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: ELECTRONICA - banca',
            'warning'
          )
        }
        else if (porcentaje4 == '0' || porcentaje4 == ''){
          Swal.fire(
            'Error',
            'Registre el porcentaje: ELECTRONICA - sin banca',
            'warning'
          )
        }
        else {
          this.submit();
        }      
    }
  </script>
@stop
