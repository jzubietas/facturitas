@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Analisis<i><b>Ojo Celeste</b></i></h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header bg-primary">
            PEDIDOSS {{ $mes_month }}  {{-- $mes_anio --}}   {{-- $mes_mes --}}
            <div class="float-right btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    Exportar
                </button>
                <div class="dropdown-menu">
                    <a href="" data-target="#modal-exportar-unico" data-toggle="modal" class="dropdown-item"
                       target="blank_"><img
                            src="{{ asset('imagenes/icon-excel.png') }}"> Analisis</a>
                    {{--<a href="" data-target="#modal-exportar-v2" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Situacion</a>--}}
                </div>
            </div>
            @include('reportes.modal.exportar_unico', ['title' => 'Exportar Analisis', 'key' => '1'])
        </div>
        <div class="form-group m-0">
            <div class="row">
                <div class="form-group col-lg-12 m-0">
                    <div class="card mx-3 my-3">
                        <div class="card-body">
                            <div class="row ">
                                @foreach ($_pedidos_mes_pasado as $pedido)
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <h5> {{ $pedido->name }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="card">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <span
                                                                class="badge badge-light">RECUPERADO.RECIENTE</span><br>
                                                            <span
                                                                class="badge badge-secondary">{{ $pedido->recuperado_reciente }}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <span
                                                                class="badge badge-light">RECUPERADO.ABANDONO</span><br>
                                                            <span
                                                                class="badge badge-secondary">{{ $pedido->recuperado_abandono }}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <span class="badge badge-light">NUEVO</span><br>
                                                            <span
                                                                class="badge badge-secondary">{{ $pedido->nuevo }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-row d-none">
                                <div class="form-group col-lg-12" style="text-align: center">
                                    {!! Form::label('servicio_id', 'Complssete sus parámetros') !!} <br><br>
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <label>Fecha inicial&nbsp;</label>
                                            {!! Form::date('desde', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Fecha final&nbsp;</label>
                                            {!! Form::date('hasta', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-none">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
