@extends('adminlte::page')

@section('title', 'Operaciones | Correcciones')

@section('content_header')
    <h1>Lista de CORRECCIONES

        <div class="float-right btn-group dropleft">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img
                        src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
            </div>
        </div>
        @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos entregados', 'key' => '10'])
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

    <div class="card">
        <div class="card-body">
            <table style="display: none;" cellspacing="5" cellpadding="5">
                <tbody>
                <tr>
                    <td>Minimum date:</td>
                    <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
                    <td></td>
                    <td>Maximum date:</td>
                    <td><input type="text" value={{ $dateMax }} id="max" name="max" class="form-control"></td>
                </tr>
                </tbody>
            </table>
            <br>
            <table id="tablaOperacionesBandejadeCorrecciones" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">Item</th>
                    <th scope="col" class="align-middle">Tipo</th>
                    <th scope="col" class="align-middle">Codigo</th>
                    <th scope="col" class="align-middle">Ruc</th>
                    <th scope="col" class="align-middle">Empresa</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Fecha de correccion</th>
                    <th scope="col" class="align-middle">Condicion</th>
                    <th scope="col" class="align-middle">Motivo</th>
                    <th scope="col" class="align-middle">Adjuntos</th>
                    <th scope="col" class="align-middle">Detalle</th>
                    <th scope="col" class="align-middle">Accion</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('operaciones.modal.Correcciones.veradjunto')
            {{--@include('operaciones.modal.Correcciones.confirmacion')--}}
            @include('operaciones.modal.Correcciones.rechazar')
            @include('operaciones.modal.Correcciones.corregir')
            @include('operaciones.modal.Correcciones.DeleteAdjunto')
        </div>
    </div>
@stop

@push('css')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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
        #tablaOperacionesBandejadeCorrecciones {
          width: 100% !important;
        }
        td {
          vertical-align: middle !important;
          text-align: center !important;
        }


    </style>
@pushend

