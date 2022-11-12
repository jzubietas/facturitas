@extends('adminlte::page')

@section('title', 'Lista de Movimientos')

@section('content_header')
  <h1>Lista de movimientos
    @if($pagosobservados_cantidad > 0)
    <div class="small-box bg-danger" style="text-align: center">
      <div class="inner">
        <h3>{{ $pagosobservados_cantidad }}</h3>
        <p>MOVIMIENTOS BANCARIOS</p>
      </div>
    </div>
    @endif
    {{-- @can('movimientos.create') --}}
      <a href="" data-target="#modal-add-movimientos" data-toggle="modal"><button class="btn btn-info btn-sm"><i class="fas fa-plus-circle"></i> Agregar</button></a>
    {{-- @endcan --}}
    {{-- @can('pagos.exportar')
    <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="{{ route('pagosExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
      </div>
    </div>
    @endcan --}}
    {{-- <div class="float-right btn-group dropleft">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exportar
      </button>
      <div class="dropdown-menu">
        <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
      </div>
    </div>
    @include('pagos.modals.exportar', ['title' => 'Exportar Lista de pagos', 'key' => '1']) --}}    
    @include('movimientos.modals.AddMovimientos')
  </h1>
<br>
  <div class="row">
    <div class=" col-lg-4">
        <select name="banco_movimientos" class="border form-control selectpicker border-secondary" id="banco_movimientos" data-live-search="true">
        <option value="">---- SELECCIONE BANCO ----</option>
          <option value="BCP">BCP</option>          
          <option value="BBVA">BBVA</option>
          <option value="IBK">INTERBANK</option>
        </select>
    </div>
    <div class="col-lg-4">
        <select name="tipo_movimientos" class="border form-control selectpicker border-secondary" id="tipo_movimientos" data-live-search="true">
          <option value="">---- SELECCIONE TIPO MOVIMIENTO ----</option>
        </select>
    </div>
    <div class=" col-lg-4">
        <select name="titular_movimientos" class="border form-control selectpicker border-secondary" id="titular_movimientos" data-live-search="true">
          <option value="">---- SELECCIONE TITULAR ----</option>
          <option value="EPIFANIO SOLANO HUAMAN">EPIFANIO SOLANO HUAMAN</option>
          <option value="NIKSER DENIS ORE RIVEROS">NIKSER DENIS ORE RIVEROS</option>
          
        </select>
    </div>
    
  </div>
  

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
            <th scope="col">COD.</th>
            <th scope="col">Banco</th>
            <th scope="col">Titular</th>
            <th scope="col">Importe</th>
            <th scope="col">Tipo de movimiento</th>
            <th scope="col">Fecha</th>
            <th scope="col">Conciliacion</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('movimientos.modals.modalDeleteId')
    </div>
  </div>

@stop

