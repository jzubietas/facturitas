<!-- Modal -->
<div class="modal fade" id="modal_recojomotorizado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px!important;">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Entregas de motorizado en recojo</h5>
      </div>

      <form id="form_recojo_motorizado" name="form_recojo_motorizado" enctype="multipart/form-data">
        <input type="hidden" id="recojo_motorizado" name="recojo_motorizado">
        <div class="modal-body">

          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <fieldset>
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h5>Información:</h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      {!! Form::label('fecha_envio_doc_fis', 'Fecha de Envio') !!}
                      {!! Form::date('fecha_envio_doc_fis', '', ['class' => 'form-control', 'id' => 'fecha_envio_doc_fis', 'disabled']) !!}
                    </div>
                    <div class="col-6">
                      {!! Form::label('fecha_recepcion', 'Fecha de Entrega') !!}
                      {!! Form::date('fecha_recepcion', '', ['class' => 'form-control', 'id' => 'fecha_recepcion']) !!}
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      {!! Form::label('foto1', 'Foto recibido 1') !!}
                      @csrf
                      {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto1', 'multiple']) !!}
                      <div class="w-100">
                        <div class="spinner-border" role="status"
                             id="loading_upload_attachment_file" style="display: none">
                          <span class="sr-only">Enviando...</span>
                        </div>
                      </div>

                    </div>
                    <div class="col-6">
                      {!! Form::label('foto2', 'Foto recibido 2') !!}
                      @csrf
                      {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto2', 'multiple']) !!}
                      <div class="w-100">
                        <div class="spinner-border" role="status"
                             id="loading_upload_attachment_file" style="display: none">
                          <span class="sr-only">Enviando...</span>
                        </div>
                      </div>

                    </div>
                    <div class="col-6">
                      {!! Form::label('foto3', 'Foto recibido 3') !!}
                      @csrf
                      {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto3', 'multiple']) !!}
                      <div class="w-100">
                        <div class="spinner-border" role="status"
                             id="loading_upload_attachment_file" style="display: none">
                          <span class="sr-only">Enviando...</span>
                        </div>
                      </div>

                    </div>

                  </div>

                  <div class="row">
                    <div class="col-6">
                      <button type="button" class="btn btn-primary d-nonea" id="cargar_adjunto1">
                        Subir Informacion
                      </button>
                    </div>
                    <div class="col-6">
                      <button type="button" class="btn btn-primary d-nonea" id="cargar_adjunto2">
                        Subir Informacion
                      </button>
                    </div>
                    <div class="col-6">
                      <button type="button" class="btn btn-primary d-nonea" id="cargar_adjunto3">
                        Subir Informacion
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group col-lg-6">
                        <div class="image-wrapper">
                          <img id="picture1" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" width="150px">
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group col-lg-6">
                        <div class="image-wrapper">
                          <img id="picture2" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" width="150px">
                        </div>
                      </div>
                    </div>
                  </div>

                </fieldset>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cerrarmodalatender">Cerrar</button>
          <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
        </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>
