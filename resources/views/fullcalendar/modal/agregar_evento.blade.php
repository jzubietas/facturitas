<!---- para hacer referencia al modal puedes ponerle id con un nombre
 o llamarlo por su clase
---->
<div class="modal fade" id="agregar_evento_calendario" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_add_evento_calendario" name="frm_add_evento_calendario" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Agregar Nota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="calendario_nombre_evento">Nombre de evento</label>
                                    <input type="text" class="form-control is-valid" id="calendario_nombre_evento"
                                           placeholder="Nombre de evento" value="" name="calendario_nombre_evento">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="calendario_tipo_evento">Tipo</label>
                                    <select id="calendario_tipo_evento" name="calendario_tipo_evento" class="form-control">
                                        <option value="PAGO">PAGO</option>
                                        <option value="OTROS">OTROS</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="calendario_frecuencia_evento">Frecuencia</label>
                                    <select id="calendario_frecuencia_evento" name="calendario_frecuencia_evento" class="form-control">
                                        <option value="una_vez">Una vez</option>
                                        <option value="diario">Diario</option>
                                        <option value="ini_mes">Inicio de Mes</option>
                                        <option value="fin_mes">Fin de Mes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="calendario_descripcion_evento_nuevo">Descripcion</label>
                                    <textarea class="form-control border border-success is-valid" id="calendario_descripcion_evento_nuevo"
                                              name="calendario_descripcion_evento_nuevo" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label for="calendario_startevento">Inicio del evento</label>
                                    <input type="date" class="form-control is-valid" id="calendario_start_evento"
                                           placeholder="Nombre de evento" value="" name="calendario_start_evento">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label for="calendario_startevento">Fin del evento</label>
                                    <input type="date" class="form-control is-valid" id="calendario_end_evento"
                                           placeholder="Nombre de evento" value="" name="calendario_end_evento">
                                    <div class="valid-feedback">
                                        Valido!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-none demo-color-texto">
                                <div class="form-group">
                                    <label>Color de Texto:</label>
                                    <input id="calendario_color_evento" type="color" class="form-control" name="calendario_color_evento">
                                </div>
                            </div>
                            <div class="col-md-6 d-none demo-color-fondo">
                                <div class="form-group">
                                    <label>Color de Fondo:</label>
                                    <input id="calendario_fondo_evento" type="color" class="form-control" name="calendario_fondo_evento">
                                </div>
                            </div>
                            <div class="form-group col lg-6">
                                {!! Form::label('attachments', 'Adjuntar Archivos') !!}
                                {!! Form::file('inputFilesEvent[]', ['class' => 'form-control-file','id'=>'inputFilesEventA','accept'=>".png, .jpg,.jpeg,.pdf, .xlsx , .xls"]) !!}
                            </div>
                            <div class="form-group col-lg-6">
                                <div class="image-wrapper">
                                    <img id="picturea" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del adjunto" width="250px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_form" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="submit_form" class="btn btn-primary">Registrar nota</button>
                </div>
            </form>
        </div>
    </div>
</div>
