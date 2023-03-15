@extends('adminlte::page')

@section('title', 'Envios | Sobres sin enviar')

@section('content_header')
    <h1>DISTRIBUCION DE SOBRES</h1>
@stop

@section('content')
    @php
        $color_zones=[];
        $color_zones['NORTE']='warning';
        $color_zones['CENTRO']='info';
        $color_zones['SUR']='dark';
    @endphp
    <div class="row">
        @foreach($motorizados as $motorizado)
            <div class="col-4 container-{{Str::slug($motorizado->zona)}}">
                <div class="table-responsive">
                    <div class="card card-{{$color_zones[Str::upper($motorizado->zona)]??'info'}}">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5> {{Str::upper($motorizado->zona)}}
                                    <sup><span
                                            class="badge badge-light count_distribuirsobres_{{Str::lower($motorizado->zona)}}">0</span></sup>
                                </h5>

                                <div>
                                    <button type="button" class="btn btn-light buttom-agrupar"
                                            data-zona="{{Str::upper($motorizado->zona)}}"
                                            data-table-save="#tablaPrincipal{{Str::upper($motorizado->zona)}}"
                                            data-ajax-action="{{route('envios.distribuirsobres.agrupar',['visualizar'=>1,'motorizado_id'=>$motorizado->id,'zona'=>Str::upper($motorizado->zona)])}}">
                                        <span class="spinner-border spinner-border-sm"
                                              role="status" aria-hidden="true" style="display: none"></span>
                                        <span class="sr-only" style="display: none"></span>
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                        <b>Crear Paquetes</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-1">
                            <div>
                                <table id="tablaPrincipal{{Str::upper($motorizado->zona)}}"
                                       class="table table-striped font-12">
                                    <thead>
                                    <tr>
                                        <th scope="col">Códigos</th>
                                        <th scope="col">Zona</th>
                                        <th scope="col">Distrito</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


    </div>

    <div class="card" style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
            <div class="table-responsive">
                <table id="tablaCourierSobresconDireccion" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col" class="align-middle" style="font-size:14px;">Sobres</th>
                        <th scope="col" class="align-middle" style="font-size:14px;">Razón social</th>
                        <th scope="col" class="align-middle">Quien recibe</th>
                        <th scope="col" class="align-middle">TELEFONO</th>
                        <th scope="col" class="align-middle">PROV</th>
                        <th scope="col" class="align-middle">DISTRITO</th>
                        <th scope="col" class="align-middle">DIRECCION</th>
                        <th scope="col" class="align-middle">REFERENCIA</th>
                        <th scope="col" class="align-middle">FECHA</th>
                        <th scope="col" class="align-middle">Estado de envio</th>
                        <th scope="col" class="align-middle">ZONA</th>
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
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/v4-shims.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/solid.min.css')}}">
    <style>
        .cod_dir {
            font-size: 11px;
        }

        .cod_dir_w {
            min-width: 200px;
        }

        .cod_ped {
            font-size: 11px;
            min-width: 100px;
        }

        .bg-zone {
            background: #dbffdf;
        }

        .jconfirm-content {
            overflow: hidden !important;
        }
        #tablaCourierSobresconDireccion{
            width: 100% !important;
        }
        #tablaCourierSobresconDireccion td{
            vertical-align: middle !important;
            text-align: start !important;
        }
    </style>
    <!-- css del time line del historial de motirizado -->
    @include('partials.css.time_line_css')
@endpush

