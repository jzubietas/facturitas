@extends('adminlte::page')

@section('title', 'Agregar Rol')

@section('content_header')
  <h1>Agregar Rol</h1>
@stop

@section('content')

  <div class="card">

    {!! Form::model($role, ['route' => ['roles.update', $role], 'method' => 'put']) !!}
    
    @include('roles.partials.form')

    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
    </div>

    {!! Form::close() !!}

  </div>

@stop
