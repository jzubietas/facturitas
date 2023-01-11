@extends('adminlte::page')

@section('title', 'Lista de pedidos por confirmar')

@section('content_header')
    <div class="row border-bottom pb-16">
        <div class="col-lg-12">
            <h1 class="font-20 font-weight-bold">Sobres Devueltos</h1>
        </div>
    </div>

@stop

@section('content')
    <style>

        #placeholder-qr{
            animation: qr 1.5s ease-in-out infinite;
        }
        @keyframes  qr {
            0%{
                transform: translate(-50%, -50%) scale(0.7);
            }
            50%{
                transform: translate(-50%, -50%) scale(1);
            }
            100%{
                transform:  translate(-50%, -50%) scale(0.7);
            }

        }

        #btn-qr{
            margin-right: 16px;
            position: fixed;
            bottom: 16px;
            left: 50%;
            width: 300px;
            background-color: #3498db !important;
            color: white;
            text-shadow: 1px 2px 3px #00000063;
            transform: translate(-50%, 0px);
            border-radius: 12px;
            z-index: 999;
        }
        .activo{
            background-color: #e74c3c !important;
            color: white !important;
            border: 0 !important;
        }
        .content-wrapper{
            background-color: white;
        }
        .card{
            box-shadow: 0 0 white;
        }
    </style>
  <div class="card w-100 pb-48">
    <div class="card-body p-0">

        <div class="container-full">
            <div class="row">
                @foreach($motorizados as $motorizado)
                    <div class="col-lg-6 container-{{Str::slug($motorizado->zona)}}">
                        <div class="table-responsive">
                            <div class="card card-{{$color_zones[Str::upper($motorizado->zona)]??'info'}}">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <h5> MOTORIZADO {{Str::upper($motorizado->zona)}}</h5>
                                        <div>
                                            <h6>Sobres devueltos: <span>{{$motorizado->devueltos}}</span></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-1">
                                    <div>
                                        <table id="tablaPrincipal{{Str::upper($motorizado->zona)}}" class="table table-striped font-12">
                                            <thead>
                                            <tr>
                                                <th scope="col">Códigos</th>
                                                <th scope="col">Distrito</th>
                                                <th scope="col">Razón social</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

      @include('pedidos.modal.confirmar_recepcion_log'){{--confirmar recepcion es el recibido--}}}
      @include('envios.modal.enviarid')
      @include('pedidos.modal.recibirid')
      @include('pedidos.modal.verdireccionid')
      @include('pedidos.modal.editdireccionid')
      @include('pedidos.modal.destinoid')
    @include('pedidos.modal.escaneaqr')
    @include('operaciones.modal.confirmacion')

    </div>
  </div>

    <script>
        if (location.protocol != 'https:') {
            document.getElementById('secure-connection-message').style='display: block';
        }
    </script>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/css/admin_custom.css">


  <style>
.table_custom.toolbar {
    float: left;
}
      .qr_success{
          animation: qr_success 1s ease-in forwards;
      }


