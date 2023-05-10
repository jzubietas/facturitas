<!-- Modal -->
<div class="modal fade" id="modal-direccion_crearpedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel"><b>Direccion destino para Pedido</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 card_form ">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="formrecojo" name="formrecojo" role="form">
                                    <input type="hidden" id="recojo_cliente" name="recojo_cliente">
                                    <input type="hidden" id="recojo_pedido" name="recojo_pedido">

                                    <div class="form-row">

                                        <div class="form-group col-md-6 d-none">
                                            <label for="recojo_cliente_name">Cliente</label>
                                            <input type="text" class="form-control" id="recojo_cliente_name" placeholder="Cliente" readonly>
                                        </div>
                                        <div class="form-group col-md-6 d-none">
                                            <button type="button" class="btn-charge-history btn btn-info mt-4">Cargar de Historial</button>
                                        </div>

                                    </div>



                                    <div class="form-row ">
                                        <div class="form-group col-md-6">
                                            <label for="recojo_destino">Destino</label>

                                            <select name="recojo_destino" id="recojo_destino"
                                                    data-show-subtext="false" data-live-search="true"
                                                    class="form-control">
                                                <option value="">---- SELECCIONE Destino ----</option>
                                                <option value="LIMA">LIMA</option>
                                                {{--<option value="OLVA">OLVA</option>--}}
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {!! Form::label('distrito', 'Distrito') !!}<br>
                                            <select name="distrito_recoger" id="distrito_recoger" class="distrito_recoger form-control"
                                                    data-show-subtext="true" data-live-search="true"
                                                    data-live-search-placeholder="Seleccione distrito" title="Seleccione distrito">
                                            </select>
                                        </div>

                                    </div>

                                    <div class="form-row datos_direccion">
                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_quienrecibe_nombre">Nombre Recibe</label>
                                            <input required type="text" class="form-control" id="env_pedido_quienrecibe_nombre" placeholder="Quien recibe" autocomplete="off" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_quienrecibe_celular">Celular recibe</label>
                                            <input required type="text" class="form-control" id="env_pedido_quienrecibe_celular" maxlength="9" placeholder="Celular de quien recibe" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-row datos_direccion">

                                        <div class="form-group col-md-12">
                                            <label for="recojo_pedido_direccion" id="lbl_recojo_pedido_direccion">Direccion/Tracking</label>
                                            <textarea required class="form-control" id="env_pedido_direccion" name="env_pedido_direccion" rows="3"></textarea>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="recojo_pedido_referencia" id="lbl_recojo_pedido_referencia">Referencia/(Num Registro)</label>
                                            <input type="text" class="form-control" id="env_pedido_referencia" name="env_pedido_referencia" placeholder="Referencia" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-6 s_observacion">
                                            <label for="recojo_pedido_observacion" id="lbl_recojo_pedido_observacion">Observacion</label>
                                            <input type="text" class="form-control" id="env_pedido_observacion" name="env_pedido_observacion" placeholder="Observacion" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-6 d-none">
                                            <label for="recojo_pedido_importe" id="lbl_recojo_pedido_importe">Importe</label>
                                            <input
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                type="text" maxlength="5" placeholder="S/"
                                                class="form-control number ob" step="0.01" min="0"
                                                id="env_pedido_importe" name="env_pedido_importe" autocomplete="off" data-type="text" data-msj="Ingrese una cantidad">
                                        </div>


                                        <div class="form-group col-md-12">
                                            <label for="recojo_pedido_map" id="lbl_recojo_pedido_maps">Maps</label>
                                            <input type="text" class="form-control" id="env_pedido_map" name="env_pedido_map" placeholder="Map" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Registrar direccion</button>
                                    </div>
                                </form>

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
