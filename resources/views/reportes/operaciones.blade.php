@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>REPORTES DE PEDIDOS REALIZADOS POR OPERACIONES <i><b>Ojo Celeste</b></i></h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header bg-primary">PEDIDOS</div>
        <div class="form-group m-0">
            <div class="row">
                <div class="form-group col-lg-12 m-0">
                    <div class="card mx-3 my-3">
                        {!! Form::open(['route' => ['pedidosoperacionesexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-lg-12" style="text-align: center">
                                    {!! Form::label('servicio_id', 'Complete sus parámetros') !!} <br><br>
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
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
