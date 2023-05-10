<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF VENTAS</title>
  <style>
    #cabecera {
      width: 100%;
      border: #4c5eaf solid;
      border-collapse: collapse;
      font-family: Arial, Helvetica, sans-serif;
    }

    #cabecera th {
      width: 22%;
      text-align: right;
      background-color: #4c5eaf;
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
      padding: 10px;
      text-align: center;
    }

    #detalle th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: center;
      background-color: #4c5eaf;
      color: #fff;
    }

    #title {
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
    }

  </style>
</head>

<body>
  <h1 id="title">LISTA DE VENTAS POR FECHAS</h1>
  <table id="cabecera" border="1">
    <tr>
      <th colspan="2"><b>DESDE:</b></th>
      <td><label> {{ $request->desde }}</label></td>
    </tr>
    <tr>
      <th colspan="2"><b>HASTA:</b></th>
      <td><label> {{ $request->hasta }}</label></td>
    </tr>
    <tr>
      <th colspan="2"><b>FECHA DE EMISION DE REPORTE:</b></th>
      <td><label> {{ $fecha }}</label></td>
    </tr>
  </table>
  <table id="detalle" class="table table-bordered">
    <thead>
      <tr>
        <th scope="col" style="vertical-align: middle">ITEM</th>
        <th scope="col" style="vertical-align: middle">COMPROBANTE</th>
        <th scope="col" style="vertical-align: middle">CLIENTE</th>
        <th scope="col" style="vertical-align: middle">MONTO</th>
        <th scope="col" style="vertical-align: middle">FECHA</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 0; $sum =0;?>
      @foreach ($ventas as $venta)
        <tr>
          <td>{{ $cont + 1 }}</td>
          <td>{{ $venta->tipo_comprobante }}-00{{ $venta->id }}</td>
          <td>{{ $venta->clientes }}</td>
          <td>{{ $venta->total }}</td>
          <td>{{ $venta->fecha }}</td>
        </tr>
        <?php $cont++; $sum = $sum + $venta->total;?>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="3" style="text-align: right;"><b>TOTAL:</b></th>
      <th>{{ $sum }}</th>
      <th></th>
    </tfoot>
  </table>
</body>

</html>
