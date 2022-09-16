<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CODIGO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ARTICULOS</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CATEGORIA</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DESCRIPCION</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">STOCK</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">STOCK MINIMO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">PRECIO DE COMPRA</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">PRECIO DE VENTA</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($articulos as $articulo)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>{{ $articulo->codigo }}</td>
        <td>{{ $articulo->articulo }}</td>
        <td>{{ $articulo->categoria }}</td>
        <td>{{ $articulo->descripcion }}</td>
        <td>{{ $articulo->stock }}</td>
        <td>{{ $articulo->stock_minimo }}</td>
        <td>{{ $articulo->precio_compra }}</td>
        <td>{{ $articulo->precio }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
