@extends('adminlte::page')

@section('title', 'Lista de Articulos')

@section('content_header')
  <h1>Lista de Articulos
    @can('articulos.create')
      <a href="{{ route('articulos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
    @can('articulos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('articulosExcel') }}" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> PDF</a>
      </div>
    </div>
    @endcan
  </h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Código</th>
            <th scope="col">Nombre</th>
            <th scope="col">Categoría</th>
            <th scope="col">Descripción</th>
            <th scope="col">Stock</th>
            <th scope="col">Stock Mínimo</th>
            <th scope="col">Imagen</th>
            <th scope="col">Precio de compra</th>
            <th scope="col">Precio de venta</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($articulos as $articulo)
            <tr>
              <td>{{ $articulo->codigo }}</td>
              <td>{{ $articulo->nombre }}</td>
              <td>{{ $articulo->categoria }}</td>
              <td>{{ $articulo->descripcion }}</td>
              <td>{{ $articulo->stock }}</td>
              <td>{{ $articulo->stock_minimo }}</td>
              <td>
                <img src="{{ asset('storage/articulos/' . $articulo->imagen) }}" alt="{{ $articulo->nombre }}" height="100px" width="100px" class="img-thumbnail">
              </td>
              <td>{{ $articulo->precio_compra }}</td>
              <td>{{ $articulo->precio }}</td>
              <td>
                @can('articulos.edit')
                  <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                @endcan
                @can('articulos.destroy')
                  <a href="" data-target="#modal-delete-{{ $articulo->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('articulos.modal')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
  <script src="{{ asset('js/datatables.js') }}"></script>

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
    <script>
      Swal.fire(
        'Cliente {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