@push('js')

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var insertIds = []

        function createZoneRowTable(data, zona) {
            console.log(data, zona)
            return {
                ...data,
                codigos: data.codigos,
                zona: data.zona,
                zona_asignada: zona,
                distrito: data.distrito,
                action: `<div class="d-flex">
                            <button type="button" data-jqdetalle="${data.id}" class="btn btn-light buttom-agrupar d-flex align-items-center justify-content-center font-12">
                                <i class="fa fa-layer-group mr-1"></i>Detalle
                            </button>
                            <button type="button" data-revertir="${data.id}" class="btn btn-light buttom-agrupar d-flex align-items-center justify-content-center font-12">
                                <i class="fa fa-undo-alt mr-1 text-danger"></i> Revertir
                            </button>
                        </div>`,
            }
        }

        $(document).ready(function () {

            $('#tablaCourierSobresconDireccion').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                /*search: {
                    regex: true
                },*/
                ajax: {
                    url: "{{ route('envios.distribuirsobrestabla') }}",
                    data: function (query) {
                        query.exclude_ids = insertIds
                    }
                },
                createdRow: function (row, data, dataIndex) {
                    //console.log(row);

                },
                rowCallback: function (row, data, index) {
                    const self = this
                    $("[data-elTable]", row).click(function () {
                        $("#tablaCourierSobresconDireccion [data-elTable]").attr('disabled', 'disabled')
                        $(this).find('.spinner-border').show()
                        $(this).find('.sr-only').show()

                        var tableId = $(this).data('eltable');
                        var zona = $(this).data('zona');
                        insertIds.push(data.id)

                        $(tableId).DataTable()
                            .row.add(createZoneRowTable(data, zona)).draw(false);

                        localStorage.setItem(zona + '.envios.distribuirsobres', JSON.stringify(Array.from($("#tablaPrincipal" + zona).DataTable().data())));

                        self.api().ajax.reload();
                    })
                    $('[data-motorizado-history]', row).click(function () {
                        $.confirm({
                            title: 'Historial de adjuntos de llamadas',
                            theme: 'material',
                            type: 'dark',
                            columnClass: 'xlarge',
                            content: function () {
                                return `
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
                                        <hr class="my-2">
                                        <h4><b>Sustento</b></h4>
                                        <p class="text-wrap text-break"> ${h.sustento_text}</p>
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
                            }
                        })
                    })
                },
                columns: [
                    {data: 'codigos', name: 'codigos', searchable: true, sWidth: '10%', sClass: 'cod_dir'},
                    {data: 'productos', name: 'productos', searchable: true, sClass: 'cod_dir cod_dir_w'},
                    {data: 'cliente_recibe', name: 'cliente_recibe',},
                    {data: 'telefono', name: 'telefono',},
                    {data: 'provincia', name: 'provincia',},
                    {data: 'distrito', name: 'distrito',},
                    {data: 'direccion', name: 'direccion',},
                    {data: 'referencia', name: 'referencia',},
                    {data: 'fecha_producto', name: 'fecha_producto', sClass: 'cod_dir'},
                    {data: 'condicion_envio', name: 'condicion_envio',},
                    {data: 'zona', name: 'zona', sClass: 'bg-zone'},
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

            const configDataTableZonas = {
                /*processing: false,
                stateSave: true,
                serverSide: false,
                searching: true,
                bLengthMenu: false,
                bInfo: false,*/
                lengthChange: false,
                order: [[0, "desc"]],
                createdRow: function (row, data, dataIndex) {
                },
                columns: [
                    {data: 'codigos', name: 'codigos',},
                    {data: 'zona', name: 'zona',},
                    {data: 'distrito', name: 'distrito',},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '20%'
                    },
                ],
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
            @foreach($motorizados as $motorizado)
            $('#tablaPrincipal{{Str::upper($motorizado->zona)}}').DataTable({
                ...configDataTableZonas,
                rowCallback: function (row, data, index) {
                    var table = this;
                    $('[data-revertir]', row).unbind();
                    $('[data-jqdetalle]', row).unbind();

                    $('[data-revertir]', row).click(function () {
                        insertIds = insertIds.filter(function (id) {
                            return id != data.id;
                        })
                        $('#tablaCourierSobresconDireccion').DataTable().ajax.reload();
                        table.api().row(row).remove().draw(false)
                    })
                    $('[data-jqdetalle]', row).click(function () {
                        console.log(data)
                        $.confirm({
                            title: '¡Detalle del grupo!',
                            columnClass: 'xlarge',
                            content: getHtmlPrevisualizarDesagrupar(data),
                            theme: 'material',
                            typeAnimated: true,
                            buttons: {
                                cancelar: function () {
                                    $('#tablaCourierSobresconDireccion').DataTable().ajax.reload();
                                    return true
                                }
                            },
                            onContentReady: function () {
                                const self = this

                                function setEvents() {
                                    self.$content.find('[data-jqdesagrupar]').click(function (e) {
                                        $.ajax({
                                            url: '{{route('envios.distribuirsobres.desagrupar')}}',
                                            data: {
                                                grupo_id: e.target.dataset.jqdesagrupar,
                                                pedido_id: e.target.dataset.pedido_id,
                                            },
                                            method: 'delete'
                                        })
                                            .done(function (grupo) {
                                                $('#tablaPrincipal{{Str::upper($motorizado->zona)}}')
                                                    .DataTable()
                                                    .row(row)
                                                    .data(createZoneRowTable(grupo.data, '{{Str::upper($motorizado->zona)}}'))
                                                    .draw();
                                                if (grupo.data) {
                                                    self.setContent(getHtmlPrevisualizarDesagrupar(grupo.data))
                                                } else {
                                                    self.close()
                                                    $.alert('Desagrupado por completo')
                                                }
                                                setEvents()
                                            })
                                            .always(function () {
                                                $('#tablaCourierSobresconDireccion').DataTable().ajax.reload();
                                            })
                                    })
                                }

                                setEvents();
                            }
                        })
                    })
                },
                "fnDrawCallback": function () {
                    $(".count_distribuirsobres_{{Str::lower($motorizado->zona)}}").html(this.fnSettings().fnRecordsDisplay());
                }
            });

            @endforeach

            function getHtmlPrevisualizarDesagrupar(row, success) {
                return `
<div class="card">
    <div class="card-header">
        <h4>Cliente: <strong>${row.cliente_recibe}</strong> - <i>${row.telefono}</i></h4>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">
                            <b>Codigo</b>
                        </div>
                        <div class="col-4">
                            <b>Razon Social</b>
                        </div>
                        <div class="col-4 text-center">
                            <b>Acciones</b>
                        </div>
                    </div>
                </li>
            ${row.pedidos.map(function (pedido) {
                    return `
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">
                            ${pedido.pivot.codigo}
                        </div>
                        <div class="col-4">
                            ${pedido.pivot.razon_social}
                        </div>
                        <div class="col-4 text-center">
                            ${(row.pedidos.length > 1 && pedido.env_zona != 'OLVA') ? `<button class="btn btn-danger" data-jqdesagrupar="${row.id}" data-pedido_id="${pedido.id}"><i class="fa fa-arrow-down"></i> Desagrupar</button>` : '<button class="btn btn-danger" disabled><i class="fa fa-arrow-down"></i> Desagrupar</button>'}
                        </div>
                    </div>
                </li>`
                }).join('')}
            </ul>
        </div>
    </div>
</div>`;
            }

            function getHtmlPrevisualizarAgruparData(rows, success) {
                var html = rows.map(function (row) {
                    const ps = row.producto.split(',')
                    return `
                                        <tr>
                                        <td>${row.celular || ''}</td>
                                        <td>
                                        ${row.codigos.split(',').map(function (codigo, index) {
                        return `<b>${codigo}</b>`
                    }).join(`<hr class="my-2">`)}
                                        </td>
                                        <td>
                                        ${row.codigos.split(',').map(function (codigo, index) {
                        return `<i>${ps[index] || ''}</i>`
                    }).join(`<hr class="my-2">`)}
                                        </td>
                                        <td>
                                        ${row.distrito || ''}
                                        </td>
                                        </tr>`
                })
                return `<div class="row">
<table class="table">
                                        <tr>
                                        <th class="bg-light">Teléfono</th>
                                        <th class="bg-light">Codigo</th>
                                        <th class="bg-light">Razon social</th>
                                        <th class="bg-light">Distrito</th>
                                        </tr>
${html.join('')}
</table>

</div>`;
            }

            function getHtmlPrevisualizarPaqueteData(rows, success) {
                var html = rows.map(function (row) {
                    const ps = row.producto.split(',')
                    const productos = [`<li class="list-group-item">
                                    <div class="row">
                                        <div class="col-4 border-right">
                                        <strong>${row.nombre || ''}</strong> - <i>${row.celular || ''}</i>
                                        </div>
                                        <div class="col-4 border-right">
                                        <b>${row.distribucion || ''}</b><hr class="my-2"> ${row.distrito || ''}, ${row.direccion || ''} <hr class="my-2"><i> ${row.referencia || ''}</i>
                                        </div>
                                        <div class="col-4">
                                    ${row.codigos.split(',').map(function (codigo, index) {
                        return `<b>${codigo}</b> - <i>${ps[index] || ''}</i>`
                    }).join(`<hr class="my-2">`)}
                                        </div>
                                    </div>
                                </li>`]
                    // ${success?`<div class="col-12 alert alert-success">Grupos creados correctamente</div>`:``}
                    return `<div class="col-md-12">
<div class="card border card-dark">
<div class="card-header">
${success ? `Paquete: <strong>${row.correlativo || ''}</strong>` : `Cliente: <strong>${row.nombre || ''}</strong> - <i>${row.celular || ''}</i>`}
</div>
<div class="card-body">
<ul class="list-group">
    <li class="list-group-item">
        <div class="row">
            <div class="col-4 border-right text-center">
            <b>Cliente</b>
            </div>
            <div class="col-4 border-right text-center">
            <b>Dirección</b>
            </div>
            <div class="col-4 text-center">
            <b>Productos</b>
            </div>
        </div>
    </li>
    ${productos.join('<hr>')}
</ul>
</div>
</div>
</div>`
                })
                return `<div class="row">${html.join('')}</div>`;
            }

            $(".buttom-agrupar[data-table-save]").click(function () {
                const buttom = $(this)
                const link = buttom.attr('data-ajax-action')
                const tableId = buttom.attr('data-table-save')
                const zona = buttom.attr('data-zona')
                const table = $(tableId).DataTable();
                const grupos = Array.from(table.data()).map(function (item) {
                    return item.id
                })
                if (grupos.length === 0) {
                    return;
                }
                console.log(grupos)
                $.confirm({
                    title: '¡Confirmar creación de paquetes!',
                    columnClass: 'xlarge',
                    content: function () {
                        const self = this
                        self.$$goSobres.hide();
                        console.log("iniciar")
                        //return '¿Estas seguro de crear el paquete con los sobres listados en la zona <b>' + zona + '</b>?'
                        return $.ajax({
                            url: link,
                            data: {
                                groups: Array.from(table.data()).map(function (item) {
                                    return item.id
                                })
                            },
                            dataType: 'json',
                            method: 'post'
                        })
                            .done(function (response) {
                                self.setContent(getHtmlPrevisualizarAgruparData(response))
                            })
                    },
                    theme: 'material',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        ok: {
                            text: 'Aceptar y crear paquetes',
                            btnClass: 'btn-success',
                            action: function () {
                                console.log("iniciar en ajax")
                                buttom.find('.spinner-border').show()
                                buttom.find('.sr-only').show()
                                const self = this
                                console.log(self)
                                self.showLoading(true)
                                $.ajax({
                                    url: link.replace('visualizar=1', '').replace('visualizar', '_agrupar').replace('?&', '?'),
                                    data: {
                                        /*groups: Array.from(table.data()).map(function (item) {
                                            return item.id
                                        })*/
                                        groups: grupos
                                    },
                                    dataType: 'json',
                                    method: 'post'
                                })
                                    .done(function (response) {
                                        self.close();
                                        /* self.setTitle('<h3 class="text-success font-24">Paquetes creados exitosamente</h3>');
                                         self.setContent(getHtmlPrevisualizarPaqueteData(response, true))
                                         self.$$ok.hide();
                                         self.$$goSobres.show();
                                         self.$$cancelar.text("Cerrar");*/
                                    })
                                    .always(function () {
                                        self.hideLoading(true)
                                        //self.close()
                                        buttom.find('.spinner-border').hide()
                                        buttom.find('.sr-only').hide()

                                        $('#tablaCourierSobresconDireccion').DataTable().ajax.reload();
                                        table.clear()
                                            .draw();
                                    })
                                return false
                            }
                        },
                        goSobres: {
                            text: 'Visualizar en sobres para reparto',
                            btnClass: 'btn-success',
                            action: function () {
                                window.open('{{route('envios.parareparto')}}', '_blank')
                                return true
                            }
                        },
                        cancelar: function () {
                            return true
                        }
                    }
                });
            })

            const motorizados = {{\Illuminate\Support\Js::from($motorizados)}};
            motorizados.forEach(function (motorizado) {
                const zona = motorizado.zona.toUpperCase()
                const localdata = localStorage.getItem(zona + '.envios.distribuirsobres');
                if (localdata) {
                    var currentDate;
                    try {
                        currentDate = JSON.parse(localdata)
                    } catch (e) {
                        currentDate = [];
                    }
                    insertIds = currentDate.map(function (data) {
                        return data.id
                    })

                    const table = $('#tablaCourierSobresconDireccion' + zona).DataTable();
                    currentDate.forEach(function (data) {
                        table.row.add(createZoneRowTable(data, zona))
                    })
                    table.draw(false);
                }
            })

            function closeIt() {
                motorizados.forEach(function (motorizado) {
                    const zona = motorizado.zona.toUpperCase()
                    const table = $('#tablaPrincipal' + zona).DataTable();
                    localStorage.setItem(zona + '.envios.distribuirsobres', JSON.stringify(Array.from(table.data())));
                })
            }

            window.onbeforeunload = closeIt;
            $('#tablaCourierSobresconDireccion').DataTable().draw(false);
        });
    </script>

@endpush
