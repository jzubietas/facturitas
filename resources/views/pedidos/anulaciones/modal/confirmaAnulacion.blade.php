<!-- Modal -->
<div class="modal fade" id="modal-confirma-anulacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar Anulacion Pedido SA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="frmConfirmaAnulacion" name="frmConfirmaAnulacion" enctype="multipart/form-data">
                <input type="hidden" id="txtPedidoId" name="txtPedidoId">
                <input type="hidden" id="txtPedidoAnulacionId" name="txtPedidoAnulacionId" >
                <div class="modal-body">
                    {{-- <div class="form-row"> --}}
                    <div class="form-group col-lg-12 mb-0">
                        <div class="row mb-0">
                            <div class="col-sm-4 border-right">
                                <p >Confirme si desea <strong>ANULAR</strong> el pedido: <strong
                                        class="textcodepedido">PED000</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p> Anulacion registrada por : <span class="right badge badge-warning text-sm txtNombreRegistraAnulacion">-</span> </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        {!! Form::label('motivo', 'Ingrese el motivo de la anulación del pedido') !!}
                        {!! Form::textarea('motivo', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Motivo']) !!}
                    </div>
                    <div class="form-group col-lg-12">
                        {!! Form::label('attachments', 'Adjuntar Archivos') !!}
                        {!! Form::file('inputFilesAdmin[]', ['class' => 'form-control-file','multiple','id'=>'inputFilesAdmin','accept'=>".png, .jpg,.jpeg,.pdf, .xlsx , .xls"]) !!}
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group col-lg-12">
                                {!! Form::label('responsable', 'Responsable de la anulación') !!}
                                {!! Form::text('responsable', '', ['class' => 'form-control', 'placeholder' => 'Responsable', 'required' => 'required','readonly']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group col-lg-12">
                                {!! Form::label('anulacion_password', 'Contraseña de anulación') !!}
                                {!! Form::password('anulacion_password', ['class' => 'form-control', 'placeholder' => 'Contraseña de anulación',]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group col-lg-12">
                                {!! Form::label('cbxCulpables', 'Culpable') !!}
                                <select class="form-control selectpicker" data-live-search="true" id="cbxCulpables" name="cbxCulpables">
                                    <option value="-1">---OTRO CULPABLE---</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group col-lg-12">
                                {!! Form::label('txtOtrosCulpables', 'Otro Culpable') !!}
                                {!! Form::text('txtOtrosCulpables', '', ['class' => 'form-control', 'placeholder' => 'Otros Culpables']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success btnConfirmaSolicitudAdmin">Confirmar</button>
                </div>
                <!--{{-- Form::Close() --}}-->
            </form>
        </div>
    </div>
</div>
