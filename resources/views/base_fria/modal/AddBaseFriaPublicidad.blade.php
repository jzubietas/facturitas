<div class="modal fade" id="modal_agregarbasefria_publicidad" aria-labelledby="modal_agregarbasefria_publicidad" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Base Fria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['route' => 'movimientos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}

            <div class="modal-body">

                <div id="multi-step-form-container">
                    <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0 d-none">
                        <!-- Step 1 -->
                        <li class="form-stepper-active text-center form-stepper-list" step="1">
                            <a class="mx-2">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
                                <div class="label">TITULAR</div>
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


                                </div>

                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="button btn-navigate-form-step d-none" type="button" step_number="2">Siguiente</button>
                        </div>
                    </section>

                    <section id="step-2" class="form-step d-none">
                        <h2 class="font-normal "></h2>
                        <!-- Step 2 input fields -->

                        <div class="form-row">

                            <div class="form-group col-lg-12 mx-auto text-center" style="font-size: 18px">

                                <div class="btn-group mx-auto text-center " role="group" aria-label="Basic example">


                                </div>

                            </div>

                        </div>

                        <div class="mt-3 mx-auto text-center">



                            <button class="button btn-navigate-form-step btn btn-info btn-lg rounded text-white" type="button" step_number="1">Atras</button>
                            <button class="button btn-navigate-form-step btn-navigate-banco d-none" type="button" step_number="3">Siguiente</button>
                        </div>
                    </section>

                    <section id="step-3" class="form-step d-none">

                        <!-- Step 3 input fields -->
                        <div class="mt-3">

                            <div class="form-row">
                                

                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-6" style="font-size: 18px">
                                    {!! Form::label('monto', 'Monto pagado') !!}
                                    <input type="text" name="monto" id="monto" class="form-control number" placeholder="Monto pagado...">
                                </div>
                                <div class="form-group col lg-6" style="font-size: 18px">
                                    {!! Form::label('fecha', 'Fecha de voucher') !!}
                                    {!! Form::date('fecha', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                                </div>
                            </div>


                        </div>
                        <div class="mt-3">
                            <button class="button btn-navigate-form-step btn btn-info btn-lg rounded text-white" type="button" step_number="2">Atras</button>
                            <button id="registrar_movimientos" type="button" class="btn btn-success button btn-lg  submit-btn"><i class="fas fa-save"></i> Guardar</button>
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
