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
                <p class="p-2 border border-dark">
                    <b>Motivo de anulacion: </b> <span id="motivo_anulacion_text"></span>
                </p>
                <div class="mt-4">
                    <input type="hidden" id="anular_pedido_id">
                    <div class="alert alert-warning"> Permite seleccionar multiples notas de credito</div>
                    <div class="input-group mb-3">
                        <input type="file" name="attachments[]" multiple accept="application/pdf"
                               id="anularAttachments" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <button class="btn btn-success" id="attachmentsButtomRechazar">
                        Rechazar Anulacion
                    </button>
                    <button class="btn btn-danger" id="attachmentsButtom">
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
