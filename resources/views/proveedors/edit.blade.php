@extends('adminlte::page')

@section('title', 'Editar Proveedor')

@section('content_header')
  <h1>Editar proveedor</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::model($proveedor, ['route' => ['proveedors.update', $proveedor], 'method' => 'put']) !!}

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
