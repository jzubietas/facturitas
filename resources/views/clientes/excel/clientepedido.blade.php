<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">NOMBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CELULAR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">PROVINCIA</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DISTRITO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DIRECCION</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">REFERENCIA</th>      
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DNI</th>
      {{-- <th style="background-color: #4c5eaf; text-align: center; color: white;">CANT. PEDIDOS</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">MES</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">AÑO</th> --}}
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ENERO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">FEBRERO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">MARZO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ABRIL</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">MAYO</th>      
      <th style="background-color: #4c5eaf; text-align: center; color: white;">JUNIO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">JULIO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">AGOSTO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">SETIEMBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">OCTUBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">NOVIEMBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DICIEMBRE</th>
    </tr>
  </thead>
  <tbody>
    {{-- @foreach ($clientes as $cliente)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>CLI00{{ $cliente->id }}</td>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->celular }}</td>
        <td>{{ $cliente->provincia }}</td>
        <td>{{ $cliente->distrito }}</td>
        <td>{{ $cliente->direccion }}</td>
        <td>{{ $cliente->referencia }}</td>
        <td>{{ $cliente->dni }}</td>
        <td>{{ $cliente->cantidad }}</td>
        <td>{{ $cliente->mes }}</td>
        <td>{{ $cliente->anio }}</td>        
      </tr>--}}
    {{--@endforeach    --}}
    <?php $cont = 0; ?>
    @foreach ($clientes as $cliente)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>CLI00{{ $cliente->id }}</td>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->celular }}</td>
        <td>{{ $cliente->provincia }}</td>
        <td>{{ $cliente->distrito }}</td>
        <td>{{ $cliente->direccion }}</td>
        <td>{{ $cliente->referencia }}</td>
        <td>{{ $cliente->dni }}</td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '1')
              {{ $pedido->total }}{{--ENERO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '2')
              {{ $pedido->total }}{{--FEBRERO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '3')
              {{ $pedido->total }}{{--MARZO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '4')
              {{ $pedido->total }}{{--ABRIL--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '5')
              {{ $pedido->total }}{{--MAYO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '6')  
              {{ $pedido->total }}{{--JUNIO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '7')
              {{ $pedido->total }}{{--JULIO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '8')
              {{ $pedido->total }}{{--AGOSTO--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '9')
              {{ $pedido->total }}{{--SETIEMBRE--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '10')
              {{ $pedido->total }}{{--OCTUBRE--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '11')
              {{ $pedido->total }}{{--NOVIEMBRE--}}
            @endif
          @endforeach
        </td>
        <td>
          @foreach ($pedidos as $pedido)
            @if($pedido->cliente_id == $cliente->id && $pedido->mes == '12')
              {{ $pedido->total }}{{--DICIEMBRE--}}
            @endif
          @endforeach
        </td>
      </tr>
      <?php $cont++; ?>
    @endforeach 
  </tbody>
</table>
