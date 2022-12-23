<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">NOMBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CELULAR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ASESOR ASIGNADO</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>
    @foreach ($base_fria as $bf)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>BF00{{ $bf->id }}</td>
        <td>{{ $bf->nombre }}</td>
        <td>{{ $bf->celular  }} </td>
        <td>{{ $bf->users }}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
