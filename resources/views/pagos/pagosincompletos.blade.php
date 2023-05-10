@extends('adminlte::page')

@section('title', 'Lista de Pagos')

@section('content_header')
    <h1>Lista mis de pagos incompletos: {{ Auth::user()->name }}
        @can('pagos.create')
            <a href="{{ route('pagos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan
        {{-- @can('pagos.exportar')
        <div class="float-right btn-group dropleft">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Exportar
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('pagosincompletosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
          </div>
        </div>
        @endcan --}}
        <div class="float-right btn-group dropleft">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img
                        src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
            </div>
        </div>
        @include('pagos.modals.exportar', ['title' => 'Exportar Lista de pagos incompletos', 'key' => '3'])
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
                    <th scope="col" class="align-middle">COD.</th>
                    <th scope="col" class="align-middle">Codigo pedido</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Observacion</th>
                    <th scope="col" class="align-middle">Total cobro</th>
                    <th scope="col" class="align-middle">Total pagado</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pagos as $pago)
                    <tr>
                        <td>PAG000{{ $pago->id }}</td>
                        <td>{{ $pago->codigos }}</td>
                        <td>{{ $pago->users }}</td>
                        <td>{{ $pago->observacion }}</td>
                        <td>@php echo number_format($pago->total_deuda,2) @endphp</td>
                        <td>@php echo number_format($pago->total_pago,2) @endphp</td>
                        <td>{{ $pago->condicion }}</td>
                        <td>
                            @can('pagos.show')
                                <a href="{{ route('pagos.show', $pago) }}" class="btn btn-info btn-sm">Ver</a>
                            @endcan
                            @can('pagos.edit')
                                <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning btn-sm">Editar</a>
                            @endcan
                            @can('pagos.destroy')
                                <a href="" data-target="#modal-delete-{{ $pago->id }}" data-toggle="modal">
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </a>
                            @endcan
                        </td>
                    </tr>
                    @include('pagos.modals.modalDelete')
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop

@push('css')
    <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
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


    </style>
@endpush

@section('js')

    <script src="{{ asset('js/datatables.js') }}"></script>

    @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
        <script>
            Swal.fire(
                'Pago {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

@stop
