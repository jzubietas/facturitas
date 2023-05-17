@extends('adminlte::page')

@section('title', 'Base fría')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">

@endsection

@section('content_header')
    <h1>Base fría
        @can('base_fria.create')
            <a href="{{ route('basefria.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan

        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_agregarbasefria_publicidad">
            <i class="fas fa-plus-circle"></i> Agregar por Publicidad
        </button>

        @include('base_fria.modal.AddBaseFriaPublicidad')

        @can('base_fria.exportar')
            <div class="float-right btn-group dropleft">

                @if(Auth::user()->rol == 'Asesor')
                    <a href="{{route('excel/basefria/exportar')}}" target="_blank" class="btn btn-dark mr-4">
                        <i class="fa fa-download"></i>
                        <i class="fa fa-file-excel"></i>
                        Base fria Asesor
                    </a>
                @endif

                @if(Auth::user()->rol == \App\Models\User::ROL_LLAMADAS || Auth::user()->rol == \App\Models\User::ROL_JEFE_LLAMADAS || Auth::user()->rol == \App\Models\User::ROL_ADMIN || Auth::user()->rol == \App\Models\User::ROL_ASISTENTE_PUBLICIDAD)
                    <a href="{{route('excel.basefria-all_asesor-excel')}}" target="_blank" class="btn btn-dark mr-4">
                        <i class="fa fa-download"></i>
                        <i class="fa fa-file-excel"></i>
                        EXPORTAR TODO BASE FRIA DE TODOS LOS ASESORES
                    </a>
                @endif

                {{--@if(Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Llamadas' || Auth::user()->rol == 'Jefe de llamadas')
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Exportar
                    </button>
                    <div class="dropdown-menu">
                        <a href="" data-target="#modal-exportar2" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Base fría por asesor</a>
                    </div>
                @endif--}}

            </div>
            {{-- @include('base_fria.modal.exportar') --}}
            @include('base_fria.modal.exportar2')
        @endcan
    </h1>
    @if($superasesor > 0)
        <br>
        <div class="bg-4">
            <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
                asesores con privilegios superiores: {{ $superasesor }}
            </h1>
        </div>
    @endif
@stop

@section('content')

    <div class="card" style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
            <div class="card-title">

            </div>

            <table class="table table-striped data-table" id="tablaserverside" style="width:100%">
                <thead>
                <tr>
                    <th>COD.</th>
                    <th>Nombre de cliente</th>
                    <th>Celular</th>
                    <th>Asesor</th>
                    <th width="100px">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('base_fria.modal.convertirid')
            @include('base_fria.modal.modalid')
        </div>
    </div>

@stop


@push('css')
    {{--<link rel="stylesheet" href="/css/admin_custom.css">--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
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

        .textred {
            color: red !important;
        }
    </style>
@endpush


@section('js')

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


    {{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script>
        function resetearcamposdelete() {
            $('#motivo').val("");
            //$('#responsable').val("");
        }

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

        let stepper_titular = "";

        document.querySelectorAll(".btn-navigate-form-step").forEach((formNavigationBtn) => {
            /**
             * Add a click event listener to the button.
             */
            console.log("estep");
            formNavigationBtn.addEventListener("click", () => {
                /**
                 * Get the value of the step.
                 */
                const stepNumber = parseInt(formNavigationBtn.getAttribute("step_number"));

                navigateToFormStep(stepNumber);
            });
        });

        $(document).ready(function () {

            $('#modal_agregarbasefria_publicidad').on('show.bs.modal', function (event) {
                //cargar select2
                console.log("carga de datos")


            })

            $(document).on("click", "#registrar_basefria_publicidad", function (e) {
                e.preventDefault();

                let asesor_de_publicidad = $("#asesores_bf").prop("disabled", false).selectpicker("refresh");
                let asesor_p_bf = asesor_de_publicidad.val();
                //$("#titulares").prop("disabled", true)

                let publicidadbf=$("#publicidad_bf").val();

                let nombrebf = $("#nombre_bf").val();
                let celularbf = $("#celular_bf").val();

                if (asesor_p_bf == '') {
                    Swal.fire(
                        'Error',
                        'Elija al asesor',
                        'warning'
                    )
                    return;
                }else if (celularbf == '') {
                    Swal.fire(
                        'Error',
                        'Ingrese el celular',
                        'warning'
                    )
                    return;
                }else if (celularbf.length!=9) {
                    Swal.fire(
                        'Error',
                        'Ingrese el celular con 9 digitos',
                        'warning'
                    )
                    return;
                } else{
                    //validaciones
                    var fdbf=new FormData()

                    $.ajax({
                        url: "{{ route('basefria.store.publicidad') }}",
                        data: {
                            "nombrebf": nombrebf,
                            "celular": celularbf,
                            "asesor_bf": asesor_p_bf,
                            "publicidadbf":publicidadbf
                        },
                        method: 'POST',
                        success: function (data)
                        {
                            console.log(data);
                            if(data.error.lenght>0)
                            {
                                console.log("error");
                            }
                            else{
                                console.log("ejecuto registro");
                            }
                            /*console.log("ejecuto registro");
                            $("#nombre_bf").val("");
                            $("#celular_bf").val("");
                            Swal.fire(
                                'Base Fria registrado exitosamente',
                                '',
                                'success'
                            )
                            $('#tablaserverside').DataTable().ajax.reload();*/
                        },
                        error:function(xhr)
                        {
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                console.log(value[0])
                                Swal.fire(
                                    'Error',
                                    value[0],
                                    'warning'
                                )
                                return;
                                //$('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                            });
                        }
                    });


                }




            });

            //$.fn.modal.Constructor.prototype._enforceFocus = function() {};

            $(document).on("click", '.btn-navigate-grupopublicidad', function (e) {

                let stepNumberb = parseInt($(this).attr("step_number"));
                console.log(stepNumberb)

                if (stepNumberb == 2) {
                    //guardar grupo publicidad
                    stepper_publicidad = $(this).attr("publicidad");
                    //elegir un asesor de este publicidad
                    var fd=new FormData();
                    //fd.append('publicidad',stepper_titular);

                    $("#publicidad_bf").val(stepper_publicidad);

                    $.ajax({
                        url: "{{ route('basefria.asesor.publicidad.select') }}",
                        method: 'post',
                        success: function (data) {
                            //carga ajax a combo
                            console.log(data);
                            $('#asesores_bf').html(data.html);
                            $("#asesores_bf").selectpicker("refresh");//.trigger('change');
                        }
                    });

                    /*$( "#asesores_bf" ).select2({
                        placeholder: "Elije Asesor",
                        dropdownParent: $('#modal_agregarbasefria_publicidad .modal-content'),
                        theme: "bootstrap",
                        ajax: {
                            url: "{{route('basefria.asesor.publicidad.select')}}",
                            type: "post",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    search: params.term
                                };
                            },
                            processResults: function (response) {
                                return {
                                    results: response
                                };
                            },
                            cache: true
                        }
                    });*/

                    /*$.ajax({
                        //data: fd,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        dataType:'json',
                        url: "{{-- route('basefria.asesor.publicidad.select') --}}",
                        success: function (data) {
                            console.log(data);
                            var options = '';
                            for (var i = 0; i < data.length; i++) {
                                options += '<option value="' + data[i].identificador + '">' + data[i].name + '</option>';
                            }
                            console.log(options)
                            $("#titulares").html(options);
                        }
                    });*/

                    //console.log(stepper_titular);
                }

                navigateToFormStep(stepNumberb);

            });

            $('#modal-delete').on('hidden.bs.modal', function (event) {
                $("#motivo").val('')
                $("#anulacion_password").val('')
                $("#attachments").val(null)
            })

            $('#modal-delete').on('show.bs.modal', function (event) {
//cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('cliente')//id  basefria
                var idresponsable = button.data('responsable')//id  basefria
                var idcodigo = button.data('asesor')
//console.log(idunico);
                $("#hiddenIDdelete").val(idunico);
                if (idcodigo < 10) {
                    idcodigo = 'BF' + idcodigo + '000' + idunico;
                } else if (idcodigo < 100) {
                    idcodigo = 'BF' + idcodigo + '00' + idunico;
                } else if (idunico < 1000) {
                    idcodigo = 'BF' + idcodigo + '0' + idunico;
                } else {
                    idcodigo = 'BF' + idcodigo + '' + idunico;
                }
//solo completo datos
//hiddenId
//

                console.log(idcodigo)
                $(".textcode").html(idcodigo);
                $("#motivo").val('');
//$("#responsable").val(idresponsable);

            });

            $(document).on("submit", "#formdelete", function (evento) {
                evento.preventDefault();
                console.log("action delete action")

                var formData = new FormData();
                formData.append("hiddenID", $("#hiddenIDdelete").val())

                formData.append("motivo", $("#motivo").val())
                formData.append("responsable", $("#responsable").val())
                formData.append("anulacion_password", $("#anulacion_password").val())
                if ($("#attachments")[0].files.length > 0) {
                    var attachments = Array.from($("#attachments")[0].files)
                    attachments.forEach(function (file) {
                        formData.append("attachments[]", file, file.name)
                    })
                }
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('basefriadeleteRequest.post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    $("#modal-delete").modal("hide");
                    resetearcamposdelete();
                    $('#tablaPrincipal').DataTable().ajax.reload();
                }).fail(function (err, error, errMsg) {
                    console.log(arguments, err, errMsg)
                    if (err.status == 401) {
                        Swal.fire(
                            'Error',
                            'No autorizado para poder bloquear el cliente, ingrese una contraseña correcta',
                            'error'
                        )
                    } else {
                        Swal.fire(
                            'Error',
                            'Ocurrio un error: ' + errMsg,
                            'error'
                        )
                    }
                });
            });



            $('#tablaserverside').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('basefriatabla') }}",
                initComplete: function (settings, json) {

                },
                "createdRow": function (row, data, dataIndex) {
                    if (data["situacion"] == 'BLOQUEADO') {
                        $(row).addClass('textred');
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            if (row.id < 10) {
                                if (row.identificador == null) {
                                    return 'BF' + '000' + row.id;
                                } else {
                                    return 'BF' + row.identificador + '000' + row.id;
                                }
                            } else if (row.id < 100) {
                                if (row.identificador == null) {
                                    return 'BF' + '00' + row.id;
                                } else {
                                    return 'BF' + row.identificador + '00' + row.id;
                                }
                            } else if (row.id < 1000) {
                                if (row.identificador == null) {
                                    return 'BF' + '0' + row.id;
                                } else {
                                    return 'BF' + row.identificador + '0' + row.id;
                                }
                            } else {
                                return 'BF' + row.identificador + '000' + row.id;
                            }
                            //return row.id+' aa';
                            //return ''+data+'';
                        }
                    },
                    {
                        data: 'nombre',
                        name: 'nombre',
                    },
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            if (row.icelular != null) {
                                return row.celular + '-' + row.icelular;
                            } else {
                                return row.celular;
                            }
                        }
                    },
                    {
                        data: 'identificador',
                        name: 'Asesor',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '20%',
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
            $('#tablaPrincipal_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });
            $(document).on("keypress", '#tablaPrincipal_filter label input', function () {
                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));
            });


            $('#modal-convertir').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget)
                var idunico = button.data('opcion')//id  basefria
                console.log(idunico);
//id return

                $.ajax({
                    url: "{{ route('basefria.cargarid') }}?basefria_id=" + idunico,
                    method: 'GET',
                    dataType: 'JSON',
                    success: function (data) {
                        let result = JSON.parse(JSON.stringify(data.html[0]));
                        let codigo = '';
                        if (result['id'] < 10) {
                            codigo = 'BF000' + result['id'] + result['celular'];
                        } else if (result['id'] < 100) {
                            codigo = 'BF00' + result['id'] + result['celular'];
                        } else if (result['id'] < 100) {
                            codigo = 'BF0' + result['id'] + result['celular'];
                        } else if (result['id'] < 1000) {
                            codigo = 'BF' + result['id'] + result['celular'];
                        }
                        /*console.log(codigo);
                        console.log(result['nombre']);
                        console.log(result['celular']);
                        console.log(result['id']);*/
                        $(".textcode").html(codigo);
                        $("#nombre").val(result['nombre']);
                        $("#celular").val(result['celular']);
                        $("#hiddenId").val(result['id']);


                        //$('#pruc').html(data.html);
                    }
                });

                /*var button = $(event.relatedTarget)
                var recipient = button.data('whatever')

                var modal = $(this)
                modal.find('.modal-title').text('New message to ' + recipient)
                modal.find('.modal-body input').val(recipient)*/
//console.log("asd");

            });
