@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
  <h1>Lista de pedidos por enviar - ENVIOS
    {{-- <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosporenviarExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
      </div>
    </div> --}}
    {{-- @can('clientes.exportar') --}}
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos POR ENVIAR', 'key' => '1'])
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
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Asesor</th>
            <th scope="col">Cliente</th>
            <th scope="col">Fecha de Envio</th>
            <th scope="col">Razón social</th>
            <th scope="col">Destino</th>
            <th scope="col">Dirección de envío</th>
            <th scope="col">Referencia</th>
            <th scope="col">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('envios.modal.enviarid')
      @include('pedidos.modal.recibirid')
      {{--@include('sobres.modal.direccionid')--}}
      @include('pedidos.modal.verdireccionid')
      @include('pedidos.modal.editdireccionid')
      @include('pedidos.modal.destinoid')
      @include('envios.modal.distribuir')
        @include('operaciones.modal.confirmacion')
    </div>
  </div>

@stop

@section('css')

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

  <script>



    $(document).ready(function () {

    $('#modal-confirmacion').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('ide')
        var codigos = button.data('codigos')

        $('.titulo-confirmacion').html("Enviar sobre a Motorizado");

        $("#hiddenCodigo").val(idunico)
        $("#modal-confirmacion .textcode").html(codigos);
    });

    $(document).on("submit", "#formulario_confirmacion", function (evento) {
        evento.preventDefault();
        //validacion

        var fd2 = new FormData();
        fd2.append('hiddenCodigo', $('#hiddenCodigo').val() );
        $.ajax({
            data: fd2,
            processData: false,
            contentType: false,
            type: 'POST',
            url:"{{ route('operaciones.confirmar') }}",
            success:function(data){
                $("#modal-confirmacion").modal("hide");
                $('#tablaPrincipal').DataTable().ajax.reload();

            }
        });
    });



      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });



      $(document).on("submit", "#formulario", function (evento) {
        evento.preventDefault();
        var fd = new FormData();
      });

      $('#modal-enviar').on('show.bs.modal', function (event) {
        //cuando abre el form de anular pedido
        var button = $(event.relatedTarget)
        var idunico = button.data('enviar')//pedido
        var destino = button.data('destino')//pedido
        var dfecha = button.data('fechaenvio')//pedido

        var newOption = $('<option value="REGISTRADO">REGISTRADO</option>');
        var newOption2 = $('<option value="NO ENTREGADO">NO ENTREGADO</option>');
        var newOption3 = $('<option value="ENTREGADO">ENTREGADO</option>');

        var newOption4 = $('<option value="EN CAMINO">EN CAMINO</option>');
        var newOption5 = $('<option value="EN TIENDA/AGENTE">EN TIENDA/AGENTE</option>');
        console.log(dfecha)
        $('#condicion').empty().append(newOption3);
        $("#fecha_envio_doc_fis").val(dfecha);

       // if (destino=='LIMA')
       // $('#condicion').empty().append(newOption).append(newOption2).append(newOption3);
      //  else
      //  $('#condicion').empty().append(newOption).append(newOption2).append(newOption4).append(newOption5).append(newOption3);

        console.log(destino);

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



      $(document).on("change","#foto1",function(event){
          console.log("cambe image")
          var file = event.target.files[0];
          var reader = new FileReader();
          reader.onload = (event) => {
            //$("#picture").attr("src",event.target.result);
              document.getElementById("picture1").setAttribute('src', event.target.result);
          };
          reader.readAsDataURL(file);

      });

      $(document).on("change","#foto2",function(event){
          console.log("cambe image")
          var file = event.target.files[0];
          var reader = new FileReader();
          reader.onload = (event) => {
            //$("#picture").attr("src",event.target.result);
              document.getElementById("picture2").setAttribute('src', event.target.result);
          };
          reader.readAsDataURL(file);

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
        console.log("form enviarid")
        //validacion

        var fd2 = new FormData();
        let files=$('input[name="pimagen')
        var fileitem=$("#DPitem").val();

        fd2.append('hiddenEnviar', $('#hiddenEnviar').val() );
        fd2.append('fecha_envio_doc_fis', $('#fecha_envio_doc_fis').val() );
        fd2.append('fecha_recepcion', $('#fecha_recepcion').val() );
        fd2.append('foto1', $('input[type=file][id="foto1"]')[0].files[0]);
        fd2.append('foto2', $('input[type=file][id="foto2"]')[0].files[0]);
        fd2.append('condicion', $('#condicion').val() );

        $.ajax({
          data: fd2,
          processData: false,
          contentType: false,
          type: 'POST',
          url:"{{ route('envios.enviarid') }}",
          success:function(data){
            $("#modal-enviar").modal("hide");
            $('#tablaPrincipal').DataTable().ajax.reload();

          }
        });


      });



      $(document).on("submit", "#formulariorecibir", function (evento) {
        evento.preventDefault();
      });


      /*$('#modal-atender').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('atender')
        $(".textcode").html("PED"+idunico);
        $("#hiddenAtender").val(idunico);
      });*/

      $('#tablaPrincipal').DataTable({
        processing: true,
        stateSave:true,
		serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('envios.enrepartotabla') }}",
        createdRow: function( row, data, dataIndex){
          //console.log(row);
        },
        rowCallback: function (row, data, index) {

              if(data.destino2=='PROVINCIA'){
                $('td', row).css('color','red')

              }else if(data.destino2=='LIMA'){
                if(data.distribucion!=null)
                {
                  if(data.distribucion=='NORTE')
                  {
                    //$('td', row).css('color','blue')
                  }else if(data.distribucion=='CENTRO')
                  {
                    //$('td', row).css('color','yellow')
                  }else if(data.distribucion=='SUR')
                  {
                    //$('td', row).css('color','green')
                  }

                }else{

                }



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
            },
          },
          {data: 'identificador', name: 'identificador', },
          {
            data: 'celular',
            name: 'celular',
            render: function ( data, type, row, meta ) {
              return row.celular+'<br>'+row.nombre
            },
          },
          {
            data: 'fecha',
            name: 'fecha',
            render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' )
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
          {data: 'destino', name: 'destino', },
          {
            data:'direccion',
            name:'direccion',
            render: function ( data, type, row, meta ) {
              //console.log(data);
              datas='';
              if(data!=null)
              {
                return data;
                /*if(data=='0')
                {
                  return '<span class="badge badge-danger">REGISTRE DIRECCION</span>';
                }else if(data=='LIMA')
                {
                  var urlshow = '{{ route("pedidos.show", ":id") }}';
                  urlshow = urlshow.replace(':id', row.id);

                  return '<a href="" data-target="#modal-verdireccion" data-toggle="modal" data-dirreccion="'+row.id+'"><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                }
                else if(data=='PROVINCIA')
                {
                  return '<span class="badge badge-info">ENVIO A PROVINCIA</span>';
                }else{
                  return '<span class="badge badge-info">PROBLEMAS CON REGISTRO DE DESTINO</span>';
                }*/

                //return datas;

              }else{
                return '<span class="badge badge-info">REGISTRE DIRECCION</span>';
              }
              return '';
            },
          },
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
              data: 'condicion_envio', name: 'condicion_envio',
              render: function (data, type, row, meta) {
                  if (row.pendiente_anulacion == 1) {
                      return '<span class="badge badge-success">' + '{{\App\Models\Pedido::PENDIENTE_ANULACION }}' + '</span>';
                  }
                  var badge_estado=''
                  /*if (true) {
                      badge_estado += '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding:6px;">Direccion agregada</span>';
                  }*/
                  badge_estado+='<span class="badge badge-success" style="background-color: '+row.condicion_envio_color+'!important;">'+row.condicion_envio+'</span>';
                  return badge_estado;
              }
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'10%',
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


            $("#destino", this).on( 'keyup change', function () {
              if ( table.column(i).search() !== this.value ) {
                  table
                      .column(8)
                      .search( this.value )
                      .draw();
                }
            } );

        });
  </script>

@stop
