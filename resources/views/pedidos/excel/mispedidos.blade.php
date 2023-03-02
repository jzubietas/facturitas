<table>
  <thead>
    <tr>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">ITEM</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">ID</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">USUARIO QUE CREO EL PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">FECHA DE ÚLTIMA MODIFIACIÓN</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">USUARIO DE ÚLTIMA MODIFICACIÓN</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">ASESOR NOMBRE</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">ASESOR IDENTIFICADOR</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">FECHA DE PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">CODIGO DE PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">NOMBRE DE CLIENTE</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">TELEFONO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">RAZON SOCIAL</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">MES</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">RUC</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">CANTIDAD DE PEDIDO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">TIPO</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">PORCENTAJE (%)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">IMPORTE A PAGAR (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">COURIER (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">TOTAL POR PAGAR (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">CANT. DE COMPROBANTES (S/)</th>
      <th style="background-color: #D9D9D9; text-align: center; color: #000000; vertical-align: middle">OPERARIO QUE EMITIO LA FACTURA</th>
      <th style="background-color: #FFFF00; text-align: center; color: #000000; vertical-align: middle">ESTADO DE PEDIDO</th>
      <th style="background-color: #FFFF00; text-align: center; color: #000000; vertical-align: middle">ESTADO DE SOBRE</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000; vertical-align: middle">FECHA DE CANCELACION DE PAGO</th>
      <th style="background-color: #FFC000; text-align: center; color: #000000; vertical-align: middle">ESTADO DE PAGO</th>
      <th style="background-color: #FF0000; text-align: center; color: #000000; vertical-align: middle">DIFERENCIA</th>
      <th style="background-color: #92D050; text-align: center; color: #000000; vertical-align: middle">FECHA DE APROBACIÓN DE PAGO</th>
      <th style="vertical-align: middle"></th>
      <th style="background-color: #BDD7EE; text-align: center; color: #000000; vertical-align: middle">Responsable</th>
      <th style="background-color: #BDD7EE; text-align: center; color: #000000; vertical-align: middle">Motivo de anulación</th>
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
        <td>{{ $pedido->celulares }}-{{ $pedido->icelulares }}</td>{{-- TELEFONO CLIENTE --}}
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
        @if($pedido->fecha_ult_pago == null){{-- FECHA CANCELACION DE PAGO --}}
          <td style="color: #FF0000">SIN PAGOS</td>
        @else
          <td>{{ $pedido->fecha_ult_pago }}</td>
        @endif
        {{-- FECHA DE VOUCHER --}}
        {{-- IMPORTE PAGADO --}}
        {{-- BANCO --}}
        @if($pedido->estado_pago==null){{-- ESTADO PAGO --}}
          <td style="color: #FF0000">SIN PAGOS</td>
        @else
          <td>{{ $pedido->estado_pago }}</td>
        @endif
        <td>{{ $pedido->diferencia }}</td>{{-- DIFERENCIA --}}
        @if($pedido->fecha_aprobacion==null){{-- FECHA APROBACION PAGO --}}
          <td style="color: #FF0000">SIN APROBACION</td>
        @else
          <td>{{ $pedido->fecha_aprobacion }}</td>
        @endif
        <td></td>
        <td>{{ $pedido->responsable }}</td>{{-- RESPONSABLE --}}
        <td>{{ $pedido->motivo }}</td>{{-- MOTIVO ANULACION --}}
      </tr>
      <?php $cont++; ?>
    @endforeach
    {{-- @foreach ($pedidos2 as $pedido)
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
        <td>{{ $pedido->creador }}</td>
        <td>{{ $pedido->fecha_mod }}</td>
        <td>{{ $pedido->modificador }}</td>
        <td>{{ $pedido->asesor_nombre }}</td>
        <td>{{ $pedido->asesor_identificador }}</td>
        <td>{{ $pedido->fecha }}</td>
        <td>{{ $pedido->codigos }}</td>
        <td>{{ $pedido->nombres }}</td>
        <td>{{ $pedido->celulares }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->mes }}</td>
        <td>{{ $pedido->ruc }}</td>
        <td>{{ $pedido->cantidad }}</td>
        <td>{{ $pedido->tipo }}</td>
        <td>{{ $pedido->porcentaje }}</td>
        <td>{{ $pedido->importe }}</td>
        <td>{{ $pedido->courier }}</td>
        <td>{{ $pedido->total }}</td>
        <td>
            {{ $pedido->cant_compro }}
        </td>
        <td>USER0{{ $pedido->operario }}</td>
        <td>{{ $pedido->estado_pedido }}</td>
        <td>{{ $pedido->estado_envio }}</td>
        <td style="color: #FF0000">SIN PAGOS</td>
        <td style="color: #FF0000">SIN PAGOS</td>
        <td>{{ $pedido->total }}</td>
        <td style="color: #FF0000">SIN PAGOS</td>
        <td></td>
        <td>{{ $pedido->responsable }}</td>
        <td>{{ $pedido->motivo }}</td>
      </tr>
    @endforeach --}}
  </tbody>
</table>
