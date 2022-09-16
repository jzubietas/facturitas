<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF CONTRATO</title>
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
      float: left;
      margin-left: 1%;
      width: 50%;
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

    /**/

    #detalle1 {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      float: right;
      width: 50%;
    }

    #detalle1 td,
    #detalle1 th {
      border: 1px, solid #ddd;
      padding: 10px;
      text-align: center;
    }

    #detalle1 th {
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
  <h1 id="title">CONTRATO CON00{{ $contrato->id }}</h1>

  <table id="cabecera">
    <tr>
      <th><b>CONTRATO:</b></th>
      <td><label> CON00{{ $contrato->id }}</label></td>
    </tr>
    <tr>
      <th><b>CLIENTE:</b></th>
      <td><label> {{ $cliente->numero_documento }} - {{ $cliente->razon_social }}</label></td>
    </tr>
    <tr>
      <th><b>FECHA DE CONTRATACIÓN:</b></th>
      <td><label> {{ $contrato->fecha_contratacion }}</label></td>
    </tr>
    <tr>
      <th><b>FECHA DE FINALIZACIÓN:</b></th>
      <td><label> {{ $contrato->fecha_finalizacion }}</label></td>
    </tr>
    <tr>
      <th><b>CONDICIÓN:</b></th>
      <td><label> {{ $contrato->condicion }}</label></td>
    </tr>
    <tr>
      <th><b>FECHA:</b></th>
      <td><label> {{ $fecha }}</label></td>
    </tr>
  </table>

  <table id="detalle" class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">ITEM</th>
        <th scope="col">PRODUCTO</th>
        <th scope="col">CANTIDAD</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 0; ?>
      @foreach ($productos as $producto)
        <tr>
          <td>{{ $cont + 1 }}</td>
          <td>{{ '(' }}PRO00{{ $producto->producto_id }}{{ ')' }} - {{ $producto->producto }}</td>
          <td>{{ $producto->cantidad }}</td>
        </tr>
        <?php $cont++; ?>
      @endforeach
    </tbody>
  </table>
  <table id="detalle1" class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">ITEM</th>
        <th scope="col">PRODUCTO</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 0; ?>
      @foreach ($servicios as $servicio)
        <tr>
          <td>{{ $cont + 1 }}</td>
          <td>{{ '(' }}SER00{{ $servicio->servicio_id }}{{ ')' }} - {{ $servicio->servicio }}</td>
        </tr>
        <?php $cont++; ?>
      @endforeach
    </tbody>
  </table>
</body>

</html>
