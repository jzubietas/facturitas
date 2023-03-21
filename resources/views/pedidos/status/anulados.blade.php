@extends('adminlte::page')
@section('title', 'Pedidos en proceso de anulación')
@section('content_header')
    <h1>Pedidos en proceso de anulación</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <table id="tablaOperacionesPendienteAnulacion" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">Item</th>
                    <th scope="col" class="align-middle">Código</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Fecha de Anulacion</th>
                    <th scope="col" class="align-middle">Tipo de Banca</th>
                    <th scope="col" class="align-middle">Adjuntos</th>
                    <th scope="col" class="align-middle">Atencion</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('operaciones.modal.confirmarAnular')
    @include('pedidos.modal.Verimagenatenciones')
@endsection

@section('css')
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    @if (session('info') == 'registrado')
        <script>
            Swal.fire(
                'RUC {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif


    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#tablaOperacionesPendienteAnulacion').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: "{{ route('pedidos.estados.anulados',['ajax-datatable'=>1]) }}",
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    if (  data.vtipoAnulacion!='F') {
                        $('td', row).css('background', 'red').css('font-weight', 'bold');
                    }else{
                        $('td', row).css('background', 'RosyBrown').css('font-weight', 'bold');
                    }
                    $("[data-toggle=jqconfirm]", row).click(function () {
                        const action = $(this).data('target')
                        const method = $(this).data('method')
                        $.confirm({
                            theme:'material',
                            type: 'red',
                            title: 'Rechazar anulación',
                            content:`¿Estas seguro de rechazar la solicitud de anulación?`,
                            buttons: {
                                rechazar: {
                                    btnClass: 'btn-red',
                                    action: function () {
                                        const self=this
                                        self.showLoading(true)
                                        $.ajax({
                                            type: method,
                                            url: action,
                                            data: data,
                                        }).done(function (data) {
                                            self.close()
                                            if (data.success) {
                                                Swal.fire(
                                                    'Mensaje',
                                                    'Solicitud de anulacion rechazada correctamente',
                                                    'success'
                                                )
                                            } else {
                                                Swal.fire(
                                                    'Mensaje',
                                                    'Pedido ya ha sido anulado',
                                                    'warning'
                                                )
                                            }
                                        }).always(function () {
                                            self.hideLoading(true)
                                            $('#tablaOperacionesPendienteAnulacion').DataTable().draw(false);
                                        });
                                    }
                                },
                                cancelar:{}
                            }
                        })
                    })
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            if (row.id < 10) {
                                return 'PED000' + row.id;
                            } else if (row.id < 100) {
                                return 'PED00' + row.id;
                            } else if (row.id < 1000) {
                                return 'PED0' + row.id;
                            } else {
                                return 'PED' + row.id;
                            }
                        }
                    },
                    {data: 'codigos', name: 'codigos',},
                    //{data: 'empresas', name: 'empresas', },
                    {data: 'users', name: 'users',},
                    {
                        data: 'fecha',
                        name: 'fecha',
                        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
                        //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ).format('HH:mm:ss'),
                    },
                    {data: 'tipo_banca', name: 'tipo_banca',},
                    {
                        data: 'imagenes',
                        name: 'imagenes',
                        orderable: false,
                        searchable: false,
                        sWidth: '10%',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return '';
                            } else {
                                if (data > 0) {
                                    data = '<a href="" data-target="#modal-veradjunto" data-adjunto=' + row.id + ' data-toggle="modal" ><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                                    return data;
                                } else {
                                    return '';
                                }
                            }

                        }
                    },
                    {data: 'adjunto', name: 'adjunto',},
                    {
                        data: 'condicion_code',
                        name: 'condicion_code',
                        sWidth: '10%',
                    },
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


            $('#modal_confirmar_anular').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                console.log(event.relatedTarget)
                console.log(button.data('pedido_id_code'))
                $("#anular_pedido_id").html(button.data('pedido_id_code'))
                $("#motivo_anulacion_text").html(button.data('pedido_motivo'))
                $("#anular_pedido_id").val(button.data('pedido_id'))
                $("#montot_anulacion_text").html(button.data('tatal_pedido'))
                $("#montoa_anulacion_text").html(button.data('tatal_anular'))
                var myString = button.data('tatal_anular');
                var stringLength = myString.length;
                if (stringLength<1){
                    $(".lblMontoAnular, .txtMontoAnular").hide();
                }
            })

            $('#modal_imagen_atenciones').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                console.log(event.relatedTarget)
                console.log(button.data('id_imagen_atencion'))
                var idunico = button.data('pedido_id')
                //recupera imagenes adjuntas
                $.ajax({
                    url: "{{ route('operaciones.veratencionanulacion',':id') }}".replace(':id', idunico),
                    data: idunico,
                    method: 'POST',
                    success: function (data) {
                        console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idunico)
                        $('#listado_adjuntos_ver').html("");
                        $('#listado_adjuntos_antes_ver').html(data);
                        console.log(data);
                    }
                });
            })

            $('#attachmentsButtom').click(function (event) {
                var files = Array.from($("#anularAttachments")[0].files);
                if (files.length == 0) {
                    Swal.fire(
                        'Error',
                        'Debe adjuntar almenos una nota de credito',
                        'warning'
                    )
                    return;
                }
                var data = new FormData();
                data.append("pedido_id", $("#anular_pedido_id").val())
                data.append("action", 'confirm_anulled')
                for (var i in files) {
                    if (files[i].name) {
                        data.append('attachments[' + i + ']', files[i], files[i].name)
                    }
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('pedidos.confirmar.anular') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    console.log('CONFIRMA N-C = ',data)
                    if (data.success) {
                        Swal.fire(
                            'Mensaje',
                            'Pedido anulado correctamente',
                            'success'
                        )
                    } else {
                        Swal.fire(
                            'Mensaje',
                            'Pedido ya ha sido anulado',
                            'warning'
                        )
                    }
                }).always(function () {
                    $('#modal_confirmar_anular').modal('hide')
                    $("#anularAttachments").val(null)

                    $('#tablaOperacionesPendienteAnulacion').DataTable().ajax.reload();
                });
            })

        });
    </script>
    <script>
    </script>
@stop
