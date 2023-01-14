@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
  <h1>Editar clientes</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::model($cliente, ['route' => ['clientes.update', $cliente], 'method' => 'put', 'id' => 'formulario']) !!}

    <div class="border rounded card-body border-secondary">
      <div class="card-body">
        <div class="form-row">

          <input type="text" name="id" id="id" class="form-control" value="{{ $cliente->id }}">

          <div class="form-group col-lg-6">
            {!! Form::label('tipo', 'Tipo de cliente') !!}
              <input type="hidden" name="tipo" requerid value="1" class="form-control">
              <input type="text" name="cliente" value="Cliente" class="form-control" disabled>
            @error('tipo')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor*') !!}
            {!! Form::select('user_id', $users, null, ['class' => 'form-control selectpicker border border-secondary', 'id' => 'user_id','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE USUARIO ----']) !!}
            @error('user_id')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('nombre', 'Nombre*') !!}
            {!! Form::text('nombre', null, ['class' => 'form-control', 'id' => 'nombre']) !!}
            @error('nombre')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-3">
            {!! Form::label('dni', 'DNI') !!}
            {!! Form::number('dni', null, ['class' => 'form-control', 'id' => 'dni', 'min' =>'0', 'max' => '99999999', 'maxlength' => '8', 'oninput' => 'maxLengthCheck(this)']) !!}
            @error('dni')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-3">
            {!! Form::label('celular', 'Celular*') !!}
            {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
            @error('celular')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('provincia', 'Provincia*') !!}
            {!! Form::text('provincia', null, ['class' => 'form-control', 'id' => 'provincia']) !!}
            @error('provincia')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('distrito', 'Distrito*') !!}
            {!! Form::text('distrito', null, ['class' => 'form-control', 'id' => 'distrito']) !!}
            @error('distrito')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('direccion', 'Dirección*') !!}
            {!! Form::text('direccion', null, ['class' => 'form-control', 'id' => 'direccion']) !!}
            @error('direccion')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('referencia', 'Referencia*') !!}
            {!! Form::text('referencia', null, ['class' => 'form-control', 'id' => 'referencia']) !!}
            @error('referencia')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="border rounded card-body border-secondary">
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <h5 style="text-align: center"><b>Porcentajes</b></h5>
                </div>
                @foreach ($porcentajes as $porcentaje)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <label>{{ $porcentaje->nombre }}</label>
                        <input type="hidden" name="idporcentaje[]" value={{ $porcentaje->id }}>
                        <input type="text" step="0.1" name="porcentaje[]" id="porcentaje1" min="1.7" class="form-control porcentaje-banca decimal" value={{ $porcentaje->porcentaje}} required>
                      </div>
                    </div>

                @endforeach
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

@section('css')

@stop

@section('js')
    <script src="https://unpkg.com/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <script>
        $(document).ready(function() {

            new AutoNumeric('.decimal', 1.7);

        });

    </script>
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

    $(document).on('change blur','#porcentaje1,#porcentaje2,#porcentaje3,#porcentaje4' ,function(event){
        console.log(event.target.id);

        let val=parseFloat($(this).val());
        if(isNaN(val))
        {
            Swal.fire(
                'Ingrese un numero valido mayor o igual a 1.7',
                '',
                'warning'
            )
        }else{
            console.log("no es nan")
            if(val>=1.7)
            {

            }else{
                Swal.fire(
                    'Ingrese un numero mayor o igual a 1.7',
                    '',
                    'warning'
                )
                $(this).val(1.70)
            }
        }

    })



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
        var porcentaje_banca = document.getElementsByClassName('porcentaje-banca').value;

        var por = $('.porcentaje-banca').filter(function(e){
            var porcent = parseFloat($(this).val());
            return isNaN(porcent)||porcent < 1.5
        });
        if(por.length >0){
            Swal.fire(
                'Error',
                'El porcentaje debe ser mayor a 1.5',
                'warning'
            );
            return ;
        }
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
      else if (porcentaje_banca < '1.5'){
          Swal.fire(
              'Error',
              'El valor debe ser mayor a 1.5',
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
        else {
          //valida numero que no exista
          var fd2=new FormData();
          fd2.append("celular", celular);
          fd2.append("id", {{$cliente->id }});

          $.ajax({
            data: fd2,
            processData: false,
            contentType: false,
            type: 'POST',
            data:'json',
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
