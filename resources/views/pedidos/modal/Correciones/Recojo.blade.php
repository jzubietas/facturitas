<div class="modal fade" id="modal-recojo-pedidos" aria-labelledby="modal-correccion-pedidos" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <form id="form-recojo" name="form-recojo" class="correccion">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="exampleModalLabel">Recojo pedidos</h5>

                    <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>

                <div class="modal-body">
                  <input type="hidden" id="correccion_b" name="correccion_b">
                  <input type="hidden" name="opcion" value="4">

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-row">
                        <div class="form-group">
                          <input type="hidden" class="form-control" id="Cliente" >
                          <input type="text" class="form-control" id="cod_Cliente"  >
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4">
                      <div class="form-row">
                        <div class="form-group">
                          <input type="hidden" class="form-control" id="Id-Cliente">
                          <input type="text" class="form-control" id="cod_pedido"  >
                        </div>
                      </div>

                    </div>
                    <div class="col-md-2">
                      <button type="button" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn-sm btn btn-primary" data-target="#modal-listclientes" >+</button>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <i class="fa fa-user text-red" aria-hidden="true"></i>
                          <input type="hidden" name="nombre_id" id="Nombre_recibe">
                          {!! Form::label('nombre_recojo', 'Nombre del contacto quien recibe') !!}
                          {!! Form::text('nombre_recojo', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'autocomplete' => 'off']) !!}
                        </div>
                        <div class="form-group col-md-6">
                          <i class="fa fa-phone text-red" aria-hidden="true"></i>
                          {!! Form::label('celular', 'Telefono del contacto quien recibe') !!}
                          <span class="badge badge-pill badge-secondary">9 digitos</span>
                          {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular_recojo', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','placeholder' => '9 digitos']) !!}
                        </div>

                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <i class="fa fa-street-view text-red" aria-hidden="true"></i>
                          {!! Form::label('direccion_recojo', 'Direccion') !!}
                          {!! Form::text('direccion_recojo', null, ['class' => 'form-control', 'placeholder' => 'Direccion_recojo' , 'autocomplete' => 'off']) !!}
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <i class="fa fa-commenting-o text-red" aria-hidden="true"></i>
                          {!! Form::label('referencia_recojo', 'Referencia') !!}
                          {!! Form::text('referencia_recojo', null, ['class' => 'form-control', 'placeholder' => 'Referencia_recojo' , 'autocomplete' => 'off']) !!}
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          {!! Form::label('observacion_recojo', 'Observacion') !!}
                          {!! Form::text('observacion_recojo', null, ['class' => 'form-control', 'placeholder' => 'Observacion_recojo' , 'autocomplete' => 'off']) !!}
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          {!! Form::label('gmlink_recojo', 'Link Google Map') !!}
                          {!! Form::text('gmlink_recojo', null, ['class' => 'form-control', 'placeholder' => 'Ejem: https://goo.gl/maps/*********' , 'autocomplete' => 'off']) !!}
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="Direccion_de_entrega">Direccion de entrega</label>
                          <input type="text" class="form-control" id="Direccion_de_entrega" placeholder="" readonly>
                        </div>
                      </div>

                    </div>
                  </div>


                    <ul class="list-group">

                        <li class="list-group-item text-wrap">




                        </li>
                        <li class="list-group-item text-wrap">
                            <h6 class="alert alert-warning text-center font-weight-bold">
                                Sustento
                                <span class="text-danger">(Obligatorio)</span>
                            </h6>
                            <textarea name="sustento-recojo" id="sustento-recojo" class="form-control w-100"
                                      rows="3" style=" color: red; font-weight: bold; background: white; "
                                      placeholder="Colocar sustento"></textarea>
                        </li>
                    </ul>
                    <hr class="mt-2 mb-3"/>
                    <div class="modal-footer">
                        <div class="form-group col-lg-12">
                            <button type="submit" class="float-right btn btn-success">Enviar</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
