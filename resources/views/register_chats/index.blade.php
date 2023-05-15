@extends('adminlte::page')

@section('title', 'Registro de Chats')

@section('content_header')
  <h1 class="text-center">
    <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i> Registro De Chats
  </h1>

@stop

@section('content')

  <div class="card p-0">

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="enmotorizado" role="tabpanel" aria-labelledby="enmotorizado-tab">
        <table id="registerchatstable" class="table table-striped w-100">
          <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Fecha de Registro</th>
            <th scope="col" style="vertical-align: middle">Base Fria</th>
            <th scope="col" style="vertical-align: middle">Asesor</th>
            <th scope="col" style="vertical-align: middle">Chat</th>
            <th scope="col" style="vertical-align: middle">Accion</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

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

@endpush

@section('js')

  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

  <script>
    let datatable = null;

    $(document).ready(function () {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $(document).on('click','.btn_chat',function(event){
          var button = $(this);
          var basefria = button.data('basefria');
          console.log(basefria);

          var dfdata = new FormData();
          dfdata.append('basefria', basefria);
          $.ajax({
              data: dfdata,
              processData: false,
              contentType: false,
              type: 'POST',
              url: "{{ route('registro.chats.realizo.chat') }}",
              success: function (data) {

                  $('#registerchatstable').DataTable().ajax.reload();

                  window.open('https://api.whatsapp.com/send?phone=+51'+basefria, '_blank')
              }
          });

      });

        datatable = $('#registerchatstable').DataTable({
        //dom: '<"top"i>rt<"bottom"lp><"clear">',
        lengthChange: false,
        processing: true,
        stateSave: false,
        serverSide: true,
        searching: true,
        order: [ [0,'asc'], [4,'asc']],
        ajax: {
          url: "{{ route('registro.chats.index',['datatable'=>1]) }}",
          data: function (q) {
            q.fechaconsulta = $("#fecha_consulta").val();
            q.tab = 'enmotorizado'
          }
        },
        initComplete: function () {

        },
        createdRow: function (row, data, dataIndex) {

        },
        drawCallback: function (settings) {
        },
        rowCallback: function (row, data, index) {
        },
        columns: [
          {
            data: 'created_at',
            name: 'created_at',
          },
          {
            data: 'basefria',
            name: 'basefria',
          },
          {
            data: 'asesor',
            name: 'asesor',
          },
          {data:'chat',name:'chat',visible:false},
          {
            data: 'action',
            name: 'action',
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

    });
  </script>

@stop
