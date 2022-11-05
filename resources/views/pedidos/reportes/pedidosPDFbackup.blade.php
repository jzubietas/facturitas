<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">  

  <title>PDF PEDIDO</title> 
  <style>
    #tabla {
      width: 100%;
      border: 2px #000000 solid;
      border-collapse: collapse;
      font-family: Arial, Helvetica, sans-serif;
      table-layout:fixed;
    }

    #tabla td,
    #tabla th {
      width: 100%;
      text-align: center;
      vertical-align: top;
      border: 1px solid #000;
      font-size:20px;    
      table-layout:fixed;  
    }

    #detalle th {
      padding-top: 12px;
      padding-bottom: 12px;
    }

    #title {
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
    }
  </style>
</head>

<body>
  {{-- <h1 id="title">PEDIDO</h1> --}}
  <table id="tabla" class="table table-bordered">
    @foreach ($pedidos as $pedido)          
      <thead>
        <tr>
          <th colspan="2" style="background: #44A3CA"><h2>PEDIDO</h2></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row"><br>CODIGO<br><br></th>
          <td><br>{{ $pedido->codigos }}<br><br></td>
        </tr>
        <tr>
          <th scope="row"><br>CANTIDAD<br><br></th>
          <td>{{-- {{ $pedido->cantidad }} --}}
            <br>@php echo number_format($pedido->cantidad,2) @endphp<br><br>
          </td>
        </tr>
        <tr>
          <th scope="row"><br>RUC<br><br></th>
          <td><br>{{ $pedido->ruc }}<br><br></td>
        </tr>
        <tr>
          <th scope="row"><br>RAZON SOCIAL<br><br></th>
          <td><br>{{ $pedido->empresas }}<br><br></td>
        </tr>
        <tr>
          <th scope="row"><br>MES<br><br></th>
          <td><br>{{ $pedido->mes }}<br><br></td>
        </tr>
        <tr>
          <th scope="row"><br>AÑO<br><br></th>
          <td><br>{{ $pedido->anio }}<br><br></td>
        </tr>
        <tr>
          <th scope="row"><br>FISICO O ELECTRONICO<br><br></th>
          <td><br>{{ $pedido->tipo_banca }}<br><br></td>
        </tr>
        <tr>
          <th colspan="2" style="background: #44A3CA">DESCRIPCIÓN</th>
        </tr>
        <tr>
          <td colspan="2"><p>{{ $pedido->descripcion }}</div></p></td>
        </tr>
        <tr>
          <th colspan="2" style="background: #44A3CA">NOTA</th>
        </tr>
        <tr>
          <td colspan="2"><p>{{ $pedido->nota }}</p></td>
        </tr>
      </tbody>
      <tfoot>
        <tr><td colspan="2"></td></tr>
      </tfoot> 
    @endforeach
  </table>
</body>

</html>
