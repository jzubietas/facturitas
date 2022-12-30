@extends('adminlte::page')

@section('title', 'Motorizado')

@section('content_header')
    <h1>
        Motorizado
    </h1>
@stop

@section('content')

    @include('envios.motorizado.modal.entregado')

    <div class="card">
        <div class="card-body">
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Fecha de Envio</th>
                    <th scope="col">Razón social</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Dirección de envío</th>
                    <th scope="col">Referencia</th>
                    <th scope="col">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

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
                order: [[0, "desc"]],
                ajax: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    if (data.destino2 == 'PROVINCIA') {
                        $('td', row).css('color', 'red')
                    }
                },
                columns: [
                    {
                        data: 'correlativo',
                        name: 'correlativo',
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
                        },
                    },
                    {data: 'identificador', name: 'identificador',},
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            return row.celular + '<br>' + row.nombre
                        },
                    },
                    {
                        data: 'fecha',
                        name: 'fecha',
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },
                    {
                        data: 'producto',
                        name: 'producto',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return 'SIN RUCS';
                            } else {
                                var numm = 0;
                                var returndata = '';
                                var jsonArray = data.split(",");
                                $.each(jsonArray, function (i, item) {
                                    numm++;
                                    returndata += numm + ": " + item + '<br>';

                                });
                                return returndata;
                            }
                        }
                    },
                    {data: 'destino', name: 'destino',},
                    {
                        data: 'direccion',
                        name: 'direccion',
                        render: function (data, type, row, meta) {
                            if (data != null) {
                                return data;
                            } else {
                                return '<span class="badge badge-info">REGISTRE DIRECCION</span>';
                            }
                        },
                    },
                    {
                        data: 'referencia',
                        name: 'referencia',
                        sWidth: '10%',
                        render: function (data, type, row, meta) {
                            var datal = "";
                            if (row.destino == 'LIMA') {
                                return data;
                            } else if (row.destino == 'PROVINCIA') {
                                var urladjunto = '{{ route("pedidos.descargargastos", ":id") }}'.replace(':id', data);
                                datal = datal + '<p><a href="' + urladjunto + '">' + data + '</a><p>';
                                return datal;
                            }
                        }
                    },
                    {data: 'condicion_envio', name: 'condicion_envio',},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '10%',
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

            $('#modal-motorizado-entregar').on('show.bs.modal', function (event) {
                //adjunta dos fotos
                var button = $(event.relatedTarget)
                var idunico = button.data('entregar')//
                console.log(idunico);
                var idcodigo = button.data('codigos')//
                $(".textcode").html(idcodigo);
                $("#hiddenMotorizadoEntregar").val(idunico)

            })

            $(document).on("change","#adjunto1",function(event){
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picture1").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $(document).on("change","#adjunto2",function(event){
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picture2").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $(document).on("submit", "#formulariomotorizadoentregar", function (evento) {
                evento.preventDefault();
                var fd2 = new FormData();

                fd2.append('hiddenMotorizadoEntregar', $('#hiddenMotorizadoEntregar').val());
                fd2.append('fecha_envio_doc_fis', $('#fecha_envio_doc_fis').val());
                fd2.append('fecha_recepcion', $('#fecha_recepcion').val());

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.confirmarmotorizado') }}",
                    success: function (data) {
                        $("#modal-motorizado-entregar").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();

                    }
                });
            });

            $(document).on("click", "#cerrarmotorizadoentregar", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                fd.append('hiddenMotorizadoEntregar', $("#hiddenMotorizadoEntregar").val());
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.confirmarmotorizadodismiss') }}",
                    success: function (data) {
                        //console.log(data);
                        $("#modal-motorizado-entregar .textcode").text('');
                        $("#modal-motorizado-entregar").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on("change", "#adjunto1", function (evento) {
                $("#cargar_adjunto1").trigger("click");
            });

            $(document).on("change", "#adjunto1", function (evento) {
                $("#cargar_adjunto2").trigger("click");
            });

            $(document).on("click", "#cargar_adjunto1", function (evento) {
                let idunico = $("#hiddenMotorizadoEntregar").val();
                var data = new FormData(document.getElementById("formulariomotorizadoentregar"));
                $("#loading_upload_attachment_file").show()
                $("#adjunto1").hide()
                $.ajax({
                    type: 'POST',
                    url: "{{ route('operaciones.updateatendersinconfirmar',':id') }}".replace(':id', idunico),
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#cargar_adjunto').prop("disabled", false);
                        $('#cargar_adjunto').text('Subir Informacion');
                        console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idunico)
                        $('#listado_adjuntos').html(data);
                    }
                })
                    .done(function (data) {
                        $("#adjunto").val(null)
                    })
                    .always(function () {
                        $("#adjunto").show()
                        $("#loading_upload_attachment_file").hide()
                    });


            });
        });
    </script>

@stop
