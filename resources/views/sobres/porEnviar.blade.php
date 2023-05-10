{{--sobres.porenviar--}}
@extends('adminlte::page')

@section('title', 'Pedidos | Sobres por enviar')

@section('content_header')
  <h1>Lista de sobres por enviar - ENVIOS

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

  <div class="card" style="overflow: hidden !important;">
    <div class="card-body" style="overflow-x: scroll !important;">

      <table id="tablaSobresPorEnviar" class="table table-striped" style="width:100% !important;">

        <thead>
        <tr>
          <th>
            data
          </th>
        </tr>
        <tr>
          <th scope="col" class="align-middle">Item</th>
          <th scope="col" class="align-middle">Código</th>
          <th scope="col" class="align-middle">Id</th>
          <th scope="col" class="align-middle">Razón social</th>
          <th scope="col" class="align-middle">Nombre Cliente</th>
          <th scope="col" class="align-middle">Telefono Cliente</th>
          <th scope="col" class="align-middle">Fecha de registro</th>
          <th scope="col" class="align-middle">Estado de envio</th>
          <th scope="col" class="align-middle">Observacion Devolucion</th>
          <th scope="col" class="align-middle">Acciones</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('sobres.modal.direccionid')

      @include('sobres.modal.historialLima')
      @include('sobres.modal.historialProvincia')
      @include('sobres.modal.modal_recoger_sobre')
      @include('sobres.modal.historialenvio')

    </div>
  </div>

@stop

@push('css')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf_viewer.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.csss">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.csss">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.csss">

  <style>

    .bootstrap-select.btn-group .btn .filter-option {
      text-align: right
    }

    .bootstrap-select .dropdown-toggle .filter-option {
      text-align: right !important;
    }

    .bootstrap-select .dropdown-menu.inner {
      text-align: right !important;
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

    .pull-right {
      float: right !important;
    }

    #pdf_renderer {
      position: relative;
    }

    #canvas_container {
      position: relative;
    }

    .modal-lg {
      max-width: 80%;
    }

    @if(auth()->user()->rol !='Administrador')
            .visible_button_recoger {
      opacity: 0;
    }
    @endif

  </style>

@endpush

