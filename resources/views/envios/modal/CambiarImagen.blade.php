  <!-- Modal -->
  <div class="modal fade" id="modal-cambiar-imagen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="modal-cambiar-imagen-title">Cambiar Imagen 1</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

        <input type="hidden" value="" name="cambiapedido" id="cambiapedido">
        <input type="hidden" value="" name="cambiaitem" id="cambiaitem">

          <div class="form-row">

            <div class="form-group col-lg-6">
              <div class="image-wrapper">
                <img id="picture" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" width="250px">
              </div>
            </div>

            <div class="form-group col-lg-6">
              {!! Form::label('pimagen', 'Imagen') !!}
              {!! Form::file('pimagen', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="change_imagen" >Agregar</button>
        </div>

      </div>
    </div>
  </div>
