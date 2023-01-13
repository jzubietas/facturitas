@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
    <h1>Lista de pedidos para reparto - ENVIOS

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
        @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos POR ENVIAR', 'key' => '1'])
        {{-- @endcan --}}
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
    <style>
        .activo {
            background-color: #e74c3c !important;
            color: white !important;
            border: 0 !important;
        }
    </style>
    <div class="card">
        <div class="card-body">

            <ul class="nav nav-tabs mb-24" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="zona-tabla nav-link activo active font-weight-bold" id="home-tab" data-toggle="tab"
                       data-url="NORTE" href="#home" role="tab" aria-controls="home" aria-selected="true">NORTE</a>
                </li>
                <li class="nav-item">
                    <a class="zona-tabla nav-link font-weight-bold" id="profile-tab" data-toggle="tab" data-url="CENTRO"
                       href="#profile" role="tab" aria-controls="profile" aria-selected="false">CENTRO</a>
                </li>
                <li class="nav-item">
                    <a class="zona-tabla nav-link font-weight-bold" id="contact-tab" data-toggle="tab" data-url="SUR"
                       href="#contact" role="tab" aria-controls="contact" aria-selected="false">SUR</a>
                </li>
            </ul>


            <table id="tablaPrincipal" style="width:100%;" class="table table-striped mt-24">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Zona</th>
                    <th scope="col">Motorizado</th>
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

        {{-- <table cellspacing="5" cellpadding="5">
          <tbody>
            <tr>
              <td>Destino:</td>
              <td>
                <select name="destino" id="destino" class="form-control">
                  <option value="LIMA">LIMA</option>
                  <option value="PROVINCIA">PROVINCIA</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table><br> --}}

        @include('envios.modal.enviarid')
        @include('pedidos.modal.recibirid')
        {{--@include('sobres.modal.direccionid')--}}
        @include('pedidos.modal.verdireccionid')
        @include('pedidos.modal.editdireccionid')
        @include('pedidos.modal.destinoid')
        @include('envios.modal.distribuir')
        @include('envios.modal.confirmacion')
        @include('envios.modal.desvincularpedidos')
    </div>
    </div>

@stop

