<table>
    <thead>
      <tr>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">FECHA</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ASESOR</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CLIENTE</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CANTIDAD</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">CODIGOS</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">PRODUCTO</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">DIRECCION</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">REFERENCIA</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">OBSERVACION</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">DISTRITO</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 0; ?>
      @foreach ($pedidos as $plima)
        <tr>
          {{--<td>{{ $cont + 1 }}</td>--}}
          <td>ENV00{{ $plima->id }}</td>
          <td>{{ $plima->fecha }}</td>
          <td>{{ $plima->identificador }}</td>
          <td>{{ $plima->celular }}-{{ $plima->nombre }}</td>
          <td>{{ $plima->cantidad }}</td>
          <td>{{ $plima->codigos }}</td>
          <td>{{ $plima->producto }}</td>
          <td>{{ $plima->direccion }}</td>
          <td>{{ $plima->referencia }}</td>
          <td>{{ $plima->observacion }}</td>
          <td>{{ $plima->distrito }}</td>
        </tr>
        <?php $cont++; ?>
      @endforeach
      
    </tbody>
  </table>