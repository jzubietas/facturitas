<table>
  <thead>
    <tr>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">ITEM</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">FECHA DE ELABORACION</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">ASESOR</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">CODIGO DE PEDIDO</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">NOMBRE DE EMPRESA</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">RUC</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">MES</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">BANCA - TIPO</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">TOTAL</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">ELABORADO POR</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">CANTIDAD DE DOCUMENTOS</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">FECHA DE ENVIO</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">FECHA DE REPARTO</th>
      <th style="background-color: #1538d4; text-align: center; color: #ffff;">OBSERVACION</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pedido->fecha_envio_doc }}</td>
        <td>{{ $pedido->users }}</td>
        <td>{{ $pedido->codigos }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->ruc }}</td>
        <td>{{ $pedido->mes }}</td>
        <td>{{ $pedido->tipo_banca }}</td>
        <td>{{ $pedido->total }}</td>
        <td>{{ $pedido->atendido_por }}</td>
        <td>{{ $pedido->cant_compro }}</td>
        <td>{{ $pedido->fecha_envio_doc_fis }}</td>
        <td>{{ $pedido->fecha_recepcion }}</td>
        <td>{{ $pedido->descripcion }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>