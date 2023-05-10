<!-- Modal -->
<div class="modal fade" id="modal-atender" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Atender pedido</h5>
            </div>
            {{-- Form::Open(['route' => ['pedidos.atender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
            <form id="formularioatender" name="formularioatender" enctype="multipart/form-data">
                <input type="hidden" id="hiddenAtender" name="hiddenAtender">
                <div class="modal-body">
                    <p>Complete los siguientes datos para pasar a estado <strong>ATENDIDO</strong> el pedido: <strong
                            class="textcode">PED00</strong></p>
                </div>
                <div style="margin: 10px">
                    <div class="card">
                        <div class="border rounded card-body border-secondary">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <h5>Información:</h5>
                                            </div>
                                            <br><br>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                <div class="row">

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        {!! Form::label('envio_doc', 'Documento enviado') !!}
                                                        @csrf
                                                        {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto', 'multiple']) !!}
                                                        {{-- <td>@csrf<input type="file" id="adjunto" name="adjunto[]" multiple=""/></td> --}}
                                                        <div class="w-100">
                                                            <div class="spinner-border" role="status"
                                                                 id="loading_upload_attachment_file" style="display: none">
                                                                <span class="sr-only">Enviando...</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        {!! Form::label('cant_compro', 'Total de facturas adjuntadas') !!}
                                                        {!! Form::number('cant_compro', '', ['class' => 'form-control', 'id' => 'cant_compro', 'step'=>'1', 'min' => '0']) !!}

                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 d-none">
                                                        {!! Form::label('cant_compro_confirm', 'Total de facturas adjuntadas') !!}
                                                        {!! Form::number('cant_compro_confirm', '', ['class' => 'form-control', 'id' => 'cant_compro_confirm']) !!}

                                                    </div>

                                                    <hr>
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
                                                <div class="row">


                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        {!! Form::label('condicion', 'Estado') !!}

                                                        <select name="condicion" class="form-control" id="condicion">
                                                            <option
                                                                value="{{\App\Models\Pedido::EN_PROCESO_ATENCION_INT}}">{{\App\Models\Pedido::EN_ATENCION_OPE}}</option>
                                                            <option
                                                                value="{{\App\Models\Pedido::ATENDIDO_INT}}">{{\App\Models\Pedido::ATENDIDO_OPE}}</option>
                                                        </select>
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
                    <button type="button" class="btn btn-secondary" id="cerrarmodalatender">Cerrar</button>
                    <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
