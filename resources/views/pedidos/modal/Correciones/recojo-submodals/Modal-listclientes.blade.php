<!-- Modal -->
<div class="modal fade" id="modal-listclientes" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Dirección de envío para cliente </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formlistclientes" name="formlistclientes" autocomplete="off">
                <div class="modal-body">
                    <p class="d-none">Ingrese la dirección de envío del pedido: <strong class="textcode">PED000</strong>
                    </p>

                    <input id="cliente_id" name="cliente_id" value="" type="hidden">
                    <input id="cod_nombre" name="cod_pedido" value="" type="hidden">
                    <input id="cod_estado" name="cod_ase" value="" type="hidden">

                    <div class="row" id="show_direccion_is_enabled" >
                        <div class="col-12  contenedor-tabla"><!--tabla-->

                            <div class="table-responsive">

                                <table id="tabla-listar-clientes" class="table table-striped display tabla-listar-clientes"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Id cliente</th>
                                        <th scope="col">Nombre</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
                <div class="modal-footer">
                    <button id="btnrrellenar_recojo" type="button" class="btn btn-primary btnrrellenar_recojo" data-dismiss="modal">Agregar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
