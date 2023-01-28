<div class="modal fade" id="modal-annuncient-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Modal</h5>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" class="d-none" id="courierregistro2" name="courierregistro2">

                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            {!! Form::label('opciones_modal1', 'Opciones') !!}
                            <select name="opciones_modal1" class="border form-control  border-secondary selectpicker" id="opciones_modal1"
                                    data-live-search="true" title="Seleccione">
                            </select>
                        </div>
                    </div>

                    <div id="op-1-row" class="form-row op-1-row"><!---->

                        <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op1', 'Asesor*') !!}

                            <select name="asesor_op1" class="border form-control border-secondary" id="asesor_op1"
                                data-ruta="{{route('cargar.clientedeudaparaactivar')}}">
                                <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            {!! Form::label('cliente_op1', 'Cliente*') !!}
                            <select name="cliente_op1" class="border form-control border-secondary" id="cliente_op1" data-live-search="true" >
                                <option value="">---- SELECCIONE CLIENTE ----</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            {!! Form::label('clientenuevo_op1', 'Numero de cliente nuevo') !!}
                            <input type="text" name="clientenuevo_op1" id="clientenuevo_op1"  class="form-control" placeholder="Cliente nuevo">

                        </div>

                        <div class="form-group col-lg-12">
                            {!! Form::label('captura_op1', 'Captura de pantalla') !!}
                            <input type="file" name="captura_op1" id="captura_op1"  class="form-control" placeholder="">

                        </div>

                    </div>

                    <div id="op-2-row" class="form-row op-2-row"><!---->
                        <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op2', 'Asesor*') !!}
                            <select name="asesor_op2" class="border form-control border-secondary" id="asesor_op2" data-live-search="true"
                                    data-route="{{ route('cargar.clientedeudaparaactivar') }}">
                                <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            {!! Form::label('cliente_op2', 'Cliente*') !!}
                            <select name="cliente_op2" class="border form-control border-secondary" id="cliente_op2" data-live-search="true" >
                                <option value="">---- SELECCIONE CLIENTE ----</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            {!! Form::label('cantidadpedidos_op2', 'Cantidad de pedidos') !!}
                            <input type="text" name="cantidadpedidos_op2" id="cantidadpedidos_op2"  class="form-control" placeholder="Cliente nuevo">

                        </div>

                        <div class="form-group col-lg-12">
                            {!! Form::label('captura_op2', 'Captura de pantalla') !!}
                            <input type="file" name="captura_op2" id="captura_op2"  class="form-control" placeholder="">

                        </div>


                    </div>

                    <div id="op-3-row" class="form-row op-3-row"><!---->
                        <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op3', 'Asesor*') !!}
                            <select name="asesor_op3" class="border form-control border-secondary" id="asesor_op3" data-live-search="true"
                                    data-route="{{ route('cargar.clientedeudaparaactivar') }}">
                                <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            {!! Form::label('cliente_op3', 'Cliente*') !!}
                            <select name="cliente_op3" class="border form-control border-secondary" id="cliente_op3" data-live-search="true" >
                                <option value="">---- SELECCIONE CLIENTE ----</option>
                            </select>
                        </div>

                        <div class="table-responsive form-group col-lg-12">
                            <table class="table">
                                <thead>
                                    <th>Agregar codigos</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="form-group col-lg-12">
                            {!! Form::label('captura_op3', 'Captura de pantalla') !!}
                            <input type="file" name="captura_op3" id="captura_op3"  class="form-control" placeholder="">

                        </div>

                    </div>

                    <div id="op-4-row" class="form-row op-4-row">
                        <div class="form-group col-lg-6">
                            {!! Form::label('asesor_op4', 'Asesor*') !!}
                            <select name="asesor_op4" class="border form-control border-secondary" id="asesor_op4" data-live-search="true"
                                    data-route="{{ route('cargar.clientedeudaparaactivar') }}">
                                <option value="">---- SELECCIONE ASESOR ----</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            {!! Form::label('cliente_op4', 'Cliente*') !!}
                            <select name="cliente_op4" class="border form-control border-secondary" id="cliente_op4" data-live-search="true" >
                                <option value="">---- SELECCIONE CLIENTE ----</option>
                            </select>
                        </div>
                    </div>






                </form>
            </div>
            <div class="modal-footer">
                sdds
            </div>

        </div>
    </div>
</div>
