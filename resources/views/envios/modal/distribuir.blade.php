  <!-- Modal modal-enviar -->
  <div class="modal fade" id="modal-distribuir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 900px!important;">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">Distribuir Envio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="formulariodistribuir" name="formulariodistribuir" enctype="multipart/form-data">
          <input type="hidden" id="hiddenDistribuir" name="hiddenDistribuir">
          <div class="modal-body">

          </div>
          <div style="margin: 10px">
            <div class="card">
              <div class="border rounded card-body border-secondary">
                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-lg-12">
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <h5>Distribuir entre LIMA:</h5>
                        </div><br><br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="form-row">
                                <div class="form-group col-lg-6 mx-auto" style="font-size: 18px">

                                    <div class="form-group col-lg-12">
                                        {!! Form::label('distribuir', 'Distribuir') !!}
                                        {!! Form::select('distribuir', $distribuir , '0', ['class' => 'form-control border selectpicker border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                    </div>


                                </div>
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
            <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
          </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>
</div>
