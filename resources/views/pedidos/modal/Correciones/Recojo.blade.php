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
                    <ul class="list-group">
                        <li class="form-group text-wrap form-row " style="border: 1px solid rgba(0,0,0,.125);">
                            <div class="form-group mx-sm-3 mb-2 col-4 mt-3">
                                <input type="hidden" class="form-control" id="Cliente" >
                                <input type="text" class="form-control" id="cod_Cliente"  >
                            </div>
                            <div class="form-group mx-sm-3 mb-2 col-4 mt-3">
                                <input type="hidden" class="form-control" id="Id-Cliente">
                                <input type="text" class="form-control" id="cod_pedido"  >
                            </div>
                            <button type="button" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn-sm dropdown-item btn btn-primary mb-2 col-1 mt-3" data-target="#modal-listclientes" >+</button>

                        </li>
                        <li class="list-group-item text-wrap">
                            <div class="form-group">

                                <div class="form-group col-md-6">
                                    <i class="fa fa-user text-red" aria-hidden="true"></i>
                                    <input type="hidden" name="nombre_id" id="Nombre_recibe">
                                    {!! Form::label('nombre_recojo', 'Nombre del contacto quien recibe') !!}
                                    {!! Form::text('nombre_recojo', null, ['class' => 'form-recojo', 'placeholder' => 'Nombre', 'autocomplete' => 'off']) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    <i class="fa fa-phone text-red" aria-hidden="true"></i>
                                    {!! Form::label('celular', 'Telefono del contacto quien recibe') !!}
                                    <span class="badge badge-pill badge-secondary">9 digitos</span>
                                    {!! Form::number('celular', null, ['class' => 'form-recojo', 'id' => 'celular_recojo', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','placeholder' => '9 digitos']) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    <i class="fa fa-street-view text-red" aria-hidden="true"></i>

                                    {!! Form::label('direccion_recojo', 'Direccion') !!}
                                    {!! Form::text('direccion_recojo', null, ['class' => 'form-recojo', 'placeholder' => 'Direccion_recojo' , 'autocomplete' => 'off']) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    <i class="fa fa-commenting-o text-red" aria-hidden="true"></i>

                                    {!! Form::label('referencia_recojo', 'Referencia') !!}
                                    {!! Form::text('referencia_recojo', null, ['class' => 'form-recojo', 'placeholder' => 'Referencia_recojo' , 'autocomplete' => 'off']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('observacion_recojo', 'Observacion') !!}
                                    {!! Form::text('observacion_recojo', null, ['class' => 'form-recojo', 'placeholder' => 'Observacion_recojo' , 'autocomplete' => 'off']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('gmlink_recojo', 'Link Google Map') !!}
                                    {!! Form::text('gmlink_recojo', null, ['class' => 'form-recojo', 'placeholder' => 'Ejem: https://goo.gl/maps/*********' , 'autocomplete' => 'off']) !!}
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="Direccion_de_entrega">Direccion de entrega</label>
                                <input type="text" class="form-control" id="Direccion_de_entrega" placeholder="" readonly>
                            </div>

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
