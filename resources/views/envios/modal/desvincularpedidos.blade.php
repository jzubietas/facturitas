<!-- Modal -->
<div class="modal fade" id="modal-desvincular" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">
                    Retornar a sobres con dirección
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formdireccion" name="formdireccion">
                <div class="modal-body">
                    <p class="d-none">Ingrese la dirección de envío del pedido: <strong class="textcode">PED000</strong>
                    </p>

                    <input id="direcciongrupo" name="direcciongrupo" value="" type="hidden">

                    <h5 class="modal-title" id="exampleModalLabel">
                        <div class="alert alert-warning">Que código desea retornar a SOBRES CON DIRECCION</div>
                    </h5>

                    <div class="row">
                        <div class="col-12  contenedor-tabla"><!--tabla-->

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


                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="desvincularConfirmar">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>


