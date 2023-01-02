<!-- Modal -->
<div class="modal fade" id="modal-direccion" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Dirección de envío para cliente </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formdireccion" name="formdireccion">
                <div class="modal-body">
                    <p class="d-none">Ingrese la dirección de envío del pedido: <strong class="textcode">PED000</strong>
                    </p>

                    <input id="cliente_id" name="cliente_id" value="" type="hidden">
                    <input id="cod_pedido" name="cod_pedido" value="" type="hidden">
                    <input id="cod_ase" name="cod_ase" value="" type="hidden">

                    <div class="row" id="show_direccion_is_enabled" style="display: none">
                        <div class="col-4  contenedor-tabla"><!--tabla-->

                            <div class="table-responsive">

                                <table id="tablaPrincipalpedidosagregar" class="table table-striped display"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Codigo Pedido</th>
                                        <th scope="col">Producto</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>


                            </div>

                        </div>
                        <div class="col-4 contenedor-formulario"><!--formulario-->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {!! Form::label('limaprovincia', 'Seleccione') !!}
                                    {!! Form::select('limaprovincia', array('L'=>'Lima','P'=>'Provincia')  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                </div>

                            </div>
                            <!---->


                            <div class="lima d-none">
                                <div class="row">
                                    <div class="col">

                                        <div class="row">
                                            <div class="col">

                                                <h1>LIMA</h1>

                                                <a id="modal-historial-lima-a" href=""
                                                   data-target="#modal-historial-lima" data-toggle="modal"
                                                   data-cliente="">
                                                    <button class="btn btn-info btn-sm">
                                                        <i class="fas fa-envelope"></i>
                                                        Historial
                                                    </button>
                                                </a>


                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <i class="fa fa-user text-red" aria-hidden="true"></i>

                                                    {!! Form::label('nombre', 'Nombre del contacto quien recibe') !!}
                                                    {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                                                </div>


                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <i class="fa fa-phone text-red" aria-hidden="true"></i>

                                                    {!! Form::label('celular', 'Telefono del contacto quien recibe') !!}
                                                    <span class="badge badge-pill badge-secondary">9 digitos</span>
                                                    {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','placeholder' => '9 digitos']) !!}
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <i class="fa fa-street-view text-red" aria-hidden="true"></i>

                                                    {!! Form::label('direccion', 'Direccion') !!}
                                                    {!! Form::text('direccion', null, ['class' => 'form-control', 'placeholder' => 'Direccion', 'required' => 'required']) !!}
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <i class="fa fa-commenting-o text-red" aria-hidden="true"></i>

                                                    {!! Form::label('referencia', 'Referencia') !!}
                                                    {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referencia', 'required' => 'required']) !!}
                                                </div>

                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        {!! Form::label('distrito', 'Distrito') !!}<br>
                        
                        <select id="distrito" class="distrito form-control" data-show-subtext="true" data-live-search="true" data-live-search-placeholder="Seleccione distrito">
                          @foreach($distritos as $distrito)
                            <option data-subtext="{{$distrito->zona}}" value="{{$distrito->distrito}}">{{($distrito->distrito) }}</option>
                          @endforeach
                        </select>
                        
                        

                      </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    {!! Form::label('observacion', 'Observacion') !!}
                                                    {!! Form::text('observacion', null, ['class' => 'form-control', 'placeholder' => 'Observacion', 'required' => 'required']) !!}
                                                </div>

                                                {{--<button type="button" id="saveHistoricoLima" class="btn btn-danger btn-md"><i class="fa"></i>GRABA HISTORICO</button>--}}
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <br>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="saveHistoricoLima">
                                                        <label class="form-check-label" for="saveHistoricoLima">GRABA
                                                            HISTORICO</label>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!---->
                            <div class="provincia d-none">
                                <div class="row">
                                    <div class="col">
                                        <h1>PROVINCIA</h1>

                                        <a id="modal-historial-provincia-a" href=""
                                           data-target="#modal-historial-provincia" data-toggle="modal" data-cliente="">
                                            <button class="btn btn-info btn-sm">
                                                <i class="fas fa-envelope"></i>
                                                Historial
                                            </button>
                                        </a>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-none">
                                            {!! Form::label('departamento', 'departamento') !!}
                                            {!! Form::select('departamento', $departamento  , null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-none">
                                            {!! Form::label('oficina', 'Oficina') !!}
                                            {!! Form::text('oficina', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            {!! Form::label('tracking', 'Tracking') !!}
                                            {!! Form::text('tracking', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required','maxlength' => 12]) !!}
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            {!! Form::label('numregistro', 'Numero de Registro') !!}
                                            {!! Form::text('numregistro', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required','maxlength' => 12]) !!}
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            {!! Form::label('importe', 'Importe') !!}
                                            <input
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                type="text" maxlength="5" id="importe" name="importe"
                                                placeholder="Importe" class="form-control number ob" step="0.01" min="0"
                                                data-type="text" data-msj="Ingrese una cantidad">
                                        </div>

                                        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6">
                                            <div class="row">
                                                <div class="col-10">
                                                    {!! Form::label('rotulo', 'Rotulo') !!}
                                                    @csrf
                                                    {!! Form::file('rotulo', ['class' => 'form-control-file', 'accept' =>'pdf/*']) !!}
                                                </div>
                                                <div
                                                    class="col-2 d-none justify-content-center align-items-center drop-rotulo">
                                                    <button type="button" id="droprotulo" class="btn btn-danger btn-md">
                                                        <i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>

                                            {{----}}

                                            <br>

                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <br>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="saveHistoricoProvincia"><!--checked-->
                                                <label class="form-check-label" for="saveHistoricoProvincia">GRABA
                                                    HISTORICO</label>
                                            </div>

                                        </div>

                                        {{--<button type="button" id="saveHistoricoProvincia" class="btn btn-danger btn-md"><i class="fa"></i>GRABA HISTORICO</button>--}}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4 viewpdf d-none contenedor-pdf border border-primary"><!--PDF-->
                            <div class="row ">
                                <div class="col">

                                    <div id="my_pdf_viewer" class="d-none">


                                        <div id="navigation_controls" class="row">
                                                
                                            <button id="go_previous" class="btn btn-xs btn-info col">Atras</button>
                                                <input id="current_page" class="form-control col" value="1"
                                                       type="number"/>
                                                
                                            <button id="go_next" class="btn btn-xs  btn-info col">Siguiente</button>
                                        </div>

                                          

                                        <div id="zoom_controls" class="d-none">
                                                
                                            <button id="zoom_in">+</button>
                                                
                                            <button id="zoom_out">-</button>
                                        </div>
                                    </div>
                                    <div id="canvas_container">
                                            
                                        <canvas id="pdf_renderer"></canvas>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="show_direccion_is_disabled">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                Los archivos del pedido <b class="set_pedido_code"></b> aun no han sido enviados al cliente.<br>
                                <b>Primero envie los archivos al cliente para que se active el boton de envio.</b>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="direccionConfirmar">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
