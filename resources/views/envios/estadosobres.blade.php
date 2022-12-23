@extends('adminlte::page')

@section('title', 'Envios | Sobres sin enviar')

@section('content_header')
  <h1>Lista de sobres sin enviar - ENVIOS
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
    @include('pedidos.modal.exportar', ['title' => 'Exportar Estado Sobres', 'key' => '11'])
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



<div class="row">
@foreach ($_pedidos as $pedido)
        <div class="col-2">
            <div class="card card-warning">
                <div class="card-header">
                    <h5>ASESOR {{ $pedido->identificador }}</h5>
                </div>
                <div class="card-body">
                    <h4 class="text-center">
                        <b>{{ $pedido->total }}</b>
                    </h4>
                </div>
            </div>
        </div>
        @endforeach      
</div>




  <div class="card">
    <div class="card-body">

      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Asesor</th>
            {{-- <th scope="col">Cliente</th> --}}
            <th scope="col">Razón social</th>
            <th scope="col">Fecha de registro</th>
            <th scope="col">Fecha de envio</th>
            <th scope="col">Fecha de entrega</th>
            <th scope="col">Destino</th>
            <th scope="col">Dirección de envío</th>
            <th scope="col">Estado de envio</th>
            <th scope="col">Estado de sobre</th>
            <th scope="col">Observacion Devolucion</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('sobres.modal.direccionid')

      @include('sobres.modal.historialLima')
      @include('sobres.modal.historialProvincia')

    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf_viewer.css">

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
  <style>
    /*#canvas_container {
            width: 200px !important;
            height: 400px !important;
            overflow: auto;
        }*/
   /* #canvas_container {
        background: #333;
        text-align: center;
        border: solid 3px;
    }*/

    #pdf_renderer{
      position:relative;
    }
    #canvas_container{
      position:relative;
    }
    .modal-lg {
    max-width: 80%;
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
  var tablehistoricolima=null;
  tablehistoricolima=$('#tablaHistorialLima').DataTable({"bPaginate": false,"bFilter": false,"bInfo": false,"length": 3,
          columns:
          [
            {data: 'id'},{data: 'nombre'},{data: 'recibe'},{data: 'direccion'},{data: 'referencia'},{data: 'distrito'},{data: 'observacion'},{data: null},
          ],
        });

  var tablehistoricoprovincia=null;
  tablehistoricoprovincia=$('#tablaHistorialProvincia').DataTable({"bPaginate": false,"bFilter": false,"bInfo": false,"length": 3,
          columns:
          [
            {data: 'id'},{data: 'tracking'},{data: 'numregistro'},{data: null},
          ],
        });

      var myState = {
          pdf: null,
          currentPage: 1,
          zoom: 1
      }
   
  var currPage=0;

  var tabla_pedidos=null;

</script>
<script>
  $(document).ready(function () {

    /*$(document).on("click","#saveHistoricoLima",function(){

      let cliente=$("#cliente_id").val();
      let nombre=$("#nombre").val();
      let celular=$("#celular").val();
      let direccion=$("#direccion").val();
      let referencia=$("#referencia").val();
      let distrito=$("#distrito").val();
      let observacion=$("#observacion").val();
      var fd2=new FormData();

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
          }
        });

    });*/

    /*$(document).on("click","#saveHistoricoProvincia",function(){

      var fd2=new FormData();
      let cliente=$("#cliente_id").val();
      let tracking=$("#tracking").val();
      let numregistro=$("#numregistro").val();
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

          }
        });

    });*/

    $(document).on("click","#go_previous",function(e){
      e.preventDefault();
      if(myState.pdf == null || myState.currentPage == 1)
      {
        console.log("atras")
        return false;
      }
            myState.currentPage -= 1;
            $("#current_page").val(myState.currentPage);
            render();
    });

    $(document).on("click","#go_next",function(e){
      e.preventDefault();
      console.log("numpages "+myState.pdf._pdfInfo.numPages);
      console.log("currentpage "+myState.currentPage)
        if(myState.pdf == null || myState.currentPage == myState.pdf._pdfInfo.numPages)
        {
          console.log("next")
          return false;
        }


              myState.currentPage += 1;
              $("#current_page").val(myState.currentPage);
              if(myState.currentPage == myState.pdf._pdfInfo.numPages)
              {
                $("#go_next").addClass("d-none");
              }
              render();
    });

    $(document).on("keypress","#current_page",function(e){
          if(myState.pdf == null) return;

            // Get key code
            var code = (e.keyCode ? e.keyCode : e.which);

            // If key code matches that of the Enter key
            if(code == 13) {
                var desiredPage = document.getElementById('current_page').valueAsNumber;

                if(desiredPage >= 1 && desiredPage <= myState.pdf._pdfInfo.numPages) {
                        myState.currentPage = desiredPage;
                        document.getElementById("current_page").value = desiredPage;
                        render();
                }
            }
    });

    $(document).on("click","#zoom_in",function(e){
        if(myState.pdf == null) return;
            myState.zoom += 0.5;
            render();
    });

    $(document).on("click","#zoom_out",function(e){
        if(myState.pdf == null) return;
            myState.zoom -= 0.5;
            render();
    });

  });
</script>

  <script>

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
        $(".drop-rotulo").removeClass("d-none");
        console.log("cambe rotulo")
        var file = event.target.files[0];
        console.log(file);
        var reader = new FileReader();
        reader.onload = (event) => {

          pdfjsLib.getDocument(event.target.result).then((pdf) => {
            $("#my_pdf_viewer").removeClass("d-none");
            //cargar frame
            myState.pdf = pdf;
            render();
            thePDF=pdf;
            numPages=pdf.numPages;
            myState.currentPage=1;
            $("#current_page").val(myState.currentPage)
            pdf.getPage(1).then(handlePages);

            if(myState.currentPage == myState.pdf._pdfInfo.numPages)
            {

              $("#go_next").addClass("d-none");
              $("#go_previous").addClass("d-none");
              $("#current_page").addClass("d-none");
            }else{
              $("#go_next").removeClass("d-none");
              $("#go_previous").removeClass("d-none");
              $("#current_page").removeClass("d-none");
            }




          });

        };
        reader.readAsDataURL(file);

      });

      window.render=function()
      {
        myState.pdf.getPage(myState.currentPage).then((page) => {
          var canvas = document.getElementById("pdf_renderer");

          var ctx = canvas.getContext('2d');
          var viewport = page.getViewport(1);
          canvas.width = viewport.width;//viewport.width;
          canvas.height = viewport.height;//viewport.height;

          //canvas.width  = 100%;
          //canvas.height = 400;
          canvas.style.width  = '100%';
          canvas.style.height = '100%';

          page.render({
              canvasContext: ctx,
              viewport: viewport
          });

        });
      }

      window.handlePages=function(page)
      {
        var viewport = page.getViewport( 1 );
        //We'll create a canvas for each page to draw it on
        var canvas = document.createElement( "canvas" );
        canvas.style.display = "block";
        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        //Draw it on the canvas
        page.render({canvasContext: context, viewport: viewport});

        //Add it to the web page
        document.body.appendChild( canvas );

        //Move to next page
        currPage++;
        if ( thePDF !== null && currPage <= numPages )
        {
            thePDF.getPage( currPage ).then( handlePages );
        }
      }

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

      $('input.number').keyup(function (event) {
        console.log("number")

        if (event.which >= 37 && event.which <= 40) {
            event.preventDefault();
        }

        $(this).val(function (index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
        });
      });

      
      $("#tracking").bind('keypress', function(event) {
        var regex = new RegExp("^[0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });
      
      $("#numregistro").bind('keypress', function(event) {
        var regex = new RegExp("^[0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

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
            if(!$(".viewpdf").hasClass("d-none"))
            {
              $(".viewpdf").addClass("d-none");
            }
            $(".lima").removeClass("d-none");

            /*.removeClass('col-md-2')
            .addClass('col-md-3')*/
            if($(".contenedor-tabla").hasClass("col-4"))
            {
              $(".contenedor-tabla").removeClass("col-4");
              $(".contenedor-tabla").addClass("col-6");
            }
            if($(".contenedor-formulario").hasClass("col-4"))
            {
              $(".contenedor-formulario").removeClass("col-4");
              $(".contenedor-formulario").addClass("col-6");
            }

            tabla_pedidos.columns.adjust().draw();
            //$("#tablaPrincipalpedidosagregar").DataTable().ajax.reload();
            //$("#tablaPrincipalpedidosagregar").columns.adjust().draw();

            //$(".contenedor-formulario").removeClass("col-4");
            //$(".contenedor-pdf").removeClass("col-4");



            break;
          case 'P':
            console.log("e P");
            if(!$(".lima").hasClass("d-none"))
            {
              $(".lima").addClass("d-none");
            }
            $(".provincia").removeClass("d-none");
            $(".viewpdf").removeClass("d-none");


            if($(".contenedor-tabla").hasClass("col-6"))
            {
              $(".contenedor-tabla").removeClass("col-6");
              $(".contenedor-tabla").addClass("col-4");
            }
            if($(".contenedor-formulario").hasClass("col-6"))
            {
              $(".contenedor-formulario").removeClass("col-6");
              $(".contenedor-formulario").addClass("col-4");
            }
            tabla_pedidos.columns.adjust().draw();
            //$("#tablaPrincipalpedidosagregar").DataTable().ajax.reload();
            //$("#tablaPrincipalpedidosagregar").columns.adjust().draw();

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

            if($(".contenedor-tabla").hasClass("col-4"))
            {
              $(".contenedor-tabla").removeClass("col-4");
              $(".contenedor-tabla").addClass("col-6");
            }
            if($(".contenedor-formulario").hasClass("col-4"))
            {
              $(".contenedor-formulario").removeClass("col-4");
              $(".contenedor-formulario").addClass("col-6");
            }

            tabla_pedidos.columns.adjust().draw();
            break;

        }
      });

      $(document).on("click","#direccionConfirmar",function(event){
        var fd2=new FormData();
        //return false;
        let val_cliente=$("#cliente_id").val();
          let val_cod_pedido=$("#cod_pedido").val();
          let val_cod_ase=$("#cod_ase").val();
        fd2.append('cliente_id', val_cliente);
          fd2.append('cod_pedido', val_cod_pedido);
          fd2.append('cod_asesor', val_cod_ase);

        let val_nombre=$("#nombre").val();
        let val_contacto=$("#celular").val();
        let val_direccion=$("#direccion").val();
        let val_referencia=$("#referencia").val();
        let val_distrito=$("#distrito").val();
        let val_observacion=$("#observacion").val();
        let saveHistoricoLima= ($('#saveHistoricoLima').is(':checked')) ? '1':'0';
        let saveHistoricoProvincia= ($('#saveHistoricoProvincia').is(':checked')) ? '1':'0';

        let files=$('input[name="rotulo')[0].files;
        console.log(files.length)
        var combo_limaprovincia=$("#limaprovincia").val();
        var val_departamento=$("#departamento").val();
        var val_oficina=$("#oficina").val();
        var val_tracking=$("#tracking").val();
        var val_numregistro=$("#numregistro").val();
        var val_importe=$("#importe").val();
        val_importeEx = val_importe.replace(",", "")
        var importeex = parseFloat(val_importeEx);
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
                'Debe ingresar nombre',
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
            }else if(val_distrito=="") {
                Swal.fire(
                    'Error',
                    'Debe seleccionar un distrito',
                    'warning'
                )
                return;
            }
          }else if(combo_limaprovincia=="P")
          {
            var cont_rotulo=files.length;
            /*if(val_departamento=="")
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
            }else */
            if(val_tracking=="")
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
            }else if(val_importe=="")
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
              fd2.append('importe', val_importe);

            for (let i = 0; i < files.length; i++) {
              fd2.append('rotulo', $('input[type=file][name="rotulo"]')[0].files[0]);
            }

            fd2.append('saveHistoricoProvincia', saveHistoricoProvincia);
          }else if(combo_limaprovincia=="L")
          {
            fd2.append('nombre', val_nombre);
            fd2.append('contacto', val_contacto);
            fd2.append('direccion', val_direccion);
            fd2.append('referencia', val_referencia);
            fd2.append('distrito', val_distrito);
            fd2.append('observacion', val_observacion);

            fd2.append('saveHistoricoLima', saveHistoricoLima);
          }

        }
        var destino= (combo_limaprovincia=="L")? 'LIMA':'PROVINCIA';
          fd2.append('destino', destino);
        var pedidos=[];
        $.each(rows_selected, function(index, rowId){
            console.log("index "+index);
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
        //fd2.append('pedidos', JSON.stringify(pedidos) );
        fd2.append('pedidos', $pedidos );

        console.log("finalizo registro");
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
          }
        });

      });
      /*$(document).on("submit","#formdireccion",function(event){
        event.preventDefault();
        console.log("aa");

      });*/

      $(document).on("click","#droprotulo",function(){
        $("#rotulo").val("");
        $(".drop-rotulo").addClass("d-none");
      });

      $('#modal-historial-provincia').on('show.bs.modal', function (event) {
        tablehistoricoprovincia.destroy();

        let provincialima="PROVINCIA";
        let clienteidprovincia=$("#modal-historial-provincia-a").attr("data-cliente");

        tablehistoricoprovincia=$('#tablaHistorialProvincia').DataTable({
          "bPaginate": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
           "pageLength":5,
          "order": [[ 0, "asc" ]],
          'ajax': {
            url:"{{ route('movimientostablaconciliar') }}",
            'data': { "provincialima":provincialima,"cliente_id":clienteidprovincia},
            "type": "get",
          },
          columns:
          [
            {
              data: 'id',
              name: 'id',
              "visible":true
            },
            {
              data: 'tracking',
              name: 'tracking',
              sWidth:'30%',
              render: function ( data, type, row, meta ) {
                return '<span class="titular">' + data + '</span>';
              }
            },
            {
              data: 'numregistro',
              name: 'numregistro',
              sWidth:'15%',
              render: function ( data, type, row, meta ) {
                return '<span class="banco">' + data + '</span>';
              }
            },
            {
              data: null,
              name: null,
              sWidth:'20%',
              render: function ( data, type, row, meta ) {
                data = data+''+
                      '<button class="btn btn-danger btn-sm button_provincia" data-provincia="'+row.id+'"><i class="fas fa-check-circle"></i></button>'+
                            '';
                return data;
              },
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
          },
        });

      });

      $('#modal-historial-lima').on('show.bs.modal', function (event) {
        tablehistoricolima.destroy();
        let provincialima="LIMA";
        let clienteidlima=$("#modal-historial-lima-a").attr("data-cliente");
        tablehistoricolima=$('#tablaHistorialLima').DataTable({
          "bPaginate": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
           "pageLength":5,
          "order": [[ 0, "asc" ]],
          'ajax': {
            url:"{{ route('sobreenvioshistorial') }}",
            'data': { "provincialima":provincialima,"cliente_id":clienteidlima },
            "type": "get",
          },
          columns:
          [
            {
              data: 'id',
              name: 'id',
              "visible":true,
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: 'nombre',
              name: 'nombre',
              sWidth:'30%',
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: 'recibe',
              name: 'recibe',
              sWidth:'15%',
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: 'direccion',
              name: 'direccion',
              sWidth:'15%',
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: 'referencia',
              name: 'referencia',
              sWidth:'15%',
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: 'distrito',
              name: 'distrito',
              sWidth:'15%',
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: 'observacion',
              name: 'observacion',
              sWidth:'15%',
              render: function ( data, type, row, meta ) {
                return data;
              }
            },
            {
              data: null,
              name: null,
              sWidth:'20%',
              render: function ( data, type, row, meta ) {
                data = data+''+
                      '<button class="btn btn-danger btn-sm button_provincia" data-provincia="'+row.id+'"><i class="fas fa-check-circle"></i></button>'+
                            '';
                return data;
              },
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
          },
        });

      });

      //inicio tabla pedidos
      $('#modal-direccion').on('show.bs.modal', function (event) {

        //desmarcr checkbox
        $("#saveHistoricoLima").prop("checked",false).val("0");
        $("#saveHistoricoProvincia").prop("checked",false).val("0");
        $("#nombre").val("")
        $("#celular").val("")
        $("#direccion").val("")
        $("#referencia").val("")
        $("#distrito").val("").selectpicker("refresh")
        $("#observacion").val("")
        $("#tracking").val("")
        $("#numregistro").val("")
        $("#importe").val("")
        $(".drop-rotulo").addClass("d-none");

        if(!$(".viewpdf").hasClass("d-none"))
        {
          $(".viewpdf").addClass("d-none");
        }
        $("#rotulo").val("");

        var button = $(event.relatedTarget)
        var cliente = button.data('cliente');
        var codigo_ped = button.data('codigo');
          var codigo_asesor = button.data('asesor');

        console.log("cliente "+cliente);
        $("#cliente_id").val(cliente);
        $("#cod_pedido").val(codigo_ped);
          $("#cod_ase").val(codigo_asesor);

        $("#modal-historial-lima-a").attr("data-cliente",cliente);
        $("#modal-historial-provincia-a").attr("data-cliente",cliente);


        console.log("carga modales")
        tabla_pedidos.destroy();

        tabla_pedidos=$('#tablaPrincipalpedidosagregar').DataTable({
          responsive: true,
          "bPaginate": false,
          "bFilter": false,
          "bInfo": false,
          'ajax': {
            url:"{{ route('cargar.pedidosenvioclientetabla') }}",
            'data': { "cliente_id": cliente},
            "type": "get",
          },
          'columnDefs': [ {
            'targets': [0], /* column index */
            'orderable': false, /* true or false */
          }],
          columns:[
            {
                "data": "id",
                'targets': [0],
                'checkboxes': {
                    'selectRow': true
                },
                defaultContent: '',
                orderable: false,
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
              'style': 'multi',
              selector: 'td:first-child'
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
        ajax: "{{ route('envios.estadosobrestabla') }}",
        createdRow: function( row, data, dataIndex){
          //console.log(row);
        },
        rowCallback: function (row, data, index) {
              console.log(data);
              if( data.devuelto!=null ){
                $('td', row).css('color','#cf0a0a');
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
              },"visible":false
          },
          {data: 'codigo', name: 'codigo', },
          {data: 'users', name: 'users', },
          // {
          //   data: 'celulares',
          //   name: 'celulares',
          //   render: function ( data, type, row, meta ) {
          //     return row.celulares+' - '+row.nombres
          //   },
          // },
           {data: 'empresas', name: 'empresas', },
          {data: 'fecha_envio_doc', name: 'fecha_envio_doc',"visible":false },
          {data: 'fecha_envio_doc_fis', name: 'fecha_envio_doc_fis', },
          {data: 'fecha_recepcion', name: 'fecha_recepcion',"visible":false },
          {data: 'destino', name: 'destino', "visible":false },
          {
            data:'direccion',
            name:'direccion',
            "visible":false,
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
          {
              data: 'condicion_envio',
              name: 'condicion_envio',
              render: function ( data, type, row, meta ) {

                  if(row.condicion_envio=='ANULADO'){
                      return 'ANULADO';
                  }else if(row.condicion_envio == 0){
                      return 'ANULADO';
                  }else if(row.condicion_envio == 1){
                      return 'PENDIENTE DE ENVÍO';
                  }else if(row.condicion_envio == 2){
                      return 'EN REPARTO';
                  }else if(row.condicion_envio == 3){
                      return 'ENTREGADO';
                  }else{
                      return data;
                  }
              }
          },
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
            data: 'observacion_devuelto',
            name: 'observacion_devuelto',
            render: function ( data, type, row, meta ) {
              if(data!=null)
              {
                return data;
              }else{
                return ''
              }
            },
            "visible":true
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'20%',
            "visible":false,
            render: function ( data, type, row, meta ) {
              datass='';

              
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



        });
  </script>

@stop
