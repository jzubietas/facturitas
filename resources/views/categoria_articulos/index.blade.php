@extends('adminlte::page')

@section('title', 'Lista de Categoría de Artículos')

@section('content_header')
  <h1>Lista de Categoría de Artículos
    @can('categoria_articulos.create')
      <a href="{{ route('categoria_articulos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
  </h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Categoria</th>
            <th scope="col">Descripción</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categoria_articulos as $categoria)
            <tr>
              <td>CA00{{ $categoria->id }}</td>
              <td>{{ $categoria->nombre }}</td>
              <td>{{ $categoria->descripcion }}</td>
              <td>
                @can('categoria_articulos.edit')
                  <a href="{{ route('categoria_articulos.edit', $categoria) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                @endcan
                @can('categoria_articulos.destroy')
                  <a href="" data-target="#modal-delete-{{ $categoria->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('categoria_articulos.modal')
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
