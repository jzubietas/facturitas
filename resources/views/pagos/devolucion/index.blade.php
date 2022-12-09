@extends('adminlte::page')

@section('title', 'Devoluciones')

@section('content_header')
    <h1>Devolver Pago a <b>{{$devolucion->cliente->nombre}}</b></h1>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card mt-4">
                    <div class="card-header">
                        Cliente <b>{{$devolucion->cliente->nombre}}</b>
                    </div>

                    <div class="card-body">
                        <div>
                            <ul class="list-group">
                                <li class="list-group-item">DNI: <b>{{$devolucion->cliente->dni}}</b></li>
                                <li class="list-group-item">Celular: <b>{{$devolucion->cliente->celular}}</b></li>
                                <li class="list-group-item">Provincia: <b>{{$devolucion->cliente->provincia}}</b></li>
                                <li class="list-group-item">Distrito: <b>{{$devolucion->cliente->distrito}}</b></li>
                                <li class="list-group-item">Dirección: <b>{{$devolucion->cliente->direccion}}</b></li>
                                <li class="list-group-item">Referencia: <b>{{$devolucion->cliente->referencia}}</b></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mt-4">
                    <div class="card-header">
                        Asesor <b>{{$devolucion->asesor->name}}</b>
                    </div>
                    <div class="card-body">
                        <div>
                            <ul class="list-group">
                                <li class="list-group-item">Correo: <b>{{$devolucion->asesor->email}}</b></li>
                                <li class="list-group-item">Celular: <b>{{$devolucion->asesor->celular}}</b></li>
                                <li class="list-group-item">Encargado: <b>{{optional($devolucion->asesor->encargado)->name}}</b></li>
                                <li class="list-group-item">Operario: <b>{{optional($devolucion->asesor->asesoroperario)->name}}</b></li>
                              
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
