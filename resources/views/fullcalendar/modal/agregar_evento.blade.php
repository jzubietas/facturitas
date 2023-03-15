<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="agregar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Campos para agregar evento.</p>

                <div class="container-fluid">

                    <form>
                        <div class="row">
                            <div class="col-md-12">


                                <div class="form-group">
                                    <label for="calendario_nombre_evento">Nombre de evento</label>
                                    <input type="text" class="form-control is-valid" id="calendario_nombre_evento" placeholder="Nombre de evento" value="">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="calendario_startevento">Inicio</label>
                                    <input type="date" class="form-control is-valid" id="calendario_start_evento" placeholder="Nombre de evento" value="">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="calendario_startevento">Inicio</label>
                                    <input type="date" class="form-control is-valid" id="calendario_start_evento" placeholder="Nombre de evento" value="">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 demo-color">


                                <div class="form-group">
                                    <label>Color picker:</label>
                                    <input type="color" class="form-control">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Registrar tarea</button>
            </div>
        </div>
    </div>
</div>
