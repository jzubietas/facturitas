<div class="card">
    <div class="card-body">
        <div class="border rounded card-body border-secondary">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-lg-6">
                        {!! Form::label('user_id', 'Asesor') !!}
                        {{--@if (Auth::user()->rol == 'Asesor' || Auth::user()->rol == 'Super asesor')--}}
                        {{--<input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">
                        <input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>--}}

                        <input type="hidden" id="txtValidaSobre" name="txtValidaSobre" value="Si">
                        <select name="user_id" class="border form-control  border-secondary selectpicker" id="user_id"
                                data-live-search="true">
                            <option value="0" class="ob" data-type="select" data-msj="Seleccione un Asesor">----
                                SELECCIONE ASESOR ----
                            </option>
                        </select>

                    </div>


                    <div class="form-group col-lg-6">
                        {!! Form::label('cliente_id', 'Cliente*') !!} &nbsp; &nbsp; &nbsp;

                        <a href="" data-target="#modal-historial" data-toggle="modal">
                            <button class="btn btn-danger btn-sm">Deudores</button>
                        </a>

                        <select name="cliente_id" class="border form-control  border-secondary selectpicker"
                                id="cliente_id" data-live-search="true">{{-- selectpicker lang="es" --}}
                            <option>---- SELECCIONE CLIENTE ----</option>
                        </select>

                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="border rounded card-body border-secondary" id="vertabla">
            <div class="card-body">

                <div class="form-row">
                    <div class="form-group col-lg-3  mb-1 py-2">
                        {!! Form::label('pruc', 'RUC *') !!} &nbsp; &nbsp; &nbsp;

                        <a href="" data-target="#modal-add-ruc" id="btn_agregar_ruc" data-toggle="modal"
                           class="btn btn-info btn-sm">AGREGAR RUC Y R.S.</a>

                        <select name="pruc" class="border form-control border-secondary selectpicker" id="pruc"
                                data-live-search="true" style="height: 100% !important;">
                            <option value="">---- SELECCIONE ----</option>
                        </select>
                    </div>

                    @error('num_ruc')
                    <small class="text-danger" style="font-size: 16px">{{ $message }}</small>
                    @enderror

                    <div class="form-group col-lg-3  mb-1 py-2">
                        {!! Form::label('pempresa', 'Nombre de empresa') !!}
                        <input type="text" name="pempresa" id="pempresa" class="form-control"
                               placeholder="Nombre de empresa..." disabled>
                    </div>

                    <div class="form-group col-lg-3  mb-1 py-2">
                        {!! Form::label('ptipo_banca', 'Tipo de comprobante y banca' ) !!}
                        <select name="ptipo_banca" id="ptipo_banca" class="border form-control border-secondary">
                            <option value="">---- SELECCIONE ----</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-3 d-flex justify-content-end align-items-center  mb-1 py-2">
                        <a href="" data-target="#modal-historial-2" data-toggle="modal">
                            <button class="btn btn-danger btn-lg ">Historial de detalle</button>
                        </a><!--align-items-stretch-->
                    </div>


                </div>

                <div class="form-row">
                    <div class="form-group col-lg-2">

                    </div>

                    <input type="hidden" name="pcodigo" id="pcodigo"
                           value="{{ Auth::user()->identificador }}-{{ $fecha }}-{{ $numped}}" class="form-control"
                           readonly>

                </div>
                <div class="form-row">

                    <div class="form-group col-lg-2 mb-1 py-2">
                        {!! Form::label('pcantidad', 'Cantidad') !!}
                        <input type="text" name="pcantidad" id="pcantidad" step="0.01" min="0"
                               class="form-control number ob" data-type="text" data-msj="Ingrese una cantidad"
                               placeholder="Cantidad...">
                    </div>

                    <div class="form-group col-lg-2 mb-1 py-2">

                        {!! Form::label('pmes', 'Mes') !!}
                        {!! Form::select('pmes', $meses , '', ['class' => 'form-control border selectpicker border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                    </div>

                    <div class="form-group col-lg-2 mb-1 py-2">

                        {!! Form::label('panio', 'Año') !!}
                        {!! Form::select('panio', $anios , '', ['class' => 'form-control border selectpicker border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                    </div>


                    <div class="form-group col-lg-2 mb-1 py-2">
                        {!! Form::label('pporcentaje', 'Porcentaje(%)') !!}
                        <input type="number" name="pporcentaje" id="pporcentaje" step="0.1" min="0" class="form-control"
                               placeholder="Porcentaje..." disabled>
                    </div>
                    <div class="form-group col-lg-1 mb-1 py-2">
                        {!! Form::label('pcourier', 'Courier(S/)') !!}
                        {{-- <input type="number" name="pcourier" id="pcourier" step="0.01" min="0" class="form-control" placeholder="Courier..."> --}}
                        <input type="text" name="pcourier" id="pcourier" step="0.01" min="0" class="form-control number"
                               value="0" placeholder="Courier...">
                    </div>
                    <div class="form-group col-lg-3 d-flex justify-content-end align-items-center  mb-1 py-2">
                        <div class="btn-group-vertical" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-info btn-lg"
                                    data-toggle="jqconfirm"
                                    data-type="previsualizar"
                                    data-target="{{route('pedidos.store.save-history')}}">
                                <i class="fa fa-copy"></i>
                                Previsualizar
                            </button>
                        </div>

                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-5">
                        {!! Form::label('pdescripcion', 'Descripción') !!}
                        <input type="text" name="pdescripcion OB" data-msj="ingrese 9 digitos"
                               id="pdescripcion" class="form-control" placeholder="Descripción...">
                    </div>
                    <div class="form-group col-lg-4">
                        {!! Form::label('pnota', 'Nota') !!}
                        <input type="text" name="pnota" id="pnota" class="form-control" placeholder="Nota...">
                    </div>

                    <div class="form-group col-lg-3 d-flex justify-content-end align-items-center">

                        <button type="button" id="bt_add" class="btn btn-primary btn-lg"><i
                                class="fas fa-plus-circle"></i> Agregar
                        </button>

                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-12">
                        {!! Form::label('adjunto', 'Adjuntar archivos') !!}
                        <input type="file" id="adjunto" name="adjunto[]" multiple class="form-control">
                    </div>

                </div>
                <div class="row">


                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="text-align: center">
                        <div class="table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                <thead style="background-color: #A9D0F5">
                                <th style="vertical-align: middle">Opciones</th>
                                <th style="vertical-align: middle">Empresa</th>
                                <th style="vertical-align: middle">Mes</th>
                                <th style="vertical-align: middle">Año</th>
                                <th style="vertical-align: middle">RUC</th>
                                <th style="vertical-align: middle">Cantidad</th>
                                <th style="vertical-align: middle">Tipo de comprobante<br>y banca</th>
                                <th style="vertical-align: middle">Porcentaje</th>
                                <th style="vertical-align: middle">Courier</th>
                                <th style="vertical-align: middle">Descripción</th>
                                <th style="vertical-align: middle">Nota</th>
                                <th style="vertical-align: middle">Validasobres</th>
                                <th style="vertical-align: middle">Adjunto</th>
                                <th style="vertical-align: middle">FT</th>
                                </thead>
                                <tfoot>
                                <th style="text-align: center">TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="text-align: center"><h4 id="total">S/. 0.00</h4></th>
                                </tfoot>
                                <tbody>

                                </tbody>
                            </table>
                            <br>
                            <textarea class="form-control d-none" rows="6" placeholder="Descripcion Otros"
                                      name="pedido_copiar_2" cols="50" id="pedido_copiar_2"></textarea>
                        </div>
                    </div>
                </div>


                <div class="row" id="section_content_address" style="display: none">
                    <div class="col-lg-9 col-sm-9 col-md-9 col-xs-9" style="text-align: center">
                        <div class="alert alert-warning" role="alert">
                            <b>PUEDES AGREGAR LA DIRECCIÓN DE MANERA OPCIONAL SOLO PARA LOS CLIENTES DE LIMA</b>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3" style="text-align: center">
                        <button data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                data-target="#modal-direccion_crearpedido" type="button"
                                id="bt_add_dir" class="float-right btn btn-success btn-lg d-none"><i
                                class="fa  fa-map-marker-alt text-success mr-8 text-danger"></i> Agregar Direccion
                        </button>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="text-align: center">
                        <div class="table-responsive">
                            <table id="table_direccion"
                                   class="table table-striped table-bordered table-condensed table-hover">
                                <thead style="background-color: #63ab46 ">
                                <th>Opciones</th>
                                <th>Destino</th>
                                <th>Distrito</th>
                                <th>Zona</th>
                                <th>CONTACTO</th>
                                <th>TEL CONTACTO</th>
                                <th>Direccion/tracking</th>
                                <th>Referencia/numregistro</th>
                                <th>Observacion/rotulo</th>
                                <th>Google Maps</th>
                                <th>Importe</th>
                                </thead>
                                <tbody id="table_direccion_body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
