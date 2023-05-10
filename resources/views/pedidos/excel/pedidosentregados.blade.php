<table>
  <thead>
    <tr>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">ITEM</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">IDENTIFICADOR DE ASESOR</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">CODIGO DE PEDIDO</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">RAZON SOCIAL</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">RUC</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">MES</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">TIPO</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">CANTIDAD DEL PEDIDO</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">CANT. DE DOCUMENTOS</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">ESTADO DE PEDIDO</th>
      <th style="background-color: #FFFF00; text-align: center; vertical-align: middle; color: #0000;">FECHA DE REGISTRO DE PEDIDO</th>
      <th style="background-color: #FFFF00; text-align: center; vertical-align: middle; color: #0000;">FECHA ELABORACIÓN PEDIDOS</th>
      <th style="background-color: #FFFF00; text-align: center; vertical-align: middle; color: #0000;">FECHA DE FINALIZACIÓN DE PEDIDO</th>
      <th style="background-color: #4472C4; text-align: center; vertical-align: middle; color: #ffff;">DETALLE</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>{{-- ITEM --}}

        <td>{{ $pedido->id_asesor }}</td>{{-- IDENTIFICADOR ASESOR --}}
        <td>{{ $pedido->codigo }}</td>{{-- CODIGO PEDIDO --}}
        <td>{{ $pedido->empresa }}</td>{{-- RAZON SOCIAL --}}
        <td>{{ $pedido->num_ruc }}</td>{{-- RUC --}}
        <td>{{ $pedido->mes }}</td>{{-- MES --}}
        <td>{{ $pedido->tipo }}</td>{{-- TIPO --}}
        <td>{{ $pedido->cantidad }}</td>{{-- CANTIDAD --}}

        <td>{{ $pedido->cant_compro }}</td>{{-- CANT. DOCUMENTOS --}}
        <td>{{ $pedido->estado_envio }}</td>{{-- ESTADO PEDIDO --}}
        <td>{{ $pedido->fecha_registro }}</td>{{-- FECHA REGISTRO PEDIDO --}}
        <td>{{ $pedido->fecha_elaboracion }}</td>{{-- FECHA ELABORACION PEDIDO --}}
        <td>{{ $pedido->fecha_finalizacion }}</td>{{-- FECHA FINALIZACIÓN PEDIDO --}}
        <td>{{ $pedido->descripcion }}</td>{{-- DETALLE --}}
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
