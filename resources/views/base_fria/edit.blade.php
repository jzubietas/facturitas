@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
  <h1>Editar base fría</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::model($basefrium, ['route' => ['basefria.update', $basefrium], 'method' => 'put', 'id' => 'formulario']) !!}
    <div class="border rounded card-body border-secondary">
      <div class="card-body">
        <div class="form-row">

        <input type="hidden" name="id" id="id" class="form-control" value="{{ $basefrium->id }}">

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
            {!! Form::select('user_id', $users, null, ['class' => 'form-control selectpicker border border-secondary', 'id' => 'user_id', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE USUARIO ----']) !!}
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
            {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
            @error('celular')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div> 
        </div>  
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
          var fd2=new FormData();
          fd2.append("celular", celular);
          fd2.append("id", {{$basefrium->id }});

          $.ajax({
            data: fd2,
            processData: false,
            contentType: false,
            type: 'POST',
            url:"{{ route('cliente.edit.celularduplicado') }}",
            success:function(data)
            {
              console.log(data)
              if(data.html.status==true)
              {
                $("#formulario").trigger('submit');
              }else{
                Swal.fire(
                  'Error',
                  'Se encontro un error',
                  'warning'
                )
              }

            }
        })
      }
    }
  </script>
@stop
