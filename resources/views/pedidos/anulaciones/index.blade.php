{{--pedidos.recojo--}}
@extends('adminlte::page')

@section('title', 'ANULACIONES')

@section('content_header')

    @if(Auth::user()->rol == 'Administrador')
        <a class="btn btn-info btn-sm m-0" href="#" data-target="#modal-agregar-anulacion" data-toggle="modal">
            <b class="text-white font-weight-bold d-flex align-items-center justify-content-center">
                <i class="fas fa-user-plus p-1"></i>
                <p class="m-0 text-card-navbar">Agregar Anulacion</p>
            </b>
        </a>
    @endif

    <h1 class="text-center">
        <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i> Bandeja de Anulaciones
    </h1>

@stop

@section('content')

    @include('modal.AgegarAnulacion.modalAgregarAnulacion')

    <div class="card p-0" style="overflow: hidden !important;">

        <div class="tab-content" id="myTabContent" style="overflow-x: scroll !important;">

            <div class="tab-pane fade show active" id="enmotorizado" role="tabpanel" aria-labelledby="enmotorizado-tab">
                <table id="tblListadoRecojo" class="table table-striped">{{-- display nowrap  --}}
                    <thead>
                    <tr>
                        <th></th>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Cliente</th>
                        <th scope="col" class="align-middle">Razón social</th>
                        <th scope="col" class="align-middle">Cantidad</th>
                        <th scope="col" class="align-middle">Id</th>
                        <th scope="col" class="align-middle">RUC</th>
                        <th scope="col" class="align-middle">F. Registro</th>
                        <th scope="col" class="align-middle">F. Actualizacion</th>
                        <th scope="col" class="align-middle">Total (S/)</th>
                        <th scope="col" class="align-middle">Est. pago</th>
                        <th scope="col" class="align-middle">Con. pago</th>
                        <th scope="col" class="align-middle">Est. Sobre</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                @include('operaciones.modal.confirmarRecepcionRecojo')
            </div>

        </div>


    </div>

@stop

