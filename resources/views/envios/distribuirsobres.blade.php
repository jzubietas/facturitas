@extends('adminlte::page')

@section('title', 'Envios | Sobres sin enviar')

@section('content_header')
    <h1>Distribución de sobres</h1>
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
                            <h5>Distribucion {{Str::ucfirst(Str::lower($motorizado->zona))}}</h5>
                            <div>
                                <button type="button" class="btn btn-light buttom-agrupar"
                                        data-zona="{{Str::upper($motorizado->zona)}}"
                                        data-ajax-action="{{route('envios.distribuirsobres.agrupar',['motorizado_id'=>$motorizado->id,'zona'=>Str::upper($motorizado->zona)])}}">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                      style="display: none"></span>
                                    <span class="sr-only" style="display: none"></span>
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    <b>Agrupar</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="text-center"></h4>

                        <table id="tablaPrincipal{{Str::upper($motorizado->zona)}}" class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Código</th>
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
        @endforeach


    </div>

    <div class="card">
        <div class="card-body">

            <table id="tablaPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">ZONA</th>
                    <th scope="col">DISTRITO</th>
                    <th scope="col">Razón social</th>

                    <th scope="col">Dias</th>

                    <th scope="col">Fecha de envio</th>
                    <th scope="col">Estado de envio</th>
                    <th scope="col">Observacion Devolucion</th>
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
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/v4-shims.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/solid.min.css')}}">
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $('#tablaPrincipal').DataTable({
                processing: false,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: "{{ route('envios.distribuirsobrestabla') }}",
                createdRow: function (row, data, dataIndex) {
                    //console.log(row);

                },
                rowCallback: function (row, data, index) {
                    $('[data-ajax-post]', row).click(function () {
                        const link = $(this).attr('data-ajax-post')
                        $(this).find('.spinner-border').show()
                        $(this).find('.sr-only').show()

                        $.post(link).always(function () {
                            $(this).find('.spinner-border').hide()
                            $(this).find('.sr-only').hide()

                            $('#tablaPrincipal').DataTable().ajax.reload();
                            @foreach($motorizados as $m)
                            $('#tablaPrincipal{{Str::upper($m->zona)}}').DataTable().ajax.reload();
                            @endforeach
                        })
                    })
                },
                columns: [
                    {data: 'codigo', name: 'codigo',},
                    {data: 'users', name: 'users',},
                    {data: 'env_zona', name: 'env_zona',},
                    {data: 'env_distrito', name: 'env_distrito',},

                    {data: 'empresas', name: 'empresas',},
                    {data: 'dias', name: 'dias',},

                    {data: 'fecha_envio_doc_fis', name: 'fecha_envio_doc_fis',},
                    {
                        data: 'condicion_envio',
                        name: 'condicion_envio',
                    },
                    {
                        data: 'observacion_devuelto',
                        name: 'observacion_devuelto',
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


            const configDataTableZonas = {
                processing: false,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                createdRow: function (row, data, dataIndex) {
                },
                columns: [
                    {data: 'codigo', name: 'codigo',},
                    {data: 'env_zona_asignada', name: 'env_zona_asignada',},
                    {data: 'env_distrito', name: 'env_distrito',},
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
                ajax: "{{ route('envios.distribuirsobresporzona.table',['zona'=>Str::upper($motorizado->zona)]) }}",
                rowCallback: function (row, data, index) {
                    $('[data-ajax-post]', row).click(function () {
                        const link = $(this).attr('data-ajax-post')
                        $(this).find('.spinner-border').show()
                        $(this).find('.sr-only').show()

                        $.post(link).always(function () {
                            $(this).find('.spinner-border').hide()
                            $(this).find('.sr-only').hide()
                            @foreach($motorizados as $m)
                            $('#tablaPrincipal{{Str::upper($m->zona)}}').DataTable().ajax.reload();
                            @endforeach
                            $('#tablaPrincipal').DataTable().ajax.reload();
                        })
                    })
                },
            });
            @endforeach
            $(".buttom-agrupar[data-ajax-action]").click(function () {
                const buttom = $(this)
                const link = buttom.attr('data-ajax-action')
                $.confirm({
                    title: '¡Advertencia!',
                    content: '¿Estas seguro de crear el paquete con los sobres listados en la zona <b>'+$(this).data('zona')+'</b>?',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        accept: {
                            text: 'Aceptar y agrupar',
                            btnClass: 'btn-red',
                            action: function(){
                                buttom.find('.spinner-border').show()
                                buttom.find('.sr-only').show()
                                const self=this
                                self.showLoading(true)
                                $.post(link).always(function () {
                                    self.hideLoading(true)
                                    self.close()
                                    buttom.find('.spinner-border').hide()
                                    buttom.find('.sr-only').hide()

                                    $('#tablaPrincipal').DataTable().ajax.reload();
                                    @foreach($motorizados as $m)
                                    $('#tablaPrincipal{{Str::upper($m->zona)}}').DataTable().ajax.reload();
                                    @endforeach
                                })
                                return false
                            }
                        },
                        cancelar: function () {
                            return true
                        }
                    }
                });
            })
        });
    </script>

@endpush
