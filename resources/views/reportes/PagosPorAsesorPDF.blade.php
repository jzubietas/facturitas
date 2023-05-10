<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF - PAGOS POR ASESOR</title>
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
  <h4 class="title">PAGOS DEL ASESOR: {{$request->user_id}}</h4>
  <table id="detalle">
    <thead>
      <tr>
        <th>ITEM</th>
        <th>COD</th>
        <th>PEDIDOS</th>
        <th>ASESOR</th>
        <th>OBSERVACION</th>
        <th>TOTAL A COBRAR</th>
        <th>TOTAL A PAGADO</th>
        <th>FECHA DE REGISTRO</th>
        <th>ESTADO</th>
      </tr>
    </thead>
    <tbody>
      @php
        $cont = 0;
        $sum = 0;
      @endphp
      @foreach ($pagos as $pago)
        <tr>
          <td>{{ $cont + 1 }}</td>
          <td>PAG000{{ $pago->id }}</td>
          <td>{{ $pago->codigos }}</td>
          <td>{{ $pago->users }}</td>
          <td>{{ $pago->observacion }}</td>
          <td>{{ $pago->total_deuda }}</td>
          <td>{{ $pago->total_pago }}</td>
          <td>{{ $pago->fecha }}</td>
          <td>{{ $pago->condicion }}</td>
        </tr>
        @php
          $cont++;
          $sum = ($pago->total) + $sum;
        @endphp
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4"></td>
        <td colspan=""></td>
        <td colspan="2">TOTAL</td>
        <td colspan="2">S/{{$sum}}</td>
      </tr>
    </tfoot>
  </table>

  <br>

  </div>
</body>

</html>
