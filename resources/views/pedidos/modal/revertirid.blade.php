<!-- Modal recibir -->
<div class="modal fade" id="modal-revertir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="exampleModalLabel">Revertir</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <form id="formulariorevertir" name="formulariorevertir" enctype="multipart/form-data">
          <input type="hidden" id="hiddenRecibir" name="hiddenRecibir">
          <div class="modal-body">
            <p>Confirme que desea revertir el pedido: <strong class="textcode">ENV000</strong></p>
          </div>      
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-warning">Confirmar</button>
          </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>