<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">CLIENTE</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">RAZON SOCIAL</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">FECHA DE REGISTRO</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">TOTAL (S/)</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ESTADO DE PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center;vertical-align: middle; color: #ffff;">ESTADO DE PAGO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>PED00{{ $pedido->id }}</td>
        <td>{{ $pedido->codigos }}</td>
        <td>{{ $pedido->celulares }}-{{ $pedido->icelulares }} - {{ $pedido->nombres }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->users }}</td>
        <td>{{ $pedido->fecha }}</td>
        <td>{{ $pedido->total }}</td>
        <td>{{ $pedido->condiciones }}</td>
        <td>{{ $pedido->condicion_pa }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
