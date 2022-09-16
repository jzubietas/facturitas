<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF - PEDIDOS POR ASESOR</title>
  <style>
    #cabecera {
      width: 100%;
      border: #028bff solid;
      border-collapse: collapse;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
    }

    #cabecera th {
      width: 22%;
      text-align: right;
      background-color: #028bff;
      color: #ffff;
    }

    #detalle {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    #detalle td,
    #detalle th {
      border: 1px, solid #ddd;
      padding: 5px;
      text-align: center;
      font-size: 10px;
    }

    #detalle th {
      padding-top: 10px;
      padding-bottom: 10px;
      text-align: center;
      background-color: #028bff;
      color: #fff;
    }

    #title {
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
    }

  </style>
</head>

<body>
  <h4 class="title">PEDIDOS DEL ASESOR {{ $request->user_id }}</h4>
  <table id="detalle">
    <thead>
      <tr>
        <th>ITEM</th>
        <th>COD</th>
        <th>PEDIDOS</th>
        <th>CLIENTE</th>
        <th>ASESOR</th>
        <th>RAZON SOCIAL</th>
        <th>MONTO TOTAL</th>
        <th>FECHA DE REGISTRO</th>
        <th>ESTADO DE PEDIDO</th>
        <th>ESTADO DE PAGO</th>
      </tr>
    </thead>
    <tbody>
      @php
        $cont = 0;
        $sum = 0;
      @endphp
      @foreach ($pedidos as $pedido)
        <tr>
          <td>{{ $cont + 1 }}</td>
          @if ($pedido->id < 10)
                <td>PED000{{ $pedido->id }}</td>
              @elseif($pedido->id < 100)
                <td>PED00{{ $pedido->id }}</td>
              @elseif($pedido->id < 1000)
                <td>PED0{{ $pedido->id }}</td>
              @else
                <td>PED{{ $pedido->id }}</td>
              @endif
          <td>{{ $pedido->codigos }}</td>
          <td>{{ $pedido->nombres }} - {{ $pedido->celulares }}</td>
          <td>{{ $pedido->users }}</td>
          <td>{{ $pedido->empresas }}</td>
          <td>{{ $pedido->total }}</td>
          <td>{{ $pedido->fecha }}</td>
          <td>{{ $pedido->condiciones }}</td>
          <td>{{ $pedido->condicion_pa }}</td>
        </tr>
        @php
          $cont++;
          $sum = ($pedido->total) + $sum;
        @endphp
      @endforeach
      @foreach ($pedidos2 as $pedido)
        <tr>
          <td>{{ $cont + 1 }}</td>
          @if ($pedido->id < 10)
                <td>PED000{{ $pedido->id }}</td>
              @elseif($pedido->id < 100)
                <td>PED00{{ $pedido->id }}</td>
              @elseif($pedido->id < 1000)
                <td>PED0{{ $pedido->id }}</td>
              @else
                <td>PED{{ $pedido->id }}</td>
              @endif
          <td>{{ $pedido->codigos }}</td>
          <td>{{ $pedido->nombres }} - {{ $pedido->celulares }}</td>
          <td>{{ $pedido->users }}</td>
          <td>{{ $pedido->empresas }}</td>
          <td>{{ $pedido->total }}</td>
          <td>{{ $pedido->fecha }}</td>
          <td>{{ $pedido->condiciones }}</td>
          <td>SIN PAGOS REGISTRADOS</td>
        </tr>
        @php
          $cont++;
          $sum = ($pedido->total) + $sum;
        @endphp
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4"></td>
        <td colspan="1"></td>
        <td colspan="2">TOTAL</td>
        <td colspan="3">S/{{$sum}}</td>
      </tr>
    </tfoot>
  </table>

  <br>

  </div>
</body>

</html>
