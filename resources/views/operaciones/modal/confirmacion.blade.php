  <!-- Modal -->
  <div class="modal fade" id="modal-confirmacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="titulo-confirmacion"  id="exampleModalLabel">Atender pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- Form::Open(['route' => ['pedidos.atender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
          <form id="formulario_confirmacion" name="formulariorecepcion" enctype="multipart/form-data">
              {{-- Form::Open(['route' => ['pedidos.envio', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
              <input type="hidden" id="hiddenCodigo" name="hiddenCodigo">

              <div class="modal-body">
                  <p id="msj-modal">Esta seguro que desea enviar el sobre <strong class="textcode"></strong> a Motorizados del <strong class="textzone"></strong></p>
              </div>
              {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('destino', 'Destino') !!}
                {!! Form::select('destino', $destinos , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
              </div> --}}
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-success">Confirmar</button>
              </div>
          </form>
      </div>
    </div>
  </div>
