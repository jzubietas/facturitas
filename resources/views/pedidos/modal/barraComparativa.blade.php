<!-- Modal -->
<div class="modal fade show" id="modal-scan-comparador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content br-16 cnt-shw border-0">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fa fa-barcode mr-12" aria-hidden="true"></i> <span id="titulo-scan">Escanear Pedido</span></h5>
          <div id="option-modal-extra">
          </div>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
          <div class="row">
             <div class="col-lg-6 border-right text-left">
                 <h5 class="font-weight-bold"> Listado de codigos de hoy </h5>
                 <div class="row" id="pedidos-recepcion"></div>
             </div>

              <div class="col-lg-6 text-left pl-20">
                  <h5 class="font-weight-bold"> Listado ya escaneados</h5>
                  <div id="pedidos-escaneados" class="row"></div>
              </div>
          </div>
      </div>
      <div class="modal-footer">
          <input type="text" value="" id="codigo_comprobar" placeholder="00-0000-0" name="hiddenCodigo" style="    opacity: 0.5;
    border: 1px solid #bbbbbb;
    border-radius: 4px;
    padding: 4px;
    font-size: 16px;">
        <button class="btn btn-danger" id="close-scan" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
