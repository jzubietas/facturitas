<!-- Modal -->
<div class="modal fade" id="modalcorreccion-confirmacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="titulo-confirmacion"  id="exampleModalLabel">Confirmar Correccion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formcorreccion_confirmacion" name="formcorreccion_confirmacion" enctype="multipart/form-data">
                <input type="hidden" id="confirmacion" name="confirmacion">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <p>Esta seguro que desea confirmar la correccion <strong class="textcode"></strong></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
