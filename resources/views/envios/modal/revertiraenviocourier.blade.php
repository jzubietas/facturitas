<!-- Modal -->
<div class="modal fade" id="modal-revertir-aenviocourier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Revertir pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulariorevertiraenviocourier" name="formulariorevertiraenviocourier" enctype="multipart/form-data">
                <input type="hidden" id="aenviocourierrevertir" name="aenviocourierrevertir">
                <div class="modal-body">
                    <p>Confirme si desea revertir el o los pedidos <strong class="textcode d-none">PED000</strong> a Envio Courier</p>
                    <p class="d-none">Recuerde que tiene <strong class="textcantadjunto"></strong> archivos adjunto(s)</p>

                    <div class="row">
                        <div class="col-12  contenedor-tabla"><!--tabla-->

                            <div class="table-responsive">

                                <table id="tablaPrincipalrevertiraenviocourier" class="table table-striped display"
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
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
