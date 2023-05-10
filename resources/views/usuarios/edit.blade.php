@extends('adminlte::page')

@section('title', 'Actualizacion de Usuarios')

@section('content_header')
  <h1>Actualizar Usuario</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'id'=>'formulario', 'files'=>true]) !!}
    <div class="card-body">
      <div class="form-row">
        <div class="form-group col-lg-6">

          @if($mirol =='Administrador')
            {!! Form::label('name', 'Nombres y Apellidos') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Ingrese nombres completos']) !!}
          @else 
            {!! Form::label('name', 'Nombres y Apellidos') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Ingrese nombres completos']) !!}
          @endif

          @error('name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-3">
          {!! Form::label('role_id', 'Rol actual') !!}
          {!! Form::text('rol', null, ['class' => 'form-control', 'placeholder' => 'Ingrese nombres completos', 'disabled']) !!}
        </div>
        <div class="form-group col-lg-3">
          @if($mirol =='Administrador')
            {!! Form::label('role_id', 'Nuevo Rol') !!}
            <select name="prole_id" id="prole_id" class="form-control" >
              <option value=" ">----SELECCIONE----</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}_{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
          @else
            {!! Form::label('role_id', 'Nuevo Rol') !!}
            <select name="prole_id" id="prole_id" class="form-control" readonly disabled>
              <option value=" ">----SELECCIONE----</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}_{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
          @endif

          
          @error('prole_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
          {!! Form::hidden('role_id', null, ['id' => 'role_id']) !!}
          {!! Form::hidden('role_name', null, ['id' => 'role_name']) !!}
        </div>
        <div class="form-group col-lg-2">
        @if($mirol =='Administrador')
          {!! Form::label('identificador', 'Identificador') !!}
          {!! Form::text('identificador', null, ['class' => 'form-control', 'id' => 'identificador', 'placeholder' => 'Ingrese identificador']) !!}
        @else
          {!! Form::label('identificador', 'Identificador') !!}
          {!! Form::text('identificador', null, ['class' => 'form-control', 'id' => 'identificador', 'placeholder' => 'Ingrese identificador','readonly']) !!}
        @endif
          
          @error('identificador')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-5">
          @if($mirol =='Administrador')
            {!! Form::label('email', 'Correo Electrónico') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Ingrese correo electrónico']) !!}
          @else
            {!! Form::label('email', 'Correo Electrónico') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Ingrese correo electrónico','readonly']) !!}
          @endif
          
          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-5">
          {!! Form::label('celular', 'Celular personal') !!}
          {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
          @error('celular')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('provincia', 'Provincia') !!}
          {!! Form::text('provincia', null, ['class' => 'form-control', 'id' => 'provincia']) !!}
          @error('provincia')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('distrito', 'Distrito') !!}
          {!! Form::text('distrito', null, ['class' => 'form-control', 'id' => 'distrito']) !!}
          @error('distrito')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('direccion', 'Dirección') !!}
          {!! Form::text('direccion', null, ['class' => 'form-control', 'id' => 'direccion']) !!}
          @error('direccion')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('referencia', 'Referencia') !!}
          {!! Form::text('referencia', null, ['class' => 'form-control', 'id' => 'referencia']) !!}
          @error('referencia')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-12 border rounded card-body border-secondary" style="text-align: center">
          <h4 style="text-align: center;" class="mb-3"><b>FOTO DE PERFIL</b></h4>
          <div class="form-row">
            <div class="form-group col-lg-6" style="margin-top: 5%">
              {!! Form::label('imagen', 'Seleccione') !!}
              @csrf
              {!! Form::file('imagen', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-6">
              <div class="image-wrapper">
                <img id="picture" src="{{ asset('storage/users/'. $user->profile_photo_path ) }}" alt="Imagen de perfil" height="300px" width="300px">
              </div>
            </div>
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

@section('js')
  <script>
    $("#prole_id").change(mostrarValores);

    function mostrarValores() {
      datosArticulo = document.getElementById('prole_id').value.split('_');
      $("#role_id").val(datosArticulo[0]);
      $("#role_name").val(datosArticulo[1]);
    }

  //VALIDAR CAMPO CELULAR
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }
  //CAMBIAR IMAGEN
    document.getElementById("imagen").addEventListener('change', cambiarImagen);

    function cambiarImagen(event){
        var file = event.target.files[0];

        var reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById("picture").setAttribute('src', event.target.result);
        };

        reader.readAsDataURL(file);
    }

  //VALIDAR CAMPOS ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });

    function validarFormulario(evento) {
      evento.preventDefault();
      var name = document.getElementById('name').value;
      var identificador = document.getElementById('identificador').value;
      var celular = document.getElementById('celular').value;
      var provincia = document.getElementById('provincia').value;
      var distrito = document.getElementById('distrito').value;
      var direccion = document.getElementById('direccion').value;
      var referencia = document.getElementById('referencia').value;
      var email = document.getElementById('email').value;
      if (name == '') {
          Swal.fire(
            'Error',
            'Ingrese nombre del usuario a registrar',
            'warning'
          )
        }
        else if (identificador == '') {
          Swal.fire(
            'Error',
            'Agregue un identificador para este usuario',
            'warning'
          )
        }
        else if (celular == ''){
          Swal.fire(
            'Error',
            'Agregue número celular para este usuario',
            'warning'
          )
        }
        else if (celular.length != 9){
          Swal.fire(
            'Error',
            'Número celular del usuario debe tener 9 dígitos',
            'warning'
          )
        }
        else if (provincia == ''){
          Swal.fire(
            'Error',
            'Registre la provincia del usuario',
            'warning'
          )
        }
        else if (distrito == ''){
          Swal.fire(
            'Error',
            'Registre el distrito del usuario',
            'warning'
          )
        }
        else if (direccion == ''){
          Swal.fire(
            'Error',
            'Registre la dirección del usuario',
            'warning'
          )
        }
        else if (referencia == ''){
          Swal.fire(
            'Error',
            'Registre la referencia del usuario',
            'warning'
          )
        }
        else if (email == ''){
          Swal.fire(
            'Error',
            'Registre el email del usuario',
            'warning'
          )
        }
        else {
          this.submit();
        }      
    }
  </script>
@endsection
