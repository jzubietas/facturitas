@extends('adminlte::page')

@section('title', 'Rutas de Envio')

@section('content_header')
  <h1>Rutas de envio - ENVIOS
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
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Asesor</th>
            <th scope="col">Cliente</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Codigos</th>
            <th scope="col">Producto</th>            
            <th scope="col">Direccion</th>
            <th scope="col">Referencia</th>
            <th scope="col">Observacion</th>
            <th scope="col">Distrito</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('pedidos.modal.enviarid')
      @include('pedidos.modal.recibirid')
      @include('pedidos.modal.direccionid')
      @include('pedidos.modal.verdireccionid')
      @include('pedidos.modal.editdireccionid')
      @include('pedidos.modal.destinoid')
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
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

  <script>
    $(document).ready(function () {

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
        ajax: "{{ route('envios.rutaenviotabla') }}",
        rowCallback: function (row, data, index) {
            console.log(data.destino)
              if(data.destino=='LIMA'){
                $('td:eq(0)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(1)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(2)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(3)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(4)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(5)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(6)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
                $('td:eq(7)', row).css('color','#000').css('text-align','center').css('font-weight','bold');
              }else if(data.destino=='PROVINCIA'){                
                $('td:eq(0)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(1)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(2)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(3)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(4)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(5)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(6)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                $('td:eq(7)', row).css('color','red').css('text-align','center').css('font-weight','bold');
                
              }
        },
        columns: [
          {
              data: 'id', 
              name: 'id',"visible":true,
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
          {data: 'identificador', name: 'identificador', },
          //{data: 'codigos', name: 'codigos', },
          //{data: 'users', name: 'users', },
          {
            data: 'celular', 
            name: 'celular',
            render: function ( data, type, row, meta ) {
              return row.celular+' - '+row.nombre
            },
            //searchable: true
        },
        {data: 'cantidad', name: 'cantidad', },
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
          {data: 'observacion', name: 'observacion', },
          {
            data: 'distrito', 
            name: 'distrito',
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
            sWidth:'20%',
            render: function ( data, type, row, meta ) {  
              datass="";
              datass = datass+ '<a href="" data-target="#modal-revertir" data-toggle="modal" data-recibir="'+row.id+'"><button class="btn btn-info btn-sm"><i class="fas fa-trash"></i> REVERTIR</button></a>'; 
              return datass;                               
            }
          },
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay informaciÃ³n",
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

      if (condicion == 'ENTREGADO') {
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