@section('css')
  <!--<link rel="stylesheet" href="../css/admin_custom.css">-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <style>
    .yellow {
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

  <script>
    function clickformdelete()
    {
      console.log("action delete action")
      var formData = $("#formdelete").serialize();
      console.log(formData);
      $.ajax({
        type:'POST',
        url:"{{ route('movimientodeleteRequest.post') }}",
        data:formData,
      }).done(function (data) {
        $("#modal-delete").modal("hide");
        resetearcamposdelete();          
        $('#tablaPrincipal').DataTable().ajax.reload();      
      });
    }
  </script>
  <script>

    /*$(document).on("submit","#formulario",function(e){
      e.preventDefault();
      validarFormulario();
    });/*
  /*document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
  });*/

    function validarFormulario()
    {
      //var submitevent=this;
      //evento.preventDefault();
      let banco = $("#banco").val();

      let tipotrans = $("#tipotransferencia").val();
      let descrip_otros = $("#descrip_otros").val();
      let titular = $("#titulares").val();
      console.log("t "+titular);
      let monto = $("#monto").val();
      let fecha = $("#fecha").val();

      if(tipotrans=='')
      {
        Swal.fire(
            'Error',
            'Elija el banco',
            'warning'
          )
          return;
      }else{
        if(tipotrans=='OTROS')
        {
          if(descrip_otros=='')
          {
            Swal.fire(
            'Error',
            'Ingrese la descripcion para el movimiento OTROS',
            'warning'
          )
          return;
          }
        }
      }

      if(banco=='')
      {
        Swal.fire(
            'Error',
            'Elija el banco',
            'warning'
          )
          return;
      }else if(tipotrans=='')
      {
        Swal.fire(
            'Error',
            'Elija el movimiento',
            'warning'
          )
          return;
      }else if(titular=='')
      {
        Swal.fire(
            'Error',
            'Elija al titular',
            'warning'
          )
          return;
      }else if(monto=='')
      {
        Swal.fire(
            'Error',
            'Ingrese el monto',
            'warning'
          )
          return;
      }else if(fecha=='')
      {
        Swal.fire(
            'Error',
            'Seleccione la fecha',
            'warning'
          )
          return;
      }else{
        //validar repetido

        $.ajax({
          //async:false,
          url: "{{ route('validar_repetido') }}",
          data:({"banco":banco,"tipo":tipotrans,"titulares":titular,"monto":monto,"fecha":fecha}),
          method: 'GET',
          success: function(data) {
            console.log(data.html);
            var dataresponse=data.html.split("|");

            if(dataresponse[0]=="bloqueo")
            {
              let movim=dataresponse[1];
              if(movim<10){
                movim='MOV000'+movim;
              }else if(movim<100){
                movim= 'MOV00'+movim;
              }else if(movim<1000){
                movim='MOV0'+movim;
              }else{
                movim='MOV'+movim;
              }

              Swal.fire({
                title: "Deseas continuar con el registro?",
                text: 'La misma informacion se encuentra registrado en el movimiento '+movim,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!'
              }).then((result) => {
                console.log(result);
                if (result.value==true) {
                  $("#formulario").trigger("submit")
                  //console.log("aaaaaaa")
                  /*Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  )*/
                }else{
                  //console.log("cancel")
                  //$("#modal-add-movimientos").hide();
                  //limpiar campos
                  $("#banco").val("").selectpicker('refresh');
                  $("#tipotransferencia").val("").selectpicker('refresh');
                  $("#descrip_otros").val("").html("");
                  $("#titulares").val("").selectpicker('refresh');
                  $("#monto").val("");
                  $("#fecha").val("");
                  
                  $("#modal-add-movimientos").modal("hide");
                }
              })

              /*swal({
                  title: "Seguro de continar?",
                  text: 'La misma informacion se encuentra registrado en el movimiento '+movim+'\n Deseas continar con el registro?',
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
                .then((willDelete) => {
                  if (willDelete) {
                    swal("Poof! Your imaginary file has been deleted!", {
                      icon: "success",
                    });
                  } else {
                    swal("Your imaginary file is safe!");
                  }
                });*/

              /*Swal.fire({
                title: 'La misma informacion se encuentra registrado en el movimiento '+movim+'\n Deseas continar con el registro?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Grabar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                if (result.isConfirmed) {
                  $("#modal-add-movimientos").hide();
                  console.log("confirm")
                } else if (result.isDenied) {
                  console.log("denied")
                  $("#modal-add-movimientos").hide();
                }
              });  */            
            }else  if(dataresponse[0]=="sigue")
            {
              $("#formulario").trigger("submit")
              //$("#formulario").submit();
            }            
          }
        });


        
      }

    }


  $(document).ready(function () {


    $(document).on("click", "#registrar_movimientos", function (e) {
      e.preventDefault();
      console.log("log");
      validarFormulario();
       // var oForm = $(this);
        //var formId = oForm.attr("id");
        //var firstValue = oForm.find("input").first().val();
        //alert("Form '" + formId + " is being submitted, value of first input is: " + firstValue);
        // Do stuff 
        //return false;
    })

    //$("#banco").val("").html("");
    $("#tipotransferencia").html("");
    $("#tipotransferencia").selectpicker("refresh");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    

    //$(document).on("submit", "#formulario", function (event) {
      //event.preventDefault();
      
      //var total_pedido_pagar = document.getElementById('total_pedido_pagar').value;

      
    //});

    /*function validarFormulario(evento) {
      evento.preventDefault();
      

    }*/
    

    /*$.ajax({
      url: "{{ route('asesorespago') }}",
      method: 'GET',
      success: function(data) {
        console.log(data.html);
        $('#banco_movimientos').html(data.html);
        $('#banco_movimientos').selectpicker('refresh');
      }
    });*/
    /*$.ajax({
      url: "{{ route('asesorespago') }}",
      method: 'GET',
      success: function(data) {
        console.log(data.html);
        $('#tipo_movimientos').html(data.html);
        $('#tipo_movimientos').selectpicker('refresh');
      }
    });*/
    /*$.ajax({
      url: "{{ route('asesorespago') }}",
      method: 'GET',
      success: function(data) {
        console.log(data.html);
        $('#titular_movimientos').html(data.html);
        $('#titular_movimientos').selectpicker('refresh');
      }
    });*/
    

    $(document).on("change","#banco",function(event){

      console.log("banco change");
      $.ajax({
        url: "{{ route('cargar.tipomovimiento') }}?banco=" + $(this).val(),
        method: 'GET',
        success: function(data) {
          //carga ajax a combo
          $('#tipotransferencia').html(data.html);
          $("#tipotransferencia").selectpicker("refresh");
        }
      });
    });

    $(".descrip_otros").hide();

    $(document).on("change","#tipotransferencia",function(event){
      console.log($(this).val());
      if($(this).val()=='OTROS' || $(this).val()=='YAPE'  || $(this).val()=='PAGO YAPE' || $(this).val()=='ABON YAPE'){
        //$("#descrip_otros").prop("visibled",none);
        $(".descrip_otros").show();
      }else{
        $(".descrip_otros").hide();
      }
    });

    $(document).on("change","#banco_movimientos",function(event){

      console.log("banco_movimientos change");
      $.ajax({
        url: "{{ route('cargar.tipomovimiento') }}?banco=" + $(this).val(),
        method: 'GET',
        success: function(data) {
          //carga ajax a combo
          $('#tipo_movimientos').html(data.html);
          $("#tipo_movimientos").selectpicker("refresh");
          $('#tablaPrincipal').DataTable().ajax.reload();
        }
      });
    });

    $(document).on("change","#tipo_movimientos",function(event){
      $('#tablaPrincipal').DataTable().ajax.reload();
    });
    $(document).on("change","#titular_movimientos",function(event){
      $('#tablaPrincipal').DataTable().ajax.reload();
    });

    //para opcion eliminar  movimientos
     $('#modal-delete').on('show.bs.modal', function (event) {     
      var button = $(event.relatedTarget) 
      var idunico = button.data('delete')      
      $("#hiddenIDdelete").val(idunico);
      if(idunico<10){
        idunico='MOV000'+idunico;
      }else if(idunico<100){
        idunico= 'MOV00'+idunico;
      }else if(idunico<1000){
        idunico='MOV0'+idunico;
      }else{
        idunico='MOV'+idunico;
      }
      $(".textcode").html(idunico);
    });

    //submit para form eliminar pago
     $(document).on("submit", "#formdelete", function (evento) {
      evento.preventDefault();
      console.log("validar delete");
        clickformdelete();

    })

    $('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: {
          url: "{{ route('movimientostabla') }}",
          data: function (d) {
            d.banco = $("#banco_movimientos").val();
            d.tipo = $("#tipo_movimientos").val();
            d.titular = $("#titular_movimientos").val();
          },
        },
        //ajax: "{{ route('movimientostabla') }}",
        createdRow: function( row, data, dataIndex){  

        },
        rowCallback: function (row, data, index) {           
        },
        columns: [
        {
            data: 'id', 
            name: 'id',
            render: function ( data, type, row, meta ) {             
              if(row.id<10){
                return 'MOV000'+row.id;
              }else if(row.id<100){
                return 'MOV00'+row.id;
              }else if(row.id<1000){
                return 'MOV0'+row.id;
              }else{
                return 'MOV'+row.id;
              } 
            }
        },
        {
          data: 'banco' , name: 'banco' },
        {//asesor
          data: 'titular', 
          name: 'titular',
          render: function ( data, type, row, meta ) {
            if(data=='EPIFANIO SOLANO HUAMAN'){
              data='EPIFANIO';
            }else if(data=='NIKSER DENIS ORE RIVEROS'){
              data='DENIS';
            }else{
              data='';
            }
            return data;
          }
        },
        {//cliente
          data: 'importe',  name: 'importe' },
        {//observacion
          data: 'tipo', 
          name: 'tipo',
          render: function ( data, type, row, meta ) { 
            if(row.descripcion_otros==null)
            {
              return data;
            }else{
              return data+'<br>('+row.descripcion_otros+')';
            }
          }
        },
        {//totalcobro
          data: 'fecha', name: 'fecha'
        },
        {
          data: 'pago', 
          name: 'pago', 
          render: function ( data, type, row, meta ) {  
            /*if(data==null || data==0 || data=='0')
            {
              return 'SIN CONCILIAR';
            }else{
              return "CONCILIADO";
            }*/
            return data;             
          }
        },//estado de pago
        {
          data: 'action', 
          name: 'action', 
          orderable: false, 
          searchable: false,
          sWidth:'20%',
          render: function ( data, type, row, meta ) {
            var urlcreate = '{{ route("movimientos.show", ":id") }}';
            var urledit = '{{ route("movimientos.edit", ":id") }}';
            urlcreate = urlcreate.replace(':id', row.id);
            urledit = urledit.replace(':id', row.id);
            @can('movimientos.create')
              data = data+'<a href="'+urlcreate+'" class="btn btn-info btn-sm">Ver</a>';
            @endcan
            @can('movimientos.edit')
              data = data+'<a href="'+urledit+'" class="btn btn-info btn-sm">Ver</a>';
            @endcan
           
              data = data+'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'+row.id+'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
           

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

    

  });
  </script>

  <script>
    function resetearcamposdelete(){
      //$('#motivo').val("");
      //$('#responsable').val("");      
    }

    
  </script>

  <script>  
    //VALIDAR CAMPOS NUMERICO DE MONTO EN PAGOS
    
    $('input.number').keyup(function(event) {

    if(event.which >= 37 && event.which <= 40){
      event.preventDefault();
    }

    $(this).val(function(index, value) {
      return value
        .replace(/\D/g, "")
        .replace(/([0-9])([0-9]{2})$/, '$1.$2')  
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
      ;
    });
    });
  </script>

  @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
    <script>
      Swal.fire(
        'Pago {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

@stop
