<table>
    <thead>
    <tr>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">ITEM</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">ID</th>
        <th width="120px" style="background-color: #4c5eaf; text-align: center;vertical-align:middle;  color: #ffff;">PEDIDO</th>
        <th width="120px" style="background-color: #4c5eaf; text-align: center;vertical-align:middle;  color: #ffff;">MES PEDIDO</th>
        <th width="120px" style="background-color: #4c5eaf; text-align: center;vertical-align:middle;  color: #ffff;">AÑO PEDIDO</th>
        <th width="120px" style="background-color: #4c5eaf; text-align: center;vertical-align:middle;  color: #ffff;">CLIENTE CELULAR</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">NOMBRE CLIENTE</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">RAZON SOCIAL</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">ASESOR</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">FECHA DE REGISTRO</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">IMPORTE (S/)</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">TIPO</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">PORCENTAJE</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">FT</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">COURIER (S/)</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">TOTAL (S/)</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">DIFERENCIA (S/)</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">ESTADO DE PAGO</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">REVISADO ADMINISTRACION</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">ESTADO DE ENVIO</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">FECHA DE MODIFICACION</th>
        <th width="80px" style="background-color: #4c5eaf; text-align: center; vertical-align:middle; color: #ffff;">MODIFICADOR</th>
    </tr>
    </thead>
    <tbody>
    <?php $cont = 0; ?>
    @foreach ($pedidos as $pedido)
        <tr style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $cont + 1 }}</td>
            @if ($pedido->id < 10)
                <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                    PED0000{{ $pedido->id }}</td>
            @elseif($pedido->id < 100)
                <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                    PED000{{ $pedido->id }}</td>
            @elseif($pedido->id < 1000)
                <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                    PED00{{ $pedido->id }}</td>
            @else
                <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                    PED0{{ $pedido->id }}</td>
            @endif
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->codigos }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->mespedido }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->aniopedido }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->celulares }}
                -{{ $pedido->icelulares }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->nombres }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->empresas }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->users }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->fecha }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->cantidad }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->tipo_banca }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->porcentaje }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado==0)
                    0
                @else
                    {{ $pedido->ft }}
                @endif
            </td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado==0)
                    0
                @else
                    {{ $pedido->courier }}
                @endif

            </td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado==0)
                    0
                @else
                    {{ $pedido->total }}
                @endif
            </td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado==0)
                    0
                @else
                    {{ $pedido->diferencia }}
                @endif
            </td>

            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado == 0)
                    ANULADO
                @elseif($pedido->pendiente_anulacion == 1)
                    PENDIENTE ANULACION
                @elseif($pedido->condicion_pa == null)
                    SIN PAGO REGISTRADO
                @else
                    @if($pedido->condicion_pa == 0)
                        SIN PAGO REGISTRADO
                    @endif
                    @if($pedido->condicion_pa == 1)
                        ADELANTO
                    @endif
                    @if($pedido->condicion_pa == 2)
                        PAGADO
                    @endif
                @endif
            </td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado == 0)
                    ANULADO
                @elseif($pedido->pendiente_anulacion == 1)
                    PENDIENTE ANULACION
                @elseif($pedido->condiciones_aprobado == null)
                    SIN REVISAR
                @else
                    {{ $pedido->condiciones_aprobado }}
                @endif
            </td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">
                @if($pedido->estado == 0)
                    ANULADO
                @elseif($pedido->pendiente_anulacion == 1)
                    PENDIENTE ANULACION
                @else
                    {{ $pedido->condicion_env }}
                @endif
            </td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->fecha_mod }}</td>
            <td style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ ($pedido->estado==0?$pedido->responsable:$pedido->modificador) }}</td>
        </tr>
            <?php $cont++; ?>
    @endforeach
    {{-- @foreach ($pedidos2 as $pedido)
        <tr>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $cont + 1 }}</td>
          @if ($pedido->id < 10)
            <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">PED0000{{ $pedido->id }}</td>
          @elseif($pedido->id < 100)
            <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">PED000{{ $pedido->id }}</td>
          @elseif($pedido->id < 1000)
            <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">PED00{{ $pedido->id }}</td>
          @else
            <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">PED0{{ $pedido->id }}</td>
          @endif
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->codigos }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->celulares }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->nombres }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->empresas }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->users }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->fecha }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->cantidad }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->tipo_banca }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->porcentaje }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->courier }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->total }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->total }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->condiciones }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">SIN PAGO</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->condicion_env }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->fecha_mod }}</td>
          <td  style="background-color: {{$pedido->estado==0?'red':($pedido->pendiente_anulacion==1?'orange':'white')}}">{{ $pedido->modificador }}</td>
        </tr>
    @endforeach --}}
    </tbody>
</table>
