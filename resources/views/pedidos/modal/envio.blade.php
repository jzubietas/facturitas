<!-- Modal -->
<div class="modal fade" id="modal-envio-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Enviar pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::Open(['route' => ['pedidos.envio', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) }}
      <div class="modal-body">
        {{-- <p>Elija el tipo de envío del pedido: <strong>PED000{{ $pedido->id }}</strong></p> --}}
        <p>Confirme si desea revertir el pedido <strong>PED000{{ $pedido->id }}</strong></p>
      </div>
      {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        {!! Form::label('destino', 'Destino') !!}
        {!! Form::select('destino', $destinos , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
      </div> --}}
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>
