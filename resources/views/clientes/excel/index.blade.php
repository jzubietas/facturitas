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
      <th style="background-color: #4c5eaf; text-align: center; color: white;">IDENTIFICADOR ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">NOMBRE ASESOR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DEPOSITO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">FECHA DE ULTIMO PEDIDO</th>
      {{-- <th style="background-color: #4c5eaf; text-align: center; color: white;">DIA ULTIMO PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">MES ULTIMO PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">AÑO ULTIMO PEDIDO</th> --}}
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ESTADO PEDIDOS</th>
      {{-- <th style="background-color: #4c5eaf; text-align: center; color: white;">mes actual</th> --}}
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($clientes1 as $cliente)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>CLI00{{ $cliente->id }}</td>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->celular }}-$cliente->icelular</td>
        <td>{{ $cliente->provincia }}</td>
        <td>{{ $cliente->distrito }}</td>
        <td>{{ $cliente->direccion }}</td>
        <td>{{ $cliente->referencia }}</td>
        <td>{{ $cliente->dni }}</td>
        <td>{{ $cliente->id_asesor }}</td>
        <td>{{ $cliente->nombre_asesor }}</td>
        @if ($cliente->deuda == 1)
          <td>DEBE</td>
        @else
          <td>CANCELADO</td>
        @endif
        <td>{{ $cliente->fecha }}</td>
        {{-- <td>{{ ($cliente->dia)*1 }}</td>
        <td>{{ ($cliente->mes)*1 }}</td>
        <td>{{ ($cliente->anio)*1 }}</td> --}}
        @if( (($dateM*1)-($cliente->mes*1)) >= 0 && (($dateM*1)-($cliente->mes*1))<3 && (($dateY*1)-($cliente->anio*1)) == 0)
          <td style="background: #4ac4e2">RECURRENTE</td>
        @else
          <td style="background: #e73d3d">ABANDONO</td>
        @endif
      </tr>
      <?php $cont++; ?>
    @endforeach
    @foreach ($clientes2 as $cliente)
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
        <td>{{ $cliente->id_asesor }}</td>
        <td>{{ $cliente->nombre_asesor }}</td>
        <td>SIN PEDIDOS</td>        
        <td>-</td>
        {{-- <td>-</td>
        <td>-</td>
        <td>-</td> --}}
        <td>SIN PEDIDOS</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
