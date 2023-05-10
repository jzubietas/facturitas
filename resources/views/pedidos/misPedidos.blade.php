@extends('adminlte::page')

@section('title', 'Pedidos | Mis pedidos')

@section('content_header')

  <h1>Lista de mis pedidos
    @can('pedidos.create')
      <a href="{{ route('pedidos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan
    {{-- @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('mispedidosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
      </div>
    </div>
    @endcan --}}
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @include('pedidos.modal.exportar', ['title' => 'Exportar Lista de mis pedidos', 'key' => '4'])
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

  <div class="card">
    <div class="card-body">
      <!--<table cellspacing="5" cellpadding="5" class="table-responsive">
        <tbody>
          <tr>
            <td>Fecha Minima:</td>
            <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
            <td> </td>
            <td>Fecha Máxima:</td>
            <td><input type="text" value={{ $dateMax }} id="max" name="max"  class="form-control"></td>
          </tr>
        </tbody>
      </table><br>-->
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Item</th>
            <th scope="col" style="vertical-align: middle">Código</th>
            <th scope="col" style="vertical-align: middle">Cliente</th>
            <th scope="col" style="vertical-align: middle">Razón social</th>
            <th scope="col" style="vertical-align: middle">Cantidad</th>
            <th scope="col" style="vertical-align: middle">Asesor</th>
            <th scope="col" style="vertical-align: middle">RUC</th>
            <th scope="col" style="vertical-align: middle">Fecha de registro</th>
            <th scope="col" style="vertical-align: middle">Total (S/)</th>
            <th scope="col" style="vertical-align: middle">Estado de pago</th>
            <th scope="col" style="vertical-align: middle">Estado de envío</th>
            <th scope="col" style="vertical-align: middle">Diferencia</th>
            <th scope="col" style="vertical-align: middle">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

@stop

@section('css')
  {{-- <link rel="stylesheet" href="../css/admin_custom.css"> --}}
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  <style>
    .yellow {
      /*background-color: yellow !important;*/
      color:#fcd00e !important;
    }
    .red {
      background-color: red !important;
    }

    .white {
      background-color: white !important;
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
  <!--<script src="{{ asset('js/datatables.js') }}"></script>-->
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script>
  $(document).ready(function () {

      $(function () {
          $('[data-toggle="tooltip"]').tooltip()
      })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#tablaPrincipal').DataTable({
        processing: true,
        stateSave:true,
		serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('mispedidostabla') }}",
        "createdRow": function( row, data, dataIndex){
            /*if(data["estado"] == "1")
            {
            }else{
              $(row).addClass('yellow');
            } */
        },
        rowCallback: function (row, data, index) {
              var pedidodiferencia=data.diferencia;
              //pedidodiferencia=0;
            if(data.pendiente_anulacion==1){
                $('td',row).css('background', 'red').css('font-weight','bold');
            }
              if(pedidodiferencia==null){
                $('td:eq(11)', row).css('background', '#ca3a3a').css('color','#ffffff').css('text-align','center').css('font-weight','bold');
              }else{
                if(pedidodiferencia>3){
                  $('td:eq(11)', row).css('background', '#ca3a3a').css('color','#ffffff').css('text-align','center').css('font-weight','bold');
                }else{
                  $('td:eq(11)', row).css('background', '#44c24b').css('text-align','center').css('font-weight','bold');
                }
              }

        },
        initComplete:function(settings,json){

        },
        columns: [
        {//15 columnas
            data: 'id',
            name: 'id',
            render: function ( data, type, row, meta ) {
              if(row.id<10){
                return 'PED000'+row.id;
              }else if(row.id<100){
                return 'PED00'+row.id;
              }else if(row.id<1000){
                return 'PED0'+row.id;
              }else{
                return 'PED'+row.id;
              }
            }
        },
        {
            data: 'codigos',
            name: 'codigos',
            render: function ( data, type, row, meta ) {
                return '<b>' + row.codigos + '</b>'
            }
        },
        {
            data: 'celulares',
            name: 'celulares',
            render: function ( data, type, row, meta ) {
              if(row.icelulares!=null)
              {
                return row.celulares+'-'+row.icelulares+' - '+row.nombres;

              }else{
                return row.celulares+' - '+row.nombres;
              }

            },
            //searchable: true
        },
        {data: 'empresas', name: 'empresas', },
        {data: 'cantidad', name: 'cantidad', render: $.fn.dataTable.render.number(',', '.', 2, ''),},
        {data: 'users', name: 'users', },
            {data: 'ruc', name: 'ruc', },
        {data: 'fecha', name: 'fecha', },
        {
          data: 'total',
          name: 'total',
          render: $.fn.dataTable.render.number(',', '.', 2, '')
        },

        {
          data: 'condicion_pa',
          name: 'condicion_pa',
          render: function ( data, type, row, meta ) {
            if(row.condicion_pa==null){
              return 'SIN PAGO REGISTRADO';
            }else{
              if(row.condicion_pa=="0"){
                return 'SIN PAGO REGISTRADO';
              }else if(row.condicion_pa=="1"){
                return 'ADELANTO';
              }
              else if(row.condicion_pa=="2"){
                return 'PAGO';
              }
              return data;
            }
          }
        },//estado de pago
        /*{
          //estado del sobre
          data: 'envio',
          name: 'envio',
          render: function ( data, type, row, meta ) {
            if(row.envio==null){
              return '';
            }else{
              if(row.envio=='1'){
                return '<span class="badge badge-success">Enviado</span><br>'+
                        '<span class="badge badge-warning">Por confirmar recepcion</span>';
              }else if(row.envio=='2'){
                return '<span class="badge badge-success">Enviado</span><br>'+
                        '<span class="badge badge-info">Recibido</span>';
              }else{
                return '<span class="badge badge-danger">Pendiente</span>';
              }

            }
          }
        },*/
        //{data: 'responsable', name: 'responsable', },//estado de envio

        //{data: 'condicion_pa', name: 'condicion_pa', },//ss
        {   data: 'condicion_env',
            name: 'condicion_envio',
            render: function ( data, type, row, meta ) {

                if(row.condicion_env=='ANULADO'){
                    return '<span class="badge badge-info">ANULADO</span>';
                }else if(row.condicion_env == 0){
                    return     '<span class="badge badge-info">ANULADO</span>';
                }else if(row.condicion_env == 1){
                    return     '<span class="badge badge-info">PENDIENTE DE ENVIO</span>';
                }else if(row.condicion_env == 2){
                    return     '<span class="badge badge-info">EN REPARTO</span>';
                }else if(row.condicion_env == 3){
                    return     '<span class="badge badge-info">ENTREGADO</span>';
                }else{
                    return  '<span class="badge badge-info">'+data+'</span>' ;
                }
            }
        },//
        /*{
          data: 'envio',
          name: 'envio',
          render: function ( data, type, row, meta ) {
              if(row.envio==1){
                return '<span class="badge badge-success">ENVIADO</span>';
              }else{
                return '<span class="badge badge-danger">NO ENVIADO</span>';
              }
            }
        },*/
        {
          data: 'diferencia',
          name: 'diferencia',
          render: function ( data, type, row, meta ) {
            if(row.diferencia==null){
              return 'NO REGISTRA PAGO';
            }else{
              if(row.diferencia>0){
                return row.diferencia;
              }else{
                return row.diferencia;
              }
            }
          }
        },
        //{data: 'responsable', name: 'responsable', },
        {data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},
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
    $('#tablaPrincipal_filter label input').on('paste', function(e) {
      var pasteData = e.originalEvent.clipboardData.getData('text')
      localStorage.setItem("search_tabla",pasteData);
    });
    $(document).on("keypress",'#tablaPrincipal_filter label input',function(){
      localStorage.setItem("search_tabla",$(this).val());
      console.log( "search_tabla es "+localStorage.getItem("search_tabla") );
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
    /*window.onload = function () {
      $('#tablaPrincipal').DataTable().draw();
    }*/
  </script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");
                    // need to change str order before making  date obect since it uses a new Date("mm/dd/yyyy") format for short date.
                    var d = data[5].split("/");
                    var startDate = new Date(d[1]+ "/" +  d[0] +"/" + d[2]);

                    if (min == null && max == null) { return true; }
                    if (min == null && startDate <= max) { return true;}
                    if(max == null && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );


            $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"});
            $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy" });
            var table = $('#tablaPrincipal').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });
  </script>

@stop
