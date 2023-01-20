<!-- Modal -->
<div class="modal fade" id="modal-escanear-estado-sobre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" style="max-width: 850px;">
    <div class="modal-content br-16 cnt-shw border-0">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fa fa-barcode mr-12" aria-hidden="true"></i> <span id="titulo-scan">Escanear Pedido</span></h5>
          <div id="option-modal-extra"></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">

          <div class="row">
              <div class="col-lg-12 text-left pl-20">
                  <div id="info-pedido">
                      <div class="text-center">
                      <img src="{{asset('imagenes/scan.gif')}}" width="300" class="mr-8">
                      <h5 class="font-weight-bold">Escanee un pedido para saber sus detalles</h5>
                      </div>
                  </div>
              </div>
          </div>


      </div>
      <div class="modal-footer">
          <input type="text" value="" id="input-info-pedido" placeholder="00-0000-0" name="hiddenCodigo" style="opacity: 0.5;
    border: 1px solid #bbbbbb;
    border-radius: 4px;
    padding: 4px;
    font-size: 17px;">
        <button class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
