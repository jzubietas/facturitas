@extends('adminlte::page')

@section('title', 'Lista de pedidos')

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
  <br>
  <div class="bg-4">
    <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
      asesores con privilegios superiores: {{ $superasesor }}
    </h1>
  </div>
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
            <th scope="col">Fecha de registro</th>
            <th scope="col">Total (S/)</th>
            <th scope="col">Est. pedido</th>
            <th scope="col">Est. pago</th>
            <th scope="col">Est. sobre</th>
            <!--<th scope="col">Est. envío</th>
            <th scope="col">Cond. Pago</th>-->
            <th scope="col">Est. Envio</th>
            <th scope="col">Estado</th>
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

<!--  <script src="{{ asset('js/datatables.js') }}"></script>-->

<script>
  $(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#modal-delete').on('show.bs.modal', function (event) {
      //cuando abre el form de anular pedido
      var button = $(event.relatedTarget) 
      var idunico = button.data('delete')//id  basefria
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

      $(".textcode").html(idunico);
      $("#motivo").val('');
      $("#responsable").val('');

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

    $('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('pedidostabla') }}",
        "createdRow": function( row, data, dataIndex){
            if(data["estado"] == "1")
            {
            }else{
              $(row).addClass('yellow');
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
        {
            data: 'celulares', 
            name: 'celulares',
            render: function ( data, type, row, meta ) {
              return row.celulares+' - '+row.nombres
            },
            //searchable: true
        },
        {data: 'empresas', name: 'empresas', },
        {data: 'users', name: 'users', },
        {data: 'fecha', name: 'fecha', },
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
        },//estado de pago
        {
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
        },
        //{data: 'responsable', name: 'responsable', },//estado de envio
        
        //{data: 'condicion_pa', name: 'condicion_pa', },//ss
        {data: 'condicion_envio', name: 'condicion_envio', },//
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
        {data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},
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
