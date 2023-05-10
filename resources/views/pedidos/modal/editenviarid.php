  <!-- Modal modal-enviar -->
  <div class="modal fade" id="modal-editenviar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 900px!important;">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">Edit pedidos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="formularioeditenviar" name="formularioeditenviar" enctype="multipart/form-data">
          <input type="text" id="hiddenEditenviar" name="hiddenEditenviar">
          <div class="modal-body">
            <p>Complete los siguientes datos del envío del pedido: <strong class="textcode">PED00</strong></p>
          </div>
          <div style="margin: 10px">
            <div class="card">
              <div class="border rounded card-body border-secondary">
                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-lg-12">
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <h5>Información:</h5>
                        </div><br><br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                          <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('fecha_envio_doc_fis', 'Fecha de envío') !!}
                            {!! Form::date('fecha_envio_doc_fis', null, ['class' => 'form-control', 'id' => 'fecha_envio_doc_fis', 'disabled']) !!}
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('fecha_recepcion', 'Fecha de entrega') !!}
                            {!! Form::date('fecha_recepcion', null, ['class' => 'form-control', 'id' => 'fecha_recepcion']) !!}
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('foto1', 'Foto de recibido 1') !!}
                              @csrf
                              {!! Form::file('foto1', ['class' => 'form-control-file', 'accept' =>'image/*']) !!}
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('foto2', 'Foto de recibido 2') !!}
                              @csrf
                              {!! Form::file('foto2', ['class' => 'form-control-file', 'accept' =>'image/*']) !!}
                            </div>

                            <!--foto 1  y descargar-->
                            <!--foto 2  y descargar-->

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('condicion', 'Estado') !!}
                              <select name="condicion" class="form-control" id="condicion">
                                <option value="1">PENDIENTE DE ENVIO</option>
                                <option value="2">EN REPARTO</option>
                                {{--@if($pedido->destino == "PROVINCIA")--}}
                                  <option value="REGISTRO">REGISTRO</option>
                                  <option value="TRASLADO">TRASLADO</option>
                                  <option value="EN TIENDA">EN TIENDA</option>
                                {{--@endif--}}
                                <option value="3">ENTREGADO</option>
                              </select>

                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('trecking', 'N° Trecking/Guia') !!}
                              {!! Form::text('trecking', null, ['class' => 'form-control', 'id' => 'cant_compro']) !!}
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
