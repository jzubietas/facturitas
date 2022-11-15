  <!-- Modal -->
  <div class="modal fade" id="modal-historial-pagos-pedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1000px!important;">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="exampleModalLabel"><b>Historial</b> de Pagos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="card-body border border-secondary rounded">
              <table id="tablapagospedidoshistorial" class="table table-striped" style="text-align: center">
                <thead><h4 style="text-align: center"><strong>Listado de Pagos por el pedido</strong></h4>
                  <tr>
                    <th scope="col">Pago</th>
                    <th scope="col">Codigos</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Observacion</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Condicion</th>
                    <th scope="col">Accion</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>   
          </div>
        </div>  
        <div class="modal-footer">
          <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
