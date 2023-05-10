<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="editar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nombre evento</h5>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button type="button" class="btn btn-danger btn-delete-event" aria-label="Close">
                        <i class="fa fa-trash"></i>
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

                </div>
                <div class="modal-body">

                    <div class="container-fluid">
                        <input type="text" id="editar_evento" name="editar_evento">
                        <form id="frm_editar_evento_calendario" name="frm_editar_evento_calendario">
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
                            </div>
                        </form>

                    </div>

                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <i class="google-material-icons meh4fc hggPq" aria-hidden="true">event</i>
                        </div>
                    </div>


                </div>

        </div>
    </div>
</div>
