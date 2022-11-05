@extends('adminlte::page')

@section('title', 'Lista de Usuarios Llamadas')

@section('content_header')
  <h1>Lista de usuario llamadas - Asignar JEFE y ASESOR
    {{-- @can('users.create')
      <a href="{{ route('users.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan --}}
  </h1>
  @if($superasesor > 0)
  <br>
  <div class="bg-4">
    <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
      asesores con privilegios superiores: {{ $superasesor }}
    </h1>
  </div>
  @endif
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">CODIGO</th>
            <th scope="col">NOMBRES Y APELLIDOS</th>
            <th scope="col">CORREO</th>
            <th scope="col">ENCARGADO</th>
            <th scope="col">OPERARIO</th>
            <th scope="col">ESTADO</th>
            <th scope="col">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr>
              <td>USER{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                @php
                if ($user->supervisor == null) {
                  echo '<span class="badge badge-danger">Por asignar</span>';  
                  
                } else {
                  echo 'USER'.$user->supervisor;
                  /* if($user->supervisor == $users->id){echo $user->name;} */
                }
                @endphp
              </td>
              <td>
                @php
                if ($user->operario == null) {
                  echo '<span class="badge badge-danger">Por asignar</span>';  
                  
                } else {
                  echo 'USER'.$user->operario;  
                }
                @endphp
              </td>
              <td>
                @php
                  if ($user->estado == '1') {
                      echo '<span class="badge badge-success">Activo</span>';
                  } else {
                      echo '<span class="badge badge-danger">Inactivo</span>';
                  }
                @endphp
              </td>
              <td>
                {{-- @can('users.asignarsupervisor') --}}
                  <a href="" data-target="#modal-asignarsupervisor-{{ $user->id }}" data-toggle="modal"><button class="btn btn-info btn-sm">Asignar encargado</button></a>
                {{-- @endcan --}}
                  <a href="" data-target="#modal-asignaroperario-{{ $user->id }}" data-toggle="modal"><button class="btn btn-warning btn-sm">Asignar operario</button></a>
              </td>
            </tr>
            @include('usuarios.modal.asignarsupervisor')
            @include('usuarios.modal.asignaroperario')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="../css/admin_custom.css">
  <style>
    .bg-4{
      background: linear-gradient(to right, rgb(240, 152, 25), rgb(237, 222, 93));
    }

    .t-stroke {
        color: transparent;
        -moz-text-stroke-width: 2px;
        -webkit-text-stroke-width: 2px;
        -moz-text-stroke-color: #000000;
        -webkit-text-stroke-color: #ffffff;
    }

    .t-shadow-halftone2 {
        position: relative;
    }

    .t-shadow-halftone2::after {
        content: "AWESOME TEXT";
        font-size: 10rem;
        letter-spacing: 0px;
        background-size: 100%;
        -webkit-text-fill-color: transparent;
        -moz-text-fill-color: transparent;
        -webkit-background-clip: text;
        -moz-background-clip: text;
        -moz-text-stroke-width: 0;
        -webkit-text-stroke-width: 0;
        position: absolute;
        text-align: center;
        left: 0px;
        right: 0;
        top: 0px;
        z-index: -1;
        background-color: #ff4c00;
        transition: all 0.5s ease;
        text-shadow: 10px 2px #6ac7c2;
    }

  </style>
@stop

@section('js')
  <script src="{{ asset('js/datatables.js') }}"></script>

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado' || session('info') == 'asignado')
    <script>
      Swal.fire(
        'Usuario {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif
@stop
