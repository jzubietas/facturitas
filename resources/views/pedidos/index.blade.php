@extends('adminlte::page')

@section('title', 'Pedidos - Bandeja de pedidos')

@section('content_header')
  <h1>Lista de pedidos
    @can('pedidos.create')
      <a href="{{ route('pedidos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
      {{-- <a href="" data-target="#modal-add-ruc" data-toggle="modal">(Agregar +)</a> --}}
    @endcan
    {{-- @can('pedidos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pedidosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar Lista de pedidos', 'key' => '3'])    
  </h1>
  {{--@if($superasesor > 0)--}}
  {{--
  <br>
  <div class="bg-4">
    <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
      asesores con privilegios superiores: {{ $superasesor }}
    </h1>
  </div>--}}
  {{--@endif--}}
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
      <table id="tablaPrincipal" class="table table-striped table-responsive">{{-- display nowrap  --}}
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Cliente</th>
            <th scope="col">Razón social</th>
            <th scope="col">Asesor</th>
            <th scope="col">F. Registro</th>
            <th scope="col">F. Actualizacion</th> 
            <th scope="col">Total (S/)</th>
            <th scope="col">Est. pedido</th>
            
            <th scope="col">Est. pago</th>
            <th scope="col">Con. pago</th>
            <th scope="col">Est. sobre</th>
           <th scope="col">Est. envío</th>
            <th scope="col">Cond. Pago</th>
            <!--<th scope="col">Estado</th>-->
            <th scope="col">Diferencia</th>
            {{--<th scope="col">Resp. Pedido</th>--}}
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('pedidos.modalid')
      @include('pedidos.modal.restaurarid')

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
    .textred{
      color: red !important;
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


<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


{{--<script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.0.1/js/dataTables.searchBuilder.min.js"></script>--}}
{{--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>--}}
{{--<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.24/sorting/datetime-moment.js"></script>--}}

<script src="https://momentjs.com/downloads/moment.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

<!--  <script src="{{ asset('js/datatables.js') }}"></script>-->

<script>
  $(document).ready(function () {
    //moment.updateLocale(moment.locale(), { invalidDate: "Invalid Date Example" });
    //$.fn.dataTable.moment('DD-MMM-Y HH:mm:ss');
    //$.fn.dataTable.moment('DD/MM/YYYY');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (localStorage.getItem("search_tabla") === null) {
      //...

    }else{
      //si existe la variable  localstorage

    }

    $('#modal-delete').on('show.bs.modal', function (event) {
      //cuando abre el form de anular pedido
      var button = $(event.relatedTarget) 
      var idunico = button.data('delete')//id  basefria
      var idresponsable = button.data('responsable')//id  basefria
      //console.log(idunico);
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
      //solo completo datos
      //hiddenId
      //
      $

      $(".textcode").html(idunico);
      $("#motivo").val('');
      $("#responsable").val( idresponsable );

    });

    $('#modal-restaurar').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) 
      var idunico = button.data('restaurar')
      console.log("unico "+idunico)
      $("#hiddenIDrestaurar").val(idunico);
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

    });

    var tablaPrincipal=$('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('pedidostabla') }}",
        "createdRow": function( row, data, dataIndex){
            if(data["estado"] == "1")
            {
            }else{
              $(row).addClass('textred');
            }           
        },
        rowCallback: function (row, data, index) {
              var pedidodiferencia=data.diferencia;
              //pedidodiferencia=0;
              if(pedidodiferencia==null){
                $('td:eq(12)', row).css('background', '#ca3a3a').css('color','#ffffff').css('text-align','center').css('font-weight','bold');
              }else{
                if(pedidodiferencia>3){
                  $('td:eq(12)', row).css('background', '#ca3a3a').css('color','#ffffff').css('text-align','center').css('font-weight','bold');
                }else{
                  $('td:eq(12)', row).css('background', '#44c24b').css('text-align','center').css('font-weight','bold');
                }
              }
        },
        initComplete:function(settings,json){          
          if (localStorage. getItem("search_tabla") === null) {
            //no existe
          }else{
            $('#tablaPrincipal_filter label input').val(localStorage.getItem("search_tabla") ).change();
          }          
        },
        columns: [
          //ID
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
        // CODIGO
        {data: 'codigos', name: 'codigos', },
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
        //EMPRESAS
        {data: 'empresas', name: 'empresas', },
        //USUARIOS
        {data: 'users', name: 'users', },
        //FECHA
        {
          data: 'fecha', 
          name: 'fecha', 
          //render: $.fn.dataTable.render.moment( 'DD-MMM-YYYY HH:mm:ss' )
        },
        {
          data: 'fecha_up', 
          name: 'fecha_up', 
          "visible":false,
          //render: $.fn.dataTable.render.moment( 'DD-MMM-YYYY HH:mm:ss' )
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
        },//estado de pedido
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
        },//estado de pago
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
          //estado del sobre
          data: 'envio', 
          name: 'envio', 
          render: function ( data, type, row, meta ) {
            if(row.envio==null){
              return '';
            }else{
              {
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
          }
        },
        //{data: 'responsable', name: 'responsable', },//estado de envio
        
        //{data: 'condicion_pa', name: 'condicion_pa', },//ss
        {
          data: 'condicion_envio', 
          name: 'condicion_envio',
          render: function ( data, type, row, meta ) {
            if(row.condiciones=='ANULADO'){
                return 'ANULADO';
            }else{
              return data;
            }
          }
        },//
        {
          data: 'estado',
          name: 'estado',
          render: function ( data, type, row, meta ) {
              if(row.estado==1){
                return '<span class="badge badge-success">Activo</span>';
              }else{
                return '<span class="badge badge-danger">Anulado</span>';
              }
            }
        },
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
            //Guardamos el ID
            localStorage.setItem('PreviousID', row.id);

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


    $('#tablaPrincipal_filter label input').on('paste', function(e) {
      var pasteData = e.originalEvent.clipboardData.getData('text')
      localStorage.setItem("search_tabla",pasteData);
    });
    $(document).on("keypress",'#tablaPrincipal_filter label input',function(){      
      localStorage.setItem("search_tabla",$(this).val());
      console.log( "search_tabla es "+localStorage.getItem("search_tabla") );
    });

    $(document).on("blur",'#tablaPrincipal_filter label input',function(){
      localStorage.setItem("search_tabla",$(this).val());
      console.log( "search_tabla es "+localStorage.getItem("search_tabla") );

    });

    $('#tablaPrincipal_filter label input').on('paste', function(e) {
      var pasteData = e.originalEvent.clipboardData.getData('text')
      localStorage.setItem("search_tabla",pasteData);
    });

    

    

    //$('#myInput').val( ... ).change();



    /*$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
      console.log("data" +data);
    });*/

   /* $(document).on("change","search.dt",function(){
      console.log("aaaaa")
    });
*/

    /*$("").on( 'search.dt', function () {
    $('#filterInfo').html( 'Currently applied global search: '+table.search() );
} );*/

    $(document).on("submit", "#formdelete", function (evento) {
      evento.preventDefault();
      console.log("validar delete");
      var motivo = $("#motivo").val();
      var responsable = $("#responsable").val();
   
      if (motivo.length < 1) {
        Swal.fire(
          'Error',
          'Ingrese el motivo para anular el pedido',
          'warning'
        )
      }
      else if (responsable == ''){
        Swal.fire(
          'Error',
          'Ingrese el responsable de la anulación',
          'warning'
        )
      }
      else {
      //this.submit();
        clickformdelete();
      }     

      /*var oForm = $(this);
      var formId = oForm.attr("id");
      var firstValue = oForm.find("input").first().val();
      alert("Form '" + formId + " is being submitted, value of first input is: " + firstValue);
      // Do stuff 
      return false;*/
   })

   $(document).on("submit", "#formrestaurar", function (evento) {
      evento.preventDefault();
      clickformrestaurar();     
   });

  });
</script>

<script>
  function resetearcamposdelete(){
      $('#motivo').val("");
      $('#responsable').val("");      
    }

  function clickformdelete()
    {
      console.log("action delete action")
      var formData = $("#formdelete").serialize();
      console.log(formData);
      $.ajax({
        type:'POST',
        url:"{{ route('pedidodeleteRequest.post') }}",
        data:formData,
      }).done(function (data) {
        $("#modal-delete").modal("hide");
        resetearcamposdelete();          
        $('#tablaPrincipal').DataTable().ajax.reload();      
      });
    }

    function clickformrestaurar()
    {
      var formData = $("#formrestaurar").serialize();
      $.ajax({
        type:'POST',
        url:"{{ route('pedidorestaurarRequest.post') }}",
        data:formData,
      }).done(function (data) {
        $("#modal-restaurar").modal("hide");
        //resetearcamposdelete();          
        $('#tablaPrincipal').DataTable().ajax.reload();      
      });
    }

    /*function clickformdelete(){
      $("#modal-delete").modal("show");
    }*/

</script>

  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado' || session('info') == 'restaurado')
    <script>
      Swal.fire(
        'Pedido {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  <script>
    //VALIDAR ANTES DE ENVIAR
    /*document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("formdelete").addEventListener('submit', validarFormularioDelete); 
    });*/

  </script>

  <script>
    //VALIDAR CAMPO RUC
    function maxLengthCheck(object)
      {
        if (object.value.length > object.maxLength)
          object.value = object.value.slice(0, object.maxLength)
      }
      
    //VALIDAR ANTES DE ENVIAR 2
    document.addEventListener("DOMContentLoaded", function() {    
    var form = document.getElementById("formulario2")
      if(form)
      {
        form.addEventListener('submit', validarFormulario2); 
      }    
    });

    function validarFormulario2(evento) {
      evento.preventDefault();
      var agregarruc = document.getElementById('agregarruc').value;

      if (agregarruc == '') {
          Swal.fire(
            'Error',
            'Debe ingresar el número de RUC',
            'warning'
          )
      }
      else if (agregarruc.length < 11){
        Swal.fire(
            'Error',
            'El número de RUC debe tener 11 dígitos',
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