@push('js')

  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>




  <script
    src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

  <script>


    const configDataTableLanguages = {
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

    var myState = {
      pdf: null,
      currentPage: 1,
      zoom: 1
    }

    var currPage = 0;

    var tabla_pedidos = null;

  </script>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  @include('partials.historial_direccion.historial_direccion_javascript')
  <script>
    $(document).ready(function () {

      $(document).on("click", "#go_previous", function (e) {
        e.preventDefault();
        if (myState.pdf == null || myState.currentPage == 1) {
          console.log("atras")
          return false;
        }
        myState.currentPage -= 1;
        $("#current_page").val(myState.currentPage);
        render();
      });

      $(document).on("click", "#go_next", function (e) {
        e.preventDefault();
        console.log("numpages " + myState.pdf._pdfInfo.numPages);
        console.log("currentpage " + myState.currentPage)
        if (myState.pdf == null || myState.currentPage == myState.pdf._pdfInfo.numPages) {
          console.log("next")
          return false;
        }


        myState.currentPage += 1;
        $("#current_page").val(myState.currentPage);
        if (myState.currentPage == myState.pdf._pdfInfo.numPages) {
          $("#go_next").addClass("d-none");
        }
        render();
      });

      $(document).on("keypress", "#current_page", function (e) {
        if (myState.pdf == null) return;

        // Get key code
        var code = (e.keyCode ? e.keyCode : e.which);

        // If key code matches that of the Enter key
        if (code == 13) {
          var desiredPage = document.getElementById('current_page').valueAsNumber;

          if (desiredPage >= 1 && desiredPage <= myState.pdf._pdfInfo.numPages) {
            myState.currentPage = desiredPage;
            document.getElementById("current_page").value = desiredPage;
            render();
          }
        }
      });

      $(document).on("click", "#zoom_in", function (e) {
        if (myState.pdf == null) return;
        myState.zoom += 0.5;
        render();
      });

      $(document).on("click", "#zoom_out", function (e) {
        if (myState.pdf == null) return;
        myState.zoom -= 0.5;
        render();
      });

    });
  </script>

  <script>

  </script>

  <script>
    let tablaSobresPorEnviar = null;

    $(document).ready(function () {

      window.limpiar_campos_historico_recojo = function () {
        $("#recojo_pedido_quienrecibe_nombre").val("");
        $("#recojo_pedido_quienrecibe_celular").val("");
        $("#recojo_pedido_direccion").val("");
        $("#recojo_pedido_referencia").val("");
        $("#recojo_pedido_observacion").val("");
      }

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
            if (data.html == '0') {
            } else {
              $("#modal-cambiar-imagen").modal("hide");
              var urlimg = "{{asset('imagenes/logo_facturas.png')}}";
              urlimg = urlimg.replace('imagenes/', 'storage/entregas/');
              urlimg = urlimg.replace('logo_facturas.png', data.html);
              urlimg = urlimg.replace(' ', '%20');
              console.log(urlimg);
              $("#imagen_" + cambiapedido + '-' + cambiaitem).attr("src", urlimg);
            }
          }
        });

      });

      $(document).on("change", "#rotulo", function (event) {
        $(".drop-rotulo").removeClass("d-none");
        console.log("cambe rotulo")
        var file = event.target.files[0];

        $("#pdf_renderer_object").removeClass('d-none')

        setTimeout(function () {
          $("#pdf_renderer_object").attr('data', URL.createObjectURL(file))

          $("#pdf_renderer_object").css('height', ($("#pdf_renderer_object").parents('.viewpdf').height() + 50) + 'px')
        }, 50)

        console.log(file);
      });

      window.render = function () {
        myState.pdf.getPage(myState.currentPage).then((page) => {
          var canvas = document.getElementById("pdf_renderer");

          var ctx = canvas.getContext('2d');
          var viewport = page.getViewport(1);
          canvas.width = viewport.width;
          canvas.height = viewport.height;
          canvas.style.width = '100%';
          canvas.style.height = '100%';
          page.render({
            canvasContext: ctx,
            viewport: viewport
          });

        });
      }

      window.handlePages = function (page) {
        var viewport = page.getViewport(1);
        var canvas = document.createElement("canvas");
        canvas.style.display = "block";
        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        page.render({canvasContext: context, viewport: viewport});

        document.body.appendChild(canvas);

        currPage++;
        if (thePDF !== null && currPage <= numPages) {
          thePDF.getPage(currPage).then(handlePages);
        }
      }

      $('#celular').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
      });

      $("#direccion", '#referencia', '#observacion').bind('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9 ]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

      $(document).on('change keyup', "#tracking, #numregistro", function (event) {

        let id_element = event.target.id;
        let val_element = $(this).val();
        switch (id_element) {
          case 'tracking':
            let ntrack = val_element.length;
            console.log("n " + ntrack)
            if (ntrack > 9) {
              $.ajax({
                data: {'element': id_element, 'value': val_element, 'from': 'direcccionenvio'},
                type: 'POST',
                url: "{{ route('envios.validacion_direccionenvio') }}",
                success: function (data) {
                  console.log(data);
                  if (data.response == '1') {
                    Swal.fire(
                      'Error',
                      'Informacion repetida con el campo ' + data.element,
                      'warning'
                    )
                  }
                }
              });
            }
            break;
          case 'numregistro':
            let nreg = val_element.length;
            console.log("n2 " + nreg)
            if (nreg > 11) {
              $.ajax({
                data: {'element': id_element, 'value': val_element, 'from': 'direcccionenvio'},
                type: 'POST',
                url: "{{ route('envios.validacion_direccionenvio') }}",
                success: function (data) {
                  console.log(data);
                  if (data.response == '1') {
                    Swal.fire(
                      'Error',
                      'Informacion repetida con el campo ' + data.element,
                      'warning'
                    )
                  }
                }
              });
            }
            break;
        }
      });

      $('input.number').keyup(function (event) {
        console.log("number")

        if (event.which >= 37 && event.which <= 40) {
          event.preventDefault();
        }

        $(this).val(function (index, value) {
          return value
            .replace(/\D/g, "")
            .replace(/([0-9])([0-9]{2})$/, '$1.$2')
            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
        });
      });

      $("#numregistro").bind('keypress', function (event) {
        var regex = new RegExp("^[0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

      $("#cantidad").bind('keypress', function (event) {
        var regex = new RegExp("^[0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

      $(".provincia").addClass("d-none");
      $(".lima").addClass("d-none");


      $(document).on("change", "#user_id", function () {
        $("#datatable-clientes-lista-recojer").DataTable().ajax.reload();
        //refresh tabla//$("#distrito").val("").selectpicker("refresh")
      });


      $(document).on("change", "#limaprovincia", function () {
        $("#distrito").val("").selectpicker("refresh")
        $('#pdf_renderer_object').attr('data', null)
        $('#rotulo').val(null)
        let paralimaprovincia2=$("select[name=limaprovincia]").val();
        let cliente2 = $("#cliente_id").val();

        switch ($(this).val()) {
          case 'L':
            console.log("e L");
            if (!$(".provincia").hasClass("d-none")) {
              $(".provincia").addClass("d-none");

            }
            if (!$(".viewpdf").hasClass("d-none")) {
              $(".viewpdf").addClass("d-none");
            }
            $(".lima").removeClass("d-none");

            if ($(".contenedor-tabla").hasClass("col-4")) {
              $(".contenedor-tabla").removeClass("col-4");
              $(".contenedor-tabla").addClass("col-6");
            }
            if ($(".contenedor-formulario").hasClass("col-4")) {
              $(".contenedor-formulario").removeClass("col-4");
              $(".contenedor-formulario").addClass("col-6");
            }

            $('#nombre').val('')
            $('#celular').val('')
            $('#direccion').val('')
            $('#referencia').val('')
            $('#observacion').val('')

            break;
          case 'P':
            console.log("e P");
            if (!$(".lima").hasClass("d-none")) {
              $(".lima").addClass("d-none");
            }
            $(".provincia").removeClass("d-none");
            $(".viewpdf").removeClass("d-none");


            if ($(".contenedor-tabla").hasClass("col-6")) {
              $(".contenedor-tabla").removeClass("col-6");
              $(".contenedor-tabla").addClass("col-4");
            }
            if ($(".contenedor-formulario").hasClass("col-6")) {
              $(".contenedor-formulario").removeClass("col-6");
              $(".contenedor-formulario").addClass("col-4");
            }
            $('#numregistro').val('')
            $('#tracking').val('')
            $('#importe').val('')
            $('#rotulo').val('')
            break;
          default:
            if (!$(".lima").hasClass("d-none")) {
              $(".lima").addClass("d-none");
            }
            if (!$(".provincia").hasClass("d-none")) {
              $(".provincia").addClass("d-none");
            }

            if ($(".contenedor-tabla").hasClass("col-4")) {
              $(".contenedor-tabla").removeClass("col-4");
              $(".contenedor-tabla").addClass("col-6");
            }
            if ($(".contenedor-formulario").hasClass("col-4")) {
              $(".contenedor-formulario").removeClass("col-4");
              $(".contenedor-formulario").addClass("col-6");
            }

            break;
        }

        tabla_pedidos.destroy();

        tabla_pedidos = $('#tablaPrincipalpedidosagregar').DataTable({
          responsive: true,
          "bPaginate": false,
          "bFilter": false,
          "bInfo": false,
          'ajax': {
            url: "{{ route('cargar.pedidosenvioclientetabla') }}",
            'data': {"cliente_id": cliente2,"destino":paralimaprovincia2},
            "type": "get",
          },
          columnDefs: [{
            'targets': [0], /* column index */
            'orderable': false, /* true or false */
          }],
          rowCallback: function (row, data, index) {
            if (data.da_confirmar_descarga != '1') {
              $('input[type=checkbox]', row).attr('disabled', 'disabled')
            }
          },
          columns: [
            {
              "data": "id",
              'targets': [0],
              'checkboxes': {
                'selectRow': true
              },
              defaultContent: '',
              orderable: false,
              sWidth: '5%',
            },
            {data: 'codigo', name: 'codigo', sWidth: '40%',},
            {
              "data": 'nombre_empresa',
              "name": 'nombre_empresa',
              "render": function (data, type, row, meta) {
                return data;
              },
              sWidth: '40%',
            },
            {data: 'condicion_envio', name: 'condicion_envio', sWidth: '15%',},
          ],
          'select': {
            'style': 'multi',
            selector: 'td:first-child'
          },
        });
        $('#tablaPrincipalpedidosagregar').DataTable().ajax.reload();
        tabla_pedidos.columns.adjust().draw();


      });

      $(document).on("click", "#direccionConfirmar", function (event) {
        var fd2 = new FormData();
        let val_direccion_id = $("#direccion_id").val();
        let val_cliente = $("#cliente_id").val();
        let val_cod_pedido = $("#cod_pedido").val();
        let val_cod_ase = $("#cod_ase").val();
        let val_check=($('#envio_urgente').is(':checked')) ? '1' : '0';
        fd2.append('cliente_id', val_cliente);
        fd2.append('cod_pedido', val_cod_pedido);
        fd2.append('cod_asesor', val_cod_ase);
        fd2.append('urgente', val_check);

        let val_nombre = $("#nombre").val();
        let val_contacto = $("#celular").val();
        let val_direccion = $("#direccion").val();
        console.log(val_direccion)
        let val_referencia = $("#referencia").val();
        let val_distrito = $("#distrito").val();
        console.log(val_distrito)
        let val_observacion = $("#observacion").val();
        let val_gmlink = $("#gmlink").val();
        let saveHistoricoLima = ($('#saveHistoricoLima').is(':checked')) ? '1' : '0';
        let saveHistoricoProvincia = ($('#saveHistoricoProvincia').is(':checked')) ? '1' : '0';

        let files = $('input[name="rotulo')[0].files;
        console.log(files.length)
        var combo_limaprovincia = $("#limaprovincia").val();
        var val_departamento = $("#departamento").val();
        var val_oficina = $("#oficina").val();
        var val_tracking = $("#tracking").val();
        var val_numregistro = $("#numregistro").val();
        var val_importe = $("#importe").val();
        val_importeEx = val_importe.replace(",", "")
        var importeex = parseFloat(val_importeEx);
        var rows_selected = tabla_pedidos.column(0).checkboxes.selected();
        if (combo_limaprovincia == "") {
          Swal.fire(
            'Error',
            'Debe selecionar lima o provincia',
            'warning'
          )
          return;
        } else {

          if (combo_limaprovincia == "L") {
            console.log(combo_limaprovincia)
            if (val_nombre == "") {
              Swal.fire(
                'Error',
                'Debe ingresar nombre',
                'warning'
              )
              return;
            } else if (val_contacto == "" || val_contacto.length != 9) {
              Swal.fire(
                'Error',
                'El numero tiene ' + val_contacto.length + ' digitos, complete a 9 digitos',
                'warning'
              )
              return;
            } else if (val_direccion == "") {
              Swal.fire(
                'Error',
                'Debe ingresar direccion',
                'warning'
              )
              return;
            } else if (val_referencia == "") {
              Swal.fire(
                'Error',
                'Debe ingresar referencia',
                'warning'
              )
              return;
            } else if (val_distrito == "" || val_distrito == null) {
              Swal.fire(
                'Error',
                'Debe seleccionar un distrito',
                'warning'
              )
              return;
            }

          } else if (combo_limaprovincia == "P") {
            var cont_rotulo = files.length;
            if (val_tracking == "") {
              Swal.fire(
                'Error',
                'Debe ingresar tracking',
                'warning'
              )
              return;
            } else if (val_numregistro == "") {
              Swal.fire(
                'Error',
                'Debe ingresar tracking',
                'warning'
              )
              return;
            } else if (val_importe == "") {
              Swal.fire(
                'Error',
                'Debe ingresar tracking',
                'warning'
              )
              return;
            } else if (cont_rotulo == 0) {
              Swal.fire(
                'Error',
                'Debe ingresar rotulo',
                'warning'
              )
              return;
            }
          }
          if (combo_limaprovincia == "P") {
            fd2.append('distrito', val_distrito);
            fd2.append('departamento', val_departamento);
            fd2.append('oficina', val_oficina);
            fd2.append('tracking', val_tracking);
            fd2.append('numregistro', val_numregistro);
            fd2.append('importe', val_importe);

            for (let i = 0; i < files.length; i++) {
              var file = $('input[type=file][name="rotulo"]')[0].files[0]
              fd2.append('rotulo', file, file.name);
            }

            fd2.append('saveHistoricoProvincia', saveHistoricoProvincia);
          } else if (combo_limaprovincia == "L") {
            fd2.append('nombre', val_nombre);
            fd2.append('contacto', val_contacto);
            fd2.append('direccion', val_direccion);
            fd2.append('referencia', val_referencia);
            fd2.append('distrito', val_distrito);
            fd2.append('observacion', val_observacion);
            fd2.append('gmlink', val_gmlink);

            fd2.append('saveHistoricoLima', saveHistoricoLima);
          }

        }
        var destino = (combo_limaprovincia == "L") ? 'LIMA' : 'PROVINCIA';
        fd2.append('destino', destino);
        var pedidos = [];
        $.each(rows_selected, function (index, rowId) {
          console.log("index " + index);
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

        $pedidos = pedidos.join(',');
        fd2.append('pedidos', $pedidos);

        function sendAjax() {
          if (val_direccion_id) {
            if (combo_limaprovincia == "L") {
              if ($("#saveHistoricoLimaEditar").prop('checked')) {
                fd2.append('model_id', val_direccion_id)
              }
            } else {
              if ($("#saveHistoricoProvinciaEditar").prop('checked')) {
                fd2.append('model_id', val_direccion_id)
              }
            }
          }
          $.ajax({
            data: fd2,
            processData: false,
            contentType: false,
            type: 'POST',
            url: "{{ route('envios.direccion') }}",
            success: function (data) {
              console.log(data)
              if (!data.success) {
                Swal.fire(
                  'Error',
                  data.html,
                  'warning'
                )
                $("#rotulo").val(null)
                $("#numregistro").val(null)
                $("#tracking").val(null)
                return false;
              } else {
                $("#modal-direccion").modal("hide");
                $("#tablaSobresPorEnviar").DataTable().ajax.reload();
              }
            }
          });
        }

        var form = $("#formdireccion")[0]
        if (combo_limaprovincia == "L") {
          if (val_direccion_id) {
            var msg = ''
            if ($(form.nombre).data('old_value') != $(form.nombre).val()) {
              msg += '<li><b>NOMBRE</b></li>'
            }
            if ($(form.celular).data('old_value') != $(form.celular).val()) {
              msg += '<li><b>CELULAR</b></li>'
            }
            if ($(form.direccion).data('old_value') != $(form.direccion).val()) {
              msg += '<li><b>DIRECCIÓN</b></li>'
            }
            if ($(form.referencia).data('old_value') != $(form.referencia).val()) {
              msg += '<li><b>REFERENCIA</b></li>'
            }
            if ($(form.distrito).data('old_value') != $(form.distrito).val()) {
              msg += '<li><b>DISTRITO</b></li>'
            }
            if ($(form.observacion).data('old_value') != $(form.observacion).val()) {
              msg += '<li><b>OBSERVACIÓN</b></li>'
            }
            if (msg.length > 0) {
              $.confirm({
                title: '<h3 class="font-20 font-weight-500">¡Ups! acabas de editar la información de :</h3> ',
                content: `<ul>${msg}</li>`,
                type: 'orange',
                buttons: {
                  confirm: {
                    text: 'Aceptar y guardar cambios',
                    btnClass: 'btn-red',
                    action: function () {
                      if ($("#saveHistoricoLimaEditar").prop('checked')) {
                        $.confirm({
                          title: '¡Advertencia!',
                          content: `Los direccion ingresada se actualizara en el historial`,
                          type: 'orange',
                          buttons: {
                            confirm: {
                              text: 'Aceptar y guardar ',
                              btnClass: 'btn-red',
                              action: function () {
                                sendAjax()
                              }
                            },
                            cancel: function () {

                            }
                          }
                        });
                      }
                      sendAjax()
                    }
                  },
                  cancel: function () {

                  }
                }
              });
            } else {
              sendAjax()
            }
          } else {
            sendAjax()
          }
        } else if (combo_limaprovincia == "P") {
          if (val_direccion_id) {
            var msg = ''
            if ($(form.tracking).data('old_value') != $(form.tracking).val()) {
              msg += '<li>El <b>tracking</b> ah sido modificado</li>'
            }
            if ($(form.numregistro).data('old_value') != $(form.numregistro).val()) {
              msg += '<li>El <b>numregistro</b> ah sido modificado</li>'
            }
            if ($(form.importe).data('old_value') != $(form.importe).val()) {
              msg += '<li>La <b>importe</b> ah sido modificada</li>'
            }

            if (msg.length > 0) {
              $.confirm({
                title: '¡Advertencia de cambios en los datos del formulario!',
                content: `<ul>${msg}</li>`,
                type: 'orange',
                buttons: {
                  confirm: {
                    text: 'Aceptar y guardar cambios',
                    btnClass: 'btn-red',
                    action: function () {
                      if ($("#saveHistoricoProvinciaEditar").prop('checked')) {
                        $.confirm({
                          title: '¡Advertencia!',
                          content: `Los datos ingresados se actualizara en el historial`,
                          type: 'orange',
                          buttons: {
                            confirm: {
                              text: 'Aceptar y guardar ',
                              btnClass: 'btn-red',
                              action: function () {
                                sendAjax()
                              }
                            },
                            cancel: function () {

                            }
                          }
                        });
                      }
                    }
                  },
                  cancel: function () {

                  }
                }
              });
            } else {
              sendAjax()
            }
          } else {
            sendAjax()
          }
        } else {
          sendAjax()
        }


      });

      $(document).on("click", "#droprotulo", function () {
        $("#rotulo").val("");
        $(".drop-rotulo").addClass("d-none");
        $("#pdf_renderer_object").attr("data", null);
        $("#pdf_renderer_object").addClass("d-none");
      });


      $("#set_cliente_clear_provincia").click(function () {
        var form = $("#formdireccion")[0]
        form.direccion_id.value = ''
        form.tracking.value = ''
        form.numregistro.value = ''
        form.importe.value = ''

        $(form.direccion_id).data('old_value', form.direccion_id.value);
        $(form.tracking).data('old_value', form.tracking.value);
        $(form.numregistro).data('old_value', form.numregistro.value);
        $(form.importe).data('old_value', form.importe.value);

        $("#set_cliente_clear_provincia").hide()
      })
      $('#modal-historial-provincia').on('show.bs.modal', function (event) {
        tablehistoricoprovincia.destroy();

        let provincialima = "PROVINCIA";
        let clienteidprovincia = $("#modal-historial-provincia-a").attr("data-cliente");

        $("#set_cliente_clear_provincia").hide()
        tablehistoricoprovincia = $('#tablaHistorialProvincia').DataTable({
          "bPaginate": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
          "pageLength": 5,
          "order": [[0, "asc"]],
          ajax: {
            url: "{{ route('sobreenvioshistorial') }}",
            data: function (d) {
              d.provincialima = provincialima;
              d.cliente_id = clienteidprovincia;
            },
            type: 'get',
          },
          rowCallback: function (row, data, index) {
            $('.button_provincia', row).click(function (e) {
              const json = $(this).data('json');
              const selectedData = ((json && typeof json != 'string') ? json : JSON.parse($(this).data('json')))
              console.log(selectedData)
              var form = $("#formdireccion")[0]
              form.direccion_id.value = selectedData.id;
              form.tracking.value = selectedData.tracking;
              form.numregistro.value = selectedData.registro;
              form.importe.value = selectedData.importe;


              $(form.direccion_id).data('old_value', form.direccion_id.value);
              $(form.tracking).data('old_value', form.tracking.value);
              $(form.numregistro).data('old_value', form.numregistro.value);
              $(form.importe).data('old_value', form.importe.value);

              $("#modal-historial-provincia").modal('hide')
              $("#set_cliente_clear_provincia").show()
            })
          },
          columns:
            [
              {
                data: 'id',
                name: 'id',
                "visible": true
              },
              {
                data: 'tracking',
                name: 'tracking',
                sWidth: '30%',
                render: function (data, type, row, meta) {
                  return '<span class="titular">' + data + '</span>';
                }
              },
              {
                data: 'registro',
                name: 'registro',
                sWidth: '15%',
                render: function (data, type, row, meta) {
                  return '<span class="banco">' + data + '</span>';
                }
              },
              {data: 'foto', name: 'foto',},
              {
                data: 'action',
                name: 'action',
                sWidth: '20%',
                render: function (data, type, row, meta) {
                  data = data +
                    `<button class="btn btn-danger btn-sm button_provincia" data-json='${JSON.stringify(row)}' data-provincia="${row.id}"><i class="fas fa-check-circle"></i></button>`;
                  return data;
                },
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

      });
      $("#set_cliente_clear").click(function () {
        var form = $("#formdireccion")[0]
        form.direccion_id.value = '';
        form.nombre.value = '';
        form.celular.value = '';
        form.direccion.value = '';
        form.referencia.value = '';
        $(form.distrito).val('').trigger('change');
        form.observacion.value = '';


        $(form.direccion_id).data('old_value', selectedData.id);
        $(form.nombre).data('old_value', form.nombre.value);
        $(form.celular).data('old_value', form.celular.value);
        $(form.direccion).data('old_value', form.direccion.value);
        $(form.referencia).data('old_value', form.referencia.value);
        $(form.distrito).data('old_value', form.distrito.value);
        $(form.observacion).data('old_value', form.observacion.value);

        $("#set_cliente_clear").hide()

        $("#saveHistoricoLima").parent().show()
        $("#saveHistoricoLimaEditar").parent().hide()

      })

      $("#formdireccion input,#formdireccion select").change(function () {
        var form = $("#formdireccion")[0];
        var msg = ''
        if ($(form.nombre).val() && $(form.nombre).data('old_value') != $(form.nombre).val()) {
          msg += '1'
        }
        if ($(form.celular).val() && $(form.celular).data('old_value') != $(form.celular).val()) {
          msg += '1'
        }
        if ($(form.direccion).val() && $(form.direccion).data('old_value') != $(form.direccion).val()) {
          msg += '1'
        }
        if ($(form.referencia).val() && $(form.referencia).data('old_value') != $(form.referencia).val()) {
          msg += '1'
        }
        if ($(form.distrito).val() && $(form.distrito).data('old_value') != $(form.distrito).val()) {
          msg += '1'
        }
        if ($(form.observacion).val() && $(form.observacion).data('old_value') != $(form.observacion).val()) {
          msg += '1'
        }
        if (msg.length > 0) {
          $("#saveHistoricoLimaEditar").removeAttr('disabled')
        } else {
          $("#saveHistoricoLimaEditar").attr('disabled', 'disabled')
          $("#saveHistoricoLimaEditar").prop('checked', false)
        }
      })


      $("#distrito").on('change', function () {
        var distrito_seleccionado = $(this).val();
        distrito_seleccionado = distrito_seleccionado.replace('+', ' ');
        console.log(distrito_seleccionado)

        $.ajax({
          data: {
            distrito: distrito_seleccionado
          },
          type: 'POST',
          url: "{{ route('envios.verificarzona') }}",
          success: function (data) {
            console.log(data);
            if (data.html == 0) {
              /**********
               * CARGAMOS EL FORMULARIO DE PROVINCIA
               */
              $('#limaprovincia  option[value="P"]').prop("selected", true);
              $('#distrito-olva').html(distrito_seleccionado);
              console.log("La zona es Olva");
              if (!$(".lima").hasClass("d-none")) {
                $(".lima").addClass("d-none");
              }
              $(".provincia").removeClass("d-none");
              $(".viewpdf").removeClass("d-none");


              if ($(".contenedor-tabla").hasClass("col-6")) {
                $(".contenedor-tabla").removeClass("col-6");
                $(".contenedor-tabla").addClass("col-4");
              }
              if ($(".contenedor-formulario").hasClass("col-6")) {
                $(".contenedor-formulario").removeClass("col-6");
                $(".contenedor-formulario").addClass("col-4");
              }
              tabla_pedidos.columns.adjust().draw();
            }
          }
        });

        return false;
      });


      //inicio tabla pedidos
      $('#modal-direccion').on('hide.bs.modal', function (event) {
        $("#pdf_renderer_object").attr("data", '');
        $("#pdf_renderer_object").addClass("d-none");
      })

      $('#modal-direccion').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget)
        //desmarcr checkbox
        $("#saveHistoricoLima").prop("checked", false).val("0");
        $("#saveHistoricoProvincia").prop("checked", false).val("0");
        $("#nombre").val("")
        $("#celular").val("")
        $("#direccion").val("")
        $("#referencia").val("")
        $("#distrito").val("").selectpicker("refresh")
        $("#observacion").val("")
        $("#tracking").val("")
        $("#numregistro").val("")
        $("#importe").val("")
        $(".drop-rotulo").addClass("d-none");

        if (!$(".viewpdf").hasClass("d-none")) {
          $(".viewpdf").addClass("d-none");
        }
        $("#rotulo").val("");

        var cliente = button.data('cliente');
        var codigo_ped = button.data('codigo');
        var codigo_asesor = button.data('asesor');
        var confirm_descarga = button.data('confirm_descarga');
        var pedido_codigo = button.data('pedido_codigo');
        $(".set_pedido_code").text(pedido_codigo)
        if (confirm_descarga == '1') {
          $("#show_direccion_is_disabled").hide()
          $("#show_direccion_is_enabled").show()
          $("#direccionConfirmar").show()
        } else {
          $("#show_direccion_is_disabled").show()
          $("#show_direccion_is_enabled").hide()
          $("#direccionConfirmar").hide()
        }

        console.log("cliente " + cliente);
        $("#cliente_id").val(cliente);
        $("#cod_pedido").val(codigo_ped);
        $("#cod_ase").val(codigo_asesor);

        $("#modal-historial-lima-a").attr("data-cliente", cliente);
        $("#modal-historial-provincia-a").attr("data-cliente", cliente);
        let paralimaprovincia=$("select[name=limaprovincia]").val();

        console.log("carga modales")
        tabla_pedidos.destroy();

        tabla_pedidos = $('#tablaPrincipalpedidosagregar').DataTable({
          responsive: true,
          "bPaginate": false,
          "bFilter": false,
          "bInfo": false,
          'ajax': {
            url: "{{ route('cargar.pedidosenvioclientetabla') }}",
            'data': {"cliente_id": cliente,"destino":paralimaprovincia},
            "type": "get",
          },
          columnDefs: [{
            'targets': [0], /* column index */
            'orderable': false, /* true or false */
          }],
          rowCallback: function (row, data, index) {
            if (data.da_confirmar_descarga != '1') {
              $('input[type=checkbox]', row).attr('disabled', 'disabled')
            }
          },
          columns: [
            {
              "data": "id",
              'targets': [0],
              'checkboxes': {
                'selectRow': true
              },
              defaultContent: '',
              orderable: false,
              sWidth: '5%',
            },
            {data: 'codigo', name: 'codigo', sWidth: '40%',},
            {
              "data": 'nombre_empresa',
              "name": 'nombre_empresa',
              "render": function (data, type, row, meta) {
                return data;
              },
              sWidth: '40%',
            },
            {data: 'condicion_envio', name: 'condicion_envio', sWidth: '15%',},
          ],
          'select': {
            'style': 'multi',
            selector: 'td:first-child'
          },
        });

        $("#limaprovincia").val("").trigger("change");

      });

      $(document).on("change", "#departamento", function () {

      });

      $('#tablaPrincipalpedidosagregar tbody').on('click', 'input', function () {
        var data = tabla_pedidos.row($(this).parents('tr')).data();
        var indice = tabla_pedidos.row($(this).parents('tr')).index();
        console.log(data);
        var enhtml = $(this).parents('tr').html();
        console.log(enhtml);
        var arrray_data = JSON.stringify(data);
        console.log(arrray_data);
        console.log(data["id"] + "bbb's idpedido is: ");
        console.log(data["codigo"] + "bbb's codigo pedido is: ");
        console.log(data["DT_RowIndex"] + "bbb's indice is: ");
        console.log(indice + "'index  codigo is: ");
      });

      tabla_pedidos = $('#tablaPrincipalpedidosagregar').DataTable({
        responsive: true,
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
        columns:
          [
            {
              data: 'id'
            },
            {
              data: 'codigo'
            },
            {
              data: 'saldo'
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
        }
      });

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
        console.log("form enviarid");

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
            $('#tablaSobresPorEnviar').DataTable().ajax.reload();

          }
        });
      });


      tablaSobresPorEnviar = $('#tablaSobresPorEnviar').DataTable({
        dom: 'Blfrtip',
        processing: true,
        stateSave: true,
        serverSide: true,
        searching: true,
        "order": [[0, "desc"]],
        ajax: "{{ route('sobres.porenviartabla') }}",
        createdRow: function (row, data, dataIndex) {
          //console.log(row);
        },
        rowCallback: function (row, data, index) {
          if (data.devuelto != null) {
            $('td', row).css('color', '#cf0a0a');
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
            }, "visible": false
          },
          {data: 'codigo', name: 'codigo',},
          {data: 'users', name: 'users',},
          {data: 'empresas', name: 'empresas',},
          {data: 'nombres', name: 'nombres',},
          {data: 'celulares', name: 'celulares',},
          {data: 'fecha', name: 'fecha', "visible": true},
          {
            data: 'condicion_envio',
            name: 'condicion_envio',
          },
          {
            data: 'observacion_devuelto',
            name: 'observacion_devuelto',
            render: function (data, type, row, meta) {
              if (data != null) {
                return data;
              } else {
                return ''
              }
            },
            "visible": true
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth: '20%',
            render: function (data, type, row, meta) {
              datass = '';

              @if (Auth::user()->rol == "Asesor" || Auth::user()->rol == "Administrador" || Auth::user()->rol=='Llamadas' || Auth::user()->rol=='Encargado' || Auth::user()->rol=='ASESOR ADMINISTRATIVO' || Auth::user()->rol=='ASISTENTE PUBLICIDAD')

                datass = datass + '<button type="button" class="btn btn-dark btn-sm ' + (row.da_confirmar_descarga == '1' ? '' : '') + '" data-target="#modal-direccion" data-toggle="modal" data-pedido_codigo="' + row.codigo + '" data-confirm_descarga="' + row.da_confirmar_descarga + '" data-cliente="' + row.cliente_id + '" data-asesor="' + row.user_id + '" data-direccion="' + row.id + '" data-codigo="' + row.id + '"><i class="fa  ' + (row.da_confirmar_descarga != '1' ? 'fa-exclamation-triangle text-warning font-12 mr-8' : 'fa-map-marker-alt text-success mr-8') + '" aria-hidden="true"></i> Direccion</button>';
              @endif


                @if($ver_botones_accion > 2)
                @can('envios.enviar')
                datass = datass + '<a href="" data-target="#modal-enviar" data-toggle="modal" data-enviar="' + row.id + '"><button class="btn btn-success btn-sm"><i class="fas fa-envelope"></i> Entregado</button></a>';
              if (row.envio == '1') {
                datass = datass + '<a href="" data-target="#modal-recibir" data-toggle="modal" data-recibir="' + row.id + '"><button class="btn btn-warning btn-sm"><i class="fas fa-check-circle"></i> Recibido</button></a>';
              }
              @endcan
                @endif

              if (row.destino == null && row.direccion == '0' && (row.envio * 1) > 0) {
                var urldireccion = '{{ route("envios.createdireccion", ":id") }}';
                urldireccion = urldireccion.replace(':id', row.id);
                data = data + '<a href="' + urldireccion + '" class="btn btn-dark btn-sm"><i class="fas fa-map"></i> Destino</a><br>';
              }

              return datass;
            }
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
        /*                buttons: [
                            {
                                text: 'RECOGER',
                                className: 'btn btn-danger visible_button_recoger mb-4',
                                action: function (e, dt, node, config) {
                                    $('#modal-recoger-sobre').modal("show");
                                }
                            }
                        ],*/
      });


      /*$('#datatable-historial-recojer tbody').on( 'click', 'button.elegir', function () {

      })*/


      $('#tablaSobresPorEnviar tbody').on('click', 'button', function () {
        var data = tablaSobresPorEnviar.row($(this).closest('tr')).data();
        console.log("got the data"); //This alert is never reached
        console.log(data)

        /*$('[data-jqconfirm]', row).click(function () {

        });*/
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
    function maxLengthCheck(object) {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

    /* Custom filtering function which will search data in column four between two values */
    $(document).ready(function () {

      /*$("#destino", this).on( 'keyup change', function () {
        if ( table.column(i).search() !== this.value ) {
            table
                .column(8)
                .search( this.value )
                .draw();
          }*/
      //} );

    });
  </script>

@endpush
