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

@include('pagos.modals.revisarhistorial')

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

                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">ITEM</th>
                        <th scope="col">PEDIDO</th>
                        <th scope="col">CODIGO</th>
                        <th scope="col">ESTADO DE PAGO</th>
                        <th scope="col">ESTADO</th>
                        <th scope="col">MONTO TOTAL</th>
                        <th scope="col">ABONADO</th>
                        <th scope="col"><span style="color:red;">DIFERENCIA</span></th>
                        <th scope="col">Historial</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        $contPe = 0;
                        $sumPe = 0;
                        $sumPe2 = 0;
                      @endphp
                      @foreach ($pagoPedidos as $pagoPedido)
                        <tr>
                          <td>{{ $contPe + 1 }}</td>
                          <td>PED000{{ $pagoPedido->pedidos }}<input type="hidden" name="pedido_id[]" id="pedido_id" value="{{ $pagoPedido->pedidos }}"></td>
                          <td>{{ $pagoPedido->codigo }}</td>

                          @if($pago->condicion==\App\Models\Pago::ABONADO)
                              @if($pagoPedido->pagado == 1)
                              <td>ADELANTO ABONADO</td>
                              @else
                              <td>PAGADO ABONADO</td>
                              @endif
                          @elseif($pago->condicion==\App\Models\Pago::OBSERVADO)
                              @if($pagoPedido->pagado == 1)
                              <td>ADELANTO OBSERVADO</td>
                              @else
                              <td>PAGADO OBSERVADO</td>
                              @endif
                          @elseif($pago->condicion==\App\Models\Pago::PAGO)
                              @if($pagoPedido->pagado == 1)
                              <td>ADELANTO PAGO</td>
                              @else
                              <td>PAGADO PAGO</td>
                              @endif
                          @endif

                          <td>{{ $pagoPedido->condicion }}</td>
                          <td>{{ $pagoPedido->total }}</td>
                          <td><input type="hidden" name="pedido_id_abono[]" id="pedido_id_abono" value="{{ $pagoPedido->abono }}">{{ $pagoPedido->abono }}</td>
                          @if ($pagoPedido->total - $pagoPedido->abono < 3)
                            <td><span style="color:black;">{{ number_format($pagoPedido->total - $pagoPedido->abono, 2, '.', ' ') }}</span></td>
                          @else
                            <td><span style="color:red;">{{ number_format($pagoPedido->total - $pagoPedido->abono, 2, '.', ' ') }}</span></td>
                          @endif
                          <td>
                            <a href="" data-target="#modal-historial-pagos-pedido" data-toggle="modal" data-pedido="{{ $pagoPedido->codigo }}" data-pago="{{$pago->id}}"><button class="btn btn-danger btn-sm">Historial</button></a>
                          </td>
                        </tr>
                        @php
                          $sumPe = $sumPe + $pagoPedido->abono;
                          $sumPe2 = $sumPe2 + ($pagoPedido->total - $pagoPedido->abono );
                          $contPe++;
                        @endphp
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>TOTAL ABONADO</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo number_format($sumPe, 2, '.', ' ')?></td>
                        <td><span style="color:red;"><?php echo number_format($sumPe2, 2, '.', ' ')?></span></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

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
