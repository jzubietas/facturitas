@extends('adminlte::page')

@section('title', 'Operaciones | Sobres terminados')

@section('content_header')
  <h1>Lista de pedidos ENTREGADOS - OPERACIONES

    <div class="float-right btn-group dropleft">
        <button type="button" class="btn btn-option" data-accion="confirmacion_operaciones" data-responsable="maria_recepcion" data-toggle="modal" data-target="#modal-escanear" data-backdrop="static" style="margin-right:16px;" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-barcode" aria-hidden="true"></i> RECEPCIONAR PEDIDOS
        </button>
        <button type="button" class="btn btn-option" data-accion="envio_courier_operaciones" data-responsable="maria_courier" data-toggle="modal" data-target="#modal-escanear" data-backdrop="static" style="margin-right:16px;" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-barcode" aria-hidden="true"></i> ENVIAR A COURIER
        </button>

      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos entregados', 'key' => '10'])
  </h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <table style="display: none;" cellspacing="5" cellpadding="5">
        <tbody>
          <tr>
            <td>Minimum date:</td>
            <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
            <td> </td>
            <td>Maximum date:</td>
            <td><input type="text" value={{ $dateMax }} id="max" name="max"  class="form-control"></td>
          </tr>
        </tbody>
      </table><br>
      <table id="tablaOperacionesJefeOperaciones" class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th scope="col" class="align-middle">Item</th>
            <th scope="col" class="align-middle">Código</th>
            <th scope="col" class="align-middle">Razón social</th>
            <th scope="col" class="align-middle">Asesor</th>
            <th scope="col" class="align-middle">Fecha de registro</th>
            <th scope="col" class="align-middle">Estado</th>
            <th scope="col" class="align-middle">Atendido por</th>
            <th scope="col" class="align-middle">Jefe</th>
            <th scope="col" class="align-middle">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
        @include('pedidos.modal.confirmarecepcion')
      @include('pedidos.modal.atender_pedido_op')
      @include('pedidos.modal.revertirporenviar')
        @include('pedidos.modal.escaneaqr')
    </div>
  </div>

@stop

