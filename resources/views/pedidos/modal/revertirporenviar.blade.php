<!-- Modal -->
<div class="modal fade" id="modal-revertir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Revertir pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulariorevertir" name="formulariorevertir" enctype="multipart/form-data">
      <input type="text" id="hiddenRevertirpedido" name="hiddenRevertirpedido">
      <div class="modal-body">
        <p>Confirme si desea enviar el pedido <strong class="textcode">PED000</strong> al área de ENVIOS</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>