<!-- Modal -->
<div class="modal fade" id="modal-destino" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-outline-dark">
        <h5 class="modal-title" id="exampleModalLabel">Destino de pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- Form::Open(['route' => ['pedidos.destino', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
      <div class="modal-body">
        <p>Elija el destino de envío del pedido: <strong>PED000</strong></p>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        {!! Form::label('destino', 'Destino') !!}                      
        {!! Form::select('destino', [] , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>