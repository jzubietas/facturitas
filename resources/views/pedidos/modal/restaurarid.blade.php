<!-- Modal -->
<div class="modal fade" id="modal-restaurar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Restaurar pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form id="formrestaurar" name="formrestaurar">
      <input type="hidden" id="hiddenIDrestaurar" name="hiddenID" class="form-control">
      <div class="modal-body">
        <p>Confirme si desea <strong>RESTAURAR</strong> el pedido: <strong class="textcode">PED000</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Confirmar</button>
      </div>
      </form>
    </div>
  </div>
</div>