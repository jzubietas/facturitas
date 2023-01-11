@extends('adminlte::page')

@section('title', 'Motorizado')

@section('content_header')
    <h1 class="text-center">
        <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i> Motorizado
    </h1>
    @if(now()>now()->startOfDay()->addHours(16)->addMinutes(30) && now()<now()->startOfDay()->addHours(17))
        <div class="alert alert-warning">
            LOS SOBRES NO ENTREGADOS A TIEMPO AFECTARAN SU PORCENTAJE DE ENTREGA
        </div>
    @endif
@stop

@section('content')

    @include('envios.motorizado.modal.entregado')

    <div class="card p-48">

        <table cellspacing="5" cellpadding="5" class="table-responsive">
            <tbody>
            <tr>

                <td><p class="font-20 font-weight-bold">Buscar por fecha de salida:</p>
                    <input type="date" value="{{$fecha_consulta->format('Y-m-d')}}" id="fecha_consulta" name="fecha_consulta" class="form-control" autocomplete="off"></td>
                <td></td>


            </tr>
            </tbody>
        </table><br>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab"
                   data-action-name="Acciones"
                   aria-controls="general" aria-selected="true" data-action="general">
                    EN MOTORIZADO
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="entregado-tab" data-toggle="tab" href="#entregado" role="tab"
                   data-action-name="Acciones"
                   aria-controls="entregado" aria-selected="false" data-action="entregado">
                    ENTREGADO
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="no_contesto-tab" data-toggle="tab" href="#no_contesto" role="tab"
                   data-action-name="Acciones"
                   aria-controls="no_contesto" aria-selected="false" data-action="no_contesto">
                    NO CONTESTO
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="observado-tab" data-toggle="tab" href="#observado" role="tab"
                   data-action-name="Acciones/Sustento"
                   aria-controls="observado" aria-selected="false" data-action="observado">
                    OBSERVADOS
                </a>
            </li>
        </ul>
        <div class="card-body px-1">
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped dt-responsive w-100">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    <th scope="col">Distrito</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Fecha de Salida</th>
                    <th scope="col">Fecha de Entrega</th>
                    <th scope="col">Razón social</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Dirección de envío</th>
                    <th scope="col">Referencia</th>
                    <th scope="col">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@stop

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    <style>
        @media (max-width: 32rem) {
            div.dataTables_wrapper div.dataTables_filter input {
                width: 200px !important;
            }

            .content-wrapper {
                background-color: white !important;
            }

            .card {
                box-shadow: 0 0 1px white !important;
            }
        }
    </style>
    @include('partials.css.time_line_css')
