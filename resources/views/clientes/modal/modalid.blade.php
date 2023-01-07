<!-- Modal -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Bloquear cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formdelete" name="formdelete">
                <input type="hidden" id="hiddenIDdelete" name="hiddenID" class="form-control">
                <div class="modal-body">
                    {{-- <div class="form-row"> --}}
                    <div class="form-group col-lg-12">
                        <p style="text-align: justify;">Confirme si desea <strong>BLOQUEAR</strong> el pedido: <strong
                                class="textcode">PED000</strong></p>
                    </div>
                    <div class="form-group col lg-12">
                        {!! Form::label('motivo', 'Ingrese el motivo de la anulación del pedido(Max. 250 caracteres)') !!}
                        {!! Form::textarea('motivo', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Motivo', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group col lg-12">
                        {!! Form::label('attachments', 'Adjuntar Foto') !!}
                        {!! Form::file('attachments[]', ['class' => 'form-control','multiple','id'=>'attachments','accept'=>"image/*"]) !!}
                    </div>
                    <div class="form-group col lg-12">
                        {!! Form::label('anulacion_password', 'Contraseña de anulaciòn') !!}
                        {!! Form::password('anulacion_password', ['class' => 'form-control', 'placeholder' => 'Contraseña de anulaciòn', 'required' => 'required',]) !!}
                    </div>
                    {{-- </div> --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>
                <!--{{-- Form::Close() --}}-->
            </form>
        </div>
    </div>
</div>
