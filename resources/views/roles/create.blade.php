@extends('adminlte::page')

@section('title', 'Agregar Rol')

@section('content_header')
  <h1>Agregar Rol</h1>
@stop

@section('content')

  <div class="card">

    {!! Form::open(['route' => 'roles.store']) !!}

    @include('roles.partials.form')

    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fas fa-times-circle"></i> ATRAS</a>
    </div>

    {!! Form::close() !!}

  </div>

@stop
