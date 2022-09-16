@extends('adminlte::page')

@section('title', 'Agregar Articulos')

@section('content_header')
  <h1>Agregar Articulo</h1>
@stop

@section('content')

  <div class="card">
    {!! Form::open(['route' => 'articulos.store','files'=>true]) !!}
    <div class="border rounded card-body border-secondary">
      <div class="form-row">
        <div class="form-group col-lg-6">
          {!! Form::label('categoria_articulo_id', 'Categoría del Artículo*') !!}
          {!! Form::select('categoria_articulo_id', $categorias, null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            @error('categoria_articulo_id')
              <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('codigo', 'Código*') !!}
          {!! Form::text('codigo', null, ['class' => 'form-control']) !!}
            @error('codigo')
              <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('nombre', 'Nombre*') !!}
          {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
            @error('nombre')
              <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('descripcion', 'Descripcion') !!}
          {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('stock', 'Stock*') !!}
          {!! Form::number('stock', null, ['class' => 'form-control']) !!}
            @error('stock')
              <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('stock_minimo', 'Stock mínimo del Artículo') !!}
          {!! Form::number('stock_minimo', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('precio_compra', 'Precio de compra') !!}
          {!! Form::number('precio_compra', null, ['class' => 'form-control','step'=>'0.01']) !!}
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('precio', 'Precio de venta*') !!}
          {!! Form::number('precio', null, ['class' => 'form-control','step'=>'0.01']) !!}
            @error('precio')
              <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group col-lg-6">
          {!! Form::label('imagen', 'Imagen') !!}
          {!! Form::file('imagen', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
        </div>
        <div class="form-group col-lg-6">
          <div class="image-wrapper">
            <img id="picture" src="{{asset('imagenes/LOGO_LIBRERIA.jpeg')}}" alt="Imagen de producto" height="300px" width="300px">
          </div>
        </div>
      </div>
    </div>
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
