<div class="card">
  <div class="card-body">

    <table class="table border table-striped">
      <thead>
      <tr>
        <th style="text-align: left; color: white;" class="bg-danger align-middle">Nº</th>
        <th style="text-align: left; color: white;" class="bg-danger align-middle">Jefe o Supervisor</th>
        <th style="text-align: left; color: white;" class="bg-danger align-middle">Nombres</th>
        <th style="text-align: left; color: white;" class="bg-danger align-middle text-center">QTY Llamada atencion</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($resultados as $usuarios)
        <tr>
          <td>{{ $loop->index + 1 }}</td>
          <td>{{ $usuarios->nombre_jefe }}</td>
          <td>{{ $usuarios->name }}</td>
          <td>
            <div class="p-2 rounded text-white text-center" style="background: #e91e63;">
              <h6> {{ $usuarios->cant_vidas_cero }}</h6>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
