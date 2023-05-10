<!DOCTYPE html>
<html lang="es">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TICKET VENTA</title>
    <style>
      * {
      font-size: 12px;
      font-family: 'Times New Roman';
      }

      td,
      th,
      tr,
      table {
          border-top: 1px solid black;
          border-collapse: collapse;
      }

      td.producto,
      th.producto {
          width: 130px;
          max-width: 130px;
      }

      td.cantidad,
      th.cantidad {
          width: 60px;
          max-width: 60px;
          word-break: break-all;
      }

      td.precio,
      th.precio {
          width: 60px;
          max-width: 60px;
          word-break: break-all;
      }

      .centrado {
          text-align: center;
          align-content: center;
      }

      .ticket {
          width: 270px;
          max-width: 270px;
      }

      img {
          max-width: inherit;
          width: inherit;
      }
    </style>
  </head>

  <body>
    <div class="ticket">
      <p class="centrado">
                  <br>LIBRERIA - BAZAR
                  <br><b style="font-size:20px">"LIDIA"</b>
                  <br>Av. Pacasmayo Mz A Lt 8
                  <br>Urb. Los Robles De Santa Rosa - SMP
                  <br>Cel: 928 823 357
                  <br>R.U.C.: 10773826477
                  <br><b>BOLETA DE VENTA
                  @foreach ($ventas as $v)
                  <br>Nª 000{{ $v->id }}</b>
                  <br>Cliente: {{ $v->clientes }}
                  <br>Vendedor: {{ $v->users}}
                  @endforeach
                  <br>
                  <br>{{ $fecha }}</p>
      <br>
      <table width: 100%;>
        <thead>
          <tr>
            <th class="cantidad" style="vertical-align: middle">CANT</th>
            <th class="producto" style="vertical-align: middle">PRODUCTO</th>
            <th class="precio" style="vertical-align: middle">S/</th>
          </tr>
        </thead>
        <tbody>
          @php $sum = 0; @endphp
          @foreach ($detalleVentas as $dv)
          <tr>
            <td class="cantidad">{{ $dv->cantidad }}</td>
            <td class="producto">{{ $dv->articulos }}</td>
            <td class="precio">s/{{ $dv->subtotal }}</td>
          </tr>
          @php $sum += $dv->subtotal; @endphp
          @endforeach
          <tr>
            <td class="cantidad"></td>
            <td class="producto">TOTAL</td>
            <td class="precio">s/@php echo $sum; @endphp</td>
          </tr>
        </tbody>
        <tfoot>
        </tfoot>
      </table>
      <p class="centrado">¡GRACIAS POR SU COMPRA!
        <br><br>  OVP Dynamic</p>

      {{-- <button class="oculto-impresion" onclick="imprimir()">Imprimir</button> --}}
      <a href="{{ route('ventas.index') }}" class="btn btn-info btn-sm"><button class="oculto-impresion">Volver</button></a>
    </div>
  </body>

</html>

<style>
  @media print {
    .oculto-impresion,
    .oculto-impresion * {
        display: none !important;
    }
  }
</style>

<script src="{{ asset('js/impresora.js') }}"></script>

<script>
window.onload = imprimir();//imprime sin botón

  function imprimir() {
      window.print();
    }
</script>
