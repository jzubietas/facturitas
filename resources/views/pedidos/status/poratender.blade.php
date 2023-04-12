{{--pedidos/estados/porAtender--}}
@extends('adminlte::page')
@section('title', 'Estado del pedidos')
@section('content_header')

@stop

@section('content')
  <div class="row">
    <div class="col-md-3  d-none">
      <div class="card card-success">
        <div class="card-header">
          <h5>PEDIDOS ATENDIDOS</h5>
        </div>
        <div class="card-body">
          <h4 class="text-center">
            <b>{{$pedidos_atendidos}}</b>
          </h4>
        </div>
      </div>
    </div>
    <div class="col-md-8 p-16">
      <h1>Pedidos por atender</h1>
    </div>
    <div class="col-md-4 p-16">
      <div class="row bg-white">
        <div class="col-lg-8 bg-white p-16"><h5 class="font-weight-bold">Total de pedidos por atender</h5></div>
        <div class="col-lg-4 bg-white p-16">
          <h4 class="text-center text-danger">
            <b>{{$pedidos_por_atender}}</b>
          </h4></div>
      </div>
    </div>
  </div>
  <div class="card" style="overflow: hidden !important;">
    <div class="card-body" style="overflow-x: scroll !important;">
      <div class="table-responsive">
        <table id="tablaBandejaPedidosPorAtender" class="table table-striped">
          <thead>
          <tr>
            <th scope="col" class="align-middle">Item</th>
            <th scope="col" class="align-middle">Código</th>
            <th scope="col" class="align-middle">Cliente</th>
            <th scope="col" class="align-middle">Razón social</th>
            <th scope="col" class="align-middle">Mes</th>
            <th scope="col" class="align-middle">Asesor</th>
            <th scope="col" class="align-middle">Fecha de registro</th>{{--fecha hora--}}
            <th scope="col" class="align-middle">Tipo de Banca</th>
            <th scope="col" class="align-middle">Adjuntos</th>
            <th scope="col" class="align-middle">Estado</th>
            <th scope="col" class="align-middle">Adjuntos Operaciones</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('operaciones.modal.atenderid')
  @include('operaciones.modal.veradjuntoid')

@endsection

@section('css')
    <style>
    .color-recuperado-abandono
    {
    font-family: "Comic Sans MS" !important;
    color:blue !important;
    }
    </style>
