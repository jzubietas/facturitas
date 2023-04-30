<div class="modal fade" id="modal-recojo-pedidos" aria-labelledby="modal-recojo-pedidos" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <form id="form-recojo" name="form-recojo" >
            <div class="modal-content">
                <div class="modal-header bg-success p-2">
                    <h5 class="modal-title" id="exampleModalLabel">Recojo pedidos</h5>

                    <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>

                <div class="modal-body pt-3 pr-3 pl-3 pb-0">
                  <input type="hidden" id="recojo_b" name="recojo_b">
                  <input type="hidden" name="opcion" value="4">

                  <div class="row clase">



                    <div class="col-md-10">
                      <div class="form-row">
                        <div class="form-group">
                          <input type="hidden" class="form-control col-md-10" id="clienteid" >
                          <input type="text" class="form-control" id="clientenombre" readonly >
                        </div>
                      </div>

                    </div>

                    <div class="col-0">
                      <div class="form-row">
                        <div class="form-group">
                          <input type="hidden" class="form-control" id="pedidoid">
                          <input type="hidden" class="form-control" id="pedidocodigo" readonly >
                        </div>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <button type="button" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn-sm btn btn-primary btnVerMasPedidos" data-target="#modal-listclientes" >+</button>
                    </div>
                  </div>
                  <div id="direcciones_add">

                    <ul class="d-flex " style="grid-gap: 40px;"></ul>
                    <input type="hidden" class="form-control" id="pedido_concatenado">


                  </div>


                  <div class="row">

                    <div id="cnt-distritos" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                      {!! Form::label('distrito_recojo', 'Distrito') !!}<br>

                      <select name="distrito_recojo" id="distrito_recojo" class="distrito form-control bg-dark"
                              data-show-subtext="true" data-live-search="true"
                              data-live-search-placeholder="Seleccione distrito" title="Ningun distrito seleccionado">
                        @foreach($distritos as $distrito)
                          <option data-subtext="{{$distrito->zona}}"
                                  value="{{$distrito->distrito}}">{{($distrito->distrito) }}</option>
                        @endforeach
                      </select>


                    </div>


                    <div class="col-md-12">

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <i class="fa fa-user text-red" aria-hidden="true"></i>
                          <input type="hidden" name="nombre_id" id="Nombre_recibe">
                          {!! Form::label('nombre_recojo', 'Nombre del contacto quien recibe') !!}
                          {!! Form::text('nombre_recojo', null, ['class' => 'form-control', 'id' => 'nombre_recojo', 'placeholder' => 'Nombre', 'autocomplete' => 'off']) !!}
                        </div>
                        <div class="form-group col-md-6">
                          <i class="fa fa-phone text-red" aria-hidden="true"></i>
                          {!! Form::label('celular', 'Telefono del contacto quien recibe') !!}
                          <span class="badge badge-pill badge-secondary">9 digitos</span>
                          {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular_recojo', 'min' =>'100000000', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','placeholder' => '9 digitos']) !!}
                        </div>

                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <i class="fa fa-street-view text-red" aria-hidden="true"></i>
                          {!! Form::label('direccion_recojo', 'Direccion') !!}
                          {!! Form::text('direccion_recojo', null, ['class' => 'form-control', 'id' => 'direccion_recojo', 'placeholder' => 'Direccion_recojo' , 'autocomplete' => 'off']) !!}
                        </div>
                        <div class="form-group col-md-6">
                          <i class="fa fa-commenting-o text-red" aria-hidden="true"></i>
                          {!! Form::label('referencia_recojo', 'Referencia') !!}
                          {!! Form::text('referencia_recojo', null, ['class' => 'form-control', 'id' => 'referencia_recojo', 'placeholder' => 'Referencia_recojo' , 'autocomplete' => 'off']) !!}
                        </div>
                      </div>


                      <div class="form-row">
                        <div class="form-group col-md-6">
                          {!! Form::label('observacion_recojo', 'Observacion') !!}
                          {!! Form::text('observacion_recojo', null, ['class' => 'form-control', 'id' => 'observacion_recojo', 'placeholder' => 'Observacion_recojo' , 'autocomplete' => 'off']) !!}
                        </div>
                        <div class="form-group col-md-6">
                          {!! Form::label('gmlink_recojo', 'Link Google Map') !!}
                          {!! Form::text('gmlink_recojo', null, ['class' => 'form-control', 'id' => 'gmlink_recojo', 'placeholder' => 'Ejem: https://goo.gl/maps/*********' , 'autocomplete' => 'off']) !!}
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="Direccion_de_entrega">Direccion de entrega</label>
                          <input type="text" class="form-control" id="Direccion_de_entrega" name="Direccion_de_entrega" placeholder="" readonly>
                        </div>
                      </div>

                    </div>
                  </div>


                    <ul class="list-group">
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
