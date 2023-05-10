@extends('adminlte::page')

@section('title', 'Registro de Usuarios')

@section('content_header')
  <h1>Registrar Usuario</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::open(['route' => 'users.store', 'autocomplete' => 'off','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
    <div class="card-body">
      <div class="form-row">
        <div class="form-group col-lg-5">
          {!! Form::label('name', 'Nombres y Apellidos') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Ingrese nombres completos']) !!}
          @error('name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-2">
          {!! Form::label('role_id', 'Rol') !!}
          {{-- {!! Form::select('role_id', $roles, null, ['class' => 'form-control']) !!} --}}
          <select name="prole_id" id="prole_id" class="form-control">
            <option value=" ">----SELECCIONE----</option>
            @foreach ($roles as $role)
              <option value="{{ $role->id }}_{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
          </select>
          @error('prole_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
          {!! Form::hidden('role_id', null, ['id' => 'role_id']) !!}
          {!! Form::hidden('role_name', null, ['id' => 'role_name']) !!}
        </div>
        <div class="form-group col-lg-2">
          {!! Form::label('identificador', 'Identificador') !!}
          {!! Form::text('identificador', null, ['class' => 'form-control', 'id' => 'identificador','placeholder' => 'Ingrese identificador']) !!}
          @error('identificador')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-3">
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
          {!! Form::label('distrito', 'Distrito') !!}
          {!! Form::text('distrito', null, ['class' => 'form-control', 'id' => 'distrito']) !!}
          @error('distrito')
            <small class="text-danger">{{ $message }}</small>
          @enderror
          {!! Form::label('direccion', 'Dirección') !!}
          {!! Form::text('direccion', null, ['class' => 'form-control', 'id' => 'direccion']) !!}
          @error('direccion')
            <small class="text-danger">{{ $message }}</small>
          @enderror
          {!! Form::label('referencia', 'Referencia') !!}
          {!! Form::text('referencia', null, ['class' => 'form-control', 'id' => 'referencia']) !!}
          @error('referencia')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-6 border rounded card-body border-secondary" style="text-align: center">
          <h4 style="text-align: center;" class="mb-3"><b>FOTO DE PERFIL</b></h4>
          <div class="form-row">
            <div class="form-group col-lg-6" style="margin-top: 10%">
              {!! Form::label('imagen', 'Seleccione') !!}
              @csrf
              {!! Form::file('imagen', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-6">
              <div class="image-wrapper">
                <img id="picture" src="{{ asset('storage/users/logo_facturas.png') }}" alt="Imagen de perfil" height="300px" width="300px">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="border rounded card-body border-secondary">
      <h4 style="text-align: center;" class="mb-3"><b>ACCESO AL SISTEMA</b></h4>
      <div class="form-row">
        <div class="form-group col-lg-4">
          {!! Form::label('email', 'Correo Electrónico') !!}
          {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Ingrese correo electrónico']) !!}
          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-4">
          {!! Form::label('password', 'Contraseña') !!}
          {!! Form::password('password', ['class' => 'form-control', 'id' =>'password', 'placeholder' => 'Ingrese contraseña']) !!}
          @error('password')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group col-lg-4">
          {!! Form::label('confirm_password', 'Confirme contraseña') !!}
          {!! Form::password('confirm_password', ['class' => 'form-control', 'id' =>'confirm_password', 'placeholder' => 'Ingrese nuevamente contraseña']) !!}
          @error('confirm_password')
            <small class="text-danger">{{ $message }}</small>
          @enderror
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
      var prole_id = document.getElementById('prole_id').value;
      var role_id = document.getElementById('role_id').value;
      var identificador = document.getElementById('identificador').value;
      var celular = document.getElementById('celular').value;
      var provincia = document.getElementById('provincia').value;
      var distrito = document.getElementById('distrito').value;
      var direccion = document.getElementById('direccion').value;
      var referencia = document.getElementById('referencia').value;
      var email = document.getElementById('email').value;
      var password = document.getElementById('password').value;
      var confirm_password = document.getElementById('confirm_password').value;
      if (name == '') {
          Swal.fire(
            'Error',
            'Ingrese nombre del usuario a registrar',
            'warning'
          )
        }
        else if (prole_id == '') {
          Swal.fire(
            'Error',
            'Seleccione el rol que se le asignará al usuario',
            'warning'
          )
        }
        else if (role_id == '') {
          Swal.fire(
            'Error',
            'Seleccione el rol que se le asignará al usuario',
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
        else if (password == ''){
          Swal.fire(
            'Error',
            'Registre la contraseña del usuario',
            'warning'
          )
        }
        else if (confirm_password == ''){
          Swal.fire(
            'Error',
            'Confirme la contraseña del usuario',
            'warning'
          )
        }
        else {
          this.submit();
        }      
    }
  </script>
@endsection
