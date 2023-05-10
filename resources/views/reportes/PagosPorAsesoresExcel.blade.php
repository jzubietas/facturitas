<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">OBSERVACION</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL COBRO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL PAGADO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ESTADO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pagos as $pago)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>PAG00{{ $pago->id }}</td>
        <td>{{ $pago->codigos }}</td>
        <td>{{ $pago->users }}</td>
        <td>{{ $pago->observacion }}</td>
        <td>{{ $pago->total_deuda }}</td>
        <td>{{ $pago->total_pago }}</td>
        <td>{{ $pago->condicion }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>