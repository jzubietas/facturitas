@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    <style>
        @foreach(get_color_role() as $rol=>$color)
            .bg-{{Str::slug($rol)}}             {
            @if(is_array($color))
                        background: {{$color[0]}}            !important;;
            color: {{$color[1]}}            !important;;
            @else
                        background: {{$color}};
            color: #000 !important;
            @endif
                        font-weight: bold !important;;
        }
        @endforeach
    </style>
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')

    @include("layouts.modal.modal1")
    @include("layouts.modal.modal2")
    <div class="wrapper">

        {{-- Preloader Animation --}}
        @if($layoutHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
    <div id="alert-authorization">
        <x-common-autorizar-ruta-motorizado></x-common-autorizar-ruta-motorizado>
    </div>

    @include('pedidos.modal.escanear_estado_sobres')
    @include('modal.AgregarContacto.modalAgregarContacto')
    @include('vendor.adminlte.modal.modal_imagen_cliente')
@stop





@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script>
        let tblListadoLlamadas=null;
        let tblCambioNombre=null;
        let tblBloqueoClientes=null;
        let tblCambioNumero=null;

        let dataForm_agregarcontacto_n = {};
        let dataForm_agregarcontacto_cno = {};
        let dataForm_agregarcontacto_b = {};
        let dataForm_agregarcontacto_cnu = {};

        function insertContador(child, dotClass, value) {
          var parent = $(child).parent();
          var epa = parent.find(dotClass);
          if (epa.length > 0) {
            epa.html(value);
          } else {
            parent.append('<i class="' + dotClass.split('.').join(' ').trim() + '">' + value + '</i>');
          }
        }
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

          tblListadoLlamadas = $('#tablaListadoLlamadas').DataTable({
            responsive: true,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            columns:
              [
                {
                  data: 'tipo_insert'
                },
                {
                  data: 'nombre_asesor'
                },
                {
                  nane : 'celular'
                },
                {
                  data: 'nombres_cliente'
                },
                {data: 'nombre_contacto'},
                {
                  data: 'action'
                }
              ],
          });
          tblCambioNombre = $('#tablaCambioNombre').DataTable({
            responsive: true,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            columns:
              [
                {
                  data: 'tipo_insert'
                },
                {
                  data: 'nombre_asesor'
                },
                {
                  nane : 'celular'
                },
                {
                  data: 'nombres_cliente'
                },
                {data: 'nombre_contacto'},
                {
                  data: 'action'
                }
              ],
          });
          tblBloqueoClientes = $('#tablaBloqueoClientes').DataTable({
            responsive: true,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            columns:
              [
                {
                  data: 'tipo_insert'
                },
                {
                  data: 'nombre_asesor'
                },
                {
                  nane : 'celular'
                },
                {
                  data: 'foto'
                },
                {data: 'nombre_contacto'},
                {
                  data: 'action'
                }
              ],
          });
          tblCambioNumero = $('#tablaCambioNumero').DataTable({
            responsive: true,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            columns:
              [
                {
                  data: 'tipo_insert'
                },
                {
                  data: 'nombre_asesor'
                },
                {
                  nane : 'celular'
                },
                {
                  data: 'nombres_cliente'
                },
                {data: 'nombre_contacto'},
                {
                  data: 'action'
                }
              ],
          });





          window.ocultar_div_modal1 = function () {
                console.log("ocultar div asdfss")
                $("#op-1-row").hide();
                $("#form-op-1-row input").val("");
                $("#op-2-row").hide();
                $("#form-op-2-row input").val("");
                $("#op-3-row").hide();
                $("#form-op-3-row input").val("");
                $("#op-4-row").hide();
                $("#form-op-4-row input").val("");
            }

          window.ocultar_div_modal_agregarcontacto = function () {
            $("#modal-agregarcontacto-n-container").hide();
            $("#form-agregarcontacto-n input").val("");

            $("#modal-agregarcontacto-cno-container").hide();
            $("#form-agregarcontacto-cno input").val("");

            $("#modal-agregarcontacto-b-container").hide();
            $("#form-agregarcontacto-b input").val("");

            $("#modal-agregarcontacto-cnu-container").hide();
            $("#form-agregarcontacto-cnu input").val("");
          }


          $('#modal-agregar-contacto').on('show.bs.modal', function (event) {
            ocultar_div_modal_agregarcontacto();
          })

          $(document).on("click", "#form-agregarcontacto-b #attachmentfiles", function () {
            var file = document.createElement('input');
            file.type = 'file';
            file.click()
            file.addEventListener('change', function (e) {
              console.log("change")
              if (file.files.length > 0) {
                $('#form-agregarcontacto-b').find('.result_picture').css('display', 'block');
                console.log(URL.createObjectURL(file.files[0]))
                dataForm_agregarcontacto_b.agregarcontacto_b_captura = file.files[0]
                $('#form-agregarcontacto-b').find('.result_picture>img').attr('src', URL.createObjectURL(file.files[0]))
              }
            })
          })

            $("#form-agregarcontacto-b").bind("paste", function(event){
                var items = (event.clipboardData || event.originalEvent.clipboardData).items;
                console.log(items);
                console.log((event.clipboardData || event.originalEvent.clipboardData));
                var files = []
                for (index in items) {
                    var item = items[index];
                    if (item.kind === 'file') {
                        var file = item.getAsFile()
                        files.push(file)
                    }
                }
                if (files.length > 0) {
                    $('#form-agregarcontacto-b').find('.result_picture').css('display', 'block')
                    console.log(URL.createObjectURL(files[0]))
                    $('#form-agregarcontacto-b').find('.result_picture>img').attr('src', URL.createObjectURL(files[0]))
                    dataForm_agregarcontacto_b.agregarcontacto_b_captura = files[0]
                }
            } );

          $(document).on("submit", "form.agregarcontacto", function (e) {
            e.preventDefault();
            var form = null;
            var formData = new FormData();
            switch (e.target.id)
            {
              case 'form-agregarcontacto-b':
                let cant_txtSustentoBloqueo= $("textarea[name='txtSustentoBloqueo']").val().length;
                dataForm_agregarcontacto_b.txtSustentoBloqueo = $("textarea[name='txtSustentoBloqueo']").val()

                if (cant_txtSustentoBloqueo == 0) {
                  Swal.fire('Error', 'No se puede ingresar un sustento vacio', 'warning').then(function () {
                    console.log("before")
                    $("textarea[name='txtSustentoBloqueo']").focus()
                  });
                  return false;
                }
                if (dataForm_agregarcontacto_b.agregarcontacto_b_captura === undefined) {
                  Swal.fire('Error', 'No se puede ingresar una captura vacia', 'warning');
                  return false;
                }

                var cliente_id= $('#cbxClienteBloqueo').val();
                var sustentoBloqueo= $('#txtSustentoBloqueo').val();

                var formBloqueos = new FormData();
                formBloqueos.append("cliente_id", cliente_id);
                formBloqueos.append("sustentoBloqueo", sustentoBloqueo);
                formBloqueos.append("agregarcontacto_b_captura", dataForm_agregarcontacto_b.agregarcontacto_b_captura );

                $.ajax({
                  processData: false,
                  contentType: false,
                  type: 'POST',
                  url: "{{ route('solicitabloqueocliente') }}",
                  data: formBloqueos,
                  success: function (data) {
                    console.log('BLoqueando',data);
                    Swal.fire('Notificacion', 'Se solicito el bloqueo del contacto correctamente.', 'success');
                    $('#cbxClienteBloqueo').val('-1');
                    $('#txtSustentoBloqueo').val('');
                    $('#form-agregarcontacto-b').find('.result_picture>img').attr('src', "")
                    dataForm_agregarcontacto_b.agregarcontacto_b_captura = ""
                    $('#cbxClienteBloqueo').html(data.html).selectpicker("refresh");
                  }
                });
                break;
              case 'form-agregarcontacto-n':
                var cliente_id= $('#cbxClienteAgregaNuevo').val();
                var contacto_nombre= $('#txtNombreContactoNuevo').val();
                $.ajax({
                  url: "{{ route('agregarcontactonuevo') }}",
                  method: 'POST',
                  data:{cliente_id:cliente_id,contacto_nombre:contacto_nombre},
                  success: function (data) {
                    Swal.fire('Notificacion', 'Se guardo el contacto correctamente.', 'success');
                    $('#cbxClienteAgregaNuevo').val('-1');
                    $('#txtNombreContactoNuevo').val('');
                    $('#cbxClienteAgregaNuevo').html(data.html).selectpicker("refresh");
                  }
                });
                break;
              case 'form-agregarcontacto-cno':
                var cno_cliente_id= $('#cbxCambiaNombre').val();
                var cno_cambio_nombre= $('#txtCambiaNombre').val();
                if (cno_cliente_id == -1) {
                  Swal.fire('Error', 'Seleccione un cliente', 'warning').then(function () {
                    $("select[name='cbxCambiaNombre']").focus()
                  });
                  return false;
                }

                if (cno_cambio_nombre == '') {
                  Swal.fire('Error', 'No se puede enviar el nombre vacio', 'warning').then(function () {
                    console.log("before")
                    $("text[name='txtCambiaNombre']").focus()
                  });
                  return false;
                }
                var formCambioNombre = new FormData();
                formCambioNombre.append("cno_cliente_id", cno_cliente_id);
                formCambioNombre.append("cno_cambio_nombre", cno_cambio_nombre);
                $.ajax({
                  processData: false,
                  contentType: false,
                  url: "{{ route('cambiarnombrecontacto') }}",
                  method: 'POST',
                  data:formCambioNombre,
                  success: function (data) {
                    Swal.fire('Notificacion', 'Se solicito el cambio de nombre correctamente.', 'success');
                    $('#cbxCambiaNombre').val('-1');
                    $('#txtCambiaNombre').val('');
                    $('#cbxCambiaNombre').html(data.html).selectpicker("refresh");
                  }
                });
                break;

              case 'form-agregarcontacto-cnu':
                var cnu_cliente_id= $('#cbxCambiaNumero').val();
                var cnu_cambio_numero= $('#txtCambioNumeroNuevo').val();
                if (cnu_cliente_id == -1) {
                  Swal.fire('Error', 'Seleccione un cliente', 'warning').then(function () {
                    $("select[name='cbxCambiaNumero']").focus()
                  });
                  return false;
                }

                if (cnu_cambio_numero == '') {
                  Swal.fire('Error', 'No se puede enviar el numero vacio', 'warning').then(function () {
                    console.log("before")
                    $("text[name='txtCambioNumeroNuevo']").focus()
                  });
                  return false;
                }
                var formCambioNumero = new FormData();
                formCambioNumero.append("cnu_cliente_id", cnu_cliente_id);
                formCambioNumero.append("cnu_cambio_numero", cnu_cambio_numero);
                $.ajax({
                  processData: false,
                  contentType: false,
                  url: "{{ route('cambiarnumerocontacto') }}",
                  method: 'POST',
                  data:formCambioNumero,
                  success: function (data) {
                    Swal.fire('Notificacion', 'Se solicito el cambio de numero correctamente.', 'success');
                    $('#cbxCambiaNumero').val('-1');
                    $('#txtCambioNumeroAnterior').val('');
                    $('#txtCambioNumeroNuevo').val('');
                    $('#cbxCambiaNumero').html(data.html).selectpicker("refresh");
                  }
                });
                break;
            }

          });

          $(document).on('click',
            "button#btn_agregarcontacto_n,button#btn_agregarcontacto_cno,button#btn_agregarcontacto_b,button#btn_agregarcontacto_cnu",
            function (e) {
              console.log(e.target.id);
              ocultar_div_modal_agregarcontacto();
              switch (e.target.id) {
                case 'btn_agregarcontacto_n':
                  $.ajax({
                    url: "{{ route('clientecomboagregarcontacto') }}",
                    method: 'POST',
                    success: function (data) {
                      $('#cbxClienteAgregaNuevo').html(data.html).selectpicker("refresh");
                      $("#modal-agregarcontacto-n-container").show();
                    }
                  });
                  break;
                case 'btn_agregarcontacto_cno':
                  $.ajax({
                    url: "{{ route('clientecomboagregarcontacto') }}",
                    method: 'POST',
                    success: function (data) {
                      $('#cbxCambiaNombre').html(data.html).selectpicker("refresh");
                      $("#modal-agregarcontacto-cno-container").show();
                    }
                  });
                  break;
                case 'btn_agregarcontacto_b':
                  $.ajax({
                    url: "{{ route('clientecomboagregarcontacto') }}",
                    method: 'POST',
                    success: function (data) {
                      $('#cbxClienteBloqueo').html(data.html).selectpicker("refresh");
                      $("#modal-agregarcontacto-b-container").show();
                    }
                  });
                  break;
                case 'btn_agregarcontacto_cnu':
                  $.ajax({
                    url: "{{ route('clientecomboagregarcontacto') }}",
                    method: 'POST',
                    success: function (data) {
                      $('#cbxCambiaNumero').html(data.html).selectpicker("refresh");
                      $("#modal-agregarcontacto-cnu-container").show();
                    }
                  });
                  break;
              }

            })

          $("#cbxCambiaNumero").on("change", function () {
            var valcelular = $("option[value=" + $(this).val() + "]", this).attr('valcelular');
            $('#txtCambioNumeroAnterior').val(valcelular)
          });

          //btn_componente-1
          $('#modal-annuncient-1').on('show.bs.modal', function (event) {
            ocultar_div_modal1();
            $("#opciones_modal1")
              .html("")
              .append($('<option/>').attr({'value': 'op-1-row'}).text('Base fria y referido'))
              .append($('<option/>').attr({'value': 'op-2-row'}).text('Autorizacion para subir pedido'))
              .append($('<option/>').attr({'value': 'op-3-row'}).text('Eliminar Pago'))
              //.append($('<option/>').attr({'value': 'op-4-row'}).text('Agrega Contacto'))
              .selectpicker("refresh")
          })

          function  fnListaTablaLlamadas(vtipo,vrbnvalue){
            tblListadoLlamadas.destroy();
            tblListadoLlamadas = $('#tablaListadoLlamadas').DataTable({
              responsive: true,
              "bPaginate": true,
              "bFilter": true,
              "bInfo": false,
              "pageLength": 10,
              'ajax': {
                url: "{{ route('listtablecontactos') }}",
                data:{tipo:vtipo,rbnvalue:vrbnvalue},
                "type": "get",
              },
              initComplete: function (settings, json) {
                var totalListaNuevos=tblListadoLlamadas.rows().count();
                if (vrbnvalue==1){
                  insertContador("i.btnSinGuardarCont", '.dot-notify.noti-side', totalListaNuevos);
                }else if (vrbnvalue==2){
                  insertContador("i.btnGuardadoCont", '.dot-notify.noti-side', totalListaNuevos);
                }else if (vrbnvalue==3){
                  insertContador("i.btnConfirmadoCont", '.dot-notify.noti-side', totalListaNuevos);
                }
              },
              columns: [
                {data: 'tipo_insert', name: 'tipo_insert'},
                {data: 'codigo_asesor', name: 'codigo_asesor'},
                {data: 'celular', name: 'celular',},
                {data: 'nombres_cliente', name: 'nombre_cliente',},
                {data: 'nombre_contacto', name: 'nombre_contacto',},
                {data: 'action', name: 'action',},
              ],
              "createdRow": function (row, data, dataIndex) {
                if(data["guardado"]==1)
                {
                  $(row).css('background', '#F6F7C1').css('text-align', 'center').css('font-weight', 'bold');
                }
              },
              order: false,
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
          }
          function  fnListaCambioNombre(vtipo,vrbnvalue){
            tblCambioNombre.destroy();
            tblCambioNombre = $('#tablaCambioNombre').DataTable({
              responsive: true,
              "bPaginate": true,
              "bFilter": true,
              "bInfo": false,
              'ajax': {
                url: "{{ route('listtablecontactos') }}",
                data:{tipo:vtipo,rbnvalue:vrbnvalue},
                "type": "get",
              },
              columns: [
                {data: 'tipo_insert', name: 'tipo_insert'},
                {data: 'codigo_asesor', name: 'codigo_asesor'},
                {data: 'celular', name: 'celular',},
                {data: 'nombres_cliente', name: 'nombre_cliente',},
                {data: 'nombre_contacto', name: 'nombre_contacto',},
                {data: 'action', name: 'action',},
              ],
              initComplete: function (settings, json) {
                var totalCambioNombre=tblCambioNombre.rows().count();
                if (vrbnvalue==1){
                  insertContador("i.btnNoSaveContCamNom", '.dot-notify.noti-side', totalCambioNombre);
                }else if (vrbnvalue==2){
                  insertContador("i.btnSavedContCamNom", '.dot-notify.noti-side', totalCambioNombre);

                }else if (vrbnvalue==3){
                  insertContador("i.btnConfirmContCamNom", '.dot-notify.noti-side', totalCambioNombre);
                }
              },
              "createdRow": function (row, data, dataIndex) {
                if(data["guardado"]==1)
                {
                  $(row).css('background', '#F6F7C1').css('text-align', 'center').css('font-weight', 'bold');
                }
              },
              order: false,
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
          }
          function  fnListaBloqueoClientes(vtipo,vrbnvalue){
            tblBloqueoClientes.destroy();
            tblBloqueoClientes = $('#tablaBloqueoClientes').DataTable({
              responsive: true,
              "bPaginate": true,
              "bFilter": true,
              "bInfo": false,
              'ajax': {
                url: "{{ route('listtablecontactos') }}",
                data:{tipo:vtipo,rbnvalue:vrbnvalue},
                "type": "get",
              },
              columns: [
                {data: 'tipo_insert', name: 'tipo_insert'},
                {data: 'codigo_asesor', name: 'codigo_asesor'},
                {data: 'celular', name: 'celular',},
                {data: 'foto', name: 'foto',},
                {data: 'nombre_contacto', name: 'nombre_contacto',},
                {data: 'action', name: 'action',},
              ],
              initComplete: function (settings, json) {
                var totalBloqueados=tblBloqueoClientes.rows().count();
                if (vrbnvalue==1){
                  insertContador("i.btnNoSaveContBloq", '.dot-notify.noti-side', totalBloqueados);
                }else if (vrbnvalue==2){
                  insertContador("i.btnSavedContBloq", '.dot-notify.noti-side', totalBloqueados);

                }else if (vrbnvalue==3){
                  insertContador("i.btnConfirmContBloq", '.dot-notify.noti-side', totalBloqueados);
                }
              },
              "createdRow": function (row, data, dataIndex) {
                if(data["guardado"]==1)
                {
                  $(row).css('background', '#F6F7C1').css('text-align', 'center').css('font-weight', 'bold');
                }
              },
              order: false,
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
          }
          function  fnListaCambioNumero(vtipo,vrbnvalue){
            tblCambioNumero.destroy();
            tblCambioNumero = $('#tablaCambioNumero').DataTable({
              responsive: true,
              "bPaginate": true,
              "bFilter": true,
              "bInfo": false,
              'ajax': {
                url: "{{ route('listtablecontactos') }}",
                data:{tipo:vtipo,rbnvalue:vrbnvalue},
                "type": "get",
              },
              columns: [
                {data: 'tipo_insert', name: 'tipo_insert'},
                {data: 'codigo_asesor', name: 'codigo_asesor'},
                {data: 'celular', name: 'celular',},
                {data: 'nombres_cliente', name: 'nombre_cliente',},
                {data: 'nombre_contacto', name: 'nombre_contacto',},
                {data: 'action', name: 'action',},
              ],
              initComplete: function (settings, json) {
                var totalCambioNumero=tblCambioNumero.rows().count();
                if (vrbnvalue==1){
                  insertContador("i.btnNoSaveContCamNro", '.dot-notify.noti-side', totalCambioNumero);
                }else if (vrbnvalue==2){
                  insertContador("i.btnSavedContCamNro", '.dot-notify.noti-side', totalCambioNumero);

                }else if (vrbnvalue==3){
                  insertContador("i.btnConfirmContCamNro", '.dot-notify.noti-side', totalCambioNumero);
                }
              },
              "createdRow": function (row, data, dataIndex) {
                if(data["guardado"]==1)
                {
                  $(row).css('background', '#F6F7C1').css('text-align', 'center').css('font-weight', 'bold');
                }
              },
              order: false,
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
          }

          $('#modal-llamadas-1').on('show.bs.modal', function (event) {
              $.ajax({
                  type: 'GET',
                  url: "{{ route('listcontadorescontactos') }}",
                  success: function (data) {
                      console.log(data);
                      insertContador("i.btnNewClienteCont", '.dot-notify.noti-side', data.nuevoCliente);
                      insertContador("i.btnChangeNameCont", '.dot-notify.noti-side', data.cambioNombre);
                      insertContador("i.btnBloqueoCont", '.dot-notify.noti-side', data.contbloqueo);
                      insertContador("i.btnChangeNroCont", '.dot-notify.noti-side', data.cambioNumero);
                  }
              });
            fnListaTablaLlamadas(1,1);
            fnListaCambioNombre(2,1);
            fnListaBloqueoClientes(3,1);
            fnListaCambioNumero(4,1);
            ocultar_div_modal_listado_clientes();
          })


          $('#tablaListadoLlamadas tbody').on('click', 'button.btnGuardado', function () {
            var data = tblListadoLlamadas.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.guardado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaListadoLlamadas').DataTable().ajax.reload();
              }


            });

          })

          $('#tablaListadoLlamadas tbody').on('click', 'button.btnConfirmado', function () {
            var data = tblListadoLlamadas.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.confirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaListadoLlamadas').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaListadoLlamadas tbody').on('click', 'button.btnReconfirmado', function () {
            var data = tblListadoLlamadas.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);
            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('enviarreconfirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaListadoLlamadas').DataTable().ajax.reload();
              }
            });
          })



          $('#tablaCambioNombre tbody').on('click', 'button.btnGuardado', function () {
            var data = tblCambioNombre.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.guardado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaCambioNombre').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaCambioNombre tbody').on('click', 'button.btnConfirmado', function () {
            var data = tblCambioNombre.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.confirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaCambioNombre').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaCambioNombre tbody').on('click', 'button.btnReconfirmado', function () {
            var data = tblCambioNombre.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);
            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('enviarreconfirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaCambioNombre').DataTable().ajax.reload();
              }
            });
          })

          $('#tablaBloqueoClientes tbody').on('click', 'button.btnGuardado', function () {
            var data = tblBloqueoClientes.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.guardado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaBloqueoClientes').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaBloqueoClientes tbody').on('click', 'button.btnConfirmado', function () {
            var data = tblBloqueoClientes.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.confirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaBloqueoClientes').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaBloqueoClientes tbody').on('click', 'button.btnReconfirmado', function () {
            var data = tblBloqueoClientes.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);
            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('enviarreconfirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaBloqueoClientes').DataTable().ajax.reload();
              }
            });
          })

          $('#tablaCambioNumero tbody').on('click', 'button.btnGuardado', function () {
            var data = tblCambioNumero.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.guardado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaCambioNumero').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaCambioNumero tbody').on('click', 'button.btnConfirmado', function () {
            var data = tblCambioNumero.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);

            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('alertas.confirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaCambioNumero').DataTable().ajax.reload();
              }


            });

          })
          $('#tablaCambioNumero tbody').on('click', 'button.btnReconfirmado', function () {
            var data = tblCambioNumero.row($(this).parents('tr')).data();
            console.log('datos table',data);
            var idllamada=data.id;
            var formLlamadas = new FormData();
            formLlamadas.append("detalle_contactos_id", idllamada);
            $.ajax({
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('enviarreconfirmado') }}",
              data: formLlamadas,
              success: function (data) {
                console.log(data);
                $('#tablaCambioNumero').DataTable().ajax.reload();
              }
            });
          })

          /*window.ocultar_div_modal_correccion_pedidos = function () {
            console.log("ocultar div asd")
            $("#modal-correccionpedido-pc-container").hide();
            $("#modal-correccionpedido-f-container").hide();
            $("#modal-correccionpedido-g-container").hide();
            $("#modal-correccionpedido-b-container").hide();
          }*/

          window.ocultar_div_modal_listado_clientes = function () {
            console.log("ocultar div asd")
            $("#modal-ListadoClientes").hide();
            $("#modal-CambioNombre").hide();
            $("#modal-BLoqueoCliente").hide();
            $("#modal-CambioNumero").hide();
          }

          $(document).on('click',
            "button#btnListNuevoCliente,button#btnListCambioNombre,button#btnListBloqueo,button#btnListCambioNumero",
            function (e) {
              ocultar_div_modal_listado_clientes();
              switch (e.target.id) {
                case 'btnListNuevoCliente':
                  $("#modal-ListadoClientes").show();
                  break;
                case 'btnListCambioNombre':
                  $("#modal-CambioNombre").show();
                  break;
                case 'btnListBloqueo':
                  $("#modal-BLoqueoCliente").show();
                  break;
                case 'btnListCambioNumero':
                  $("#modal-CambioNumero").show();
                  break;
              }

            })
          $("input[name='rbnTipo']",$('#radioBtnDiv')).change(function(e)
          {
            var valorRadioButton= $(this).val();
            fnListaTablaLlamadas(1,valorRadioButton);
          });
          $("input[name='rbnTipo2']",$('#radioBtnDiv2')).change(function(e)
          {
            var valorRadioButton2= $(this).val();
            fnListaCambioNombre(2,valorRadioButton2);
          });
          $("input[name='rbnTipo3']",$('#radioBtnDiv3')).change(function(e)
          {
            var valorRadioButton3= $(this).val();
            fnListaBloqueoClientes(3,valorRadioButton3);
            fnListaCambioNumero(4,1);
          });
          $("input[name='rbnTipo4']",$('#radioBtnDiv4')).change(function(e)
          {
            var valorRadioButton4= $(this).val();
            fnListaCambioNumero(4,valorRadioButton4);
          });
            $(document).on("change", "#opciones_modal1", function () {
                let value = $(this).val();
                ocultar_div_modal1();
                switch (value) {
                    case 'op-1-row':
                        $("#op-1-row").show()
                        break;
                    case 'op-2-row':
                        $("#op-2-row").show()
                        break;
                    case 'op-3-row':
                        $("#op-3-row").show()
                        break;
                    case 'op-4-row':
                        $("#op-4-row").show()
                        break;
                }
                cargar_asesor_modal1();
            })

            window.cargar_asesor_modal1 = function () {
                let value = $("#opciones_modal1").val();
                switch (value) {
                    case 'op-1-row':
                        $.ajax({
                            url: "{{ route('asesorcombomodal') }}",
                            method: 'POST',
                            success: function (data) {
                                $('#asesor_op1').html(data.html);
                                $("#asesor_op1").selectpicker("refresh").trigger("change");
                            }
                        });
                        break;
                    case 'op-2-row':
                        $.ajax({
                            url: "{{ route('asesorcombomodal') }}",
                            method: 'POST',
                            success: function (data) {
                                $('#asesor_op2').html(data.html);
                                $("#asesor_op2").selectpicker("refresh").trigger("change");
                            }
                        });
                        break;
                    case 'op-3-row':
                        $.ajax({
                            url: "{{ route('asesorcombomodal') }}",
                            method: 'POST',
                            success: function (data) {
                                $('#asesor_op3').html(data.html);
                                $("#asesor_op3").selectpicker("refresh").trigger("change");
                            }
                        });
                        break;
                    case 'op-4-row':
                        $.ajax({
                            url: "{{ route('asesorcombomodal') }}",
                            method: 'POST',
                            success: function (data) {
                                $('#asesor_op4').html(data.html);
                                $("#asesor_op4").selectpicker("refresh").trigger("change");
                            }
                        });
                        break;
                }

            }

            $(document).on("change", "#asesor_op1", function () {
                $.ajax({
                    url: $(this).data("ruta"),
                    method: 'GET',
                    data: {"user_id": $(this).val()},
                    success: function (data) {
                        $('#cliente_op1').html(data.html);
                        $("#cliente_op1").selectpicker("refresh");
                    }
                });
            });
            $(document).on("change", "#asesor_op2", function () {
                $.ajax({
                    url: $(this).data("ruta"),
                    method: 'GET',
                    data: {"user_id": $(this).val()},
                    success: function (data) {
                        $('#cliente_op2').html(data.html);
                        $("#cliente_op2").selectpicker("refresh");
                    }
                });
            });
            $(document).on("change", "#asesor_op3", function () {
                $.ajax({
                    url: $(this).data("ruta"),
                    method: 'GET',
                    data: {"user_id": $(this).val()},
                    success: function (data) {
                        $('#cliente_op3').html(data.html);
                        $("#cliente_op3").selectpicker("refresh");
                    }
                });
            });
            $(document).on("change", "#asesor_op4", function () {

                $.ajax({
                    url: $(this).data("ruta"),
                    method: 'GET',
                    data: {"user_id": $(this).val()},
                    success: function (data) {
                        $('#cliente_op4').html(data.html);
                        $("#cliente_op4").selectpicker("refresh");
                    }
                });
            });

            $('#clientenuevo_op1').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $(document).on("submit", "#form-op-1-row", function (event) {
                event.preventDefault();
                var form = $('#form-op-1-row')[0];
                var formData = new FormData(form);
                formData.append('opcion', "1");
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('ajax_modal1_response') }}",
                    success: function (data) {
                        if(data.html!="0")
                        {
                            /*PNotify.notice({
                                text: 'Notice 1.',
                            });*/
                            $("#modal-annuncient-1").modal("hide");
                            console.log("response 1")
                        } else {
                            console.log("response 0")
                        }
                    }
                })
            })

            $(document).on("submit", "#form-op-2-row", function (event) {
                event.preventDefault();
                var form = $('#form-op-2-row')[0];
                var formData = new FormData(form);
                formData.append('opcion', "2");
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('ajax_modal1_response') }}",
                    success: function (data) {
                        if(data.html!="0")
                        {
                            /*PNotify.notice({
                                text: 'Notice 2.',
                            });*/
                            $("#modal-annuncient-1").modal("hide");
                            console.log("response 1")
                        } else {
                            console.log("response 0")
                        }
                    }
                })
            })

            $(document).on("submit", "#form-op-3-row", function (event) {
                event.preventDefault();
                var form = $('#form-op-3-row')[0];
                var formData = new FormData(form);
                formData.append('opcion', "3");
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('ajax_modal1_response') }}",
                    success: function (data) {
                        if(data.html!="0")
                        {
                            /*PNotify.notice({
                                text: 'Notice 3.',
                            });
                            $("#modal-annuncient-3").modal("hide");*/
                            $("#modal-annuncient-1").modal("hide");
                            console.log("response 1")
                        } else {
                            console.log("response 0")
                        }
                    }
                })
            })

            /*$(document).on("submit","#form-op-4-row",function(event) {
                event.preventDefault();
                var form = $('#form-op-4-row')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,processData: false,contentType: false,type: 'GET',url: "{{ route('ajax_modal1_response') }}",
                    success: function (data) {
                        if(data.html=="1")
                        {
                            $.notify("Hello World");
                            console.log("response 1")
                        }else{
                            console.log("response 0")
                        }
                    }
                })
            })*/


        });
    </script>
    <script>
        $(document).ready(function () {
            if (document.location.href != '{{route('envios.distribuirsobres')}}') {
                for (var key in localStorage) {
                    if (key.includes('.envios.distribuirsobres')) {
                        localStorage.removeItem(key)
                    }
                }
            }
            $('#modal-imagen-contacto').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('imagen');
                var str = "storage/" + idunico;
                var urlimage = '{{ asset(":id") }}';
                urlimage = urlimage.replace(':id', str);
                console.table('jaaaa',idunico)
                console.table('str',str)
                console.table('urlimage',urlimage)
                $("#modal-imagen-contacto .img-thumbnail").attr("src", urlimage);
            });

            $('#modal-escanear-estado-sobre').on('show.bs.modal', function (event) {
                $('#info-pedido').html('<div class="text-center"><img src="{{asset('imagenes/scan.gif')}}" width="300" class="mr-8"><h5 class="font-weight-bold">Escanee un pedido para saber sus detalles</h5></div>');
                $('#input-info-pedido').focus();
                $('#input-info-pedido').val("");

                $('#input-info-pedido').unbind();
                $('#input-info-pedido').change(function (event) {
                    event.preventDefault();

                    var codigo_caturado = ($(this).val() || '').trim();
                    var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-').replaceAll("'", '-').replaceAll("(", '*');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('escaneo.estado_pedidos') }}",
                        data: {
                            'codigo': codigo_mejorado,
                        },
                        success: function (data) {
                            console.log(data);
                            if (data.codigo == 0) {
                                $('#info-pedido').html('<div class="text-danger text-center"><i class="fa fa-exclamation-triangle font-44" aria-hidden="true"></i><br><h4 class="font-weight-bold">Este pedido no se encuentra en el sistema</h4></div>');
                            } else if (data.codigo == 1) {
                                $('#input-info-pedido').val("");

                                var InfoString = '<h4 class="font-16 font-weight-bold">Información del pedido:</h4> <table class="table w-100">';
                                InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">Codigo</td><td>' + data.pedido.codigo + '</td><td class="font-weight-bold p-8">Estado</td><td style="width: 250px;"><span class="bagde p-8 br-12 font-weight-bold" style="font-size:12px; background-color: ' + data.pedido.condicion_envio_color + '">' + data.pedido.condicion_envio + '<s/pan></td></tr>';
                                InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0"></td><td></td><td class="font-weight-bold p-8"></td><td></td></tr>';
                                // SI TIENE DIRECCION
                                if (data.pedido.estado_sobre == 0) {

                                    InfoString += '<tr><td class="font-weight-bold p-8">Tiene Direccion</td><td colspan="3"> NO TIENE DIRECCION</td></tr>';
                                } else {

                                    InfoString += '<tr><td colspan="4" class="font-weight-bold p-8" style="background-color:#ededed;"><i class="fa fa-map-marker text-success mr-12" aria-hidden="true"></i> DIRECCION</td></tr>';
                                    InfoString += '<tr><td class="font-weight-bold">Dirección</td><td>' + data.pedido.env_direccion + '</td>';
                                    if (data.pedido.env_zona == 'OLVA') {
                                        InfoString += '<tr>';
                                    } else {
                                        InfoString += '<td class="font-weight-bold p-8">Distrito</td><td>' + data.pedido.env_distrito + '</td></tr>';
                                    }

                                    InfoString += '<tr><td class="font-weight-bold">Zona</td><td>' + data.pedido.env_zona + '</td><td class="font-weight-bold p-8">Destino</td><td>' + data.pedido.env_destino + '</td></tr>';

                                }
                                // SI ESTA ASIGNADO A UN MOTORIZADO
                                if (data.pedido.direccion_grupo == null) {
                                    InfoString += '<tr><td class="font-weight-bold p-8">Esta asignado a una zona?</td><td colspan="3"> NO</td></tr>';
                                } else {
                                    //SI TIENE MOTORIZADO
                                    if (data.pedido.direcciongrupo.motorizado == null) {
                                        InfoString += '<tr><td class="font-weight-bold p-8">Se encuentra en Reparto?</td><td colspan="3"> NO</td></tr>';
                                    } else {
                                        if (data.pedido.direcciongrupo.fecha_salida == null) {
                                            var env_fecha_salida = "Fecha no asignada";
                                        } else {
                                            var env_fecha_salida = data.pedido.direcciongrupo.fecha_salida;
                                        }
                                        InfoString += '<tr><td colspan="4" class="font-weight-bold p8 pt-8 pb-8" style="background-color:#ededed;"><i class="fa fa-motorcycle text-primary mr-12" aria-hidden="true"></i> COURIER</td></tr>';
                                        InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">Nombre Motorizado</td><td>' + data.pedido.direcciongrupo.motorizado.name + '</td><td class="font-weight-bold p-8">Zona motorizado</td><td>' + data.pedido.direcciongrupo.motorizado.zona + '</td></tr>';
                                        InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">Zona</td><td>' + data.pedido.direcciongrupo.distribucion + '</td><td class="font-weight-bold p-8">Fecha de salida</td><td>' + data.pedido.direcciongrupo.fecha_salida_format + '</td></tr>';
                                        InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">ID Grupo</td><td>' + data.pedido.direcciongrupo.id + '</td><td class="font-weight-bold p-8">Estado Grupo</td><td><span class="bagde p-8 br-12 font-weight-bold font-11" style="background-color: #f97100; padding: 4px 16px !important; background-color: ' + data.pedido.condicion_envio_color + '">' + data.pedido.direcciongrupo.condicion_envio + '</span></td></tr>';

                                        //SI TIENE MOTORIZADO
                                        if (data.pedido.condicion_envio_code == 10) {
                                            InfoString += '<tr><td colspan="4" class="font-weight-bold p8 pt-8 pb-8" style="background-color:#ededed;"><i class="fa fa-paperclip text-danger mr-12" aria-hidden="true"></i> ADJUNTOS</td></tr>';
                                            InfoString += '<tr><td><img style="width:150px; height: 150px; object-fit:cover;" src="/storage/' + data.pedido.direcciongrupo.foto1 + '"></td>' +
                                                '<td><img style="width:150px; height: 150px; object-fit:cover;" src="/storage/' + data.pedido.direcciongrupo.foto2 + '"></td>' +
                                                '<td class="font-weight-bold p-8"><img style="width:150px; height: 150px; object-fit:cover;" src="/storage/' + data.pedido.direcciongrupo.foto3 + '"></td>' +
                                                '<td></td></tr>';
                                        } else {
                                            InfoString += '<tr><td class="font-weight-bold p-8">Tiene adjuntos?</td><td colspan="3"> NO</td></tr>';
                                        }
                                    }
                                }
                            }
                            $('#info-pedido').html(InfoString);

                        }
                    }).always(function () {
                        $('#codigo_confirmar').focus();
                    });

                    return false;
                });
            });
        })
    </script>
    <script>
        $(document).ready(function () {
            PNotify.defaultModules.set(PNotifyMobile, {});
            PNotify.defaultModules.set(PNotifyBootstrap4, {});
            PNotify.defaultModules.set(PNotifyFontAwesome4, {});
            //https://sciactive.com/pnotify/demo/styling.html
            $('[data-toggle=addalert]').click(function () {
                $.confirm({
                    theme: 'material',
                    type: 'dark',
                    icon: 'fa fa-plus',
                    title: 'Agregar Nota',
                    content: `<form>
<div class="p-2">
<div class="form-group">
<label>Titulo</label>
<input type="text" class="form-control" name="title">
</div>
<div class="form-group">
<label>Fecha (opcional)</label>
<input type="datetime-local" class="form-control" name="fecha">
</div>
<div class="form-group">
<label>Nota</label>
<textarea type="text" class="form-control" rows="5" name="nota"></textarea>
</div>
</div></form>`,
                    buttons: {
                        cancelar: {
                            btnClass: 'btn-ligth'
                        },
                        agregar: {
                            btnClass: 'btn-dark',
                            action: function () {
                                const self = this
                                const form = self.$content.find('form')
                                if (!form[0].title.value) {
                                    $.confirm({
                                        type: 'red',
                                        title: 'Advertencia',
                                        content: `Es necesario ingresar un titulo`
                                    })
                                    return false
                                }
                                if (!form[0].nota.value) {
                                    $.confirm({
                                        type: 'red',
                                        title: 'Advertencia',
                                        content: `Es necesario ingresar una nota`
                                    })
                                    return false
                                }
                                self.showLoading(true)
                                $.post('{{route('alertas.store')}}', form.serialize()).always(function () {
                                    self.hideLoading(true)
                                })
                            }
                        },
                    }
                })
            })
          $('[data-toggle=contactoalert]').click(function () {
            $.confirm({
              theme: 'material',
              draggable: true,
              type: 'dark',
              icon: 'fa fa-plus',
              title: 'Agregar Contacto',
              columnClass:'large',
              content: function () {
                const self = this
                return $.get('{!! route('cargar.clientemodal1',['user_id'=>user()->identificador,'rol'=>user_rol()]) !!}').done(function (data) {
                  self.setContent(`<form class="p-2" style="height: 35vh">
<div class="row">
<div class="form-group col-10">
<label>Cliente </label>
<select type="text" class="form-control" name="client_id">${data.html}</select>
</div>
<div class="form-group col-12">
<label>Nombre q quiere q tenga su contacto</label>
<input type="text" class="form-control"  name="contact_name">
</div>
</div></form>`)
                })
              },
              buttons: {
                cancelar: {
                  btnClass: 'btn-ligth'
                },
                agregar: {
                  btnClass: 'btn-dark',
                  action: function () {
                    const self = this
                    const form = self.$content.find('form')
                    if (!form[0].client_id.value) {
                      $.confirm({
                        type: 'red',
                        title: 'Advertencia',
                        content: `Es necesario seleccionar un cliente`
                      })
                      return false
                    }
                    if (!form[0].contact_name.value) {
                      $.confirm({
                        type: 'red',
                        title: 'Advertencia',
                        content: `Es necesario ingresar el nombre q quiere q tenga su contacto`
                      })
                      return false
                    }
                    self.showLoading(true)
                    const cliente= self.$content.find( "select option:selected" ).text();
                    $.post('{{route('alertas.store')}}', {
                      tipo:'info',
                      title:'Agregar Contacto',
                      nota:`El asesor "{{user()->identificador}}" solicita agregar un contacto del cliente "${cliente}" con el nombre "${form[0].contact_name.value}" `,
                      user_add_role:['{{\App\Models\User::ROL_LLAMADAS}}',/**Agregar mas roles aca**/],
                      id_usuario:{{user()->id}},
                      cliente_id:form[0].client_id.value,
                      contacto_nombre:form[0].contact_name.value,
                    }).always(function () {
                      self.hideLoading(true)
                    })
                  }
                },
              },
              onContentReady:function () {
                this.$content.find('select').select2({
                  dropdownParent:this.$content,
                  matcher: function matchCustom(params, data) {
                    // If there are no search terms, return all of the data
                    if ($.trim(params.term) === '') {
                      return data;
                    }

                    // Do not display the item if there is no 'text' property
                    if (typeof data.text === 'undefined') {
                      return null;
                    }

                    // `params.term` should be the term that is used for searching
                    // `data.text` is the text that is displayed for the data object
                    if (data.text.includes((params.term||'').trim())) {
                      return $.extend({}, data, true);
                    }

                    // Return `null` if the term should not be displayed
                    return null;
                  }
                })
              }
            })
          })

          $.get('{{route('getvidasusuario')}}',)
            .done(function (data) {
              $('#divListadoVidas').html(data.html);
            })
            .fail(function () {
              //console.log(arguments)
            })
            .always(function () {
              //$("#modal_clientes_deudas_content_loading").hide()
              //console.log(arguments)
            });


        })

    </script>
@stop
