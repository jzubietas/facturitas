  <!-- Modal -->
  <div class="modal fade" id="modal-add-pedidos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Pedidos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('ppedido_id', 'Pedido') !!}
              {{-- {!! Form::select('ppedido_id', [], null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'ppedido_id']) !!} --}}
              <select name="ppedido_id" id="ppedido_id" class="border form-control border-secondary">
                <option value="">---- SELECCIONE ----</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="add_pedido" data-dismiss="modal" {{-- onclick="Remove_options()" --}}>Agregar</button>
        </div>
        {{-- {{ Form::Close() }} --}}
      </div>
    </div>
  </div>
