<!-- Modal -->
<div class="modal fade" id="modal-recoger-sobre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel"><b>Pedidos para recojo</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 card_clientes ">

                        <div class="cmb_asesores row">
                            <div class="col-2 mx-auto">
                                <select id="user_id" name="user_id" class="border form-control  border-secondary selectpicker" id="user_id" data-live-search="true"
                                        data-live-search-placeholder="">
                                    <option value="0" class="ob" data-type="select" data-msj="Seleccione un Asesor">---- SELECCIONE ASESOR ----</option>
                                    @foreach($user_id as $asesor)
                                        <option data-subtext="{{$asesor->exidentificador}}"
                                                value="{{$asesor->id}}">{{($asesor->identificador) }} {{ $asesor->letra  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                                    <table id="datatable-clientes-lista-recojer"
                                           class="table table-striped table-bordered nowrap"
                                           style="text-align: center;width:100%;">
                                        <thead>
                                        <h4 style="text-align: center"><strong>Listado de clientes para seleccion</strong></h4>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Celular</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                    </div>
                    <div class="col-md-6 card_pedidos ">

                        <div class="row">
                            <div clas="col-md-12">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn-cancel-recojo btn btn-warning">Atras</button>
                                        <br><br>
                                    </div>
                                </div>


                                <div class="table-responsives">
                                    <table id="datatable-pedidos-lista-recojer" class="table table-striped" style="text-align: center;width:100%;">
                                        <thead>
                                        <h4 style="text-align: center"><strong>Listado de pedidos del cliente <span class="badge badge-success nombre_cliente_recojo"></span></strong></h4>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Codigo</th>
                                            <th scope="col">Condicion</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 card_form ">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="formrecojo" name="formrecojo" role="form">
                                    <input type="hidden" id="recojo_cliente" name="recojo_cliente">
                                    <input type="hidden" id="recojo_pedido" name="recojo_pedido">

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="recojo_cliente_name">Cliente</label>
                                            <input type="text" class="form-control" id="recojo_cliente_name" placeholder="Cliente" readonly>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_codigo">Pedido</label>
                                            <input type="text" class="form-control" id="recojo_pedido_codigo" placeholder="Pedido" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_grupo">Grupo</label>
                                            <input type="text" class="form-control" id="recojo_pedido_grupo" placeholder="Grupo" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('recojo_fecha', 'Fecha de recojo') !!}
                                            {!! Form::dateTimeLocal('recojo_fecha', \Carbon\Carbon::now(), ['class' => 'form-control', 'id' => 'recojo_fecha']) !!}
                                        </div>
                                    </div>

                                    <div class="form-row d-none">
                                        <div class="form-group col-md-12">
                                            <label for="recojo_descripcion">Descripcion</label>
                                            <textarea class="form-control" id="recojo_descripcion" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row ">
                                        <div class="form-group col-md-6 d-none">
                                            <label for="recojo_destino">Destino</label>
                                            <select class="form-control" id="recojo_destino">
                                                <option value="LIMA">LIMA</option>
                                                <option value="OLVA">OLVA</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {!! Form::label('distrito', 'Distrito') !!}<br>
                                            <select name="distrito_recoger" id="distrito_recoger" class="distrito_recoger form-control"
                                                    data-show-subtext="true" data-live-search="true" title="Elegir distrito"
                                                    data-live-search-placeholder="Seleccione distrito">
                                                @foreach($distritos_recojo as $distrito)
                                                    <option data-subtext="{{$distrito->zona}}"
                                                            value="{{$distrito->distrito}}">{{($distrito->distrito) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 mt-4">
                                            <button type="button" class="btn-charge-history btn btn-info">Cargar de Historial</button>
                                        </div>

                                    </div>

                                    <div class="form-row datos_direccion">
                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_quienrecibe_nombre">Nombre Recibe</label>
                                            <input required type="text" class="form-control" id="recojo_pedido_quienrecibe_nombre" placeholder="Quien recibe" autocomplete="off" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_quienrecibe_celular">Celular recibe</label>
                                            <input required type="text" class="form-control" id="recojo_pedido_quienrecibe_celular" maxlength="9" placeholder="Celular de quien recibe" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-row datos_direccion">

                                        <div class="form-group col-md-12">
                                            <label for="recojo_pedido_direccion">Direccion</label>
                                            <input required type="text" class="form-control" id="recojo_pedido_direccion" placeholder="Direccion" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="recojo_pedido_referencia">Referencia</label>
                                            <input type="text" class="form-control" id="recojo_pedido_referencia" placeholder="Referencia" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="recojo_pedido_observacion">Observacion</label>
                                            <input type="text" class="form-control" id="recojo_pedido_observacion" placeholder="Observacion" autocomplete="off">
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Registrar recojo</button>
                                    </div>

                                </form>
                                <div class="card d-none">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <span>Destino</span>
                                            </div>
                                            <div class="col">
                                                <span class="destino_recojo"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <span>Distrito</span>
                                            </div>
                                            <div class="col">
                                                <span class="distrito_recojo"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <span>Direccion</span>
                                            </div>
                                            <div class="col">
                                                <span class="direccion_recojo"></span>
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
                {{--<a href="{{ route('pedidos.sinpagos') }}" class="btn btn-danger btn-sm">Ver deudores</a>--}}
                <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
