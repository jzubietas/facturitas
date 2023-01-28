@extends('adminlte::page')

@section('title', 'Lista de pedidos por confirmar')

@section('content_header')
    <div class="row border-bottom pb-16">
        <div class="col-lg-6">
            <h1 class="text-center font-20 font-weight-bold">Recepcion para motorizados - ENVIOS</h1>
        </div>
        <div class="col-lg-6">
            <div class="row mx-auto d-flex justify-content-center">
                <div class="col-lg-12 ">
                    <div class="d-flex justify-content-between">
                        <div class="btn-group">


                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                Exportar
                            </button>


                            <div class="dropdown-menu">
                                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item"
                                   target="blank_"><img
                                        src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>

                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button id="download_rotulos" data-href="{{route('envios.rutaenvio.merge-routulos')}}"
                                    class="btn btn-info">
                                <i class="fa fa-download"></i>
                                Descargar Rotulos
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            @include('envios.motorizado.modal.exportar_motorizado', ['title' => 'Exportar Recepcion Motorizado', 'key' => '2'])

        </div>
    </div>

    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos POR ENVIAR', 'key' => '1'])

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

        #placeholder-qr {
            animation: qr 1.5s ease-in-out infinite;
        }

        @keyframes qr {
            0% {
                transform: translate(-50%, -50%) scale(0.7);
            }
            50% {
                transform: translate(-50%, -50%) scale(1);
            }
            100% {
                transform: translate(-50%, -50%) scale(0.7);
            }

        }

        #btn-qr {
            margin-right: 16px;
            position: fixed;
            bottom: 16px;
            left: 50%;
            width: 300px;
            background-color: #3498db !important;
            color: white;
            text-shadow: 1px 2px 3px #00000063;
            transform: translate(-50%, 0px);
            border-radius: 12px;
            z-index: 999;
        }

        .activo {
            background-color: #e74c3c !important;
            color: white !important;
            border: 0 !important;
        }

        .content-wrapper {
            background-color: white;
        }

        .card {
            box-shadow: 0 0 white;
        }
    </style>
    <div class="card w-100 pb-48">
        <div class="card-body p-0">


            <div class="row">
                <div class="col-6 ">
                    <input type="date" value="{{$fecha_consulta}}" id="fecha_consulta" name="fecha_consulta"
                           class="form-control mx-auto" autocomplete="off">
                </div>
                <div class="col-6 mx-auto">
                    <input id="buscador_global" name="buscador_global" value=""
                           type="text" class="form-control" autocomplete="off"
                           placeholder="Ingrese su búsqueda" aria-label="Recipient's username" aria-describedby="basic-addon2">
                </div>
            </div>

            <br>


                                <div class="row">

                                        @foreach($motorizados as $motorizado)
                                            <div class="col-lg-4 container-{{Str::slug($motorizado->zona)}}">
                                                <div class="table-responsive">
                                                    <div
                                                        class="card card-{{$color_zones[Str::upper($motorizado->zona)]??'success'}}">
                                                        <div class="card-header pt-8 pb-8">
                                                            <div class="d-flex justify-content-between">
                                                                <h5 class="mb-0 font-16">
                                                                    MOTORIZADO {{Str::upper($motorizado->zona)}}</h5>
                                                                <div>

                                                                    <h6 class="mb-0">
                                                                        <button data-toggle="modal" data-target="#modal-scan-comparador"
                                                                            class="btn btn-sm btn-option"
                                                                            data-zona="{{$motorizado->zona}}" data-motorizado="{{$motorizado->id}}" data-vista="">
                                                                            <i class="fa fa-barcode"></i> Comprobar archivos
                                                                        </button>
                                                                        <button
                                                                            class="btn btn-sm btn-danger exportar_zona"
                                                                            data-motorizado="{{$motorizado->id}}">
                                                                            <i class="fa fa-file-excel"></i>Excel

                                                                        </button>

                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body py-1">
                                                            <div>

                                                                <ul class="nav nav-tabs"
                                                                    style="font-size:11px !important;"
                                                                    id="myTab{{Str::slug($motorizado->zona)}}"
                                                                    role="tablist">
                                                                    <li class="nav-item">
                                                                        <a class="nav-link active"
                                                                           id="recepcionhijo{{Str::slug($motorizado->zona)}}-tab"
                                                                           data-vista="18"
                                                                           data-zona="{{Str::slug($motorizado->zona)}}"
                                                                           data-toggle="tab"
                                                                           href="#recepcionhijo{{Str::slug($motorizado->zona)}}"
                                                                           role="tab"
                                                                           data-tab="recepcionhijo{{Str::slug($motorizado->zona)}}"
                                                                           aria-controls="recepcionhijo{{Str::slug($motorizado->zona)}}"
                                                                           aria-selected="true"
                                                                           data-action="recepcionhijo">
                                                                            RECEPCIÓN
                                                                        </a>
                                                                    </li>
                                                                    <!--<li class="nav-item">
                                                                        <a class="nav-link"
                                                                           id="enrutahijo{{Str::slug($motorizado->zona)}}-tab"
                                                                           data-vista="19"
                                                                           data-zona="{{Str::slug($motorizado->zona)}}"
                                                                           data-toggle="tab"
                                                                           href="#enrutahijo{{Str::slug($motorizado->zona)}}"
                                                                           role="tab"
                                                                           data-tab="enrutahijo{{Str::slug($motorizado->zona)}}"
                                                                           aria-controls="enrutahijo{{Str::slug($motorizado->zona)}}"
                                                                           aria-selected="false"
                                                                           data-action="enrutahijo">
                                                                            EN RUTA
                                                                        </a>
                                                                    </li>-->
                                                                </ul>

                                                                <table
                                                                    id="tablaPrincipal{{Str::upper($motorizado->zona)}}"
                                                                    class="tabla-data table table-hijo table-striped dt-responsive w-100">
                                                                    <thead>
                                                                    <tr>
                                                                        <!--<th scope="col">Item</th>-->
                                                                        <th scope="col">Código</th>
                                                                        <th scope="col">Teléfono</th>
                                                                        <th scope="col">Zona</th>
                                                                        <th scope="col">Distrito</th>
                                                                        <th scope="col">Acciones</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>



                            </div>
                </div>

            @include('pedidos.modal.confirmar_recepcion_log')
            @include('envios.modal.enviarid')
            @include('pedidos.modal.recibirid')

            @include('pedidos.modal.verdireccionid')
            @include('pedidos.modal.editdireccionid')
            @include('pedidos.modal.destinoid')
            @include('pedidos.modal.escaneaqr')
            @include('operaciones.modal.confirmacion')


                <div class="modal fade" id="modal-qr" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px!important;">
                        <div class="modal-content br-16 cnt-shw">

                            <form id="formulario_confirmacion" name="formulariorecepcion" enctype="multipart/form-data">

                                <input type="hidden" id="hiddenCodigo" name="hiddenCodigo">

                                <div class="modal-body">
                                    <h5 class="titulo-confirmacion text-center font-weight-bold" id="exampleModalLabel">
                                        <i
                                            class="fa fa-qrcode" aria-hidden="true"></i>
                                        Escanear Pedido</h5>
                                    <div class="row-element-set row-element-set-QRScanner">

                                        <noscript>
                                            <div class="row-element-set error_message">
                                                Your web browser must have JavaScript enabled
                                                in order for this application to display correctly.
                                            </div>
                                        </noscript>
                                        <div class="row-element-set error_message" id="secure-connection-message"
                                             style="display: none;" hidden>
                                            You may need to serve this page over a secure connection (https) to run
                                            JsQRScanner
                                            correctly.
                                        </div>
                                        <script>
                                            if (location.protocol != 'https:') {
                                                document.getElementById('secure-connection-message').style = 'display: block';
                                            }
                                        </script>

                                        <div class="row-element">
                                            <div class="FlexPanel detailsPanel QRScannerShort">
                                                <div class="FlexPanel shortInfoPanel">
                                                    <div class="text-center">
                                                        Escanea el código QR del sobre
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="cnt-scanner" style="position:relative;">
                                            <div class="qrscanner"
                                                 style="background-color: #222; width:200px; height:200px; margin:auto; border-radius: 16px; overflow:hidden;"
                                                 id="scanner"></div>
                                            <img src="{{asset('images/codigo-qr.png')}}" id="placeholder-qr" style="width: 150px;
    opacity: 0.1;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);">
                                        </div>
                                        <div>
                                            <p class="mb-8 mt-16" id="mensaje-resultado"></p>
                                            <!--<p class="mb-0">PAQUETE: <label id="paquete_ped" class="mb-0">Paquete:</label></p>-->
                                            <table class="w-100">
                                                <tr>
                                                    <td><p class="mb-0 font-weight-bold font-16">CODIGO:</p></td>
                                                    <td><label id="code_ped"
                                                               class="mb-0 font-weight-normal">Codigo</label></td>
                                                </tr>
                                                <tr>
                                                    <td><p class="mb-0 font-weight-bold font-16">DISTRITO:</p></td>
                                                    <td><label id="dist_ped"
                                                               class="mb-0 font-weight-normal">Distrito</label></td>
                                                </tr>
                                                <!--
                                                <tr>
                                                    <td>
                                                        <p class="mb-0 font-weight-bold font-16">DIRECCIÓN: </p>
                                                    </td>
                                                    <td>
                                                        <label id="dir_ped" class="mb-0 font-weight-normal">Dirección</label>
                                                    </td>
                                                </tr>-->
                                            </table>

                                            <p id="detalle_paquete"
                                               class="badge badge-warning font-14 w-100 p-16 mt-12 text-left"></p>
                                            <a href="#" id="recepcion_btn" class="btn btn-warning font-weight-bold"
                                               style="display:none;">Confirmar Pedido</a>

                                            <div class="mt-16">
                                                <!--<textarea id="scannedTextMemo" class="textInput form-memo form-field-input textInput-readonly w-100" rows="3" readonly></textarea>-->
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  {!! Form::label('destino', 'Destino') !!}
                                  {!! Form::select('destino', $destinos , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                </div> --}}
                                <div class="text-center">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                                <!--
                                <div class="modal-footer">

                                </div>-->
                            </form>
                            <hr>

                        </div>
                    </div>
                </div>

                <button type="button" id="btn-qr" class="btn btn-option" data-toggle="modal" data-target="#modal-qr"
                        data-backdrop="static" style="margin-right:16px;" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-qrcode" aria-hidden="true"></i>
                    Escanear QR
                </button>

                <audio id="chatAudio">
                    <source src="{{asset('sonidos/notificacion.mp3')}}" type="audio/mpeg">
                </audio>

                @include('pedidos.modal.barraComparativa')

                <script>
                    if (location.protocol != 'https:') {
                        document.getElementById('secure-connection-message').style = 'display: block';
                    }
                </script>


                @stop

                @section('css')
                    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
                    <link rel="stylesheet"
                          href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
                    <link rel="stylesheet" href="/css/admin_custom.css">


                    <style>
                        .table_custom.toolbar {
                            float: left;
                        }

                        .qr_success {
                            animation: qr_success 1s ease-in forwards;
                        }


                        @keyframes qr_success {
                            0% {
                                box-shadow: 1px 1px 0px green;
                            }

                            70% {
                                box-shadow: 1px 1px 24px green;
                            }

                            100% {

                                box-shadow: 1px 1px 0px green;

                            }
                        }

                        .qrPreviewVideo {
                            width: 100%;
                            width: 100%;
                            border-radius: 16px;
                            margin: auto;
                        }

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
                        .dataTables_filter {
                            display: none;
                        }
                    </style>
                @stop

                @section('js')
                    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
                    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
                    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
                    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

                    <script src="https://momentjs.com/downloads/moment.js"></script>
                    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

                    <script type="text/javascript" src="{{asset('js/jsqrscanner.nocache.js')}}"></script>

                    <script type="text/javascript">

                        var codigo_pedido = false;
                        let tablaPrincipal=null;
                        let pedidos_escaneados=[];

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


                            $(".tabla-data").DataTable().search( valor ).draw();
                            //tabla_pedidos_principal_centro.search( valor ).draw();
                            //tabla_pedidos_principal_sur.search( valor ).draw();
                        }

                        $('#btn_buscar').click(applySearch);
                        $("#buscador_global").bind('paste',function () {
                            setTimeout(applySearch,100)
                        });
                        $('#buscador_global').change(applySearch);
                        $('#buscador_global').keydown(applySearch);

                        function onQRCodeScanned(scannedText) {
                            console.log(arguments)
                            if (codigo_pedido) {
                                return
                            }
                            if(!scannedText){
                                return
                            }
                            if(scannedText=='Requested device not found'){
                                return
                            }
                            //var scannedTextMemo = document.getElementById("scannedTextMemo");
                            //var codigo_pedido = "";
                            $('#scanner').removeClass("qr_success");
                            codigo_pedido = true;
                            setTimeout(function () {
                                $.ajax({
                                    processData: false,
                                    contentType: false,
                                    type: 'POST',
                                    url: "{{ route('envio.escaneoqr',':id') }}".replace(':id', scannedText),
                                    success: function (data) {
                                        console.log(data);
                                        //console.log({{ route('envio.escaneoqr',':id') }}.replace(':id',scannedText));
                                        if (data.html == 0) {
                                            $('#mensaje-resultado').html('<span class="text-danger font-20 font-weight-bold">El pedido ya se encuentra Recibido</span>');
                                            $('#recepcion_btn').css({'display': 'none'});
                                            return false;

                                        } else {

                                            $('#scanner').addClass("qr_success");
                                            $('#mensaje-resultado').html('<span class="text-success font-20 font-weight-bold">Se encontro el pedido</span>');

                                            $('#code_ped').html(data.html);
                                            $('#dist_ped').html(data.distrito);
                                            //$('#dir_ped').html(data.direccion);
                                            $('#recepcion_btn').css({'display': 'block'});
                                            $('#recepcion_btn').data("code", scannedText);
                                            $('#detalle_paquete').html("");

                                            $('#chatAudio')[0].play();

                                            const synth = window.speechSynthesis
                                            if (!synth.pending) {
                                                let text = "Pedido Reconocido"
                                                const utterThis = new SpeechSynthesisUtterance(text)

                                                synth.speak(utterThis)
                                            }

                                            if ($('#recepcion_btn').data('asignado') != 1) {

                                                $('#recepcion_btn').on('click', function () {
                                                    $cod_actual = $(this).data("code");
                                                    $.ajax({
                                                        data: {id: $cod_actual},
                                                        type: 'POST',
                                                        url: "{{ route('envio.recibirpedidomotorizado') }}",
                                                        success: function (data) {

                                                            if (data.html == 0) {
                                                                $('#mensaje-resultado').html('<span class="text-danger font-20 font-weight-bold">El pedido ya se encuentra Recibido</span>');
                                                            } else {
                                                                $('#code_ped').html("");
                                                                $('#dist_ped').html("");
                                                                //$('#dir_ped').html("");
                                                                $('#recepcion_btn').css({'display': 'none'});
                                                                $('#detalle_paquete').html('<h4 class="font-20 font-weight-bold">Pedido Confirmado</h4><ul class="pl-0"><li><span class="text-danger">' + data.sobres_recibidos + '</span> sobres ya fueron confirmados' + '</li><li>' + 'Quedan <span class="text-danger">' + data.sobres_restantes + '</span> por confirmar </li>');
                                                                console.log(data.sobres_recibidos + ' sobres ya fueron cofirmados');
                                                                console.log("Quedan " + data.sobres_restantes + " por confirmar");
                                                                $('#mensaje-resultado').html('<span class="font-20 font-weight-bold">Escanear pedido</span>');
                                                                $('#tablaPrincipal').DataTable().ajax.reload();
                                                                return false;
                                                            }
                                                        }
                                                    });
                                                });
                                                $('#recepcion_btn').data('asignado', 1);
                                                $('#tablaPrincipal').DataTable().ajax.reload();
                                            }
                                        }
                                    }
                                }).always(function () {
                                    codigo_pedido = false;
                                });
                            }, 200);


                            //scannedTextMemo.value = scannedText;

                        }

                        function provideVideo() {
                            var n = navigator;

                            if (n.mediaDevices && n.mediaDevices.getUserMedia) {
                                return n.mediaDevices.getUserMedia({
                                    video: {
                                        facingMode: "environment"
                                    },
                                    audio: false
                                });
                            }

                            return Promise.reject('Your browser does not support getUserMedia');
                        }

                        function provideVideoQQ() {
                            return navigator.mediaDevices.enumerateDevices()
                                .then(function (devices) {
                                    var exCameras = [];
                                    devices.forEach(function (device) {
                                        if (device.kind === 'videoinput') {
                                            exCameras.push(device.deviceId)
                                        }
                                    });

                                    return Promise.resolve(exCameras);
                                }).then(function (ids) {
                                    if (ids.length === 0) {
                                        return Promise.reject('Could not find a webcam');
                                    }

                                    return navigator.mediaDevices.getUserMedia({
                                        video: {
                                            'optional': [{
                                                'sourceId': ids.length === 1 ? ids[0] : ids[1]//this way QQ browser opens the rear camera
                                            }]
                                        }
                                    });
                                });
                        }

                        //this function will be called when JsQRScanner is ready to use
                        function JsQRScannerReady() {
                            //create a new scanner passing to it a callback function that will be invoked when
                            //the scanner succesfully scan a QR code
                            var jbScanner = new JsQRScanner(onQRCodeScanned);
                            //var jbScanner = new JsQRScanner(onQRCodeScanned, provideVideo);
                            //reduce the size of analyzed image to increase performance on mobile devices
                            jbScanner.setSnapImageMaxSize(300);
                            var scannerParentElement = document.getElementById("scanner");
                            if (scannerParentElement) {
                                //append the jbScanner to an existing DOM element
                                jbScanner.appendTo(scannerParentElement);
                            }
                        }
                    </script>

                    <script>
                        $(document).ready(function () {

                            $('#zona-consulta').on('change', function () {

                            });

                            $(document).on('click', '.exportar_zona', function (event) {
                                event.preventDefault();
                                $motorizado = $(this).data('motorizado');
                                $fecha = $("#fecha_consulta").val();
                                $("#user_motorizado").val($motorizado);
                                $("#user_motorizado").selectpicker('refresh')
                                $("#fecha_envio").val($fecha)
                                $("#modal-exportar").modal('show');
                            })

                            $(document).on('click', '.exportar_tabla', function (event) {
                                event.preventDefault();
                                $url = $(this).data('url');
                                //$motorizado = $(this).data('motorizado');
                                $fecha = $("#fecha_consulta").val();
                                //console.log($motorizado);
                                console.log($fecha)

                                //abrir modal y setear valores
                                $("#condicion_envio").val($url);
                                $("#user_motorizado").val('');
                                $("#user_motorizado").selectpicker('refresh')
                                $("#fecha_envio").val($fecha)
                                $("#modal-exportar").modal('show');


                            })


                            function getHtmlPrevisualizarDesagrupar(row, text) {
                                return `
<form>
<div class="card">
    <div class="card-body">
        <div class="col-md-12">
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">
                            <b>${text}</b>
                        </div>
                        <div class="col-4">
                            <b>Codigo</b>
                        </div>
                        <div class="col-4">
                            <b>Razon Social</b>
                        </div>
                    </div>
                </li>
            ${row.pedidos.map(function (pedido) {
                                    return `
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">
                            <input type="checkbox" name="pedidos[]" value="${pedido.id}" style="transform: scale(2);">
                        </div>
                        <div class="col-4">
                            ${pedido.codigo}
                        </div>
                        <div class="col-4">
                            ${pedido.detalle_pedido.nombre_empresa}
                        </div>
                    </div>
                </li>`
                                }).join('')}
            </ul>
        </div>
    </div>
</div>
</form>
`;
                            }


                            function addEventButtonRetornar(row, data) {
                                $("[data-toggle=jqconfirm]", row).unbind()
                                $("[data-toggle=jqconfirm]", row).click(function () {
                                    const action = $(this).data('target')
                                    const actionPost = $(this).data('target-post')
                                    const count = $(this).data('count')
                                    const btncolor = $(this).data('btncolor')
                                    const btntext = $(this).data('btntext')
                                    const isrecibido = $(this).data('recibido') == '1'
                                    $.confirm({
                                        theme:'material',
                                        title: 'Confirmar ' + btntext + ' a PARA REPARTO',
                                        type: btncolor || 'blue',
                                        columnClass: 'xlarge',
                                        content: function () {
                                            const self = this
                                            if (count == '1') {
                                                if (isrecibido) {
                                                    return `<p>Esta seguro de confirmar la recepción del Pedido <strong class="textcode">${data.codigos}</strong></p>  ${data.cambio_direccion_at != null ? `<div class="col-12">
                    <p class="alert alert-warning">Datos de la dirección fueron modificados, ¿desea continuar?.</p>
                  </div>` : ''}`
                                                } else {
                                                    return `<p>Esta seguro de retornar a PARA REPARTO el pedido <strong class="textcode">${data.codigos}</strong></p>`
                                                }
                                            } else {
                                                self.showLoading(true)
                                                return $.get(action).done(function (data) {
                                                    self.setContent(getHtmlPrevisualizarDesagrupar(data.grupo, btntext))
                                                }).always(function () {
                                                    self.hideLoading(true)
                                                })
                                            }
                                        },
                                        buttons: {
                                            no_recibido: {
                                                text: btntext,
                                                btnClass: 'btn-' + btncolor,
                                                action: function () {
                                                    const self = this
                                                    if (count == '1') {
                                                        self.showLoading(true)
                                                        $.post(actionPost)
                                                            .always(function () {
                                                                self.hideLoading(true)
                                                                $('#tablaPrincipal').DataTable().draw(false)
                                                                $(row).parents('table').DataTable().draw(false)
                                                            })
                                                    } else {
                                                        if (!self.$content.find('form').serialize()) {
                                                            $.confirm("Seleccione un pedido")
                                                            return false;
                                                        }
                                                        self.showLoading(true)
                                                        $.post(actionPost, self.$content.find('form').serialize()).done(function () {
                                                            self.close()
                                                        })
                                                            .always(function () {
                                                                self.hideLoading(true)
                                                                $('#tablaPrincipal').DataTable().draw(false)
                                                                $(row).parents('table').DataTable().draw(false)
                                                            })
                                                    }

                                                }
                                            },
                                            cancelar: {}
                                        }
                                    })
                                })
                            }

                            const configDataTableZonas = {
                                serverSide: true,
                                searching: true,
                                lengthChange: false,
                                order: [[0, "desc"]],
                                createdRow: function (row, data, dataIndex) {

                                },
                                rowCallback: function (row, data, index) {
                                    addEventButtonRetornar(row, data)
                                },
                                'columnDefs': [
                                    {responsivePriority: 1, targets: 0},
                                    {responsivePriority: 5, targets: 1},
                                    {responsivePriority: 3, targets: 2},
                                    {responsivePriority: 4, targets: 3},
                                    {responsivePriority: 2, targets: 4}
                                ],
                                columns: [
                                    {data: 'codigos', name: 'codigos',},
                                    {data: 'celular', name: 'celular',},
                                    {data: 'distribucion', name: 'distribucion',},
                                    {data: 'distrito', name: 'distrito',},
                                    {data: 'action', name: 'action',sWidth:'5%'},

                                ],
                                language: {
                                    "decimal": "",
                                    "emptyTable": "No hay información",
                                    "info": "_START_ - _END_ / _TOTAL_",
                                    "infoEmpty": "0 Entradas",
                                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                                    "infoPostFix": "",
                                    "thousands": ",",
                                    "lengthMenu": "Mostrar _MENU_ Entradas",
                                    "loadingRecords": "Cargando...",
                                    "processing": ``,
                                    "search": "Buscar:",
                                    "zeroRecords": "Sin resultados encontrados",
                                    "paginate": {
                                        "first": "Primero",
                                        "last": "Ultimo",
                                        "next": "Siguiente",
                                        "previous": "Anterior"
                                    }
                                },
                            }

                            @foreach($motorizados as $motorizado)
                            $('#tablaPrincipal{{Str::upper($motorizado->zona)}}').DataTable({
                                ...configDataTableZonas,
                                "bFilter": false,
                                ajax: {
                                    url: "{{route('envios.recepcionmotorizadotablageneral',['datatable'=>'1'])}}",
                                    data: function (a) {
                                        a.consulta = 'paquete';
                                        a.fechaconsulta = $("#fecha_consulta").val();
                                        a.tab = $("#myTab{{Str::slug($motorizado->zona)}} li>a.active").data('tab');
                                        a.motorizado_id = {{ $motorizado->id }};
                                        a.zona = "{{ Str::upper($motorizado->zona)}}";
                                        //let vista = 18;//18 19
                                        //vista = $('#' + vista).data('url');
                                        a.vista = 19;
                                    }
                                },
                            });
                            $('#tablaPrincipal{{Str::upper($motorizado->zona)}}').DataTable()
                                .on('responsive-display', function (e, datatable, row, showHide, update) {
                                    console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
                                    if (showHide) {
                                        addEventButtonRetornar($(row.node()).siblings('.child'), row.data())
                                    }
                                });
                            @endforeach

                            @foreach($motorizados as $motorizado)
                            var tt = $("#myTab{{Str::upper($motorizado->zona)}}")[0];

                            $('a[data-toggle="tab"]', tt).on('shown.bs.tab', function (e) {
                                let zona = $(this).data('zona');
                                $('#tablaPrincipal{{Str::upper($motorizado->zona)}}').DataTable().ajax.reload();
                            })
                            @endforeach


                            $(document).on("submit", "#form_recepcionmotorizado", function (event) {
                                let user_motivov = $("#user_motorizado").val();
                                let fecha_env = $("#fecha_envio").val();
                                //console.log(fecha_env)
                                if (!user_motivov) {
                                    Swal.fire(
                                        'Error',
                                        'Seleccione el usuario a generar el reporte.',
                                        'warning'
                                    )
                                    event.preventDefault();
                                    return true;
                                } else if (!fecha_env) {
                                    Swal.fire(
                                        'Error',
                                        'Elija una fecha de reporte.',
                                        'warning'
                                    )
                                    event.preventDefault();
                                    return true;
                                }

                            });
                            var timeoutMessage;
                            $("#fecha_consulta").on('change', function () {
                                console.log($(this).val());
                                console.log(new Date())

                                var dateA = moment($(this).val());
                                var dateB = moment(new Date());
                                let diff = dateB.diff(dateA, 'days')
                                console.log('Diferencia es ', diff, 'days');

                                //mostrar alert
                                if (timeoutMessage) {
                                    clearTimeout(timeoutMessage)
                                }
                                timeoutMessage = setTimeout(function () {
                                    Swal.fire(
                                        'Warning',
                                        'La fecha colocada es posterior o anterior a la fecha habitual (1 dia)',
                                        'warning'
                                    )
                                }, 300)

                                //var fecha_formateada = $(this).val().replaceAll('-', '/');
                                var fecha_format = $(this).val().split("-")
                                var fecha_formateada = fecha_format[2] + "/" + fecha_format[1] + "/" + fecha_format[0];
                                $(this).data('fecha', fecha_formateada);
                                console.log(fecha_formateada);

                                $('.tabla-data').DataTable().ajax.reload();
                            });

                            /*switch ($('ul#myTab li.nav-item>a.active').attr('id')) {
                                case 'recepcion-tab':
                                    $('.count_recepcionmotorizados_inroutes_courier').html(0);
                                    $('.count_recepcionmotorizados_receptioned_courier').html(this.fnSettings().fnRecordsDisplay());
                                    $('div.toolbar').html('<div class="d-flex justify-content-center">' +
                                        '<button class="btn btn-success exportar_tabla" data-url="19">EXPORTAR RECEPCION</button>' +
                                        '</div>');
                                    @foreach($motorizados as $motorizado)
                                    $('#recepcionhijo{{Str::slug($motorizado->zona)}}-tab').tab('show');
                                    @endforeach
                                        break;
                                case 'enruta-tab':
                                    $('.count_recepcionmotorizados_receptioned_courier').html(0);
                                    $('.count_recepcionmotorizados_inroutes_courier').html(this.fnSettings().fnRecordsDisplay());
                                    $('div.toolbar').html('<div class="d-flex justify-content-center">' +
                                        '<button class="btn btn-secondary exportar_tabla" data-url="18">EXPORTAR RUTA MASIVA</button>' +
                                        '<button id="iniciar-ruta-masiva" class="btn btn-success">INICIAR RUTA MASIVA</button>' +
                                        '</div>');
                                    @foreach($motorizados as $motorizado)
                                    $("#enrutahijo{{Str::slug($motorizado->zona)}}-tab").tab('show');
                                    @endforeach
                                        break;
                            }*/


                            $('.condicion-tabla').on('click', function () {
                                $('.condicion-tabla').removeClass("activo");
                                $(this).addClass("activo");
                                //var url = $(this).data("url");
                                $('#tablaPrincipal').DataTable().ajax.reload();

                                var $activeItem = $('.nav .active').html();
                                console.log($activeItem);

                                var id = $('.condicion-tabla.active').attr('id');
                                console.log(id)//profile-tab   home-tab
                                if ($('.condicion-tabla.active').attr('id') == 'home-tab') {

                                    $('div.toolbar').html('<div class="d-flex justify-content-center"><button id="iniciar-ruta-masiva" class="btn btn-success">Iniciar RUTA MASIVA</button></div>');

                                } else {
                                    $('div.toolbar').html('');
                                }
                                //if ( ! $.fn.DataTable.isDataTable( '#tablaPrincipal' ) ) {
                                //}

                            });

                            /************
                             * ESCANEAR PEDIDO
                             */

                            $('#modal-escanear').on('shown.bs.modal', function () {
                                $('#codigo_confirmar').focus();
                                $('#codigo_accion').val("fernandez");
                                $('#titulo-scan').html("Escanear para confirmar la <span class='text-success'>Recepción de sobres</span>");
                                $('#modal-escanear').on('click', function () {
                                    console.log("focus");
                                    $('#codigo_confirmar').focus();

                                    return false;
                                });
                            })

                            $('#codigo_confirmar').change(function (event) {
                                event.preventDefault();
                                var codigo_caturado = $(this).val();
                                var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-').replaceAll("'", '-').replaceAll("(", '*');
                                var codigo_accion = $('#codigo_accion').val();
                                console.log("El codigo es: " + codigo_mejorado);
                                /*************
                                 * Enviamos la orden al controlaor
                                 * @type {FormData}
                                 */
                                var fd_scan = new FormData();

                                fd_scan.append('hiddenCodigo', codigo_mejorado);
                                fd_scan.append('accion', codigo_accion);

                                $.ajax({
                                    data: fd_scan,
                                    processData: false,
                                    contentType: false,
                                    type: 'POST',
                                    url: "{{ route('operaciones.confirmaropbarras') }}",
                                    success: function (data) {
                                        console.log(data);
                                        $('#respuesta_barra').removeClass("text-danger");
                                        $('#respuesta_barra').removeClass("text-success");
                                        $('#respuesta_barra').addClass(data.class);
                                        $('#respuesta_barra').html(data.html);
                                    }
                                });

                                $(this).val("");
                                return false;
                            });

                            /***********
                             * FIN ESCANEAR MOUSE
                             */

                            $('#modal-scan-comparador').on('show.bs.modal', function (event) {
                                var button = $(event.relatedTarget)
                                var zona = button.data('zona');
                                pedidos_escaneados=[];
                                $("#codigo_comprobar").val('')

                                $.ajax({
                                    //processData: false,
                                    //contentType: false,
                                    type: 'POST',
                                    url: "{{ route('operaciones.comparacionmotorizado') }}",
                                    data: {
                                        'fechaconsulta':$("#fecha_consulta").val(),
                                        'motorizado_id' : button.data('motorizado'),
                                        'zona' : button.data('zona'),
                                            },
                                    success: function (data) {
                                        if(data.codigo == 0){
                                            Swal.fire(
                                                'Error',
                                                'El pedido no se encontro',
                                                'warning'
                                            )
                                        }else{
                                            var lista = "";
                                            jQuery.each(data.grupo, function(index, item) {
                                                lista += '<div id="'+item+'" class="item_recepcionado col-lg-6"><i class="fa fa-envelope text-warning mr-8" aria-hidden="true"></i> '+item+'</div>';
                                                //$('#pedidos-recepcion').append('<li id="'+item+'" class="item_recepcionado">'+item+'</li>');
                                            });
                                            $('#pedidos-recepcion').html(lista);
                                        }
                                    }
                                });

                                $('#codigo_comprobar').change(function (event) {
                                    event.preventDefault();
                                    console.log("evento ");

                                    var codigo_caturado = ($(this).val() || '').trim();
                                    var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-').replaceAll("'", '-').replaceAll("(", '*');
                                    console.log("codigo_mejorado"+codigo_mejorado);
                                    console.log("lista de pedidos escaneados")
                                    console.log(pedidos_escaneados);

                                    if($.inArray(codigo_mejorado, pedidos_escaneados) !== -1)
                                    {
                                        console.log("codigo se encuentra repetido en lista");
                                        console.log(pedidos_escaneados);
                                    }else{
                                        if($('#'+codigo_mejorado).length===0){
                                            return;
                                        }
                                        console.log("codigo encontrado");
                                        $('#'+codigo_mejorado).fadeOut();
                                        $("#pedidos-escaneados").append('<div class="col-lg-6"><i class="fa fa-check text-success mr-8" aria-hidden="true"></i>'+ codigo_mejorado +'</div>');
                                        pedidos_escaneados.push(codigo_mejorado);
                                        console.log("nuevo lista pedidos escaneados")
                                        console.log(pedidos_escaneados)
                                        //limpio campo
                                        $("#codigo_comprobar").val('');
                                        //comprobar cuantos faltanm
                                        count_ped_=$('#pedidos-recepcion .item_recepcionado').length;//total pedidos
                                        count_ped =$('#pedidos-recepcion .item_recepcionado[style*="display: none"]').length;//pe ocultos
                                        console.log(count_ped);
                                        let calc_=count_ped_-count_ped;
                                        if(calc_==0)
                                        {
                                            pedidos_escaneados=[];
                                            //evento cerrar
                                        }
                                    }

                                    /*if($.inArray(codigo_mejorado,pedidos_escaneados))
                                    {

                                    }else{

                                    }*/

                                    //$('.item_recepcionado').each(function(){
                                        //var ide = $(this).attr('id');
                                        //valida duplicado

                                        //if(ide == codigo_mejorado){

                                        //}


                                    //});
/*
                                    $('#'+codigo_mejorado).fadeOut();
                                    $("#pedidos-escaneados").append('<li>'+ codigo_mejorado +'</li>');

 */
                                    //return false;
                                });
                            });

                            $('#modal-confirmacion').on('show.bs.modal', function (event) {
                                var button = $(event.relatedTarget)
                                var idunico = button.data('ide')
                                var codigos = button.data('codigos')

                                $('.titulo-confirmacion').html("Enviar sobre a Motorizado");

                                $("#hiddenCodigo").val(idunico)
                                $("#modal-confirmacion .textcode").html(codigos);
                            });

                            $(document).on("submit", "#formulario_confirmacion", function (evento) {
                                evento.preventDefault();
                                //validacion

                                var fd2 = new FormData();
                                fd2.append('hiddenCodigo', $('#hiddenCodigo').val());
                                $.ajax({
                                    data: fd2,
                                    processData: false,
                                    contentType: false,
                                    type: 'POST',
                                    url: "{{ route('operaciones.confirmarrecepcionmotorizado') }}",
                                    success: function (data) {
                                        $("#modal-confirmacion").modal("hide");
                                        $('#tablaPrincipal').DataTable().ajax.reload();

                                    }
                                });
                            });

                            $('#modal-envio').on('show.bs.modal', function (event) {
                                //cuando abre el form de anular pedido
                                var button = $(event.relatedTarget)
                                var idunico = button.data('recibir')

                                var codigos = button.data('codigos')
                                var accion = button.data('accion')

                                $(".textcode").html(codigos);
                                $("#hiddenEnvio").val(idunico);
                                $("#hiddenAccion").val(accion);

                                if (accion == "recibir") {
                                    $('#titulo_modal').html("Confirmar recepción");
                                    $('#mensaje_modal').html("Esta seguro de confirmar la recepción del Pedido <b>" + codigos + "</b>");
                                } else if (accion == "rechazar") {
                                    $('#titulo_modal').html("Rechazar recepción");
                                    $('#mensaje_modal').html("Esta seguro de rechazar el recibido el pedido <b>" + codigos + "</b>");
                                }


                            });

                            $(document).on("submit", "#formulariorecepcion", function (evento) {
                                evento.preventDefault();
                                var fd = new FormData();
                                var data = new FormData(document.getElementById("formulariorecepcion"));

                                fd.append('hiddenEnvio', $("#hiddenEnvio").val());

                                $.ajax({
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    type: 'POST',
                                    url: "{{ route('envios.recepcionarmotorizado') }}",
                                    success: function (data) {
                                        console.log(data);
                                        $("#modal-envio .textcode").text('');
                                        $("#modal-envio").modal("hide");
                                        $('#tablaPrincipal').DataTable().ajax.reload();
                                    }
                                });
                            });


                            $(document).on("submit", "#formulario", function (evento) {
                                evento.preventDefault();
                                var fd = new FormData();

                            });

                            $('#modal-enviar').on('show.bs.modal', function (event) {
                                //cuando abre el form de anular pedido
                                var button = $(event.relatedTarget)
                                var idunico = button.data('enviar')//pedido
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
                            });

                            $(document).on("submit", "#formulariorecibir", function (evento) {
                                evento.preventDefault();
                                var formData = $("#formulariorecibir").serialize();

                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('envios.recibirid') }}",
                                    data: formData,
                                }).done(function (data) {
                                    if (data.html != 0) {
                                        $("#modal-recibir").modal("hide");
                                        $('#tablaPrincipal').DataTable().ajax.reload();
                                    } else {

                                    }
                                    /*
                                    //resetearcamposdelete();
                                     */
                                });

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

                        function maxLengthCheck(object) {
                            if (object.value.length > object.maxLength)
                                object.value = object.value.slice(0, object.maxLength)
                        }

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

                            $("#download_rotulos").click(function () {
                                const url = $(this).data('href')
                                window.open(url+'?fecha_salida='+$("#fecha_consulta").val(), '_blank');
                            })

                        });
                    </script>

@stop
