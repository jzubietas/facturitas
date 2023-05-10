  <!-- Modal -->
<div class="modal fade" id="modal-activartiempo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel"><b>Activar al cliente por tiempo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulariotiempo" name="formulariotiempo">
                <div class="modal-body">
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-body border border-secondary rounded">

                            <div class="form-row">

                                <div class="form-group col-lg-6">
                                    {!! Form::label('user_id_tiempo', 'Asesor*') !!} &nbsp; &nbsp; &nbsp;

                                    <select name="user_id_tiempo" class="border form-control border-secondary" id="user_id_tiempo" data-live-search="true" >
                                        <option value="">---- SELECCIONE ASESOR ----</option>
                                    </select>

                                </div>

                                <div class="form-group col-lg-6">
                                    {!! Form::label('cliente_id_tiempo', 'Cliente*') !!} &nbsp; &nbsp; &nbsp;

                                    <select name="cliente_id_tiempo" class="border form-control border-secondary" id="cliente_id_tiempo" data-live-search="true" >
                                        <option value="">---- SELECCIONE CLIENTE ----</option>
                                    </select>

                                </div>

                                <div class="form-group col-lg-6">
                                    {!! Form::label('pcantidad_pedido', 'Cantidad por pedidos (unidad)') !!}
                                    <input type="text" name="pcantidad_pedido" id="pcantidad_pedido" step="0.01" min="0" class="form-control number" placeholder="Cantidad por pedidos...">

                                </div>

                                <div class="form-group col-lg-6">
                                    {!! Form::label('pcantidad_tiempo', 'Tiempo (min)') !!}
                                    <input type="text" name="pcantidad_tiempo" id="pcantidad_tiempo" step="0.01" min="0" class="form-control number" placeholder="Cantidad por tiempo...">

                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="activar_tiempo" class="btn btn-info btn-sm" >Establecer</button>
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </form>

      </div>
    </div>
</div>
