<table>
  <thead>
    <tr>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">Nº</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">CODIGO</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">ASESOR</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">RAZON SOCIAL</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">DIAS</th>
      
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">FECHA ENVIO</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">ESTADO DE ENVIO</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">NOMBRE CLIENTE</th>
      <th style="background-color: #e0dbdb; text-align: center; color: #ffff;">OBSERVACION DEVOLUCION</th>
     
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidosLima as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $pedido->codigo }}</td>
        <td>{{ $pedido->identificador }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->dias }}</td>
        
        <td>{{ $pedido->fecha_envio_doc }}</td>
        <td>{{ $pedido->condicion_envio }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->observacion_devuelto }}</td>
        
        
      </tr>
      <?php $cont++; ?>
    @endforeach
   </tbody>
</table>