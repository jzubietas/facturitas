<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>PDF PEDIDO</title>
  <style>
    .textLayer {
  -webkit-touch-callout: none !important; /* iOS Safari */
  -webkit-user-select: none !important; /* Safari */
   -khtml-user-select: none !important; /* Konqueror HTML */
     -moz-user-select: none !important; /* Firefox */
      -ms-user-select: none !important; /* Internet Explorer/Edge */
          user-select: none !important; /* Non-prefixed version, currently
                                supported by Chrome and Opera */
}

    #tabla {
      width: 100%;
      /*border: 2px #000000 solid;*/
      border-collapse: collapse;
      font-family: Arial, Helvetica, sans-serif;
      table-layout:fixed;
    }

    #tabla td,
    #tabla th {
      width: 100%;
      text-align: center;
      vertical-align: top;
      /*border: 1px solid #000;*/
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

    .noselect {
      -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
          -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
                user-select: none; /* Non-prefixed version, currently
                                      supported by Chrome and Opera */
    }
  </style>
</head>

<body>
  {{-- <h1 id="title">PEDIDO</h1> --}}
  <table id="tabla" class="table ">
    @foreach ($pedidos as $pedido)
      <thead class="noselect">
        <tr style="border-bottom: 1px dashed #888; padding-bottom:8px; ">
          <th colspan="2">
              <div style="background: white; border-right: 1px solid #CCC;">
                  <span style="font-size: 16px;">{{ $pedido->empresas}} {{ $pedido->codigos }}</span><br>
                  <img style="margin-top:12px;" src="@php echo $codigo_barras_img @endphp" width="250">
              </div>
          </th>
            <th>

                <img src="@php echo $codigo_qr_img @endphp" width="100">
                <p style="font-size:12px; color:#888;">Escanee el QR para recibir este pedido</p>
            </th>
        </tr>
      <tr>
          <td colspan="3">
              <h2>PEDIDO</h2>
          </td>
      </tr>

      </thead>
      <tbody>
        <tr class="noselect">

        @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{ $pedido->codigos }}<br><br></td>
        @else
            <th scope="row"><br><span class="textLayer">CODIGO</span><br></th>
            <td colspan="2"><br><span class="textLayer">{{ $pedido->codigos }}</span><br></td>
        @endif
        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td>
              <br>@php echo number_format($pedido->cantidad,2) @endphp<br>
            </td>
          @else
            <th scope="row"><br>CANTIDAD<br></th>
            <td colspan="2">
              <br>@php echo number_format($pedido->cantidad,2) @endphp<br>
            </td>
          @endif
        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{ $pedido->ruc }}<br></td>
          @else
            <th scope="row"><br>RUC<br></th>
            <td colspan="2"><br>{{ $pedido->ruc }}<br></td>
          @endif

        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{ $pedido->empresas }}<br></td>
          @else
            <th scope="row"><br>RAZON SOCIAL<br></th>
            <td colspan="2"><br>{{ $pedido->empresas }}<br></td>
          @endif


        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
                <td><br>{{ $pedido->mes }}<br></td>
          @else
            <td scope="row"><br><b>MES</b><br></td>
            <td colspan="2"> <br>{{ $pedido->mes }}<br></td>
          @endif

        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{ $pedido->anio }}<br><br></td>
          @else
            <th scope="row"><br>AÑO<br></th>
            <td colspan="2"><br>{{ $pedido->anio }}<br></td>
          @endif

        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{ $pedido->tipo_banca }}<br></td>
          @else
            <th scope="row"><br>TIPO<br></th>
            <td colspan="2"><br>{{ $pedido->tipo_banca }}<br></td>
          @endif

        </tr>

        @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
          <tr>
            <td colspan="2"><p style="font-size: 14px;">{{ $pedido->descripcion }}</div></p></td>
          </tr>
        @else
          <tr>
            <th colspan="2" style="background: white">DESCRIPCIÓN</th>
          </tr>
          <tr>
            <td colspan="2"><p style="font-size: 14px;">{{ $pedido->descripcion }}</div></p></td>
          </tr>
        @endif

        @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
          <tr>
            <td colspan="2"><p style="font-size: 16px;">{{ $pedido->nota }}</p></td>
          </tr>
        @else
          <tr>
            <th colspan="2" style="background: white">NOTA</th>
          </tr>
          <tr>
            <td colspan="2"><p style="font-size: 16px;">{{ $pedido->nota }}</p></td>
          </tr>
        @endif


      </tbody>
      <tfoot>
        <tr><td colspan="2"></td></tr>
      </tfoot>
    @endforeach
  </table>
</body>

</html>