//


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            /*$(document).on("click","#submit",function(e){


              event.preventDefault();
            });*/


        });
    </script>

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Base fría {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>
        //VALIDAR CAMPO CELULAR
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }
    </script>

    <script>
        //VALIDAR CAMPOS ANTES DE ENVIAR
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("formconvertir").addEventListener('submit', validarFormulario);
        });

        function validarFormulario(evento) {
            evento.preventDefault();
            var nombre = document.getElementById('nombre').value;
            /*var dni = document.getElementById('dni').value;*/
            var celular = document.getElementById('celular').value;
            /*var provincia = document.getElementById('provincia').value;
            var distrito = document.getElementById('distrito').value;
            //var direccion = document.getElementById('direccion').value;
            var referencia = document.getElementById('referencia').value;*/
            var porcentaje_fsb = document.getElementById('porcentaje_fsb').value;
            var porcentaje_esb = document.getElementById('porcentaje_esb').value;
            var porcentaje_fcb = document.getElementById('porcentaje_fcb').value;
            var porcentaje_ecb = document.getElementById('porcentaje_ecb').value;

            if (nombre == '') {
                Swal.fire(
                    'Error',
                    'Ingrese nombre de cliente',
                    'warning'
                )
            } else if (celular == '') {
                Swal.fire(
                    'Error',
                    'Agregue número celular del cliente',
                    'warning'
                )
            } else if (celular.length != 9) {
                Swal.fire(
                    'Error',
                    'Número celular del cliente debe tener 9 dígitos',
                    'warning'
                )
            }
            else if (porcentaje_fsb == '0' || porcentaje_fsb == '') {
                Swal.fire(
                    'Error',
                    'Registre el porcentaje: FÍSICO - sin banca',
                    'warning'
                )
            } else if (porcentaje_esb == '0' || porcentaje_esb == '') {
                Swal.fire(
                    'Error',
                    'Registre el porcentaje: ELECTRÓNICA - sin banca',
                    'warning'
                )
            } else if (porcentaje_fcb == '0' || porcentaje_fcb == '') {
                Swal.fire(
                    'Error',
                    'Registre el porcentaje: FÍSICO - banca',
                    'warning'
                )
            } else if (porcentaje_ecb == '0' || porcentaje_ecb == '') {
                Swal.fire(
                    'Error',
                    'Registre el porcentaje: ELECTRÓNICA - banca',
                    'warning'
                )
            } else {
                clickformconvertir();
                //this.trigger("click");

            }
        }

        function resetearcamposconvertir() {
            $('#nombre').val("");
            $('#dni').val("");
            $('#celular').val("");
            $('#provincia').val("");
            $('#distrito').val("");
            $('#direccion').val("");
            $('#referencia').val("");
            $('#porcentaje1').val("");
            $('#porcentaje2').val("");
            $('#porcentaje3').val("");
            $('#porcentaje4').val("");
        }

        function clickformconvertir() {
            var formData = $("#formconvertir").serialize();
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('basefriaRequest.post') }}",
                data: formData,
            }).done(function (data) {
                $("#modal-convertir").modal("hide");
                resetearcamposconvertir();
                $('#tablaserverside').DataTable().ajax.reload();
            });
        }
    </script>

@stop
