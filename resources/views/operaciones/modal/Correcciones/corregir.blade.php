<!-- Modal -->
<div class="modal fade" id="modalcorreccion-corregir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Ejecutar correccion</h5>
            </div>

            <form id="formcorreccion_corregir" name="formcorreccion_corregir" enctype="multipart/form-data">
                <input type="hidden" id="corregir" name="corregir">
                <input type="hidden" id="cant_adjuntos" name="cant_adjuntos">
                <div class="modal-body">
                    <div class="card">
                        <div class="border rounded card-body border-secondary">
                            <div class="form-row">
                                <div class="form-group col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h5>Información:</h5>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    {!! Form::label('envio_doc', 'Documento enviado') !!}
                                                    @csrf
                                                    {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto', 'multiple']) !!}
                                                    <div class="w-100">
                                                        <div class="spinner-border" role="status"
                                                             id="loading_upload_attachment_file" style="display: none">
                                                            <span class="sr-only">Enviando...</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    {!! Form::label('cant_compro', 'Total de facturas adjuntadas') !!}
                                                    {!! Form::number('cant_compro', '', ['class' => 'form-control', 'id' => 'cant_compro', 'step'=>'1', 'min' => '0','placeholder'=>'0']) !!}

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <button type="button" class="btn btn-primary d-none"
                                                            id="cargar_adjunto">Subir Informacion
                                                    </button>
                                                    <br><br><br>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-12">
                                                    <h6><b>Archivos adjuntos:</b></h6>
                                                </div>
                                                <div class="col-6 d-none">
                                                    <h6><b>Archivos adjuntos Confirmados:</b></h6>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12" id="listado_adjuntos"></div>
                                                        <div class="col-12" id="listado_adjuntos_antes"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cerrarmodalcorreccion">Cerrar</button>
                    <button type="submit" class="btn btn-info">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
