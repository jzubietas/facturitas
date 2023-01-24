<!-- Modal -->
<div class="modal fade" id="modal-addcourierregistro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Courier Registro</h5>
            </div>
            <form id="formaddcourierregistro" name="formaddcourierregistro" enctype="multipart/form-data">
                <input type="hidden" id="courierregistro" name="courierregistro">
                <div class="modal-body d-none">
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
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-20">
                                                        {!! Form::label('numregistro', 'Numero de Registro') !!}
                                                        {!! Form::text('numregistro', null, ['class' => 'form-control', 'placeholder' => 'Número de registro', 'required' => 'required','maxlength' => 12,'pattern'=>'\d*','autocomplete'=>'off']) !!}
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
                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-info" id="addform">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
