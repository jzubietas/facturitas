  <!-- Modal -->
  <div class="modal fade" id="modal-veradjunto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Ver adjuntos de atención de pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{--<p>Adjuntos del pedido: <strong class="textcode">PED00</strong></p>
          <p>Cantidad de adjuntos: <strong class="textcountadj">0</strong></p>--}}

            <div style="margin: 10px">
                <div class="card">
                    <div class="border rounded card-body border-secondary">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row border rounded border-danger mb-2" >
                                        <div class="col-6">
                                            <h3 class="text-danger"><b>Monto a Anular:</b></h3>
                                        </div>
                                        <div class="col-6">
                                            <span class="h3 txtMontoAnularAdjuntos text-danger" ><b> 0.01</b></span>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            {!! Form::label('envio_doc', 'Documento(s) adjuntado(s) del pedido') !!}

                                            <div id="imagenes_adjunto"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
