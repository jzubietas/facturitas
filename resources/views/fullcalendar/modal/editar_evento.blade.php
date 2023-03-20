<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="editar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

                <div class="modal-header bg-info">
                    <h5 class="modal-title ">Editar evento</h5>

                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-light btn-edit">
                            <i class="fa fa-edit text-warning"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-delete" aria-label="Close">
                            <i class="fa fa-trash text-danger"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-delete-check d-none" aria-label="Close">
                            <i class="fa fa-trash text-success"></i>
                        </button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            <i class="fa fa-close text-info"></i>
                        </button>
                    </div>

                </div>
                <div class="modal-body">

                    <div class="container-fluid">

                        <form id="frm_editar_evento_calendario" name="frm_editar_evento_calendario">
                            <input type="hidden" id="editar_evento" name="editar_evento">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="form-group mr-2">
                                            <a class="text-primary" href="#"><i class="fas fa-square"></i></a>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control border border-0" id="calendario_nombre_evento_editar"
                                                   placeholder="Nombre de evento" value="" name="calendario_nombre_evento_editar" readonly>
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="calendario_descripcion_evento_editar">Descripcion</label>
                                            <textarea class="form-control border border-0" id="calendario_descripcion_evento_editar"
                                                     name="calendario_descripcion_evento_editar" rows="3" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12 text-center">
                                            <button type="button" class="btn btn-light btn-edit-check d-none">
                                                <i class="fa fa-check text-success"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
                <div class="modal-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-left">

                                <h1 class="float-left">
                                    <i class="fa fa-calendar text-dark font-9"></i>
                                    <span class="fecha_lectura_start text-info font-9"></span>:
                                    <span class="fecha_lectura_end text-success font-9"></span>
                                </h1>

                            </div>
                        </div>
                    </div>

                </div>

        </div>
    </div>
</div>
