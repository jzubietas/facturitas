@extends('adminlte::page')

@section('title', 'Rutas de Envio')

@section('content_header')
@include('envios.modal.exportar', ['title' => 'Exportar RUTAS DE ENVIAR', 'key' => '1'])

    @if($superasesor > 0)
        <br>
        <div class="bg-4">
            <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
                asesores con privilegios superiores: {{ $superasesor }}
            </h1>
        </div>
    @endif
<div class="d-flex justify-content-end">
    <a href="{{route('envios.rutaenvio.merge-routulos')}}" target="_blank" class="btn btn-info">
        <i class="fa fa-download"></i>
        Descargar Rotulos
    </a>
</div>
@stop

@section('content')

    <div class="card p-48">

        <table cellspacing="5" cellpadding="5" class="table-responsive">
            <tbody>
            <tr>

                <td><p class="font-20 font-weight-bold">Buscar por fecha de salida:</p>
                    <input type="date" value="{{$fecha_consulta->format('Y-m-d')}}" id="fecha_consulta"
                           name="fecha_consulta" class="form-control" autocomplete="off"></td>
                <td></td>


            </tr>
            </tbody>
        </table>
        <br>

        <div class="row">
            @foreach($motorizados as $motorizado)
                <div class="col-lg-4 container-{{Str::slug($motorizado->zona)}}">
                    <div class="table-responsive">
                        <div class="card card-{{$color_zones[Str::upper($motorizado->zona)]??'success'}}">
                            <div class="card-header pt-8 pb-8">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0 font-16"> MOTORIZADO {{Str::upper($motorizado->zona)}}</h5>
                                    <div>
                                        <!--
                                        <h6 class="mb-0">Sobres devueltos/observados: <span>{{$motorizado->devueltos}}</span>
                                        </h6>-->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-1">
                                <div>

                                    <ul  class="nav nav-tabs" style="font-size:11px !important;" id="myTab{{Str::slug($motorizado->zona)}}"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="general-tab" data-zona="{{Str::slug($motorizado->zona)}}" data-toggle="tab"
                                               href="#general" role="tab"
                                               data-tab="motorizado"
                                               aria-controls="general" aria-selected="true" data-action="general">
                                                EN MOTORIZADO
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="entregado-tab" data-zona="{{Str::slug($motorizado->zona)}}" data-toggle="tab" href="#entregado"
                                               role="tab"
                                               data-tab="entregado"
                                               aria-controls="entregado" aria-selected="false" data-action="entregado">
                                                ENTREGADO
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="no_contesto-tab" data-zona="{{Str::slug($motorizado->zona)}}" data-toggle="tab"
                                               href="#no_contesto" role="tab"
                                               data-tab="no_contesto"
                                               aria-controls="no_contesto" aria-selected="false"
                                               data-action="no_contesto">
                                                NO CONTESTO
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="observado-tab" data-toggle="tab" href="#observado"
                                               role="tab"
                                               data-tab="observado"
                                               aria-controls="observado" aria-selected="false" data-action="observado">
                                                OBSERVADOS
                                            </a>
                                        </li>
                                    </ul>

                                    <table id="tablaPrincipal{{Str::upper($motorizado->zona)}}"
                                           class="tabla-data table table-striped dt-responsive w-100">
                                        <thead>
                                        <tr>
                                            <!--<th scope="col">Item</th>-->
                                            <th scope="col">Código</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Zona</th>
                                            <th scope="col">Distrito</th>
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

            $("#fecha_consulta").on('change', function () {
                //var fecha_formateada = $(this).val().replaceAll('-', '/');
                var fecha_format = $(this).val().split("-")
                var fecha_formateada = fecha_format[2] + "/" + fecha_format[1] + "/" + fecha_format[0];
                $(this).data('fecha', fecha_formateada);
                console.log(fecha_formateada);
                $('.tabla-data').DataTable().ajax.reload();

            });

            const configDataTableZonas = {
                serverSide: true,
                searching: false,
                lengthChange: false,
                order: [[0, "desc"]],
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    $('[data-toggle=jqconfirm]', row).click(function () {
                        const action = $(this).data('target')
                        $.confirm({
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
                                            })
                                    }
                                },
                                cancelar: {}
                            }
                        })
                    })
                },
                columns: [
                    {data: 'codigos', name: 'codigos',},
                    {data: 'celular', name: 'celular',},
                    {data: 'distribucion', name: 'distribucion',},
                    {data: 'distrito', name: 'distrito',},

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
                ajax: {
                    url:"{{route('envios.rutaenviotabla',['datatable'=>'1'])}}",
                    data:function (a) {
                        a.fechaconsulta = $("#fecha_consulta").data("fecha");
                        a.tab=$("#myTab{{Str::slug($motorizado->zona)}} li>a.active").data('tab');
                        a.motorizado_id = {{ $motorizado->id }};
                        a.zona = "{{ Str::upper($motorizado->zona)}}";
                        a.vista = "envio_ruta";
                    }
                },
            });
            @endforeach

            @foreach($motorizados as $motorizado)
                var tt= $("#myTab{{Str::upper($motorizado->zona)}}")[0];

            $('a[data-toggle="tab"]',tt).on('shown.bs.tab', function (e) {
                let zona = $(this).data('zona');
                $('#tablaPrincipal{{Str::upper($motorizado->zona)}}').DataTable().ajax.reload();
            })
            @endforeach




            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
    </script>

@stop
