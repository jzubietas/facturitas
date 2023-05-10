<!-- Modal -->
<div class="modal fade" id="modal-envio-op" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="titulo-modal-op" id="exampleModalLabel">Confirmar recepción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulario_atender_op" name="formulario_atender_op" enctype="multipart/form-data">
      {{-- Form::Open(['route' => ['pedidos.envio', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
      <input type="hidden" id="hiddenEnvioOP" name="hiddenEnvio">
          <input type="hidden" id="hiddenGroup" name="hiddenGroup">
      <div class="modal-body">
          <p id="msj-modal">Esta seguro que desea Enviar el Pedido <strong class="textcode">PED000</strong> a Courier?</p>

          {{--<p>Esta seguro de confirmar la recepción del Pedido <strong class="textcode"></strong>?</p>--}}
      </div>
      {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        {!! Form::label('destino', 'Destino') !!}
        {!! Form::select('destino', $destinos , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
      </div> --}}
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="conf-modal-OP" >ENVIO A COURIER_JEFE OPE</button>
      </div>
    </form>
    </div>
  </div>
</div>
