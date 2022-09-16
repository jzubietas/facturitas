@extends('adminlte::page')

@section('title', 'Editar Artículo')

@section('content_header')
  <h1>Editar Artículo</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::model($articulo, ['route' => ['articulos.update', $articulo], 'method' => 'put', 'files'=>true]) !!}

    @include('articulos.partials.form')

    <div class="card-footer">
      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      <a href="{{ route('articulos.index') }}" class="btn btn-danger"><i class="fas fa-times-circle"></i> Cancelar</a>
    </div>
    {!! Form::close() !!}
  </div>

@stop

@section('css')

@stop

@section('js')
  <script>
    document.getElementById("imagen").addEventListener('change', cambiarImagen);

    function cambiarImagen(event){
    var file = event.target.files[0];

    var reader = new FileReader();
    reader.onload = (event) => {
        document.getElementById("picture").setAttribute('src', event.target.result);
    };

    reader.readAsDataURL(file);
    }
  </script>
@stop
