@extends('adminlte::page')

@section('title', 'Lista de pedidos pagados')

@section('content_header')
  <h1>Lista de pedidos por atender - OPERACIONES
    {{-- @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosporatenderExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos por atender', 'key' => '7'])
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
      <table cellspacing="5" cellpadding="5">
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
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="vertical-align: middle">Item</th>
            <th scope="col" style="vertical-align: middle">Código</th>
            <th scope="col" style="vertical-align: middle">Razón social</th>
            <th scope="col" style="vertical-align: middle">Asesor</th>
            <th scope="col" style="vertical-align: middle">Fecha de registro</th>
            <th scope="col" style="vertical-align: middle">Adjuntos</th>
            <th scope="col" style="vertical-align: middle">Estado</th>
            <th scope="col" style="vertical-align: middle">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pedidos as $pedido)
            <tr>
              @if ($pedido->id < 10)
                <td>PED000{{ $pedido->id }}</td>
              @elseif($pedido->id < 100)
                <td>PED00{{ $pedido->id }}</td>
              @elseif($pedido->id < 1000)
                <td>PED0{{ $pedido->id }}</td>
              @else
                <td>PED{{ $pedido->id }}</td>
              @endif
              <td>{{ $pedido->codigos }}</td>
              <td>{{ $pedido->empresas }}</td>
              <td>{{ $pedido->users }}</td>
              <td>{{ $pedido->fecha }}</td>
              <td style="text-align: center">
                <a href="" data-target="#modal-veradjunto-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>
              </td>
              <td>{{ $pedido->condicion }}</td>
              <td>
                @can('operacion.atender')
                  <a href="" data-target="#modal-atender-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-success btn-sm">Atender</button></a>
                @endcan
                @can('operacion.PDF')
                  <a href="{{ route('pedidosPDF', $pedido) }}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                @endcan
              </td>
            </tr>
            @include('pedidos.modal')
            @include('pedidos.modal.atender')
            @include('pedidos.modal.veradjunto')
          @endforeach
        </tbody>
      </table>
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
        ajax: "{{ route('operaciones.poratendertabla') }}",
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
          {data: 'fecha', name: 'fecha', },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'20%',
            render: function ( data, type, row, meta ) {
              data = data+'<a href="" data-target="#modal-veradjunto-'+row.id+'" data-toggle="modal" ><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';

              @include('pedidos.modal')
              @include('pedidos.modal.atender')
              @include('pedidos.modal.veradjunto')

              return data;
            }
          },
          {data: 'condicion', name: 'condicion', },
          {
            data: 'action2',
            name: 'action2',
            orderable: false,
            searchable: false,
            sWidth:'20%',
            render: function ( data, type, row, meta ) {

              var urlpdf = '{{ route("pedidosPDF", ":id") }}';
              urlpdf = urlpdf.replace(':id', row.id);

              @can('operacion.atender')
                data = data+'<a href="" data-target="#modal-atender-'+row.id+'" data-toggle="modal" ><button class="btn btn-success btn-sm">Atender</button></a>';
                //<a href="" data-target="#modal-atender-{{ $pedido->id }}" data-toggle="modal"><button class="btn btn-success btn-sm">Atender</button></a>
              @endcan
              @can('operacion.PDF')
                data = data+'<a href="'+urlpdf+'" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                <a href="{{ route('pedidosPDF', $pedido) }}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
              @endcan

              return data;

            }
          },
        ]
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

  <script>
    //VALIDAR CAMPOS ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario);
    });

    function validarFormulario(evento) {
      evento.preventDefault();
      var adjunto = document.getElementById('adjunto').value;
      var cant_compro = document.getElementById('cant_compro').value;

      if (adjunto == '') {
          Swal.fire(
            'Error',
            'Debe registrar almenos un documento adjunto',
            'warning'
          )
        }
        else if (cant_compro == '0'){
          Swal.fire(
            'Error',
            'Cantidad de comprobantes enviados debe ser diferente de 0 (cero)',
            'warning'
          )
        }
        else if (cant_compro == '') {
          Swal.fire(
            'Error',
            'Ingrese cantidad de comprobantes enviados',
            'warning'
          )
        }
        else {
          this.submit();
        }
    }
  </script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
  window.onload = function () {
    $('#tablaPrincipal').DataTable().draw();
  }
</script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker("getDate");
                    var max = $('#max').datepicker("getDate");
                    // need to change str order before making  date obect since it uses a new Date("mm/dd/yyyy") format for short date.
                    var d = data[4].split("/");
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
