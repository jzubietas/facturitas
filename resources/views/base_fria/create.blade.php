@extends('adminlte::page')

@section('title', 'Agregar Cliente')

@section('content_header')
  <h1>Agregar base fría</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::open(['route' => 'clientes.storebf', 'id' => 'formulario']) !!}
    <div class="border rounded card-body border-secondary">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('tipo', 'Tipo de cliente') !!}
              <input type="hidden" name="tipo" requerid value="0" class="form-control">
              <input type="text" name="cliente" value="Base fría" class="form-control" disabled>
            @error('tipo')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor*') !!}
            @if(Auth::user()->rol == "Asesor")
            {!! Form::text('muser_id', Auth::user()->identificador, ['class' => 'form-control', 'id' => 'muser_id', 'disabled']) !!}
            {!! Form::hidden('user_id', Auth::user()->id, ['class' => 'form-control', 'id' => 'user_id']) !!}
            @else
            {!! Form::select('user_id', $users, null, ['class' => 'form-control selectpicker border border-secondary', 'id' => 'user_id','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE USUARIO ----']) !!}
            @endif
            @error('user_id')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('nombre', 'Nombre') !!}
            {!! Form::text('nombre', null, ['class' => 'form-control', 'id' => 'nombre']) !!}
            @error('nombre')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('celular', 'Celular*') !!}
            {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
            @error('celular')
              <small class="text-danger" style="font-size: 16px">{{ $message }}</small>
            @enderror
          </div> 
        </div>  
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <a href="{{ route('basefria') }}" class="btn btn-danger"><i class="fas fa-times-circle"></i> Cancelar</a>
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
      var celular = document.getElementById('celular').value;
      if (usuario == '') {
          Swal.fire(
            'Error',
            'Seleccione asesor para el cliente',
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
        else {
          this.submit();
        }      
    }
  </script>
@stop
