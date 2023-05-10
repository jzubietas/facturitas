  <!-- Modal -->
  <div class="modal fade" id="modal-conciliar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="exampleModalLabel">Conciliar Movimiento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formconciliar" name="formconciliar">
        <input type="hidden" id="hiddenIDconciliar" name="hiddenIDconciliar" class="form-control"> 
        <!--{{-- Form::Open(['route' => ['pagos.destroy', $pago['id']], 'method' => 'delete']) --}}-->
        <div class="modal-body">
          <p>Esta seguro que desea <strong>CONCILIAR con el importe <strong class="textimporte"></strong></p>
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