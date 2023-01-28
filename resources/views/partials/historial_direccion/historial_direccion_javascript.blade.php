<script>
    var tablehistoricolima = null;
    var tablehistoricoprovincia = null;
    let tablaHistorialRecojo=null;
    let tablehistoricoenvio=null;

    let tablaClienteLista = null;
    let tablaPedidosLista = null;

    $(document).ready(function () {

        $('#datatable-historico-recojer tbody').on( 'click', 'button.elegir', function () {
            var data = tablaHistorialRecojo.row( $(this).parents('tr') ).data();
            console.log(data);
            $("span.nombre_cliente_recojo").html(data.nombre)
            $("#recojo_pedido").val(data.id)
        })

        tablehistoricolima = $('#tablaHistorialLima').DataTable({
            "bPaginate": false, "bFilter": false, "bInfo": false, "length": 3,
            columns:
                [
                    {data: 'nombre'},
                    {data: 'recibe'},
                    {data: 'direccion'},
                    {data: 'referencia'},
                    {data: 'distrito'},
                    {data: 'observacion'},
                    {data: null},
                ],
        });

        tablehistoricoenvio = $('#tablaHistorialenvio').DataTable({
            "bPaginate": false, "bFilter": false, "bInfo": false, "length": 3,
            columns:
                [
                    {data: 'nombre'},
                    {data: 'recibe'},
                    {data: 'direccion'},
                    {data: 'referencia'},
                    {data: 'distrito'},
                    {data: 'observacion'},
                    {data: null},
                ],
        });

        tablaHistorialRecojo = $('#datatable-historico-recojer').DataTable({
            ...configDataTableLanguages,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            columns: [
                {data: 'nombre', name: 'nombre'},
                {data: 'quienrecibe', name: 'quienrecibe',},
                {data: 'direccion', name: 'direccion',},
                {data: 'referencia', name: 'referencia',},
                {data: 'distrito', name: 'distrito',},
                {data: 'observacion', name: 'observacion',},
                {data: 'action', name: 'action',},
            ],
        });

        tablehistoricoprovincia = $('#tablaHistorialProvincia').DataTable({
            "bPaginate": false, "bFilter": false, "bInfo": false, "length": 3,
            columns:
                [
                    {data: 'id'}, {data: 'tracking'}, {data: 'numregistro'}, {data: null},
                ],
        });

        $('#modal-historico-recojo').on('show.bs.modal', function (event) {
            var data = tablaPedidosLista.row( $(this).parents('tr') ).data();
            console.log(data);
            $('#datatable-historial-recojer').DataTable().clear().destroy();
            tablaHistorialRecojo = $('#datatable-historial-recojer').DataTable({
                ...configDataTableLanguages,
                processing: true,
                stateSave: false,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                createdRow: function (row, data, dataIndex) {
                },
                ajax: {
                    url: "{{ route('pedidos.recoger.clientes.pedidos.historial') }}",
                    data: function (d) {
                        d.length = 5;
                        d.cliente_id = $("#recojo_cliente").val();
                    },
                },
                columns:
                    [
                        {data: 'nombre', name: 'nombre',},
                        {data: 'celular', name: 'celular',},
                        {data: 'direccion', name: 'direccion',},
                        {data: 'referencia', name: 'referencia',},
                        {data: 'distrito', name: 'distrito',},
                        {data: 'observacion', name: 'observacion',},
                        {data: 'action', name: 'action',},
                    ],
            });


        })

        $('#modal-historial-recojo').on('show.bs.modal', function (event) {
            if (tablehistoricoenvio != null) {
                tablehistoricoenvio.destroy();
            }
            tablehistoricoenvio = $('#tablaHistorialenvio').DataTable({
                "bPaginate": true,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                "pageLength": 5,
                "order": [[0, "asc"]],
                ajax: {
                    url: "{{ route('pedidos.recoger.clientes.pedidos.historial') }}",
                    data: function (d) {
                        d.length = 5;
                        d.cliente_id = $("#recojo_cliente").val();
                        d.provincialima='';
                    },
                },
                columns:
                    [
                        {
                            data: 'nombre',
                            name: 'nombre',
                            sWidth: '30%',
                        },
                        {
                            data: 'recibe',
                            name: 'recibe',
                            sWidth: '15%',
                        },
                        {
                            data: 'direccion',
                            name: 'direccion',
                            sWidth: '15%',
                        },
                        {
                            data: 'referencia',
                            name: 'referencia',
                            sWidth: '15%',
                        },
                        {
                            data: 'distrito',
                            name: 'distrito',
                            sWidth: '15%',
                        },
                        {
                            data: 'observacion',
                            name: 'observacion',
                            sWidth: '15%',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            sWidth: '20%',
                            /*render: function (data, type, row, meta) {
                                data = data +
                                    `<button class="btn btn-danger btn-sm button_provincia_lima" data-json='${JSON.stringify(row)}' data-provincia="${row.id}"><i class="fas fa-check-circle"></i></button>`;
                                return data;
                            },*/
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
                },
            });
        });

        $('#modal-historial-lima').on('show.bs.modal', function (event) {
            console.log(tablehistoricolima)
            if (tablehistoricolima != null) {
                tablehistoricolima.destroy();
            }
            $("#set_cliente_clear").hide()
            let provincialima = "LIMA";
            let clienteidlima = $("#modal-historial-lima-a").attr("data-cliente");
            tablehistoricolima = $('#tablaHistorialLima').DataTable({
                "bPaginate": true,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                "pageLength": 5,
                "order": [[0, "asc"]],
                'ajax': {
                    url: "{{ route('sobreenvioshistorial') }}",
                    'data': {"provincialima": provincialima, "cliente_id": clienteidlima},
                    "type": "get",
                },
                rowCallback: function (row, data, index) {
                    $('.button_provincia_lima', row).click(function (e) {
                        const json = $(this).data('json');
                        const selectedData = ((json && typeof json != 'string') ? json : JSON.parse($(this).data('json')))
                        console.log(selectedData)
                        var form = $("#formdireccion")[0]

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

                        $("#modal-historial-lima").modal('hide')
                        $("#set_cliente_clear").show()
                        $("#saveHistoricoLima").parent().hide()
                        $("#saveHistoricoLimaEditar").parent().show()
                    })
                },
                columns:
                    [
                        {
                            data: 'nombre',
                            name: 'nombre',
                            sWidth: '30%',
                            render: function (data, type, row, meta) {
                                return data;
                            }
                        },
                        {
                            data: 'recibe',
                            name: 'recibe',
                            sWidth: '15%',
                            render: function (data, type, row, meta) {
                                return data;
                            }
                        },
                        {
                            data: 'direccion',
                            name: 'direccion',
                            sWidth: '15%',
                            render: function (data, type, row, meta) {
                                return data;
                            }
                        },
                        {
                            data: 'referencia',
                            name: 'referencia',
                            sWidth: '15%',
                            render: function (data, type, row, meta) {
                                return data;
                            }
                        },
                        {
                            data: 'distrito',
                            name: 'distrito',
                            sWidth: '15%',
                            render: function (data, type, row, meta) {
                                return data;
                            }
                        },
                        {
                            data: 'observacion',
                            name: 'observacion',
                            sWidth: '15%',
                            render: function (data, type, row, meta) {
                                return data;
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            sWidth: '20%',
                            render: function (data, type, row, meta) {
                                data = data +
                                    `<button class="btn btn-danger btn-sm button_provincia_lima" data-json='${JSON.stringify(row)}' data-provincia="${row.id}"><i class="fas fa-check-circle"></i></button>`;
                                return data;
                            },
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
                },
            });

        });


    })

</script>
