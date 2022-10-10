<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">IDENTIFICADOR ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">NOMBRE ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PEDIDO</th>      
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">OBSERVACION</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL A COBRAR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL PAGADO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">SALDO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ESTADO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pagos as $pago)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pago->id_asesor }}</td>
        <td>{{ $pago->nombre_asesor }}</td>
        <td>{{ $pago->codigo_pedido }}</td>        
        <td>{{ $pago->observacion }}</td>
        <td>{{ $pago->total_deuda }}</td>
        <td>{{ $pago->total_pago }}</td>
        <td>{{ $pago->diferencia }}</td>
        <td>{{ $pago->estado_pago }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>