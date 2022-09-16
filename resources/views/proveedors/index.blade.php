@extends('adminlte::page')

@section('title', 'Lista de Proveedores')

@section('content_header')
  <h1>Lista de proveedores
    @can('proveedors.create')
      <a href="{{ route('proveedors.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
  </h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">COD.</th>
            <th scope="col">Razón Social</th>
            <th scope="col">R.U.C. <i class="fas fa-question-circle fa-fw" title="Registro Único de Contribuyentes"></i></th>
            <th scope="col">Teléfono</th>
            <th scope="col">E-mail</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($proveedors as $proveedor)
            <tr>
              <td>PRO00{{ $proveedor->id }}</td>
              <td>{{ $proveedor->razon_social }}</td>
              <td>{{ $proveedor->ruc }}</td>
              <td>{{ $proveedor->telefono }}</td>
              <td>{{ $proveedor->email }}</td>
              <td>
                @can('proveedors.edit')
                  <a href="{{ route('proveedors.edit', $proveedor) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                @endcan
                @can('proveedors.destroy')
                  <a href="" data-target="#modal-delete-{{ $proveedor->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                @endcan
              </td>
            </tr>
            @include('proveedors.modal')
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
        'Proveedor {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
