<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">NOMBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CELULAR</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($usuarios1 as $usuario)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>USER00{{ $usuario->id }}</td>
        <td>{{ $usuario->name }}</td>
        <td>{{ $usuario->email }}</td>        
      </tr>
      <?php $cont++; ?>
    @endforeach
   
  </tbody>
</table>
