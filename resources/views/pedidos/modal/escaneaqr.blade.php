<!-- Modal -->
<div class="modal fade" id="modal-escanear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
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
             <div class="col-lg-6 border-right">
                 <h4 class="font-16">Por favor, escanee el documento para confirmarlo:</h4>

                 <img src="{{asset('imagenes/scan.gif')}}" width="80%"><br>

                 <input type="text" value="" id="codigo_confirmar" placeholder="00-0000-0" name="hiddenCodigo" style="opacity: 0.5;
    border: 1px solid #bbbbbb;
    border-radius: 4px;
    padding: 8px;
    font-size: 20px;">
                 <input type="text" value="12" id="codigo_accion" name="accion" style="opacity: 0">
                 <input type="hidden" value="" id="codigo_responsable" name="codigo_responsable">

                 <p id="respuesta_barra"></p>
             </div>
              <div class="col-lg-6 text-left pl-20">
                  <h4 class="font-16 font-weight-bold">Pedidos procesados:</h4>
                  <ul id="pedidos-procesados">

                  </ul>
              </div>
          </div>


      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="close-scan" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
