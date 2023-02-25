<div class="modal fade" id="modal-annuncient-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Opciones Multiples</h5>

                <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
            <div class="modal-body">

                    <input type="hidden" class="d-none" id="courierregistro2" name="courierregistro2">

                  <div class="form-row">
                    <div class="form-group col-lg-6">
                      {!! Form::label('opciones_modal1', 'Opciones') !!}
                      <select name="opciones_modal1" class="border form-control  border-secondary selectpicker" id="opciones_modal1"
                              data-live-search="true" title="Seleccione">
                      </select>
                    </div>
                  </div>

                    <div id="op-1-row" class="op-1-row"><!---->
                        <form id="form-op-1-row" name="form-op-1-row" class="form-row ">
                          <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op1', 'Asesor*') !!}

                            <select name="asesor_op1" class="border form-control border-secondary" id="asesor_op1"
                                    data-ruta="{{route('cargar.clientemodal1')}}">
                              <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                          </div>
                            <div class="form-group col-lg-6">
                                {!! Form::label('cliente_op1', 'Cliente*') !!}
                                <select name="cliente_op1" class="border form-control border-secondary" id="cliente_op1" data-live-search="true" >
                                    <option value="">---- SELECCIONE CLIENTE ----</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                {!! Form::label('clientenuevo_op1', 'Numero de cliente nuevo') !!}
                                {{--<input type="text" name="clientenuevo_op1" id="clientenuevo_op1"  class="form-control" placeholder="Cliente nuevo" maxlength="9">--}}
                                {!! Form::text('clientenuevo_op1', null, ['autocomplete'=>'off','class' => 'form-control', 'id' => 'clientenuevo_op1', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9','placeholder' => '9 digitos']) !!}
                            </div>
                            <div class="form-group col-lg-6">
                                {!! Form::label('clientenuevocontacto_op1', 'Nombre del contacto') !!}
                                {!! Form::text('clientenuevocontacto_op1', null, ['autocomplete'=>'off','class' => 'form-control', 'id' => 'clientenuevocontacto_op1','placeholder' => 'Nombre de contacto']) !!}
                            </div>

                            <div class="form-group col-lg-12">
                                {!! Form::label('captura_op1', 'Captura de pantalla') !!}
                                <input type="file" name="captura_op1" id="captura_op1"  class="form-control" placeholder="">
                            </div>
                            <hr class="mt-2 mb-3"/>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="float-right btn btn-success">Enviar</button>
                            </div>

                        </form>
                    </div>

                    <div id="op-2-row" class="op-2-row"><!---->
                        <form id="form-op-2-row" name="form-op-2-row" class="form-row ">
                          <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op2', 'Asesor*') !!}
                            <select name="asesor_op2" class="border form-control border-secondary" id="asesor_op2" data-live-search="true"
                                    data-ruta="{{route('cargar.clientemodal1')}}">
                              <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                          </div>

                            <div class="form-group col-lg-6">
                                {!! Form::label('cliente_op2', 'Cliente*') !!}
                                <select name="cliente_op2" class="border form-control border-secondary" id="cliente_op2" data-live-search="true" >
                                    <option value="">---- SELECCIONE CLIENTE ----</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                {!! Form::label('cantidadpedidos_op2', 'Cantidad de pedidos') !!}
                                <input type="text" name="cantidadpedidos_op2" id="cantidadpedidos_op2"  class="form-control" placeholder="Cantidad de pedidos" maxlength="1">
                            </div>

                            <div class="form-group col-lg-12">
                                {!! Form::label('captura_op2', 'Captura de pantalla') !!}
                                <input type="file" name="captura_op2" id="captura_op2"  class="form-control" placeholder="">
                            </div>
                            <hr class="mt-2 mb-3"/>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="float-right btn btn-success">Enviar</button>
                            </div>
                        </form>

                    </div>
                    <div id="op-3-row" class="op-3-row"><!---->
                        <form id="form-op-3-row" name="form-op-3-row" class="form-row ">
                          <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op3', 'Asesor*') !!}
                            <select name="asesor_op3" class="border form-control border-secondary" id="asesor_op3" data-live-search="true"
                                    data-ruta="{{route('cargar.clientemodal1')}}">
                              <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                          </div>

                            <div class="form-group col-lg-6">
                                {!! Form::label('cliente_op3', 'Cliente*') !!}
                                <select name="cliente_op3" class="border form-control border-secondary" id="cliente_op3" data-live-search="true" >
                                    <option value="">---- SELECCIONE CLIENTE ----</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                {!! Form::label('pedido_op3', 'Pedido') !!}
                                <input type="text" name="pedido_op3" id="pedido_op3"  class="form-control" placeholder="Pedido">
                            </div>

                            <div class="form-group col-lg-12">
                                {!! Form::label('captura_op3', 'Captura de pantalla') !!}
                                <input type="file" name="captura_op3" id="captura_op3"  class="form-control" placeholder="">
                            </div>
                            <hr class="mt-2 mb-3"/>

                            <div class="form-group col-lg-12">
                                <button type="submit" class="float-right btn btn-success">Enviar</button>
                            </div>
                        </form>
                    </div>

                    <div id="op-4-row" class="op-4-row d-none">
                        <form id="form-op-4-row" name="form-op-4-row" class="form-row ">
                            <input type="hidden" id="opcion4" name="opcion" value="4">

                          <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op4', 'Asesor*') !!}
                            <select name="asesor_op4" class="border form-control border-secondary" id="asesor_op4"
                                    data-live-search="true"
                                    data-ruta="{{route('cargar.clientemodal1')}}">
                              <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                          </div>
                            <div class="form-group col-lg-6">
                                {!! Form::label('cliente_op4', 'Cliente*') !!}
                                <select name="cliente_op4" class="border form-control border-secondary" id="cliente_op4" data-live-search="true" >
                                    <option value="">---- SELECCIONE CLIENTE ----</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                {!! Form::label('contacto_op4', 'Contacto') !!}
                                <input type="text" name="contacto_op4" id="contacto_op4"  class="form-control" placeholder="Contacto">
                            </div>
                            <div class="form-group col-lg-6"></div>
                            <hr class="mt-2 mb-3"/>

                            <div class="form-group col-lg-12">
                                <button type="submit" class="float-right btn btn-success">Enviar</button>
                            </div>

                        </form>
                    </div>

            </div>

        </div>
    </div>
</div>
