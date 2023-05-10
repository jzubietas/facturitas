@extends('adminlte::page')

@section('title', 'Lista de Pagos')

@section('content_header')
    <h1>Lista mis de pagos observados: {{ Auth::user()->name }}
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
        {{-- @can('pagos.exportar')
        <div class="float-right btn-group dropleft">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Exportar
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('pagosobservadosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
        @include('pagos.modals.exportar', ['title' => 'Exportar Lista de pagos observados', 'key' => '4'])
    </h1>

    <div class="form-group col-lg-6">

        <select name="asesores_observado" class="border form-control selectpicker border-secondary"
                id="asesores_observado" data-live-search="true">
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
                    <th scope="col" class="align-middle">Codigo pedido</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Observacion</th>
                    <th scope="col" class="align-middle">Total cobro</th>
                    <th scope="col" class="align-middle">Total pagado</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Acciones</th>
                </tr>
                </thead>
                <tbody>
                {{--
                @foreach ($pagos as $pago)
                    <tr>
                        <td>PAG000{{ $pago->id }}</td>
                        <td>{{ $pago->codigos }}</td>
                        <td>{{ $pago->users }}</td>
                        <td>{{ $pago->observacion }}</td>
                        <td>@php echo number_format($pago->total_deuda,2) @endphp</td>
                        <td>@php echo number_format($pago->total_pago,2) @endphp</td>
                        <td>{{ $pago->condicion }}</td>
                        <td>
                            @can('pagos.show')
                                <a href="{{ route('pagos.show', $pago) }}" class="btn btn-info btn-sm">Ver</a>
                            @endcan
                            @can('pagos.edit')
                                <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning btn-sm">Editar</a>
                            @endcan
                            @can('pagos.destroy')
                                <a href="" data-target="#modal-delete-{{ $pago->id }}" data-toggle="modal">
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                --}}
                </tbody>
            </table>
        </div>
    </div>

    @include('pagos.modals.modalDeleteId')
@stop

@push('css')
    <link rel="stylesheet" href="../css/admin_custom.css">
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

        #tablaPrincipal td:nth-child(8) {
            display: flex;
            justify-content: center;
            align-items: center;
            grid-gap: 3px;
        }

        #tablaPrincipal .sorting::before,
        #tablaPrincipal .sorting::after,
        #tablaPrincipal .sorting_asc::before,
        #tablaPrincipal .sorting_asc::after {
            top: 20px !important;
        }

    </style>
@endpush

@section('js')

    {{-- <script src="{{ asset('js/datatables.js') }}"></script>--}}

    @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
        <script>
            Swal.fire(
                'Pago {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>

        (function ($) {
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function () {

                $('#tablaPrincipal').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    order: [[0, "asc"]],
                    ajax: {
                        url: "{{ route('pagos.pagosobservados',['datatable'=>1]) }}",
                        data: function (d) {
                            d.asesores = $("#asesores_observado").val();
                            d.min = $("#min").val();
                            d.max = $("#max").val();
                            // d.custom = $('#myInput').val();
                            // etc
                        },
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
                            }
                        },
                        {
                            data: 'users',
                            name: 'users',
                        },
                        {
                            data: 'observacion',
                            name: 'observacion'
                        },
                        {
                            data: 'total_deuda',
                            name: 'total_deuda'
                        },
                        {data: 'total_pago', name: 'total_pago'},//total_pago
                        {
                            data: 'condicion',
                            name: 'condicion',
                            render: function (data, type, row, meta) {
                                if (row.subcondicion != null) {
                                    return '<span class="badge badge-dark">' + row.subcondicion + '</span>' + data;
                                } else {
                                    return data;
                                }

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

                $.ajax({
                    url: "{{ route('asesorespago') }}",
                    method: 'GET',
                    success: function (data) {
                        console.log(data.html);
                        $('#asesores_observado').html(data.html);
                        $('#asesores_observado').selectpicker('refresh');

                        if (localStorage.getItem('pagosobservados')) {
                            $('#asesores_observado').val(localStorage.getItem('pagosobservados'));
                            $('#asesores_observado').selectpicker("refresh").trigger("change");
                        }
                    }
                });

                $(document).on("change", "#asesores_observado", function () {
                    localStorage.setItem('pagosobservados', $(this).val());
                    $('#tablaPrincipal').DataTable().ajax.reload();
                });

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

                $(document).on("submit", "#formdelete", function (evento) {
                    evento.preventDefault();
                    console.log("validar delete");
                    clickformdelete();
                })
            });

        })(window.jQuery)
    </script>

@stop
