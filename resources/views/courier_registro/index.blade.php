@extends('adminlte::page')

@section('title', 'Courier | Courier Registros')

@section('content_header')
    <h1>Lista de Registros Courier

        <a href="" data-target="#modal-addcourierregistro" data-toggle="modal" data-backdrop="static">
            <button class="btn btn-info btn-sm" ><i class="fas fa-plus-circle"></i> Agregar</button>
        </a>
        {{-- @endcan --}}
        {{-- @can('pagos.exportar')--}}
        <div class="float-right btn-group dropleft">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar-2" data-toggle="modal" class="dropdown-item"
                   target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Movimientos</a>
            </div>
        </div>

        @include('courier_registro.modals.AddRegistro')
        @include('courier_registro.modals.modal_registros_asesor')
    </h1>
    <br>
    <div class="d-flex justify-content-between align-items-center">
        <div class="row d-none">
            <div class=" col-lg-6">
                <select name="banco_movimientos" class="border form-control selectpicker border-secondary"
                        id="banco_movimientos" data-live-search="true">
                    <option value="">---- SELECCIONE BANCO ----</option>
                    <option value="BCP">BCP</option>
                    <option value="BBVA">BBVA</option>
                    <option value="INTERBANK">INTERBANK</option>
                </select>
            </div>
            <div class=" col-lg-6">
                <select name="titular_movimientos" class="border form-control selectpicker border-secondary"
                        id="titular_movimientos" data-live-search="true">
                    <option value="">---- SELECCIONE TITULAR ----</option>
                    <option value="EPIFANIO SOLANO HUAMAN">EPIFANIO SOLANO HUAMAN</option>
                    <option value="ALFREDO ALEJANDRO GABRIEL MONTALVO">ALFREDO ALEJANDRO GABRIEL MONTALVO</option>
                    <option value="SABINA LACHOS">SABINA LACHOS</option>
                    <option value="NIKSER DENIS ORE RIVEROS">NIKSER DENIS ORE RIVEROS</option>
                </select>
            </div>
            <div class="col-lg-4 d-none">
                <select name="tipo_movimientos" class="border form-control selectpicker border-secondary"
                        id="tipo_movimientos" data-live-search="true">
                    <option value="">---- SELECCIONE TIPO MOVIMIENTO ----</option>
                </select>
            </div>

        </div>

    </div>
@stop

@section('content')

    <ul class="nav nav-pills nav-justified nav-tabs mb-24 mt-24" id="myTab" role="tablist">
        <li class="nav-item text-center">
            <a class="nav-link active font-weight-bold"
               id="norelacionado-tab"
               data-toggle="tab">
                <i class="fa fa-inbox" aria-hidden="true"></i> No relacionado
                <sup><span class="badge badge-light count_courierregistro_norelacionado d-none">0</span></sup>
            </a>
        </li>
        <li class="nav-item text-center">
            <a class="nav-link font-weight-bold"
               id="relacionado-tab"
               data-toggle="tab">
                <i class="fa fa-inbox" aria-hidden="true"></i> Relacionado
                <sup><span class="badge badge-light count_courierregistro_relacionado d-none">0</span></sup>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="norelacionado" role="tabpanel" aria-labelledby="norelacionado-tab">
            <table id="tablaPrincipal_norelacionado" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Registro</th>
                    <th scope="col">Registrado</th>
                    <th scope="col">Actualizado</th>
                    <th scope="col">Relacionado</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="relacionado" role="tabpanel" aria-labelledby="relacionado-tab">
            <table id="tablaPrincipal_norelacionado" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Registro</th>
                    <th scope="col">Registrado</th>
                    <th scope="col">Actualizado</th>
                    <th scope="col">Relacionado</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            {{--@include('movimientos.modals.modalDeleteId')--}}
        </div>
    </div>

@stop

