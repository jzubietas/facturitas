@extends('adminlte::page')
@section('title', 'Estado del pedidos')
@section('content_header')
    <h1>Estado del pedidos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header text-center">
                    <h6 class="mb-6">PEDIDOS ATENDIDOS</h6>
                </div>
                <div class="card-body p-8">
                    <h4 class="text-center mb-0">
                        <b>{{$pedidos_atendidos}}</b>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header text-center">
                    <h6 class="mb-6">PEDIDO POR ATENDER</h6>
                </div>
                <div class="card-body p-8">
                    <h4 class="text-center mb-0">
                        <b>{{$pedidos_por_atender}}</b>
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="form-group">
                <label for="select_status_list">Selecionar estado</label>
                <select class="form-control" id="select_status_list">
                    <option value="atendendido">ATENDIDOS</option>
                    <option value="por_atender">POR ATENDER</option>

                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaPrincipal" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col" style="vertical-align: middle">Item</th>
                        <th scope="col" style="vertical-align: middle">Código</th>
                        <th scope="col" style="vertical-align: middle">Cliente</th>
                        <th scope="col" style="vertical-align: middle">Razón social</th>
                        <th scope="col" style="vertical-align: middle">Asesor</th>
                        <th scope="col" style="vertical-align: middle">Fecha de registro</th>{{--fecha hora--}}
                        <th scope="col" style="vertical-align: middle">Tipo de Banca</th>
                        <th scope="col" style="vertical-align: middle">Adjuntos</th>
                        <th scope="col" style="vertical-align: middle">Estado</th>
                        <th scope="col" style="vertical-align: middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('operaciones.modal.atenderid')
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
            $("#select_status_list").change(function () {
                $('#tablaPrincipal').DataTable().ajax.reload();
            })

            function openConfirmDownloadDocuments(action, idc, codigo) {
                $.confirm({
                    theme:'material',
                    title: '<h5>Archivos del pedido</h5> <h4 class="text-success"><b>' + codigo + '</b></h4>',
                    buttons: {
                        confirm: {
                            text: 'Confirmar descarga',
                            btnClass: 'btn-primary',
                            action: function () {
                                var $checkbox = this.$content.find('#enableCheckbox');

                                if ($checkbox.prop('checked')) {
                                    this.showLoading(true)
                                    var self = this
                                    $.post(action, {
                                        'action': 'confirm_download'
                                    }).done(function () {
                                        self.close();
                                        $.confirm('Descarga confirmada correctamente', 'Mensaje');
                                    }).fail(function () {
                                        self.hideLoading(true)
                                    }).always(function () {
                                        $('#tablaPrincipal').DataTable().ajax.reload();
                                    })
                                }
                                return false
                            }
                        },
                        cancel: {
                            text: 'Cancelar',
                            btnClass: 'btn-outline-dark',
                            action: function () {
                                return true
                            }
                        },
                    },
                    backgroundDismiss: function () {
                        return false; // modal wont close.
                    },
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: action,
                            dataType: 'json',
                            method: 'get'
                        }).done(function (response) {
                            var html = `<div class="list-group">`
                            html += `<li class="list-group-item"><b>Adjuntos de detalle de atencion</b></li>`
                            html += response.data.map(function (item) {
                                return `<li class="list-group-item"><a href="${item.link}" download>${item.adjunto}</a></li>`
                            }).join('')
                            html += `<li class="list-group-item">
<div class="checkbox"><label><input type="checkbox" id="enableCheckbox"> Termine de descargar</label></div>
</li>`
                            html += `</div>`
                            self.setContentAppend(html);
                        }).fail(function () {
                            self.setContent('Ocurrio un error.');
                        });
                    }
                });
            }

            /*$("#tablaPrincipal").bind("DOMSubtreeModified", function() {
                console.log("tree changed",arguments);
            });
            new MutationObserver(() => {
                console.log("tree changed",arguments);
            }).observe(document, {subtree: true, childList: true});
        */
            $('#tablaPrincipal').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('pedidos.estados.index',['ajax-datatable'=>1]) }}",
                    data: function (d) {
                        d.load_data = $("#select_status_list").val();
                    },
                },
                drawCallback: function (settings) {
                    setTimeout(function () {
                        $("[data-toggle=jqConfirm]").on('click', function (e) {
                            openConfirmDownloadDocuments($(e.target).data('target'), $(e.target).data('idc'), $(e.target).data('codigo'))
                        })
                    }, 100)
                },
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    if (data.pendiente_anulacion == 1) {
                        $('td', row).css('background', 'red').css('font-weight', 'bold');
                    }
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
                        //searchable: true
                    },
                    {data: 'empresas', name: 'empresas',},
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
                        sWidth: '15%',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return '';
                            } else {
                                if (data > 0) {
                                    data = '<a href="" data-target="#modal-veradjunto" data-adjunto=' + row.id + ' data-toggle="modal" ><button class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                                    return data;
                                } else {
                                    return '';
                                }
                            }

                        }
                    },
                    {
                        data: 'condicion_envio',
                        name: 'condicion_envio',
                        render: function (data, type, row, meta) {
                            if (row.pendiente_anulacion == 1) {
                                return '<span class="badge badge-success">' + '{{\App\Models\Pedido::PENDIENTE_ANULACION }}' + '</span>';
                            }
                            if (row.condicion_envio_code == {{\App\Models\Pedido::POR_ATENDER_INT }}) {
                                return '<span class="badge badge-warning border border-danger">' + '{{\App\Models\Pedido::POR_ATENDER }}' + '</span>';
                            } else if (row.condicion_envio_code == {{\App\Models\Pedido::EN_PROCESO_ATENCION_INT }}) {
                                return '<span class="badge badge-warning border border-success">' + '{{\App\Models\Pedido::EN_PROCESO_ATENCION }}' + '</span>';
                            } else if (row.condicion_envio_code == {{\App\Models\Pedido::ATENDIDO_OPE_INT}}) {
                                return '<span class="badge badge-success">' + '{{\App\Models\Pedido::ATENDIDO_OPE }}' + '</span>';
                            } else if (row.condicion_envio_code == {{\App\Models\Pedido::ANULADO_INT}}) {
                                return '<span class="badge badge-danger">' + '{{\App\Models\Pedido::ANULADO }}' + '</span>';
                            } else {
                                return '<span class="badge badge-info">' + row.condicion_envio + '</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '15%',
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


            function validarFormulario(evento) {
                var adjunto = document.getElementById('adjunto').files;
                var cant_compro = document.getElementById('cant_compro').value;
                if (adjunto.length == 0) {
                    Swal.fire(
                        'Error',
                        'Debe registrar almenos un documento adjunto',
                        'warning'
                    )
                    return false;
                } else if (cant_compro == '0') {
                    Swal.fire(
                        'Error',
                        'Cantidad de comprobantes enviados debe ser diferente de 0 (cero)',
                        'warning'
                    )

                    return false;
                }
                return true;
            }

            $(document).on("submit", "#formularioatender", function (evento) {
                evento.preventDefault();
                var status = validarFormulario(evento);
                if (!status) {
                    return;
                }

                let files = $('input[name="adjunto[]');
                //console.log(files)

                var imagen = $('input[type=file][name="adjunto[]"]')[0].files[0];
                // console.log(imagen)
                //return false;

                var data = new FormData(document.getElementById("formularioatender"));

                var fd = new FormData();

                if (files.length == 0) {
                    Swal.fire(
                        'Error',
                        'Debe ingresar el detalle del pedido',
                        'warning'
                    )
                    return false;
                }

                for (let i = 0; i < files.length; i++) {
                    fd.append('adjunto', $('input[type=file][name="adjunto[]"]')[0].files[0]);
                }

                //console.log(files);
                //return false;
                //fd.append( 'cant_compro', $("#cant_compro").val() );
                fd.append('cant_compro', files.length);
                fd.append('condicion', $("#condicion").val());
                fd.append('hiddenAtender', $("#hiddenAtender").val());

                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.atenderid') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-atender .textcode").text('');
                        $("#modal-atender").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();

                    }

                });
                console.log(fd);
            });

            $('#modal-atender').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('atender')
                $(".textcode").html("PED" + idunico);
                $("#hiddenAtender").val(idunico);

            });
        });
    </script>
@stop
