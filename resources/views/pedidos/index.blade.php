{{--/pedidos--}}
@extends('adminlte::page')

@section('title', 'Pedidos - Bandeja de pedidos')

@section('content_header')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600;700&family=Work+Sans:wght@300;400&display=swap');

        body {
            font-family: 'Work Sans', sans-serif;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
        }

        .bootstrap-select.btn-group .btn .filter-option {
            text-align: right
        }

        .bootstrap-select .dropdown-toggle .filter-option {
            text-align: right !important;
        }

        .bootstrap-select .dropdown-menu.inner {
            text-align: right !important;
        }

        .btn.dropdown-toggle.bs-placeholder {
            background-color: black !important;
        }
    </style>

    <h1>Lista de pedidos
        @can('pedidos.create')
            <a href="{{ route('pedidos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
            {{-- <a href="" data-target="#modal-add-ruc" data-toggle="modal">(Agregar +)</a> --}}
        @endcan
        {{-- @can('pedidos.exportar')
        <div class="float-right btn-group dropleft">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Exportar
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('pedidosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
          </div>
        </div>
        @endcan --}}
        <div class="float-right btn-group dropleft" style="display: contents">

            <a href="{{route('excel.clientes-four-month-ago-excel')}}" target="_blank" class="btn btn-dark mr-4">
                <i class="fa fa-download"></i>
                <i class="fa fa-file-excel"></i>
                DEJARON DE PEDIR (4) meses
            </a>

            <a href="{{route('excel.clientes-two-month-ago-excel')}}" target="_blank" class="btn btn-dark mr-4">
                <i class="fa fa-download"></i>
                <i class="fa fa-file-excel"></i>
                DEJARON DE PEDIR
            </a>

            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img
                        src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
            </div>
        </div>
        @include('pedidos.modal.exportar', ['title' => 'Exportar Lista de pedidos', 'key' => '3'])
    </h1>
    {{--@if($superasesor > 0)--}}
    {{--
    <br>
    <div class="bg-4">
      <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
        asesores con privilegios superiores: {{ $superasesor }}
      </h1>
    </div>--}}
    {{--@endif--}}
@stop

@section('content')
    <div class="card" style="overflow: hidden !important;">
        <div class="card-body pl-1 pr-1" style="overflow-x: scroll !important;">
            <!--
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
            <br>-->
            <table id="tablabandejapedidos" class="table table-striped">{{-- display nowrap  --}}
                <thead>
                <tr>
                    {{--<th class="align-middle"></th>--}}
                    <th class="align-middle" scope="col">Código</th>
                    <th class="align-middle" scope="col">Cliente</th>
                    <th class="align-middle" scope="col">Razón social</th>
                    <th class="align-middle" scope="col">Cantidad</th>
                    <th class="align-middle" scope="col">Id</th>
                    <th class="align-middle" scope="col">RUC</th>
                    <th class="align-middle" scope="col">F. Registro</th>
                    <th class="align-middle" scope="col">F. Actualizacion</th>
                    <th class="align-middle" scope="col">Total (S/)</th>
                    <th class="align-middle" scope="col">Est. pago</th>
                    <th class="align-middle" scope="col">Con. pago</th>
                    <th class="align-middle" scope="col">Est. Sobre</th>
                    <th class="align-middle" scope="col">Dif.</th>
                    <th class="align-middle" scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('pedidos.modalid')
            @include('pedidos.modal.restaurarid')
            @include('pedidos.modal.Correciones.Correccion')
            @include('pedidos.modal.Correciones.Recojo')
            @include('pedidos.modal.Correciones.recojo-submodals.Modal-listclientes')

        </div>
    </div>
@stop

@push('css')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>

        .yellow {
            /*background-color: yellow !important;*/
            color: #fcd00e !important;
        }

        .textred {
            color: red !important;
        }

        .red {
            background-color: red !important;
        }

        .white {
            background-color: white !important;
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

        td.details-control {
            background: url("/images/details_open.png") no-repeat center center;
            cursor: pointer;
        }

        tr.details td.details-control {
            background: url('/images/details_close.png') no-repeat center center;
        }

        @media screen and (max-width: 2249px) {
            #tablabandejapedidos{
                width: 100% !important;
            }
        }


    </style>
@endpush

@section('js')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


    {{--<script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.0.1/js/dataTables.searchBuilder.min.js"></script>--}}
    {{--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>--}}
    {{--<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.24/sorting/datetime-moment.js"></script>--}}

    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script
        src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

    <!--  <script src="{{ asset('js/datatables.js') }}"></script>-->
    <script>
        //variable
        var tabla_pedidos = null;


        //VALIDAR CAMPO CELULAR
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }
    </script>
    <script>
        //import objects from "lodash/_SetCache";
        let tablaBandejaPedidos = null;
        let dataForm_pc = {};
        let dataForm_f = {};
        let dataForm_g = {};
        let dataForm_b = {};
        const configDataTableLanguages = {
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
        }

        $(document).ready(function () {
            //moment.updateLocale(moment.locale(), { invalidDate: "Invalid Date Example" });
            //$.fn.dataTable.moment('DD-MMM-Y HH:mm:ss');
            //$.fn.dataTable.moment('DD/MM/YYYY');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on("change", "#recojo_destino", function () {
                $("#distrito").val("").selectpicker("refresh")
            });

            $('#recojo_pedido_quienrecibe_nombre').on('input', function () {
                this.value = this.value.replace(/[^a-zA-Z >]/g, '');
            });

            $('#recojo_pedido_quienrecibe_celular').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#recojo_pedido_direccion,#recojo_pedido_referencia,#recojo_pedido_observacion').on('input', function () {
                this.value = this.value.replace(/[^0-9 a-zA-Z]/g, '');
            });

            $(document).on('click', '.button_load_history_recojo', function (e) {
                const json = $(this).data('json');
                const selectedData = ((json && typeof json != 'string') ? json : JSON.parse($(this).data('json')))
                console.log(selectedData)
                var form = $("#formrecojo")[0];

                form.direccion_id.value = selectedData.id;
                form.nombre.value = selectedData.nombre;
                form.celular.value = selectedData.celular;
                form.direccion.value = selectedData.direccion;
                form.referencia.value = selectedData.referencia;
                $(form.distrito).val(selectedData.distrito).trigger('change');

                form.observacion.value = selectedData.observacion;

                $(form.direccion_id).data('old_value', selectedData.id);
                $(form.nombre).data('old_value', form.nombre.value);
                $(form.celular).data('old_value', form.celular.value);
                $(form.direccion).data('old_value', form.direccion.value);
                $(form.referencia).data('old_value', form.referencia.value);
                $(form.distrito).data('old_value', form.distrito.value);
                $(form.observacion).data('old_value', form.observacion.value);

                /*$("#modal-historial-lima").modal('hide')
                $("#set_cliente_clear").show()
                $("#saveHistoricoLima").parent().hide()
                $("#saveHistoricoLimaEditar").parent().show()*/
            })

            tablaPedidosLista = $('#datatable-pedidos-lista-recojer').DataTable({
                ...configDataTableLanguages,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                columns:
                    [
                        {data: 'id', name: 'id', "visible": false},
                        {data: 'codigo', name: 'codigo',},
                        {data: 'condicion_envio', name: 'condicion_envio',},
                        {data: 'action', name: 'action',},
                    ],
            });

            tablaClienteLista = $('#datatable-clientes-lista-recojer').DataTable({
                ...configDataTableLanguages,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                columns: [
                    {data: 'id', name: 'id', "visible": false},
                    {data: 'user_id', name: 'user_id',},
                    {data: 'celular', name: 'celular',},
                    {data: 'action', name: 'action',},
                ],
            });

            $('#modal-correccion-pedidos').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $('#modalcorreccionpedido').val(button.data('codigo'))
                $('button:submit').prop("disabled", false)
                ocultar_div_modal_correccion_pedidos();
            })

            $(document).on("click", ".btn-cancel-recojo", function () {
                $(".card_pedidos").hide();
                $(".card_form").hide();
                $(".card_clientes").show();
                //limpiar datos de direcion
                $("#distrito_recoger").val("").selectpicker("refresh")
                $("#recojo_pedido_quienrecibe_nombre").val("")
                $("#recojo_pedido_quienrecibe_celular").val("")
                $("#recojo_pedido_direccion").val("")
                $("#recojo_pedido_referencia").val("")
                $("#recojo_pedido_observacion").val("")
            });


            $('#modal-recojo-pedidos').on('show.bs.modal', function (event) {
                $("#distrito_recojo").val("").selectpicker("refresh")
                var button = $(event.relatedTarget)
                $('#clienteid').val(button.data('clienteid'))
                $('#clientenombre').val(button.data('clientenombre'))
                $('#pedidoid').val(button.data('pedidoid'))
                $('#pedidocodigo').val(button.data('pedidocodigo'))
                $('#direccion_recojo').val(button.data('direccionreco'))
                $('#nombre_recojo').val(button.data('nombreresiv'))
                $('#celular_recojo').val(button.data('telefonoresiv'))
                $('#referencia_recojo').val(button.data('referenciareco'))
                $('#observacion_recojo').val(button.data('observacionreco'))
                $('#gmlink_recojo').val(button.data('gmclink'))
                $("#sustento-recojo").val("");

                var cod_pedido = $('#pedidoid').val();

                $('#pedido_concatenado').val(button.data('pedidoid'));

                $('#direcciones_add ul').html('');
                $('#pedidoid').val(button.data('pedidoid'));
                $('#direcciones_add ul').append(`
                    <li>` + button.data('pedidocodigo') + `</li>
                `);

                var fd_asesor = new FormData();
                fd_asesor.append('codigo_pedido', cod_pedido);
                fd_asesor.append('codigo_cliente', button.data('clienteid'));

                $.ajax({
                    processData: false,
                    contentType: false,
                    data: fd_asesor,
                    type: 'POST',
                    url: "{{ route('getdireecionentrega') }}",
                    success: function (data) {

                        const datosdevueltos = data.split("|");
                        console.log(datosdevueltos)
                        let validadatosdevueltos = datosdevueltos[1];
                        if (validadatosdevueltos == 0) {
                            $("button.btnVerMasPedidos").attr("disabled", true);
                        }
                        $("#Direccion_de_entrega").val(datosdevueltos[0]);
                    }
                });

                $('button:submit').prop("disabled", false)
                ocultar_div_modal_correccion_pedidos();
            })

            $(document).on("submit", "#form-recojo", function (event) {
                event.preventDefault();
                let Nombre_recibe = $("#nombre_recojo").val();
                let celular_id = $("#celular_recojo").val();
                let direccion_recojo = $("#direccion_recojo").val();
                let referencia_recojo = $("#referencia_recojo").val();
                let observacion_recojo = $("#observacion_recojo").val();
                let gm_link = $("#gmlink_recojo").val();
                let direccion_entrega = $("#Direccion_de_entrega").val();
                let sustento_recojo = $("#sustento-recojo").val();
                let pedido_concatenado = $("#pedido_concatenado").val();
                let distrito_recojo = $("#distrito_recojo").val();

                console.log(distrito_recojo)

                if (distrito_recojo == "" || distrito_recojo == null) {
                    Swal.fire('Debe seleccionar un distrito', '', 'warning');
                    return false;
                } else if (direccion_recojo == "") {
                    Swal.fire('Debe colocar una direccion de recojo', '', 'warning');
                    return false;
                } else if (Nombre_recibe == "") {
                    Swal.fire('Debe colocar el nombre del que recive', '', 'warning');
                    return false;
                } else if (celular_id == "") {
                    Swal.fire('Debe colocar el celular del quien recibe', '', 'warning');
                    return false;
                } else if (referencia_recojo == "") {
                    Swal.fire('debe colocar un referencia', '', 'warning');
                    return false;
                } else if (observacion_recojo == "") {
                    Swal.fire('Debe colocar una observacion', '', 'warning');
                    return false;
                } /*else if (gm_link == "") {
                    Swal.fire('Debe colocar el link de Google Maps', '', 'warning');
                    return false;
                }*/
                else if (sustento_recojo == "") {
                    Swal.fire('Debe colocar un sustento', '', 'warning');
                    return false;
                } else


                    var fd_courier = new FormData();
                fd_courier.append('Nombre_recibe', Nombre_recibe);
                fd_courier.append('celular_id', celular_id);
                fd_courier.append('direccion_recojo', direccion_recojo);
                fd_courier.append('referencia_recojo', referencia_recojo);
                fd_courier.append('observacion_recojo', observacion_recojo);
                fd_courier.append('gm_link', gm_link);
                fd_courier.append('direccion_entrega', direccion_entrega);
                fd_courier.append('sustento_recojo', sustento_recojo);
                fd_courier.append('pedido_concatenado', pedido_concatenado);
                fd_courier.append('distrito_recojo', distrito_recojo);

                $.ajax({
                    processData: false,
                    contentType: false,
                    data: fd_courier,
                    type: 'POST',
                    url: "{{ route('registrar_recojer_pedido') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-recojo-pedidos").modal("hide");
                        $('#tablabandejapedidos').DataTable().ajax.reload();
                    }
                });

            });


            $('#modal-listclientes').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var cliente = $('#clienteid').val()
                var pedido = $('#pedidoid').val()
                var pedidosNotIn = $('#pedido_concatenado').val()
                console.log('valores negados: ', pedidosNotIn);


                tabla_pedidos.destroy();
                tabla_pedidos = $('#tabla-listar-clientes').DataTable({
                    responsive: true,
                    "bPaginate": true,
                    "bFilter": true,
                    "bInfo": false,
                    'ajax': {
                        url: "{{ route('cargar.recojolistclientes') }}",
                        'data': {
                            "cliente_id": cliente,
                            "pedido": pedido,
                            "pedidosNotIn": pedidosNotIn,
                        },
                        "type": "get",
                    },
                    columnDefs: [{
                        'orderable': false,
                        'className': 'select-checkbox',
                        'targets': [0], /* column index */
                        'orderable': false, /* true or false */
                    }],
                    ColumnDefs: [{
                        'targets': [0],
                        'orderable': false,
                    }],
                    columns: [
                        {
                            "data": "pedidoid",
                            'targets': 0,
                            'checkboxes': {
                                'selectRow': false
                            },
                            defaultContent: '',
                            orderable: false,
                            sWidth: '5%',
                        },
                        {data: 'codigo', name: 'codigo', sWidth: '40%',},
                        {
                            "data": 'nombre_empresa',
                            "name": 'nombre_empresa',
                            "render": function (data, type, row, meta) {
                                return data;
                            },
                            sWidth: '40%',
                        },
                    ],
                    'select': {
                        'style': 'multi',
                        selector: 'td:first-child'
                    },
                    order: [[1, 'asc']]
                });

            })


            $(document).on("change", "#departamento", function () {

            });


            tabla_pedidos = $('#tabla-listar-clientes').DataTable({
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                columns:
                    [
                        {
                            data: 'pedidoid'
                        },
                        {
                            nane: 'clienteid'
                        },
                        {
                            data: 'codigo'
                        }
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
                }
            });


            $('#celular_recojo').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#datatable-clientes-lista-recojer tbody').on('click', 'button.elegir', function () {
                var data = tablaClienteLista.row($(this).parents('tr')).data();
                console.log(data);
                console.log("The ID is: " + data.id + " user id : " + data.user_id + " celular:" + data.celular + " action" + data.action);
                //disparar la otra tabla
                //pinto la clase span

                $(".card_clientes").hide()
                $(".card_pedidos").show()
                $(".card_form").show()

                $("span.nombre_cliente_recojo").html(data.nombre)
                $("#recojo_cliente").val(data.id)
                $("#recojo_cliente_name").val(data.nombre)

                $("#recojo_pedido").val("")
                $("#recojo_pedido_codigo").val("")
                $("#recojo_pedido_grupo").val("")

                $("span.destino_recojo").html("");
                $("span.distrito_recojo").html("");
                $("span.direccion_recojo").html("");

                $('#datatable-pedidos-lista-recojer').DataTable().clear().destroy();

                tablaPedidosLista = $('#datatable-pedidos-lista-recojer').DataTable({
                    ...configDataTableLanguages,
                    processing: true,
                    stateSave: false,
                    serverSide: true,
                    searching: true,
                    "order": [[0, "desc"]],
                    createdRow: function (row, data, dataIndex) {
                    },
                    ajax: {
                        url: "{{ route('pedidos.recoger.clientes.pedidos') }}",
                        data: function (d) {
                            d.length = 5;
                            d.cliente_id = data.id;
                        },
                    },
                    columns:
                        [
                            {data: 'id', name: 'id', "visible": false},
                            {data: 'codigo', name: 'codigo',},
                            {data: 'condicion_envio', name: 'condicion_envio',},
                            {data: 'action', name: 'action',},
                        ],
                });
                $("#distrito_recoger").val("").selectpicker("refresh")
            });

            $(document).on("click", ".btn-charge-history", function () {
                console.log($("#recojo_cliente").val())
                let clienteid = $("#recojo_cliente").val();
                if (clienteid != '') {
                    //cargar modal
                    //
                    $("#modal-historico-recojo").modal("show");
                }
            })

            $('#datatable-pedidos-lista-recojer tbody').on('click', 'button.elegir', function () {
                var data = tablaPedidosLista.row($(this).parents('tr')).data();
                console.log(data);
                $("span.nombre_cliente_recojo").html(data.nombre)
                $("#recojo_pedido").val(data.id)
                $("#recojo_pedido_codigo").val(data.codigo)

                $("#recojo_pedido_grupo").val(((data.direccion_grupo == null) ? 'SIN GRUPO' : data.direccion_grupo))
                $("span.destino_recojo").html(data.env_destino);
                $("span.distrito_recojo").html(data.env_distrito);
                $("span.direccion_recojo").html(data.env_direccion);
            })


            $('#modal-recoger-sobre').on('show.bs.modal', function (event) {
                $(".card_clientes").show()
                $(".card_pedidos").hide()
                $(".card_form").hide()

                $("#recojo_cliente").val("")
                $("#recojo_cliente_name").val("")
                $("#recojo_pedido").val("")
                $("#recojo_pedido_codigo").val("")
                $("#recojo_pedido_grupo").val("")
                $("#distrito_recoger").val("").selectpicker("refresh")
                $("#recojo_pedido_direccion").val("")
                $("#recojo_pedido_quienrecibe_nombre").val("")
                $("#recojo_pedido_quienrecibe_celular").val("")
                $("#recojo_pedido_direccion").val("")
                $("#recojo_pedido_referencia").val("")
                $("#recojo_pedido_observacion").val("")


                $('#datatable-clientes-lista-recojer').DataTable().clear().destroy();

                tablaClienteLista = $('#datatable-clientes-lista-recojer').DataTable({
                    ...configDataTableLanguages,
                    processing: true,
                    stateSave: false,
                    serverSide: true,
                    searching: true,
                    lengthMenu: [
                        [5, -1],
                        [5, 'All'],
                    ],
                    "order": [[0, "desc"]],
                    createdRow: function (row, data, dataIndex) {
                    },
                    ajax: {
                        url: "{{ route('pedidos.recoger.clientes') }}",
                        data: function (d) {
                            //d.length=5;
                            d.user_id = $("#user_id").val();
                        },
                    },
                    columns: [
                        {data: 'id', name: 'id', "visible": false},
                        {data: 'nombre', name: 'nombre',},
                        {data: 'celular', name: 'celular',},
                        {data: 'action', name: 'action',},
                    ],
                });
            });

            window.ocultar_div_modal_correccion_pedidos = function () {
                console.log("ocultar div")
                $("#modal-correccionpedido-pc-container").hide();
                $("#form-correccionpedido-pc input").val("");
                $("#form-correccionpedido-pc img").attr('src', '');
                $("#form-correccionpedido-pc textarea").val("");
                $("#modal-correccionpedido-f-container").hide();
                $("#form-correccionpedido-f input").val("");
                $("#form-correccionpedido-f textarea").val("");
                $("#modal-correccionpedido-g-container").hide();
                $("#form-correccionpedido-g input").val("");
                $("#form-correccionpedido-g textarea").val("");
                $("#modal-correccionpedido-b-container").hide();
                $("#form-correccionpedido-b input").val("");
                $("#form-correccionpedido-b textarea").val("");
            }

            $(document).on('click',
                "button#btn_correccion_pc,button#btn_correccion_f,button#btn_correccion_g,button#btn_correccion_b",
                function (e) {
                    ocultar_div_modal_correccion_pedidos();
                    switch (e.target.id) {
                        case 'btn_correccion_pc':
                            $("#modal-correccionpedido-pc-container").show();
                            break;
                        case 'btn_correccion_f':
                            $("#modal-correccionpedido-f-container").show();
                            break;
                        case 'btn_correccion_g':
                            $("#modal-correccionpedido-g-container").show();
                            break;
                        case 'btn_correccion_b':
                            $("#modal-correccionpedido-b-container").show();
                            break;
                    }

                })

            $(document).on("submit", "form.correccion", function (e) {
                e.preventDefault();
                var form = null;
                var formData = null;
                console.log(e.target.id)
                if (e.target.id == 'form-correccionpedido-pc') {
                    let cant_sustento_pc = $("textarea[name='sustento-pc']").val().length;
                    dataForm_pc.sustento_pc = $("textarea[name='sustento-pc']").val()
                    let cant_detalle_pc = $("textarea[name='detalle-pc']").val().length;
                    dataForm_pc.detalle_pc = $("textarea[name='detalle-pc']").val();
                    if (cant_sustento_pc == 0) {
                        Swal.fire('Error', 'No se puede ingresar un sustento vacio', 'warning').then(function () {
                            console.log("before")
                            $("textarea[name='sustento-pc']").focus()
                        });
                        return false;
                    } else if (cant_detalle_pc == 0) {
                        Swal.fire('Error', 'No se puede ingresar un detalle vacio', 'warning');
                        return false;
                    }
                    if (dataForm_pc.correcion_pc_captura === undefined) {
                        Swal.fire('Error', 'No se puede ingresar una captura vacia', 'warning');
                        return false;
                    }
                } else if (e.target.id == 'form-correccionpedido-f') {
                    let cant_sustento_f = $("textarea[name='sustento-f']").val().length;
                    dataForm_f.sustento_f = $("textarea[name='sustento-f']").val()
                    let cant_facturas_f = $('input[name="correcion_f_facturas[]"]')[0].files.length
                    //dataForm_f.correcion_f_facturas=$('input[name="correcion_f_facturas"]')[0].files;
                    let cant_detalle_f = $("textarea[name='detalle-f']").val().length;
                    dataForm_f.detalle_f = $("textarea[name='detalle-f']").val()


                    if (cant_sustento_f == 0) {
                        Swal.fire('Error', 'No se puede ingresar un sustento vacio', 'warning');
                        return false;
                    } else if (cant_facturas_f == 0) {
                        Swal.fire('Error', 'No se puede ingresar facturas vacias', 'warning');
                        return false;
                    } else if (cant_detalle_f == 0) {
                        Swal.fire('Error', 'No se puede ingresar un detalle vacio', 'warning');
                        return false;
                    }
                } else if (e.target.id == 'form-correccionpedido-g') {
                    let cant_sustento_g = $("textarea[name='sustento-g']").val().length
                    dataForm_g.sustento_g = $("textarea[name='sustento-g']").val()
                    let cant_adjuntos_g = $('input[name="correcion_g_adjuntos[]"]')[0].files.length

                    let cant_detalle_g = $("textarea[name='detalle-g']").val().length;
                    dataForm_g.detalle_g = $("textarea[name='detalle-g']").val()
                    if (cant_sustento_g == 0) {
                        Swal.fire('Error', 'No se puede ingresar un sustento vacio', 'warning');
                        return false;
                    } else if (cant_adjuntos_g == 0) {
                        Swal.fire('Error', 'No se puede ingresar una adjuntos vacios', 'warning');
                        return false;
                    } else if (cant_detalle_g == 0) {
                        Swal.fire('Error', 'No se puede ingresar un detalle vacio', 'warning');
                        return false;
                    }
                } else if (e.target.id == 'form-correccionpedido-b') {
                    let cant_sustento_b = $("textarea[name='sustento-b']").val().length;
                    dataForm_b.sustento_b = $("textarea[name='sustento-b']").val()
                    let cant_adjuntos_b = $('input[name="correcion_b_adjuntos[]"]')[0].files.length
                    if (cant_sustento_b == 0) {
                        Swal.fire('Error', 'No se puede ingresar un sustento vacio', 'warning');
                        return false;
                    } else if (cant_adjuntos_b == 0) {
                        Swal.fire('Error', 'No se puede ingresar una adjuntos vacios', 'warning');
                        return false;
                    }
                }
                switch (e.target.id) {
                    case 'form-correccionpedido-pc':
                        dataForm_pc.opcion = 1
                        dataForm_pc.modalcorreccionpedido = $('#modalcorreccionpedido').val();
                        formData = dataForm_pc
                        break;
                    case 'form-correccionpedido-f':
                        dataForm_f.opcion = 2
                        dataForm_f.modalcorreccionpedido = $('#modalcorreccionpedido').val();
                        formData = dataForm_f
                        break;
                    case 'form-correccionpedido-g':
                        dataForm_g.opcion = 3
                        dataForm_g.modalcorreccionpedido = $('#modalcorreccionpedido').val();
                        formData = dataForm_g
                        break;
                    case 'form-correccionpedido-b':
                        dataForm_b.opcion = 4
                        dataForm_b.modalcorreccionpedido = $('#modalcorreccionpedido').val();
                        formData = dataForm_b
                        break;
                }
                var fd = new FormData();
                Object.keys(formData).forEach(function (key) {
                    if (key == 'file' && formData[key]) {
                        fd.append(key, formData[key], formData[key].name);
                    } else {
                        fd.append(key, formData[key]);
                    }
                })
                switch (e.target.id) {
                    case 'form-correccionpedido-pc':
                        break;
                    case 'form-correccionpedido-f':
                        let files_f_f = $('[name="correcion_f_facturas[]');
                        if (files_f_f[0].files.length > 0) {
                            for (let i in files_f_f[0].files) {
                                fd.append('correcion_f_facturas[]', files_f_f[0].files[i]);
                            }
                        }
                        let files_a_f = $('[name="correcion_f_adjuntos[]');
                        if (files_a_f[0].files.length > 0) {
                            for (let i in files_a_f[0].files) {
                                fd.append('correcion_f_adjuntos[]', files_a_f[0].files[i]);
                            }
                        }
                        break;
                    case 'form-correccionpedido-g':
                        let files_a_g = $('[name="correcion_g_adjuntos[]');
                        if (files_a_g[0].files.length > 0) {
                            for (let i in files_a_g[0].files) {
                                fd.append('correcion_g_adjuntos[]', files_a_g[0].files[i]);
                            }
                        }
                        break;
                    case 'form-correccionpedido-b':
                        let files_a_b = $('[name="correcion_b_adjuntos[]');
                        if (files_a_b[0].files.length > 0) {
                            for (let i in files_a_b[0].files) {
                                fd.append('correcion_b_adjuntos[]', files_a_b[0].files[i]);
                            }
                        }
                        break;
                }
                console.log(fd);
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('ajax_modal_correccionpedidos') }}",
                    beforeSend: function () {
                        $('button:submit').prop("disabled", true)
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.html != "0") {
                            $("#modal-correccion-pedidos").modal("hide");
                            //recargar tabla
                            $('#tablabandejapedidos').DataTable().ajax.reload();
                            var urlpdf = '{{ route('correccionPDF', ':id') }}';
                            urlpdf = urlpdf.replace(':id', data.codigo);
                            window.open(urlpdf, '_blank');
                            console.log(data.codigo);

                            console.log("response 1")
                        } else {
                            console.log("response 0")
                        }
                    },
                    error: function (xhr) {
                        console.log("Error occured.please try again");
                        //$(placeholder).append(xhr.statusText + xhr.responseText);
                        //$(placeholder).removeClass('loading');
                    },
                })
            });

            $(document).on("click", "#form-correccionpedido-pc #attachmentfiles", function () {
                console.log("creando input virtual")
                var file = document.createElement('input');
                file.type = 'file';
                file.click()
                file.addEventListener('change', function (e) {
                    console.log("change")
                    if (file.files.length > 0) {
                        $('#form-correccionpedido-pc').find('.result_picture').css('display', 'block');
                        console.log(URL.createObjectURL(file.files[0]))
                        dataForm_pc.correcion_pc_captura = file.files[0]
                        $('#form-correccionpedido-pc').find('.result_picture>img').attr('src', URL.createObjectURL(file.files[0]))
                    }
                })
            })

            window.document.onpaste = function (event) {
                var items = (event.clipboardData || event.originalEvent.clipboardData).items;
                console.log(items);
                console.log((event.clipboardData || event.originalEvent.clipboardData));
                var files = []
                for (index in items) {
                    var item = items[index];
                    if (item.kind === 'file') {
                        // adds the file to your dropzone instance
                        var file = item.getAsFile()
                        files.push(file)
                    }
                }
                if (files.length > 0) {
                    $('#form-correccionpedido-pc').find('.result_picture').css('display', 'block')
                    console.log(URL.createObjectURL(files[0]))
                    $('#form-correccionpedido-pc').find('.result_picture>img').attr('src', URL.createObjectURL(files[0]))
                    dataForm_pc.correcion_pc_captura = files[0]
                }
            }


            if (localStorage.getItem("search_tabla") === null) {
                //...

            } else {
                //si existe la variable  localstorage

            }

            $('#modal-delete').on('hidden.bs.modal', function (event) {
                $("#motivo").val('')
                $("#anulacion_password").val('')
                $("#attachments").val(null)
            })
            $('#modal-delete').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('delete')//id  basefria
                var idresponsable = button.data('responsable')//id  basefria
                var idcodigo = button.data('codigo')
                //console.log(idunico);
                $("#hiddenIDdelete").val(idunico);
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }
                //solo completo datos
                //hiddenId
                //


                $(".textcode").html(idcodigo);
                $("#motivo").val('');
                $("#responsable").val(idresponsable);

            });

            $('#modal-restaurar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('restaurar')
                var idcodigo = button.data('codigo')
                console.log("unico " + idunico)
                $("#hiddenIDrestaurar").val(idunico);
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }

                $(".textcode").html(idcodigo);

            });

            function openConfirmDownloadDocuments(action, idc, codigo) {
                $.confirm({
                    theme: 'material',
                    title: `
<h5>Detalle de atencion de pedido <b class="allow-copy">${codigo}</b></h5>
`,
                    columnClass: 'col-md-6 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    buttons: {
                        cancel: {
                            text: 'Cerrar',
                            btnClass: 'btn-outline-dark',
                            action: function () {
                                return true
                            }
                        },
                    },
                    draggable: false,
                    backgroundDismiss: function () {
                        return false; // modal wont close.
                    },
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: action,
                            dataType: 'json',
                            method: 'get',
                        }).done(function (response) {
                            var html = `<div class="list-group">`
                            // html += `<li class="list-group-item bg-dark">Codigo: ${codigo}</li>`
                            if (response.sustento) {
                                html += `<li class="list-group-item text-wrap">
<h6 class="alert alert-warning text-center font-weight-bold">Los archivos de este pedido fueron modificados</h6>
<b>Sustento del facturador:</b>
<textarea readonly class="form-control w-100" rows="6" style=" color: red; font-weight: bold; background: white; ">${response.sustento}</textarea>
</li>`
                            }
                            html += `<li class="list-group-item text-left"><h5 class="font-weight-bold">Adjuntos descargados</h5></li>`
                            html += response.data.map(function (item) {
                                return `<li class="list-group-item">
<a href="${item.link}" download class="d-flex justify-content-between"><span>Descargar </span> <span><i class="fa fa-file mx-2"></i>${item.adjunto} <i class="fa fa-download mx-2"></i></span></a>
</li>`
                            }).join('')

                            html += `</div>`
                            self.setContentAppend(html);
                            if (response.cliente) {
                                self.setTitle(`
<div class="d-flex justify-content-between w-100 align-content-center">
<h5>Cliente: <b class="allow-copy">${response.cliente.nombre}</b></h5>
<h5 class="text-right">Telf: <b class="allow-copy">${response.cliente.celular}</b></h5>
</div>
<hr class="my-0">
<h5>Detalle de atencion de pedido <b class="allow-copy">${response.detalle_pedido.codigo}</b></h5>
`)
                            }
                        }).fail(function () {
                            self.setContent('Ocurrio un error.');
                        });
                    },
                    onContentReady: function () {
                        const self = this
                        self.$content.find('#copy_pedido_buttom').click(function () {
                            self.$content.find('#copy_pedido_text').select();
                            window.document.execCommand("copy");
                        })
                    },
                });
            }

            var detailRows = [];
            tablaBandejaPedidos = $('#tablabandejapedidos').DataTable({
                dom: 'Blfrtip',
                processing: true,
                serverSide: true,
                searching: true,
                //stateSave: true,
                order: [[9, "desc"]],
                ajax: "{{ route('pedidostabla') }}",
                createdRow: function (row, data, dataIndex) {
                    if (data["estado"] == "1") {
                        if (data.pendiente_anulacion == 1 && data.vtipoAnulacion!='F') {
                            $('td', row).css('background', 'red').css('font-weight', 'bold');
                        }else if (data.pendiente_anulacion == 1 && data.vtipoAnulacion=='F'){
                            $('td', row).css('background', 'RosyBrown').css('font-weight', 'bold');
                        }
                    } else {
                        $(row).addClass('textred');
                    }
                },
                rowCallback: function (row, data, index) {
                    var pedidodiferencia = data.diferencia;

                    if (data.condicion_code == 4 || data.estado == 0) {
                        $('td:eq(10)', row).css('background', '#ff7400').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                    } else {
                        if (pedidodiferencia == null) {
                            $('td:eq(10)', row).css('background', '#ca3a3a').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                        } else {
                            if (pedidodiferencia > 3 && pedidodiferencia < 19) {
                                //naranja
                                $('td:eq(10)', row).css('background', '#FBBA72').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                            } else if (pedidodiferencia >= 19) {
                                //rojo
                                $('td:eq(10)', row).css('background', '#ca3a3a').css('color', '#ffffff').css('text-align', 'center').css('font-weight', 'bold');
                            } else {
                                //verde
                                $('td:eq(10)', row).css('background', '#44c24b').css('text-align', 'center').css('font-weight', 'bold');
                            }
                        }
                    }

                    $('[data-jqconfirm]', row).click(function () {
                        $.confirm({
                            theme: 'material',
                            columnClass: 'large',
                            title: 'Editar direccion de envio',
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    url: '{{route('pedidos.envios.get-direccion')}}?pedido_id=' + data.id,
                                    dataType: 'json',
                                    method: 'get'
                                })
                                    .done(function (response) {
                                        console.log(response);

                                        self.setContent(response.html);
                                        if (!response.success) {
                                            self.$$confirm.hide();
                                        }
                                        //self.setContent('Description: ' + response.description);
                                        //self.setContentAppend('<br>Version: ' + response.version);
                                        //self.setTitle(response.name);
                                    })
                                    .fail(function (e) {
                                        console.error(e)
                                        self.setContent('Ocurrio un error');
                                    });
                            },
                            buttons: {
                                confirm: {
                                    text: 'Actualizar',
                                    btnClass: 'btn-success',
                                    action: function () {
                                        var self = this;
                                        console.log(self.$content.find('form')[0])
                                        const form = self.$content.find('form')[0];
                                        const data = new FormData(form)

                                        /*if (form.rotulo.files.length > 0) {
                                            data.append('rotulo', form.rotulo.files[0])
                                        }*/
                                        if (data.get('celular').length != 9) {
                                            $.alert({
                                                title: 'Alerta!',
                                                content: '¡El numero de celular debe tener 9 digitos!',
                                            });
                                            return false

                                        }

                                        self.showLoading(true)
                                        $.ajax({
                                            data: data,
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            url: "{{route('pedidos.envios.update-direccion')}}",
                                        }).always(function () {
                                            self.close();
                                            $('#tablabandejapedidos').DataTable().ajax.reload();
                                        });
                                        return false
                                    }
                                },
                                cancel: function () {

                                },
                            },
                            onContentReady: function () {

                                var self = this;
                                //console.log(self.$content.find('form')[0])
                                const form = self.$content.find('form')[0];
                                const data = new FormData(form)
                                console.log("aa")
                                console.log(form)
                                //this.buttons.ok.disable();
                                //$(select).selectpicker('refresh');
                                //form.distrito.addClass('selectpicker')
                                self.$content.find('select#distrito').selectpicker('refresh');
                                //console.log("a")

                                //$(self.$).selectpicker();
                                //console.log("aa");
                            }
                        });
                    })

                    $('[data-verforotos]', row).click(function () {
                        var data = $(this).data('verforotos')
                        $.dialog({
                            columnClass: 'xlarge',
                            title: 'Fotos confirmadas',
                            type: 'green',
                            content: function () {
                                return `<div class="row">
${data.foto1 ? `
<div class="col-md-4">
<div class="card">
<div class="card-header d-none"><h5>Foto de los sobres</h5></div>
<div class="card-body">
<img src="${data.foto1}" class="w-100">
</div>
</div>
</div>
` : ''}
${data.foto2 ? `
<div class="col-md-4">
<div class="card">
<div class="card-header d-none"><h5>Foto del domicilio</h5></div>
<div class="card-body">
<img src="${data.foto2}" class="w-100">
</div>
</div>
</div>
` : ''}
${data.foto3 ? `
<div class="col-md-4">
<div class="card">
<div class="card-header d-none"><h5>Foto de quien recibe</h5></div>
<div class="card-body">
<img src="${data.foto3}" class="w-100">
</div>
</div>
</div>
` : ''}
</div>`
                            }
                        })

                    })

                    $("[data-jqconfirmdetalle=jqConfirm]", row).on('click', function (e) {
                        openConfirmDownloadDocuments($(e.target).data('target'), $(e.target).data('idc'), $(e.target).data('codigo'))
                    })
                },
                initComplete: function (settings, json) {
                },
                columns: [
                    /*{
                      class: 'details-control',
                      orderable: false,
                      data: null,
                      defaultContent: '',
                      "searchable": false
                    },*/
                    // CODIGO
                    {data: 'codigos', name: 'codigos',},
                    {
                        data: 'celulares',
                        name: 'celulares',
                        render: function (data, type, row, meta) {
                            if (row.icelulares != null) {
                                return row.celulares + '-' + row.icelulares + ' - ' + row.nombres;
                            } else {
                                return row.celulares + ' - ' + row.nombres;
                            }

                        },
                    },
                    {data: 'empresas', name: 'empresas',},
                    {data: 'cantidad', name: 'cantidad', render: $.fn.dataTable.render.number(',', '.', 2, ''),},
                    {data: 'users', name: 'users',},
                    {data: 'ruc', name: 'ruc',},
                    {data: 'fecha', name: 'fecha',},
                    {data: 'fecha_up', name: 'fecha_up', "visible": false,},
                    {data: 'total', name: 'total', render: $.fn.dataTable.render.number(',', '.', 2, '')},
                    {
                        data: 'condicion_pa', name: 'condicion_pa', render: function (data, type, row, meta) {
                            if (row.condiciones == 'ANULADO' || row.condicion_code == 4 || row.estado == 0) {
                                /*return 'ANULADO';*/
                                if (row.estado == '0' && row.condicion_code != '5'){
                                    return 'ANULADO';
                                }else if(row.condicion_code == '5'){
                                    return 'ANULADO PARCIAL';
                                }
                            } else {
                                if (row.condicion_pa == null) {
                                    return 'SIN PAGO REGISTRADO';
                                } else {
                                    if (row.condicion_pa == '0') {
                                        return '<p>SIN PAGO REGISTRADO</p>'
                                    }
                                    if (row.condicion_pa == '1') {
                                        return '<p>ADELANTO</p>'
                                    }
                                    if (row.condicion_pa == '2') {
                                        return '<p>PAGO</p>'
                                    }
                                    if (row.condicion_pa == '3') {
                                        return '<p>ABONADO</p>'
                                    }
                                    //return data;
                                }
                            }

                        }
                    },
                    /*{data: 'condiciones_aprobado', name: 'condiciones_aprobado', render: function (data, type, row, meta) {
                        if (row.condicion_code == 4 || row.estado == 0) {
                          return 'ANULADO';
                        }
                        if (data != null) {
                          return data;
                        } else {
                          return 'SIN REVISAR';
                        }

                      }
                    },*/
                    /*
                    {
                      //estado del sobre
                      data: 'envio',
                      name: 'envio',
                      render: function ( data, type, row, meta ) {
                        if(row.envio==null){
                          return '';
                        }else{
                          {
                            if(row.envio=='1'){
                              return '<span class="badge badge-success">Enviado</span><br>'+
                                      '<span class="badge badge-warning">Por confirmar recepcion</span>';
                            }else if(row.envio=='2'){
                              return '<span class="badge badge-success">Enviado</span><br>'+
                                      '<span class="badge badge-info">Recibido</span>';
                            }else{
                              return '<span class="badge badge-danger">Pendiente</span>';
                            }
                          }


                        }
                      }
                    },  */
                    //{data: 'responsable', name: 'responsable', },//estado de envio

                    {data: 'condicion_pa', name: 'condicion_pa', 'visible': false},
                    {data: 'condicion_envio', name: 'condicion_envio'},

                    /*
                    {
                      data: 'estado',
                      name: 'estado',
                      render: function ( data, type, row, meta ) {
                          if(row.estado==1){
                            return '<span class="badge badge-success">Activo</span>';
                          }else{
                            return '<span class="badge badge-danger">Anulado</span>';
                          }
                        }
                    },

                    */
                    {
                        data: 'diferencia',
                        name: 'diferencia',
                        render: function (data, type, row, meta) {
                            if (row.condicion_code == 4 || row.estado == 0) {
                                return '0';
                            }
                            if (row.diferencia == null) {
                                return 'NO REGISTRA PAGO';
                            } else {
                                if (row.diferencia > 0) {
                                    return row.diferencia;
                                } else {
                                    return row.diferencia;
                                }
                            }
                        }
                    },
                    //{data: 'responsable', name: 'responsable', },
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
                    "emptyTable": "No hay informaciÃ³n",
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
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
                /*        buttons: [
                          {
                            text: 'RECOGER',
                            className: 'btn btn-danger visible_button_recoger mb-4',
                            action: function (e, dt, node, config) {
                              $('#modal-recoger-sobre').modal("show");
                            }
                          }
                        ],*/
            });

            function charge_corrections(pedido_id) {
                //obtener datos por ajax
                var formData = new FormData();
                formData.append("pedido", pedido_id);

                $.ajax({
                    async: false,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('correccionesJson') }}",
                    success: function (res) {
                        console.log(res);
                        return res;
                    }
                });

                //return 'Detalles';

            }

            $('#tablabandejapedidos tbody').on('click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tablaBandejaPedidos.row(tr);

                var data = tablaBandejaPedidos.row($(this).closest('tr')).data()
                var idxio = detailRows.indexOf(data.id);
                console.log(idxio)
                var idx = data.id;
                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();
                    detailRows.splice(idx, 1);
                } else {
                    tr.addClass('details');
                    console.log(idx);
                    if (idxio === -1) {
                        detailRows.push(tr.attr('id'));
                    }

                    var formData = new FormData();
                    formData.append("pedido", idx);
                    //row.child('asdasd').show();

                    $.ajax({
                        url: "{{ route('correccionesJson') }}",
                        data: formData, processData: false, contentType: false, type: 'POST'
                    })
                        .done(function (data, textStatus, jqXHR) {
                            //code to handle data from webservice here.
                            console.log(data.html);
                            row.child(data.html).show()
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            //code to handle error here.
                        })
                        .always(function (data, textStatus, jqXHR) {
                            //this code will always execute regardless
                        });


                    //row.child(charge_corrections(idx)).show();

                }
            });

            tablaBandejaPedidos.on('draw', function () {
                detailRows.forEach(function (id, i) {
                    $('#' + id + ' td.details-control').trigger('click');
                });
            });


            $('#tablabandejapedidos_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });
            $(document).on("keypress", '#tablabandejapedidos_filter label input', function () {
                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));
            });

            $(document).on("blur", '#tablabandejapedidos_filter label input', function () {
                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));

            });

            $('#tablabandejapedidos_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });

            /*$("").on( 'search.dt', function () {
        $('#filterInfo').html( 'Currently applied global search: '+table.search() );
        } );*/

            $(document).on("submit", "#formdelete", function (evento) {
                evento.preventDefault();
                console.log("validar delete");
                var motivo = $("#motivo").val();
                var responsable = $("#responsable").val();
                var anulacion_password = $("#anulacion_password").val();

                if (motivo.length < 1) {
                    Swal.fire(
                        'Error',
                        'Ingrese el motivo para anular el pedido',
                        'warning'
                    )
                } else if (responsable == '') {
                    Swal.fire(
                        'Error',
                        'Ingrese el responsable de la anulación',
                        'warning'
                    )
                } else if (!anulacion_password) {
                    Swal.fire(
                        'Error',
                        'Ingrese la contraseña para autorizar la anulación',
                        'warning'
                    )
                } else {
                    //this.submit();
                    clickformdelete();
                }
            })

            $(document).on("submit", "#formrestaurar", function (evento) {
                evento.preventDefault();
                clickformrestaurar();
            });

        });
    </script>

    <script>
        function resetearcamposdelete() {
            $('#motivo').val("");
            $('#responsable').val("");
        }

        function clickformdelete() {
            console.log("action delete action")
            var formData = new FormData();//$("#formdelete").serialize();
            formData.append("hiddenID", $("#hiddenIDdelete").val())
            formData.append("motivo", $("#motivo").val())
            formData.append("responsable", $("#responsable").val())
            formData.append("anulacion_password", $("#anulacion_password").val())
            if ($("#attachments")[0].files.length > 0) {
                var attachments = Array.from($("#attachments")[0].files)
                attachments.forEach(function (file) {
                    formData.append("attachments[]", file, file.name)
                })
            }
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('pedidodeleteRequest.post') }}",
                data: formData,
                processData: false,
                contentType: false,
            }).done(function (data) {
                $("#modal-delete").modal("hide");
                resetearcamposdelete();
                $('#tablabandejapedidos').DataTable().ajax.reload();
            }).fail(function (err, error, errMsg) {
                console.log(arguments, err, errMsg)
                if (err.status == 401) {
                    Swal.fire(
                        'Error',
                        'No autorizado para poder anular el pedido, ingrese una contraseña correcta',
                        'error'
                    )
                } else {
                    Swal.fire(
                        'Error',
                        'Ocurrio un error: ' + errMsg,
                        'error'
                    )
                }
            });
        }

        function clickformrestaurar() {
            var formData = $("#formrestaurar").serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('pedidorestaurarRequest.post') }}",
                data: formData,
            }).done(function (data) {
                $("#modal-restaurar").modal("hide");
                //resetearcamposdelete();
                $('#tablabandejapedidos').DataTable().ajax.reload();
            });
        }

    </script>

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado' || session('info') == 'restaurado')
        <script>
            Swal.fire(
                'Pedido {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>
        //VALIDAR CAMPO RUC
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }

        //VALIDAR ANTES DE ENVIAR 2
        document.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById("formulario2")
            if (form) {
                form.addEventListener('submit', validarFormulario2);
            }
        });

        function validarFormulario2(evento) {
            evento.preventDefault();
            var agregarruc = document.getElementById('agregarruc').value;

            if (agregarruc == '') {
                Swal.fire(
                    'Error',
                    'Debe ingresar el número de RUC',
                    'warning'
                )
            } else if (agregarruc.length < 11) {
                Swal.fire(
                    'Error',
                    'El número de RUC debe tener 11 dígitos',
                    'warning'
                )
            } else {
                this.submit();
            }
        }
    </script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

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
            var table = $('#tablabandejapedidos').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });

            $(document).on("click", ".btnrrellenar_recojo", function () {
                var recupeardo_check = tabla_pedidos.column(0).checkboxes.selected();
                seleccion = [];
                seleccion.push($('#pedido_concatenado').val())
                $('#direcciones_add').append('');
                $.each(recupeardo_check, function (index, rowId) {
                    console.log("index " + index);
                    console.log("ID PEDIDO  es " + rowId);
                    seleccion.push(rowId);
                });
                var ids = [];
                $(".tabla-listar-clientes tr td input[type='checkbox']:checked").each(function () {
                    row = $(this).closest('tr');
                    ids.push({
                        codigo: row.find('td:eq(1)').text(),
                    });
                });
                seleccion = seleccion.join(',');
                $('#pedido_concatenado').val(seleccion); //setear un valor
                ids.forEach(function (pedido) {
                    $('#direcciones_add ul').append(`
                    <li>` + pedido.codigo + `</li>
                `);
                });

                console.log(seleccion);
            })

        });
    </script>
@stop