@section('css')
    <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .content-header h1
        {
            font-size:1.0rem !important;
        }
        .yellow {
            color: #fcd00e !important;
        }

        .red {
            background-color: red !important;
        }

        .white {
            background-color: white !important;
        }

        .bg-4 {
            background: linear-gradient(to right, rgb(240, 152, 25), rgb(237, 222, 93));
        }

        .t-stroke {
            color: transparent;
            -moz-text-stroke-width: 2px;
            -webkit-text-stroke-width: 2px;
            -moz-text-stroke-color: #000000;
            -webkit-text-stroke-color: #ffffff;
        }

        .t-shadow-halftone2 {
            position: relative;
        }

        .t-shadow-halftone2::after {
            content: "AWESOME TEXT";
            font-size: 10rem;
            letter-spacing: 0px;
            background-size: 100%;
            -webkit-text-fill-color: transparent;
            -moz-text-fill-color: transparent;
            -webkit-background-clip: text;
            -moz-background-clip: text;
            -moz-text-stroke-width: 0;
            -webkit-text-stroke-width: 0;
            position: absolute;
            text-align: center;
            left: 0px;
            right: 0;
            top: 0px;
            z-index: -1;
            background-color: #ff4c00;
            transition: all 0.5s ease;
            text-shadow: 10px 2px #6ac7c2;
        }

    </style>
    <style>

        h2 {
            margin: 0;
        }

        #multi-step-form-container {
            /*margin-top: 5rem;*/
        }

        .text-center {
            text-align: center;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .pl-0 {
            padding-left: 0;
        }

        .button {
            /*padding: 1.5rem;*/
            /*border: 1px solid #4361ee;*/
            /*background-color: #4361ee;*/
            /*color: #fff;*/
            /*border-radius: 5px;*/
            cursor: pointer;
        }

        .submit-btn {
            border: 1px solid #0e9594;
            background-color: #0e9594;
        }

        .mt-3 {
            margin-top: 2rem;
        }

        .d-none {
            display: none;
        }

        .form-step {
            /*border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 3rem;*/
        }

        .font-normal {
            font-weight: normal;
        }
    </style>
@stop

@section('js')

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

    <script>
        let tablaregistrosasesorLista=null;

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const configDataTableLanguages = {
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "_START_ - _END_ / _TOTAL_",
                    "infoEmpty": "0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": ``,
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            }

            $('#modal-relacionar-registro_courier').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#courierreg").val(button.data('courierreg-registro'));
                //console.log()

                //button.data('courierreg-registro')
                $(".text-muted").html(button.data('courierreg-registro'))

                $('#datatable-registros-asesor').DataTable().clear().destroy();

                tablaregistrosasesorLista=$('#datatable-registros-asesor').DataTable({
                    ...configDataTableLanguages,
                    "dom": 'lrtip',
                    processing: true,
                    pagging: true,
                    stateSave: true,
                    serverSide: true,
                    searching: true,
                    "order": [[0, "desc"]],
                    createdRow: function (row, data, dataIndex) {},
                    ajax: {
                        url: "{{ route('registros.asesor.lista') }}",
                        data: function (d) {
                            console.log(d);
                            d.length=5;
                            d.courier_reg=button.data('courierreg-registro');
                        },
                    },
                    columns: [
                        {data: 'id', name: 'id',},
                        {data: 'direccion', name: 'direccion',},
                        {data: 'referencia', name: 'referencia',},
                        {data: 'creacion', name: 'creacion',},
                        {data: 'importe', name: 'importe',},
                        {data: 'action', name: 'action',},
                    ],
                });
            });

            $('#datatable-registros-asesor tbody').on( 'click', '.elegir', function () {
                var data = tablaregistrosasesorLista.row( $(this).closest('tr') ).data()
                let courierreg=$("#courierreg").val()
                console.log("idenvio "+data.id)
                let dataid=data.id
                console.log("importe "+data.importe)
                console.log("tracking "+data.direccion)
                let aparicion = data.direccion.indexOf(',');
                console.log(aparicion)
                console.log("numregistro "+data.referencia)
                console.log("fecha "+data.creacion)
                if(aparicion!==-1){
                    Swal.fire(
                        'Envio contiene mas de un tracking',
                        '',
                        'warning'
                    )
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        html: 'Esta seguro de realizar el math a este envio <b>'+data.correlativo+'</b>',
                        showDenyButton: true,
                        confirmButtonText: 'Estoy de acuerdo',
                        denyButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var fd_courier = new FormData();
                            fd_courier.append('courierregistro', courierreg);
                            fd_courier.append('direcciongrupo', dataid);
                            $.ajax({
                                data: fd_courier,
                                processData: false,
                                contentType: false,
                                type: 'POST',
                                url: "{{ route('relacionar_envio_courierregistro') }}",
                                success: function (data) {
                                    console.log(data);
                                    $("#modal-relacionar-registro_courier").modal("hide");
                                    $("#datatable-registros-asesor").DataTable().ajax.reload();
                                    $("#tablaPrincipal_norelacionado").DataTable().ajax.reload();
                                    $("#tablaPrincipal_relacionado").DataTable().ajax.reload();
                                }
                            });

                        }
                    });
                }

            } );

            $("#numregistro").bind('keypress', function (event) {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });

            $(document).on("keyup","#numregistro",function(){
                let len=$(this).val().length;
                let numregistro = $("#numregistro").val();
                console.log(len);
                if(len==12){
                    //validar existente
                    $.ajax({
                        url: "{{ route('validar_register_courier_registros') }}",
                        data: {
                            "numregistro": numregistro
                        },
                        method: 'POST',
                        success: function (data) {
                            let datas=data.html;
                            if(datas==2){
                                Swal.fire(
                                    'Error',
                                    'Ocurrio un error',
                                    'warning'
                                )
                            }else if(datas==1){
                                Swal.fire(
                                    'Error',
                                    'Ya existe un registro con esta informacion',
                                    'warning'
                                )
                                //bloquear form
                                $('#addform').prop("disabled",true)
                                return;
                            }else{
                                $('#addform').prop("disabled",false)
                            }
                        }
                    });
                }
            });

            $(document).on("click", "#registrar_registros", function (e) {
                e.preventDefault();

                let numregistro = $("#numregistro").val();

                if (numregistro == '') {
                    Swal.fire(
                        'Error',
                        'Ingrese un numero de registro valido',
                        'warning'
                    )
                    return;
                }

                $.ajax({
                    //async:false,
                    url: "{{ route('register_courier_registros') }}",
                    data: {
                        "numregistro": numregistro
                    },
                    method: 'POST',
                    success: function (data) {
                        console.log("ejecutar pago");
                        $("#numregistro").html("");
                        $('#tablaPrincipal').DataTable().ajax.reload();
                        Swal.fire(
                            'Registro correctamente ingresado',
                            '',
                            'success'
                        )
                    }
                });
            })

            $(document).on("submit","#formaddcourierregistro",function(event){
                event.preventDefault();
                let numregistro = $("#numregistro").val();

                if (numregistro == '') {
                    Swal.fire(
                        'Error',
                        'Ingrese un numero de registro valido',
                        'warning'
                    )
                    return;
                }

                $.ajax({
                    url: "{{ route('register_courier_registros') }}",
                    data: {
                        "numregistro": numregistro
                    },
                    method: 'POST',
                    success: function (data) {
                        console.log("ejecutar pago");
                        $("#numregistro").html("");
                        //$("#modal-addcourierregistro").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();
                        Swal.fire(
                            'Registro correctamente ingresado',
                            '',
                            'success'
                        )
                    }
                });
            });

            $('#modal-addcourierregistro').on('show.bs.modal', function (event) {
                $("#numregistro").val("");
            });

            $(document).on("submit", "#formdelete", function (evento) {
                evento.preventDefault();
            })

            $('#tablaPrincipal_norelacionado').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('courierregistrotabla') }}",
                    data: function (d) {
                        d.relacionado='0'
                    },
                },
                rowCallback: function (row, data, index) {
                },
                initComplete: function (settings, json) {
                },
                columns: [
                    {data: 'id',name: 'id',},
                    {data: 'courier_registro', name: 'courier_registro'},
                    {data: 'created_at',name: 'created_at',},
                    {data: 'updated_at',name: 'updated_at',},
                    {data: 'relacionado',name: 'relacionado',},
                    {data: 'status',name: 'status',},
                    {data: 'action',name: 'action',orderable: false,searchable: false,sWidth: '20%',},
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });

            $('#tablaPrincipal_relacionado').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('courierregistrotabla') }}",
                    data: function (d) {
                        d.relacionado='1'
                    },
                },
                rowCallback: function (row, data, index) {
                },
                initComplete: function (settings, json) {
                },
                columns: [
                    {data: 'id',name: 'id',},
                    {data: 'courier_registro', name: 'courier_registro'},
                    {data: 'created_at',name: 'created_at',},
                    {data: 'updated_at',name: 'updated_at',},
                    {data: 'relacionado',name: 'relacionado',},
                    {data: 'status',name: 'status',},
                    {data: 'action',name: 'action',orderable: false,searchable: false,sWidth: '20%',},
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });


        });
    </script>

    <script>
        function resetearcamposdelete() {
            //$('#motivo').val("");
            //$('#responsable').val("");
        }


    </script>

    <script>
        //VALIDAR CAMPOS NUMERICO DE MONTO EN PAGOS

        $('input.number').keyup(function (event) {

            if (event.which >= 37 && event.which <= 40) {
                event.preventDefault();
            }

            $(this).val(function (index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
                    ;
            });
        });
    </script>

    @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
        <script>
            Swal.fire(
                'Pago {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>

        $(document).ready(function () {
            /*btn-navigate-titular*/
        });


        /**
         * Define a function to navigate betweens form steps.
         * It accepts one parameter. That is - step number.
         */
        const navigateToFormStep = (stepNumber) => {
            /**
             * Hide all form steps.
             */
            document.querySelectorAll(".form-step").forEach((formStepElement) => {
                formStepElement.classList.add("d-none");
            });
            /**
             * Mark all form steps as unfinished.
             */
            document.querySelectorAll(".form-stepper-list").forEach((formStepHeader) => {
                formStepHeader.classList.add("form-stepper-unfinished");
                formStepHeader.classList.remove("form-stepper-active", "form-stepper-completed");
            });
            /**
             * Show the current form step (as passed to the function).
             */
            document.querySelector("#step-" + stepNumber).classList.remove("d-none");
            /**
             * Select the form step circle (progress bar).
             */
            const formStepCircle = document.querySelector('li[step="' + stepNumber + '"]');
            /**
             * Mark the current form step as active.
             */
            formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-completed");
            formStepCircle.classList.add("form-stepper-active");
            /**
             * Loop through each form step circles.
             * This loop will continue up to the current step number.
             * Example: If the current step is 3,
             * then the loop will perform operations for step 1 and 2.
             */
            for (let index = 0; index < stepNumber; index++) {
                /**
                 * Select the form step circle (progress bar).
                 */
                const formStepCircle = document.querySelector('li[step="' + index + '"]');
                /**
                 * Check if the element exist. If yes, then proceed.
                 */
                if (formStepCircle) {
                    /**
                     * Mark the form step as completed.
                     */
                    formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-active");
                    formStepCircle.classList.add("form-stepper-completed");
                }
            }
        };
        /**
         * Select all form navigation buttons, and loop through them.
         */
        let stepper_banco = "";
        let stepper_titular = "";
        document.querySelectorAll(".btn-navigate-form-step").forEach((formNavigationBtn) => {
            /**
             * Add a click event listener to the button.
             */
            formNavigationBtn.addEventListener("click", () => {
                /**
                 * Get the value of the step.
                 */
                const stepNumber = parseInt(formNavigationBtn.getAttribute("step_number"));

                navigateToFormStep(stepNumber);
            });
        });


        $(document).ready(function () {

            $(document).on("click", '.btn-navigate-titular', function (e) {

                let stepNumberb = parseInt($(this).attr("step_number"));
                console.log(stepNumberb)

                if (stepNumberb == 2) {
                    //guardar banco
                    stepper_titular = $(this).attr("titular");
                    $("#titulares").val(stepper_titular).selectpicker("refresh").trigger("change");
                    //console.log(stepper_titular);
                }

                navigateToFormStep(stepNumberb);

            });

            $(document).on("click", '.btn-navigate-banco', function (e) {

                let stepNumberc = parseInt($(this).attr("step_number"));
                //console.log(stepNumberc)

                if (stepNumberc == 3) {
                    stepper_banco = $(this).attr("banco");
                    console.log(stepper_banco);
                    $("#banco").val(stepper_banco).selectpicker("refresh").trigger("change");

                    $("#banco").prop('disabled', true).selectpicker("refresh");
                    $("#titulares").prop('disabled', true).selectpicker("refresh");
                    //automarcar opciones en readonly
                }
                navigateToFormStep(stepNumberc);
                //navigateToFormStep(stepNumberb);

            });

        });
    </script>

@stop
