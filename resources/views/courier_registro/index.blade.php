@extends('adminlte::page')

@section('title', 'Administracion | Movimientos Bancarios')

@section('content_header')
    <h1>Lista de Registros Courier

        <a href="" data-target="#modal-add-movimientos" data-toggle="modal">
            <button class="btn btn-info btn-sm"><i class="fas fa-plus-circle"></i> Agregar</button>
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
        <div>
            <div class="card bg-danger">
                <div class="card-body">
                    <h5>SIN CONCILIAR: <b>{{$movimientosSinConciliar}}</b></h5>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Registro</th>
                    <th scope="col">Registrado</th>
                    <th scope="col">Actualizado</th>
                    <th scope="col">Eliminado</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            {{--@include('movimientos.modals.modalDeleteId')--}}
        </div>
    </div>

@stop

@section('css')
    <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
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

        $(document).ready(function () {

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

               {
                    $.ajax({
                        //async:false,
                        url: "{{ route('validar_repetido') }}",
                        data: {"banco": banco, "tipo": tipotrans, "titulares": titular, "monto": monto, "fecha": fecha},
                        method: 'POST',
                        success: function (data) {
                            console.log(data.html);
                            var dataresponse = data.html.split("|");

                            if (dataresponse[0] == "bloqueo") {
                                let movim = dataresponse[1];
                                if (movim < 10) {
                                    movim = 'MOV000' + movim;
                                } else if (movim < 100) {
                                    movim = 'MOV00' + movim;
                                } else if (movim < 1000) {
                                    movim = 'MOV0' + movim;
                                } else {
                                    movim = 'MOV' + movim;
                                }

                                var htmlContent = "<input placeholder='text' class='swal2-input' id='swal-input1'>" +
                                    "<input placeholder='link' class='swal2-input' id='swal-input2'>"

                                Swal.fire({
                                    title: "Deseas continuar con el registro?",
                                    html: 'La misma informacion se encuentra registrado en el movimiento <b>' + movim + '</b>',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Si, continuar!'
                                }).then((result) => {
                                    console.log(result);
                                    if (result.value == true) {
                                        //$("#formulario").trigger("submit");


                                        $.ajax({
                                            //async:false,
                                            url: "{{ route('register_movimiento') }}",
                                            data: {
                                                "banco": banco,
                                                "tipo": tipotrans,
                                                "titulares": titular,
                                                "monto": monto,
                                                "fecha": fecha,
                                                "descrip_otros": descrip_otros
                                            },
                                            method: 'POST',
                                            success: function (data) {
                                                console.log("ejecutar pago");
                                                $("#monto").val("");
                                                $("#fecha").val("");
                                                $("#tipotransferencia").html("");
                                                $("#tipotransferencia").selectpicker("refresh");
                                                $('#tablaPrincipal').DataTable().ajax.reload();
                                                $("#banco").trigger("change");
                                                Swal.fire(
                                                    'Pago registrado correctamente',
                                                    '',
                                                    'success'
                                                )
                                            }

                                        });

                                        //$("#registrar_movimientos").prop("")
                                        //$("#registrar_movimientos").prop( "disabled", true );
                                        //$("#modal-add-movimientos").modal("hide");

                                    } else {
                                        //$("#banco").val("").selectpicker('refresh');
                                        //$("#tipotransferencia").val("").selectpicker('refresh');
                                        $("#descrip_otros").val("").html("");
                                        //$("#titulares").val("").selectpicker('refresh');
                                        $("#monto").val("");
                                        $("#fecha").val("");

                                        //$("#modal-add-movimientos").modal("hide");
                                    }
                                })
                            } else if (dataresponse[0] == "sigue") {
                                $.ajax({
                                    //async:false,
                                    url: "{{ route('register_movimiento') }}",
                                    data: {
                                        "banco": banco,
                                        "tipo": tipotrans,
                                        "titulares": titular,
                                        "monto": monto,
                                        "fecha": fecha,
                                        "descrip_otros": descrip_otros
                                    },
                                    method: 'POST',
                                    success: function (data) {
                                        console.log("ejecutar pago");
                                        $("#monto").val("");
                                        $("#fecha").val("");
                                        $("#tipotransferencia").html("");
                                        $("#tipotransferencia").selectpicker("refresh");
                                        $('#tablaPrincipal').DataTable().ajax.reload();
                                        $("#banco").trigger("change");
                                        Swal.fire(
                                            'Pago registrado correctamente',
                                            '',
                                            'success'
                                        )
                                    }

                                });

                                //$("#formulario").trigger("submit")
                            }
                        }
                    });


                }
                // var oForm = $(this);
                //var formId = oForm.attr("id");
                //var firstValue = oForm.find("input").first().val();
                //alert("Form '" + formId + " is being submitted, value of first input is: " + firstValue);
                // Do stuff
                //return false;
            })

            $("#tipotransferencia").html("");
            $("#tipotransferencia").selectpicker("refresh");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on("change", "#banco", function (event) {

                console.log("banco change");
                $.ajax({
                    url: "{{ route('cargar.tipomovimiento') }}?banco=" + $(this).val(),
                    method: 'GET',
                    success: function (data) {
                        //carga ajax a combo
                        $('#tipotransferencia').html(data.html);
                        $("#tipotransferencia").selectpicker("refresh");
                    }
                });
            });

            $(".descrip_otros").hide();

            $(document).on("change", "#tipotransferencia", function (event) {
                if ($(this).val() == 'OTROS' || $(this).val() == 'YAPE' || $(this).val() == 'PAGO YAPE' || $(this).val() == 'ABON YAPE') {
                    //$("#descrip_otros").prop("visibled",none);
                    $(".descrip_otros").show();
                } else {
                    $(".descrip_otros").hide();
                }
            });

            $(document).on("change", "#banco_movimientos", function (event) {

                console.log("banco_movimientos change");
                $.ajax({
                    url: "{{ route('cargar.tipomovimiento') }}?banco=" + $(this).val(),
                    method: 'GET',
                    success: function (data) {
                        //carga ajax a combo
                        $('#tipo_movimientos').html(data.html);
                        $("#tipo_movimientos").selectpicker("refresh");
                        $('#tablaPrincipal').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on("change", "#tipo_movimientos", function (event) {
                $('#tablaPrincipal').DataTable().ajax.reload();
            });
            $(document).on("change", "#titular_movimientos", function (event) {
                $('#tablaPrincipal').DataTable().ajax.reload();
            });


            $(document).on("click", ".btn-navigate-titular", function () {
                let titular__ = $(this).attr("titular");
                console.log(titular__);
                localStorage.setItem('titular', titular__);
            });

            $(document).on("click", ".btn-navigate-banco", function (e) {
                //e.preventDefault();
                let banco__ = $(this).attr("banco");
                console.log(banco__);
                localStorage.setItem('banco', banco__);
                //return true;
            });

            $('#modal-add-movimientos').on('show.bs.modal', function (event) {
                navigateToFormStep(1);
                $("#registrar_movimientos").prop("disabled", false);
            });

            //para opcion eliminar  movimientos
            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('delete')
                $("#hiddenIDdelete").val(idunico);
                if (idunico < 10) {
                    idunico = 'MOV000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'MOV00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'MOV0' + idunico;
                } else {
                    idunico = 'MOV' + idunico;
                }
                $(".textcode").html(idunico);
            });

            //submit para form eliminar pago
            $(document).on("submit", "#formdelete", function (evento) {
                evento.preventDefault();
                console.log("validar delete");
                clickformdelete();

            })

            $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('movimientostabla') }}",
                    data: function (d) {
                        d.banco = $("#banco_movimientos").val();
                        d.tipo = $("#tipo_movimientos").val();
                        d.titular = $("#titular_movimientos").val();
                    },
                },
                rowCallback: function (row, data, index) {
                    //console.log(data.pago)
                    if (data.pago == "SIN CONCILIAR") {
                        $('td:eq(6)', row).css('color', 'red');
                    }
                },
                initComplete: function (settings, json) {
                    /*if (localStorage. getItem("search_tabla") === null) {
                      //no existe
                    }else{
                      $('#tablaPrincipal_filter label input').val(localStorage.getItem("search_tabla") ).change();
                    }*/
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            if (row.id < 10) {
                                return 'MOV000' + row.id;
                            } else if (row.id < 100) {
                                return 'MOV00' + row.id;
                            } else if (row.id < 1000) {
                                return 'MOV0' + row.id;
                            } else {
                                return 'MOV' + row.id;
                            }
                        }
                    },
                    {
                        data: 'id2',
                        name: 'id2',
                        "visible": false,
                    },
                    {
                        data: 'banco', name: 'banco'
                    },
                    {//asesor
                        data: 'titular',
                        name: 'titular',
                        render: function (data, type, row, meta) {
                            if (data == 'EPIFANIO SOLANO HUAMAN') {
                                data = 'EPIFANIO';
                            } else if (data == 'NIKSER DENIS ORE RIVEROS') {
                                data = 'DENIS';
                            } else {
                                data = '';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'fecha',
                        name: 'fecha',
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },
                    /*{
                      data: 'fecha2',
                      name: 'fecha2',"visible":false
                      //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' )
                    },*/
                    {//observacion
                        data: 'tipo',
                        name: 'tipo',
                        render: function (data, type, row, meta) {
                            if (row.descripcion_otros == null) {
                                return data;
                            } else {
                                return data + '<br>(' + row.descripcion_otros + ')';
                            }
                        }
                    },

                    {//cliente
                        data: 'importe', name: 'importe'
                    },
                    {
                        data: 'pago',
                        name: 'pago',
                        render: function (data, type, row, meta) {
                            /*if(data==null || data==0 || data=='0')
                            {
                              return 'SIN CONCILIAR';
                            }else{
                              return "CONCILIADO";
                            }*/
                            return data;
                        }
                    },//estado de pago
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '20%',
                        render: function (data, type, row, meta) {
                            var urlcreate = '{{ route("movimientos.show", ":id") }}';
                            var urledit = '{{ route("movimientos.edit", ":id") }}';
                            var urlshow = '{{ route("movimientos.show", ":id") }}';
                            urlcreate = urlcreate.replace(':id', row.id);
                            urledit = urledit.replace(':id', row.id);
                            urlshow = urlshow.replace(':id', row.id);
                            @can('movimientos.create')
                                data = data + '<a href="' + urlcreate + '" class="btn btn-info btn-sm">Crear</a>';
                            @endcan
                                @can('movimientos.edit')
                                data = data + '<a href="' + urledit + '" class="btn btn-info btn-sm">Editar</a>';
                            @endcan

                                data = data + '<a href="' + urlshow + '" class="btn btn-info btn-sm">Ver</a>';

                            if (row.pago == 'SIN CONCILIAR') {
                                //data = data+'<a href="" data-target="#modal-update" data-toggle="modal" data-update="'+row.id+'"><button class="btn btn-warning btn-sm"><i class="fas fa-trash-alt"></i> Editar</button></a><br><br>';
                                data = data + '<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="' + row.id + '"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                            }

                            return data;
                        }
                    },
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
