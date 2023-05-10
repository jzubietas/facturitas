<table>
  <thead>
    <tr>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">ITEM</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">ASESOR</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">CODIGO</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">TELEFONO</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">NOMBRE EMPRESA</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">MES</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">RUC</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">CANTIDAD (S/)</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">BANCA - TIPO</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">%</th>
      <th style="background-color: #2b4b32; text-align: center; color: #ffff;">FT (S/)</th>
      <th style="background-color: #2b4b32; text-align: center; color: #ffff;">COURIER (S/)</th>
      <th style="background-color: #2b4b32; text-align: center; color: #ffff;">TOTAL (S/)</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">ENVIO DE FOTO O PDF</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">CANTIDAD DE COMPROBANTE</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">ENVIO DE DOCUMENTO</th>
      <th style="background-color: #67c97c; text-align: center; color: #ffff;">FECHA DE RECEPCION</th>
      <th style="background-color: #ffc5ee; text-align: center; color: #ffff;">(S/)</th>
      <th style="background-color: #ffc5ee; text-align: center; color: #ffff;">BANCO</th>
      <th style="background-color: #ffc5ee; text-align: center; color: #ffff;">FECHA</th>
      <th style="background-color: #ffc5ee; text-align: center; color: #ffff;">¿ESTADO?</th>
      <th style="background-color: #ffc5ee; text-align: center; color: #ffff;">TITULAR</th>

    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pedido->users }}</td>
        <td>{{ $pedido->codigos }}</td>
        <td>{{ $pedido->celulares }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->mes }}</td>
        <td>{{ $pedido->ruc }}</td>
        <td>{{ $pedido->cantidad }}</td>
        <td>{{ $pedido->tipo_banca }}</td>
        <td>{{ $pedido->porcentaje }}</td>
        <td>{{ $pedido->ft }}</td>
        <td>{{ $pedido->courier }}</td>
        <td>{{ $pedido->total }}</td>
        <td>{{ $pedido->envio_doc }}</td>
        <td>{{ $pedido->cant_compro }}</td>
        <td>{{ $pedido->fecha_envio_doc_fis }}</td>
        <td>{{ $pedido->fecha_recepcion }}</td>
        <td>{{ $pedido->total_pago }}</td>
        <td>{{ $pedido->banco }}</td>
        <td>{{ $pedido->fecha_pago }}</td>
        <td>{{ $pedido->condicion_pa }}</td>
        <td>{{ $pedido->titular }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
    @foreach ($pedidos2 as $pedido)
        <tr>
          <td>{{ $cont + 1 }}</td>
          <td>{{ $pedido->users }}</td>
          <td>{{ $pedido->codigos }}</td>
          <td>{{ $pedido->celulares }}</td>
          <td>{{ $pedido->empresas }}</td>
          <td>{{ $pedido->mes }}</td>
          <td>{{ $pedido->ruc }}</td>
          <td>{{ $pedido->cantidad }}</td>
          <td>{{ $pedido->tipo_banca }}</td>
          <td>{{ $pedido->porcentaje }}</td>
          <td>{{ $pedido->ft }}</td>
          <td>{{ $pedido->courier }}</td>
          <td>{{ $pedido->total }}</td>
          <td>{{ $pedido->envio_doc }}</td>
          <td>{{ $pedido->cant_compro }}</td>
          <td>{{ $pedido->fecha_envio_doc_fis }}</td>
          <td>{{ $pedido->fecha_recepcion }}</td>
          <td>{{ $pedido->total_pago }}</td>
          <td>{{ $pedido->banco }}</td>
          <td>{{ $pedido->fecha_pago }}</td>
          <td>SIN PAGO REGISTRADO</td>
          <td>{{ $pedido->titular }}</td>          
        </tr>
        <?php $cont++; ?>
    @endforeach
  </tbody>
</table>