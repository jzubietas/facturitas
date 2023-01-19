<!-- Modal -->
<div class="modal fade show" id="modal-scan-comparador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content br-16 cnt-shw border-0">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fa fa-barcode mr-12" aria-hidden="true"></i> <span id="titulo-scan">Escanear Pedido</span></h5>
          <div id="option-modal-extra">

              <select class="form-control ml-24" id="zona-consulta">
                  <option>NORTE</option>
                  <option>CENTRO</option>
                  <option>SUR</option>
              </select>
          </div>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
          <div class="row">
             <div class="col-lg-6 border-right">
                 <h5> Listado de codigos de hoy </h5>
                 <ul id="pedidos-recepcion"></ul>
             </div>

              <div class="col-lg-6 text-left pl-20">
                  <h5> Listado ya escaneados</h5>
                  <ul id="pedidos-escaneados"></ul>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="close-scan" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
