<!-- Modal -->
<div class="modal fade" id="modal-congelado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Congelar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formcongelar" name="formcongelar">
                <input type="hidden" id="congelar" name="congelar" class="form-control">
                <div class="modal-body">

                    <div class="form-group col-lg-12">
                        <p style="text-align: justify;">Confirme si desea <strong>CONGELAR</strong> al cliente: <strong
                                class="textcode"></strong></p>
                    </div>
                    <div class="form-group col lg-12">
                        {!! Form::label('motivo', 'Ingrese el motivo para congelar al cliente(Max. 250 caracteres)') !!}
                        {!! Form::textarea('motivo', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Motivo', 'required' => 'required']) !!}
                    </div>
                   
                    <div class="form-group col lg-12">
                        {!! Form::label('congelacion_password', 'Contraseña de congelacion') !!}
                        {!! Form::password('congelacion_password', ['class' => 'form-control', 'placeholder' => 'Contraseña de congelacion', 'required' => 'required',]) !!}
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>

            </form>
        </div>
    </div>
</div>
