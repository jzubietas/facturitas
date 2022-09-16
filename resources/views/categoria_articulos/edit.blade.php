@extends('adminlte::page')

@section('title', 'Editar Categoría de Artículo')

@section('content_header')
  <h1>Editar Categoría de Artículo</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::model($categoria_articulo, ['route' => ['categoria_articulos.update', $categoria_articulo], 'method' => 'put']) !!}

    @include('categoria_articulos.partials.form')

    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <a href="{{ route('categoria_articulos.index') }}" class="btn btn-danger"><i class="fas fa-times-circle"></i> Cancelar</a>
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
