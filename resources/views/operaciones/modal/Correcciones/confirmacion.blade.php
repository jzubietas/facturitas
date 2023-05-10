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
                    <div class="row fotos">
                        <div class="col-12 col-md-8">
                            <div class="input-group w-80">
                                <div class="custom-file w-90">
                                    <input type="file" class="custom-file-input form-control-file" id="adjunto1" name="adjunto1" lang="es" multiple>
                                    <label class="custom-file-label" for="adjunto1">Foto de las correcciones</label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" id="trash_adjunto1" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-none">
                            <div class="input-group w-80">
                                <div class="custom-file w-90">
                                    <input type="file" class="custom-file-input form-control-file" id="adjunto2" name="adjunto2" lang="es">
                                    <label class="custom-file-label" for="adjunto2">Foto del domicilio</label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" id="trash_adjunto2" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-none">
                            <div class="input-group w-80">
                                <div class="custom-file w-90">
                                    <input type="file" class="custom-file-input form-control-file" id="adjunto3" name="adjunto3" lang="es">
                                    <label class="custom-file-label" for="adjunto3">Foto de quien recibe</label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" id="trash_adjunto3" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row preview">
                        <div class="col-4 mt-12">
                            <div class="form-group">
                                <div class="image-wrapper">
                                    <img id="picture2"
                                         src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                                         data-src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                                         alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 d-none mt-12">
                            <div class="form-group">
                                <div class="image-wrapper">
                                    <img id="picture3"
                                         src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                         data-src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                         alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 d-none mt-12">
                            <div class="form-group">
                                <div class="image-wrapper">
                                    <img id="picture3"
                                         src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                         data-src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                         alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h6>Observacion (Opcional):</h6>
                            <textarea class="form-control mb-20" rowspan="3" id="observacion" name="observacion" placeholder="Si tiene una observación, ingresela aquí"></textarea>
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
