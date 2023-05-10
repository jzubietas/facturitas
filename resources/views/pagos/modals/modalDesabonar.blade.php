  <!-- Modal -->
  <div class="modal fade" id="modal-desabonar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="exampleModalLabel">Desabonar Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formdesabonar" name="formdesabonar">
        <input type="hidden" id="hiddenDesabonar" name="hiddenDesabonar" class="form-control"> 
        <!--{{-- Form::Open(['route' => ['pagos.destroy', $pago['id']], 'method' => 'delete']) --}}-->
        <div class="modal-body">
          <p>Confirme si desea <strong>DESABONAR</strong> pago: <strong class="textcode"></strong></p>
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