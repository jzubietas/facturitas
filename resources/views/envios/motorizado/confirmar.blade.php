@extends('adminlte::page')

@section('title', 'Confirmar Motorizados')

@section('content_header')
    <h1>
        Confirmar Motorizados
    </h1>
@stop

@section('content')

    @include('envios.motorizado.modal.entregadoconfirm')

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

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
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
                ajax: "{{ route('envios.motorizados.confirmar',['datatable'=>1]) }}",
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    if (data.destino2 == 'PROVINCIA') {
                        $('td', row).css('color', 'red')
                    }
                    $('[data-jqconfirm]', row).click(function () {
                        $.confirm({
                            type: 'red',
                            title: '¡Revertir Envio!',
                            content: 'Confirme si desea revertir el envio <b>'+data.codigos+'</b>',
                            buttons: {
                                ok:{
                                    text:'Si, confirmar',
                                    btnClass:'btn-red',
                                    action:function (){
                                        const self=this;
                                        self.showLoading(true)
                                        $.ajax({
                                            data: {
                                                envio_id:data.id
                                            },
                                            //operaciones.confirmar.revertir
                                            type: 'POST',
                                            url: "{{ route('operaciones.confirmarmotorizado.revertir') }}",
                                        }).always(function (){
                                            self.close()
                                            self.hideLoading(true)
                                            $('#tablaPrincipal').DataTable().ajax.reload();
                                        });
                                    }
                                },
                                cancel:{
                                    text:'No'
                                }
                            }
                        })
                    })
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

            $('#modal-motorizado-entregar-confirm').on('show.bs.modal', function (event) {
                //adjunta dos fotos
                var button = $(event.relatedTarget)
                var idunico = button.data('entregar-confirm')//
                //console.log(idunico);
                var idcodigo = button.data('codigos')//
                $(".textcode").html(idcodigo);
                $("#hiddenMotorizadoEntregarConfirm").val(idunico)

                let foto1 = button.data('imagen1');console.log(foto1);
                let foto2 = button.data('imagen2');
                let foto3 = button.data('imagen3');
                $(".foto1").attr("src", foto1);

                $(".foto2").attr("src", foto2);


                $(".foto3").attr("src", foto3);

                
                


               
            })

            $(document).on("submit", "#formulariomotorizadoentregarconfirm", function (evento) {
                evento.preventDefault();
                var fd2 = new FormData();

                fd2.append('hiddenMotorizadoEntregarConfirm', $('#hiddenMotorizadoEntregarConfirm').val());

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.confirmarmotorizadoconfirm') }}",
                    success: function (data) {
                        $("#modal-motorizado-entregar-confirm").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();

                    }
                });
            });

            $(document).on("click", "#cerrarmotorizadoentregarconfirm", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
                fd.append('hiddenMotorizadoEntregarConfirm', $("#hiddenMotorizadoEntregarConfirm").val());
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.confirmarmotorizadoconfirmdismiss') }}",
                    success: function (data) {
                        //console.log(data);
                        $("#modal-motorizado-entregar-confirm .textcode").text('');
                        $("#modal-motorizado-entregar-confirm").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>

@endpush
