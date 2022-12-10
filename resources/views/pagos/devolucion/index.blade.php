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
                    <div class="card-body">
                        {{Form::model($devolucion,['route'=>['pagos.devolucion.update',$devolucion],'files'=>true])}}
                        <div>
                            <ul class="list-group">
                                <li class="list-group-item">Monto a devolver: <b>{{$devolucion->amount_format}}</b></li>
                                <li class="list-group-item">Cliente: <b>{{$devolucion->cliente->nombre}}</b></li>
                                <li class="list-group-item">
                                    <div class="form-group col-lg-12">
                                        {!! Form::label('bank_destino', 'Banco de la cuenta del cliente') !!}
                                        {!! Form::select('bank_destino', $bancos , '0', ['required'=>'required','class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="form-group col-lg-12">
                                        {!! Form::label('bank_number', 'Numero de su cuenta bancaria') !!}
                                        {!! Form::input('text','bank_number', null,  ['class' => 'form-control','required'=>'required']) !!}
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="form-group col-lg-12">
                                        {!! Form::label('num_operacion', 'Numero de operacion o del voucher') !!}
                                        {!! Form::input('text','num_operacion', null , ['class' => 'form-control','required'=>'required']) !!}
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <b></b>
                                    <br>
                                    <div class="form-group col-lg-12">
                                        {!! Form::label('voucher', 'Adjuntar Constancia') !!}
                                        {!! Form::file('voucher', ['class' => 'form-control','accept'=>'image/*','required'=>'required']) !!}
                                    </div>
                                    <div>
                                        <img id="voucher_img" style="display: none;max-width: 450px">
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <button type="submit" class="btn btn-success">Marcar pago devuelto</button>
                                </li>
                            </ul>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div class="card-header">
                                Cliente <b>{{$devolucion->cliente->nombre}}</b>
                            </div>

                            <div class="card-body">
                                <div>
                                    <ul class="list-group">
                                        <li class="list-group-item">DNI: <b>{{$devolucion->cliente->dni}}</b></li>
                                        <li class="list-group-item">Celular: <b>{{$devolucion->cliente->celular}}</b>
                                        </li>
                                        <li class="list-group-item">Provincia:
                                            <b>{{$devolucion->cliente->provincia}}</b></li>
                                        <li class="list-group-item">Distrito: <b>{{$devolucion->cliente->distrito}}</b>
                                        </li>
                                        <li class="list-group-item">Dirección:
                                            <b>{{$devolucion->cliente->direccion}}</b></li>
                                        <li class="list-group-item">Referencia:
                                            <b>{{$devolucion->cliente->referencia}}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div class="card-header">
                                Asesor <b>{{$devolucion->asesor->name}}</b>
                            </div>
                            <div class="card-body">
                                <div>
                                    <ul class="list-group">
                                        <li class="list-group-item">Correo: <b>{{$devolucion->asesor->email}}</b></li>
                                        <li class="list-group-item">Celular: <b>{{$devolucion->asesor->celular}}</b>
                                        </li>
                                        <li class="list-group-item">Encargado:
                                            <b>{{optional($devolucion->asesor->encargado)->name}}</b></li>
                                        <li class="list-group-item">Operario:
                                            <b>{{optional($devolucion->asesor->asesoroperario)->name}}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        (function () {
            document.getElementById('voucher').onchange = evt => {
                const [file] = evt.target.files
                if (file) {
                    document.getElementById('voucher_img').src = URL.createObjectURL(file)
                    document.getElementById('voucher_img').style.display = 'block'
                }
            }
        })()
    </script>
@endsection