@push('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    <style>
        .yellow_color_table {
            background-color: #ffd60a !important;
        }

        .blue_color_table {
            background-color: #3A98B9 !important;
        }
    </style>
    @include('partials.css.time_line_css')
@endpush

@section('js')

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

    <script>
        let tblListadoRecojo = null;
        let dataForm_agregaranulacion_f = {};
        let dataForm_agregaranulacion_pc = {};

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function renderButtomsDataTable(row, data) {
                if (data.destino == 'PROVINCIA') {
                    $('td', row).css('color', '#20c997')
                }
                if (data.estado == 0) {
                    $('td', row).css('color', 'red')
                }

            }

            var detailRows = [];

            tblListadoRecojo = $('#tblListadoRecojo').DataTable({
                dom: 'Blfrtip',
                processing: true,
                serverSide: true,
                searching: true,
                //stateSave: true,
                order: [[8, "desc"]],
                ajax: "{{ route('pedidosanulacionestabla') }}",
                createdRow: function (row, data, dataIndex) {
                    if (data["estado"] == "1") {
                        if (data.pendiente_anulacion == 1) {
                            $('td', row).css('background', 'red').css('font-weight', 'bold');
                        }
                    } else {
                        $(row).addClass('textred');
                    }
                },
                rowCallback: function (row, data, index) {
                    var pedidodiferencia = data.diferencia;

                    if (data.condicion_code == 4 || data.estado == 0) {
                        $('td:eq(13)', row).css('background', '#ff7400').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                    } else {
                        if (pedidodiferencia == null) {
                            $('td:eq(13)', row).css('background', '#ca3a3a').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                        } else {
                            if (pedidodiferencia > 3) {
                                $('td:eq(13)', row).css('background', '#ca3a3a').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                            } else {
                                $('td:eq(13)', row).css('background', '#44c24b').css('text-align', 'center').css('font-weight', 'bold');
                            }
                        }
                    }

                    $('[data-jqconfirm]', row).click(function () {
                        $.confirm({
                            theme: 'material',
                            columnClass: 'large',
                            title: 'Editar direccion de envio',
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    url: '{{route('pedidos.envios.get-direccion')}}?pedido_id=' + data.id,
                                    dataType: 'json',
                                    method: 'get'
                                })
                                    .done(function (response) {

                                        self.setContent(response.html);
                                        if (!response.success) {
                                            self.$$confirm.hide();
                                        }
                                    })
                                    .fail(function (e) {
                                        self.setContent('Ocurrio un error');
                                    });
                            },
                            buttons: {
                                confirm: {
                                    text: 'Actualizar',
                                    btnClass: 'btn-success',
                                    action: function () {
                                        var self = this;
                                        const form = self.$content.find('form')[0];
                                        const data = new FormData(form)
                                        if (data.get('celular').length != 9) {
                                            $.alert({
                                                title: 'Alerta!',
                                                content: '¡El numero de celular debe tener 9 digitos!',
                                            });
                                            return false

                                        }

                                        self.showLoading(true)
                                        $.ajax({
                                            data: data,
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            url: "{{route('pedidos.envios.update-direccion')}}",
                                        }).always(function () {
                                            self.close();
                                            $('#tblListadoRecojo').DataTable().ajax.reload();
                                        });
                                        return false
                                    }
                                },
                                cancel: function () {

                                },
                            },
                            onContentReady: function () {

                                var self = this;
                                const form = self.$content.find('form')[0];
                                const data = new FormData(form)

                                self.$content.find('select#distrito').selectpicker('refresh');
                            }
                        });
                    })

                    $('[data-verforotos]', row).click(function () {
                        var data = $(this).data('verforotos')
                        $.dialog({
                            columnClass: 'xlarge',
                            title: 'Fotos confirmadas',
                            type: 'green',
                            content: function () {
                                return `<div class="row">
${data.foto1 ? `
<div class="col-md-4">
<div class="card">
<div class="card-header d-none"><h5>Foto de los sobres</h5></div>
<div class="card-body">
<img src="${data.foto1}" class="w-100">
</div>
</div>
</div>
` : ''}
${data.foto2 ? `
<div class="col-md-4">
<div class="card">
<div class="card-header d-none"><h5>Foto del domicilio</h5></div>
<div class="card-body">
<img src="${data.foto2}" class="w-100">
</div>
</div>
</div>
` : ''}
${data.foto3 ? `
<div class="col-md-4">
<div class="card">
<div class="card-header d-none"><h5>Foto de quien recibe</h5></div>
<div class="card-body">
<img src="${data.foto3}" class="w-100">
</div>
</div>
</div>
` : ''}
</div>`
                            }
                        })

                    })

                    $("[data-jqconfirmdetalle=jqConfirm]", row).on('click', function (e) {
                        openConfirmDownloadDocuments($(e.target).data('target'), $(e.target).data('idc'), $(e.target).data('codigo'))
                    })
                },
                initComplete: function (settings, json) {
                },
                columns: [
                    {
                        class: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        "searchable": false
                    },
                    {data: 'codigos', name: 'codigos',},
                    {
                        data: 'celulares',
                        name: 'celulares',
                        render: function (data, type, row, meta) {
                            if (row.icelulares != null) {
                                return row.celulares + '-' + row.icelulares + ' - ' + row.nombres;
                            } else {
                                return row.celulares + ' - ' + row.nombres;
                            }

                        },
                    },
                    {data: 'empresas', name: 'empresas',},
                    {data: 'cantidad', name: 'cantidad', render: $.fn.dataTable.render.number(',', '.', 2, ''),},
                    {data: 'users', name: 'users',},
                    {data: 'ruc', name: 'ruc',},
                    {
                        data: 'fecha',
                        name: 'fecha',
                    },
                    {
                        data: 'fecha_up',
                        name: 'fecha_up',
                        "visible": false,
                    },
                    {
                        data: 'total',
                        name: 'total',
                        render: $.fn.dataTable.render.number(',', '.', 2, '')
                    },
                    {
                        data: 'condicion_pa',
                        name: 'condicion_pa',
                        render: function (data, type, row, meta) {

                            if (row.condiciones == 'ANULADO' || row.condicion_code == 4 || row.estado == 0) {
                                return 'ANULADO';
                            } else {
                                if (row.condicion_pa == null) {
                                    return 'SIN PAGO REGISTRADO';
                                } else {
                                    if (row.condicion_pa == '0') {
                                        return '<p>SIN PAGO REGISTRADO</p>'
                                    }
                                    if (row.condicion_pa == '1') {
                                        return '<p>ADELANTO</p>'
                                    }
                                    if (row.condicion_pa == '2') {
                                        return '<p>PAGO</p>'
                                    }
                                    if (row.condicion_pa == '3') {
                                        return '<p>ABONADO</p>'
                                    }
                                    //return data;
                                }
                            }

                        }
                    },
                    {
                        data: 'condiciones_aprobado',
                        name: 'condiciones_aprobado',
                        render: function (data, type, row, meta) {
                            if (row.condicion_code == 4 || row.estado == 0) {
                                return 'ANULADO';
                            }
                            if (data != null) {
                                return data;
                            } else {
                                return 'SIN REVISAR';
                            }

                        }
                    },
                    {
                        data: 'condicion_envio',
                        name: 'condicion_envio',
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

            window.ocultar_div_modal_agregaranulacion = function () {
                $("#modal-agregaranulacion-pc-container").hide();
                $("#form-agregaranulacion-pc input").val("");

                $("#modal-agregaranulacion-f-container").hide();
                $("#form-agregaranulacion-f input").val("");
            }

            ocultar_div_modal_agregaranulacion();

            $('#modal-agregar-anulacion').on('show.bs.modal', function (event) {
                ocultar_div_modal_agregaranulacion();

            });

            /**/
            $(document).on('click', "button#btn_agregaranulacion_pc,button#btn_agregaranulacion_f",
                function (e) {
                    ocultar_div_modal_agregaranulacion();
                    switch (e.target.id) {
                        case 'btn_agregaranulacion_pc':
                            $.ajax({
                                url: "{{ route('clientecomboagregarcontacto') }}",
                                method: 'POST',
                                success: function (data) {
                                    $('#cbxClienteAgregaNuevo').html(data.html).selectpicker("refresh");
                                    $("#modal-agregaranulacion-pc-container").show();
                                }
                            });
                            break;
                        case 'btn_agregaranulacion_f':
                            $.ajax({
                                url: "{{ route('clientecomboagregarcontacto') }}",
                                method: 'POST',
                                success: function (data) {
                                    $('#cbxCambiaNombre').html(data.html).selectpicker("refresh");
                                    $("#modal-agregaranulacion-f-container").show();
                                }
                            });
                            break;
                    }
                })
            /**/

            $('#modal-envio-recojo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var grupopedido = button.data('grupopedido')
                var codigos = button.data('codigos')

                $(".textcode").html(codigos);
                $("#hiddenIdGrupoPedido").val(grupopedido);
            });

            $(document).on("submit", "#modal-envio-recojo", function (evento) {
                evento.preventDefault();

                var data = new FormData();
                data.append('hiddenIdGrupoPedido', $("#hiddenIdGrupoPedido").val());

                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('envios.confirmar-recepcion-recojo') }}",
                    success: function (data) {
                        $("#modal-envio-recojo .textcode").text('');
                        $("#modal-envio-recojo").modal("hide");
                        Swal.fire('Mensaje', data.mensaje, 'success')
                        $('#tblListadoRecojo').DataTable().ajax.reload();
                    }
                });
            });

            tblListadoRecojo.on('responsive-display', function (e, datatable, row, showHide, update) {
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });

            $('#modal_recojomotorizado').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#input_recojomotorizado").val(button.data('direccion_grupo'));
            });


            /*MODAL ANULACION PEDIDOS COMPLETOS*/
            $(document).on("keyup", '#codigoCodigoPc', function () {

                let tamanio = $.trim($(this).val()).length;
                if (tamanio > 8 && tamanio < 16) {
                    $.ajax
                    ({

                        type: "POST",
                        url: "{{ route('pedidosanulaciones.modal.agregaranulacion_pc') }}",
                        data: {
                            codigo: $(this).val(),

                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {

                            $('#asesorCodigoPc').val(response.data.name);
                            $('#importeCodigoPc').val(response.data.env_importe);
                            $('#anulacionCodigoPc').val(response.data.env_importe);
                            $('#rucCodigoPc').val(response.data.ruc);
                            $('#razonCodigoPc').val(response.data.nombre_empresa);
                        }
                    });
                }
            });

            $(document).on("click", "#form-agregaranulacion-pc #cargaArchivosPC", function () {
                var file = document.createElement('input');
                file.type = 'file';
                file.click()
                file.addEventListener('change', function (e) {
                    if (file.files.length > 0) {
                        $('#form-agregaranulacion-pc').find('.result_picture').css('display', 'block');
                        //console.log(URL.createObjectURL(file.files[0]))
                        dataForm_agregarcontacto_b.agregarcontacto_b_captura = file.files[0]
                        $('#form-agregaranulacion-pc').find('.result_picture>img').attr('src', URL.createObjectURL(file.files[0]))
                    }
                })
            });


            /*MODAL ANULACION - F*/
            $(document).on("keyup", '#codigoCodigoF', function () {

                let tamanio = $.trim($(this).val()).length;
                if (tamanio > 8 && tamanio < 16) {
                    $.ajax
                    ({
                        type: "POST",
                        url: "{{ route('pedidosanulaciones.modal.agregaranulacion_f') }}",
                        data: {
                            codigo: $(this).val(),

                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {

                            $('#asesorCodigoF').val(response.data.name);
                            $('#importeCodigoF').val(response.data.env_importe);
                            $('#anulacionCodigoF').val(response.data.env_importe);
                            $('#rucCodigoF').val(response.data.ruc);
                            $('#razonCodigoF').val(response.data.nombre_empresa);
                        }
                    });
                }
            });

            $(document).on("click", "#form-agregaranulacion-f #cargaArchivosF", function () {
                var file = document.createElement('input');
                file.type = 'file';
                file.click()
                file.addEventListener('change', function (e) {
                    if (file.files.length > 0) {
                        $('#form-agregaranulacion-f').find('.result_picture').css('display', 'block');
                        console.log("ADD: ", URL.createObjectURL(file.files[0]))
                        dataForm_agregaranulacion_f.agregar_imagen_f = file.files[0]
                        $('#form-agregaranulacion-f').find('.result_picture>img').attr('src', URL.createObjectURL(file.files[0]))
                    }
                })
            });

            window.document.onpaste = function (event) {
                console.log('AQUI PRRO :V ');
                var items = (event.clipboardData || event.originalEvent.clipboardData).items;
                console.log("ITEM #|:", items);
                console.log("ITEM #||:", (event.clipboardData || event.originalEvent.clipboardData));
                var files = []
                for (index in items) {
                    var item = items[index];
                    if (item.kind === 'file') {
                        var file = item.getAsFile()
                        files.push(file)
                    }
                }
                if (files.length > 0) {
                    $('#form-agregaranulacion-f').find('.result_picture').css('display', 'block')
                    console.log('DENTRO DE IF: ', URL.createObjectURL(files[0]))
                    $('#form-agregaranulacion-f').find('.result_picture>img').attr('src', URL.createObjectURL(files[0]))
                    dataForm_agregaranulacion_f.agregar_imagen_f = files[0]
                }
            }


        });
    </script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