@keyframes qr_success{
    0%{
        box-shadow: 1px 1px 0px green;
    }

    70%{
        box-shadow: 1px 1px 24px green;
    }

    100%{

        box-shadow: 1px 1px 0px green;

    }
}

      .qrPreviewVideo{width:100%; width:100%; border-radius: 16px; margin:auto;}

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
  <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

  <script type="text/javascript" src="{{asset('js/jsqrscanner.nocache.js')}}"></script>

  <script type="text/javascript">

      const configDataTableZonas = {
          serverSide: true,
          searching: true,
          lengthChange: false,
          order: [[0, "desc"]],
          /*createdRow: function (row, data, dataIndex) {
          },*/
          columns: [
              {data: 'codigo', name: 'codigo',},
              {data: 'env_zona', name: 'env_zona',},
              {data: 'env_distrito', name: 'env_distrito',},
              {
                  data: 'action',
                  name: 'action',
                  orderable: false,
                  searchable: false,
                  sWidth: '20%'
              },
          ],
          language: {
              "decimal": "",
              "emptyTable": "No hay información",
              "info": "_START_ - _END_ / _TOTAL_",
              "infoEmpty": "0 Entradas",
              "infoFiltered": "(Filtrado de _MAX_ total entradas)",
              "infoPostFix": "",
              "thousands": ",",
              "lengthMenu": "Mostrar _MENU_ Entradas",
              "loadingRecords": "Cargando...",
              "processing": ``,
              "search": "Buscar:",
              "zeroRecords": "Sin resultados encontrados",
              "paginate": {
                  "first": "Primero",
                  "last": "Ultimo",
                  "next": "Siguiente",
                  "previous": "Anterior"
              }
          },
      }

      @foreach($motorizados as $motorizado)
      $('#tablaPrincipal{{Str::upper($motorizado->zona)}}').DataTable({
          ...configDataTableZonas,
          ajax: {
              url: "{{ route('envios.datasobresdevueltos',['datatable'=>'1']) }}",
              data: function (d) {
                  d.id={{$motorizado->id}}
              },
          },
          /*rowCallback: function (row, data, index) {

          }*/
      });
      @endforeach
  </script>

  <script>
    $(document).ready(function () {

      $("#fecha_consulta").datepicker({
        onSelect: function () {

          $('#tablaPrincipal').DataTable().ajax.reload();
          console.log("minimo "+$(this).val());
          //localStorage.setItem('dateMin', $(this).datepicker('getDate') );
          //localStorage.setItem('dateMin', $(this).val() );
        }, changeMonth: true, changeYear: true , dateFormat:"dd/mm/yy"
      });

        $('.condicion-tabla').on('click', function (){
            $('.condicion-tabla').removeClass("activo");
            $(this).addClass("activo");
            //var url = $(this).data("url");
            $('#tablaPrincipal').DataTable().ajax.reload();

            var $activeItem = $('.nav .active').html();
            console.log($activeItem);

            var id=$('.condicion-tabla.active').attr('id');
            console.log(id)//profile-tab   home-tab
            if($('.condicion-tabla.active').attr('id')=='home-tab')
            {
              $('div.toolbar').html('<div class="d-flex justify-content-center"><button id="iniciar-ruta-masiva" class="btn btn-success">Iniciar RUTA MASIVA</button></div>');
            }else{
              $('div.toolbar').html('');
            }
            //if ( ! $.fn.DataTable.isDataTable( '#tablaPrincipal' ) ) {
                
            //}

        });

        $(document).on("click","#iniciar-ruta-masiva",function(){
          //ajax iniciar ruta masiva

          $.ajax({
              data: {
                  /*envio_id:data.id,
                  pedido:data.codigos*/
              },
              type: 'POST',
              url: "{{ route('envios.recepcionmotorizado.iniciar_ruta_masiva') }}",
          }).always(function (data){
            console.log(data);
              $('#tablaPrincipal').DataTable().ajax.reload();
              /*if(data.html=='1')
              {
                $('#tablaPrincipal').DataTable().ajax.reload();
              }else{
                Swal.fire(
                    'Error',
                    'No tiene el rol suficiente para esta operacion',
                    'error'
                )
              }*/
              //self.close()
              //self.hideLoading(true)

          });

        })

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#modal-confirmacion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var idunico = button.data('ide')
            var codigos = button.data('codigos')

            $('.titulo-confirmacion').html("Enviar sobre a Motorizado");

            $("#hiddenCodigo").val(idunico)
            $("#modal-confirmacion .textcode").html(codigos);
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
           url:"{{ route('envios.recepcionarmotorizado') }}",
           success:function(data)
           {
            console.log(data);
            $("#modal-envio .textcode").text('');
            $("#modal-envio").modal("hide");
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


      /*$('#modal-atender').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var idunico = button.data('atender')
        $(".textcode").html("PED"+idunico);
        $("#hiddenAtender").val(idunico);
      });*/

      $('#tablaPrincipal').DataTable({
        dom: '<"toolbar">frtip',
        processing: true,
        stateSave:true,
		    serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax:{ url: "{{ route('envios.recepcionmotorizadotabla') }}",
                  data: function(d){
                      d.fechaconsulta = $("#fecha_consulta").val();
                      d.consulta = "paquete";
                      d.condicion = $('.condicion-tabla.activo').data("url");
                  }
          },
        createdRow: function( row, data, dataIndex){
          //console.log(row);
        },
        rowCallback: function (row, data, index) {
        },
        columns: [
            {data: 'correlativo', name: 'correlativo'},
          {
              data: 'codigos',
              name: 'codigos',
              render: function ( data, type, row, meta ) {
                  //var codigos_ped = JSON.parse("[" + row.codigos + "]");
                  //var codigos_ped = row.codigos.split(',').map(function(n) {return Number(n);});
                  //var codigos_ped = row.codigos.split(",").map(Number);
                  var codigos_ped = row.codigos.split(',');


                  //console.log(row);
                  var codigos_conf_ped = (row.codigos_confirmados||'').split(',');

                  console.log(codigos_conf_ped);

                  var lista_codigos ='<div class="row">';

                  $.each(codigos_ped , function(index, val) {
                      //lista_codigos += '<div class="col-lg-6">' + val +'</div>';
                      if(codigos_conf_ped.includes(val.trim())){
                          lista_codigos += '<div class="col-lg-6"><span class="text-success">' + val +'</span></div>';
                      }else{
                          lista_codigos += '<div class="col-lg-6">' + val +'</div>';
                      }
                  });

                  lista_codigos += '</div>';

                  return lista_codigos;
              }
          },
          {data: 'user_id', name: 'user_id','visible':false },
          {
            data: 'celular',
            name: 'celular',
            render: function ( data, type, row, meta ) {
              return row.celulares+' - '+row.nombres
            },
            "visible":false
            //searchable: true
        },
          {data: 'producto', name: 'producto'},
          {
            data: 'fecha_salida',
            name: 'fecha_salida',
            //render: $.fn.dataTable.render.moment('DD/MM/YYYY', 'YYYY-MM-DD')
          },
          {
            data:'direccion',
            name:'direccion',"visible":false,
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
                }
*/
                //return datas;

              }else{
                return 'REGISTRE DIRECCION';
              }
              //return 'REGISTRE DIRECCION';
            },
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
            sWidth:'20%',

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
