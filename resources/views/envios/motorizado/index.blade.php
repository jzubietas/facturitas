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
    @include('envios.motorizado.modal.entregar_recojo')
    @include('envios.motorizado.modal.recojo_enviarcourier')
    @include('envios.motorizado.modal.recojo_enviarope')

    <div class="card p-0">

        <table cellspacing="5" cellpadding="5" class="table-responsive">
            <tbody>
            <tr>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold font-12" id="inputGroup-sizing-default">Fecha de Ruta:</span>
                        </div>
                        <input type="date" value="{{$fecha_consulta->format('Y-m-d')}}" id="fecha_consulta"
                               name="fecha_consulta" class="form-control" autocomplete="off">
                    </div>
                </td>
                <td>
                    <input id="buscador_global" name="buscador_global" value=""
                           type="text" class="form-control" autocomplete="off"
                           placeholder="Ingrese su búsqueda" aria-label="Recipient's username"
                           aria-describedby="basic-addon2">
                </td>
            </tr>
            </tbody>
        </table>

        <ul class="nav nav-pills nav-justified nav-tabs mb-24 mt-24" id="myTab" role="tablist">
            <li class="nav-item text-center">
                <a class="condicion-tabla nav-link activo active font-weight-bold"
                   id="enmotorizado-tab"
                   data-toggle="tab"
                   data-url="11"
                   data-action="enmotorizado"
                   data-consulta="tablaEnmotorizado"
                   href="#enmotorizado"
                   role="tab"
                   aria-controls="enmotorizado"
                   aria-selected="true">
                    <i class="fa fa-inbox" aria-hidden="true"></i> EN MOTORIZADO
                    <sup><span class="badge badge-light count_motorizados_enmotorizado">0</span></sup>
                </a>
            </li>
            <li class="nav-item text-center">
                <a class="condicion-tabla nav-link font-weight-bold"
                   id="entregado-tab"
                   data-toggle="tab"
                   data-url="11"
                   data-action="entregado"
                   data-consulta="tablaEntregado"
                   href="#entregado"
                   role="tab"
                   aria-controls="entregado"
                   aria-selected="true">
                    <i class="fa fa-inbox" aria-hidden="true"></i> ENTREGADO
                    <sup><span class="badge badge-light count_motorizados_entregado">0</span></sup>
                </a>
            </li>
            <li class="nav-item text-center">
                <a class="condicion-tabla nav-link font-weight-bold"
                   id="nocontesto-tab"
                   data-toggle="tab"
                   data-url="11"
                   data-action="nocontesto"
                   data-consulta="tablaNocontesto"
                   href="#nocontesto"
                   role="tab"
                   aria-controls="nocontesto"
                   aria-selected="true">
                    <i class="fa fa-inbox" aria-hidden="true"></i> NO CONTESTO
                    <sup><span class="badge badge-light count_motorizados_nocontesto">0</span></sup>
                </a>
            </li>
            <li class="nav-item text-center">
                <a class="condicion-tabla nav-link font-weight-bold"
                   id="observado-tab"
                   data-toggle="tab"
                   data-url="11"
                   data-action="observado"
                   data-consulta="tablaObservado"
                   href="#observado"
                   role="tab"
                   aria-controls="observado"
                   aria-selected="true">
                    <i class="fa fa-inbox" aria-hidden="true"></i> OBSERVADO
                    <sup><span class="badge badge-light count_motorizados_observado">0</span></sup>
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="enmotorizado" role="tabpanel" aria-labelledby="enmotorizado-tab">
                <table id="tablaEnmotorizado" class="table table-striped w-100">
                    <thead>
                    <tr>
                        <th scope="col" class="align-middle">Item</th>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Distrito</th>
                        <th scope="col" class="align-middle">Destino</th>
                        <th scope="col" class="align-middle">Cliente</th>
                        <th scope="col" class="align-middle">Fecha de Salida</th>
                        <th scope="col" class="align-middle">Fecha de Entrega</th>
                        <th scope="col" class="align-middle">Razón social</th>
                        <th scope="col" class="align-middle">Google Maps</th>
                        <th scope="col" class="align-middle">Dirección de envío</th>
                        <th scope="col" class="align-middle">Referencia</th>
                        <th scope="col" class="align-middle">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                        <th scope="col" class="align-middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade show" id="entregado" role="tabpanel" aria-labelledby="entregado-tab">
                <table id="tablaEntregado" class="table table-striped w-100">
                    <thead>
                    <tr>
                        <th scope="col" class="align-middle">Item</th>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Distrito</th>
                        <th scope="col" class="align-middle">Asesor</th>
                        <th scope="col" class="align-middle">Cliente</th>
                        <th scope="col" class="align-middle">Fecha de Salida</th>
                        <th scope="col" class="align-middle">Fecha de Entrega</th>
                        <th scope="col" class="align-middle">Razón social</th>
                        <th scope="col" class="align-middle">Destino</th>
                        <th scope="col" class="align-middle">Dirección de envío</th>
                        <th scope="col" class="align-middle">Referencia</th>
                        <th scope="col" class="align-middle">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                        <th scope="col" class="align-middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade show" id="nocontesto" role="tabpanel" aria-labelledby="nocontesto-tab">
                <table id="tablaNocontesto" class="table table-striped w-100">
                    <thead>
                    <tr>
                        <th scope="col" class="align-middle">Item</th>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Distrito</th>
                        <th scope="col" class="align-middle">Asesor</th>
                        <th scope="col" class="align-middle">Cliente</th>
                        <th scope="col" class="align-middle">Fecha de Salida</th>
                        <th scope="col" class="align-middle">Fecha de Entrega</th>
                        <th scope="col" class="align-middle">Razón social</th>
                        <th scope="col" class="align-middle">Destino</th>
                        <th scope="col" class="align-middle">Dirección de envío</th>
                        <th scope="col" class="align-middle">Referencia</th>
                        <th scope="col" class="align-middle">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                        <th scope="col" class="align-middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade show" id="observado" role="tabpanel" aria-labelledby="observado-tab">
                <table id="tablaObservado" class="table table-striped w-100">
                    <thead>
                    <tr>
                        <th scope="col" class="align-middle">Item</th>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Distrito</th>
                        <th scope="col" class="align-middle">Asesor</th>
                        <th scope="col" class="align-middle">Cliente</th>
                        <th scope="col" class="align-middle">Fecha de Salida</th>
                        <th scope="col" class="align-middle">Razón social</th>
                        <th scope="col" class="align-middle">Destino</th>
                        <th scope="col" class="align-middle">Dirección de envío</th>
                        <th scope="col" class="align-middle">Referencia</th>
                        <th scope="col" class="align-middle">Observacion Sustento</th>
                        <th scope="col" class="align-middle">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                        <th scope="col" class="align-middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>


    </div>

