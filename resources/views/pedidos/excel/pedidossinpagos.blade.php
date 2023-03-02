<table>
    <thead>
    <tr>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">ITEM</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">CODIGO</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">CLIENTE</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">RAZON SOCIAL</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">ASESOR</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">FECHA DE REGISTRO</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">TOTAL (S/)</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">ESTADO DE PEDIDO</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">ESTADO DE PAGO</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">ADMINISTRACION</th>
        <th scope="col" style="background-color: #4c5eaf; text-align: center; vertical-align: middle; color: #ffff;">DIFERENCIA</th>

        {{--
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ID</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PEDIDO</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CLIENTE</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">RAZON SOCIAL</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ASESOR</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">FECHA DE REGISTRO</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">TOTAL (S/)</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ESTADO DE PEDIDO</th>
              <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ESTADO DE PAGO</th>
        --}}
    </tr>
    </thead>
    <tbody>
    {{--
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>PED00{{ $pedido->id }}</td>
        <td>{{ $pedido->codigos }}</td>
        <td>{{ $pedido->celulares }}-{{ $pedido->icelulares }} - {{ $pedido->nombres }}</td>
        <td>{{ $pedido->empresas }}</td>
        <td>{{ $pedido->users }}</td>
        <td>{{ $pedido->fecha }}</td>
        <td>{{ $pedido->total }}</td>
        <td>{{ $pedido->condiciones }}</td>
        <td>SIN PAGOS REGISTRADOS</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
    --}}
    @php $cont = 0; @endphp
    @foreach ($pedidos as $pedido)
        <tr>
            <td>{{ $cont + 1 }}</td>
            <td>{{ $pedido->codigos }}</td>
            <td>{{ join(' - ',array_filter([$pedido->celulares,$pedido->icelulares])) }} {{ $pedido->nombres }}</td>
            <td>{{ $pedido->empresas }}</td>
            <td>{{ $pedido->users }}</td>
            <td>{{ $pedido->fecha }}</td>
            <td>{{ $pedido->total }}</td>
            <td>
                @if($pedido->condicion_code==1)
                    {{\App\Models\Pedido::POR_ATENDER }}
                @elseif($pedido->condicion_code==2)
                    {{\App\Models\Pedido::EN_PROCESO_ATENCION }}
                @elseif($pedido->condicion_code==3)
                    {{\App\Models\Pedido::ATENDIDO }}
                @elseif($pedido->condicion_code==4)
                    {{\App\Models\Pedido::ANULADO }}
                @endif
            </td>
            <td>
                @if($pedido->condicion_code==\App\Models\Pedido::$estadosCondicion[\App\Models\Pedido::ANULADO])
                    {{\App\Models\Pedido::ANULADO}}
                @else
                    @if($pedido->condicion_pa==null)
                        SIN PAGO REGISTRADO
                    @else
                        @if($pedido->condicion_pa=='0')
                           SIN PAGO REGISTRADO
                        @endif
                        @if($pedido->condicion_pa=='1')
                            ADELANTO
                        @endif
                        @if($pedido->condicion_pa=='2')
                            PAGO
                        @endif
                        @if($pedido->condicion_pa=='3')
                            ABONADO
                        @endif
                    @endif
                @endif
            </td>
            <td>
                @if($pedido->condiciones_aprobado!=null)
                    {{$pedido->condiciones_aprobado}}
                @else
                    SIN REVISAR
                @endif
            </td>
            <td>
                @if($pedido->diferencia==null)
                    NO REGISTRA PAGO
                @else
                    {{$pedido->diferencia}}
                @endif
            </td>
        </tr>
        @php $cont++; @endphp
    @endforeach
    </tbody>
</table>
