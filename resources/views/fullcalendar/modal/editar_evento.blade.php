<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="editar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nombre evento</h5>

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
                                                   placeholder="Nombre de evento" value="" name="calendario_nombre_evento_editar">
                                        </div>
                                        <button type="button" class="btn btn-light btn-edit-check d-none">
                                            <i class="fa fa-check text-success"></i>
                                        </button>
                                    </div>
                                    <div class="form-groupa">
                                        <span>Miércoles, 8 de marzo</span>
                                        <div class="valid-feedback">
                                            a
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
                                    <i class="fa fa-calendar text-dark"></i>
                                    Autor: Autor
                                </h1>

                            </div>
                        </div>
                    </div>

                </div>

        </div>
    </div>
</div>