@stop

@push('css')

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
        let datatableenmotorizado = null;
        let datatableentregado = null;
        let datatablenocontesto = null;
        let datatableobservado = null;

        $('.condicion-tabla').on('click', function () {
            console.log("aaaa");
            var tabla_load = $(this).data('consulta');
            console.log("tabla_load " + tabla_load)
            $('.condicion-tabla').removeClass("activo");
            $(this).addClass("activo");
            if (!$.fn.DataTable.isDataTable('#' + tabla_load)) {
                $('#' + tabla_load).dataTable();
            }
        });

        $(document).ready(function () {

            function applySearch(e) {
                console.log(e)
                let valor = $("#buscador_global").val();
                valor = (valor || '').trim()
                datatableenmotorizado.search(valor).draw();
                datatableentregado.search(valor).draw();
                datatablenocontesto.search(valor).draw();
                datatableobservado.search(valor).draw();
            }

            $('#buscador_global').change(applySearch);
            $('#buscador_global').keydown(applySearch);
            $('#fecha_consulta').change(applySearch);
            $('#fecha_consulta').keydown(applySearch);

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
                $('[data-jqconfirmcancel]', row).unbind();
                $('[data-jqconfirm=general]', row).unbind();
                $('[data-jqconfirm=observado]', row).unbind();
                $('[data-jqconfirm=no_contesto]', row).unbind();
                $('[data-motorizado-history]', row).unbind();
                $('[data-jqconfirm=revertir]', row).unbind();

                $('[data-jqconfirmcancel]', row).click(function () {

                    $.confirm({
                        theme: 'material',
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
                                        $('.table').DataTable().ajax.reload();
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
                                  <h5>Sobre de pedido:</h5>
                                  <div class="d-flex" style=" flex-wrap: wrap; column-gap: 2rem; ">${data.codigos.split('<br>').join('')}</div>
                              </div>
                          </div>
                        <div class="row mt-2">
                            <div class="col-12 col-md-4">
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
                            <div class="col-12 col-md-4">
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
                            <div class="col-12 col-md-4">
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
                                        <img id="picture1"
                                            src="{{ asset('imagenes/motorizado_preview/sobres.png') }}"
                                            data-src="{{ asset('imagenes/motorizado_preview/sobres.png') }}"
                                alt="Imagen del pago" class="img-fluid w-100" style="display: block;" width="300" height="300">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mt-12">
                        <div class="form-group">
                            <div class="image-wrapper">
                                <img id="picture2"
                                src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                                data-src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                                     alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mt-12">
                        <div class="form-group">
                            <div class="image-wrapper">
                                <img id="picture3"
                                src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                data-src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                     alt="Imagen del pago" class="img-fluid w-100" style="display: block" width="300" height="300">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                                    <h6>Observacion (Opcional):</h6>
                                    <textarea class="form-control mb-20" rowspan="3" id="observacion" name="observacion" placeholder="Si tiene una observación, ingresela aquí"></textarea>
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
                                self.$content.find("#picture1").attr('src', self.$content.find("#picture1").data('src'))
                                self.$content.find("#adjunto1").val(null)
                            })
                            self.$content.find("#trash_adjunto2").click(function (e) {
                                self.$content.find("#picture2").attr('src', self.$content.find("#picture2").data('src'))
                                self.$content.find("#adjunto2").val(null)
                            })
                            1

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
                                        theme: 'material',
                                        title: '¡Advertencia!',
                                        content: '<b>Adjunta la Foto de los sobres</b>',
                                        type: 'orange'
                                    })
                                    return false;
                                }
                                if (e.target.adjunto2.files.length === 0) {
                                    $.confirm({
                                        theme: 'material',
                                        title: '¡Advertencia!',
                                        content: '<b>Adjunta la Foto del domicilio</b>',
                                        type: 'orange'
                                    })
                                    return false;
                                }
                                if (e.target.adjunto3.files.length === 0) {
                                    $.confirm({
                                        theme: 'material',
                                        title: '¡Advertencia!',
                                        content: '<b>Adjunta la Foto de qun recibe</b>',
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
                                    $('#tablaEnmotorizado').DataTable().ajax.reload();
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
                                        theme: 'material',
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
                                    $('.table').DataTable().ajax.reload(null, false);
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
                                        theme: 'material',
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
                                    $('.table').DataTable().ajax.reload(null, false);
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
                        theme: 'material',
                        title: 'Historial de adjuntos de llamadas',
                        type: 'dark',
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
                        theme: 'material',
                        type: 'red',
                        title: `Confirmación`,
                        content: `¿Estas seguro de revertir el paquete <b>ENV${data.id}</b><br> a <b>RECEPCION - MOTORIZADO</b>?`,
                        buttons: {
                            revertir: {
                                btnClass: 'btn-red',
                                action: function () {
                                    const self = this
                                    self.showLoading(true)
                                    $.post(action, {})
                                        .done(function (data) {
                                            self.close()
                                        })
                                        .always(function () {
                                            self.hideLoading(true)
                                            $('.table').DataTable().draw(false)
                                        })
                                }
                            },
                            cancelar: {}
                        }
                    })
                })

                $('[data-jqconfirm="reprogramar"]', row).click(function () {
                    const action = $(this).data('jqconfirm-action')
                    $.confirm({
                        theme: 'material',
                        title: 'Reprogramar fecha de envio',
                        type: 'orange',
                        columnClass: 'large',
                        content: `
<div class="p-0">
<form>
<div class="form-group">
<input disabled="disabled" class="form-control" type="date" name="fecha_salida" value="${moment().add(1, 'day').format('YYYY-MM-DD')}">
</div>
<div class="form-group">
<label>Adjuntar captura de pantalla</label>
<input class="form-control" type="file" name="adjunto">
</div>

<div class="w-100">
<img src="" class="h-100 image-preview-file" style="max-width: 100%">
</div>

</form>
</div>
`,
                        buttons: {
                            reprogramar: {
                                btnClass: 'btn-info',
                                action: function () {
                                    const self = this;
                                    const form = self.$content.find('form')[0];
                                    const now = moment().startOf('day');
                                    const now3day = moment().add(1, 'day');
                                    const date = moment(form.fecha_salida.value)
                                    if (date < now) {
                                        $.alert('No puedes seleccionar una fecha menor a la actual, seleccione otra fecha porfavor', 'Alerta')
                                        return false
                                    }
                                    if (date > now3day) {
                                        $.alert('No puedes seleccionar una fecha mayor al dia siguiente de la actual, seleccione otra fecha porfavor', 'Alerta')
                                        return false
                                    }

                                    if (form.adjunto.files.length === 0) {
                                        $.alert('Es necesario adjuntar una captura de pantalla', 'Alerta')
                                        return false
                                    }
                                    const fd = new FormData()
                                    fd.append('fecha_salida', form.fecha_salida.value)
                                    fd.append('adjunto', form.adjunto.files[0], form.adjunto.files[0].name)
                                    self.showLoading(true)
                                    $.ajax({
                                        url: action,
                                        processData: false,
                                        contentType: false,
                                        data: fd,
                                        method: 'POST'
                                    })
                                        .done(function () {
                                            self.close()
                                            $(row).parents('table').DataTable().draw(false)
                                        })
                                        .always(function () {
                                            self.hideLoading(true)
                                        })
                                    return false
                                }
                            },
                            cancel: {}
                        },
                        onContentReady: function () {
                            const self = this;
                            const form = self.$content.find('form')[0];
                            $(form.adjunto).change(function (e) {
                                $("img.image-preview-file").attr('src', URL.createObjectURL(e.target.files[0]))
                            })

                        }
                    })
                })
            }

            datatableenmotorizado = $('#tablaEnmotorizado').DataTable({
                dom: '<"top"i>rt<"bottom"lp><"clear">',
                lengthChange: false,
                processing: true,
                stateSave: false,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                    data: function (q) {
                        q.fechaconsulta = $("#fecha_consulta").val();
                        q.tab = 'enmotorizado'
                    }
                },
                initComplete: function () {

                },
                createdRow: function (row, data, dataIndex) {
                    if (data["condicion_envio_code"] == 31) {
                        $(row).addClass('yellow_color_table');
                    } else if (data["condicion_envio_code"] == 32) {
                        $(row).addClass('blue_color_table');
                    }
                },
                drawCallback: function (settings) {
                    console.log(settings.json);
                    $("#tablaPrincipal").DataTable().columns().header()[12].innerText = $('a[data-toggle="tab"].active').data('action-name')
                },
                rowCallback: function (row, data, index) {
                    renderButtomsDataTable(row, data)
                    if (data.cambio_direccion_at != null) {
                        $('td', row).css('background', 'rgba(17,129,255,0.35)')
                    }
                },
                columns: [
                    {
                        data: 'correlativo',
                        name: 'correlativo',
                    },
                    {
                        data: 'codigos',
                        name: 'codigos',
                    },
                    {data: 'distrito', name: 'distrito',},
                    {data: 'destino', name: 'destino',},
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            return '<a href="tel:' + row.celular + '">' + row.celular + '</a><br>' + row.nombre
                        },
                    },
                    {
                        data: 'fecha_salida',
                        name: 'fecha_salida',
                    },
                    {
                        data: 'fecha_recepcion',
                        name: 'fecha_recepcion',
                    },
                    {
                        data: 'producto',
                        name: 'producto',
                    },
                    {data: 'gmlink', name: 'gmlink',},
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
                        sWidth: '10%'
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
                "fnDrawCallback": function () {
                    $('.count_motorizados_enmotorizado').html(this.fnSettings().fnRecordsDisplay());
                }
            });
            datatableentregado = $('#tablaEntregado').DataTable({
                dom: '<"top"i>rt<"bottom"lp><"clear">',
                lengthChange: false,
                processing: true,
                stateSave: false,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                    data: function (q) {
                        q.fechaconsulta = $("#fecha_consulta").val();
                        q.tab = 'entregado'
                    }
                },
                initComplete: function () {

                },
                createdRow: function (row, data, dataIndex) {

                },
                drawCallback: function (settings) {
                    console.log(settings.json);
                    $("#tablaPrincipal").DataTable().columns().header()[12].innerText = $('a[data-toggle="tab"].active').data('action-name')
                },
                rowCallback: function (row, data, index) {
                    renderButtomsDataTable(row, data)
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
                    },
                    {
                        data: 'fecha_recepcion',
                        name: 'fecha_recepcion',
                    },
                    {
                        data: 'producto',
                        name: 'producto',
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
                "fnDrawCallback": function () {
                    $('.count_motorizados_entregado').html(this.fnSettings().fnRecordsDisplay());
                }
            });
            datatablenocontesto = $('#tablaNocontesto').DataTable({
                dom: '<"top"i>rt<"bottom"lp><"clear">',
                lengthChange: false,
                processing: true,
                stateSave: false,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                    data: function (q) {
                        q.fechaconsulta = $("#fecha_consulta").val();
                        q.tab = 'nocontesto'
                    }
                },
                initComplete: function () {

                },
                createdRow: function (row, data, dataIndex) {

                },
                drawCallback: function (settings) {
                    console.log(settings.json);
                    $("#tablaPrincipal").DataTable().columns().header()[12].innerText = $('a[data-toggle="tab"].active').data('action-name')
                },
                rowCallback: function (row, data, index) {
                    renderButtomsDataTable(row, data)
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
                    },
                    {
                        data: 'fecha_recepcion',
                        name: 'fecha_recepcion',
                    },
                    {
                        data: 'producto',
                        name: 'producto',
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
                "fnDrawCallback": function () {
                    $('.count_motorizados_nocontesto').html(this.fnSettings().fnRecordsDisplay());
                }
            });
            datatableobservado = $('#tablaObservado').DataTable({
                dom: '<"top"i>rt<"bottom"lp><"clear">',
                lengthChange: false,
                processing: true,
                stateSave: false,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                    data: function (q) {
                        q.fechaconsulta = $("#fecha_consulta").val();
                        q.tab = 'observado'
                    }
                },
                initComplete: function () {

                },
                createdRow: function (row, data, dataIndex) {

                },
                drawCallback: function (settings) {
                    console.log(settings.json);
                    $("#tablaPrincipal").DataTable().columns().header()[12].innerText = $('a[data-toggle="tab"].active').data('action-name')
                },
                rowCallback: function (row, data, index) {
                    renderButtomsDataTable(row, data)
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
                    },
                    {
                        data: 'producto',
                        name: 'producto',
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
                    {
                        data: 'motorizado_sustento_text',
                        name: 'motorizado_sustento_text',
                        render: function (data) {
                            return `<hr class="my-2"><p class="text-wrap text-break"><i>${data}</i></p>`
                        }
                    },
                    {data: 'condicion_envio', name: 'condicion_envio', className: 'ancho_condicion_envio_global'},
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
                "fnDrawCallback": function () {
                    $('.count_motorizados_observado').html(this.fnSettings().fnRecordsDisplay());

                    let a1 = $('#tablaEnmotorizado').dataTable().fnSettings().fnRecordsDisplay();
                    let a2 = $('#tablaEntregado').dataTable().fnSettings().fnRecordsDisplay();
                    let a3 = $('#tablaNocontesto').dataTable().fnSettings().fnRecordsDisplay();
                    let a4 = this.fnSettings().fnRecordsDisplay();

                    if (a1 > 0) {
                        $('#myTab a[href="#enmotorizado"]').tab('show')
                    } else if (a2 > 0) {
                        $('#myTab a[href="#entregado"]').tab('show')
                    } else if (a3 > 0) {
                        $('#myTab a[href="#nocontesto"]').tab('show')
                    } else if (a4 > 0) {
                        $('#myTab a[href="#observado"]').tab('show')
                    }
                }
            });

            datatableenmotorizado.on('responsive-display', function (e, datatable, row, showHide, update) {
                console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });
            datatableentregado.on('responsive-display', function (e, datatable, row, showHide, update) {
                console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });
            datatablenocontesto.on('responsive-display', function (e, datatable, row, showHide, update) {
                console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });
            datatableobservado.on('responsive-display', function (e, datatable, row, showHide, update) {
                console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });

            $(document).on("submit", "#form_recojo_motorizado", function (evento) {
                evento.preventDefault();

                //validacion

                var fd2 = new FormData();
                //let files = $('input[name="pimagen')
                //var fileitem = $("#DPitem").val();

                fd2.append('entrega_motorizado_recojo', $('#input_recojomotorizado').val());
                //fd2.append('fecha_envio_doc_fis', $('#fecha_envio_doc_fis').val());
                //fd2.append('fecha_recepcion', $('#fecha_recepcion').val());
                fd2.append('foto1', $('input[type=file][id="pimagen1_recojo"]')[0].files[0]);
                fd2.append('foto2', $('input[type=file][id="pimagen2_recojo"]')[0].files[0]);
                fd2.append('foto3', $('input[type=file][id="pimagen3_recojo"]')[0].files[0]);
                //fd2.append('condicion', $('#condicion').val());

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('motorizado.recojo') }}",
                    success: function (data) {
                        $("#modal_recojomotorizado").modal("hide");
                        $('#tablaEnmotorizado').DataTable().ajax.reload();

                    }
                });

            });

            $(document).on("change", "#foto1", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture1").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });

            $(document).on("change", "#foto2", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture2").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });

            $(document).on("change", "#foto3", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture3").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });

            $('#modal_recojomotorizado').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#input_recojomotorizado").val(button.data('direccion_grupo'));
                //limpiar campos para cargar nuevas fotos
                $("#pimagen1_recojo").val("");
                $("#pimagen2_recojo").val("");
                $("#pimagen3_recojo").val("");
                $("#picture1_recojo").attr('src', '');
                $("#picture2_recojo").attr('src', '');
                $("#picture3_recojo").attr('src', '');
            });

            $('#modal_recojoenviarcourier').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#input_recojoenviarcourier").val(button.data('direccion_grupo'));

                let foto1 = button.data('imagen1');
                let foto2 = button.data('imagen2');
                let foto3 = button.data('imagen3');
                $(".foto1").attr("src", foto1);
                $(".foto2").attr("src", foto2);
                $(".foto3").attr("src", foto3);
            });

            $('#modal_recojoenviarope').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#input_recojoenviarope").val(button.data('direccion_grupo'));

                let foto1 = button.data('imagen1');
                let foto2 = button.data('imagen2');
                let foto3 = button.data('imagen3');
                $(".foto1").attr("src", foto1);
                $(".foto2").attr("src", foto2);
                $(".foto3").attr("src", foto3);
            });

            $(document).on("change", "#pimagen1_recojo", function (event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picture1_recojo").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $(document).on("change", "#pimagen2_recojo", function (event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picture2_recojo").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $(document).on("change", "#pimagen3_recojo", function (event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picture3_recojo").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $(document).on("click", "#trash_adjunto1", function (e) {
                $("#picture1_recojo").attr('src', $("#picture1_recojo").data('src'))
                $("#pimagen1_recojo").val(null)
            })

            $(document).on("click", "#trash_adjunto2", function (e) {
                $("#picture2_recojo").attr('src', $("#picture2_recojo").data('src'))
                $("#pimagen2_recojo").val(null)
            })

            $(document).on("click", "#trash_adjunto3", function (e) {
                $("#picture3_recojo").attr('src', $("#picture3_recojo").data('src'))
                $("#pimagen3_recojo").val(null)
            })

            $(document).on("submit", "#form_recojo_enviarcourier", function (evento) {
                evento.preventDefault();
                var drecojoenviarcourier = new FormData();
                drecojoenviarcourier.append('input_recojoenviarcourier', $('#input_recojoenviarcourier').val());
                $.ajax({
                    data: drecojoenviarcourier,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('motorizado.recojoenviarcourier') }}",
                    success: function (data) {
                        $("#modal_recojoenviarcourier").modal("hide");
                        $('#tablaEnmotorizado').DataTable().ajax.reload();
                    }
                });

            });


            $(document).on("submit", "#form_recojo_enviarope", function (evento) {
                evento.preventDefault();
                var data = new FormData();
                data.append('input_recojoenviarope', $('#input_recojoenviarope').val());

                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('courier.recojoenviarope') }}",
                    success: function (data) {
                        $("#modal_recojoenviarope").modal("hide");
                        $('#tablaEnmotorizado').DataTable().ajax.reload();
                    }
                });

            });

        });
    </script>

@stop
