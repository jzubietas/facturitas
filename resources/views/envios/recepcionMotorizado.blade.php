@extends('adminlte::page')

@section('title', 'Lista de pedidos por confirmar')

@section('content_header')
  <h1>Recepcion para motorizados - ENVIOS
    {{-- <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosporenviarExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
      </div>
    </div> --}}
    {{-- @can('clientes.exportar') --}}
    <div class="float-right btn-group dropleft">
        <button type="button" class="btn btn-option" data-toggle="modal" data-target="#modal-qr" data-backdrop="static" style="margin-right:16px;" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-qrcode" aria-hidden="true"></i>
            Escanear QR
        </button>
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
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
        .activo{
            background-color: #e74c3c !important;
            color: white !important;
            border: 0 !important;
        }
    </style>
  <div class="card w-100">
    <div class="card-body">

        <ul class="nav nav-tabs mb-24 mt-24" id="myTab" role="tablist">
            <li class="nav-item w-50 text-center">
                <a class="condicion-tabla nav-link activo active font-weight-bold" id="home-tab" data-toggle="tab" data-url="19" href="#home" role="tab" aria-controls="home" aria-selected="true">RECEPCION</a>
            </li>
            <li class="nav-item w-50 text-center">
                <a class="condicion-tabla nav-link font-weight-bold" id="profile-tab" data-toggle="tab" data-url="18" href="#profile" role="tab" aria-controls="profile" aria-selected="false">EN RUTA</a>
            </li>
        </ul>
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
      <table id="tablaPrincipal" class="table table-striped dt-responsive w-100">
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Asesor</th>
            <th scope="col">Cliente</th>
            <th scope="col">Razón social</th>
            <th scope="col">Fecha de registro</th>

            <th scope="col">Dirección de envío</th>
            <th scope="col">Estado de envio</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('pedidos.modal.confirmar_recepcion_log')
      @include('envios.modal.enviarid')
      @include('pedidos.modal.recibirid')

      @include('pedidos.modal.verdireccionid')
      @include('pedidos.modal.editdireccionid')
      @include('pedidos.modal.destinoid')
    @include('pedidos.modal.escaneaqr')
    @include('operaciones.modal.confirmacion')

    </div>
  </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-qr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px!important;">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="titulo-confirmacion"  id="exampleModalLabel"><i class="fa fa-qrcode" aria-hidden="true"></i>
                         Escanear Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- Form::Open(['route' => ['pedidos.atender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
                <form id="formulario_confirmacion" name="formulariorecepcion" enctype="multipart/form-data">
                    {{-- Form::Open(['route' => ['pedidos.envio', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
                    <input type="hidden" id="hiddenCodigo" name="hiddenCodigo">

                    <div class="modal-body">

                        <div class="row-element-set row-element-set-QRScanner">
                            <!-- RECOMMENDED if your web app will not function without JavaScript enabled -->
                            <noscript>
                                <div class="row-element-set error_message">
                                    Your web browser must have JavaScript enabled
                                    in order for this application to display correctly.
                                </div>
                            </noscript>
                            <div class="row-element-set error_message" id="secure-connection-message" style="display: none;" hidden >
                                You may need to serve this page over a secure connection (https) to run JsQRScanner correctly.
                            </div>
                            <script>
                                if (location.protocol != 'https:') {
                                    document.getElementById('secure-connection-message').style='display: block';
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
                            <div class="cnt-scanner">
                                <div class="qrscanner"  style="background-color: #222; width:300px; height:300px; margin:auto; border-radius: 16px;" id="scanner">
                                </div>
                            </div>
                            <div>
                                <p class="mb-8 mt-16">Pedido Encontrado</p>
                                <p class="mb-0">CODIGO: <label id="code_ped" class="mb-0">Codigo:</label></p>
                                <p class="mb-0">DISTRITO: <label id="dist_ped" class="mb-0">Distrito</label></p>
                                <p class="mb-0">DIRECCIÓN: <label id="dir_ped" class="mb-0">Dirección</label></p>
                                <a href="#" id="recepcion_btn" class="btn btn-warning font-weight-bold">Confirmar Pedido</a>
                                <div class="mt-16">
                                <textarea id="scannedTextMemo" class="textInput form-memo form-field-input textInput-readonly w-100" rows="3" readonly></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      {!! Form::label('destino', 'Destino') !!}
                      {!! Form::select('destino', $destinos , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        if (location.protocol != 'https:') {
            document.getElementById('secure-connection-message').style='display: block';
        }
    </script>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/css/admin_custom.css">


  <style>

      .qrPreviewVideo{width:100%; max-width:400px; max-width:300px; border-radius: 16px; margin:auto;}

    img:hover{
      transform: scale(1.2)
    }

    .bg-4{
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
      function onQRCodeScanned(scannedText)
      {
          var scannedTextMemo = document.getElementById("scannedTextMemo");
          if(scannedTextMemo)
          {
              $.ajax({
                  processData: false,
                  contentType: false,
                  type: 'POST',
                  url: "{{ route('envio.escaneoqr',':id') }}".replace(':id',scannedText),
                  success: function (data) {
                      console.log(data);
                      console.log({{ route('envio.escaneoqr',':id') }}.replace(':id',scannedText));
                      alert("Pedido " + scannedText + " encontrado!");
                      $('#code_ped').html(data.html);
                      $('#dist_ped').html(data.distrito);
                      $('#dir_ped').html(data.direccion);

                      $('#recepcion_btn').on('click', function (){
                          $.ajax({
                              data:{hiddenEnvio: data.html},
                              type: 'POST',
                              url: "{{ route('envios.recepcionarmotorizado') }}",
                              success: function (data) {
                                  $('#code_ped').html("");
                                  $('#dist_ped').html("");
                                  $('#dir_ped').html("data.direccion");
                                  consolle.log("Pedido recepcionado");
                              }
                          });
                      });
                  }
              });
              scannedTextMemo.value = scannedText;
          }
      }

      function provideVideo()
      {
          var n = navigator;

          if (n.mediaDevices && n.mediaDevices.getUserMedia)
          {
              return n.mediaDevices.getUserMedia({
                  video: {
                      facingMode: "environment"
                  },
                  audio: false
              });
          }

          return Promise.reject('Your browser does not support getUserMedia');
      }

      function provideVideoQQ()
      {
          return navigator.mediaDevices.enumerateDevices()
              .then(function(devices) {
                  var exCameras = [];
                  devices.forEach(function(device) {
                      if (device.kind === 'videoinput') {
                          exCameras.push(device.deviceId)
                      }
                  });

                  return Promise.resolve(exCameras);
              }).then(function(ids){
                  if(ids.length === 0)
                  {
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
      function JsQRScannerReady()
      {
          //create a new scanner passing to it a callback function that will be invoked when
          //the scanner succesfully scan a QR code
          var jbScanner = new JsQRScanner(onQRCodeScanned);
          //var jbScanner = new JsQRScanner(onQRCodeScanned, provideVideo);
          //reduce the size of analyzed image to increase performance on mobile devices
          jbScanner.setSnapImageMaxSize(300);
          var scannerParentElement = document.getElementById("scanner");
          if(scannerParentElement)
          {
              //append the jbScanner to an existing DOM element
              jbScanner.appendTo(scannerParentElement);
          }
      }
  </script>

  <script>
    $(document).ready(function () {

        $('.condicion-tabla').on('click', function (){
            $('.condicion-tabla').removeClass("activo");
            $(this).addClass("activo");
            //var url = $(this).data("url");
            $('#tablaPrincipal').DataTable().ajax.reload();

        });

        /************
         * ESCANEAR PEDIDO
         */

        $('#modal-escanear').on('shown.bs.modal', function () {
            $('#codigo_confirmar').focus();
            $('#codigo_accion').val("fernandez");
            $('#titulo-scan').html("Escanear para confirmar la <span class='text-success'>Recepción de sobres</span>");
            $('#modal-escanear').on('click', function(){
                console.log("focus");
                $('#codigo_confirmar').focus();

                return false;
            });
        })

        $('#codigo_confirmar').change(function (event) {
            event.preventDefault();
            var codigo_caturado = $(this).val();
            var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-');
            var codigo_accion = $('#codigo_accion').val();
            console.log("El codigo es: " + codigo_mejorado);
            /*************
             * Enviamos la orden al controlaor
             * @type {FormData}
             */
            var fd_scan = new FormData();

            fd_scan.append( 'hiddenCodigo', codigo_mejorado );
            fd_scan.append( 'accion', codigo_accion );

            $.ajax({
                data: fd_scan,
                processData: false,
                contentType: false,
                type: 'POST',
                url:"{{ route('operaciones.confirmaropbarras') }}",
                success:function(data)
                {
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

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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

        $(".textcode").html(codigos);
        $("#hiddenEnvio").val(idunico);

      });

      $(document).on("submit", "#formulariorecepcion", function (evento) {
        evento.preventDefault();
        var fd = new FormData();
        var data = new FormData(document.getElementById("formulariorecepcion"));

        fd.append( 'hiddenEnvio', $("#hiddenEnvio").val() );

        $.ajax({
           data: data,
           processData: false,
           contentType: false,
           type: 'POST',
           url:"{{ route('envios.recepcionarmotorizado') }}",
           success:function(data)
           {
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
        if(idunico<10){
          idunico='PED000'+idunico;
        }else if(idunico<100){
          idunico= 'PED00'+idunico;
        }else if(idunico<1000){
          idunico='PED0'+idunico;
        }else{
          idunico='PED'+idunico;
        }
        $("#modal-enviar .textcode").html(idunico);

      });

      $('#modal-recibir').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('recibir')//pedido
        $("#hiddenRecibir").val(idunico)
        if(idunico<10){
          idunico='PED000'+idunico;
        }else if(idunico<100){
          idunico= 'PED00'+idunico;
        }else if(idunico<1000){
          idunico='PED0'+idunico;
        }else{
          idunico='PED'+idunico;
        }
        $("#modal-recibir .textcode").html(idunico);


      });

      $(document).on("submit", "#formularioenviar", function (evento) {
        evento.preventDefault();
      });

      $(document).on("submit", "#formulariorecibir", function (evento) {
        evento.preventDefault();
        var formData=$("#formulariorecibir").serialize();

        $.ajax({
            type:'POST',
            url:"{{ route('envios.recibirid') }}",
            data:formData,
        }).done(function (data) {
            if(data.html!=0)
            {
                $("#modal-recibir").modal("hide");
                $('#tablaPrincipal').DataTable().ajax.reload();
            }else{

            }
            /*
            //resetearcamposdelete();
             */
        });

      });


      /*$('#modal-atender').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('atender')
        $(".textcode").html("PED"+idunico);
        $("#hiddenAtender").val(idunico);
      });*/

      $('#tablaPrincipal').DataTable({
        processing: true,
        stateSave:true,
		serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax:{ url: "{{ route('envios.recepcionmotorizadotabla') }}",
                  data: function(d){

                      d.condicion = $('.condicion-tabla.activo').data("url");
                  }
          },
        createdRow: function( row, data, dataIndex){
          //console.log(row);
        },
        rowCallback: function (row, data, index) {
        },
        columns: [
          {
              data: 'correlativo',
              name: 'correlativo',
          },
          {data: 'codigos', name: 'codigos', },
          {data: 'user_id', name: 'user_id', },
          {
            data: 'celular',
            name: 'celular',
            render: function ( data, type, row, meta ) {
              return row.celulares+' - '+row.nombres
            },
            "visible":false
            //searchable: true
        },
          {data: 'producto', name: 'producto'},
          {data: 'fecha_formato', name: 'fecha_formato'},
          {
            data:'direccion',
            name:'direccion',"visible":false,
            render: function ( data, type, row, meta ) {
              //console.log(data);
              datas='';
              if(data!=null)
              {
                return data;
                /*if(data=='0')
                {
                  return '<span class="badge badge-danger">REGISTRE DIRECCION</span>';
                }else if(data=='LIMA')
                {
                  var urlshow = '{{ route("pedidos.show", ":id") }}';
                  urlshow = urlshow.replace(':id', row.id);

                  return '<a href="" data-target="#modal-verdireccion" data-toggle="modal" data-dirreccion="'+row.id+'"><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                }
                else if(data=='PROVINCIA')
                {
                  return '<span class="badge badge-info">ENVIO A PROVINCIA</span>';
                }else{
                  return '<span class="badge badge-info">PROBLEMAS CON REGISTRO DE DESTINO</span>';
                }
*/
                //return datas;

              }else{
                return 'REGISTRE DIRECCION';
              }
              //return 'REGISTRE DIRECCION';
            },
          },
          {
              data: 'condicion_envio',
              name: 'condicion_envio',

          },

          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'20%',

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
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
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
        }
        else if (foto2 == '' && pfoto2 == ''){
          Swal.fire(
            'Error',
            'Para dar por ENTREGADO debe registrar la foto 2',
            'warning'
          )
        }
        else {
        this.submit();
        }
      }
      else {
        this.submit();
      }
    }
  </script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {


            $("#destino", this).on( 'keyup change', function () {
              if ( table.column(i).search() !== this.value ) {
                  table
                      .column(8)
                      .search( this.value )
                      .draw();
                }
            } );

        });
  </script>

@stop
