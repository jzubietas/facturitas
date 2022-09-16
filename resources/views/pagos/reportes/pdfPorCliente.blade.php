<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF CONTRATOS</title>
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

    #detalle td, #detalle th {
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
  <h1 id="title">LISTA DE CONTRATOS POR CLIENTE</h1>

  <table id="cabecera">
    <tr>
      <th><b>CLIENTE:</b></th>
      <td><label> {{ $clientes->razon_social }}</label></td>
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
        <th scope="col">CONTRATO</th>
        <th scope="col">FECHA CONTRATACIÓN</th>
        <th scope="col">FECHA FINALIZACIÓN</th>
        <th scope="col">CONDICIÓN</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 0; ?>
      @foreach ($contratos as $contrato)
        <tr>
          <td>{{ $cont + 1 }}</td>
          <td>CON00{{ $contrato->id }}</td>
          <td>{{ $contrato->fecha_contratacion }}</td>
          <td>{{ $contrato->fecha_finalizacion }}</td>
          <td>{{ $contrato->condicion }}</td>
        </tr>
        <?php $cont++; ?>
      @endforeach
    </tbody>
  </table>
</body>
</html>
