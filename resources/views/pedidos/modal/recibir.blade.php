<!-- Modal -->
<div class="modal fade" id="modal-recibir-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel">Recibir sobre</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::Open(['route' => ['envios.recibir', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) }}
      <div class="modal-body">
        <p>Confirme que recibió el sobre del pedido: <strong>PED000{{ $pedido->id }}</strong></p>
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-warning">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>