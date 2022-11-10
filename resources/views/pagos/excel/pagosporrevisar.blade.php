<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ID_</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CLIEENTE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PEDIDOS ANULADOS</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">OBSERVACION</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL PAGADO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL PAGADO ANULADOS</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ESTADO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pagos as $pago)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pago->id }}</td>
        <td>PAG00{{ $pago->id }}</td>
        <td>{{ $pago->celular }}</td>
        <td>{{ $pago->codigos }}</td>
        <td>{{ $pago->codigos_anulados_1 }} {{ $pago->codigos_anulados_2 }} {{ $pago->codigos_anulados_3 }} {{ $pago->codigos_anulados_4 }} {{ $pago->codigos_anulados_5 }} {{ $pago->codigos_anulados_6 }} {{ $pago->codigos_anulados_7 }}</td>
        <td>{{ $pago->users }}</td>
        <td>{{ $pago->observacion }}</td>
        <td>{{ $pago->total_pago }}</td>
        <td>{{ $pago->total_pago_anulados }}</td>
        <td>{{ $pago->condicion }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
