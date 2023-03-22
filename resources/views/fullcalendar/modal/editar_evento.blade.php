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

                        <form id="frm_editar_evento_calendario" name="frm_editar_evento_calendario" enctype="multipart/form-data">
                            <input type="hidden" id="editar_evento" name="editar_evento">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="calendario_nombre_evento_editar">Nombre</label>
                                        <input type="text" class="form-control border border-0" id="calendario_nombre_evento_editar"
                                               placeholder="Nombre de evento" value="" name="calendario_nombre_evento_editar" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="calendario_tipo_evento_editar">Tipo</label>
                                        <select id="calendario_tipo_evento_editar" name="calendario_tipo_evento_editar" class="form-control">
                                            <option value="PAGO">PAGO</option>
                                            <option value="OTROS">OTROS</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="calendario_frecuencia_evento_editar">Frecuencia</label>
                                        <select id="calendario_frecuencia_evento_editar" name="calendario_frecuencia_evento_editar" class="form-control">
                                            <option value="una_vez">Una vez</option>
                                            <option value="repetir">Repetir</option>
                                            <option value="ini_mes">Inicio de Mes</option>
                                            <option value="fin_mes">Fin de Mes</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="text" id="edit_start" name="edit_start">
                                <div class="col-md-12">

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="calendario_descripcion_evento_editar">Descripcion</label>
                                            <textarea class="form-control border border-0" id="calendario_descripcion_evento_editar"
                                                     name="calendario_descripcion_evento_editar" rows="3" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6 contenedor_adjunto d-none">
                                        {!! Form::label('attachments', 'Adjuntar Archivos') !!}
                                        {!! Form::file('inputFilesEvent[]', ['class' => 'form-control-file','multiple','id'=>'inputFilesEventE','accept'=>".png, .jpg,.jpeg,.pdf, .xlsx , .xls"]) !!}
                                    </div>
                                    <div class="form-group col-lg-6 contenedor_adjunto d-none">
                                        <div class="image-wrapper">
                                            <img id="picturee" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del adjunto" width="250px">
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
                                <div class="row">
                                    <div class="col-md-2 text-center">
                                        <i class="fa fa-calendar text-danger font-24"></i>
                                    </div>
                                    <div class="col-md-5">
                                        <span class="font-weight-bold bagde fecha_lectura_start text-info font-24"></span>
                                    </div>
                                    <div class="col-md-5">
                                        <span class="font-weight-bold bagde fecha_lectura_end text-success font-24"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

        </div>
    </div>
</div>