@push('css')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  <style>

      table {
          display: table;
          border-collapse:separate;
          border-spacing: 0px;
          border:0px;
      }

      table td {
          margin: 0px;
          padding:0px;
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
@endpush

@section('js')
  {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>



  <script>
    $(document).ready(function () {
        /************
         * ESCANEAR PEDIDO
         */
        $('#modal-escanear').on('hidden.bs.modal', function (e) {
            $('#respuesta_barra').html("");
            $('#pedidos-procesados').html("");

        })
        $('#modal-escanear').on('shown.bs.modal', function (event) {

            var button = $(event.relatedTarget)
            var accion = button.data('accion')
            var responsable = button.data('responsable')
             if(accion == "confirmacion_operaciones"){
                 $('#titulo-scan').html("Confirmar <span class='text-success'>Sobres</span>");
             }else if (accion == "envio_courier_operaciones"){
                 $('#titulo-scan').html("Escanear para enviar a <span class='text-success'>Courier</span></span>");
             }
            //$('#codigo_confirmar').data('action',accion);

            $('#respuesta_barra').html("");

            $('#codigo_confirmar').focus();

            $('#codigo_accion').val(accion);
            $('#codigo_responsable').val(responsable);

            $('#modal-escanear').on('click', function(){
                console.log("focus");
                $('#codigo_confirmar').focus();

                return false;
            });

            $('#close-scan').on('click', function (){
                console.log("actualizamos la tabla");
                $('#tablaOperacionesJefeOperaciones').DataTable().draw(false);
            });
        })

        codigos_agregados = []
        var data = {}
        $('#codigo_confirmar').change(function (event) {
            event.preventDefault();


            var codigo_caturado = $(this).val();
            var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-').replaceAll("'", '-').replaceAll("(", '*');
            var codigo_accion = $('#codigo_accion').val();
            var codigo_responsable = $('#codigo_responsable').val();

            console.log(codigo_accion);
            console.log("El codigo es: " + codigo_mejorado);


            data.codigo = codigo_mejorado
            data.accion = codigo_accion
            data.responsable = codigo_responsable

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('operaciones.validaropbarras') }}",
                success: function (data) {
                    if (data.error == 0) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido identificado',
                            color: '#FFF',
                            background: '#79b358',
                            showConfirmButton: false,
                            timer: 600
                        })
                        codigos_agregados.push(data.codigo);
                        codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)
                    }else
                    if (data.error == 1) {
                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html +'</b></span>');

                        Swal.fire({
                            icon: 'warning',
                            title: 'El Pedido ya se procesó anteriormente.',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 600
                        })

                    }else if(data.error == 4){
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido no se encontró en el sistema',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');

                    }
                    else if(data.error == 5){
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido esta anulado',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');
                    }else if(data.error == 6){
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'Pedido Pendiente de anulación',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');

                    }

                    $('#pedidos-procesados').html(`<p><b class="text-success w-100">codigos Escaneados (${codigos_agregados.length}):</b></p><ul>${codigos_agregados.map(function (codigo) {
                        return `<li><i class="fa fa-check text-success"></i> ${codigo}</li>`
                    }).join('')}</ul><br>`);

                }
            }).always(function () {
                $('#codigo_confirmar').focus();
            });

            $(this).val("");
        });

        $("#close-scan").click(function (e) {
            e.preventDefault();
            console.log(codigos_agregados)

            if (codigos_agregados.length === 0) {
                return;
            }

            data.codigos = codigos_agregados

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('operaciones.confirmaropbarras') }}",
                success: function (data) {

                    console.log(data.codigos_procesados);
                    $('#respuesta_barra').html("");

                    codigos_agregados = []

                    var codigos_procesados = data.codigos_procesados
                    var codigos_no_procesados = data.codigos_no_procesados

                    if(data.error == 1){

                        Swal.fire({
                            icon: 'error',
                            title: 'El Pedido ya se procesó anteriormente',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html +'</b></span>');

                    }else if(data.error == 4){

                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido no se encontró en el sistema',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');

                    }
                    else if(data.error == 5){

                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido esta anulado',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');

                    }else if(data.error == 6){

                        Swal.fire({
                            icon: 'error',
                            title: 'Pedido Pendiente de anulación',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');

                    }else if(data.error == 0){

                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido Procesado correctamente',
                            color: '#FFF',
                            background: '#79b358',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="'+ data.class +'">'+ data.html + '</span>');

                        setTimeout(function (){
                            $('#respuesta_barra').fadeOut();
                        },600);

                    }

                    $('#pedidos-procesados').html("");
                    $('#modal-escanear').modal('hide');

                    /*
                                        $('#pedidos-procesados').html(`<p><b class="text-success w-100">codigos procesados (${codigos_procesados.length}):</b></p><ul>${codigos_procesados.map(function (codigo) {
                                            return `<li><i class="fa fa-check text-success"></i> ${codigo}</li>`
                                        }).join('')}</ul><br>`);

                                        $('#pedidos-procesados').append(`<p><b class="text-danger w-100">codigos no procesados (${codigos_no_procesados.length}): </b></p><ul>${codigos_no_procesados.map(function (codigo) {
                                            return `<li><i class="fa fa-window-close text-danger"></i> ${codigo}</li>`
                                        }).join('')}</ul><br>`);

                     */
                    /*
                    $('#respuesta_barra').removeClass("text-danger");
                    $('#respuesta_barra').removeClass("text-success");
                    $('#respuesta_barra').addClass(data.class);
                    $('#respuesta_barra').html(data.html);

                    setTimeout(function(){
                        console.log("cerrar modal");
                        $('#pedidos-procesados').html("");
                        $('#modal-escanear').modal('hide');
                    },300); */

                    if (codigos_agregados.length === 0) {
                        //$('#modal-escanear').modal('hide')
                    }
                }
            }).always(function(){
                $('#codigo_confirmar').focus();
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

        $('#modal-envio-op').on('show.bs.modal', function (event) {
            //cuando abre el form de anular pedido
            var button = $(event.relatedTarget)
            var idunico = button.data('envio')
            var codigo = button.data('code')
            var group = button.data('group')

            if(group == 1){
                $('#titulo-modal-op').html("Confirmar recepción");
                $('#msj-modal').html('Esta seguro que desea confirmar la recepción del Pedido <b>' + codigo + '</b> <span class="text-success font-weight-bold">CON SOBRE<span> ?');
                $('#conf-modal-OP').html("Confirmar recepción");
            }else{
                $('#titulo-modal-op').html("Enviar pedido");
                $('#msj-modal').html('Esta seguro que desea enviar el Pedido <b>' + codigo + '</b> al Área de Courier.');
                $('#conf-modal-OP').html("Enviar a Courier");
            }
            //$(".textcode").html(codigo);

            $("#hiddenEnvioOP").val(idunico);
            $("#hiddenGroup").val(group);

        });

        $('#modal-envio').on('show.bs.modal', function (event) {
            //cuando abre el form de anular pedido
            var button = $(event.relatedTarget)
            var idunico = button.data('envio')
            var codigo = button.data('code')
            $(".textcode").html(codigo);
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
                url:"{{ route('operaciones.recepcionid') }}",
                success:function(data)
                {
                    console.log(data);
                    $("#modal-envio .textcode").text('');
                    $("#modal-envio").modal("hide");
                    $('#tablaOperacionesJefeOperaciones').DataTable().ajax.reload();
                }
            });
        });

        $(document).on("submit", "#formulario_atender_op", function (evento) {
            evento.preventDefault();

            var group = $('#hiddenGroup').val();

            var fd = new FormData();
            var data = new FormData(document.getElementById("formulario_atender_op"));

            fd.append( 'hiddenEnvio', $("#hiddenEnvio").val() );

            if(group == 1){
                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url:"{{ route('operaciones.recibir_pedido_op') }}",
                    success:function(data)
                    {
                        console.log(data);
                        $("#modal-envio-op .textcode").text('');
                        $("#modal-envio-op").modal("hide");
                        $('#tablaOperacionesJefeOperaciones').DataTable().ajax.reload();
                    }
                });
            }else{

                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url:"{{ route('operaciones.atender_pedido_op') }}",
                    success:function(data)
                    {
                        console.log(data);
                        $("#modal-envio-op .textcode").text('');
                        $("#modal-envio-op").modal("hide");
                        $('#tablaOperacionesJefeOperaciones').DataTable().ajax.reload();
                    }
                });
            }
        });

      $('#modal-revertir').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('revertir')
          var codigo_pedido = button.data('codigo')
        //$(".textcode").html("PED"+idunico);
          $(".textcode").html(codigo_pedido);
        $("#hiddenRevertirpedido").val(idunico);
      });

      $(document).on("submit", "#formulariorevertir", function (evento) {
        evento.preventDefault();
        var fd = new FormData();
        fd.append( 'hiddenRevertirpedido', $("#hiddenRevertirpedido").val() );

        $.ajax({
           data: fd,
           processData: false,
           contentType: false,
           type: 'POST',
           url:"{{ route('operaciones.revertirenvioid') }}",
           success:function(data)
           {
            console.log(data);
            $("#modal-revertir .textcode").text('');
            $("#modal-revertir").modal("hide");
            $('#tablaOperacionesJefeOperaciones').DataTable().ajax.reload();
           }
        });
      });

      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('delete')
        var idresponsable = button.data('responsable')
        $("#hiddenIDdelete").val(idunico);
        if(idunico<10){
          idunico='PED000'+idunico;
        }else if(idunico<100){
          idunico= 'PED00'+idunico;
        }else if(idunico<1000){
          idunico='PED0'+idunico;
        }else{
          idunico='PED'+idunico;
        }
        $(".textcode").html(idunico);
        $("#motivo").val('');
        $("#responsable").val( idresponsable );
      });

      $('#tablaOperacionesJefeOperaciones').DataTable({
          //dom: '<"top"i>rt',
        processing: true,
        stateSave:true,
		serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: {
          url: "{{ route('operaciones.entregadostabla') }}",
          data: function (d) {
            d.min = $("#min").val();
            d.max = $("#max").val();
          },
        },
        createdRow: function( row, data, dataIndex){},
        rowCallback: function (row, data, index) {},
        initComplete:function(settings,json){},
        columns: [
          {
              data: 'id',
              name: 'correlativo',
              render: function ( data, type, row, meta ) {
                  return row.correlativo;
              }
          },
          {data: 'codigos', name: 'codigos', },
          {data: 'empresas', name: 'empresas', },
          {data: 'users', name: 'users', },
          {
            data: 'fecha',
            name: 'fecha',
            render:$.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss' ),
          },
            {data: 'condicion_envio',name: 'condicion_envio',},
          {data: 'atendido_por', name: 'atendido_por', },
          {data: 'jefe', name: 'jefe', },
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

      $('#tablaOperacionesJefeOperaciones_filter label input').on('paste', function(e) {
      var pasteData = e.originalEvent.clipboardData.getData('text')
      localStorage.setItem("search_tabla",pasteData);
    });
    $(document).on("keypress",'#tablaOperacionesJefeOperaciones_filter label input',function(){
      localStorage.setItem("search_tabla",$(this).val());
      console.log( "search_tabla es "+localStorage.getItem("search_tabla") );
    });

    });
  </script>

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
    /*window.onload = function () {
      $('#tablaOperacionesJefeOperaciones').DataTable().draw();
    }*/
  </script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

            /*$.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");

                    var d = data[4].split("/");
                    var startDate = new Date(d[1]+ "/" +  d[0] +"/" + d[2]);

                    if (min == null && max == null) { return true; }
                    if (min == null && startDate <= max) { return true;}
                    if(max == null && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );*/

            $("#min").datepicker({
              onSelect: function () {
                $('#tablaOperacionesJefeOperaciones').DataTable().ajax.reload();
                //localStorage.setItem('dateMin', $(this).val() );
              }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"
            });

            $("#max").datepicker({
              onSelect: function () {
                $('#tablaOperacionesJefeOperaciones').DataTable().ajax.reload();
                //localStorage.setItem('dateMax', $(this).val() );
              }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy"
            });

            //$("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"});
            //$("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy" });
            //var table = $('#tablaOperacionesJefeOperaciones').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            /*$('#min, #max').change(function () {
                table.draw();
            });*/
        });
  </script>
  <script>
    /*if (localStorage.getItem('dateMin') )
    {
      $( "#min" ).val(localStorage.getItem('dateMin')).trigger("change");
    }else{
      localStorage.setItem('dateMin', "{{$dateMin}}" );
    }
    if (localStorage.getItem('dateMax') )
    {
      $( "#max" ).val(localStorage.getItem('dateMax')).trigger("change");
    }else{
      localStorage.setItem('dateMax', "{{$dateMax}}" );
    }*/
    //console.log(localStorage.getItem('dateMin'));
    //console.log(localStorage.getItem('dateMax'));
  </script>
@stop
