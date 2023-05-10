<table>
  <thead>
    <tr>
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      {{--<th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ID</th>--}}
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">CLIENTE</th>
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">FECHA VOUCHER</th>
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">PEDIDO</th>
      {{--<th style="background-color: #4c5eaf; text-align: center; color: white;">PEDIDOS ANULADOS</th>--}}
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">ASESOR</th>
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">OBSERVACION</th>
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">TOTAL PAGADO</th>
      {{--<th style="background-color: #4c5eaf; text-align: center; color: white;">TOTAL PAGADO ANULADOS</th>--}}
      <th width='80px' style="background-color: #4c5eaf; text-align: center; color: white;">ESTADO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($pagos as $pago)
      <tr>
        <td>{{ $cont + 1 }}</td>
        {{--<td>{{ $pago->id }}</td>--}}
        
        @if ($pago->id < 10)
          <td>PAG000{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @elseif($pago->id < 100)
          <td>PAG00{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @elseif($pago->id < 1000)
          <td>PAG0{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @else
          <td>PAG{{  $unido=( ($pago->cantidad_voucher>1)? 'V':'I' ).''.( ($pago->cantidad_pedido>1)? 'V':'I' );  }}{{$pago->id}}</td>
        @endif


        <td>{{ $pago->celular }}</td>
        <td>{{ $pago->fecha }}</td>
        <td>{{ $pago->codigos }}</td>
        {{--<td>{{ $pago->codigos_anulados_1 }} {{ $pago->codigos_anulados_2 }} {{ $pago->codigos_anulados_3 }} {{ $pago->codigos_anulados_4 }} {{ $pago->codigos_anulados_5 }} {{ $pago->codigos_anulados_6 }} {{ $pago->codigos_anulados_7 }}</td>--}}
        <td>{{ $pago->users }}</td>
        <td>{{ $pago->observacion }}</td>
        <td>{{ $pago->total_pago }}</td>
        {{--<td>{{ $pago->total_pago_anulados }}</td>--}}
        <td>{{ $pago->condicion }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
