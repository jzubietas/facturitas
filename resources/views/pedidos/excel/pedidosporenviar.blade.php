<table>
  <thead>
    <tr>
      <th colspan="12" style="background-color: #4c5eaf; text-align: center; vertical-align: middle;  color: #ffff;"></th>
      <th colspan="6" style="background-color: #306138; text-align: center;  vertical-align: middle; color: #ffff;">LIMA</th>
      <th colspan="3" style="background-color: #0831ff; text-align: center;  vertical-align: middle; color: #ffff;">PROVINCIA</th>
    </tr>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">IDENTIFICADOR ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">NOMBRE ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">CODIGO DE PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">FECHA DE REGISTRO</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">NOMBRE CLIENTE</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">CELULAR</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">RAZON SOCIAL</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">CANTIDAD DE PEDIDO (S/)</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">FECHA DE ELABORACION</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ESTADO DE PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ESTADO DE ENVIO</th>
      <th style="background-color: #306138; text-align: center;vertical-align: middle; color: #ffff;">NOMBRE DE QUIEN RECIBE SOBRE</th>
      <th style="background-color: #306138; text-align: center;vertical-align: middle; color: #ffff;">NUMERO DE CONTACTO</th>
      <th style="background-color: #306138; text-align: center;vertical-align: middle; color: #ffff;">DISTRITO</th>
      <th style="background-color: #306138; text-align: center;vertical-align: middle; color: #ffff;">DIRECCION</th>
      <th style="background-color: #306138; text-align: center;vertical-align: middle; color: #ffff;">REFERENCIA</th>
      <th style="background-color: #306138; text-align: center;vertical-align: middle; color: #ffff;">ZONA</th>
      <th style="background-color: #0831ff; text-align: center;vertical-align: middle; color: #ffff;">NUMERO DE TRACKING</th>
      <th style="background-color: #0831ff; text-align: center;vertical-align: middle; color: #ffff;">NUMERO DE REGISTRO</th>
      <th style="background-color: #0831ff; text-align: center;vertical-align: middle; color: #ffff;">IMPORTE (S/.)</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidosLima as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pedido->identificador_asesor }}</td>
        <td>{{ $pedido->nombre_asesor }}</td>
        <td>{{ $pedido->codigo }}</td>
        <td>{{ $pedido->fecha_registro }}</td>
        <td>{{ $pedido->nombre_cliente }}</td>
        <td>{{ $pedido->celular_cliente }}-{{ $pedido->icelular_cliente }}</td>
        <td>{{ $pedido->empresa }}</td>
        <td>{{ $pedido->cantidad }}</td>
        <td>{{ $pedido->fecha_elaboracion }}</td>{{-- FECHA ELABORACION --}}
        <td>{{ $pedido->estado_pedido }}</td>
        <td>{{ $pedido->estado_envio }}</td>
        <td>{{ $pedido->nombre_recibe }}</td>
        <td>{{ $pedido->celular_contacto }}</td>
        <td>{{ $pedido->distrito }}</td>
        <td>{{ $pedido->direccion }}</td>
        <td>{{ $pedido->referencia }}</td>
        <td>{{ $pedido->zona }}</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
