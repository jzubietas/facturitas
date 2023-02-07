
<div class="modal fade" id="modal-delete-adjunto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Adjunto en <strong class="textcode">PED00</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <Form id="formdeleteadjunto" name ="formdeleteadjunto">
                <input type = "hidden" id="eliminar_pedido_id" name="eliminar_pedido_id">
                <input type = "hidden" id="eliminar_pedido_id_imagen" name="eliminar_pedido_id_imagen">
                <input type = "hidden" id="eliminar_pedido_id_confirmado" name="eliminar_pedido_id_confirmado">
                <div class="modal-body">
                    <p>Confirme si desea <strong>ELIMINAR</strong> archivo adjunto</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>
                {{ Form::Close() }}
                <Form>
        </div>
    </div>
</div>
