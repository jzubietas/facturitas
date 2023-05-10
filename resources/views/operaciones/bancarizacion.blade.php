@extends('adminlte::page')

@section('title', 'Operaciones | Sobres terminados')

@section('content_header')
  <h1>BANCARIZACIÓN - OPERACIONES
    {{-- @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosatendidosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos entregados', 'key' => '10'])
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

      <table  style="display:none;" cellspacing="5" cellpadding="5">
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
      <table id="tablaPrincipal" class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Item</th>
            <th scope="col" style="vertical-align: middle">Código</th>
            <th scope="col" style="vertical-align: middle">Razón social</th>
            <th scope="col" style="vertical-align: middle">Asesor</th>
            <th scope="col" style="vertical-align: middle">Fecha de registro</th>
            <th scope="col" style="vertical-align: middle">Destino</th>
            <th scope="col" style="vertical-align: middle">Estado</th>
            <th scope="col" style="vertical-align: middle">Atendido por</th>
            <th scope="col" style="vertical-align: middle">Jefe</th>
            <th scope="col" style="vertical-align: middle">Estado de sobre</th>
            <th scope="col" style="vertical-align: middle">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('pedidos.modal.confirmarecepcion')
      @include('pedidos.modal.revertirporenviar')
    </div>
  </div>

@stop

@section('css')
  {{-- <link rel="stylesheet" href="../css/admin_custom.css"> --}}
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  <style>
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
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

  <script>
    $(document).ready(function () {
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#modal-envio').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('envio')
        $(".textcode").html("PED"+idunico);
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
            $('#tablaPrincipal').DataTable().ajax.reload();
           }
        });
      });

      $('#modal-revertir').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('revertir')
        $(".textcode").html("PED"+idunico);
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
            $('#tablaPrincipal').DataTable().ajax.reload();
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

      $('#tablaPrincipal').DataTable({
        processing: true,
        stateSave:true,
		serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: {
          url: "{{ route('operaciones.bancarizaciontabla') }}",
          data: function (d) {
            //d.asesores = $("#asesores_pago").val();
            d.min = $("#min").val();
            d.max = $("#max").val();

          },
        },
        createdRow: function( row, data, dataIndex){
          //console.log(row);
        },
        rowCallback: function (row, data, index) {
        },
        initComplete:function(settings,json){

        },
        columns: [
          {
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
          {data: 'codigos', name: 'codigos', },
          {data: 'empresas', name: 'empresas', },
          {data: 'users', name: 'users', },
          {
            data: 'fecha',
            name: 'fecha',
            render:$.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss' ),
            "visible":true,
          },
          {data: 'destino', name: 'destino',"visible":false },
          {
              data: 'condicion',
              name: 'condicion',
              render: function ( data, type, row, meta ) {
                  if(row.condicion =='ANULADO'){
                      return '<span class="badge badge-info">ANULADO</span>';
                  }else if(row.condicion == 0){
                      return  '<span class="badge badge-info">ANULADO</span>';
                  }else if(row.condicion == 1){
                      return '<span class="badge badge-info">PENDIENTE DE ENVIO</span>';
                  }else if(row.condicion == 2){
                      return '<span class="badge badge-info">EN REPARTO</span>';
                  }else if(row.condicion == 3){
                      return '<span class="badge badge-info">ENTREGADO</span>';
                  }else{
                      return '<span class="badge badge-info">'+data+'</span>' ;
                  }
              }
          },
          {data: 'atendido_por', name: 'atendido_por', },
          {data: 'jefe', name: 'jefe', },
          {
              data: 'envio',
              name: 'envio',
              render: function ( data, type, row, meta ) {
                if(row.envio==1){
                  return '<span class="badge badge-success">Enviado</span>'+
                        '<span class="badge badge-warning">Por confirmar recepcion</span>';
                }else if(row.envio==2){
                  return '<span class="badge badge-success">Enviado</span>'+
                          '<span class="badge badge-info">Recibido</span>';
                }else if(row.envio==3){
                  return '<span class="badge badge-dark">Sin envio</span>';
                }else{
                  return '<span class="badge badge-danger">por enviar</span>';
                }
              }, "visible":false
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'20%',
            render: function ( data, type, row, meta ) {

              var urlver = '{{ route("operaciones.showatender", ":id") }}';
              urlver = urlver.replace(':id', row.id);

                data = '<div><ul class="" aria-labelledby="dropdownMenuButton">';

              data = data+'<a href="'+urlver+'" class="btn-sm dropdown-item"><i class="fas fa-eye text-success"></i> Ver</a>';

              var urlpdf = '{{ route("pedidosPDF", ":id") }}';
              urlpdf = urlpdf.replace(':id', row.id);
              @can('operacion.PDF')
                data = data+'<a href="'+urlpdf+'" class="btn-sm dropdown-item" target="_blank"><i class="fa fa-file-pdf text-primary"></i> PDF</a>';
                data = data+'<a href="" class="btn-sm dropdown-item" data-target="#modal-envio" data-envio='+row.id+' data-toggle="modal" ><i class="fa fa-check text-warning" aria-hidden="true"></i> Jefe OP</a>';
              @endcan

              @can('operacion.enviar')
                if (row.envio == '0')
                {
                  @if (Auth::user()->rol == "Jefe de operaciones" || Auth::user()->rol == "Administrador")

                    data = data+'<a href="" class="btn-sm dropdown-item" data-target="#modal-envio" data-envio='+row.id+' data-toggle="modal" >Enviar</a>';
                    data = data+'<a href="" class="btn-sm dropdown-item" data-target="#modal-sinenvio" data-sinenvio='+row.id+' data-toggle="modal" >Sin envío</a>';
                  @endif

                }
              @endcan

              if(row.envio=='3' || row.envio=='1')
              {
                data = data+'<a href="" class="btn-sm dropdown-item" data-target="#modal-revertir" data-revertir='+row.id+' data-toggle="modal" ><i class="fa fa-undo text-danger" aria-hidden="true"></i> Revertir</a>';
              }
                data = data+'</ul></div>';
              return data;
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
      $('#tablaPrincipal').DataTable().draw();
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
                $('#tablaPrincipal').DataTable().ajax.reload();
                //localStorage.setItem('dateMin', $(this).val() );
              }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"
            });

            $("#max").datepicker({
              onSelect: function () {
                $('#tablaPrincipal').DataTable().ajax.reload();
                //localStorage.setItem('dateMax', $(this).val() );
              }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy"
            });

            //$("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"});
            //$("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true, dateFormat:"dd/mm/yy" });
            //var table = $('#tablaPrincipal').DataTable();

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
