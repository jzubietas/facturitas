@extends('adminlte::page')

@section('title', 'Lista de mis Pagos')

@section('content_header')
    <h1>Lista mis de pagos registrados: {{ Auth::user()->name }}
        @if($pagosobservados_cantidad > 0)
            <div class="small-box bg-danger" style="text-align: center">
                <div class="inner">
                    <h3>{{ $pagosobservados_cantidad }}</h3>
                    <p>PAGOS OBSERVADOS</p>
                </div>
            </div>
        @endif
        @can('pagos.create')
            <a href="{{ route('pagos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan

        @if ($mirol=='Administrador')
            <div class="float-right btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    Exportar
                </button>
                <div class="dropdown-menu">
                    <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item"
                       target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Mis Pagos</a>
                </div>
            </div>
            @include('pagos.modals.exportar', ['title' => 'Exportar Lista de mis pagos', 'key' => '2'])
        @endif
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
            <table cellspacing="5" cellpadding="5" class="table-responsive">
                <tbody>
                <tr>
                    <td>Fecha Minima:</td>
                    <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
                    <td></td>
                    <td>Fecha Máxima:</td>
                    <td><input type="text" value={{ $dateMax }} id="max" name="max" class="form-control"></td>
                </tr>
                </tbody>
            </table>
            <br>
            <table id="tablaPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">COD.</th>
                    <th scope="col" class="align-middle">Codigo pedido</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Cliente</th>
                    <th scope="col" class="align-middle">Observacion</th>
                    {{--<th scope="col">Total cobro</th>--}}
                    <th scope="col" class="align-middle">Total pagado</th>
                    <th scope="col" class="align-middle">fecha</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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

    </style>
@endpush

@section('js')

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: "{{ route('MisPagosTabla') }}",
                createdRow: function (row, data, dataIndex) {
                },
                rowCallback: function (row, data, index) {
                },
                initComplete: function (settings, json) {

                },
                columns: [
                    {
                        data: 'code',
                        name: 'code',
                    },
                    {
                        data: 'codigos'
                        , name: 'codigos'
                        , render: function (data, type, row, meta) {
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
                        }
                    },
                    {//asesor
                        data: 'users', name: 'users'
                    },
                    {//cliente
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            if (row.icelular != null) {
                                return row.celular + '-' + row.icelular;
                            } else {
                                return row.celular;
                            }

                        },
                    },
                    {//observacion
                        data: 'observacion', name: 'observacion'
                    },
                    /*{
                      data: 'total_cobro', name: 'total_cobro'
                    },*/
                    {//totalpagado
                        data: 'total_pago', name: 'total_pago'
                    },
                    {//fecha
                        data: 'fecha',
                        name: 'fecha',
                        render: function (data, type, row, meta) {
                            return data;
                        }
                    },//estado de pedido
                    {
                        data: 'condicion',
                        name: 'condicion',
                        render: function (data, type, row, meta) {
                            return data;
                        }
                    },//estado de pago
                    //{data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '20%',
                        render: function (data, type, row, meta) {

                            var urlshow = '{{ route("pagos.show", ":id") }}';
                            urlshow = urlshow.replace(':id', row.id);

                            var urledit = '{{ route("pagos.edit", ":id") }}';
                            urledit = urledit.replace(':id', row.id);

                            @can('pagos.show')
                                data = data + '<a href="' + urlshow + '" class="btn btn-dev btn-info btn-sm"><i class="fas fa-eye"></i> VER</a>';
                            @endcan
                            @can('pagos.edit')
                            //data = data+'<a href="'+urledit+'" class="btn btn-warning btn-sm"> Editar</a>';
                            @endcan
                            @can('pagos.destroy')
                            //data = data+'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'+row.id+'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>';
                            @endcan

                                return data;
                        }
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

            $(document).on("keypress", '#tablaPrincipal_filter label input', function () {
                console.log("aaaaa")

                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));

            });

            $('#tablaPrincipal_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });


        })
        ;
    </script>

    @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
        <script>
            Swal.fire(
                'Pago {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        /*window.onload = function () {
          $('#tablaPrincipal').DataTable().draw();
        }*/
    </script>

    <script>
        /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");
                    // need to change str order before making  date obect since it uses a new Date("mm/dd/yyyy") format for short date.
                    var d = data[7].split("/");
                    var startDate = new Date(d[1] + "/" + d[0] + "/" + d[2]);

                    if (min == null && max == null) {
                        return true;
                    }
                    if (min == null && startDate <= max) {
                        return true;
                    }
                    if (max == null && startDate >= min) {
                        return true;
                    }
                    if (startDate <= max && startDate >= min) {
                        return true;
                    }
                    return false;
                }
            );


            $("#min").datepicker({
                onSelect: function () {
                    table.draw();
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });
            $("#max").datepicker({
                onSelect: function () {
                    table.draw();
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });
            var table = $('#tablaPrincipal').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });
    </script>

@stop
