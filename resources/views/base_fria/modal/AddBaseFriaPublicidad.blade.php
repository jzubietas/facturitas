<div class="modal fade" id="modal_agregarbasefria_publicidad" aria-labelledby="modal_agregarbasefria_publicidad" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Base Fria - Publicidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['route' => 'basefria.store.publicidad','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}

            <div class="modal-body">

                <div id="multi-step-form-container">
                    <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0 d-none">
                        <!-- Step 1 -->
                        <li class="form-stepper-active text-center form-stepper-list" step="1">
                            <a class="mx-2">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
                                <div class="label">Usuarios de Publicidad</div>
                            </a>
                        </li>
                        <!-- Step 2 -->
                        <li class="form-stepper-unfinished text-center form-stepper-list" step="2">
                            <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>2</span>
                    </span>
                                <div class="label text-muted">Social Profiles</div>
                            </a>
                        </li>
                        <!-- Step 3 -->
                        <li class="form-stepper-unfinished text-center form-stepper-list" step="3">
                            <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>3</span>
                    </span>
                                <div class="label text-muted">Personal Details</div>
                            </a>
                        </li>
                    </ul>
                    <!-- Step Wise Form Content -->

                    <section id="step-1" class="form-step">

                        <!-- Step 1 input fields -->

                        <div class="form-row">
                            <div class="form-group col-lg-6 mx-auto" style="font-size: 18px">

                                <div class="btn-group " role="group" aria-label="Basic example">

                                        <button class="button mx-2 p-2 btn-navigate-grupopublicidad btn btn-success btn-lg rounded text-white"
                                                type="button" step_number="2"
                                                publicidad="1">
                                            Publicidad 1
                                        </button>

                                        <button class="button mx-2 p-2 btn-navigate-grupopublicidad btn btn-info btn-lg rounded text-white"
                                                type="button" step_number="2"
                                                publicidad="2">
                                            Publicidad 2
                                        </button>

                                        <button class="button mx-2 p-2 btn-navigate-grupopublicidad btn btn-primary btn-lg rounded text-white"
                                                type="button" step_number="2"
                                                publicidad="3">
                                            Publicidad 3
                                        </button>

                                </div>

                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="button btn-navigate-form-step d-none" type="button" step_number="2">Siguiente</button>
                        </div>

                    </section>

                    <section id="step-2" class="form-step d-none">

                        <!-- Step 3 input fields -->
                        <div class="mt-3">

                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    {!! Form::label('publicidad', 'Grupo Publicidad') !!}
                                    <input type="text" id="publicidad_bf" name="publicidad_bf" value="Base fría" class="form-control" disabled>
                                </div>


                                <div class="form-group col-lg-6">
                                    {!! Form::label('asesores_bf', 'Asesores') !!}
                                    {!! Form::select('asesores_bf', [] , '', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- Elige Asesor ----']) !!}


                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    {!! Form::label('tipo', 'Tipo de cliente') !!}
                                    <input type="hidden" name="tipo_bf" requerid value="0" class="form-control">
                                    <input type="text" name="cliente" value="Base fría" class="form-control" disabled>
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    {!! Form::label('nombre_bf', 'Nombre') !!}
                                    {!! Form::text('nombre_bf', null, ['class' => 'form-control', 'id' => 'nombre_bf']) !!}
                                </div>

                                <div class="form-group col-lg-6">
                                    {!! Form::label('celular_bf', 'Celular*') !!}
                                    {!! Form::number('celular_bf', null, ['class' => 'form-control', 'id' => 'celular_bf', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
                                </div>

                            </div>


                        </div>
                        <div class="mt-3">
                            <button class="button btn-navigate-form-step btn btn-info btn-lg rounded text-white" type="button" step_number="1">Atras</button>
                            <button id="registrar_basefria_publicidad" type="button" class="btn btn-success button btn-lg  submit-btn"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </section>


                </div>

            </div>


            <div class="modal-footer d-none">

            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>