@endpush

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

    <script>
        $(document).ready(function () {

            $("#fecha_consulta").on('change', function(){
                //var fecha_formateada = $(this).val().replaceAll('-', '/');
                var fecha_format = $(this).val().split("-")
                var fecha_formateada = fecha_format[2] + "/" + fecha_format[1] + "/" + fecha_format[0];
                $(this).data('fecha',fecha_formateada);
                console.log(fecha_formateada);
                $('#tablaPrincipal').DataTable().ajax.reload();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const datatable = $('#tablaPrincipal').DataTable({
                responsive: {
                    details: {
                        renderer: $.fn.dataTable.Responsive.renderer.listHiddenNodes()
                    }
                },
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                    data: function (q) {
                        //q.fechaconsulta = $("#fecha_consulta").val();
                        q.fechaconsulta = $("#fecha_consulta").data("fecha");
                        q.tab = $('a[data-toggle="tab"].active').data('action')
                    }
                },
                initComplete: function () {

                },
                createdRow: function (row, data, dataIndex) {

                },
                "drawCallback": function(settings) {
                    console.log(settings.json);
                    $("#tablaPrincipal").DataTable().columns().header()[11].innerText = $('a[data-toggle="tab"].active').data('action-name')
                },
                rowCallback: function (row, data, index) {

                    if (data.destino == 'PROVINCIA') {
                        $('td', row).css('color', '#20c997')
                    }
                    if (data.estado == 0) {
                        $('td', row).css('color', 'red')
                    }

                    $('[data-jqconfirmcancel]', row).click(function () {

                        $.confirm({
                            type: 'red',
                            title: '¡Revertir Envio!',
                            content: 'Confirme si desea revertir el envio <b>' + data.codigos + '</b>',
                            buttons: {
                                ok: {
                                    text: 'Si, confirmar',
                                    btnClass: 'btn-red',
                                    action: function () {
                                        const self = this;
                                        self.showLoading(true)
                                        $.ajax({
                                            data: {
                                                envio_id: data.id
                                            },
                                            //operaciones.confirmar.revertir
                                            type: 'POST',
                                            url: "{{ route('operaciones.confirmar.revertir') }}",
                                        }).always(function () {
                                            self.close()
                                            self.hideLoading(true)
                                            $('#tablaPrincipal').DataTable().ajax.reload();
                                        });
                                    }
                                },
                                cancel: {
                                    text: 'No'
                                }
                            }
                        })
                    });
                    $('[data-jqconfirm=general]', row).click(function () {

                        $.dialog({
                            title: '<h3 class="font-weight-bold">Entregas de motorizado</h3>',
                            type: 'green',
                            columnClass: 'xlarge',
                            content: `<div>
    <form enctype="multipart/form-data" class="card">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5>Sobre de pedido: <b>${data.codigos}</b></h5>
                </div>
            </div>
            {{--
            <div class="row">
                <div class="col-lg-6">
                   <div class="form-group">
                     <label for="fecha_envio_doc_fis">Fecha de Envio</label>
                     <input class="form-control" id="fecha_envio_doc_fis" disabled="" name="fecha_envio_doc_fis" type="date" value="${(data.fecha_salida||'').split(' ')[0]}">
                    </div>
                </div>
                <div class="col-lg-6">
                   <div class="form-group">
                        <label for="fecha_recepcion">Fecha de Entrega</label>
                        <input class="form-control" id="fecha_recepcion" value="<?php echo date('Y-m-d'); ?>"  name="fecha_recepcion" type="date" value="">
                    </div>
                </div>
            </div>
            --}}
            <div class="row mt-2">
                <div class="col-4">
                    <div class="input-group w-80">
                        <div class="custom-file w-90">
                            <input type="file" class="custom-file-input form-control-file" id="adjunto1" name="adjunto1" lang="es">
                            <label class="custom-file-label" for="adjunto1">Foto de los sobres</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-danger" id="trash_adjunto1" type="button"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group w-80">
                        <div class="custom-file w-90">
                            <input type="file" class="custom-file-input form-control-file" id="adjunto2" name="adjunto2" lang="es">
                            <label class="custom-file-label" for="adjunto2">Foto del domicilio</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-danger" id="trash_adjunto2" type="button"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group w-80">
                        <div class="custom-file w-90">
                            <input type="file" class="custom-file-input form-control-file" id="adjunto3" name="adjunto3" lang="es">
                            <label class="custom-file-label" for="adjunto3">Foto de quien recibe</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-danger" id="trash_adjunto3" type="button"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 mt-12">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture1" src="{{ asset('imagenes/sobres.jpg') }}"
                                alt="Imagen del pago" class="w-80 mh-90 h-90 img-fluid" style="display: block;">
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-12">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture2" src="{{ asset('imagenes/domicilio.jpg') }}"
                                 alt="Imagen del pago" class="w-80 mh-90 h-90 img-fluid" style="display: block">
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-12">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture3" src="{{ asset('imagenes/recibe_sobre.jpg') }}"
                                 alt="Imagen del pago" class="w-80 mh-90 h-90 img-fluid" style="display: block">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
        </div>
    </form>
</div>`,
                            onContentReady: function () {
                                const self = this
                                self.$content.find("#adjunto1").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture1").show();
                                        self.$content.find("#picture1").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#adjunto2").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture2").show();
                                        self.$content.find("#picture2").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#adjunto3").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture3").show();
                                        self.$content.find("#picture3").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#trash_adjunto1").click(function (e) {
                                    self.$content.find("#picture1").attr('src', "{{ asset('imagenes/sobres.jpg') }}")
                                })
                                self.$content.find("#trash_adjunto2").click(function (e) {
                                    self.$content.find("#picture2").attr('src', "{{ asset('imagenes/domicilio.jpg') }}")
                                })
                                self.$content.find("#trash_adjunto3").click(function (e) {
                                    self.$content.find("#picture3").attr('src', "{{ asset('imagenes/recibe_sobre.jpg') }}")
                                })

                                self.$content.find("form").on('submit', function (e) {
                                    e.preventDefault()
                                    /*if (!e.target.fecha_recepcion.value) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Ingresa la fecha de Entrega</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }*/
                                    if (e.target.adjunto1.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la Foto de los sobres</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto2.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la Foto del domicilio</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto3.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la Foto de quien recibe</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    var fd2 = new FormData(e.target);
                                    fd2.set('envio_id', data.id)
                                    self.showLoading(true)
                                    $.ajax({
                                        data: fd2,
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        url: "{{ route('operaciones.confirmarmotorizado') }}"
                                    }).done(function () {
                                        self.close()
                                        $('#tablaPrincipal').DataTable().ajax.reload();
                                    }).always(function () {
                                        self.hideLoading(true)
                                    });
                                })
                            },
                        });
                    })
                    $('[data-jqconfirm=observado]', row).click(function () {

                        $.dialog({
                            title: '<h3 class="font-weight-bold">Marcar como observado</h3>',
                            type: 'green',
                            columnClass: 'large',
                            content: `<div>
    <form enctype="multipart/form-data" class="card">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5>Paquete de pedido: <b>${data.codigos}</b></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                   <div class="form-group">
                         <label for="sustento_text">Ingrese su Sustento</label>
                     <textarea class="form-control" id="sustento_text" name="sustento_text" required placeholder="Ingrese su Sustento" rows="7"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-info" id="atender">
<i class="fa fa-save"></i>
Enviar</button>
        </div>
    </form>
</div>`,
                            onContentReady: function () {
                                const self = this
                                self.$content.find("form").on('submit', function (e) {
                                    e.preventDefault()
                                    if (!e.target.sustento_text.value) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Ingresa el sustento para continuar</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    self.showLoading(true)
                                    $.ajax({
                                        data: {
                                            grupo_id: data.id,
                                            sustento_text: e.target.sustento_text.value
                                        },
                                        type: 'POST',
                                        url: "{{ route('operaciones.confirmarmotorizado',['action'=>'update_status_observado']) }}"
                                    }).done(function () {
                                        self.close()
                                        $('#tablaPrincipal').DataTable().ajax.reload(null, false);
                                    }).always(function () {
                                        self.hideLoading(true)
                                    });
                                })
                            },
                        });
                    })
                    $('[data-jqconfirm=no_contesto]', row).click(function () {
                        $.dialog({
                            title: '<h3 class="font-weight-bold">Marcar como "NO CONTESTO"</h3>',
                            type: 'green',
                            columnClass: 'xlarge',
                            content: `<div>
    <form enctype="multipart/form-data" class="card">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5>Paquete de pedido: <b>${data.codigos}</b></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                   <div class="form-group">
                         <label for="sustento_foto">Adjuntar foto de las llamadas que realizo</label>
                     <input type="file" class="form-control" id="sustento_foto"  name="sustento_foto" required >
                    </div>
                    <div class="alert alert-warning">Adjunte una imagen que demuestre como minimo 5 llamadas hacia el cliente</div>
                </div>
                <div class="col-lg-12" style="display: none" id="sustento_foto_img_content">
                   <div class="card">
<div class="card-body">
<img src="" id="sustento_foto_img" class="w-50">
</div>
</div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-info" id="atender">
<i class="fa fa-save"></i>
Enviar</button>
        </div>
    </form>
</div>`,
                            onContentReady: function () {
                                const self = this
                                self.$content.find("#sustento_foto").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#sustento_foto_img_content").show();
                                        self.$content.find("#sustento_foto_img").attr('src', URL.createObjectURL(file))
                                    }
                                })

                                self.$content.find("form").on('submit', function (e) {
                                    e.preventDefault()
                                    if (e.target.sustento_foto.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la foto de llamadas realizadas</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    var fd2 = new FormData(e.target);
                                    fd2.set('grupo_id', data.id)
                                    self.showLoading(true)
                                    $.ajax({
                                        data: fd2,
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        url: "{{ route('operaciones.confirmarmotorizado',['action'=>'update_status_no_contesto']) }}"
                                    }).done(function () {
                                        self.close()
                                        $('#tablaPrincipal').DataTable().ajax.reload(null, false);
                                    }).always(function () {
                                        self.hideLoading(true)
                                    });
                                })
                            },
                        });
                    })
                    $('[data-motorizado-history]', row).click(function () {
                        const action = $(this).data('jqconfirm-action')
                        $.confirm({
                            title: 'Historial de adjuntos de llamadas',
                            type: 'info',
                            columnClass: 'xlarge',
                            content: function () {
                                const self = this
                                return $.get(action).done(function (response) {
                                    const data = {
                                        motorizado_histories: response
                                    }
                                    const html = `
                               <section class="timeline_area section_padding_130">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Timeline Area-->
                <div class="apland-timeline-area">
                    ${data.motorizado_histories.map(function (h) {
                                        return `
                    <!-- Single Timeline Content-->
                    <div class="single-timeline-area">
                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                            <p>${h.created_at_format}</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-9 col-lg-6">
                                <div class="single-timeline-content wow fadeInLeft position-relative" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon position-absolute" style="top: -12px;left: -9px;">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </div>

                                    <div class="timeline-text w-100">
                                        <img src="${h.sustento_foto_link}" class="w-100">
                                        ${h.sustento_text ? `<hr class="my-2">
                                        <h4><b>Sustento</b></h4>
                                        <p class="text-wrap text-break"> ${h.sustento_text || ''}</p>` : ''}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>`
                                    })}
                </div>
            </div>
        </div>
    </div>
</section>
                                `;
                                    self.setContent(html)
                                });
                            }
                        })
                    })

                    $('[data-jqconfirm=revertir]', row).click(function () {
                        const action = $(this).data('jqconfirm-action')
                        $.confirm({
                            type: 'red',
                            title: `Confirmación`,
                            content: `¿Estas seguro de revertir el paquete <b>ENV${data.id}</b><br> a <b>RECEPCION - MOTORIZADO</b>?`,
                            buttons: {
                                revertir: {
                                    btnClass: 'btn-red',
                                    action: function () {
                                        const self=this
                                        self.showLoading(true)
                                        $.post(action, {

                                        })
                                            .done(function (data) {
                                                self.close()
                                            })
                                            .always(function () {
                                                self.hideLoading(true)
                                                $('#tablaPrincipal').DataTable().draw(false)
                                            })
                                    }
                                },
                                cancelar: {}
                            }
                        })
                    })
                },
                columns: [
                    {
                        data: 'correlativo',
                        name: 'correlativo',
                    },
                    {
                        data: 'codigos',
                        name: 'codigos',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return 'SIN PEDIDOS';
                            } else {
                                var returndata = '';
                                var jsonArray = data.split(",");
                                $.each(jsonArray, function (i, item) {
                                    returndata += item + '<br>';
                                });
                                return returndata;
                            }
                        },
                    },
                    {data: 'distrito', name: 'distrito',},
                    {data: 'identificador', name: 'identificador',},
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            return row.celular + '<br>' + row.nombre
                        },
                    },
                    {
                        data: 'fecha_salida',
                        name: 'fecha_salida',
                        //render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },
                    {
                        data: 'fecha_recepcion',
                        name: 'fecha_recepcion',
                        //render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },
                    {
                        data: 'producto',
                        name: 'producto',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return 'SIN RUCS';
                            } else {
                                var numm = 0;
                                var returndata = '';
                                var jsonArray = data.split(",");
                                $.each(jsonArray, function (i, item) {
                                    numm++;
                                    returndata += numm + ": " + item + '<br>';

                                });
                                return returndata;
                            }
                        }
                    },
                    {data: 'destino', name: 'destino',},
                    {
                        data: 'direccion',
                        name: 'direccion',
                        render: function (data, type, row, meta) {
                            if (data != null) {
                                return data;
                            } else {
                                return '<span class="badge badge-info">REGISTRE DIRECCION</span>';
                            }
                        },
                    },
                    {
                        data: 'referencia',
                        name: 'referencia',
                        sWidth: '10%',
                        render: function (data, type, row, meta) {
                            var datal = "";
                            if (row.destino == 'LIMA') {
                                return data;
                            } else if (row.destino == 'PROVINCIA') {
                                var urladjunto = '{{ route("pedidos.descargargastos", ":id") }}'.replace(':id', data);
                                datal = datal + '<p><a href="' + urladjunto + '">' + data + '</a><p>';
                                return datal;
                            }
                        }
                    },
                    {data: 'condicion_envio', name: 'condicion_envio',},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '10%',
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

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                console.log(
                    'target: ',
                    e.target, // newly activated tab
                    e.relatedTarget, // previous active tab
                )
                $('#tablaPrincipal').DataTable().draw(false);
            })
        });
    </script>

@stop
