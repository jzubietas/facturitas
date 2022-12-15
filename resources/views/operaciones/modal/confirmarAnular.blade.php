<!-- Modal -->
<div class="modal fade" id="modal_confirmar_anular" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px!important;">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar anulacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Adjuntos del pedido: <strong id="anular_pedido_id">PED00</strong></p>
                <div class="mt-4">
                    <input type="hidden" id="anular_pedido_id">
                    <div class="alert alert-warning"> Permite seleccionar multiples archivos</div>
                    <div class="input-group mb-3">
                        <input type="file" name="attachments[]" multiple accept="image/*"
                               id="anularAttachments" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="attachmentsButtom">
                    Confirmar anulacion de Pedido
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
