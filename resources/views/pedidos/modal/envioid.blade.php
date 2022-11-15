<!-- Modal -->
<div class="modal fade" id="modal-envio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Enviar pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulario" name="formulario" enctype="multipart/form-data">
      {{-- Form::Open(['route' => ['pedidos.envio', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
      <div class="modal-body">
        <p>Confirme si desea enviar el pedido <strong class="textcode">PED000</strong> al área de ENVIOS</p>
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