@stop

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

  @if (session('info') == 'registrado')
    <script>
      Swal.fire(
        'RUC {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  <script>
    $(document).ready(function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $("#select_status_list").change(function () {
        $('#tablaBandejaPedidosPorAtender').DataTable().ajax.reload();
      })

      function openConfirmDownloadDocuments(action, idc, codigo) {
        $.confirm({
          theme: 'material',
          title: 'Detalle de atencion de <b>' + idc + '</b>',
          buttons: {
            confirm: {
              text: 'Confirmar descarga',
              btnClass: 'btn-success',
              action: function () {
                var $checkbox = this.$content.find('#enableCheckbox');

                if ($checkbox.prop('checked')) {
                  this.showLoading(true)
                  var self = this
                  $.post(action, {
                    'action': 'confirm_download'
                  }).done(function () {
                    self.close();
                    $.confirm('Descarga confirmada correctamente', 'Mensaje');
                  }).fail(function () {
                    self.hideLoading(true)
                  }).always(function () {
                    $('#tablaBandejaPedidosPorAtender').DataTable().ajax.reload();
                  })
                }
                return false
              }
            },
            cancel: {
              text: 'Cancelar',
              btnClass: 'btn-outline-dark',
              action: function () {
                return true
              }
            },
          },
          backgroundDismiss: function () {
            return false; // modal wont close.
          },
          content: function () {
            var self = this;
            return $.ajax({
              url: action,
              dataType: 'json',
              method: 'get'
            }).done(function (response) {
              var html = `<div class="list-group">`
              html += `<li class="list-group-item bg-dark">Codigo: ${codigo}</li>`
              html += `<li class="list-group-item bg-primary">Adjuntos de detalle de atencion</li>`
              html += response.data.map(function (item) {
                return `<li class="list-group-item"><a href="${item.link}" download>${item.adjunto}</a></li>`
              }).join('')
              html += `<li class="list-group-item">
<div class="checkbox"><label><input type="checkbox" id="enableCheckbox"> Termine de descargar</label></div>
</li>`
              html += `</div>`
              self.setContentAppend(html);
            }).fail(function () {
              self.setContent('Ocurrio un error.');
            });
          }
        });
      }

      /*$("#tablaBandejaPedidosPorAtender").bind("DOMSubtreeModified", function() {
          console.log("tree changed",arguments);
      });
      new MutationObserver(() => {
          console.log("tree changed",arguments);
      }).observe(document, {subtree: true, childList: true});
  */
      $('#tablaBandejaPedidosPorAtender').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[0, "desc"]],
        ajax: {
          url: "{{ route('pedidos.estados.poratender',['ajax-datatable'=>1]) }}",
          data: function (d) {
            d.load_data = $("#select_status_list").val();
          },
        },
        drawCallback: function (settings) {
          setTimeout(function () {
            $("[data-toggle=jqConfirm]").on('click', function (e) {
              openConfirmDownloadDocuments($(e.target).data('target'), $(e.target).data('idc'), $(e.target).data('codigo'))
            })
          }, 100)
        },
        createdRow: function (row, data, dataIndex) {

        },
        rowCallback: function (row, data, index) {
          if (data.pendiente_anulacion == 1) {
            $('td', row).css('background', 'red').css('font-weight', 'bold');
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
            }
          },
          {data: 'codigos', name: 'codigos',},
          {
            data: 'celulares',
            name: 'celulares',
          },
          {data: 'empresas', name: 'empresas',},
          {data: 'mes', name: 'mes',},
          {data: 'users', name: 'users',},
          {
            data: 'fecha',
            name: 'fecha',
            render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
            //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ).format('HH:mm:ss'),
          },
          {data: 'tipo_banca', name: 'tipo_banca',},
          {
            data: 'imagenes',
            name: 'imagenes',
            orderable: false,
            searchable: false,
            sWidth: '15%',
            render: function (data, type, row, meta) {
              if (data == null) {
                return '';
              } else {
                if (data > 0) {
                  data = '<a href="" class="btn-fontsize" " data-target="#modal-veradjunto" data-group="1" data-adjunto=' + row.id + ' data-toggle="modal" ><button class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                  return data;
                } else {
                  return '';
                }
              }

            }
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
            sWidth: '15%',
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


      function validarFormulario(evento) {
        var adjunto = document.getElementById('adjunto').files;
        var cant_compro = document.getElementById('cant_compro').value;
        if (adjunto.length == 0) {
          Swal.fire(
            'Error',
            'Debe registrar almenos un documento adjunto',
            'warning'
          )
          return false;
        } else if (cant_compro == '0') {
          Swal.fire(
            'Error',
            'Cantidad de comprobantes enviados debe ser diferente de 0 (cero)',
            'warning'
          )

          return false;
        }
        return true;
      }

      $(document).on("submit", "#formularioatender", function (evento) {
        evento.preventDefault();
        var status = validarFormulario(evento);
        if (!status) {
          return;
        }

        let files = $('input[name="adjunto[]');
        //console.log(files)

        var imagen = $('input[type=file][name="adjunto[]"]')[0].files[0];
        // console.log(imagen)
        //return false;

        var data = new FormData(document.getElementById("formularioatender"));

        var fd = new FormData();

        if (files.length == 0) {
          Swal.fire(
            'Error',
            'Debe ingresar el detalle del pedido',
            'warning'
          )
          return false;
        }

        for (let i = 0; i < files.length; i++) {
          fd.append('adjunto', $('input[type=file][name="adjunto[]"]')[0].files[0]);
        }

        //console.log(files);
        //return false;
        //fd.append( 'cant_compro', $("#cant_compro").val() );
        fd.append('cant_compro', files.length);
        fd.append('condicion', $("#condicion").val());
        fd.append('hiddenAtender', $("#hiddenAtender").val());

        $.ajax({
          data: data,
          processData: false,
          contentType: false,
          type: 'POST',
          url: "{{ route('operaciones.atenderid') }}",
          success: function (data) {
            console.log(data);
            $("#modal-atender .textcode").text('');
            $("#modal-atender").modal("hide");
            $('#tablaBandejaPedidosPorAtender').DataTable().ajax.reload();

          }

        });
        console.log(fd);
      });

      $('#modal-atender').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('atender')
        $(".textcode").html("PED" + idunico);
        $("#hiddenAtender").val(idunico);

      });


      $('#modal-veradjunto').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('adjunto')
        var target = button.data('group');
        $(".textcode").html("PED" + idunico);

        if (target == 1) {
          console.log("Mostrar data de Asesor");
          // VER ADJUNTO DE ASESOR
          //consulta de imagenes
          $.ajax({
            type: 'POST',
            url: "{{ route('pedidoobteneradjuntoRequest') }}",
            data: {"pedido": idunico},
          }).done(function (data) {

            //console.log(data.html);
            console.log(data.cantidad);
            if (data.cantidad > 0) {
              ////recorrer y poner imagenes en div con router
              var adjuntos = data.html.split('|');
              //console.log(adjuntos);
              var urladjunto = "";
              var datal = "";
              $.each(adjuntos, function (index, value) {
                urladjunto = '{{ route("pedidos.descargaradjunto", ":id") }}';
                urladjunto = urladjunto.replace(':id', value);
                datal = datal + '<p><a href="' + urladjunto + '"><i class="fa fa-file mr-2"></i>' + value + '</a><p>';
                //console.log(datal);
                //console.log( index + ": " + value );
              });
              $("#imagenes_adjunto").html(datal)
              return datal;
              //console.log(data.html)
            } else {
              $("#imagenes_adjunto").html("No hay archivos adjuntados en este pedido.")
            }
          });
        } else {
          console.log("Mostrar data de Operaciones");
          // VER ADJUNTO DE OPERACIONES
          $.ajax({
            type: 'POST',
            url: "{{ route('pedidoobteneradjuntoOPRequest') }}",
            data: {"pedido": idunico},
          }).done(function (data) {
            //console.log(data.html);
            console.log(data.cantidad);
            if (data.cantidad > 0) {
              ////recorrer y poner imagenes en div con router
              var adjuntos = data.html.split('|');
              //console.log(adjuntos);
              var urladjunto = "";
              var datal = "";
              $.each(adjuntos, function (index, value) {
                urladjunto = '{{ route("pedidos.descargaradjunto", ":id") }}';
                urladjunto = urladjunto.replace(':id', value);
                datal = datal + '<p><a href="' + urladjunto + '"><i class="fa fa-file mr-2"></i>' + value + '</a><p>';
                //console.log(datal);
                //console.log( index + ": " + value );
              });
              $("#imagenes_adjunto").html(datal)
              return datal;
              //console.log(data.html)
            } else {
              $("#imagenes_adjunto").html("No hay archivos adjuntados en este pedido.")
            }
          });

        }


      });

    });
  </script>
@stop


