<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="eliminar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_eliminar_evento_calendario" name="frm_eliminar_evento_calendario">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Esta seguro que desea eliminar la tarea.</p>
                    <div class="container-fluid">
                        <input type="text" id="eliminar_evento" name="eliminar_evento">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Seguro</button>
                </div>
            </form>
        </div>
    </div>
</div>