@section('js')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

    </script>

    <script>
        $(document).ready(function () {

            $('#modal-delete-adjunto').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var img_pedidoid = button.data('imgid')
                var imgadjunto = button.data('imgadjunto')
                var imgadjuntoconfirm = button.data('imgadjuntoconfirm')
                $(".textcode").html("PED" + img_pedidoid);
                $("#eliminar_pedido_id").val(img_pedidoid);
                $("#eliminar_pedido_id_imagen").val(imgadjunto);
                $("#eliminar_pedido_id_confirmado").val(imgadjuntoconfirm);
            });

            $(document).on("change", "#adjunto", function (evento) {
                $("#cargar_adjunto").trigger("click");
            });

            $(document).on("click", "#cargar_adjunto", function (evento) {
                let idunico = $("#corregir").val();
                console.log(idunico);
                $('#cargar_adjunto').attr("disabled", true);
                $('#cargar_adjunto').html('Subiendo archivos...');

                var data = new FormData(document.getElementById("formcorreccion_corregir"));
                $("#loading_upload_attachment_file").show()
                $("#adjunto").hide()
                $.ajax({
                    type: 'POST',
                    url: "{{ route('operaciones.subircorreccionsinconfirmar',':id') }}".replace(':id', idunico),
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {

                        $('#cargar_adjunto').prop("disabled", false);
                        $('#cargar_adjunto').text('Subir Informacion');
                        $('#listado_adjuntos').html(data);
                    }
                })
                    .done(function (data) {
                        $("#adjunto").val(null)
                    })
                    .always(function () {
                        $("#adjunto").show()
                        $("#loading_upload_attachment_file").hide()
                    });
                return false;
            });

            $(document).on("submit", "#formdeleteadjunto", function (evento) {
                evento.preventDefault();
                console.log("ejecutando eliminando adjunto")
                let pedidoidimagenes = $("#eliminar_pedido_id").val();
                let pedidoconfirmado = $("#eliminar_pedido_id_confirmado").val();/*0 o 1*/
                console.log(pedidoidimagenes);
                var fddeleteadjunto = new FormData();
                fddeleteadjunto.append('eliminar_pedido_id', pedidoidimagenes);
                fddeleteadjunto.append('eliminar_pedido_id_imagen', $("#eliminar_pedido_id_imagen").val());
                fddeleteadjunto.append('eliminar_pedido_id_confirmado', pedidoconfirmado);

                console.log(fddeleteadjunto);

                //return false;
                $.ajax({
                    url: "{{ route('operaciones.eliminaradjunto') }}",
                    type: 'POST',
                    data: fddeleteadjunto,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        console.log("rest ajax")
                        $('.adjuntos[data-adjunto="' + data.html + '"]').remove();
                        $('#modal-delete-adjunto').modal('toggle');
                        if (pedidoconfirmado == 1) {
                            $.ajax({
                                url: "{{ route('operaciones.editatencion',':id') }}".replace(':id', pedidoidimagenes),
                                data: pedidoidimagenes,
                                method: 'POST',
                                success: function (data) {
                                    console.log(data)
                                    console.log("obtuve las imagenes atencion del pedido " + pedidoidimagenes)
                                    $('#listado_adjuntos_antes').html(data);
                                }
                            });
                        } else if (pedidoconfirmado == 0) {
                            $.ajax({
                                url: "{{ route('operaciones.editatencionsinconfirmar',':id') }}".replace(':id', pedidoidimagenes),
                                data: pedidoidimagenes,
                                method: 'POST',
                                success: function (data) {
                                    console.log(data)
                                    console.log("obtuve las imagenes atencion del pedido " + pedidoidimagenes)
                                    $('#listado_adjuntos').html(data);
                                }
                            });
                        }

                    }
                }).done(function (data) {
                });

            });

            $('#modalcorreccion-corregir').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                console.log((button.data('correccion')))
                $("#corregir").val(button.data('correccion'))
                idunico=button.data('correccion');

                $.ajax({
                    url: "{{ route('operaciones.cargarimagenes.correccion',':id') }}".replace(':id', idunico),
                    data: idunico,
                    method: 'POST',
                    success: function (data) {
                        console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idunico)
                        $('#listado_adjuntos').html("");
                        $('#listado_adjuntos_antes').html(data);
                    }
                });
            })

            $(document).on("click", "#cerrarmodalcorreccion", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                fd.append('corregir', $("#corregir").val());
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.correccioncerrarmodal') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modalcorreccion-corregir .textcode").text('');
                        $("#modalcorreccion-corregir").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });

            $("#cant_compro").bind('keypress', function (event) {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });

            $(document).on("submit","#formcorreccion_corregir",function(event) {
                event.preventDefault();
                var formData = new FormData();
                formData.append("corregir", $("#corregir").val())
                console.log($("#adjunto_total_attachment").val());
                formData.append("adjunto_total_attachment", $("#adjunto_total_attachment").val())
                formData.append("cant_compro", $("#cant_compro").val())// solo numeros en cant compro
                let cant_compro=$("#cant_compro").val();
                if(cant_compro<=0 || cant_compro=='')
                {
                    Swal.fire(
                        'Ingrese una cantidad diferente de 0 o vacio en total de facturas adjuntadas',
                        '',
                        'warning'
                    );
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('correccionconfirmacionRequest.post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    $("#modalcorreccion-corregir").modal("hide");
                    $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                }).fail(function (err, error, errMsg) {
                    console.log(arguments, err, errMsg)
                });
            });


            $('#modalcorreccion-rechazo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#rechazo").val(button.data('correccion'))
            })



            $(document).on("submit","#formcorreccion_rechazo",function(event) {
                event.preventDefault();
                var formData = new FormData();
                formData.append("rechazo", $("#rechazo").val())
                $.ajax({
                    type: 'POST',
                    url: "{{ route('correccionrechazoRequest.post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    $("#modalcorreccion-rechazo").modal("hide");
                    $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                }).fail(function (err, error, errMsg) {
                    console.log(arguments, err, errMsg)
                });
            });


            $('#modalcorreccion-veradjunto').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                //$(".textcode").html(idcodigo);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('correccionobteneradjuntoRequest') }}",
                    data: {"correccion": button.data('correccion')},
                }).done(function (data) {
                    console.log(data.cantidad);
                    $(".textcountadj").html(data.cantidad);
                    if (data.cantidad > 0) {
                        $("#imagenes_adjunto").html(data.html)
                    } else {
                        console.log("sin imagenes");
                    }
                });
            });


            /*$('#modal-delete-adjunto').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var img_pedidoid = button.data('imgid')
                var imgadjunto = button.data('imgadjunto')
                var imgadjuntoconfirm = button.data('imgadjuntoconfirm')
                $(".textcode").html("PED" + img_pedidoid);
                $("#eliminar_pedido_id").val(img_pedidoid);
                $("#eliminar_pedido_id_imagen").val(imgadjunto);
                $("#eliminar_pedido_id_confirmado").val(imgadjuntoconfirm);
            });*/

            $(document).on("submit", "#formulariorevertirporatender", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                fd.append('codigo', $("#hiddenRevertirpedidoporatender").val());

                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.revertirenvioidporatender') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-revertir-poratender .textcode").text('');
                        $("#modal-revertir-poratender .textcantadjunto").text('');
                        $("#modal-revertir-poratender").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });



            $(document).on("click", "#cerrarmodalatender", function (evento) {
                evento.preventDefault();
                console.log("no atender")
                var fd = new FormData();
                fd.append('correccion', $("#correccion").val());
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.corregircerrar') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-correccion-op .textcode").text('');
                        $("#modal-correccion-op").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on("submit", "#formulario_atender_op", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                var data = new FormData(document.getElementById("formulario_atender_op"));

                fd.append('hiddenEnvio', $("#hiddenEnvio").val());

                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.atender_pedido_op') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-envio-op .textcode").text('');
                        $("#modal-envio-op").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });

            $('#modal-revertir').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('revertir')
                var codigo_pedido = button.data('codigo')
                //$(".textcode").html("PED"+idunico);
                $(".textcode").html(codigo_pedido);
                $("#hiddenRecibir").val(idunico);
            });

            $(document).on("submit", "#formulariorevertir", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                fd.append('hiddenRevertirpedido', $("#hiddenRecibir").val());

                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.revertirenvioid') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-revertir .textcode").text('');
                        $("#modal-revertir").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });

            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('delete')
                var idresponsable = button.data('responsable')
                $("#hiddenIDdelete").val(idunico);
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }
                $(".textcode").html(idunico);
                $("#motivo").val('');
                $("#responsable").val(idresponsable);
            });

            $('#modal-revertir-ajefeop').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('revertir')//
                var idcodigo = button.data('codigo')
                var idadjuntos = button.data('adjuntos')
                $(".textcode").html(idcodigo);
                $(".textcantadjunto").html(idadjuntos);
                $("#ajefeoperevertir").val(idunico);
            });

            $(document).on("submit", "#formulariorevertirajefeop", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                console.log($("#ajefeoperevertir").val());
                fd.append('ajefeoperevertir', $("#ajefeoperevertir").val());

                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.revertirajefeop') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-revertir-ajefeop .textcode").text('');
                        $("#modal-revertir-ajefeop .textcantadjunto").text('');
                        $("#modal-revertir-ajefeop").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });

            $('#modal-revertir-asindireccion').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('revertir')
                var idcodigo = button.data('codigo')
                var idadjuntos = button.data('adjuntos')
                $(".textcode").html(idcodigo);
                $(".textcantadjunto").html(idadjuntos);
                $("#asindireccionrevertir").val(idunico);
            });



            $(document).on("submit", "#formulariorevertirasindireccion", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                fd.append('asindireccionrevertir', $("#asindireccionrevertir").val());

                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.revertirasindireccion') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-revertir-asindireccion .textcode").text('');
                        $("#modal-revertir-asindireccion .textcantadjunto").text('');
                        $("#modal-revertir-asindireccion").modal("hide");
                        $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    }
                });
            });

            $('#tablaOperacionesBandejadeCorrecciones').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('operaciones.correccionestabla') }}",
                    data: function (d) {
                        d.min = $("#min").val();
                        d.max = $("#max").val();
                    },
                },
                createdRow: function (row, data, dataIndex) {
                    //console.log(row);
                    if (data["type"] == "PEDIDO COMPLETO") {
                        $('td', row).css('background', '#ffab66').css('font-weight', 'bold');
                    } else if (data["type"] == "FACTURAS"){
                        $('td', row).css('background', '#aa8caf').css('font-weight', 'bold');
                    }
                    else if (data["type"] == "GUIAS"){
                        $('td', row).css('background', '#9edc9c').css('font-weight', 'bold');
                    }
                    else if (data["type"] == "BANCARIZACIONES"){
                        $('td', row).css('background', '#a8e0e0 ').css('font-weight', 'bold');
                    }
                },
                rowCallback: function (row, data, index) {
                    $(function () {
                        $('[data-toggle="tooltip"]',row).tooltip()
                    })
                },
                initComplete: function (settings, json) {

                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {data: 'type', name: 'type',},
                    {data: 'code', name: 'code',},
                    {data: 'ruc', name: 'ruc',},
                    {data: 'razon_social', name: 'razon_social',},
                    {data: 'asesor_identify', name: 'asesor_identify',},
                    {
                        data: 'fecha_correccion',
                        name: 'fecha_correccion',
                        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss'),
                        "visible": true,
                    },
                    {data: 'condicion_envio', name: 'condicion_envio',},
                    {data: 'motivo', name: 'motivo', "visible": false,},
                    {data: 'adjuntos',name: 'adjuntos',},
                    {data: 'detalle', name: 'detalle',"visible":false,},
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

            $('#tablaOperacionesBandejadeCorrecciones_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });
            $(document).on("keypress", '#tablaOperacionesBandejadeCorrecciones_filter label input', function () {
                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));
            });

        });
    </script>

    <script>
        $("#penvio_doc").change(mostrarValores1);

        function mostrarValores1() {
            $("#envio_doc").val($("#penvio_doc option:selected").text());
        }

        $("#pcondicion").change(mostrarValores2);

        function mostrarValores2() {
            $("#condicion").val($("#pcondicion option:selected").text());
        }
    </script>

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Pedido {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        /*window.onload = function () {
          $('#tablaOperacionesBandejadeCorrecciones').DataTable().draw();
        }*/
    </script>

    <script>
        /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

            /*$.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");

                    var d = data[4].split("/");
                    var startDate = new Date(d[1]+ "/" +  d[0] +"/" + d[2]);

                    if (min == null && max == null) { return true; }
                    if (min == null && startDate <= max) { return true;}
                    if(max == null && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );*/

            $("#min").datepicker({
                onSelect: function () {
                    $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    //localStorage.setItem('dateMin', $(this).val() );
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });

            $("#max").datepicker({
                onSelect: function () {
                    $('#tablaOperacionesBandejadeCorrecciones').DataTable().ajax.reload();
                    //localStorage.setItem('dateMax', $(this).val() );
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });

            //$("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"});
            //$("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy" });
            //var table = $('#tablaOperacionesBandejadeCorrecciones').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            /*$('#min, #max').change(function () {
                table.draw();
            });*/
        });
    </script>
    <script>
        /*if (localStorage.getItem('dateMin') )
        {
          $( "#min" ).val(localStorage.getItem('dateMin')).trigger("change");
        }else{
          localStorage.setItem('dateMin', "{{$dateMin}}" );
    }
    if (localStorage.getItem('dateMax') )
    {
      $( "#max" ).val(localStorage.getItem('dateMax')).trigger("change");
    }else{
      localStorage.setItem('dateMax', "{{$dateMax}}" );
    }*/
        //console.log(localStorage.getItem('dateMin'));
        //console.log(localStorage.getItem('dateMax'));
    </script>
@stop
