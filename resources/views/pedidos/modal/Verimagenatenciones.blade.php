<!-- Modal -->
<div class="modal fade" id="modal_imagen_atenciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Ver Adjuntos de Atenciones</h5>
            </div>
            {{-- Form::Open(['route' => ['pedidos.atender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
            <form id="formularioveradjuntosatender" name="formularioveradjuntosatender" enctype="multipart/form-data">
                <input type="hidden" id="veradjuntos" name="veradjuntos">
                <input type="hidden" id="conf_descarga" name="#conf_descarga">
                <div style="margin: 10px">
                    <div class="card">
                        <div class="border rounded card-body border-secondary">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-lg-12">
                                        <div class="row">

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                <div class="row">

                                                    <div class="col-12">
                                                        <h6><b>Archivos adjuntos:</b></h6>
                                                    </div>
                                                    <div class="col-6 d-none">
                                                        <h6><b>Archivos adjuntos Confirmados:</b></h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-12" id="listado_adjuntos_ver"></div>
                                                            <div class="col-12" id="listado_adjuntos_antes_ver"></div>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cerrar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
