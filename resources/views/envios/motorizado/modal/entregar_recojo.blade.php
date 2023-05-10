<!-- Modal -->
<div class="modal fade" id="modal_recojomotorizado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px!important;">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Entregas de motorizado en recojo</h5>
      </div>
      <form id="form_recojo_motorizado" name="form_recojo_motorizado" class="card" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" id="input_recojomotorizado" name="input_recojomotorizado">

          <div class="form-row">

            <div class="form-group col-lg-4">
              {!! Form::label('pimagen1_recojo', 'Imagen 1') !!}
              {!! Form::file('pimagen1_recojo', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('pimagen2_recojo', 'Imagen 2') !!}
              {!! Form::file('pimagen2_recojo', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('pimagen3_recojo', 'Imagen 3') !!}
              {!! Form::file('pimagen3_recojo', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <button type="button" class="btn btn-primary d-none" id="cargar_adjunto1">
                Subir Informacion
              </button>
            </div>
            <div class="col-4">
              <button type="button" class="btn btn-primary d-none" id="cargar_adjunto2">
                Subir Informacion
              </button>
            </div>
            <div class="col-4">
              <button type="button" class="btn btn-primary d-none" id="cargar_adjunto3">
                Subir Informacion
              </button>
            </div>
          </div>

          <div class="row">
            <div class="col-4 mt-12">
              <div class="form-group">

                <div class="image-wrapper">
                  <button class="btn btn-danger" id="trash_adjunto1" type="button"><i class="fa fa-trash"></i></button>
                  <img id="picture1_recojo"
                       src="{{ asset('imagenes/motorizado_preview/sobres.png') }}"
                       data-src="{{ asset('imagenes/motorizado_preview/sobres.png') }}"
                       alt="Imagen del pago" class="img-fluid w-100" style="display: block;" width="300" height="300">


                </div>
              </div>
            </div>
            <div class="col-4 mt-12">
              <div class="form-group">

                <div class="image-wrapper">
                  <button class="btn btn-danger" id="trash_adjunto2" type="button"><i class="fa fa-trash"></i></button>
                  <img id="picture2_recojo"
                       src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                       data-src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                       alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">


                </div>
              </div>
            </div>
            <div class="col-4 mt-12">
              <div class="form-group">

                <div class="image-wrapper">
                  <button class="btn btn-danger" id="trash_adjunto3" type="button"><i class="fa fa-trash"></i></button>
                  <img id="picture3_recojo"
                       src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                       data-src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                       alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">

                </div>
              </div>
            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info" id="motorizado_recojo">Confirmar</button>
        </div>
      </form>

    </div>
  </div>
</div>
