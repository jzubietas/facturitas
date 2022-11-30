  <!-- Modal -->
  <div class="modal fade" id="modal-conciliar-get" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-grey">
          <h5 class="modal-title" id="exampleModalLabel"><b>Conciliar</b></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="card-body border border-secondary rounded">

              

              <div class="form-row">

                <div class="col">
                  Titular
                  <input type="text" class="form-control modalimagen_titular bg-primary" readonly>
                </div>
                <div class="col">
                  Banco
                  <input type="text" class="form-control modalimagen_banco bg-primary" readonly>
                </div>
                <div class="col">
                  Fecha
                  <input type="text" class="form-control modalimagen_fecha bg-primary" readonly>
                </div>
                <div class="col">
                  Monto
                  <input type="text" class="form-control modalimagen_monto bg-primary" readonly>
                </div>
      
      
                <div class="col d-none">
                  item
                  <input type="text" class="form-control modalimagen_item" readonly>
                </div>
                <div class="col d-none">
                  pago
                  <input type="text" class="form-control modalimagen_pago" readonly>
                </div>
                
              </div>
              <br>

              {{--<div class="table-responsive">--}}
              <div class="container-fluid"></div>
                <table id="tablaPrincipalConciliar" class="table table-striped" >
                  <thead><h4 style="text-align: center"><strong>Listado de la movimientos para conciliar con los pagos</strong></h4>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Titular</th>
                      <th scope="col">Banco</th>
                      <th scope="col">Fecha</th>
                      <th scope="col">Movimiento</th>
                      <th scope="col">Importe</th>
                      <th scope="col">Conciliar</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>   
          </div>
        </div>  
        <div class="modal-footer">
          {{--<a href="{{ route('pedidos.sinpagos') }}" class="btn btn-danger btn-sm">Ver deudores</a>--}}
          <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  