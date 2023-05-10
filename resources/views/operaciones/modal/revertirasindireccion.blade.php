<!-- Modal -->
<div class="modal fade" id="modal-revertir-asindireccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Revertir pedido a sin direccion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulariorevertirasindireccion" name="formulariorevertirasindireccion" enctype="multipart/form-data">
      <input type="hidden" id="asindireccionrevertir" name="asindireccionrevertir">
      <div class="modal-body">
        <p>Confirme si desea revertir el pedido <strong class="textcode">PED000</strong> a Sobres Sin Direccion</p>
        <p class="d-none">Recuerde que tiene <strong class="textcantadjunto"></strong> archivos adjunto(s)</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>
