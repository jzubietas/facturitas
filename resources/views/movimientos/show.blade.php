@extends('adminlte::page')

@section('title', 'Detalle de pedido')

@section('content_header')
  
    @if ($movimiento->id < 10)
      <h1>Movimiento: MOV000{{ $movimiento->id }}</h1>
    @elseif($movimiento->id < 100)
      <h1>Movimiento: MOV00{{ $movimiento->id }}</h1>
    @elseif($movimiento->id < 1000)
      <h1>>Movimiento: MOV0{{ $movimiento->id }}</h1>
    @else
      <h1>Movimiento: MOV{{ $movimiento->id }}</h1>
    @endif
  
@stop

@section('content')
  
    <div class="card">
      <div class="card-body">
        <div class="border rounded card-body border-secondary" style="text-align: center">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Titular - Banco</label>
                <p>{{ $movimiento->titular }} - Banco: {{ $movimiento->banco }}</p>
              </div>
              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Tipo Movimiento</label>
                <p>{{ $movimiento->tipo }}</p>
              </div>
              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Importe</label>
                <p>{{ $movimiento->importe }}</p>
              </div>
              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Fecha de Movimiento</label>
                <p>{{ $movimiento->fecha }}</p>
              </div>

              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Fecha de registro</label>
                <p>{{ $movimiento->created_at }}</p>
              </div>
            </div>
            <div class="form-row">
            <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Estado</label>

                @if ($movimiento->estado == "1")
                  <p>Activo</p>
                @else
                  <p>Eliminado</p>
                @endif

                
              </div>
            </div>
            
          </div>
        </div>


        
        <br>
        <a href="{{ route('movimientos.index') }}" class="btn btn-danger btn-sm">Cancelar</a>
      </div>
    </div>
  
@stop

@section('css')
  {{--<link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')

  {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}

@stop