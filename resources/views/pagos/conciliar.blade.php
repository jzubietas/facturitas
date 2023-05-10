  <!-- Modal -->
  <div class="modal fade" id="modal-conciliar-get" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <table id="tablaPrincipalConciliar" class="table table-striped" style="text-align: center">
                <thead><h4 style="text-align: center"><strong>Listado de la clientes con pedidos con deuda</strong></h4>
                  <tr>
                    <th scope="col">item</th>
                    <th scope="col">Titular</th>
                    <th scope="col">Banco</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Movimiento</th>
                    <th scope="col">Monto</th>
                  </tr>
                </thead>
                <tbody>
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
