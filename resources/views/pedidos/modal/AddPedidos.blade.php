  <!-- Modal -->
  <div class="modal fade" id="modal-add-pedidos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Agregar pedidos a enviar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('ppedido_id', 'Pedido') !!}
              <select name="ppedido_id" class="border form-control border-secondary" id="ppedido_id" data-live-search="true">
                <option value="">---- SELECCIONE PEDIDO ----</option>
                  @foreach($pedidos as $pedido)
                    <option value="{{ $pedido->id }}_{{$pedido->codigo}}">{{$pedido->codigo}}</option>   
                  @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="add_pedido" data-dismiss="modal">Agregar</button>
        </div>
      </div>
    </div>
  </div>
