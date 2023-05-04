@extends('adminlte::page')

@section('title', 'Lista de Clientes en situacion CONGELADO')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection

@section('content_header')
    <h1>Lista de clientes en situacion CONGELADO
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
            @include('clientes.modal.exportar_unico', ['title' => 'Exportar Lista de clientes CONGELADO', 'key' => '12'])
        @endcan
    </h1>

@stop

@section('content')

    <div class="card" style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">COD.</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Celular</th>
                    <th scope="col">Direccion</th>
                    <th scope="col">Asesor asignado</th>
                    <th scope="col">Situacion</th>
                    <th scope="col">Fec.Ult.Pedido</th>
                    <th scope="col">Cod.Ult.Pedido</th>
                    {{--<th scope="col">Cantidad</th>--}}
                    {{--<th scope="col">Año actual</th>
                    <th scope="col">Mes actual</th>
                    <th scope="col">anio pedido</th>
                    <th scope="col">mes pedido</th>
                    <th scope="col">Deuda</th>--}}
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            @include('clientes.modal.modalrevertircongelado')
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

        #tablaPrincipal{
            width: 100% !important;
        }

        #tablaPrincipal td{
            text-align: start !important;
            vertical-align: middle !important;
        }

        #tablaPrincipal td:nth-child(9){
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
                var formData = $("#formdelete").serialize();
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('cliente.revertir.congelado') }}",
                    data: formData,
                }).done(function (data) {
                    $("#modal-delete").modal("hide");
                    resetearcamposdelete();
                    $('#tablaPrincipal').DataTable().ajax.reload();
                });

            });

            $('#modal-revertir-congelado').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('opcion')
                var idunico_c = button.data('correlativo')
                $("#hiddenClienteId").val(idunico);
                $(".textcode").html(idunico_c);
            });

            $('#tablaPrincipal').DataTable({
                processing: true,
                responsive: true,
                autowidth: true,
                serverSide: true,
                ajax: "{{ route('clientescongeladotabla') }}",
                initComplete: function (settings, json) {

                },
                columns: [
                    {data: 'correlativo',name: 'correlativo',},
                    {data: 'nombre', name: 'nombre'},
                    {data: 'celular',name: 'celular',},
                    {data: 'direccion',name: 'direccion',},
                    {data: 'identificador', name: 'identificador'},
                    {data: 'situacion', name: 'situacion'},
                    {data: 'fechaultimopedido', name: 'fechaultimopedido'},
                    {data: 'codigoultimopedido',name: 'codigoultimopedido'},
                    {data: 'action',name: 'action',},
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
