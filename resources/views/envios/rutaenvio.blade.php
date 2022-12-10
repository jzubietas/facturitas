@extends('adminlte::page')modal

@section('title', 'Rutas de Envio')

@section('content_header')
  <h1>Rutas de envio - ENVIOS

    <div class="float-right btn-group dropleft">

      <?php if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Logística'){ ?>
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <?php } ?>


      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>

        {{--<a href="" data-target="#modal-exportar2" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Pedidos</a>

        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Provincia</a>
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Lima Norte</a>
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Lima Centro</a>
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Lima Sur</a>--}}
      </div>
    </div>
    @include('sobres.modal.exportar', ['title' => 'Exportar RUTAS DE ENVIAR', 'key' => '1'])
    {{--@include('sobres.modal.exportar', ['title' => 'Exportar RUTAS DE ENVIAR PROVINCIA', 'key' => '2'])
    @include('sobres.modal.exportar', ['title' => 'Exportar RUTAS DE ENVIAR LIMA NORTE', 'key' => '3'])
    @include('sobres.modal.exportar', ['title' => 'Exportar RUTAS DE ENVIAR LIMA CENTRO', 'key' => '4'])
    @include('sobres.modal.exportar', ['title' => 'Exportar RUTAS DE ENVIAR LIMA SUR', 'key' => '5'])--}}

    {{-- @endcan --}}
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
            <td>Fecha</td>
            <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
            <td></td>
            <td>Buscar General</td>
            <td>
              <div class="form-group col-lg-12">
                {!! Form::label('general', 'Buscador General') !!}
                  <input type="text" name="general" id="general" class="form-control" placeholder="Busqueda General..." >
              </div>

            </td>

          </tr>
        </tbody>
      </table><br>
      {{-- <table cellspacing="5" cellpadding="5">
        <tbody>
          <tr>
            <td>Destino:</td>
            <td>
              <select name="destino" id="destino" class="form-control">
                <option value="LIMA">LIMA</option>
                <option value="PROVINCIA">PROVINCIA</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table><br> --}}
      <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" class="text-center">Item</th>
            <th scope="col" class="text-center">Asesor</th>
            <th scope="col" class="text-center">Cliente</th>
            <th scope="col" class="text-center">Nombre</th>
            <th scope="col" class="text-center">Fecha</th>
            <th scope="col" class="text-center">QTY</th>
            <th scope="col" class="text-center">Codigos</th>
            <th scope="col" class="text-center">Producto</th>
            <th scope="col" class="text-center">Direccion</th>
            <th width="5px" scope="col" class="text-center">Referencia</th>
            <th scope="col" class="text-center">Observacion</th>
            <th scope="col" class="text-center">Distrito</th>
            <th scope="col" class="text-center">Destino</th>
            <th scope="col" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('envios.modal.enviarid')
      @include('pedidos.modal.recibirid')
      @include('pedidos.modal.verdireccionid')
      @include('pedidos.modal.editdireccionid')
      @include('pedidos.modal.destinoid')
      @include('envios.modal.distribuir')
      @include('envios.modal.desvincularpedidos')
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <style>
    img:hover{
      transform: scale(1.2)
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
  {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
  <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

  <script>
    var tabla_pedidos=null;
    $(document).ready(function () {

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      tabla_pedidos=$('#tablaPrincipalpedidosagregar').DataTable({
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

      $(document).on("submit", "#formulario", function (evento) {
        evento.preventDefault();
        var fd = new FormData();
      });

      $('#modal-distribuir').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('distribuir')
        console.log("distribuir "+ idunico);
        $("#modal-distribuir #hiddenDistribuir").val(idunico);
      });

      $(document).on("submit", "#formulariodistribuir", function (evento) {
        evento.preventDefault();
        console.log("form distribuir")
        //validacion

        var fd2 = new FormData();

        fd2.append('hiddenDistribuir', $('#hiddenDistribuir').val() );
        fd2.append('distribuir', $('#distribuir').val() );

        // modal para distribucion
        
        $.ajax({
          data: fd2,
          processData: false,
          contentType: false,
          type: 'POST',
          url:"{{ route('envios.distribuirid') }}",
          success:function(data){
            $("#modal-distribuir").modal("hide");
            $('#tablaPrincipal').DataTable().ajax.reload();

          }
        });


      });

      $('#modal-enviar').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('enviar')//pedido
        $("#hiddenEnviar").val(idunico)
        if(idunico<10){
          idunico='PED000'+idunico;
        }else if(idunico<100){
          idunico= 'PED00'+idunico;
        }else if(idunico<1000){
          idunico='PED0'+idunico;
        }else{
          idunico='PED'+idunico;
        }
        $("#modal-enviar .textcode").html(idunico);

      });

      $('#modal-recibir').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('recibir')//pedido
        $("#hiddenRecibir").val(idunico)
        if(idunico<10){
          idunico='PED000'+idunico;
        }else if(idunico<100){
          idunico= 'PED00'+idunico;
        }else if(idunico<1000){
          idunico='PED0'+idunico;
        }else{
          idunico='PED'+idunico;
        }
        $("#modal-recibir .textcode").html(idunico);


      });

      $(document).on("submit", "#formularioenviar", function (evento) {
        evento.preventDefault();
      });

      $(document).on("submit", "#formulariorecibir", function (evento) {
        evento.preventDefault();
        var formData=$("#formulariorecibir").serialize();

        $.ajax({
            type:'POST',
            url:"{{ route('envios.recibirid') }}",
            data:formData,
        }).done(function (data) {
            if(data.html!=0)
            {
                $("#modal-recibir").modal("hide");
                $('#tablaPrincipal').DataTable().ajax.reload();
            }else{

            }
            /*
            //resetearcamposdelete();
             */
        });

      });


      $('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: {
          url: "{{ route('envios.rutaenviotabla') }}",
          data: function (d) {
            //d.asesores = $("#asesores_pago").val();
            d.desde = $("#min").val();
            d.general = $("#general").val();
          },
        },
        rowCallback: function (row, data, index) {
            console.log(data.destino2)
              if(data.destino2=='PROVINCIA'){
                $('td', row).css('color','red')

              }else if(data.destino2=='LIMA'){
                if(data.distribucion!=null)
                {
                  if(data.distribucion=='NORTE')

                  {
                    //$('td', row).css('color','blue')
                    $('td:eq(9)', row).css('background', '#A5F1E9');
                  }else if(data.distribucion=='CENTRO')
                  {
                    //$('td', row).css('color','yellow')
                    $('td:eq(9)', row).css('background', '#F0FF42');
                  }else if(data.distribucion=='SUR')
                  {
                    //$('td', row).css('color','green')
                    $('td:eq(9)', row).css('background', '#B6E2A1');
                  }

                }else{

                }



              }

        },
        columns: [
          {
              data: 'id',
              name: 'id',"visible":false,
              render: function ( data, type, row, meta ) {
                if(row.id<10){
                  return 'ENV000'+row.id;
                }else if(row.id<100){
                  return 'ENV00'+row.id;
                }else if(row.id<1000){
                  return 'ENV0'+row.id;
                }else{
                  return 'ENV'+row.id;
                }
              }
          },
          {data: 'identificador', name: 'identificador',sWidth:'5%' },
          {
            data: 'celular',
            name: 'celular',
            render: function ( data, type, row, meta ) {
              return row.celular+' - '+row.nombre
            },
          },
          {
            data: 'nombre',
            name: 'nombre',
            "visible":false,
            render: function ( data, type, row, meta ) {
              return data;
            },
          },
          {data: 'fecha', name: 'fecha',sWidth:'5%' },
        {data: 'cantidad', name: 'cantidad',sWidth:'5%' },
          {
            data: 'codigos',
            name: 'codigos',
            render: function ( data, type, row, meta ) {
              if(data==null){
                return 'SIN PEDIDOS';
              }else{
                var returndata='';
                var jsonArray=data.split(",");
                $.each(jsonArray, function(i, item) {
                    returndata+=item+'<br>';
                });
                return returndata;
              }
            }
          },
          {
            data: 'producto',
            name: 'producto',
            render: function ( data, type, row, meta ) {
              if(data==null){
                return 'SIN RUCS';
              }else{
                var numm=0;
                var returndata='';
                var jsonArray=data.split(",");
                $.each(jsonArray, function(i, item) {
                    numm++;
                    returndata+=numm+": "+item+'<br>';

                });
                return returndata;
              }
            }
           },
          {data: 'direccion', name: 'direccion', },
          {
            data: 'referencia',
            name: 'referencia',
            sWidth:'10%',
            render: function ( data, type, row, meta ) {
              var datal="";
              if(row.destino=='LIMA')
              {
                return data;

              }else if(row.destino=='PROVINCIA'){
                urladjunto = '{{ route("pedidos.descargargastos", ":id") }}';
                urladjunto = urladjunto.replace(':id', data);
                datal = datal+'<p><a href="'+urladjunto+'">'+data+'</a><p>';
                  return datal;
              }
            }
          },
          {
            data: 'observacion',
            name: 'observacion',
            sWidth:'10%',
          },
          {
            data: 'distrito',
            name: 'distrito',"visible":true,
            render: function ( data, type, row, meta ) {
              if(data!=null)
              {
                return data;
              }else{
                return '';
              }
            }
          },
          {
            data: 'destino2',
            name: 'destino2',"visible":false,
            render: function ( data, type, row, meta ) {
              if(data!=null)
              {
                return data;
              }else{
                return '';
              }
            }
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            "visible":true,
            //sWidth:'20%',
            render: function ( data, type, row, meta ) {
              datass="";

              //si es lima

              if ("{{$rol}}" =='Administrador' || "{{$rol}}" =='Logística')
              {
                if(row.destino=='LIMA')
                datass=datass+'<a href="#" data-target="#modal-distribuir" data-toggle="modal" data-distribuir="'+row.id+'">'+
                    '<button class="btn btn-warning btn-sm"><i class="fas fa-envelope"></i> Distribuir</button></a><br>';

                datass = datass+ '<a href="" data-target="#modal-revertir" data-toggle="modal" data-recibir="'+row.id+'"><button class="btn btn-info btn-sm"><i class="fas fa-trash"></i> REVERTIR</button></a>';
                datass = datass+ '<a href="" data-target="#modal-desvincular" data-toggle="modal" data-desvincular="'+row.id+'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> DESVINCULAR</button></a>';

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
      });


$(document).on("click","#desvincularConfirmar",function(event){

        var rows_selected = tabla_pedidos.column(0).checkboxes.selected();
        var $direcciongrupo = $("#direcciongrupo").val();
        var $observaciongrupo = $("#observaciongrupo").val();
        var pedidos=[];
        $.each(rows_selected, function(index, rowId){
              console.log("ID PEDIDO  es "+  rowId);
              pedidos.push(rowId);
          });


          var let_pedidos=pedidos.length;

        if(let_pedidos==0)
        {
          Swal.fire(
              'Error',
              'Debe elegir un pedido',
              'warning'
            )
            return;
        }
        $pedidos=pedidos.join(',');
        console.log($pedidos);
        console.log($direcciongrupo);
        console.log($observaciongrupo);
        var fd2=new FormData();
        let direcciongrupo=$("#direcciongrupo").val();
        let observaciongrupo=$("#observaciongrupo").val();
        fd2.append('direcciongrupo', direcciongrupo);
        fd2.append('observaciongrupo', observaciongrupo);
        /*fd2.append('observaciongrupo', $('#observaciongrupo').val() );*/
        fd2.append('pedidos', $pedidos);


        $.ajax({
          data: fd2,
          processData: false,
          contentType: false,
          type: 'POST',
          url:"{{ route('sobres.desvinculargrupo') }}",
          success:function(data)
          {
            console.log(data);
            $("#modal-desvincular").modal("hide");
            $("#tablaPrincipal").DataTable().ajax.reload();
          }
        });
      })


$('#modal-desvincular').on('show.bs.modal', function (event) {

var button = $(event.relatedTarget)
var direcciongrupo = button.data('desvincular');
$("#direcciongrupo").val(direcciongrupo);
//$("#observaciongrupo").val(observaciongrupo);
tabla_pedidos.destroy();


tabla_pedidos=$('#tablaPrincipalpedidosagregar').DataTable({
  responsive: true,
  "bPaginate": false,
  "bFilter": false,
  "bInfo": false,
  'ajax': {
    url:"{{ route('cargar.pedidosgrupotabla') }}",
    'data': { "direcciongrupo": direcciongrupo},
    "type": "get",
  },
  'columnDefs': [ {
    'targets': [0],
    'orderable': false,
  }],
  columns:[
    {
        "data": "pedido_id",
        'targets': [0],
        'checkboxes': {
            'selectRow': true
        },
        defaultContent: '',
        orderable: false,
    },
    {data: 'codigo_pedido', name: 'codigo_pedido',},
    {
        "data": 'empresa',
        "name": 'empresa',
        "render": function ( data, type, row, meta ) {
          return data;

        }
    },
  ],
  'select': {
      'style': 'multi',
      selector: 'td:first-child'
  },
});

//$("#limaprovincia").val("").trigger("change");

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

  <script>

    //VALIDAR CAMPO CELULAR
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

    //VALIDAR ANTES DE ENVIAR
    /*document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario);
    });*/

    function validarFormulario(evento) {
      evento.preventDefault();
      var condicion = document.getElementById('condicion').value;
      var foto1 = document.getElementById('foto1').value;
      var pfoto1 = document.getElementById('pfoto1').value;
      var foto2 = document.getElementById('foto2').value;
      var pfoto2 = document.getElementById('pfoto2').value;

      if (condicion == 3) {
        if (foto1 == '' && pfoto1 == '') {
          Swal.fire(
            'Error',
            'Para dar por ENTREGADO debe registrar la foto 1',
            'warning'
          )
        }
        else if (foto2 == '' && pfoto2 == ''){
          Swal.fire(
            'Error',
            'Para dar por ENTREGADO debe registrar la foto 2',
            'warning'
          )
        }
        else {
        this.submit();
        }
      }
      else {
        this.submit();
      }
    }
  </script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>
    /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {



          $(document).on("keyup","#general",function(){
            console.log($(this).val())
            if($(this).val()=='')
            {


            }else{
              $("#min").val("");
              //busca en general
              $('#tablaPrincipal').DataTable().ajax.reload();

            }

            $('#tablaPrincipal').DataTable().ajax.reload();
          });

          $("#min").datepicker({
            onSelect: function () {
              $('#tablaPrincipal').DataTable().ajax.reload();
              console.log("minimo "+$(this).val());
              //localStorage.setItem('dateMin', $(this).datepicker('getDate') );
              //localStorage.setItem('dateMin', $(this).val() );
            }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"
          });


            /*$("#destino", this).on( 'keyup change', function () {
              if ( table.column(i).search() !== this.value ) {
                  table
                      .column(8)
                      .search( this.value )
                      .draw();
                }
            } );*/

        });



  </script>
  <script>
    /*if (localStorage.getItem('dateMin') )
    {
      $( "#min" ).val(localStorage.getItem('dateMin')).trigger("change");
    }else{
      localStorage.setItem('dateMin', "{{$dateMin}}" );
    }*/

    //console.log(localStorage.getItem('dateMin'));

  </script>

@stop
