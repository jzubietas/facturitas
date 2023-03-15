@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('content_header')
  <h1>PERFIL</h1>
@stop

@section('content')

  <div class="card">

    <form id="formperfil" name="formperfil">

      <div class="card-body">

          <div class="row">
            <div class="col-6">
              <h5 class="card-title">Información de Perfil</h5>
            </div>

            <div class="col-6">

              <div class="row">
                <div class="col-12">
                  {!! Form::label('name', 'Name') !!}
                  {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese nombres completos', 'enabled']) !!}
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  {!! Form::label('email', 'Email') !!}
                  {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Ingrese email', 'enabled']) !!}
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
      </div>
    </form>

  </div>


  <div class="card">

    <form id="formperfilclave" name="formperfilclave">

      <div class="card-body">

          <div class="row">
            <div class="col-6">
              <h5 class="card-title">Actualiza la contraseña</h5>
            </div>

            <div class="col-6">

              <div class="row">
                <div class="col-12">
                  {!! Form::label('pass', 'Contraseña') !!}
                  {!! Form::text('pass', null, ['class' => 'form-control', 'placeholder' => 'Ingrese contraseña', 'enabled']) !!}
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  {!! Form::label('passconfirm', 'Confirmar Contraseña') !!}
                  {!! Form::text('passconfirm', null, ['class' => 'form-control', 'placeholder' => 'Confirmar contraseña', 'enabled']) !!}
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Grabar</button>
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
      </div>
    </form>

  </div>

  <div class="card">

    <form id="formperfilavatar" name="formperfilavatar">

      <div class="card-body">

          <div class="row">
            <div class="col-6">
              <h5 class="card-title">Actualiza el avatar</h5>
            </div>

            <div class="col-6">

              <div class="row">
                <div class="col-12">
                  {!! Form::label('pass', 'Contraseña') !!}
                  {!! Form::text('pass', null, ['class' => 'form-control', 'placeholder' => 'Ingrese contraseña', 'enabled']) !!}
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  {!! Form::label('passconfirm', 'Confirmar Contraseña') !!}
                  {!! Form::text('passconfirm', null, ['class' => 'form-control', 'placeholder' => 'Confirmar contraseña', 'enabled']) !!}
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Grabar</button>
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
      </div>
    </form>

  </div>

@stop
@push('css')
    <style>

    </style>
@endpush
@section('js')

@endsection
