@extends('adminlte::page')

@section('title', 'Detalle de pagos')

@section('content_header')
  <h1>DETALLE DEL <b>PAGO</b>: PAG000{{ $pagos->id }}</h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <table class="table table-active">
            <thead>
              <tr>
                <td>
                  <th scope="col" class="col-lg-2" style="text-align: right;">ASESOR:</th>
                  <th scope="col">{{ $pagos->users }}</th>
                </td>
                <td>
                  <th scope="col" class="col-lg-2" style="text-align: right;">CLIENTE:</th>
                  <th scope="col">{{ $pagos->celular }} - {{ $pagos->nombre }}</th>
                </td>
                <td>
                  <th scope="col" colspan="" class="col-lg-2" style="text-align: right;">ESTADO:</th>
                <th scope="col">{{ $pagos->condicion }}</th>
                </td>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <div class="form-group col-lg-12">
            <h3>PEDIDOS</h3>
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
                </tr>
              </thead>
              <tbody>
                @php
                  $contPe = 0;
                  $sumPe = 0;
                @endphp
                @foreach ($pagoPedidos as $pagoPedido)
                  <tr>
                    <td>{{ $contPe + 1 }}</td>
                    <td>PED000{{ $pagoPedido->pedidos }}</td>
                    <td>{{ $pagoPedido->codigo }}</td>
                      @if($pagoPedido->pagado == 1)
                      <td>ADELANTO</td>
                      @else
                      <td>PAGADO</td>
                      @endif
                    <td>{{ $pagoPedido->condicion }}</td>
                    <td>{{ $pagoPedido->total }}</td>
                    <td>{{ $pagoPedido->abono }}</td>
                  </tr>
                  @php
                    $sumPe = $sumPe + $pagoPedido->abono;
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
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <div class="form-group col-lg-12">
            <h3>PAGOS @if($pagos->saldo>0) - SALDO A FAVOR DEL CLIENTE: {{ $pagos->saldo }}@endif</h3>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ITEM</th>
                    <th scope="col">PAGO</th>
                    <th scope="col">BANCO</th>                
                    <th scope="col">MONTO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">CUENTA DESTINO</th>
                    <th scope="col">TITULAR</th>
                    <th scope="col">FECHA DEPOSITO</th>
                    <th scope="col">OBSERVACION</th>
                    <th scope="col">IMAGEN</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPa = 0;
                    $sumPa = 0;
                  @endphp
                  @foreach ($detallePagos as $detallePago)
                    <tr>
                      <td>{{ $contPa + 1 }}</td>
                      <td>PAG000{{ $detallePago->id }}</td>
                      <td>{{ $detallePago->banco }}</td>                  
                      <td>@php echo number_format($detallePago->monto,2) @endphp</td>
                      <td>{{ $detallePago->fecha }}</td>
                      <td>{{ $detallePago->cuenta }}</td>                  
                      <td>{{ $detallePago->titular }}</td>
                      <td>{{ $detallePago->fecha_deposito }}</td>
                      <td>{{ $detallePago->observacion }}</td>
                      <td><a href="" data-target="#modal-imagen-{{ $detallePago->id }}" data-toggle="modal">
                          <img src="{{ asset('storage/pagos/' . $detallePago->imagen) }}" alt="{{ $detallePago->imagen }}" height="200px" width="200px" class="img-thumbnail"></a>
                        <p><br><a href="{{ route('pagos.descargarimagen', $detallePago->imagen) }}"><button type="button" class="btn btn-secondary"> Descargar</button></a></p>
                      </td>
                    </tr>
                    @php
                      $sumPa = $sumPa + $detallePago->monto;
                      $contPa++;
                    @endphp
                    @include('pagos.modals.modalimagen')
                  @endforeach
                </tbody>
                <tfoot>
                  <th style="text-align: center">TOTAL</th>
                  <th></th>
                  <th></th>
                  <th><h4><?php echo number_format($sumPa, 2, '.', ' ')?></h4></th>
                  <th></th>  
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      @if (Auth::user()->rol == "Asesor")
        <a href="{{ route('pagos.mispagos') }}" class="btn btn-danger">Volver</a>
      @else
        <a href="{{ route('pagos.index') }}" class="btn btn-danger">Volver</a>
      @endif
    </div>
  </div>  
@stop

@section('js')

  <script src="{{ asset('js/datatables.js') }}"></script>

@stop
