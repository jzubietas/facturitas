<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CODIGO PAGO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PEDIDO</th>   
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">IDENTIFICADOR ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">NOMBRE ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CLIENTE</th>
         
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">OBSERVACION</th>
      {{--<th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL A COBRAR</th>--}}
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL PAGADO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">FECHA</th>
      {{--<th style="background-color: #4c5eaf; text-align: center; color: #ffff;">SALDO</th>--}}
      <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ESTADO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pagos as $pago)
      <tr>
        <td>{{ $cont + 1 }}</td>
        @if ($pago->id < 10)
          <td>PAG000{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @elseif($pago->id < 100)
          <td>PAG00{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @elseif($pago->id < 1000)
          <td>PAG0{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @else
          <td>PAG{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @endif

        <td>
          @foreach(explode(',', $pago->codigos) as $codigo) 
            <p>{{$codigo}}</p>
          @endforeach
          </td>    


        <td>{{ $pago->users }}</td>
        <td>{{ $pago->usersname }}</td>
        <td>{{ $pago->celular }}-{{ $pago->icelular }}</td>

           
        <td>{{ $pago->observacion }}</td>
        {{--<td>{{ number_format($pago->total_cobro,2) }}</td>--}}
        <td>{{ number_format($pago->total_pago,2) }}</td>
        <td>{{ $pago->fecha }}</td>
        {{--<td>{{ number_format($pago->total_cobro - $pago->total_pago,2) }}</td>--}}
        <td>{{ $pago->condicion }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>