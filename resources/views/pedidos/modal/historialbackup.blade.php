  <!-- Modal -->
  <div class="modal fade" id="modal-historial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="exampleModalLabel">Clientes con <b>deuda</b></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="card-body border border-secondary rounded">
              <table id="tablaPrincipal" class="table table-striped" style="text-align: center">
                <thead><h4 style="text-align: center"><strong>Listado de la clientes con pedidos con deuda</strong></h4>
                  <tr>
                    <th scope="col">item</th>
                    <th scope="col">Cliente</th>
                    {{-- <th scope="col">Comentario</th> --}}
                    <th scope="col">Estado</th>
                  </tr>
                </thead>
                @php
                $cont = 0;
                @endphp
                <tbody>
                  @foreach ($deudores as $deuda)
                  <tr>
                    <td>{{ $cont+1 }}</td>
                    <td>{{ $deuda->celular }} - {{ $deuda->nombre }}</td>
                    <td><span class="badge badge-danger">Deudor</span></td>
                  </tr>
                  @php
                  $cont++;
                  @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{ route('pedidos.sinpagos') }}" class="btn btn-danger btn-sm">Ver deudores</a>
          <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