@section('css')

    <style>
        img:hover {
            transform: scale(1.2)
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

        .darkblue {
            color: #021691 !important;
        }
        .red {
            color: red !important;
        }
        .green {
            color: green !important;
        }
    </style>
@stop

@section('js')
    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>



    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script
        src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

    <script>


        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.zona-tabla').on('click', function () {
                $('.zona-tabla').removeClass("activo");
                $(this).addClass("activo");
                //var url = $(this).data("url");
                $('#tablaPrincipal').DataTable().ajax.reload();

            });

            $(document).on("click", "#desvincularConfirmar", function (event) {

                var rows_selected = tabla_pedidos.column(0).checkboxes.selected();
                var $direcciongrupo = $("#direcciongrupo").val();
                var $observaciongrupo = $("#observaciongrupo").val();
                var pedidos = [];
                $.each(rows_selected, function (index, rowId) {
                    console.log("ID PEDIDO  es " + rowId);
                    pedidos.push(rowId);
                });


                var let_pedidos = pedidos.length;

                if (let_pedidos == 0) {
                    Swal.fire(
                        'Error',
                        'Debe elegir un pedido',
                        'warning'
                    )
                    return;
                }
                var $pedidos = pedidos.join(',');
                console.log($pedidos);
                console.log($direcciongrupo);
                console.log($observaciongrupo);
                var fd2 = new FormData();
                let direcciongrupo = $("#direcciongrupo").val();
                let observaciongrupo = $("#observaciongrupo").val();
                fd2.append('direcciongrupo', direcciongrupo);
                fd2.append('observaciongrupo', observaciongrupo);
                /*fd2.append('observaciongrupo', $('#observaciongrupo').val() );*/
                fd2.append('pedidos', $pedidos);


                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('sobres.desvinculargrupo') }}",
                    success: function (data) {
                        console.log(data);
                        $("#modal-desvincular").modal("hide");
                        $("#tablaPrincipal").DataTable().ajax.reload();
                    }
                });
            })

            $('#modal-desvincular').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget)
                var direcciongrupo = button.data('desvincular');
                $("#direcciongrupo").val(direcciongrupo);
                //$("#observaciongrupo").val(observaciongrupo);
                tabla_pedidos.destroy();


                tabla_pedidos = $('#tablaPrincipalpedidosagregar').DataTable({
                    responsive: true,
                    "bPaginate": false,
                    "bFilter": false,
                    "bInfo": false,
                    'ajax': {
                        url: "{{ route('cargar.pedidosgrupotabla') }}",
                        'data': {"direcciongrupo": direcciongrupo},
                        "type": "get",
                    },
                    'columnDefs': [{
                        'targets': [0],
                        'orderable': false,
                    }],
                    columns: [
                        {
                            "data": "pedido_id",
                            'targets': [0],
                            'checkboxes': {
                                'selectRow': true
                            },
                            defaultContent: '',
                            orderable: false,
                        },
                        {data: 'codigo', name: 'codigo',},
                        {
                            "data": 'nombre_empresa',
                            "name": 'nombre_empresa',
                        },
                    ],
                    'select': {
                        'style': 'multi',
                        selector: 'td:first-child'
                    },
                });

            });

            $('#modal-confirmacion').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('ide')
                var codigos = button.data('codigos')
                var zona = button.data('distribucion')

                $('.titulo-confirmacion').html("Enviar sobre a Motorizado");

                $("#hiddenCodigo").val(idunico)
                $("#modal-confirmacion .textcode").html(codigos);
                $("#modal-confirmacion .textzone").html(zona);
            });

            $(document).on("submit", "#formulario_confirmacion", function (evento) {
                evento.preventDefault();
                //validacion


                var fd2 = new FormData();
                fd2.append('hiddenCodigo', $('#hiddenCodigo').val());
                fd2.append('fecha_salida', $('#fecha_salida').val());

                if ($('#fecha_salida').val() == '') {
                    Swal.fire(
                        'Error',
                        'Complete fecha de salida para continuar',
                        'warning'
                    )
                    return false;
                }

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.confirmar') }}",
                    success: function (data) {
                        $("#modal-confirmacion").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();

                    }
                });
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on("submit", "#formulario", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
            });

            $('#modal-enviar').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('enviar')//pedido
                var destino = button.data('destino')//pedido
                var dfecha = button.data('fechaenvio')//pedido

                var newOption = $('<option value="REGISTRADO">REGISTRADO</option>');
                var newOption2 = $('<option value="NO ENTREGADO">NO ENTREGADO</option>');
                var newOption3 = $('<option value="ENTREGADO">ENTREGADO</option>');

                var newOption4 = $('<option value="EN CAMINO">EN CAMINO</option>');
                var newOption5 = $('<option value="EN TIENDA/AGENTE">EN TIENDA/AGENTE</option>');
                console.log(dfecha)
                $('#condicion').empty().append(newOption3);
                $("#fecha_envio_doc_fis").val(dfecha);

                // if (destino=='LIMA')
                // $('#condicion').empty().append(newOption).append(newOption2).append(newOption3);
                //  else
                //  $('#condicion').empty().append(newOption).append(newOption2).append(newOption4).append(newOption5).append(newOption3);

                console.log(destino);

                $("#hiddenEnviar").val(idunico)
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }
                $("#modal-enviar .textcode").html(idunico);

            });


            $(document).on("change", "#foto1", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture1").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });

            $(document).on("change", "#foto2", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture2").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });


            $('#modal-recibir').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('recibir')//pedido
                $("#hiddenRecibir").val(idunico)
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }
                $("#modal-recibir .textcode").html(idunico);


            });

            $(document).on("submit", "#formularioenviar", function (evento) {
                evento.preventDefault();
                console.log("form enviarid")
                //validacion

                var fd2 = new FormData();
                let files = $('input[name="pimagen')
                var fileitem = $("#DPitem").val();

                fd2.append('hiddenEnviar', $('#hiddenEnviar').val());
                fd2.append('fecha_envio_doc_fis', $('#fecha_envio_doc_fis').val());
                fd2.append('fecha_recepcion', $('#fecha_recepcion').val());
                fd2.append('foto1', $('input[type=file][id="foto1"]')[0].files[0]);
                fd2.append('foto2', $('input[type=file][id="foto2"]')[0].files[0]);
                fd2.append('condicion', $('#condicion').val());

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('envios.enviarid') }}",
                    success: function (data) {
                        $("#modal-enviar").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();

                    }
                });

            });

            $(document).on("submit", "#formulariorecibir", function (evento) {
                evento.preventDefault();
            });

            let tabla_pedidos = $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                order: [[0, "desc"]],
                ajax: {
                    url: "{{ route('envios.pararepartotabla') }}",
                    data: function (d) {
                        d.zona = $('.zona-tabla.activo').data("url");
                    },
                },
                createdRow: function (row, data, dataIndex) {
                    if(data["estado"]=='1')
                    {
                        if(data["destino2"]=='PROVINCIA')
                        {
                            $(row).addClass('green');
                        }else if(data["destino2"]=='LIMA')
                        {
                            if(data["distribucion"]=='OLVA')
                            {
                                $(row).addClass('darkblue');
                            }else if(data["distribucion"]=='LIMA')
                            {
                                //$(row).addClass('darkblue');
                            }
                        }
                    }else if(data["estado"]==0){
                        $(row).addClass('red');
                    }
                },
                rowCallback: function (row, data, index) {
                    /*if (data.destino2 == 'PROVINCIA') {
                        $('td', row).css('color', 'red')
                    } else if (data.destino2 == 'LIMA') {
                        if (data.distribucion != null) {
                            if (data.distribucion == 'NORTE') {
                            } else if (data.distribucion == 'CENTRO') {
                            } else if (data.distribucion == 'SUR') {
                            }
                        }
                    }*/
                },
                columns: [
                    {
                        data: 'correlativo',
                        name: 'correlativo',

                    },
                    {
                        data: 'distribucion',
                        name: 'distribucion',

                    },
                    {
                        data: 'nombre_motorizado',
                        name: 'nombre_motorizado',

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
                    {data: 'user_identificador', name: 'user_identificador',},
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            return row.celular + '<br>' + row.nombre
                        },
                    },
                    {
                        data: 'fecha_formato',
                        name: 'fecha_formato',
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
                            if (row.destino == 'PROVINCIA') {
                                var datal = "";
                                urladjunto = '{{ route("pedidos.descargargastos", ":id") }}';
                                urladjunto = urladjunto.replace(':id', data);
                                datal = datal + '<p><a href="' + urladjunto + '">' + data + '</a><p>';
                                return datal;
                            } else {
                                return data;
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

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Pedido {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>

        //VALIDAR CAMPO CELULAR
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength) {
                object.value = object.value.slice(0, object.maxLength)
            }
        }

        //VALIDAR ANTES DE ENVIAR
        /*document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("formulario").addEventListener('submit', validarFormulario);
        });*/

        function validarFormulario(evento) {
            evento.preventDefault();
            var condicion = document.getElementById('condicion').value;
            var foto1 = document.getElementById('foto1').value;
            var pfoto1 = document.getElementById('pfoto1').value;
            var foto2 = document.getElementById('foto2').value;
            var pfoto2 = document.getElementById('pfoto2').value;

            if (condicion == 3) {
                if (foto1 == '' && pfoto1 == '') {
                    Swal.fire(
                        'Error',
                        'Para dar por ENTREGADO debe registrar la foto 1',
                        'warning'
                    )
                } else if (foto2 == '' && pfoto2 == '') {
                    Swal.fire(
                        'Error',
                        'Para dar por ENTREGADO debe registrar la foto 2',
                        'warning'
                    )
                } else {
                    this.submit();
                }
            } else {
                this.submit();
            }
        }
    </script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {


            $("#destino", this).on('keyup change', function () {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(8)
                        .search(this.value)
                        .draw();
                }
            });

        });
    </script>

@stop
