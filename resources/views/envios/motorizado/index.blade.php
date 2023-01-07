@extends('adminlte::page')

@section('title', 'Motorizado')

@section('content_header')
    <h1 class="text-center">
        <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i> Motorizado
    </h1>
@stop

@section('content')

    @include('envios.motorizado.modal.entregado')

    <div class="card p-0">
        <div class="card-body p-0">
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped dt-responsive w-100">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    <th scope="col">Distrito</th>
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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
<style>
    @media(max-width:32rem){
        div.dataTables_wrapper div.dataTables_filter input{
            width: 200px !important;
        }
        .content-wrapper{
            background-color: white !important;
        }
        .card{
            box-shadow: 0 0 1px white !important;
        }
    }
</style>
@endpush

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
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
                responsive: {
                    details: {
                        renderer: $.fn.dataTable.Responsive.renderer.listHiddenNodes()
                    }
                },
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: "{{ route('envios.motorizados.index',['datatable'=>1]) }}",
                createdRow: function (row, data, dataIndex) {

                },
                rowCallback: function (row, data, index) {
                    console.log(row)
                    if (data.destino2 == 'PROVINCIA') {
                        $('td', row).css('color', 'red')
                    }
                    $('[data-jqconfirmcancel]', row).click(function () {

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
                                            url: "{{ route('operaciones.confirmar.revertir') }}",
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
                        }
                    );
                    $('[data-jqconfirm]', row).click(function () {

                        $.dialog({
                            title: '<h3 class="font-weight-bold">Entregas de motorizado</h3>',
                            type: 'green',
                            columnClass: 'xlarge',
                            content: `<div>
    <form enctype="multipart/form-data" class="card">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5>Sobre de pedido: <b>${data.codigos}</b></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                   <div class="form-group">
                     <label for="fecha_envio_doc_fis">Fecha de Envio</label>
                     <input class="form-control" id="fecha_envio_doc_fis" disabled="" name="fecha_envio_doc_fis" type="date" value="${data.fecha}">
                    </div>
                </div>
                <div class="col-lg-6">
                   <div class="form-group">
                        <label for="fecha_recepcion">Fecha de Entrega</label>
                        <input class="form-control" id="fecha_recepcion" name="fecha_recepcion" type="date" value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="foto1">Foto de los sobres</label>
                        <input class="form-control-file" id="adjunto1" name="adjunto1" type="file">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="foto2">Foto del domicilio</label>
                        <input class="form-control-file" id="adjunto2" name="adjunto2" type="file">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="foto3">Foto de quien recibe</label>
                        <input class="form-control-file" id="adjunto3" name="adjunto3" type="file">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture1" src="{{ asset('imagenes/sobres.jpg') }}"
                                alt="Imagen del pago" class="w-80 mh-90 h-90 img-fluid" style="display: block;">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture2" src="{{ asset('imagenes/domicilio.jpg') }}"
                                 alt="Imagen del pago" class="w-80 mh-90 h-90 img-fluid" style="display: block">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture3" src="{{ asset('imagenes/recibe_sobre.jpg') }}"
                                 alt="Imagen del pago" class="w-80 mh-90 h-90 img-fluid" style="display: block">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
        </div>
    </form>
</div>`,
                            onContentReady: function () {
                                const self = this
                                self.$content.find("#adjunto1").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture1").show();
                                        self.$content.find("#picture1").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#adjunto2").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture2").show();
                                        self.$content.find("#picture2").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#adjunto3").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture3").show();
                                        self.$content.find("#picture3").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("form").on('submit', function (e) {
                                    e.preventDefault()
                                    if (!e.target.fecha_recepcion.value) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Ingresa la fecha de Entrega</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto1.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la foto 1</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto2.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la foto 2</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto3.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la foto 3</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    var fd2=new FormData(e.target);
                                    fd2.set('envio_id',data.id)
                                    self.showLoading(true)
                                    $.ajax({
                                        data: fd2,
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        url: "{{ route('operaciones.confirmarmotorizado') }}"
                                    }).done(function () {
                                        self.close()
                                        $('#tablaPrincipal').DataTable().ajax.reload();
                                    }).always(function () {
                                        self.hideLoading(true)
                                    });
                                })
                            },
                        });
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
                    {data: 'distrito', name: 'distrito',},
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
        });
    </script>

@stop
