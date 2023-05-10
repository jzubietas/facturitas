<!-- Modal -->
<div class="modal fade" id="modal-envio-recojo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Confirmar Recojo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulario-recepcion-recojo" name="formulario-recepcion-recojo" enctype="multipart/form-data">
        <input type="hidden" id="hiddenIdGrupoPedido" name="hiddenIdGrupoPedido">
        <div class="modal-body">
          <p>Esta seguro(a) de confirmar la recepción del Recojo <strong class="textcode"></strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Confirmar</button>
        </div>
      </form>
    </div>
  </div>
</div>
