<table>
  <thead>
    <tr>
    <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">ITEM</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">FECHA DE ELABORACION</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">ASESOR</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">CODIGO DE PEDIDO</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">NOMBRE DE EMPRESA</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">RUC</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">MES</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">BANCA - TIPO</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">TOTAL</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">ELABORADO POR</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">CANTIDAD DE DOCUMENTOS</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">FECHA DE ENVIO</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">FECHA DE REPARTO</th>
      <!-- <th width='180px' style="background-color: #1538d4; text-align: center; color: #ffff;">SALDO</th> -->
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">OBSERVACION</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">CRUCE CON ASESOR</th>
      <th width='80px' style="background-color: #B8CCE4; text-align: justify; color: #ffff;font-weight: bold;border: 1px solid #000;">CRUCE CON CLIENTE</th>
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
        <!-- <td>{{ ($pedido->total_cobro)-($pedido->total_pagado) }}</td> -->
        <td>{{ $pedido->descripcion }}</td>
      </tr>
    <?php $cont++; ?>
    @endforeach
   
  </tbody>
</table>