<!-- Modal -->
<div class="modal fade" id="modalcorreccion-rechazo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="titulo-rechazo"  id="exampleModalLabel">Rechazar Correccion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formcorreccion_rechazo" name="formcorreccion_rechazo" enctype="multipart/form-data">
                <input type="hidden" id="rechazo" name="rechazo">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <p>Esta seguro que desea rechazar la correccion <strong class="textcode"></strong></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Rechazar</button>
                </div>
            </form>
        </div>
    </div>
</div>
