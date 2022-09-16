<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF STOCK MINIMO</title>
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
  <h1 id="title">LISTA DE ARTICULOS CON STOCK MINIMO</h1>
  <table id="cabecera">
    <tr>
      <th colspan="1"><b>FECHA DE EMISION DE REPORTE:</b></th>
      <td><label> {{ $fecha }}</label></td>
    </tr>
  </table>
  <table id="detalle" class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">ITEM</th>
        <th scope="col">CODIGO</th>
        <th scope="col">PRODUCTO</th>
        <th scope="col">STOCK</th>
        <th scope="col">STOCK MINIMO</th>
      </tr>
    </thead>
    <tbody>
      @php
        $cont = 0;
      @endphp
      @if (!$articulosList == '')
        @foreach ($articulosList as $articulo)
          <tr>
            <td>{{ $cont + 1 }}</td>
            <td>{{ $articulo['codigo'] }}</td>
            <td>{{ $articulo['nombre'] }}</td>
            <td>{{ $articulo['stock'] }}</td>
            <td>{{ $articulo['stock_minimo'] }}</td>
          </tr>
          @php
            $cont++;
          @endphp
        @endforeach
      @else
        <tr>
          <td colspan="5"><b>SIN DATOS PARA MOSTRAR</b></td>
        </tr>
      @endif
    </tbody>
  </table>
</body>

</html>
