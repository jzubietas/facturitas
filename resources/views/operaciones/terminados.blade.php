{{--operaciones.terminados--}}
@extends('adminlte::page')

@section('title', 'Operaciones | Sobres terminados')

@section('content_header')
    <h1>Lista de pedidos TERMINADOS
        {{-- @can('pedidos.exportar')
        <div class="float-right btn-group dropleft">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Exportar
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('pedidosatendidosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
          </div>
        </div>
        @endcan --}}
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

    <div class="card"  style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
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
            <table id="tablaOperacionesBandejadeSobres" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">Item</th>
                    <th scope="col" class="align-middle">Código</th>
                    <th scope="col" class="align-middle">Razón social</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Fecha de registro</th>
                    <th scope="col" class="align-middle">Destino</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Adjuntos</th>
                    <th scope="col" class="align-middle">Atendido por</th>
                    <th scope="col" class="align-middle">Jefe</th>
                    <th scope="col" class="align-middle">Accion</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            @include('pedidos.modal.revertirid')
            @include('operaciones.modal.revertirajefeop')
            @include('operaciones.modal.revertirasindireccion')
            @include('operaciones.modal.CorreccionAtencion')
            @include('operaciones.modal.VerAdjuntosAtencion')
            @include('operaciones.modal.veradjuntoid')
        </div>
    </div>
@stop

@push('css')
    {{-- <link rel="stylesheet" href="../css/admin_custom.css"> --}}
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
    </style>



@endpush

@section('js')
    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
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

            $('#modal-veradjuntos-atencion').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('veradjuntos')
                var confirmo_descarga = button.data('adj')

                $(".textcode").html("PED" + idunico);
                $("#veradjuntos").val(idunico);
                $('#conf_descarga').val(confirmo_descarga);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('operaciones.datossubidaadj',':id') }}".replace(':id', idunico),
                    data: idunico,
                    success: function (data) {
                        console.log(data);
                        console.log(data.pedidos[0]['cant_compro']);

                        $('#cant_compro').val(data.pedidos[0]['cant_compro']);
                        $('#fecha_envio_doc').val(data.pedidos[0]['fecha_envio_doc']);

                    }
                }).done(function (data) {
                });

                //recupera imagenes adjuntas
                $.ajax({
                    url: "{{ route('operaciones.veratencion',':id') }}".replace(':id', idunico),
                    data: idunico,
                    method: 'POST',
                    success: function (data) {
                        console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idunico)
                        $('#listado_adjuntos_ver').html("");
                        $('#listado_adjuntos_antes_ver').html(data);
                        console.log(data);
                    }
                });

            });

            $('#modal-veradjunto').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('adjunto')
                var idcodigo = button.data('codigo')
                $(".textcode").html(idcodigo);

                //consulta de imagenes
                $.ajax({
                    type: 'POST',
                    url: "{{ route('pedidoobteneradjuntoRequest') }}",
                    data: {"pedido": idunico},
                }).done(function (data) {
                    //console.log(data.html);
                    console.log(data.cantidad);
                    if (data.cantidad > 0) {
                        ////recorrer y poner imagenes en div con router
                        var adjuntos = data.html.split('|');
                        //console.log(adjuntos);
                        var urladjunto = "";
                        var datal = "";
                        $.each(adjuntos, function (index, value) {
                            urladjunto = '{{ route("pedidos.descargaradjunto", ":id") }}';
                            urladjunto = urladjunto.replace(':id', value);
                            datal = datal + '<p><a href="' + urladjunto + '"><i class="fa fa-file mr-2"></i>' + value + '</a><p>';
                            //console.log(datal);
                            //console.log( index + ": " + value );
                        });
                        $("#imagenes_adjunto").html(datal)
                        return datal;
                        //console.log(data.html)
                    } else {
                        console.log("sin imagenes");
                    }
                });

            });

            $('#modal-envio-op').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('envio')
                $(".textcode").html("PED" + idunico);
                $("#hiddenEnvio").val(idunico);

            });

            $('#modal-correccion-op').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('correccion')
                var confirmo_descarga = button.data('adj')

                $(".textcode").html("PED" + idunico);
                $("#correccion").val(idunico);
                $('#conf_descarga').val(confirmo_descarga);

                /*if (confirmo_descarga == 1) {*/
                    $("#sustento_data").val("");
                    $('#sustento_adjunto').css({'display': 'block'});
                /*} else {
                    $('#sustento_adjunto').css({'display': 'none'});
                }*/
                $.ajax({
                    type: 'POST',
                    url: "{{ route('operaciones.datossubidaadj',':id') }}".replace(':id', idunico),
                    data: idunico,
                    success: function (data) {
                        //console.log(data);
                        console.log(data.pedidos[0]['cant_compro']+" traje como cantidad  hacia el input");

                        $('#cant_compro').val(data.pedidos[0]['cant_compro']);
                        $('#fecha_envio_doc').val(data.pedidos[0]['fecha_envio_doc']);

                    }
                }).done(function (data) {
                });
                $.ajax({
                    url: "{{ route('operaciones.editatencion',':id') }}".replace(':id', idunico),
                    data: idunico,
                    method: 'POST',
                    success: function (data) {
                        //console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idunico)
                        $('#listado_adjuntos').html("");
                        $('#listado_adjuntos_antes').html(data);
                        //console.log(data);
                    }
                });

            });

            $(document).on("click", "#cargar_adjunto", function (evento) {
                let idunico = $("#correccion").val();
                console.log(idunico);
                $('#cargar_adjunto').attr("disabled", true);
                $('#cargar_adjunto').html('Subiendo archivos...');
                //e.preventDefault();
                let cant_compro = $("#cant_compro").val();
                if (cant_compro == '') $("#cant_compro").val(0);
                var data = new FormData(document.getElementById("formulariocorreccionatender"));
                $("#loading_upload_attachment_file").show()
                $("#adjunto").hide()
                $.ajax({
                    type: 'POST',
                    url: "{{ route('operaciones.updateatendersinconfirmar',':id') }}".replace(':id', idunico),
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#cargar_adjunto').prop("disabled", false);
                        $('#cargar_adjunto').text('Subir Informacion');
                        //console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idunico)
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

            $(document).on("change", "#adjunto", function (evento) {
                $("#cargar_adjunto").trigger("click");
            });

            $(document).on("submit", "#formulariocorreccionatender", function (evento) {
                evento.preventDefault();
                console.log("correccion atender")

                let cnf_adjunto = $("#conf_descarga").val();

                var cant_compro = document.getElementById('cant_compro').value;
                var cant_compro_attachment = document.getElementById('adjunto_total_attachment');//adjuntos en el servidor

                if (!cant_compro_attachment) {
                    cant_compro_attachment = 0
                } else {
                    cant_compro_attachment = parseInt(cant_compro_attachment.value);
                    if (isNaN(cant_compro_attachment)) {
                        cant_compro_attachment = 0;
                    }
                }
                if (cant_compro_attachment == 0) {
                    Swal.fire(
                        'Error',
                        'No hay archivos adjuntados',
                        'warning'
                    )
                    return false;
                }

                if (!cant_compro) {
                    cant_compro = 0;
                }
                cant_compro = parseInt(cant_compro);

                if (isNaN(cant_compro)) {
                    cant_compro = 0;
                }
                if (cant_compro == 0) {
                    Swal.fire(
                        'Error',
                        'Debe colocar la cantidad de archivos',
                        'warning'
                    )
                    return false;
                }

                if (cnf_adjunto == 1) {
                    var sustento = $('#sustento_data').val();

                    if (!sustento) {
                        Swal.fire(
                            'Error',
                            'Ingrese un sustento para continuar',
                            'warning'
                        )
                        return false;
                    } else if (sustento.length < 50) {
                        Swal.fire(
                            'Error',
                            'Debe ingresar al menos 50 caracteres ('+sustento.length+'/50)',
                            'warning'
                        )
                        return false;
                    }
                }

                function submitForm() {
                    var data =   new FormData( $("#formulariocorreccionatender")[0]);
                    data.delete('adjunto')
                    data.delete('adjunto[]')
                    $.ajax({
                        data: data,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        url: "{{ route('operaciones.correccionajax') }}",
                        success: function (data) {
                            console.log(data);
                            if (data.html!='') {
                                var urlpdf = '{{ route('pedidosPDF', ':id') }}';
                                urlpdf = urlpdf.replace(':id', data.html);
                                window.open(urlpdf, '_blank');

                                //$("#modal-copiar .textcode").text(data.html);

                                //$("#modal-copiar").modal("show");
                                $("#modal-correccion-op .textcode").text('');
                                $("#modal-correccion-op").modal("hide");
                                $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
                            }else{
                                console.log("vacio")
                            }


                        }
                    });
                }
                console.log("cant_compro_attachment "+cant_compro_attachment)
                console.log("cant_compro "+cant_compro)
                let c_conf=cant_compro_attachment+cant_compro;

                if (cant_compro != cant_compro_attachment) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Aviso',
                        html: `La cantidad de archivos es (${c_conf}) y es diferente a la cantidad de facturas (${cant_compro})<br><b>¿Desea continuar?</b>`,
                        confirmButtonText: 'Aceptar y continuar',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm()
                        } else if (result.isDenied) {
                        }
                    })
                } else {
                    submitForm()
                }
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
                        $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
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
                        $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
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
                        $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
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
                        $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
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
                        $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
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
                        $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
                    }
                });
            });

            $('#tablaOperacionesBandejadeSobres').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('operaciones.terminadostabla') }}",
                    data: function (d) {
                        //d.asesores = $("#asesores_pago").val();
                        d.min = $("#min").val();
                        d.max = $("#max").val();

                    },
                },
                createdRow: function (row, data, dataIndex) {
                    //console.log(row);
                    if (data["estado"] == "1") {
                        if (data.pendiente_anulacion == 1) {
                            $('td', row).css('background', 'red').css('font-weight', 'bold');
                        }
                    } else if (data["estado"] == "0"){
                        $(row).addClass('textred');
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
                        render: function (data, type, row, meta) {
                            if (row.id < 10) {
                                return 'PED000' + row.id;
                            } else if (row.id < 100) {
                                return 'PED00' + row.id;
                            } else if (row.id < 1000) {
                                return 'PED0' + row.id;
                            } else {
                                return 'PED' + row.id;
                            }
                        }
                    },
                    {data: 'codigos', name: 'codigos',},
                    {data: 'empresas', name: 'empresas',},
                    {data: 'users', name: 'users',},
                    {
                        data: 'fecha',
                        name: 'fecha',
                        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss'),
                        "visible": true,
                    },
                    {data: 'destino', name: 'destino', "visible": false},
                    {
                        data: 'condicion_envio',
                        name: 'condicion_envio',
                    },
                    {
                        data: 'imagenes',
                        name: 'imagenes',
                        orderable: false,
                        searchable: false,
                        sWidth: '10%',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return '';
                            } else {
                                if (data > 0) {
                                    data = '<a href="" data-target="#modal-veradjunto" data-adjunto=' + row.id + ' data-codigo=' + row.codigos + ' data-toggle="modal" ><button class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                                    return data;
                                } else {
                                    return '';
                                }
                            }

                        }
                    },
                    {data: 'atendido_por', name: 'atendido_por',},
                    {data: 'jefe', name: 'jefe',},
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

            $('#tablaOperacionesBandejadeSobres_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });
            $(document).on("keypress", '#tablaOperacionesBandejadeSobres_filter label input', function () {
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
          $('#tablaOperacionesBandejadeSobres').DataTable().draw();
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
                    $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
                    //localStorage.setItem('dateMin', $(this).val() );
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });

            $("#max").datepicker({
                onSelect: function () {
                    $('#tablaOperacionesBandejadeSobres').DataTable().ajax.reload();
                    //localStorage.setItem('dateMax', $(this).val() );
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });

            //$("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"});
            //$("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy" });
            //var table = $('#tablaOperacionesBandejadeSobres').DataTable();

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
