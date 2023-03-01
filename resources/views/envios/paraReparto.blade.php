@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
    <h1>Lista de pedidos para reparto - ENVIOS

        <div class="float-right btn-group dropleft">
            <x-common-button-qr-scanner
                module-title="Sobres para reparto"
                with-fecha
                responsable="fernandez_reparto"
                tipo="grupo"
                accion="sobres_reparto"
                :tables-ids="['#tablaPrincipal_norte']"
                :extras="[]"
                :reparto='1'
            ></x-common-button-qr-scanner>

            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_">
                    <img src="{{ asset('imagenes/icon-excel.png') }}"> Excel
                </a>
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

            <div class="row">
                <div class="col-3 mx-auto">
                    <input id="buscador_global" name="buscador_global" value=""
                           type="text" class="form-control" autocomplete="off"
                           placeholder="Ingrese su búsqueda" aria-label="Recipient's username" aria-describedby="basic-addon2">
                </div>
            </div>

            <ul class="nav nav-tabs mb-24" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="zona-tabla nav-link active font-weight-bold" id="norte-tab" data-toggle="tab"
                       data-url="NORTE" href="#norte" role="tab" aria-controls="norte" aria-selected="true">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> NORTE
                        <sup><span class="badge badge-light count_parareparto_norte">0</span></sup></a>
                </li>
                <li class="nav-item">
                    <a class="zona-tabla nav-link font-weight-bold" id="centro-tab" data-toggle="tab" data-url="CENTRO"
                       href="#centro" role="tab" aria-controls="centro" aria-selected="false">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> CENTRO
                        <sup><span class="badge badge-light count_parareparto_centro">0</span></sup></a>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="zona-tabla nav-link font-weight-bold" id="sur-tab" data-toggle="tab" data-url="SUR"
                       href="#sur" role="tab" aria-controls="sur" aria-selected="false">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> SUR
                        <sup><span class="badge badge-light count_parareparto_sur">0</span></sup></a>
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="norte" role="tabpanel" aria-labelledby="norte-tab">
                    <table id="tablaPrincipal_norte" style="width:100%;" class="table table-striped mt-24">
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
                            <th scope="col">Distrito</th>
                            <th scope="col">Dirección de envío</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Estado de envio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="centro" role="tabpanel" aria-labelledby="centro-tab">
                    <table id="tablaPrincipal_centro" style="width:100%;" class="table table-striped mt-24">
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
                            <th scope="col">Distrito</th>
                            <th scope="col">Dirección de envío</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Estado de envio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="sur" role="tabpanel" aria-labelledby="sur-tab">
                    <table id="tablaPrincipal_sur" style="width:100%;" class="table table-striped mt-24">
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
                            <th scope="col">Distrito</th>
                            <th scope="col">Dirección de envío</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Estado de envio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <br>

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
                let tabla_pedidos_principal_norte=null;
                let tabla_pedidos_principal_centro=null;
                let tabla_pedidos_principal_sur=null;
                moment().format();

                function workingDays(dateFrom, dateTo) {
                    var from = moment(dateFrom, 'DD/MM/YYY'),
                        to = moment(dateTo, 'DD/MM/YYY'),
                        days = 0;

                    while (!from.isAfter(to)) {
                        // Si no es sabado ni domingo
                        if (from.isoWeekday() !== 6 && from.isoWeekday() !== 7) {
                            days++;
                        }
                        from.add(1, 'days');
                    }
                    return days;
                }

                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    function applySearch(e) {
                        console.log(e)
                        //console.log("vacio");
                        let valor=$("#buscador_global").val();
                        //valor=(valor||'').trim()
                        tabla_pedidos_principal_norte.search( valor ).draw();
                        tabla_pedidos_principal_centro.search( valor ).draw();
                        tabla_pedidos_principal_sur.search( valor ).draw();
                    }

                    $('#btn_buscar').click(applySearch);
                    $("#buscador_global").bind('paste',function () {
                        setTimeout(applySearch,100)
                    });
                    $('#buscador_global').change(applySearch);
                    $('#buscador_global').keydown(applySearch);


                    /*$(document).on('change keyup','#buscador_global',function(){
                        tabla_pedidos_principal_norte.search($(this).val()).draw();
                        tabla_pedidos_principal_centro.search($(this).val()).draw();
                        tabla_pedidos_principal_sur.search($(this).val()).draw();
                    })*/

                    //$('.zona-tabla').on('click', function () {
                    //$('.zona-tabla').removeClass("active");
                    //$(this).addClass("active")//.trigger('click');
                    //var url = $(this).data("url");
                    //$('#tablaPrincipal').DataTable().ajax.reload();

                    //});
                    var tabla_pedidos;
                    $(document).on("click", "#desvincularConfirmar", function (event) {
                        if (!tabla_pedidos) {
                            console.log("var tabla_pedido is null")
                            return;
                        }
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
                                $("#tablaPrincipal_norte").DataTable().draw(false);
                            }
                        });
                    })

                    $('#modal-desvincular').on('show.bs.modal', function (event) {

                        var button = $(event.relatedTarget)
                        var direcciongrupo = button.data('desvincular');
                        $("#direcciongrupo").val(direcciongrupo);
                        //$("#observaciongrupo").val(observaciongrupo);
                        if (tabla_pedidos != null) {
                            tabla_pedidos.destroy();
                        }


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
                                $('#tablaPrincipal_norte').DataTable().ajax.reload();

                            }
                        });

                    });

                    $(document).on("submit", "#formulariorecibir", function (evento) {
                        evento.preventDefault();
                    });

                    tabla_pedidos_principal_norte = $('#tablaPrincipal_norte').DataTable({
                        dom: '<"top"i>rt<"bottom"lp><"clear">',
                        processing: true,
                        stateSave: false,
                        serverSide: true,
                        searching: true,
                        order: [[0, "desc"]],
                        ajax: {
                            url: "{{ route('envios.pararepartotabla') }}",
                            data: function (d) {
                                d.zona = 'NORTE';//$('.zona-tabla.activo').data("url");
                                //d.buscador_global=$('#buscador_global').val();
                            },
                        },
                        createdRow: function (row, data, dataIndex) {
                            if (data["estado"] == '1') {
                                if (data["destino2"] == 'PROVINCIA') {
                                    $(row).addClass('green');
                                } else if (data["destino2"] == 'LIMA') {
                                    if (data["distribucion"] == 'OLVA') {
                                        $(row).addClass('darkblue');
                                    } else if (data["distribucion"] == 'LIMA') {
                                        //$(row).addClass('darkblue');
                                    }
                                }
                            } else if (data["estado"] == 0) {
                                $(row).addClass('red');
                            }
                        },
                        rowCallback: function (row, data, index) {
                            if (data.cambio_direccion_at != null) {
                                $('td', row).css('background', 'rgba(17,129,255,0.35)')
                            }
                            $("[data-toggle=jqconfirm]", row).click(function (e) {
                                e.preventDefault()
                                $.confirm({
                                    theme:'material',
                                    type: 'green',
                                    title: 'Enviar sobre a Motorizado',
                                    columnClass: 'large',
                                    content: `<div>
                <div class="row">
                  <div class="col-12">
                    <p>Esta seguro que desea enviar el sobre <strong>${data.codigos}</strong> a Motorizados del <strong class="textzone">${data.distribucion}</strong></p>
                  </div>
                  ${data.cambio_direccion_at != null?`<div class="col-12">
                    <p class="alert alert-warning"><b>Datos de la dirección fueron modificados, ¿desea continuar?.</b><br> En caso contrario cierre esta ventana y haga click en el boton <b>Retornar a sobres con dirección</b></p>
                  </div>`:''}
                </div>

                <div class="row">
                  <div class="col">
                      <label for="fecha_salida">Fecha de ruta</label>
                      <input class="form-control fecha_salida" id="fecha_salida" name="fecha_salida" type="date" value="{{now()->format('Y-m-d')}}">
                      <p class="mensaje_fecha_salida">

                      </p>
                  </div>
                </div>

              </div>`,
                                    buttons: {
                                        cerrar: {
                                            btnClass:'btn-dark'
                                        },
                                        confirmar: {
                                            btnClass:'btn-success',
                                            action: function () {
                                                const self = this;
                                                var fd2 = new FormData();
                                                const fecha=self.$content.find('input.fecha_salida').val();
                                                fd2.append('hiddenCodigo', data.id);
                                                fd2.append('fecha_salida', fecha);

                                                if (!fecha) {
                                                    Swal.fire(
                                                        'Error',
                                                        'Complete fecha de salida para continuar',
                                                        'warning'
                                                    )
                                                    return false;
                                                }
                                                self.showLoading(true)
                                                $.ajax({
                                                    data: fd2,
                                                    processData: false,
                                                    contentType: false,
                                                    type: 'POST',
                                                    url: "{{ route('operaciones.confirmar') }}",
                                                }).done(function () {
                                                    self.close()
                                                    $('#tablaPrincipal_norte').DataTable().draw(false);
                                                }).always(function () {
                                                    self.hideLoading(true)
                                                });
                                            }
                                        }
                                    },
                                    onContentReady: function () {
                                        const self = this;
                                        const $content = this.$content;
                                        $content.find('input.fecha_salida').on('change', function () {
                                            let fecha_actual = moment();
                                            if (fecha_actual.valueOf() > new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('<p class="bagde badge-warning p-8 font-weight-bold mt-16 br-8"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> La fecha indicada es mayor a la fecha actual.</p>');
                                            } else if (fecha_actual.valueOf() < new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('<p class="bagde badge-warning p-8 font-weight-bold mt-16 br-8"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> La fecha indicada es menor a la fecha actual.</p>');
                                            } else if (fecha_actual.valueOf() === new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('');
                                            }
                                        });
                                    }
                                })
                            })
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
                            {data: 'distrito', name: 'distrito',},
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
                        "fnDrawCallback": function () {
                            $('.count_parareparto_norte').html(this.fnSettings().fnRecordsDisplay());
                        }
                    });

                    tabla_pedidos_principal_centro = $('#tablaPrincipal_centro').DataTable({
                        dom: '<"top"i>rt<"bottom"lp><"clear">',
                        processing: true,
                        stateSave: false,
                        serverSide: true,
                        searching: true,
                        order: [[0, "desc"]],
                        ajax: {
                            url: "{{ route('envios.pararepartotabla') }}",
                            data: function (d) {
                                d.zona = 'CENTRO'//$('.zona-tabla.activo').data("url");
                                //d.buscador_global=$('#buscador_global').val();
                            },
                        },
                        createdRow: function (row, data, dataIndex) {
                            if (data["estado"] == '1') {
                                if (data["destino2"] == 'PROVINCIA') {
                                    $(row).addClass('green');
                                } else if (data["destino2"] == 'LIMA') {
                                    if (data["distribucion"] == 'OLVA') {
                                        $(row).addClass('darkblue');
                                    } else if (data["distribucion"] == 'LIMA') {
                                        //$(row).addClass('darkblue');
                                    }
                                }
                            } else if (data["estado"] == 0) {
                                $(row).addClass('red');
                            }
                        },
                        rowCallback: function (row, data, index) {
                            if (data.cambio_direccion_at != null) {
                                $('td', row).css('background', 'rgba(17,129,255,0.35)')
                            }
                            $("[data-toggle=jqconfirm]", row).click(function (e) {
                                e.preventDefault()
                                $.confirm({
                                    theme:'material',
                                    type: 'green',
                                    title: 'Enviar sobre a Motorizado',
                                    columnClass: 'large',
                                    content: `<div>
                <div class="row">
                  <div class="col-12">
                    <p>Esta seguro que desea enviar el sobre <strong>${data.codigos}</strong> a Motorizados del <strong class="textzone">${data.distribucion}</strong></p>
                  </div>
                  ${data.cambio_direccion_at != null?`<div class="col-12">
                    <p class="alert alert-warning"><b>Datos de la dirección fueron modificados, ¿desea continuar?.</b><br> En caso contrario cierre esta ventana y haga click en el boton <b>Retornar a sobres con dirección</b></p>
                  </div>`:''}
                </div>

                <div class="row">
                  <div class="col">
                      <label for="fecha_salida">Fecha de ruta</label>
                      <input class="form-control fecha_salida" id="fecha_salida" name="fecha_salida" type="date" value="{{now()->format('Y-m-d')}}">
                      <p class="mensaje_fecha_salida">

                      </p>
                  </div>
                </div>

              </div>`,
                                    buttons: {
                                        cerrar: {
                                            btnClass:'btn-dark'
                                        },
                                        confirmar: {
                                            btnClass:'btn-success',
                                            action: function () {
                                                const self = this;
                                                var fd2 = new FormData();
                                                const fecha=self.$content.find('input.fecha_salida').val();
                                                fd2.append('hiddenCodigo', data.id);
                                                fd2.append('fecha_salida', fecha);

                                                if (!fecha) {
                                                    Swal.fire(
                                                        'Error',
                                                        'Complete fecha de salida para continuar',
                                                        'warning'
                                                    )
                                                    return false;
                                                }
                                                self.showLoading(true)
                                                $.ajax({
                                                    data: fd2,
                                                    processData: false,
                                                    contentType: false,
                                                    type: 'POST',
                                                    url: "{{ route('operaciones.confirmar') }}",
                                                }).done(function () {
                                                    self.close()
                                                    //$('#tablaPrincipal_norte').DataTable().ajax.reload();
                                                    //$('#tablaPrincipal_norte').DataTable().draw();
                                                    $('#tablaPrincipal_norte').DataTable().draw();
                                                    $('#tablaPrincipal_centro').DataTable().draw();
                                                    $('#tablaPrincipal_sur').DataTable().draw();
                                                }).always(function () {
                                                    self.hideLoading(true)
                                                });
                                            }
                                        }
                                    },
                                    onContentReady: function () {
                                        const self = this;
                                        const $content = this.$content;
                                        $content.find('input.fecha_salida').on('change', function () {
                                            let fecha_actual = moment();
                                            if (fecha_actual.valueOf() > new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('<p class="bagde badge-warning p-8 font-weight-bold mt-16 br-8"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> La fecha indicada es mayor a la fecha actual.</p>');
                                            } else if (fecha_actual.valueOf() < new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('<p class="bagde badge-warning p-8 font-weight-bold mt-16 br-8"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> La fecha indicada es menor a la fecha actual.</p>');
                                            } else if (fecha_actual.valueOf() === new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('');
                                            }
                                        });
                                    }
                                })
                            })
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
                        "fnDrawCallback": function () {
                            $('.count_parareparto_centro').html(this.fnSettings().fnRecordsDisplay());
                        }
                    });

                    tabla_pedidos_principal_sur = $('#tablaPrincipal_sur').DataTable({
                        dom: '<"top"i>rt<"bottom"lp><"clear">',
                        processing: true,
                        stateSave: false,
                        serverSide: true,
                        searching: true,
                        order: [[0, "desc"]],
                        ajax: {
                            url: "{{ route('envios.pararepartotabla') }}",
                            data: function (d) {
                                d.zona = 'SUR';//$('.zona-tabla.activo').data("url");
                                //d.buscador_global=$('#buscador_global').val();
                            },
                        },
                        createdRow: function (row, data, dataIndex) {
                            if (data["estado"] == '1') {
                                if (data["destino2"] == 'PROVINCIA') {
                                    $(row).addClass('green');
                                } else if (data["destino2"] == 'LIMA') {
                                    if (data["distribucion"] == 'OLVA') {
                                        $(row).addClass('darkblue');
                                    } else if (data["distribucion"] == 'LIMA') {
                                        //$(row).addClass('darkblue');
                                    }
                                }
                            } else if (data["estado"] == 0) {
                                $(row).addClass('red');
                            }
                        },
                        rowCallback: function (row, data, index) {
                            if (data.cambio_direccion_at != null) {
                                $('td', row).css('background', 'rgba(17,129,255,0.35)')
                            }
                            $("[data-toggle=jqconfirm]", row).click(function (e) {
                                e.preventDefault()
                                $.confirm({
                                    theme:'material',
                                    type: 'green',
                                    title: 'Enviar sobre a Motorizado 3',
                                    columnClass: 'large',
                                    content: `<div>
                                              <div class="row">
                                                <div class="col-12">
                                                  <p>Esta seguro que desea enviar el sobre <strong>${data.codigos}</strong> a Motorizados del <strong class="textzone">${data.distribucion}</strong></p>
                                                </div>
                                                ${data.cambio_direccion_at != null?`<div class="col-12">
                                                  <p class="alert alert-warning"><b>Datos de la dirección fueron modificados, ¿desea continuar?.</b><br> En caso contrario cierre esta ventana y haga click en el boton <b>Retornar a sobres con dirección</b></p>
                                                </div>`:''}
                                              </div>

                                              <div class="row">
                                                <div class="col">
                                                    <label for="fecha_salida">Fecha de ruta</label>
                                                    <input class="form-control fecha_salida" id="fecha_salida" name="fecha_salida" type="date" value="{{now()->format('Y-m-d')}}">
                                                    <p class="mensaje_fecha_salida">

                                                    </p>
                                                </div>
                                              </div>

                                            </div>`,
                                    buttons: {
                                        cerrar: {
                                            btnClass:'btn-dark'
                                        },
                                        confirmar: {
                                            btnClass:'btn-success',
                                            action: function () {
                                                const self = this;
                                                var fd2 = new FormData();
                                                const fecha=self.$content.find('input.fecha_salida').val();
                                                fd2.append('hiddenCodigo', data.id);
                                                fd2.append('fecha_salida', fecha);

                                                if (!fecha) {
                                                    Swal.fire(
                                                        'Error',
                                                        'Complete fecha de salida para continuar',
                                                        'warning'
                                                    )
                                                    return false;
                                                }
                                                self.showLoading(true)
                                                $.ajax({
                                                    data: fd2,
                                                    processData: false,
                                                    contentType: false,
                                                    type: 'POST',
                                                    url: "{{ route('operaciones.confirmar') }}",
                                                }).done(function () {
                                                    self.close()
                                                    $('#tablaPrincipal_norte').DataTable().draw(false);
                                                }).always(function () {
                                                    self.hideLoading(true)
                                                });
                                            }
                                        }
                                    },
                                    onContentReady: function () {
                                        const self = this;
                                        const $content = this.$content;
                                        $content.find('input.fecha_salida').on('change', function () {
                                            let fecha_actual = moment();
                                            if (fecha_actual.valueOf() > new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('<p class="bagde badge-warning p-8 font-weight-bold mt-16 br-8"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> La fecha indicada es mayor a la fecha actual.</p>');
                                            } else if (fecha_actual.valueOf() < new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('<p class="bagde badge-warning p-8 font-weight-bold mt-16 br-8"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> La fecha indicada es menor a la fecha actual.</p>');
                                            } else if (fecha_actual.valueOf() === new Date().getTime()) {
                                                $content.find('.mensaje_fecha_salida').html('');
                                            }
                                        });
                                    }
                                })
                            })
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
                        "fnDrawCallback": function () {
                            $('.count_parareparto_sur').html(this.fnSettings().fnRecordsDisplay());

                            let a1=$('#tablaPrincipal_norte').dataTable().fnSettings().fnRecordsDisplay();
                            let a2=$('#tablaPrincipal_centro').dataTable().fnSettings().fnRecordsDisplay();
                            let a3=this.fnSettings().fnRecordsDisplay();

                            if(a1>0){
                                //$('.zona-tabla').removeClass("activo").removeClass("active");
                                //$('#norte-tab').addClass("active").addClass("activo");
                                $('#myTab a[href="#norte"]').tab('show')
                            }else if(a2>0){
                                //$('.zona-tabla').removeClass("activo").removeClass("active");
                                //$('#centro-tab').addClass("active").addClass("activo");
                                $('#myTab a[href="#centro"]').tab('show')
                            }else if(a3>0){
                                //$('.zona-tabla').removeClass("activo").removeClass("active");
                                //$('#sur-tab').addClass("active").addClass("activo");
                                $('#myTab a[href="#sur"]').tab('show')
                            }
                        }
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
