@extends('adminlte::page')

@section('title', 'Lista de pedidos por confirmar')

@section('content_header')
    <div class="border-bottom pb-16 w-100">
        <div class="d-flex justify-content-between align-content-center">
            <div>
                <h1 class="font-20 font-weight-bold">Sobres Devueltos</h1>
            </div>
            <div>
                <x-common-button-qr-scanner
                    module-title="Sobres Devueltos"
                    responsable="fernandez_devuelto"
                    accion="sobres_devuelto"
                    tipo="pedido"
                    :tables-ids="collect($motorizados)->map(fn ($motorizado)=> '#tablaPrincipal'.Str::upper($motorizado->zona))->all()"
                ></x-common-button-qr-scanner>
            </div>
        </div>
    </div>

@stop

@section('content')
    <style>

        #placeholder-qr {
            animation: qr 1.5s ease-in-out infinite;
        }

        @keyframes qr {
            0% {
                transform: translate(-50%, -50%) scale(0.7);
            }
            50% {
                transform: translate(-50%, -50%) scale(1);
            }
            100% {
                transform: translate(-50%, -50%) scale(0.7);
            }

        }

        #btn-qr {
            margin-right: 16px;
            position: fixed;
            bottom: 16px;
            left: 50%;
            width: 300px;
            background-color: #3498db !important;
            color: white;
            text-shadow: 1px 2px 3px #00000063;
            transform: translate(-50%, 0px);
            border-radius: 12px;
            z-index: 999;
        }

        .activo {
            background-color: #e74c3c !important;
            color: white !important;
            border: 0 !important;
        }

        .content-wrapper {
            background-color: white;
        }

        .card {
            box-shadow: 0 0 white;
        }
    </style>
    <div class="card w-100 pb-48">
        <div class="card-body p-0">

            <div class="container-full">
                <div class="row">
                    @foreach($motorizados as $motorizado)
                        <div class="col-lg-4 container-{{Str::slug($motorizado->zona)}}">
                            <div class="table-responsive">
                                <div class="card card-{{$color_zones[Str::upper($motorizado->zona)]??'info'}}">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h5> MOTORIZADO {{Str::upper($motorizado->zona)}}</h5>
                                            <div>
                                                <h6>Sobres devueltos/observados: <span>{{$motorizado->devueltos}}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-1">
                                        <div>
                                            <table id="tablaPrincipal{{Str::upper($motorizado->zona)}}"
                                                   class="table font-12">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Códigos</th>

                                                    <th scope="col">Distrito</th>
                                                    <th scope="col">Fecha Ruta</th>
                                                    <th scope="col">Detalle</th>
                                                    <th scope="col">Ver</th>
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
            </div>
        </div>
    </div>

    <script>
        if (location.protocol != 'https:') {
            document.getElementById('secure-connection-message').style = 'display: block';
        }
    </script>

@stop

@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/css/admin_custom.css">


    <style>
        .table_custom.toolbar {
            float: left;
        }

        .qr_success {
            animation: qr_success 1s ease-in forwards;
        }


        @keyframes qr_success {
            0% {
                box-shadow: 1px 1px 0px green;
            }

            70% {
                box-shadow: 1px 1px 24px green;
            }

            100% {

                box-shadow: 1px 1px 0px green;

            }
        }

        .qrPreviewVideo {
            width: 100%;
            width: 100%;
            border-radius: 16px;
            margin: auto;
        }

        img:hover {
            transform: scale(1.2)
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
@stop

@section('js')
    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const configDataTableZonas = {
                serverSide: true,
                searching: true,
                lengthChange: false,
                order: [[3, 'desc']],
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    var table = this.api()
                    $('td', row).css('background', data.situacion_color);
                    $('[data-toggle=jqconfirm]', row).click(function () {
                        const action = $(this).data('target')
                        $.confirm({
                            theme:'material',
                            type: 'orange',
                            title: 'Confirmar recepción',
                            content: `Esta seguro de confirmar la recepción del Pedido <b>${data.codigo}</b>`,
                            buttons: {
                                confirmar: {
                                    btnClass: 'btn-warning',
                                    action: function () {
                                        const self = this
                                        self.showLoading(true)
                                        $.post(action, {}).done(function () {

                                        })
                                            .always(function () {
                                                self.hideLoading(true)
                                                table.draw(false)
                                            })
                                    }
                                },
                                cancelar: {}
                            }
                        })
                    })
                    $('[data-toggle=jqconfirmfoto]', row).click(function () {
                        const action = $(this).data('target')
                        $.confirm({
                            theme:'material',
                            type: 'orange',
                            title: 'Foto de NO CONTESTO',
                            columnClass: 'large',
                            content: `<img src="${action}" class="w-100">`,
                            buttons: {
                                ok: {}
                            }
                        })
                    })
                    $('[data-toggle=jqconfirmtext]', row).click(function () {
                        const action = $(this).data('target')
                        console.log($(this), action)
                        $.confirm({
                            theme:'material',
                            columnClass: 'large',
                            type: 'orange',
                            title: 'Sustento de OBSERVADO',
                            content: `Sustento: <b>${action}</b>`,
                            buttons: {
                                cerrar: {}
                            }
                        })
                    })
                    if (data.motorizado_status == {{\App\Models\Pedido::ESTADO_MOTORIZADO_OBSERVADO}}) {
                        $('[data-toggle=jqconfirmmotorizado]', row).click(function () {
                            const target=$(this).data('target')
                            $.confirm({
                                theme:'material',
                                title: '¿Estas seguro de enviar a motorizado?',
                                columnClass: 'large',
                                content: `<div class="alert alert-warning">
                                   <span> El Pedido <b>${data.codigo}</b> sera enviado a motorizado con la fecha actual para que pueda adjuntar las fotos</span>
                                    </div>`,
                                buttons: {
                                    cancelar: {},
                                    aceptar: {
                                        btnClass: 'btn-info',
                                        action: function () {
                                            const self = this
                                            self.showLoading(true)
                                            $.post(target).done(function (data) {
                                                self.close()
                                            }).always(function () {
                                                self.showLoading(false)
                                                $(row).parents('table').DataTable().draw(false)
                                            })
                                        }
                                    }
                                }
                            })
                        })
                    }
                },
                columns: [
                    {data: 'codigo', name: 'codigo',},
                    //{data: 'env_zona', name: 'env_zona',},
                    {
                        data: 'env_distrito',
                        name: 'env_distrito',
                        render: function (data, type, row, meta) {

                            return row.env_distrito + "<br>" + "<b>" + row.env_zona + "</b>";

                        },
                    },
                    {data: 'grupo_fecha_salida', name: 'fecha_salida', sWidth: '12%'},
                    {
                        data: 'detalle',
                        name: 'detalle',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'Ver',
                        name: 'Ver',
                        orderable: false,
                        searchable: false,
                    },
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
                ajax: "{!! route('envios.datasobresdevueltos',['datatable'=>'1','motorizado_id'=>$motorizado->id,'zona'=>Str::upper($motorizado->zona)]) !!}",
            });
            @endforeach

        });
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
    @if ($pedidos_observados_count->count()>0)
        <script>
            Swal.fire(
                'Alerta',
                'El Motorizado {{$pedidos_observados_count->join(', ')}} sobres por entregar, por favor brindar solucion urgente, ya sea con el motorizado o corregirlo en el sistema (si fuera el caso) con el administrador',
                'danger'
            )
        </script>
    @endif
@stop
