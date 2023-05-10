@extends('adminlte::page')

@section('title', 'Lista de Clientes')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection

@section('content_header')
    <h1>Lista de clientes en situacion RECUPERADOS
        @can('clientes.create')
            <a href="{{ route('clientes.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan
        @can('clientes.exportar')
            <div class="float-right btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    Exportar
                </button>
                <div class="dropdown-menu">
                    <a href="" data-target="#modal-exportar-unico" data-toggle="modal" class="dropdown-item"
                       target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Pedidos</a>
                </div>
            </div>
            @include('clientes.modal.exportar_unico', ['title' => 'Exportar Lista de clientes RECUPERADOS', 'key' => '6'])
        @endcan
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
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">COD.</th>
                    <th scope="col" class="align-middle">Nombre</th>
                    <th scope="col" class="align-middle">Celular</th>
                    <th scope="col" class="align-middle">Direccion</th>
                    <th scope="col" class="align-middle">Asesor asignado</th>
                    <th scope="col" class="align-middle">Situacion</th>
                    <th scope="col" class="align-middle">Fec.Ult.Pedido</th>
                    <th scope="col" class="align-middle">Cod.Ult.Pedido</th>
                    {{--<th scope="col">Cantidad</th>--}}
                    {{--<th scope="col">Año actual</th>
                    <th scope="col">Mes actual</th>
                    <th scope="col">anio pedido</th>
                    <th scope="col">mes pedido</th>
                    <th scope="col">Deuda</th>--}}
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

    <style>

        .perla {
            background-color: #faedcd !important;
        }

        .red {
            background-color: red !important;
        }

        .white {
            background-color: white !important;
        }

        .lighblue {
            background-color: #4ac4e2 !important;
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

        #tablaPrincipal {
            width: 100% !important;
        }

        #tablaPrincipal td {
            text-align: start !important;
            vertical-align: middle !important;
        }

        #tablaPrincipal td:nth-child(9) {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            grid-gap: 5px !important;
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

            $(document).on("click", "#delete", function () {

                console.log("action delete action")
                var formData = $("#formdelete").serialize();
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('clientedeleteRequest.post') }}",
                    data: formData,
                }).done(function (data) {
                    $("#modal-delete").modal("hide");
                    resetearcamposdelete();
                    $('#tablaPrincipal').DataTable().ajax.reload();
                });

            });

            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('delete')
                $("#hiddenClienteId").val(idunico);
                if (idunico < 10) {
                    idunico = 'PAG000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PAG00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PAG0' + idunico;
                } else {
                    idunico = 'PAG' + idunico;
                }
                $(".textcode").html(idunico);

            });


            $('#tablaPrincipal').DataTable({
                processing: true,
                responsive: true,
                autowidth: true,
                serverSide: true,
                ajax: "{{ route('recuperadostabla') }}",
                initComplete: function (settings, json) {

                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            if (row.id < 10) {
                                return 'CL' + row.identificador + '000' + row.id;
                            } else if (row.id < 100) {
                                return 'CL' + row.identificador + '00' + row.id;
                            } else if (row.id < 1000) {
                                return 'CL' + row.identificador + '00' + row.id;
                            } else {
                                return 'CL' + row.identificador + '' + row.id;
                            }
                        }
                    },
                    {data: 'nombre', name: 'nombre'},
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            if (row.icelular != null) {
                                return row.celular + '-' + row.icelular;
                            } else {
                                return row.celular;
                            }
                        }
                    },
                    //{data: 'estado', name: 'estado'},
                    //{data: 'user', name: 'user'},
                    //{data: 'identificador', name: 'identificador'},
                    //{data: 'provincia', name: 'provincia'},
                    {
                        data: 'direccion',
                        name: 'direccion',
                        render: function (data, type, row, meta) {
                            return row.direccion + ' - ' + row.provincia + ' (' + row.distrito + ')';
                        }
                    },
                    //{data: 'direccion', name: 'direccion'},
                    {data: 'identificador', name: 'identificador'},
                    {data: 'situacion', name: 'situacion'},
                    //{data: 'cantidad', name: 'cantidad'},
                    //{data: 'dateY', name: 'dateY'},
                    //{data: 'dateM', name: 'dateM'},
                    //{data: 'anio', name: 'anio'},
                    //{data: 'mes', name: 'mes'},
                    //{data: 'deuda', name: 'deuda'},
                    {data: 'fechaultimopedido', name: 'fechaultimopedido'}, {
                        data: 'codigoultimopedido',
                        name: 'codigoultimopedido'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '20%',
                        render: function (data, type, row, meta) {
                            var urledit = '{{ route("clientes.edit.recuperado", ":id") }}';
                            urledit = urledit.replace(':id', row.id);

                            var urlshow = '{{ route("clientes.show", ":id") }}';
                            urlshow = urlshow.replace(':id', row.id);

                            @can('clientes.edit.recuperado')
                                data = data + '<a href="' + urledit + '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>';
                            @endcan

                                @if($mirol !='Administradorsdsd')
                                data = data + '<a href="' + urlshow + '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>';
                            @endif

                                @can('clientes.destroy')
                                data = data + '<a href="" data-target="#modal-delete" data-toggle="modal" data-opcion="' + row.id + '"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';

                            @endcan
                                return data;
                        }
                    },
                ],
                "createdRow": function (row, data, dataIndex) {
                    if (data["situacion"] == 'BLOQUEADO') {
                        $(row).addClass('textred');
                    } else {
                        if (data["pedidos_mes_deuda_antes"] == 0) {
                            if (data["pedidos_mes_deuda"] == 0) {
                            } else if (data["pedidos_mes_deuda"] == 1) {
                                $(row).addClass('perla');
                            } else {
                                $(row).addClass('lighblue');
                            }
                        } else {
                            $(row).addClass('red');
                        }

                    }
                },
                "language": {
                    "url": "{{asset('vendor/datatables/Spanish.json')}}"
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
            $('#tablaPrincipal_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });
            $(document).on("keypress", '#tablaPrincipal_filter label input', function () {
                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));
            });
        });


    </script>

    <!--<script src="{{ asset('js/datatables.js') }}"></script>-->

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Cliente {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

@stop
