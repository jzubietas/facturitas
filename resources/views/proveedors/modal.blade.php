<!-- Modal -->
<div class="modal fade" id="modal-delete-{{ $proveedor->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar proveedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::Open(['route' => ['proveedors.destroy', $proveedor->id], 'method' => 'delete']) }}
      <div class="modal-body">
        <p>Confirme si desea <strong>ELIMINAR</strong> el proveedor: <strong>PRO00{{ $proveedor->id }}</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>
