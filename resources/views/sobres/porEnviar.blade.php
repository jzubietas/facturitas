@extends('adminlte::page')

@section('title', 'Lista de sobres por enviar')

@section('content_header')
  <h1>Lista de sobres por enviar - ENVIOS
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
      
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Asesor</th>
            <th scope="col">Cliente</th>
            <th scope="col">Razón social</th>            
            <th scope="col">Fecha de registro</th>
            <th scope="col">Fecha de envio</th>
            <th scope="col">Fecha de entrega</th>
            <th scope="col">Destino</th>
            <th scope="col">Dirección de envío</th>
            <th scope="col">Estado de envio</th>
            <th scope="col">Estado de sobre</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('pedidos.modal.direccionid')
     
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
  
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>

  <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

  

<script>
      var myState = {
          pdf: null,
          currentPage: 1,
          zoom: 1
      }
   
      // more code here

  </script>

  <script>
    var tabla_pedidos=null;
  </script>

  <script>
    $(document).ready(function () {

      $(document).on("click","#change_imagen",function(){
        var fd2 = new FormData();
        //agregados el id pago
        let files=$('input[name="pimagen')
        var cambiaitem=$("#cambiaitem").val();
        var cambiapedido=$("#cambiapedido").val();        

        fd2.append("item",cambiaitem )
        fd2.append("pedido",cambiapedido )
        for (let i = 0; i < files.length; i++) {
          fd2.append('adjunto', $('input[type=file][name="pimagen"]')[0].files[0]);
        }

        $.ajax({
          data: fd2,
          processData: false,
          contentType: false,
          type: 'POST',
          url:"{{ route('envios.changeImg') }}",
          success:function(data){
            console.log(data);
            if(data.html=='0')
            {
            }else{
              $("#modal-cambiar-imagen").modal("hide");
              var urlimg = "{{asset('imagenes/logo_facturas.png')}}";
              urlimg = urlimg.replace('imagenes/', 'storage/entregas/');
              urlimg = urlimg.replace('logo_facturas.png', data.html);
              urlimg = urlimg.replace(' ', '%20');
              console.log(urlimg);
              $("#imagen_"+cambiapedido+'-'+cambiaitem).attr("src", urlimg );
            }
          }
        });

      });

      $(document).on("change","#rotulo",function(event){
        console.log("cambe rotulo")
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = (event) => {

          //pdfjsLib.getDocument(event.target.result).then((pdf) => {          });
          
        };
        reader.readAsDataURL(file);

      });

      $('#celular').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g,'');
      });

      $("#direccion",'#referencia','#observacion').bind('keypress', function(event) {
        var regex = new RegExp("^[a-zA-Z0-9 ]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

      /*$("#tracking").bind('keypress', function(event) {
        var regex = new RegExp("^[0-9]{2}+[0-1]{2}$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });*/

      $("#cantidad").bind('keypress', function(event) {
        var regex = new RegExp("^[0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

      
      $(".provincia").addClass("d-none");
      $(".lima").addClass("d-none");
      
      $(document).on("change","#limaprovincia",function(){
        switch($(this).val())
        {
          case 'L':
            console.log("e L");
            if(!$(".provincia").hasClass("d-none"))
            {
              $(".provincia").addClass("d-none");
            }
            $(".lima").removeClass("d-none");            
            break;
          case 'P':
            console.log("e P");
            if(!$(".lima").hasClass("d-none"))
            {
              $(".lima").addClass("d-none");
            }
            $(".provincia").removeClass("d-none");   
            break;
          default: 
            if(!$(".lima").hasClass("d-none"))
            {
              $(".lima").addClass("d-none");
            }
            if(!$(".provincia").hasClass("d-none"))
            {
              $(".provincia").addClass("d-none");
            }
            break;
          
        }
      });

      $(document).on("click","#direccionConfirmar",function(event){
        console.log("aasss")
        //var form=$(this);
        var fd2=new FormData();
        //return false;
        let val_cliente=$("#cliente_id").val();
        fd2.append('cliente_id', val_cliente);

        let val_nombre=$("#nombre").val();
        let val_contacto=$("#celular").val();
        let val_direccion=$("#direccion").val();
        let val_referencia=$("#referencia").val();
        let val_distrito=$("#distrito").val();
        let val_observacion=$("#observacion").val();

        //$('input[name="rotulo')[0]   total d input que aparece
        //let files=$('input[name="rotulo')[0].files.length;   imagenes nmo vacias

        let files=$('input[name="rotulo')[0].files;
        console.log(files.length)
        var combo_limaprovincia=$("#limaprovincia").val();
        var val_departamento=$("#departamento").val();
        var val_oficina=$("#oficina").val();
        var val_tracking=$("#tracking").val();
        var val_numregistro=$("#numregistro").val();
        var rows_selected = tabla_pedidos.column(0).checkboxes.selected();
        if(combo_limaprovincia=="")
        {
          Swal.fire(
              'Error',
              'Debe selecionar lima o provincia',
              'warning'
            )
            return;
        }else{

          if(combo_limaprovincia=="L")
          {
            if(val_nombre=="" )
            {
              Swal.fire(
                'Error',
                'Debe ingresar direccion',
                'warning'
              )
              return;
            }else if(val_contacto=="" || val_contacto.length!=9 )
            {
              Swal.fire(
                'Error',
                'Debe ingresar contacto valido (no vacio y que tenga 9 digitos)',
                'warning'
              )
              return;
            }else if(val_direccion=="" )
            {
              Swal.fire(
                'Error',
                'Debe ingresar direccion',
                'warning'
              )
              return;
            }else if(val_referencia=="" )
            {
              Swal.fire(
                'Error',
                'Debe ingresar referencia',
                'warning'
              )
              return;
            }
          }else if(combo_limaprovincia=="P")
          {
            var cont_rotulo=files.length;
            if(val_departamento=="")
            {
              Swal.fire(
                'Error',
                'Debe selecionar departamento',
                'warning'
              )
              return;
            }else if(val_oficina=="")
            {
              Swal.fire(
                'Error',
                'Debe ingresar oficina',
                'warning'
              )
              return;
            }else if(val_tracking=="")
            {
              Swal.fire(
                'Error',
                'Debe ingresar tracking',
                'warning'
              )
              return;
            }else if(val_numregistro=="")
            {
              Swal.fire(
                'Error',
                'Debe ingresar tracking',
                'warning'
              )
              return;
            }else if(cont_rotulo==0)
            {
              Swal.fire(
                'Error',
                'Debe ingresar rotulo',
                'warning'
              )
              return;
            }
          }
          //paso provincia validacion
          if(combo_limaprovincia=="P")
          { 
            fd2.append('departamento', val_departamento);
            fd2.append('oficina', val_oficina);
            fd2.append('tracking', val_tracking);
            fd2.append('numregistro', val_numregistro);

            for (let i = 0; i < files.length; i++) {
              fd2.append('rotulo', $('input[type=file][name="rotulo"]')[0].files[0]);
            }

          }else if(combo_limaprovincia=="L")
          {
            fd2.append('nombre', val_nombre);
            fd2.append('contacto', val_contacto);
            fd2.append('direccion', val_direccion);
            fd2.append('referencia', val_referencia);
            fd2.append('distrito', val_distrito);
            fd2.append('observacion', val_observacion);

          }          
          
          //submit
        }
        console.log("aaa");
        var destino= (combo_limaprovincia=="L")? 'LIMA':'PROVINCIA';
          fd2.append('destino', destino);
        var pedidos=[];
        $.each(rows_selected, function(index, rowId){
              // Create a hidden element
              console.log("ID PEDIDO  es "+  rowId);
              pedidos.push(rowId);
              /*$(form).append(
                  $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'codigos[]')
                    .val(rowId)
              );*/
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


        //return let_pedidos;
         // console.log("aaaa  "+let_pedidos);
        $pedidos=pedidos.join(',');
        //fd2.append('pedidos', JSON.stringify(pedidos) );
        fd2.append('pedidos', $pedidos );

        console.log(fd2);

        console.log("finalizo registro");
        //return false;
        $.ajax({
          data: fd2,
          processData: false,
          contentType: false,
          type: 'POST',
          url:"{{ route('envios.direccion') }}",
          success:function(data)
          {
            console.log(data);
            $("#modal-direccion").modal("hide");
            $("#tablaPrincipal").DataTable().ajax.reload();
            //console.log("data " +data);
            //console.log("aa");
          }
        });


        //validaciones 

        // Iterate over all selected checkboxes

      });
      /*$(document).on("submit","#formdireccion",function(event){
        event.preventDefault();
        console.log("aa");

      });*/

      //inicio tabla pedidos
      $('#modal-direccion').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) 
        var cliente = button.data('cliente');
        console.log("cliente "+cliente);
        $("#cliente_id").val(cliente);

        tabla_pedidos.destroy();

        tabla_pedidos=$('#tablaPrincipalpedidosagregar').DataTable({
          "bPaginate": false,
          "bFilter": false,
          "bInfo": false,
          'ajax': {
            url:"{{ route('cargar.pedidosenvioclientetabla') }}",					
            'data': { "cliente_id": cliente}, 
            "type": "get",
          },
         
          columns:[
            {
                "data": "id",
                'targets': 0,
                'checkboxes': {                        
                    'selectRow': true
                }
            },
            {data: 'codigo', name: 'codigo',},
            {
                "data": 'nombre_empresa',
                "name": 'nombre_empresa',
                "render": function ( data, type, row, meta ) {      
                  return data;                
                    
                }
            },
          ],
          'select': {
              'style': 'multi'
          },
        });

        $("#limaprovincia").val("").trigger("change");

      });

      $(document).on("change","#departamento",function(){

      });

      $('#tablaPrincipalpedidosagregar tbody').on('click', 'input', function () {
            var data = tabla_pedidos.row($(this).parents('tr')).data();
    var indice = tabla_pedidos.row($(this).parents('tr')).index();
            console.log(data);
            var enhtml = $(this).parents('tr').html();
            console.log(enhtml);
            var arrray_data=JSON.stringify(data);
            console.log(arrray_data);
          console.log(data["id"] + "bbb's idpedido is: ");
          console.log(data["codigo"] + "bbb's codigo pedido is: ");
          console.log(data["DT_RowIndex"] + "bbb's indice is: ");
          console.log(indice + "'index  codigo is: ");
        });

      tabla_pedidos=$('#tablaPrincipalpedidosagregar').DataTable({
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
          }
        });

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
        console.log("form enviarid");

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

      $('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('sobres.porenviartabla') }}",
        createdRow: function( row, data, dataIndex){
          //console.log(row);          
        },
        rowCallback: function (row, data, index) {           
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
              },"visible":false
          },
          {data: 'codigos', name: 'codigos', },
          {data: 'users', name: 'users', },
          {
            data: 'celulares', 
            name: 'celulares',
            render: function ( data, type, row, meta ) {
              return row.celulares+' - '+row.nombres
            },
        },
          {data: 'empresas', name: 'empresas', },
          {data: 'fecha_envio_doc', name: 'fecha_envio_doc',"visible":false },
          {data: 'fecha_envio_doc_fis', name: 'fecha_envio_doc_fis', },
          {data: 'fecha_recepcion', name: 'fecha_recepcion',"bisible":false },
          {data: 'destino', name: 'destino', "visible":false },
          {
            data:'direccion',
            name:'direccion',
            render: function ( data, type, row, meta ) {
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
              }else{
                return '<span class="badge badge-danger">REGISTRE DIRECCION</span>';
              }
              //return 'REGISTRE DIRECCION';
            },
          },
          {data: 'condicion_envio', name: 'condicion_envio', },
          {
            data: 'envio', 
            name: 'envio',
            render: function ( data, type, row, meta ) {
              if(row.envio=='1')
              {
                return '<span class="badge badge-danger">Por confirmar recepcion</span>';
              }else{
                return '<span class="badge badge-info">Recibido</span>';
              }
            },
            "visible":false
          },
          {
            data: 'action', 
            name: 'action', 
            orderable: false, 
            searchable: false,
            sWidth:'20%',
            render: function ( data, type, row, meta ) {   
              datass='';
              datass=datass+'<a href="" data-target="#modal-direccion" data-toggle="modal" data-cliente="'+row.cliente_id+'" data-direccion="'+row.id+'"><button class="btn btn-info btn-sm"><i class="fas fa-envelope"></i> Direccion</button></a>';  

              @if($ver_botones_accion > 2)
                @can('envios.enviar')
                  datass=datass+'<a href="" data-target="#modal-enviar" data-toggle="modal" data-enviar="'+row.id+'"><button class="btn btn-success btn-sm"><i class="fas fa-envelope"></i> Entregado</button></a>';  
                  if(row.envio=='1')
                  {
                    datass = datass+ '<a href="" data-target="#modal-recibir" data-toggle="modal" data-recibir="'+row.id+'"><button class="btn btn-warning btn-sm"><i class="fas fa-check-circle"></i> Recibido</button></a>'; 
                  }
                @endcan
              @endif
              
              if(row.destino == null && row.direccion =='0' && (row.envio*1) >0)
              {
                var urldireccion = '{{ route("envios.createdireccion", ":id") }}';
                urldireccion = urldireccion.replace(':id', row.id);
                data = data+'<a href="'+urldireccion+'" class="btn btn-dark btn-sm"><i class="fas fa-map"></i> Destino</a><br>';
              }
              
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
  
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  
  <script>
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

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
