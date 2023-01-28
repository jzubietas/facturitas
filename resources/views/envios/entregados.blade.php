@extends('adminlte::page')

@section('title', 'Lista de pedidos entregados')

@section('content_header')
    <h1>Lista de pedidos entregados - ENVIOS

        <div class="float-right btn-group dropleft">
            <?php if (Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Logística'){ ?>
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <?php } ?>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img
                        src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
            </div>
        </div>
        @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos ENTREGADOS', 'key' => '2'])

    </h1>
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

    <div class="card">
        <div class="card-body">
            <table cellspacing="5" cellpadding="5" class="d-none">
                <tbody>
                <tr>
                    <td>Minimum date:</td>
                    <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
                    <td></td>
                    <td>Maximum date:</td>
                    <td><input type="text" value={{ $dateMax }} id="max" name="max" class="form-control"></td>
                </tr>
                </tbody>
            </table>
            <br>
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Código</th>
                    <th scope="col">Razón social</th>
                    <th scope="col">Fecha de entrega</th>
                    <th scope="col">Foto del sobre</th>
                    <th scope="col">Foto del domicilio</th>
                    <th scope="col">Foto de quien recibe</th>
                    <th scope="col">Estado de envio</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('pedidos.modal.verenvioid')
            @include('pedidos.modal.imagenid')
            @include('pedidos.modal.imagen2id')
            @include('pedidos.modal.atenderid')
            @include('pedidos.modal.DeleteFoto1id')
            @include('pedidos.modal.DeleteFoto2id')
            @include('envios.modal.CambiarImagen')
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="../css/admin_custom.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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

        /*.fill {
            object-fit: fill;
            }*/

        /*.contain {
        object-fit: contain;
        }*/

        .cover {
        object-fit: cover;
        }

       /* .none {
        object-fit: none;
        }

        .scale-down {
        object-fit: scale-down;
        }*/

    </style>
@stop

@push('css')
@endpush

@section('js')
    <script>
        $("#penvio_doc").change(mostrarValores1);

        function mostrarValores1() {
            $("#envio_doc").val($("#penvio_doc option:selected").text());
        }

        $("#pcondicion").change(mostrarValores2);

        function mostrarValores2() {
            $("#condicion").val($("#pcondicion option:selected").text());
        }
    </script>


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

            $('#modal-imagen').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('imagen');
                var str = "storage/" + idunico;
                var urlimage = '{{ asset(":id") }}';
                urlimage = urlimage.replace(':id', str);
                $("#modal-imagen .img-thumbnail").attr("src", urlimage);
            });

            $('#modal-cambiar-imagen').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var imagen = button.data('imagen');
                var pedido = button.data('pedido');
                var item = button.data('item');

                var str = "storage/" + imagen;
                var urlimage = '{{ asset(":id") }}';
                urlimage = urlimage.replace(':id', str);
                urlimage = urlimage.replace(' ', '%20');
                console.log(urlimage)
                $("#picture").attr("src", urlimage); //cambiar imnagen
                //campos ocultos
                $("#cambiapedido").val(pedido);
                $("#cambiaitem").val(item);
                $("#modal-cambiar-imagen-title").text('Cambiar Imagen ' + item);
            });


            $('#modal-imagen2').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('imagen');
                var str = "storage/" + idunico;
                var urlimage = '{{ asset(":id") }}';

                urlimage = urlimage.replace(':id', str);
                $("#modal-imagen2 .img-thumbnail").attr("src", urlimage);
            });


            $(document).on("submit", "#formulario", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
            });

            $('#modal-editenviar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('editenviar')
                $("#modal-editenviar .textcode").html("PED" + idunico);
                $("#hiddenEditenviar").val(idunico);

                //ajax para obtener los datos
            });

            $(document).on("submit", "#formularioVerenvio", function (evento) {
                evento.preventDefault();
                let item = $("#hiddenVerenvio").val();
                //console.log(item)
            });

            $(document).on("submit", "#formularioEditenviar", function (evento) {
                evento.preventDefault();
                let item = $("#hiddenEditenviar").val();
                //console.log(item);
            });

            $(document).on("click", "#change_imagen", function () {
                var fd2 = new FormData();
                //agregados el id pago
                let files = $('input[name="pimagen')
                var cambiaitem = $("#cambiaitem").val();
                var cambiapedido = $("#cambiapedido").val();

                fd2.append("item", cambiaitem)
                fd2.append("pedido", cambiapedido)
                for (let i = 0; i < files.length; i++) {
                    fd2.append('adjunto', $('input[type=file][name="pimagen"]')[0].files[0]);
                }

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('envios.changeImg') }}",
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            $("#modal-cambiar-imagen").modal("hide");

                            $('#tablaPrincipal').DataTable().ajax.reload( null, false);
                        }
                    }
                });

            });

            $(document).on("change", "#pimagen", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[5, "desc"]],
                ajax: "{{ route('envios.enviadostabla') }}",
                createdRow: function (row, data, dataIndex) {
                    //console.log(rsow);
                },
                rowCallback: function (row, data, index) {

                    if (data.destino2 == 'PROVINCIA') {
                        $('td', row).css('color', 'red')
                    } else if (data.destino2 == 'LIMA') {
                        if (data.distribucion != null) {
                            if (data.distribucion == 'NORTE') {
                                //$('td', row).css('color','blue')
                            } else if (data.distribucion == 'CENTRO') {
                                //$('td', row).css('color','yellow')
                            } else if (data.distribucion == 'SUR') {
                                //$('td', row).css('color','green')
                            }
                        } else {
                        }
                    }

                    $('[data-jqconfirm]', row).click(function () {
                        $.confirm({
                            theme:'material',
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
                                                envio_id:data.id,
                                                pedido:data.codigos
                                            },
                                            type: 'POST',
                                            url: "{{ route('operaciones.revertirhaciaatendido') }}",
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
                    });
                },
                columns: [
                    {
                        data: 'correlativo',
                        name: 'correlativo'

                    },
                    {
                        data: 'identificador',
                        name: 'identificador',
                    },
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            return row.celular + ' - ' + row.nombre
                        },
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
                    {
                        data: 'fechaentrega',
                        name: 'fechaentrega',
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },
                    {
                        data: 'foto1',
                        name: 'foto1',
                    },
                    {
                        data: 'foto2',
                        name: 'foto2',
                    },
                    {
                        data: 'foto3',
                        name: 'foto3',
                    },
                    {
                        data: 'condicion_envio',
                        name: 'condicion_envio',
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},

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

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Pedido {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");
                    // need to change str order before making  date obect since it uses a new Date("mm/dd/yyyy") format for short date.
                    var d = data[6].split("/");
                    var startDate = new Date(d[1] + "/" + d[0] + "/" + d[2]);

                    if (min == null && max == null) {
                        return true;
                    }
                    if (min == null && startDate <= max) {
                        return true;
                    }
                    if (max == null && startDate >= min) {
                        return true;
                    }
                    if (startDate <= max && startDate >= min) {
                        return true;
                    }
                    return false;
                }
            );


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
            var table = $('#tablaPrincipal').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });
    </script>

@stop
