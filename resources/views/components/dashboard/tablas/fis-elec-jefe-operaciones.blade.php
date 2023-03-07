<div class="card">
  <div class="card-body">

    <table class="table border table-striped">
      <thead>
      <tr>
        <th style="background-color: #4c5eaf; text-align: left; color: white;">Nº</th>
        <th style="background-color: #4c5eaf; text-align: left; color: white;">OPERARIOS</th>
        <th style="background-color: #4c5eaf; text-align: left; color: white;" class=" text-center">
          ELECTRONICA
        </th>
        <th style="background-color: #4c5eaf; text-align: left; color: white;" class=" text-center">FISICA</th>
        <th style="background-color: #4c5eaf; text-align: left; color: white;" class=" text-center">TOTAL</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($resultados as $b)
        <tr>
          <td>{{ $loop->index + 1 }}</td>
          <td>{{ $b->operario }}</td>
          <td>
            <div class="p-2 rounded text-white text-center" style="background: #e91e63;">
              <h6> {{ $b->electronico }}</h6>
            </div>
          </td>
          <td>
            <div class="p-2 rounded text-white text-center" style="background: #00bcd4;">
              <h6> {{ $b->fisico }}</h6>
            </div>
          </td>
          <td>
            <div class="p-2 rounded text-white text-center" style="background: #000;">
              <h6> {{ ((float)$b->fisico+(float)$b->electronico) }}</h6>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
