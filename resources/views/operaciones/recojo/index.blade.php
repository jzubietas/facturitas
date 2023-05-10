@extends('adminlte::page')

@section('title', 'RECOJO')

@section('content_header')
  <h1 class="text-center">
    <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i> Recojo
  </h1>

@stop

@section('content')

  @include('envios.motorizado.modal.recojo_enviarope')

  <div class="card p-0">

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="enmotorizado" role="tabpanel" aria-labelledby="enmotorizado-tab">
        <table id="tablaRecojo" class="table table-striped w-100">
          <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Item</th>
            <th scope="col" style="vertical-align: middle">Codigo</th>
            <th scope="col" style="vertical-align: middle">Razon social</th>
            <th scope="col" style="vertical-align: middle">Estado Envio</th>
            <th scope="col" style="vertical-align: middle">Sustento</th>
            <th scope="col" style="vertical-align: middle">Acciones</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        @include('operaciones.modal.confirmarRecepcionRecojo')
      </div>

    </div>


  </div>

@stop

@push('css')

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
  <style>
    @media (max-width: 32rem) {
      div.dataTables_wrapper div.dataTables_filter input {
        width: 200px !important;
      }

      .content-wrapper {
        background-color: white !important;
      }

      .card {
        box-shadow: 0 0 1px white !important;
      }
    }

    .yellow_color_table {
      background-color: #ffd60a !important;
    }
    .blue_color_table {
      background-color: #3A98B9 !important;
    }
  </style>
  @include('partials.css.time_line_css')
@endpush

@section('js')

  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

  <script>
    let datatablerecojo = null;

    $(document).ready(function () {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      function renderButtomsDataTable(row, data) {
        if (data.destino == 'PROVINCIA') {
          $('td', row).css('color', '#20c997')
        }
        if (data.estado == 0) {
          $('td', row).css('color', 'red')
        }

      }

      datatablerecojo = $('#tablaRecojo').DataTable({
        //dom: '<"top"i>rt<"bottom"lp><"clear">',
        lengthChange: false,
        processing: true,
        stateSave: false,
        serverSide: true,
        searching: true,
        order: [[0, "desc"]],
        ajax: {
          url: "{{ route('operaciones.recojos.index',['datatable'=>1]) }}",
          data: function (q) {
            q.fechaconsulta = $("#fecha_consulta").val();
            q.tab = 'enmotorizado'
          }
        },
        initComplete: function () {

        },
        createdRow: function (row, data, dataIndex) {
          if(data["condicion_envio_code"]==31)
          {
            $(row).addClass('yellow_color_table');
          }else if(data["condicion_envio_code"]==32)
          {
            $(row).addClass('blue_color_table');
          }
        },
        drawCallback: function (settings) {
          console.log(settings.json);
          $("#tablaPrincipal").DataTable().columns().header()[12].innerText = $('a[data-toggle="tab"].active').data('action-name')
        },
        rowCallback: function (row, data, index) {
          renderButtomsDataTable(row, data)
          if (data.cambio_direccion_at != null) {
            $('td', row).css('background', 'rgba(17,129,255,0.35)')
          }
        },
        columns: [
          {
            data: 'correlativo',
            name: 'correlativo',
          },
          {
            data: 'codigos',
            name: 'codigos',
          },
          {
            data: 'producto',
            name: 'producto',
          },
          {data: 'condicion_envio', name: 'condicion_envio',},
            {data: 'env_sustento_recojo', name: 'env_sustento_recojo',},
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
          //$('.count_motorizados_enmotorizado').html(this.fnSettings().fnRecordsDisplay());
        }
      });

      $('#modal-envio-recojo').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var grupopedido = button.data('grupopedido')
        var codigos = button.data('codigos')

        $(".textcode").html(codigos);
        $("#hiddenIdGrupoPedido").val(grupopedido);
      });

      $(document).on("submit", "#modal-envio-recojo", function (evento) {
        evento.preventDefault();

        var data = new FormData();
        data.append('hiddenIdGrupoPedido', $("#hiddenIdGrupoPedido").val());

        $.ajax({
          data: data,
          processData: false,
          contentType: false,
          type: 'POST',
          url: "{{ route('envios.confirmar-recepcion-recojo') }}",
          success: function (data) {
            console.log(data);
            $("#modal-envio-recojo .textcode").text('');
            $("#modal-envio-recojo").modal("hide");
            Swal.fire('Mensaje', data.mensaje, 'success')
            $('#tablaRecojo').DataTable().ajax.reload();
          }
        });
      });

      datatablerecojo.on('responsive-display', function (e, datatable, row, showHide, update) {
        console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
        if (showHide) {
          renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
        }
      });

      $('#modal_recojomotorizado').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        $("#input_recojomotorizado").val(button.data('direccion_grupo'));
      });

      /*$(document).on("submit", "#form_recojo_enviarope", function (evento) {
        evento.preventDefault();
        var drecojoenviarope = new FormData();
        drecojoenviarope.append('input_recojoenviarope', $('#input_recojoenviarope').val());
        $.ajax({
          data: drecojoenviarope,
          processData: false,
          contentType: false,
          type: 'POST',
          url: "{{ route('courier.recojoenviarope') }}",
          success: function (data) {
            $("#modal_recojoenviarope").modal("hide");
            $('#tablaPrincipal').DataTable().ajax.reload();
          }
        });

      });*/
    });
  </script>

@stop
