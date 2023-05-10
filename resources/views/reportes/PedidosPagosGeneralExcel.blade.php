<table>
  <thead>
    <tr>
      <th width='40px' style="background-color: #67c97c; text-align: center; color: #ffff;">ITEM</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">ASESOR</th>
      {{--<th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">ID ASESOR</th>--}}
      <th width='40px' style="background-color: #67c97c; text-align: center; color: #ffff;">IDENTIFICADOR ASESOR</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">CODIGO</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">TELEFONO</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">NOMBRE EMPRESA</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">MES</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">RUC</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">CANTIDAD (S/)</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">BANCA - TIPO</th>
      <th width='40px' style="background-color: #67c97c; text-align: center; color: #ffff;">%</th>
      <th width='80px' style="background-color: #2b4b32; text-align: center; color: #ffff;">FT (S/)</th>
      <th width='80px' style="background-color: #2b4b32; text-align: center; color: #ffff;">COURIER (S/)</th>
      <th width='80px' style="background-color: #2b4b32; text-align: center; color: #ffff;">TOTAL (S/)</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">FECHA DE CREACION</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">FECHA DE ATENCION</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">ESTADO</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">CANTIDAD DE COMPROBANTE</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">ENVIO DE DOCUMENTO</th>
      <th width='80px' style="background-color: #67c97c; text-align: center; color: #ffff;">FECHA DE RECEPCION</th>
      <th width='80px' style="background-color: #ffc5ee; text-align: center; color: #ffff;">(S/)</th>
      <th width='80px' style="background-color: #ffc5ee; text-align: center; color: #ffff;">BANCO</th>
      <th width='80px' style="background-color: #ffc5ee; text-align: center; color: #ffff;">FECHA</th>
      <th width='80px' style="background-color: #ffc5ee; text-align: center; color: #ffff;">¿ESTADO?</th>
      <th width='80px' style="background-color: #ffc5ee; text-align: center; color: #ffff;">TITULAR</th>

    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pedido->name }}</td>
        {{--<td>USER{{ $pedido->user_id }}</td>--}}
        <td>{{ $pedido->asesor_identificador }}</td>
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
        <td>{{ $pedido->created_at }}</td>
        <td>{{ $pedido->updated_at }}</td>
        <td>{{ $pedido->condicion }}</td>
        <td>{{ $pedido->cant_compro }}</td>
        <td>{{ $pedido->fecha_envio_doc_fis }}</td>
        <td>{{ $pedido->fecha_recepcion }}</td>
        <td>{{ $pedido->total_pago }}</td>
        <td>{{ $pedido->banco }}</td>
        <td>{{ $pedido->fecha_pago }}</td>
        <td>{{ $pedido->condicion_envio }}</td>
        <td>{{ $pedido->titular }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
    
  </tbody>
</table>