@extends('adminlte::page')

@section('title', 'Confirmar Motorizados')

@section('content_header')
    <h1>
        Confirmar Motorizados

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
        @include('envios.motorizado.modal.exportar_motorizado', ['title' => 'Exportar Confirmar Motorizado', 'key' => '1'])

    </h1>

@stop

@section('content')

    @include('envios.motorizado.modal.entregadoconfirm')
    @include('envios.motorizado.modal.entregar_confirm_recojo')
    @include('envios.motorizado.modal.recojo_enviarope')

    <div class="card">
        <div class="card-body">
            <table id="tablaCourierConfirmarFoto" style="width:100%;" class="table table-striped">
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
@endpush

@push('js')
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
            $('#tablaCourierConfirmarFoto').DataTable({
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
                            theme:'material',
                            type: 'red',
                            title: '¡Revertir Envio!',
                            content: 'Confirme si desea revertir el envio <b>' + data.codigos + '</b>',
                            buttons: {
                                ok: {
                                    text: 'Si, confirmar',
                                    btnClass: 'btn-red',
                                    action: function () {
                                        const self = this;
                                        self.showLoading(true)
                                        $.ajax({
                                            data: {
                                                envio_id: data.id
                                            },
                                            //operaciones.confirmar.revertir
                                            type: 'POST',
                                            url: "{{ route('operaciones.confirmarmotorizado.revertir') }}",
                                        }).always(function () {
                                            self.close()
                                            self.hideLoading(true)
                                            $('#tablaCourierConfirmarFoto').DataTable().ajax.reload();
                                        });
                                    }
                                },
                                cancel: {
                                    text: 'No'
                                }
                            }
                        })
                    })
                    $("[data-toggle=jqConfirm]", row).click(function () {
                        const targetLink = $(this).data('target')
                        const imagen1 = $(this).data('imagen1')
                        const imagen2 = $(this).data('imagen2')
                        const imagen3 = $(this).data('imagen3')
                        $.confirm({
                            theme:'material',
                            title: 'Entregas de motorizado Confirmaciones',
                            type: 'green',
                            columnClass: 'xlarge',
                            content: `<div class="p-4">
<div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><br>
                            <p class="font-weight-bold">Foto de los sobres</p>
                            ${imagen1 ? `<img class="foto1 w-100" src="${imagen1}" alt="FOTO 1">` : ''}
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><br>
                            <p class="font-weight-bold">Foto del domicilio</p>
                            ${imagen2 ? `<img class="foto2 w-100" src="${imagen2}" alt="FOTO 2">` : ''}
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><br>
                            <p class="font-weight-bold">Foto de quien recibe</p>
                            ${imagen3 ? `<img class="foto3 w-100" src="${imagen3}" alt="FOTO 3">` : ''}
                        </div>

                        <div class="col-lg-12">
                            <div style="font-size: 11px; background-color: #fdf69d; padding: 8px; margin-top: 16px;">
                                Recordar como Jefe de Operaciones debes de ser estricto en la verificación de fotos del motorizado, los motorizados deben cumplir con enviar las fotos de manera correcta, si fuera reinsidente el Jefe courier tiene la obligación de llamar la atención al motorizado.
                            </div>
                        </div>

                    </div>
</div>`,
                            buttons: {
                                Cerrar: {
                                    btnClass: 'btn-secondary'
                                },
                                confirmar: {
                                    btnClass: 'btn-info',
                                    action: function () {
                                        const self=this
                                        self.showLoading(true)
                                        $.post(targetLink)
                                            .done(function () {
                                                self.close()
                                            })
                                            .always(function () {
                                                self.showLoading(false)
                                                $('#tablaCourierConfirmarFoto').DataTable().draw(false)
                                            })
                                        return false
                                    }
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
                var idunico = button.data('direccion_grupo')
                //var idcodigo = button.data('codigos')
                //$(".textcode").html(idcodigo);
                $("#input_confirmrecojomotorizado").val(idunico)

                let foto1 = button.data('imagen1');
                console.log("foto 1");
                console.log(foto1)
                let foto2 = button.data('imagen2');
                let foto3 = button.data('imagen3');
                $(".foto1").attr("src", foto1);
                $(".foto2").attr("src", foto2);
                $(".foto3").attr("src", foto3);
            })

          $('#modal_confirmrecojomotorizado').on('show.bs.modal', function (event) {
            //adjunta dos fotos
            var button = $(event.relatedTarget)
            var idunico = button.data('direccion_grupo')
            //var idcodigo = button.data('codigos')
            //$(".textcode").html(idcodigo);
            $("#input_confirmrecojomotorizado").val(idunico)

            let foto1 = button.data('imagen1');
            console.log("foto 1");
            console.log(foto1)
            let foto2 = button.data('imagen2');
            let foto3 = button.data('imagen3');
            $(".foto1").attr("src", foto1);
            $(".foto2").attr("src", foto2);
            $(".foto3").attr("src", foto3);
          })

          $(document).on("submit", "#form_confirmrecojo_motorizado", function (evento) {
            evento.preventDefault();
            var dconfirmrecojo = new FormData();
            dconfirmrecojo.append('input_confirmrecojomotorizado', $('#input_confirmrecojomotorizado').val());
            $.ajax({
              data: dconfirmrecojo,
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('courier.confirmrecojo') }}",
              success: function (data) {
                $("#modal_confirmrecojomotorizado").modal("hide");
                $('#tablaCourierConfirmarFoto').DataTable().ajax.reload();

              }
            });

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
                        $('#tablaCourierConfirmarFoto').DataTable().ajax.reload();

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
                        $('#tablaCourierConfirmarFoto').DataTable().ajax.reload();
                    }
                });
            });





        });
    </script>

@endpush
