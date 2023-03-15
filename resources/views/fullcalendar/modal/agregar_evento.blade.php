<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="agregar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_add_evento_calendario" name="frm_add_evento_calendario">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Campos para agregar evento.</p>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="calendario_nombre_evento">Nombre de evento</label>
                                    <input type="text" class="form-control is-valid" id="calendario_nombre_evento"
                                           placeholder="Nombre de evento" value="" name="calendario_nombre_evento">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="calendario_startevento">Inicio</label>
                                    <input type="date" class="form-control is-valid" id="calendario_start_evento"
                                           placeholder="Nombre de evento" value="" name="calendario_start_evento">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="calendario_startevento">Inicio</label>
                                    <input type="date" class="form-control is-valid" id="calendario_start_evento"
                                           placeholder="Nombre de evento" value="" name="calendario_start_evento">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 demo-color">
                                <div class="form-group">
                                    <label>Color picker:</label>
                                    <input id="calendario_color_evento" type="color" class="form-control" name="calendario_color_evento">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_form" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="submit_form" class="btn btn-primary">Registrar tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>
