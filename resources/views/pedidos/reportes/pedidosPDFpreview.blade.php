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

      <thead class="noselect">
        <tr >
          <th colspan="2" style="background: white"><h2>PEDIDO</h2></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td style="font-weight: bolder;">
              <br>{{$pcantidad}}<br><br>
            </td>
          @else
            <th scope="row"><br>*CANTIDAD*<br><br></th>
            <td style="font-weight: bolder;">
              <br>{{$pcantidad}}<br><br>
            </td>
          @endif
        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{$pruc}}<br><br></td>
          @else
            <th scope="row"><br>*RUC*<br><br></th>
            <td><br>{{$pruc}}<br><br></td>
          @endif

        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td style="font-weight: bolder;"><br>{{$pempresa}}<br><br></td>
          @else
            <th scope="row"><br>*RAZON SOCIAL*<br><br></th>
            <td style="font-weight: bolder;"><br>{{$pempresa}}<br><br></td>
          @endif


        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td style="font-weight: bolder;"><br>{{$pmes}}<br><br></td>
          @else
            <th scope="row"><br>*MES*<br><br></th>
            <td style="font-weight: bolder;"><br>{{$pmes}}<br><br></td>
          @endif

        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{$panio}}<br><br></td>
          @else
            <th scope="row"><br>*AÑO*<br><br></th>
            <td><br>{{$panio}}<br><br></td>
          @endif

        </tr>
        <tr>
          @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
            <td><br>{{$ptipo_banca}}<br><br></td>
          @else
            <th scope="row"><br>*TIPO*<br><br></th>
            <td><br>{{$ptipo_banca}}<br><br></td>
          @endif

        </tr>

        @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
          <tr>
            <td colspan="2"><p>{{$pdescripcion}}</div></p></td>
          </tr>
        @else
          <tr>
            <th colspan="2" style="background: white">*DESCRIPCIÓN*</th>
          </tr>
          <tr>
            <td colspan="2"><p>{{$pdescripcion}}</div></p></td>
          </tr>
        @endif

        @if ( ($mirol=="Asesor") && ($identificador=="06" || $identificador=="07" || $identificador=="08" || $identificador=="09" || $identificador=="10" ) )
          <tr>
            <td colspan="2"><p>{{$pnota}}</p></td>
          </tr>
        @else
          <tr>
            <th colspan="2" style="background: white">*NOTA*</th>
          </tr>
          <tr>
            <td colspan="2"><p>{{$pnota}}</p></td>
          </tr>
        @endif


      </tbody>
      <tfoot>
        <tr><td colspan="2"></td></tr>
      </tfoot>

  </table>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>


</body>


</html>
