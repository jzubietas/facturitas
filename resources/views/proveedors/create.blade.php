@extends('adminlte::page')

@section('title', 'Agregar Proveedor')

@section('content_header')
  <h1>Agregar proveedor</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::open(['route' => 'proveedors.store']) !!}
    @include('proveedors.partials.form')
    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <a href="{{ route('proveedors.index') }}" class="btn btn-danger"><i class="fas fa-times-circle"></i> Cancelar</a>
    </div>
    {!! Form::close() !!}
  </div>

@stop

@section('css')

@stop

@section('js')
  <script>

  </script>
@stop
