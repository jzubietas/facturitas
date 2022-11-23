<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">BANCO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">TITULAR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">FECHA</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">TIPO - OTROS</th>
      {{--<th style="background-color: #4c5eaf; text-align: center; color: white;">OTROS</th>---}}
      <th style="background-color: #4c5eaf; text-align: center; color: white;">IMPORTE</th>
      
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ESTADO</th>{{--CONCILIADO  SIN CONCILIACION--}}
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CODIGO DE VOUCHER</th>
      
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CANT.DE VOUCHER </th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($movimientos1 as $movimiento)
      <tr>
        <td>{{ $cont + 1 }}</td>

        @if ($movimiento->id < 10)
        <td>MOV000{{$movimiento->id}}</td>
      @elseif($movimiento->id < 100)
        <td>MOV00{{$movimiento->id}}</td>
      @elseif($movimiento->id < 1000)
        <td>MOV0{{$movimiento->id}}</td>
      @else
        <td>MOV{{$movimiento->id}}</td>
      @endif

        <td>{{ $movimiento->banco }}</td>
        <td>{{ $movimiento->titular }}</td>
        <td style="text-align: right">{{ $movimiento->fecha }}</td>
        <td>{{ $movimiento->tipo }} - {{ $movimiento->otros }}</td>
        <td>{{ $movimiento->importe }}</td>
        {{--<td>{{ $movimiento->otros }}</td>--}}
        
        @if ($movimiento->pago=="1")
          <td>CONCILIADO</td>
        @else
          <td>SIN CONCILIAR</td>
        @endif
        
        {{--<td>{{ $movimiento->detpago."-".$movimiento->id }}</td>--}}
        <td>PAG{{ $movimiento->users }}-{{$movimiento->cantidad_voucher}}{{$movimiento->cantidad_pedido}}-{{ $movimiento->pagoid }}</td>
        <td>{{ $movimiento->cant }}</td>
        
      </tr>
      <?php $cont++; ?>
    @endforeach
   
  </tbody>
</table>
