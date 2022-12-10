@extends('adminlte::page')

@section('title', 'Detalle de pedido')

@section('content_header')
  
    @if ($movimiento->id < 10)
      <h1>Movimiento: MOV000{{ $movimiento->id }}</h1>
    @elseif($movimiento->id < 100)
      <h1>Movimiento: MOV00{{ $movimiento->id }}</h1>
    @elseif($movimiento->id < 1000)
      <h1>Movimiento: MOV0{{ $movimiento->id }}</h1>
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

            <!---->
            @if ($movimiento->pago=='1')
                <h1>Pago Conciliado</h1><br>

                <h1>PAG{{$pago->users}}-{{$pago->cantidad_voucher}}{{$pago->cantidad_pedido}}-{{$pago->id}}</h1>

                <br><br>

                <h1>Detalle de Pago Conciliado</h1><br>
                
                <div class="form-row">
                  <div class="form-group col-lg-4">
                    <label for="id_ingresomaterial">Monto</label>
                    <p>{{ $detallepago->monto }}</p>
                  </div>
                  <div class="form-group col-lg-4">
                    <label for="id_ingresomaterial">Banco</label>
                    <p>{{ $detallepago->banco }}</p>
                  </div>
                  <div class="form-group col-lg-4">
                    <label for="id_ingresomaterial">Imagen</label>
                    <p><img src="{{ asset('storage/pagos/' . $detallepago->imagen) }}" alt="{{ $detallepago->imagen }}" height="200px" width="200px" class="img-thumbnail"></p>                    
                  </div>
                  <div class="form-group col-lg-4">
                    <label for="id_ingresomaterial">Fecha de Deposito</label>
                    <p>{{ $detallepago->fecha }}</p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label for="id_ingresomaterial">Titular</label>
                    <p>{{ $detallepago->titular }}</p>
                  </div>
                </div>
                
            @else

              <h1 style="color:red !important;">Movimiento no se encuentra conciliado</h1><br>
            @endif
            
            
          </div>
        </div>


        
        <br>
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
      </div>
    </div>
  
@stop

@section('css')
  {{--<link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')

  {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}

@stop