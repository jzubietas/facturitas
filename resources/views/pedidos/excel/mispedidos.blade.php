<table>
  <thead>
    <tr>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">ITEM</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">ID</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">USUARIO QUE CREO EL PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">FECHA DE ÚLTIMA MODIFIACIÓN</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">USUARIO DE ÚLTIMA MODIFIACIÓN</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">ASESOR NOMBRE</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">ASESOR IDENTIFICADOR</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">FECHA DE PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">CODIGO DE PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">NOMBRE DE CLIENTE</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">TELEFONO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">RAZON SOCIAL</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">MES</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">RUC</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">CANTIDAD DE PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">TIPO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">PORCENTAJE (%)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">IMPORTE A PAGAR (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">COURIER (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">TOTAL POR PAGAR (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">CANT. DE COMPROBANTES (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">OPERARIO QUE EMITIO LA FACTURA</th>
      <th style="background-color: #FFFF00; text-align: center; color: #000000;">ESTADO DE PEDIDO</th>
      <th style="background-color: #FFFF00; text-align: center; color: #000000;">ESTADO DE SOBRE</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">FECHA DE REGISTRO DE PAGO</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">FECHA DE VOUCHER</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">IMPORTE PAGADO</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">BANCO</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">ESTADO DE PAGO</th>
      <th style="background-color: #FF0000; text-align: center; color: #000000;">DIFERENCIA</th>
      <th style="background-color: #92D050; text-align: center; color: #000000;">FECHA DE APROBACIÓN DE PAGO</th>
      <th></th>
      <th style="background-color: #BDD7EE; text-align: center; color: #000000;">Responsable</th>
      <th style="background-color: #BDD7EE; text-align: center; color: #000000;">Motivo de anulación</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        @if ($pedido->id < 10)
          <td>PED0000{{ $pedido->id }}</td>
        @elseif($pedido->id < 100)
          <td>PED000{{ $pedido->id }}</td>
        @elseif($pedido->id < 1000)
          <td>PED00{{ $pedido->id }}</td>
        @else
          <td>PED0{{ $pedido->id }}</td>
        @endif
        <td></td>{{-- CREADOR --}}
        <td>{{ $pedido->fecha_mod }}</td>{{-- FECHA MODIFICACION --}}
        <td>{{ $pedido->modificador }}</td>{{-- USUARIO MODIFICADOR --}}
        <td>{{ $pedido->asesor_nombre }}</td>{{-- ASESOR NOMBRE --}}
        <td>{{ $pedido->asesor_identificador }}</td>{{-- ASESOR IDENTIFICADOR --}}
        <td>{{ $pedido->fecha }}</td>{{-- FECHA PEDIDO --}}
        <td>{{ $pedido->codigos }}</td>{{-- CODIGO PEDIDO --}}
        <td>{{ $pedido->nombres }}</td>{{-- NOMBRE CLIENTE --}}
        <td>{{ $pedido->celulares }}</td>{{-- TELEFONO CLIENTE --}}
        <td>{{ $pedido->empresas }}</td>{{-- RAZON SOCIAL --}}
        <td></td>{{-- MES --}}
        <td></td>{{-- RUC --}}
        <td></td>{{-- CANTIDAD PEDIDO --}}
        <td></td>{{-- TIPO --}}
        <td></td>{{-- PORCENTAJE --}}
        <td></td>{{-- IMPORTE A PAGAR --}}
        <td>{{ $pedido->courier }}</td>{{-- COURIER --}}
        <td>{{ $pedido->total }}</td>{{-- TOTAL A PAGAR --}}
        <td></td>{{-- CANTIDAD COMPROBANTES --}}
        <td></td>{{-- OPERARIO EMITIO FACTURA --}}        
        <td>{{ $pedido->condiciones }}</td>{{-- ESTADO PEDIDO --}}
        <td></td>{{-- ESTADO SOBRE --}}
        <td></td>{{-- FECHA DE PAGO --}}
        <td></td>{{-- FECHA DE VOUCHER --}}
        <td></td>{{-- IMPORTE PAGADO --}}
        <td></td>{{-- BANCO --}}
        <td>{{ $pedido->condicion_pa }}</td>{{-- ESTADO PAGO --}}
        <td>{{ $pedido->diferencia }}</td>{{-- DIFERENCIA --}}
        <td></td>{{-- FECHA APROBACION PAGO --}}
        <td></td>
        <td></td>{{-- RESPONSABLE --}}
        <td></td>{{-- MOTIVO ANULACION --}}
      </tr>
      <?php $cont++; ?>
    @endforeach
    @foreach ($pedidos2 as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        @if ($pedido->id < 10)
          <td>PED0000{{ $pedido->id }}</td>
        @elseif($pedido->id < 100)
          <td>PED000{{ $pedido->id }}</td>
        @elseif($pedido->id < 1000)
          <td>PED00{{ $pedido->id }}</td>
        @else
          <td>PED0{{ $pedido->id }}</td>
        @endif
        <td></td>{{-- CREADOR --}}
        <td>{{ $pedido->fecha_mod }}</td>{{-- FECHA MODIFICACION --}}
        <td>{{ $pedido->modificador }}</td>{{-- USUARIO MODIFICADOR --}}
        <td></td>{{-- ASESOR NOMBRE --}}
        <td>{{ $pedido->users }}</td>{{-- ASESOR IDENTIFICADOR --}}
        <td>{{ $pedido->fecha }}</td>{{-- FECHA PEDIDO --}}
        <td>{{ $pedido->codigos }}</td>{{-- CODIGO PEDIDO --}}
        <td>{{ $pedido->nombres }}</td>{{-- NOMBRE CLIENTE --}}
        <td>{{ $pedido->celulares }}</td>{{-- TELEFONO CLIENTE --}}
        <td>{{ $pedido->empresas }}</td>{{-- RAZON SOCIAL --}}
        <td></td>{{-- MES --}}
        <td></td>{{-- RUC --}}
        <td></td>{{-- CANTIDAD PEDIDO --}}
        <td></td>{{-- TIPO --}}
        <td></td>{{-- PORCENTAJE --}}
        <td></td>{{-- IMPORTE A PAGAR --}}
        <td>{{ $pedido->courier }}</td>{{-- COURIER --}}
        <td>{{ $pedido->total }}</td>{{-- TOTAL A PAGAR --}}
        <td></td>{{-- CANTIDAD COMPROBANTES --}}
        <td></td>{{-- OPERARIO EMITIO FACTURA --}}        
        <td>{{ $pedido->condiciones }}</td>{{-- ESTADO PEDIDO --}}
        <td></td>{{-- ESTADO SOBRE --}}
        <td></td>{{-- FECHA DE PAGO --}}
        <td></td>{{-- FECHA DE VOUCHER --}}
        <td></td>{{-- IMPORTE PAGADO --}}
        <td></td>{{-- BANCO --}}
        <td>SIN PAGOS</td>{{-- ESTADO PAGO --}}
        <td>{{ $pedido->total }}</td>{{-- DIFERENCIA --}}
        <td></td>{{-- FECHA APROBACION PAGO --}}
        <td></td>
        <td></td>{{-- RESPONSABLE --}}
        <td></td>{{-- MOTIVO ANULACION --}}
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>