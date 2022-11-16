<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">BANCO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">TITULAR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">IMPORTE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">TIPO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">OTROS</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">FECHA</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ESTADO</th>{{--CONCILIADO  SIN CONCILIACION--}}
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CONCILIADO </th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($movimientos1 as $movimiento)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>MOV00{{ $movimiento->id }}</td>
        <td>{{ $movimiento->banco }}</td>
        <td>{{ $movimiento->titular }}</td>
        <td>{{ $movimiento->importe }}</td>
        <td>{{ $movimiento->tipo }}</td>
        <td>{{ $movimiento->otros }}</td>
        <td>{{ $movimiento->fecha }}</td>
        <td>{{ $movimiento->pago }}</td>
        <td>{{ $movimiento->detpago."-".$movimiento->id }}</td>
        
      </tr>
      <?php $cont++; ?>
    @endforeach
   
  </tbody>
</table>
