@extends('adminlte::page')

@section('title', 'Lista de Pagos')

@section('content_header')
    <h1>Lista de pagos APROBADOS
        @can('pagos.create')
            <a href="{{ route('pagos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan
        {{-- @can('pagos.exportar')
        <div class="float-right btn-group dropleft">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Exportar
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('pagosaprobadosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
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
        @include('pagos.modals.exportar', ['title' => 'Exportar Lista de pagos aprobados', 'key' => '5'])
    </h1>

    <div class="form-group col-lg-6">

        <select name="asesores_aprobado" class="border form-control selectpicker border-secondary"
                id="asesores_aprobado" data-live-search="true">
            <option value="">---- SELECCIONE ASESOR ----</option>
        </select>
    </div>

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
            <table id="tablaPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">COD.</th>
                    <th scope="col" class="align-middle">COD2.</th>
                    <th scope="col" class="align-middle">Cliente</th>
                    <th scope="col" class="align-middle">Codigo pedido</th>
                    <th scope="col" class="align-middle">Fecha Voucher</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Observacion</th>
                    {{--<th scope="col">Total cobro</th>--}}
                    <th scope="col" class="align-middle">Total pagado</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('pagos.modals.modalDeleteId')
            @include('pagos.modals.modalDesabonar')

        </div>
    </div>

@stop

@push('css')
    <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
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
            flex-direction: column !important;
            grid-gap: 5px !important;
        }

    </style>
@endpush

@section('js')

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>
        function clickformdelete() {
            console.log("action delete action")
            var formData = $("#formdelete").serialize();
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('pagodeleteRequest.post') }}",
                data: formData,
            }).done(function (data) {
                $("#modal-delete").modal("hide");
                //resetearcamposdelete();
                $('#tablaPrincipal').DataTable().ajax.reload();
            });
        }

        function clickformdesabonar() {

            var formData = $("#formdesabonar").serialize();
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('pagodesabonarRequest.post') }}",
                data: formData,
            }).done(function (data) {
                $("#modal-desabonar").modal("hide");
                //resetearcamposdelete();
                $('#tablaPrincipal').DataTable().ajax.reload();
            });
        }
    </script>

    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('asesorespago') }}",
                method: 'GET',
                success: function (data) {
                    console.log(data.html);
                    $('#asesores_aprobado').html(data.html);
                    $('#asesores_aprobado').selectpicker('refresh');
                }
            });

            $(document).on("change", "#asesores_aprobado", function () {

                $('#tablaPrincipal').DataTable().ajax.reload();

            });

            $(document).on("submit", "#formdelete", function (evento) {
                evento.preventDefault();
                console.log("validar delete");
                clickformdelete();
            })

            $(document).on("submit", "#formdesabonar", function (evento) {
                evento.preventDefault();
                console.log("validar delete");
                clickformdesabonar();
            })

            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('delete')
                $("#hiddenId").val(idunico);
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

            $('#modal-desabonar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('desabonar')
                var textpago = button.data('pago')
                $("#hiddenDesabonar").val(idunico);

                $("#modal-desabonar .textcode").html(textpago);
            });

            $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('administracion.aprobadostabla') }}",
                    data: function (d) {
                        d.asesores = $("#asesores_aprobado").val();
                    },
                },
                initComplete: function (settings, json) {

                },
                columns: [
                    {
                        data: 'id2',
                        name: 'id2',
                    },
                    {
                        data: 'id'
                        , name: 'id', "visible": false
                    },
                    {data: 'celular', name: 'celular'},
                    {
                        data: 'codigos'
                        , name: 'codigos'
                        , render: function (data, type, row, meta) {
                            var returndata = '';
                            var jsonArray = data.split(",");
                            $.each(jsonArray, function (i, item) {
                                returndata += item + '<br>';
                            });
                            return returndata;
                        }
                    },
                    {data: 'fecha', name: 'fecha'},////asesor
                    {data: 'users', name: 'users'},////asesor
                    {data: 'observacion', name: 'observacion'},//observacion
                    //{ data: 'total_deuda', name: 'total_deuda'},//total_deuda
                    {data: 'total_pago', name: 'total_pago'},//total_pago
                    {
                        data: 'condicion',
                        name: 'condicion',
                        render: function (data, type, row, meta) {
                            return data;
                        }
                    },//estado
                    {data: 'action', name: 'action', orderable: false, searchable: false, sWidth: '20%'},
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


        });
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

@stop
