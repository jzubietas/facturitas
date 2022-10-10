<table>
  <thead>
    <tr>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">ITEM</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">ID</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">USUARIO QUE CREO EL PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">FECHA DE ÚLTIMA MODIFIACIÓN</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000;">USUARIO DE ÚLTIMA MODIFICACIÓN</th>
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
      <th style="background-color: #FFC000; text-align: center; color: #000000;">FECHA DE CANCELACION DE PAGO</th>
      {{-- <th style="background-color: #FFC000; text-align: center; color: #000000;">FECHA DE VOUCHER</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">IMPORTE PAGADO</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000;">BANCO</th> --}}
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
        <td>{{ $pedido->creador }}</td>{{-- CREADOR --}}
        <td>{{ $pedido->fecha_mod }}</td>{{-- FECHA MODIFICACION --}}
        <td>{{ $pedido->modificador }}</td>{{-- USUARIO MODIFICADOR --}}
        <td>{{ $pedido->asesor_nombre }}</td>{{-- ASESOR NOMBRE --}}
        <td>{{ $pedido->asesor_identificador }}</td>{{-- ASESOR IDENTIFICADOR --}}
        <td>{{ $pedido->fecha }}</td>{{-- FECHA PEDIDO --}}
        <td>{{ $pedido->codigos }}</td>{{-- CODIGO PEDIDO --}}
        <td>{{ $pedido->nombres }}</td>{{-- NOMBRE CLIENTE --}}
        <td>{{ $pedido->celulares }}</td>{{-- TELEFONO CLIENTE --}}
        <td>{{ $pedido->empresas }}</td>{{-- RAZON SOCIAL --}}
        <td>{{ $pedido->mes }}</td>{{-- MES --}}
        <td>{{ $pedido->ruc }}</td>{{-- RUC --}}
        <td>{{ $pedido->cantidad }}</td>{{-- CANTIDAD PEDIDO --}}
        <td>{{ $pedido->tipo }}</td>{{-- TIPO --}}
        <td>{{ $pedido->porcentaje }}</td>{{-- PORCENTAJE --}}
        <td>{{ $pedido->importe }}</td>{{-- IMPORTE A PAGAR --}}
        <td>{{ $pedido->courier }}</td>{{-- COURIER --}}
        <td>{{ $pedido->total }}</td>{{-- TOTAL A PAGAR --}}
        <td>
            {{ $pedido->cant_compro }}
        </td>{{-- CANTIDAD COMPROBANTES --}}
        <td>USER0{{ $pedido->operario }}</td>{{-- OPERARIO EMITIO FACTURA --}}        
        <td>{{ $pedido->estado_pedido }}</td>{{-- ESTADO PEDIDO --}}
        <td>{{ $pedido->estado_envio }}</td>{{-- ESTADO SOBRE --}}
        <td>{{ $pedido->fecha_ult_pago }}</td>{{-- FECHA CANCELACION DE PAGO --}}
        {{-- FECHA DE VOUCHER --}}
        {{-- IMPORTE PAGADO --}}
        {{-- BANCO --}}
        <td>{{ $pedido->estado_pago }}</td>{{-- ESTADO PAGO --}}
        <td>{{ $pedido->diferencia }}</td>{{-- DIFERENCIA --}}
        <td>{{ $pedido->fecha_aprobacion }}</td>{{-- FECHA APROBACION PAGO --}}
        <td></td>
        <td>{{ $pedido->responsable }}</td>{{-- RESPONSABLE --}}
        <td>{{ $pedido->motivo }}</td>{{-- MOTIVO ANULACION --}}
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
        <td>{{ $pedido->creador }}</td>{{-- CREADOR --}}
        <td>{{ $pedido->fecha_mod }}</td>{{-- FECHA MODIFICACION --}}
        <td>{{ $pedido->modificador }}</td>{{-- USUARIO MODIFICADOR --}}
        <td>{{ $pedido->asesor_nombre }}</td>{{-- ASESOR NOMBRE --}}
        <td>{{ $pedido->asesor_identificador }}</td>{{-- ASESOR IDENTIFICADOR --}}
        <td>{{ $pedido->fecha }}</td>{{-- FECHA PEDIDO --}}
        <td>{{ $pedido->codigos }}</td>{{-- CODIGO PEDIDO --}}
        <td>{{ $pedido->nombres }}</td>{{-- NOMBRE CLIENTE --}}
        <td>{{ $pedido->celulares }}</td>{{-- TELEFONO CLIENTE --}}
        <td>{{ $pedido->empresas }}</td>{{-- RAZON SOCIAL --}}
        <td>{{ $pedido->mes }}</td>{{-- MES --}}
        <td>{{ $pedido->ruc }}</td>{{-- RUC --}}
        <td>{{ $pedido->cantidad }}</td>{{-- CANTIDAD PEDIDO --}}
        <td>{{ $pedido->tipo }}</td>{{-- TIPO --}}
        <td>{{ $pedido->porcentaje }}</td>{{-- PORCENTAJE --}}
        <td>{{ $pedido->importe }}</td>{{-- IMPORTE A PAGAR --}}
        <td>{{ $pedido->courier }}</td>{{-- COURIER --}}
        <td>{{ $pedido->total }}</td>{{-- TOTAL A PAGAR --}}
        <td>
            {{ $pedido->cant_compro }}
        </td>{{-- CANTIDAD COMPROBANTES --}}
        <td>USER0{{ $pedido->operario }}</td>{{-- OPERARIO EMITIO FACTURA --}}        
        <td>{{ $pedido->estado_pedido }}</td>{{-- ESTADO PEDIDO --}}
        <td>{{ $pedido->estado_envio }}</td>{{-- ESTADO SOBRE --}}
        <td style="color: #FF0000">SIN PAGOS</td>{{-- FECHA CANCELACION DE PAGO --}}
        {{-- FECHA DE VOUCHER --}}
        {{-- IMPORTE PAGADO --}}
        {{-- BANCO --}}
        <td style="color: #FF0000">SIN PAGOS</td>{{-- ESTADO PAGO --}}
        <td>{{ $pedido->total }}</td>{{-- DIFERENCIA --}}
        <td style="color: #FF0000">SIN PAGOS</td>{{-- FECHA APROBACION PAGO --}}
        <td></td>
        <td>{{ $pedido->responsable }}</td>{{-- RESPONSABLE --}}
        <td>{{ $pedido->motivo }}</td>{{-- MOTIVO ANULACION --}}
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>