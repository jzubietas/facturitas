@extends('adminlte::page')

@section('title', 'Lista de Usuarios')

@section('content_header')
    <h1>Lista de Usuarios
        @can('users.create')
            <a href="{{ route('users.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan

        @if($mirol=='Administrador')
            @can('clientes.exportar')
                <div class="float-right btn-group dropleft">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Exportar
                    </button>
                    <div class="dropdown-menu">
                        <a href="" data-target="#modal-exportar-2" data-toggle="modal" class="dropdown-item"
                           target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Usuarios</a>
                    </div>
                </div>
                @include('usuarios.modal.exportar2', ['title' => 'Exportar Lista de Usuarios', 'key' => '1'])
            @endcan
        @endif

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

    <div class="card" style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
            <table id="tablaPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">CODIGO</th>
                    <th scope="col" class="align-middle">NOMBRES Y APELLIDOS</th>
                    <th scope="col" class="align-middle">CORREO</th>
                    <th scope="col" class="align-middle">ROL</th>
                    <th scope="col" class="align-middle">ESTADO</th>
                    <th scope="col" class="align-middle">ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>USER{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->rol }}</td>
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
                            @can('users.reset')
                                <a href="" data-target="#modal-reset-{{ $user->id }}" data-toggle="modal">
                                    <button class="btn btn-info btn-sm">Resetear</button>
                                </a>
                                {{--<a href="" id="btn_resetear_clave" data-target="#modal-resetclave-usuario" data-toggle="modal" data-userid="{{ $user->id }}" ><button class="btn btn-info btn-sm"><i class="fas fa-researchgate"></i> Resetear</button></a>--}}
                            @endcan
                            @can('users.edit')
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-edit"></i> Editar</a>
                            @endcan
                            @can('users.destroy')
                                @if ($user->estado == '1')
                                    <a href="" data-target="#modal-desactivar-{{ $user->id }}" data-toggle="modal">
                                        <button class="btn btn-danger btn-sm"> Desactivar</button>
                                    </a>
                                @else
                                    <a href="" data-target="#modal-activar-{{ $user->id }}" data-toggle="modal">
                                        <button class="btn btn-success btn-sm"> Activar</button>
                                    </a>
                                @endif
                            @endcan
                        </td>
                    </tr>
                    @include('usuarios.modal.desactivar')
                    @include('usuarios.modal.activar')
                    @include('usuarios.modal.reset')
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop

@push('css')
    <link rel="stylesheet" href="../css/admin_custom.css">
    <style>
        .bg-4 {
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

        #tablaPrincipal {
            width: 100% !important;
        }

        #tablaPrincipal .sorting::before,
        #tablaPrincipal .sorting::after,
        #tablaPrincipal .sorting_desc::before,
        #tablaPrincipal .sorting_desc::after {
            top: 10px !important;
        }

        #tablaPrincipal tbody td {
            vertical-align: middle !important;
            text-align: start !important;
        }

        #tablaPrincipal tbody td:nth-child(5) span {
            padding: 4px !important;
            position: initial !important;
        }

        #tablaPrincipal tbody td:nth-child(5) {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }


    </style>
@endpush

@section('js')
    <script src="{{ asset('js/datatables.js') }}"></script>

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Usuario {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif
    <script>
        $(document).on("click", "#btn_resetear_clave", function (event) {
            var button = $(event.relatedTarget)
            var idunico = button.data('data-userid')
            console.log('reseteClave', button, 'idunico', idunico);
        });

        /*$(document).ready(function(){
          $('#modal-resetclave-usuario').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            console.log('reseteClave',button);
            var button = $(event.relatedTarget)
            var idunico = button.data('jefellamadas')
            $("#hiddenIdjefellamadas").val(idunico);
            if(idunico<10){
              idunico='USER000'+idunico;
            }else if(idunico<100){
              idunico= 'USER00'+idunico;
            }else if(idunico<1000){
              idunico='USERG0'+idunico;
            }else{
              idunico='USER'+idunico;
            }
            $(".textcode").html(idunico);
          });
        });*/


    </script>
@stop
