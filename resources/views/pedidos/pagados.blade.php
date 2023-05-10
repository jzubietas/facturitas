@extends('adminlte::page')

@section('title', 'Lista de pedidos pagados')

@section('content_header')
  <h1>Lista de pedidos pagados
    {{-- @can('pedidos.create')
      <a href="{{ route('pedidos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan --}}
    {{-- @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidospagadosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar Lista de pedidos pagados', 'key' => '5'])
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
      <table cellspacing="5" cellpadding="5" class="table-responsive">
        <tbody>
          <tr>
            <td>Fecha Minima:</td>
            <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
            <td> </td>
            <td>Fecha Máxima:</td>
            <td><input type="text" value={{ $dateMax }} id="max" name="max"  class="form-control"></td>
          </tr>
        </tbody>
      </table><br>
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Item</th>
            <th scope="col" style="vertical-align: middle">Código</th>
            <th scope="col" style="vertical-align: middle">Cliente</th>
            <th scope="col" style="vertical-align: middle">Razón social</th>
            <th scope="col" style="vertical-align: middle">Asesor</th>
            <th scope="col" style="vertical-align: middle">Fecha de registro</th>
            <th scope="col" style="vertical-align: middle">Total (S/)</th>
            <th scope="col" style="vertical-align: middle">Estado de pedido</th>
            <th scope="col" style="vertical-align: middle">Estado de pago</th>
            <th scope="col" style="vertical-align: middle">Administracion</th>
            <th scope="col" style="vertical-align: middle">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      {{--@include('pedidos.modal')--}}
      @include('pedidos.modalid')
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

  <script>
    $(document).ready(function () {

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
        ajax: "{{ route('pedidos.pagadostabla') }}",
        "createdRow": function( row, data, dataIndex){
            /*if(data["estado"] == "1")
            {
            }else{
              $(row).addClass('textred');
            }   */
        },
        rowCallback: function (row, data, index) {
              /*var pedidodiferencia=data.diferencia;
              if(pedidodiferencia==null){
                $('td:eq(12)', row).css('background', '#efb7b7').css('color','#ffffff').css('text-align','center').css('font-weight','bold');
              }else{
                if(pedidodiferencia>3){
                  $('td:eq(12)', row).css('background', '#efb7b7').css('color','#ffffff').css('text-align','center').css('font-weight','bold');
                }else{
                  $('td:eq(12)', row).css('background', '#afdfb2').css('text-align','center').css('font-weight','bold');
                }
              }*/
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
            },
            "visible":false,
        },
        {data: 'codigos', name: 'codigos', },
        {
            data: 'celulares',
            name: 'celulares',
            render: function ( data, type, row, meta ) {
              if(row.icelulares!=null)
              {
                return row.celulares+'-'+row.icelulares;
              }else{
                return row.celulares;
              }
            },
        },
        {data: 'empresas', name: 'empresas', },
        {data: 'users', name: 'users', },
        {
          data: 'fecha',
          name: 'fecha',
        },
        {
          data: 'total',
          name: 'total',
          render: $.fn.dataTable.render.number(',', '.', 2, '')
        },
        {
          data: 'condiciones',
          name: 'condiciones',
          render: function ( data, type, row, meta ) {
              return data;
          }
        },
        {
          data: 'condicion_pa',
          name: 'condicion_pa',
          render: function ( data, type, row, meta ) {
            if(row.condiciones=='ANULADO'){
                return 'ANULADO';
            }else{
              if(row.condicion_pa==null){
                return 'SIN PAGO REGISTRADO';
              }else{
                if(row.condicion_pa=='0'){
                  return '<p>SIN PAGO REGISTRADO</p>'
                }
                if(row.condicion_pa=='1'){
                  return '<p>ADELANTO</p>'
                }
                if(row.condicion_pa=='2'){
                  return '<p>PAGO</p>'
                }
                if(row.condicion_pa=='3'){
                  return '<p>ABONADO</p>'
                }
                //return data;
              }
            }

          }
        },
        {
          data: 'condiciones_aprobado',
          name: 'condiciones_aprobado',
          render: function ( data, type, row, meta ) {
            if(data!=null)
            {
              return data;
            }else{
              return 'SIN REVISAR';
            }

          }
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false,
          sWidth:'20%',
          render: function ( data, type, row, meta ) {
            var urlpdf = '{{ route("pedidosPDF", ":id") }}';
            urlpdf = urlpdf.replace(':id', row.id);
            var urlshow = '{{ route("pedidos.show", ":id") }}';
            urlshow = urlshow.replace(':id', row.id);
            var urledit = '{{ route("pedidos.edit", ":id") }}';
            urledit = urledit.replace(':id', row.id);

            @can('pedidos.pedidosPDF')
              data = data+'<a href="'+urlpdf+'" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a><br>';
            @endcan
            @can('pedidos.show')
              data = data+'<a href="'+urlshow+'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> VER</a><br>';
            @endcan
            @can('pedidos.edit')
              if(row.condicion_pa==0)
              {
                data = data+'<a href="'+urledit+'" class="btn btn-warning btn-sm"> Editar</a><br>';
              }
            @endcan
            @can('pedidos.destroy')
            if(row.estado==0)
            {
              data = data+'<a href="" data-target="#modal-restaurar" data-toggle="modal" data-restaurar="'+row.id+'" ><button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Restaurar</button></a><br>';
            }else{
              if(row.condicion_pa==0)
              {
                data = data+'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'+row.id+'" data-responsable="{{ $miidentificador }}"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>';
              }
            }

            @endcan

            return data;
          }
        },
      ],

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