@stop

@push('css')
    <style>
        @media screen and (max-width: 2249px) {
            .dis-grid {
                display: flex;
                justify-content: center;
                align-items: center;
                align-self: center;
                flex-direction: column;
            }

            .btn-fontsize {
                font-size: 15px;
            }

            .etiquetas_asignacion {
                background-color: #b0deb3 !important;
                font-size: 12px;
                padding: 4px;
                font-weight: 700;
                line-height: 1;
                white-space: nowrap;
                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                color: #4a604b !important;
                margin-left: 2px;
            }

            .sorting:before,
            .sorting:after {
                top: 20px;
            }

        }

        @media screen and (max-width: 2144px) {
            thead,
            tr,
            td {
                vertical-align: middle !important;
            }

            .btn-fontsize {
                font-size: 11px;
                min-width: 85px;
                max-width: 130px;
            }

            .dis-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 2fr));
                gap: 0.7rem
            }
        }

        @media screen and (max-width: 2039px) {
            .dis-grid {
                display: flex;
                justify-content: center;
                align-items: center;
                align-self: center;
                flex-direction: column;
            }

            .btn-fontsize {
                min-width: 75px;
                width: 100px;
            }
        }

        @media screen and (max-width: 1440px) {
            .etiquetas_asignacion {
                font-size: 9px;
                padding: 2px;
                white-space: pre-line !important;
            }
        }
    </style>
@endpush

