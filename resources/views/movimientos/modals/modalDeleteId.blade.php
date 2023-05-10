  <!-- Modal -->
  <div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar Movimiento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formdelete" name="formdelete">
        <input type="hidden" id="hiddenIDdelete" name="hiddenIDdelete" class="form-control"> 
        <!--{{-- Form::Open(['route' => ['pagos.destroy', $pago['id']], 'method' => 'delete']) --}}-->
        <div class="modal-body">
          <p>Confirme si desea <strong>ELIMINAR</strong> Movimiento: <strong class="textcode"></strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-danger">Confirmar</button>
        </div>
        <!--{{ Form::Close() }}-->
        </form>
      </div>
    </div>
  </div>