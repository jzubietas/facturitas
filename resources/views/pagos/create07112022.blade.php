@extends('adminlte::page')

@section('title', 'Agregar Pago')

@section('content_header')
  <h1>Agregar pago</h1>
@stop

@section('content')
  <div class="card">
     {!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!} 
    {{--<form id="formulario" name="formulario" enctype="multipart/form-data">--}}
      <div class="border rounded card-body border-secondary" style="margin: 1%">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor') !!}
            {{--<input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">--}}
            {{--<input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>--}}
            <select name="user_id" class="border form-control selectpicker border-secondary" id="user_id" data-live-search="true">
              {{--<option value="">---- SELECCIONE ASESOR ----</option>--}}
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{$user->identificador}} - {{$user->name}}</option>
              @endforeach

            </select>
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('cliente_id', 'Cliente*') !!}{!! Form::hidden('cliente_id', '',['id' => 'cliente_id']) !!}
              <select name="pcliente_id" class="border form-control selectpicker border-secondary" id="pcliente_id" data-live-search="true">
                {{--<option value="">---- SELECCIONE CLIENTE ----</option>
                  @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}_{{ $cliente->saldo }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                @endforeach
                --}}
              </select>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="form-row">

        <div class="form-group col-lg-6">
            <div class="form-row" >
              <div class="form-group col-lg-10">
                <h2>PAGOS  <b style="font-size:20px"> {!! Form::label('', '') !!}</b></h2>
              </div>
              
              <div class="form-group col-lg-2">
                <a data-target="#modal-add-pagos" id="addpago" data-toggle="modal"><button class="btn btn-primary"><i class="fas fa-plus-circle"></i></button></a>
              </div>
            </div>          
              @error('imagen')
                <small class="text-danger">{{$message}}</small>
              @enderror
            <div class="table-responsive">
              <table id="tabla_pagos" class="table table-striped">
                <thead class="bg-primary">
                  <tr>
                    <th scope="col">#</th> 
                    <th scope="col">T.MOV.</th>
                    <th scope="col">TITULAR</th>               
                    <th scope="col">BANCO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">IMAGEN</th>
                    <th scope="col">MONTO</th>
                    <th scope="col">ACCIÓN</th>
                  </tr>
                </thead>
                <tfoot>
                  <th style="text-align: center">TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <!--<th></th>-->
                  <th colspan="2" style="text-align: right"><h4 id="total_pago">S/. 0.00</h4></th>
                  <th><input type="text" name="total_pago_pagar" requerid value="" id="total_pago_pagar" class="form-control"></th>  
                </tfoot>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="form-group col-lg-6">
            <div class="form-row">
              <div class="form-group col-lg-6">
                <h2>PEDIDOS A PAGAR</h2>
              </div>
              <div class="form-group col-lg-6">
                {{-- <a data-target="#modal-add-pedidos" id="addpedido" data-toggle="modal"><button class="btn btn-info"><i class="fas fa-plus-circle"></i></button></a> --}}  
              </div>
            </div>
            <div class="table-responsive">
              <table id="tabla_pedidos" class="table table-striped" style="text-align: center">
                <thead class="bg-info">
                  <tr>
                    <th scope="col">ITEM</th>
                    {{-- <th scope="col">PEDIDO</th> --}}
                    <th scope="col">CODIGO</th>
                    {{--<th scope="col">MONTO</th>--}}
                    <th scope="col">SALDO</th>
                    <th scope="col">DIFERENCIA</th>
                    {{-- <th scope="col">ACCIÓN</th> --}}
                    {{-- <th scope="col">TOTAL</th> --}}
                      {{-- <th scope="col">ADELANTO</th> --}}
                      <th>TOTAL</th>
                      <th>ADELANTO</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <td>{{--ITEM--}}</td>
                    <td>{{--CODIGO--}}</td>
                    {{--<td>TOTAL MONTO</td>--}}
                    <td>TOTAL SALDO</td>
                    <td>TOTAL DIFERENCIA</td>
                    {{--<td>ACCION</td>--}}
                    {{--<td>TOTAL</td>--}}
                    {{--<td>ADELANTO</td>--}}
                    <td></td>
                    <td></td>
                  </tr>
                </tfoot>
                <tbody style="text-align: center">
                </tbody>
                
              </table>
            </div>
          </div>

          


        </div>
      {{-- MODALS --}}
      @include('pagos.modals.AddPedidos')
      @include('pagos.modals.AddPagos')
    </div>
    <div class="card-footer">
      <div class="form-row">
        
        <div class="form-group col-lg-1">
          <div id="guardar">
            <button id="registrar_pagos" type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
          </div>
        </div>

        <div class="form-group col-lg-1">
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
        </div>

        <div class="form-group col-lg-2">
        </div>

        <div class="form-group col-lg-1 text-right">
          <span style="color: red; font-weight:bold; font-weight: 900; font-size:21px">SALDO S/:</span>
        </div>

        <div class="form-group col-lg-1 text-left">
          <input type="text" name="diferencia" value="" disabled id="diferencia" class="form-control" style="color: red; font-weight:bold;  font-size:21px"> 
        </div>
        
        <div class="form-group col-lg-1">
          <input type="hidden" name="saldo" id="saldo" class="form-control number" placeholder="Saldo a favor...">
        </div>

        <div class="form-group col-lg-3">

        </div>

        <div class="form-group col-lg-1" style="text-align:center;">
          <div id="considerasaldo" class="d-none">
            <a class="btn btn-danger" disabled href="#"><i class="fas fa-times-circle"></i> Saldo</a>
          </div>
        </div>

        <div class="form-group col-lg-1" style="text-align:center;">
          <div id="consideradevolucion" class="d-none">
            <a class="btn btn-danger" disabled href="#"><i class="fas fa-times-circle"></i> Devolucion</a>
          </div>
        </div>

      </div>
    </div>
    {!! Form::close() !!}
  </div>
@stop

@section('css')
<style>
tfoot tr, thead tr {
	background: lightblue;
}
tfoot td {
	font-weight:bold;
}
</style>
@stop

@section('js')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script>  

    $("#guardar").hide();
    $("#addpedido").hide();
    $("#addpago").hide();
    $("#pcliente_id").change(mostrarBotones);

    function mostrarBotones() {
      $("#addpedido").show();
      $("#addpago").show();
    }    

    // CARGAR PEDIDOS DE CLIENTE SELECCIONADO

    var tabla_pedidos=null;
    var total_pedido=0.00;
    var total_pago=0.00;
    //$(document).ready(function () {

      function eliminarPa(index) {
        total_pago = total_pago - subtotal_pago[index];
        $("#total_pago").html("S/. " + total_pago.toLocaleString("en-US"));
        $("#total_pago_pagar").val(total_pago);
        $("#filasPa" + index).remove();
        evaluarPa();
      }

      function limpiarPa() {
        $("#pmonto").val("");
        $("#pbanco").val('').change();
        $("#pfecha").val("");
        $("#pimagen").val("");
      }

      /*function evaluarPa() {
          if (total_pago > 0) {//total_pedido > 0 && 
            $("#guardar").show();
          } else {
            $("#guardar").hide();
          }
        }*/

       

      $(document).ready(function() {

        /* inicio mi carga de table  sin datos*/
        $('#tabla_pagos').DataTable().clear().destroy();

        

        $(document).on("submit","#formulario",function(event){
          event.preventDefault();
          validarFormulario();
        });

        /*$(document).on("submit","#formulario",function(event){
          event.preventDefault();
          console.log("abrir");
          var fd = new FormData();

          fd.append("total_pedido_pagar", $("#total_pedido_pagar").val());
          fd.append("total_pedido", $("#total_pedido").val());
          fd.append("total_pago_pagar", $("#total_pago_pagar").val());
          fd.append("total_pago", $("#total_pago").val());

          var total_pedido_pagar = $("#total_pedido_pagar").val();
          var total_pedido = document.getElementById('total_pedido').value;
          var total_pago_pagar = document.getElementById('total_pago_pagar').value;
          var total_pago = document.getElementById('total_pago').value;
          var falta = total_pedido_pagar - total_pago_pagar;
          falta = falta.toFixed(2);
          imagen = document.getElementsByName("imagen[]");
          var img = [];
         
          let files=$('input[name="adjunto[]');
         
          for(var i=0;i<imagen.length;i++){
              img.push(imagen[i].value);
          }

          if(img.includes('') == true)
          {
            Swal.fire(
                'Error',
                'Seleccione una imagen para cada pago agregado',
                'warning'
              )
          }

          $('input[name="nombre_empresa[]"]').each(function(){
            fd.append("nombre_empresa[]", this.value);
          });


        });*/

        /*$("#formulario").submit(function(event){

          event.preventDefault();
          console.log("abrir")
        
        var fd = new FormData();
        }*/

        tabla_pagos=$('#tabla_pagos').DataTable({
            "bPaginate": false,
              "bFilter": false,
              "bInfo": false,
              columns: 
              [
                {
                  data: 'item', 
                  name: 'item',
                  sWidth:'10%', 
                },
                {
                  data: 'movimiento', 
                  name: 'movimiento',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="tipomovimiento['+row.item+']" value="' + data + '"><span class="tipomovimiento">' + data + '</span></td>';
                  }
                },
                {
                  data: 'titular', 
                  name: 'titular',
                  sWidth:'10%',
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="titular['+row.item+']" value="' + data + '"><span class="titular">' + data + '</span></td>';
                  }
                },
                {
                  data: 'banco', 
                  name: 'banco',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="banco['+row.item+']" value="' + data + '"><span class="banco">' + data + '</span></td>';
                  }
                },
                {
                  data: 'fecha', 
                  name: 'fecha',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="fecha['+row.item+']" value="' + data + '"><span class="fecha">' + data + '</span></td>';
                  }
                },
                {
                  data: 'imagen', 
                  name: 'imagen',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    //return '<input type="hidden" name="fecha['+row.item+']" value="' + data + '"><span class="fecha">' + data + '</span></td>';
                    //<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/>
                    //<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/>
                    var str="storage/adjuntos/"+data;
                    var urlimage = '{{ asset(":id") }}';

                    urlimage = urlimage.replace(':id', str);
                    data = '<input type="hidden" name="imagen['+row.item+']" value="' + data + '"></td><img src="'+urlimage+'" alt="'+urlimage+'" height="200px" width="200px" class="img-thumbnail">';
                          //'<input type="file" id="imagen'+row.item+'" name="imagen['+row.item+']" accept="image/*" style="width:150px;">';
                    return data

                  }
                },
                {
                  data: 'monto', 
                  name: 'monto',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="monto['+row.item+']" value="' + data + '"><span class="monto">' + data + '</span></td>';
                  }
                },
                {
                  data: 'accion', 
                  name: 'accion',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<button type="button" class="btn btn-danger btn-sm remove"><i class="fas fa-trash-alt"></i>'+row.item+'</button>';
                  }
                },
              ],
              "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = 4;api.columns().nodes().length;
                var j = 2;

                //para footer  monto
                var pageTotal = api
                      .column( 6, { page: 'current'} )
                      .data()
                      .reduce( function (a, b) {
                          return Number(a) + Number(b);
                      }, 0 );
                // Update footer
                $( api.column( 6 ).footer() ).html('<input type="hidden" name="total_pago" id="total_pago" value="'+pageTotal+'"/>'+pageTotal);

                $( api.column( 7 ).footer() ).html('<input type="hidden" name="total_pago_pagar" id="total_pago_pagar" value="'+pageTotal+'" />'+pageTotal);

                //console.log("sumar nuevamente para total en pagos");
                //console.log("habia en diferencia "+$("#diferencia").val() );
                //console.log("sumare "+pageTotal);
                $("#diferencia").val(pageTotal);
                $("#saldo").val(0.00);
                let uncliente=$("#pcliente_id").val();
                if(uncliente!='')
                {
                  //$('#tabla_pedidos').DataTable().clear().destroy();
                  //$("#tabla_pedidos").DataTable().ajax.reload();
                }


                //$("#tabla_pedidos").DataTable().ajax.reload();
                //tabla_pedidos.Databale().ajax.reload();
                //calcular
                diferenciaFaltante();

              },
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

        function sumatotalpagos()
        {
          var sisuma=parseFloat(0.00);
          var nuevosuma=parseFloat(0.00);

          $('#tabla_pagos > tbody  > tr').each(function(index,tr) {
              console.log(index+" posicion");
              console.log(tr+" tr");
              //var idfila=$(this).find("td").eq(0).html();//fila idpedido
              console.log( $(this).find("td").eq(6).html()) ;
              nuevosuma=parseFloat($(this).find("td").eq(6).find(".monto").text());
              console.log("nuevosuma "+nuevosuma)
              sisuma=(sisuma)+(nuevosuma);
              console.log("sumo "+sisuma)

          });
          return sisuma

        }

        function sumatotalpedidos()
        {
          var sisumapedido=parseFloat(0.00);
          var nuevosumapedido=parseFloat(0.00);
          $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
              console.log(index+" posicion");
              console.log(tr+" tr");
              console.log( $(this).find("td").eq(2).html()) ;
              nuevosumapedido=parseFloat( $(this).find("td").eq(2).find(".numbersaldo").text() );
              console.log("nuevosuma "+nuevosumapedido);
              sisumapedido=(sisumapedido)+(nuevosumapedido);
              console.log("sumo "+sisumapedido)
          });
          return sisumapedido
        }

        function evaluarPa() {
          console.log("sumatotalpagos >1")
          var total_pago=sumatotalpagos();
          console.log("evaluarPa "+total_pago)
          if (total_pago > 0) {//total_pedido > 0 && 
            $("#guardar").show();
          } else {
            $("#guardar").hide();
          }
        }


        $('#tabla_pagos').on('click', '.remove', function() {
          //si elimino pago, recargo datatable pedidos y diferencia vuelvo a calcular con la suma de pagos
          var table = $('#tabla_pagos').DataTable();
          var row = $(this).parents('tr');
          var subtotal=row.find("td").eq("6").find("span.monto").text();
          console.log(subtotal);
          let diff=$("#diferencia").val();
      
          if ($(row).hasClass('child')) {
            table.row($(row).prev('tr')).remove().draw();
            console.log("sumatotalpagos >2")
            var sumapago=sumatotalpagos();
            console.log("sumapagos v1 "+sumapago)
          } else {
            table
              .row($(this).parents('tr'))
              .remove()
              .draw();
            console.log("sumatotalpagos <3")
            var sumapago=sumatotalpagos();
            
            console.log("sumapagos v2 "+sumapago)

            $("#total_pago").html("S/. " + sumapago.toLocaleString("en-US"));
            $("#total_pago_pagar").val(sumapago);
            $("#diferencia").val(sumapago);
            $("#pcliente_id").trigger("change");

              //total_pago = diff*1 - subtotal*1;
              //console.log(total_pago);
              //$("#total_pago").html("S/. " + total_pago.toLocaleString("en-US"));
              //$("#total_pago_pagar").val(total_pago);
              
              evaluarPa();
            
          }
      
        });
              

        tabla_pedidos=$('#tabla_pedidos').DataTable({
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
            },
            {
              data: 'diferencia'
            },
            {
              data: null
            },{
              data: null
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

        $(document).on("change","#user_id",function(){
          console.log("123");
          var uid=$(this).val();
          $.ajax({
                url: "{{ route('clientescreatepago') }}?user_id=" + uid,
                method: 'GET',
                success: function(data) {
                  console.log(data.html);
                  $('#pcliente_id').html(data.html);
                  $("#pcliente_id").selectpicker("refresh");//addClass("your-custom-class")


                }
              });

        });

        $("#user_id").trigger("change");


        $(document).on("change","#pcliente_id",function(){

          $('#tabla_pagos').DataTable().clear().destroy();
          tabla_pagos=$('#tabla_pagos').DataTable({
            "bPaginate": false,
              "bFilter": false,
              "bInfo": false,
              columns: 
              [
                {
                  data: 'item', 
                  name: 'item',
                  sWidth:'10%', 
                },
                {
                  data: 'movimiento', 
                  name: 'movimiento',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="tipomovimiento['+row.item+']" value="' + data + '"><span class="tipomovimiento">' + data + '</span></td>';
                  }
                },
                {
                  data: 'titular', 
                  name: 'titular',
                  sWidth:'10%',
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="titular['+row.item+']" value="' + data + '"><span class="titular">' + data + '</span></td>';
                  }
                },
                {
                  data: 'banco', 
                  name: 'banco',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="banco['+row.item+']" value="' + data + '"><span class="banco">' + data + '</span></td>';
                  }
                },
                {
                  data: 'fecha', 
                  name: 'fecha',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="fecha['+row.item+']" value="' + data + '"><span class="fecha">' + data + '</span></td>';
                  }
                },
                {
                  data: 'imagen', 
                  name: 'imagen',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    //return '<input type="hidden" name="fecha['+row.item+']" value="' + data + '"><span class="fecha">' + data + '</span></td>';
                    //<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/>
                    //<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/>
                    var str="storage/adjuntos/"+data;
                    var urlimage = '{{ asset(":id") }}';

                    urlimage = urlimage.replace(':id', str);
                    data = '<input type="hidden" name="imagen['+row.item+']" value="' + data + '"></td><img src="'+urlimage+'" alt="'+urlimage+'" height="200px" width="200px" class="img-thumbnail">';
                          //'<input type="file" id="imagen'+row.item+'" name="imagen['+row.item+']" accept="image/*" style="width:150px;">';
                    return data

                  }
                },
                {
                  data: 'monto', 
                  name: 'monto',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<input type="hidden" name="monto['+row.item+']" value="' + data + '"><span class="monto">' + data + '</span></td>';
                  }
                },
                {
                  data: 'accion', 
                  name: 'accion',
                  sWidth:'10%', 
                  render: function ( data, type, row, meta ) {
                    return '<button type="button" class="btn btn-danger btn-sm remove"><i class="fas fa-trash-alt"></i>'+row.item+'</button>';
                  }
                },
              ],
              "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = 4;api.columns().nodes().length;
                var j = 2;

                //para footer  monto
                var pageTotal = api
                      .column( 6, { page: 'current'} )
                      .data()
                      .reduce( function (a, b) {
                          return Number(a) + Number(b);
                      }, 0 );
                // Update footer
                $( api.column( 6 ).footer() ).html('<input type="hidden" name="total_pago" id="total_pago" value="'+pageTotal+'"/>'+pageTotal);

                $( api.column( 7 ).footer() ).html('<input type="hidden" name="total_pago_pagar" id="total_pago_pagar" value="'+pageTotal+'" />'+pageTotal);

                //console.log("sumar nuevamente para total en pagos");
                //console.log("habia en diferencia "+$("#diferencia").val() );
                //console.log("sumare "+pageTotal);
                

              },
              "initComplete": function(settings, json) {

                total_pago=sumatotalpagos();
                if(isNaN(total_pago) )
                {
                  total_pago=0.00;

                }else{
                  total_pago=total_pago;
                }

                console.log("reseteo diferencia "+total_pago);
                $("#diferencia").val(total_pago);
                $("#saldo").val(0.00);
                let uncliente=$("#pcliente_id").val();
                if(uncliente!='')
                {
                  //$('#tabla_pedidos').DataTable().clear().destroy();
                  //$("#tabla_pedidos").DataTable().ajax.reload();
                }
                diferenciaFaltante();

                /*console.log("reseteo diferencia "+total_pago);
                $("#diferencia").val(total_pago);
                $("#saldo").val(0.00);*/
                //let uncliente=$("#pcliente_id").val();
                //if(uncliente!='')
                //{
                  //$('#tabla_pedidos').DataTable().clear().destroy();
                  //$("#tabla_pedidos").DataTable().ajax.reload();
                //}
                //diferenciaFaltante();


                //$("#tabla_pedidos").DataTable().ajax.reload();
                //tabla_pedidos.Databale().ajax.reload();
                //calcular
                

                
                //console.log("total_pedido "+total_pedido);

                //alert( 'DataTables has finished its initialisation.' );
              },
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
        //$("#diferencia").val(0)

          //$('#tabla_pagos').on('click', '.remove'

          tabla_pedidos.destroy();
          //$('#tabla_pedidos').DataTable().clear().destroy();
          datosCliente = $(this).val().split('-');//  document.getElementById('pcliente_id').value.split('_');
          cliente_id = datosCliente[0];
          saldo = datosCliente[1];
          //$("#diferencia").prop("disabled",null)
          //diferencia=$("#diferencia").val();
          //$("#diferencia").prop("disabled",true)
          //console.log("diferencia en change pcliente_id");
          //console.log(diferencia);
          $("#diferencia").prop("disabled",false);
          let diferenciaval=$("#diferencia").val();
          //console.log(diferenciaval);
          $("#cliente_id").val(cliente_id);
          $("#saldo").val(saldo);
          
          
          tabla_pedidos=$('#tabla_pedidos').DataTable({
              "bPaginate": false,
              "bFilter": false,
              "bInfo": false,
              'ajax': {
                url:"{{ route('cargar.pedidosclientetabla') }}",					
                'data': { "cliente_id": $(this).val(),"diferencia":$("#diferencia").val()}, 
                "type": "get",
              },
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                  $(nRow).attr('id', aData["id"]);
              },
              columns: [
                {
                  data: 'id', 
                  name: 'id',
                  render:function(data,type,row,meta){
                    if(row.id<10){
                      return '<input type="hidden" name="pedido_id['+data+']" value="' + data + '">PED000' + data + '</td>';
                    }else if(row.id<100){
                      return '<input type="hidden" name="pedido_id['+data+']" value="' + data + '">PED00' + data + '</td>';
                    }else if(row.id<1000){
                      return '<input type="hidden" name="pedido_id['+data+']" value="' + data + '">PED0' + data + '</td>';
                    }else{
                      return '<input type="hidden" name="pedido_id['+data+']" value="' + data + '">PED' + data + '</td>';
                    } 
                    //return '<input type="hidden" name="pedido_id[]" value="' + data + '">PED000' + data + '</td>';
                  }
                },
                {data: 'codigo', name: 'codigo',},
                /*{
                  data: 'total', 
                  name: 'total',
                  render:function(data,type,row,meta){
                      return '<input type="hidden" name="numbermonto[]" value="' + data + '">' + data + '</td>';
                  },
                  "visible": true
                },*/
                {
                  data: 'saldo', 
                  name: 'saldo',
                  render:function(data,type,row,meta){
                      return '<input type="hidden" name="numbersaldo['+row.id+']" value="' + data + '"><span class="numbersaldo">' + data + '</span></td>';
                  },
                  "visible": true
                },
                {
                  data: 'diferencia', 
                  name: 'diferencia',
                  render:function(data,type,row,meta){
                      return '<input type="hidden" name="numberdiferencia['+row.id+']" value="' + data + '"><span class="numberdiferencia">' + data + '</span></td>'+
                       '<input type="hidden" name="numbertotal['+row.id+']" value="' + data + '"><span class="numbertotal"></span></td>';
                  },
                  "visible": true
                },
                {
                    "data": null,
                    "render": function ( data, type, row, meta ) {
                        //para total pago
                        //return '<input type="checkbox" onclick="onclickradiototal('+row.id+')" class="form-control radiototal" name="totaladelanto">';//row.Firstname + ' ' + row.Lastname;  // Column will display firstname lastname
                        return '<input type="checkbox" disabled class="form-control radiototal" name="checktotal['+row.id+']" value="0">';//row.Firstname + ' ' + row.Lastname;  // Column will display firstname lastname
                        //'+row.id+'
                    }
                },
                {
                    "data": null,
                    "render": function ( data, type, row, meta ) {
                        //para adelanto
                      //return '<input type="checkbox" onclick="onclickradioadelanto('+row.id+')" class="form-control radioadelanto" name="totaladelanto">'//row.Firstname + ' ' + row.Lastname;  // Column will display firstname lastname
                      return '<input type="checkbox" disabled class="form-control radioadelanto" name="checkadelanto['+row.id+']" value="0">'//row.Firstname + ' ' + row.Lastname;  // Column will display firstname lastname
                      //'+row.id+'
                    }
                }
              ],
              "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = 4;api.columns().nodes().length;
                var j = 2;

                //para footer  monto
                var pageTotal = api
                      .column( 2, { page: 'current'} )
                      .data()
                      .reduce( function (a, b) {
                          return Number(a) + Number(b);
                      }, 0 );
                // Update footer
                $( api.column( 2 ).footer() ).html('<input type="hidden" name="total_pedido" id="total_pedido" value="'+pageTotal+'"/>'+pageTotal);

                var pageSaldo = api
                      .column( 3, { page: 'current'} )
                      .data()
                      .reduce( function (a, b) {
                          return Number(a) + Number(b);
                      }, 0 );
                // Update footer
                $( api.column( 3 ).footer() ).html('<input type="hidden" name="total_pedido_pagar" id="total_pedido_pagar" value="'+pageSaldo+'" />'+pageSaldo);

              },
              "initComplete": function(settings, json) {
                total_pedido=sumatotalpedidos();
                console.log("total_pedido "+total_pedido);

                //alert( 'DataTables has finished its initialisation.' );
              },
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

            /*$(document).on('.radiototal','click', function (e) {
              console.log("aaaa")
            });*/


            /*$(document).on('.radiototal','mousedown', function (e) {
              e.preventDefefault();
              console.log("aaaa")              
            });*/
            /*$(document).on("mousedown",'#tabla_pedidos input[type="checkbox"]', function (event) {
              event.preventDefault();
              event.stopPropagation();
              console.log("checkBox");
              
              var cell = $(this).closest('td');
              
              var data = tabla_pedidos.row( cell ).data();
              console.log('Using TD:', data)

              var cell = $(this).parent();
              
              var data = tabla_pedidos.row( cell ).data();
              console.log('Using Parent:', data)

              var row = $(this).closest('tr');
              
              var data = tabla_pedidos.row( row ).data();
              console.log('Using TR:', data)
              
              console.log('Checked name from TR:', data[0]);
              return false;
              
          });*/

          $(document).on("click",".radiototal",function(event){
            event.preventDefault();
          });

          $(document).on("click",".radioadelanto",function(event){
            event.preventDefault();
          });

          $(document).on("mousedown",".radiototal",function(event){
            event.preventDefault();
            if($(this).prop("checked") == true){
              console.log("marcado")
              $(this).prop("checked",false).val("0")////
              //revertir
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
                  if(montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }

                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  console.log(filedata)
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }

                  console.log("desmarco saldo "+pedidosaldo+" monto "+montopagos)

                  montopagos=parseFloat(montopagos+pedidosaldo);
                  console.log("diferencia "+montopagos);
                  $("#diferencia").val(montopagos);
                  
                  $(this).closest('tr').find("td").eq(3).find(":input").val(pedidosaldo.toFixed(2));
                  $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text(pedidosaldo.toFixed(2));
                  let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                  let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                  let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                  $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);

                  $(this).closest('tr').find(".radioadelanto").prop("disabled",false);
                  $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      //if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=parseFloat($(this).find("td").eq(3).html());//saldo
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());
                        console.log("saldo fila "+idfila+" es "+saldofila);
                        var radiototalfila=$(this).find("td").eq(4).find("input").prop("checked");//check radiototal
                        console.log("checktotal fila "+idfila+" es "+( (radiototalfila)? 'si':'no') );
                        var radiosaldofila=$(this).find("td").eq(5).find("input").prop("checked");
                        console.log("checksaldo fila "+idfila+" es "+( (radiosaldofila)? 'si':'no') );
                        if(radiototalfila || radiosaldofila)
                        {
                          console.log("radiofila  "+index+" total o saldo esta checkedado")
                        }
                        if(!radiototalfila && !radiosaldofila){
                          console.log("saldofila = "+saldofila + " y montopagos="+montopagos);//600/100
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo "+saldofila+" es menor igual a monto "+montopagos+", bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",true);
                          }else{
                            console.log("saldo "+saldofila+" es mayor a monto "+montopagos+", bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }
                      }
                      
                    });
              //fin revertir

            }else if($(this).prop("checked") == false){
              console.log("no marcado");///aca falla cuando el monto es menor que el saldo
              
              //validar si sumar depende el saldo y monto
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
                  if(montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }

                  console.log("monto es "+montopagos)

                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  console.log(filedata)
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }

                  console.log("montopagos "+montopagos+"  saldo es "+pedidosaldo)

                  if(montopagos>=pedidosaldo)
                  {
                    //acaqueda el codigo
                    $(this).prop("checked",true).val("1")////aqui valida////////data aqui check para diferencia
                    montopagos=parseFloat(montopagos-pedidosaldo);
                    console.log("diferencia "+montopagos);
                    $("#diferencia").val(montopagos.toFixed(2));
                    /////
                    console.log("aqui debo cambiar el valor de input y span de columna diferencia");
                    $(this).closest('tr').find("td").eq(3).find(":input").val("0.00");
                    $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text("0.00");
                    let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                    let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                    let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                    $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
                    //$(this).closest('tr').find(".numberdiferencia").text("0.00");
                    //$(this).find("td").eq(3).find(":input").val("0.00");///aqui me quede
                    //$(this).find("td").eq(3).find("span").text("0.00");///aqui me quede

                    $(this).closest('tr').find(".radioadelanto").prop("disabled",true);
                    console.log("nuevo montogeneral "+$("#diferencia").val());
                    $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      //if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=parseFloat($(this).find("td").eq(3).html());//saldo
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());//saldo
                        console.log("saldo fila "+idfila+" es "+saldofila);
                        var radiototalfila=$(this).find("td").eq(4).find("input").prop("checked");//check radiototal
                        console.log("checktotal fila "+idfila+" es "+( (radiototalfila)? 'si':'no') );
                        var radiosaldofila=$(this).find("td").eq(5).find("input").prop("checked");
                        console.log("checksaldo fila "+idfila+" es "+( (radiosaldofila)? 'si':'no') );
                        if(radiototalfila || radiosaldofila)
                        {
                          console.log("radiofila  "+index+" total o saldo esta checkedado")
                        }
                        if(!radiototalfila && !radiosaldofila){
                          console.log("saldofila = "+saldofila + " y montopagos="+montopagos);
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo menor igual a monto, bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",true);
                          }else{
                            console.log("saldo mayor monto, bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }
                      }
                      
                    });
                    
                  }else{
                    console.log("si monto es menor a pedidos")
                    $(this).prop("checked",true).val("1");
                  }
              //fin validar

            }
            return;
           
          });

          //para saldo
          //inicio
          $(document).on("mousedown",".radioadelanto",function(event){
            event.preventDefault();
            if($(this).prop("checked") == true){
              console.log("marcado")
              $(this).prop("checked",false).val("0")//////revertir              
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));//0
                  /*if(montopagos==0 || montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }*/
                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }

                  console.log("desmarco saldo "+pedidosaldo+" monto "+montopagos);

                  /////falta aqui analizis
                  //montopagos=parseFloat(montopagos+pedidosaldo);

                  //551.20---356.00
                  console.log("guardasaldo "+guardasaldo);
                  montopagos=(guardasaldo);
                  $("#diferencia").val(montopagos);

                  $(this).closest('tr').find("td").eq(3).find(":input").val(pedidosaldo.toFixed(2));
                  $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text(pedidosaldo.toFixed(2));
                  let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                  let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                  let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                  $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
                  //$(this).closest('tr').find(".radioadelanto").prop("disabled",false);

                  //revertir pago reviso todo otra vez
                  $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=$(this).find("td").eq(3).html();//saldo/551.20
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());
                        console.log("saldo fila "+idfila+" es "+saldofila);//551.20
                        var radiototalfila=$(this).find("td").eq(4).find("input").prop("checked");//check radiototal
                        console.log("checktotal fila "+idfila+" es "+( (radiototalfila)? 'si':'no') );//
                        var radiosaldofila=$(this).find("td").eq(5).find("input").prop("checked");
                        console.log("checksaldo fila "+idfila+" es "+( (radiosaldofila)? 'si':'no') );
                        /*if(radiototalfila || radiosaldofila)
                        {
                          console.log("radiofila  "+index+" total o saldo esta checkedado")
                        }
                        if(!radiototalfila && !radiosaldofila){
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo menor igual a monto, bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }else{
                            console.log("saldo mayor monto, bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }*/
                      }
                      
                    });
              //fin revertir

            }else if($(this).prop("checked") == false){
              console.log("no marcado adelanto");///aca falla cuando el monto es menor que el saldo
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
                  if(montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }
                  console.log("montopagos "+montopagos);
                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }
                  //sol si saldo es mayor al monto
                  console.log(montopagos +" < "+pedidosaldo )
                  if(montopagos<pedidosaldo)
                  {
                    $(this).prop("checked",true).val("1")////aqui valida
                    console.log("marco el check adelanto")
                    guardasaldo=$("#diferencia").val();//356 guardo
                    let montopagosante=montopagos;
                    montopagos=(0).toFixed(2);
                    console.log("diferencia "+montopagos);
                    $("#diferencia").val(montopagos);

                    console.log("aqui cuando es adelanto y la diferencia debe ser 0 y guardarlo");
                    console.log("pedidosaldo "+pedidosaldo+" montopagos saldo "+montopagosante);
                    //restar saldo menos monto que queda general
                    let montoqueda=parseFloat(pedidosaldo-montopagosante);
                    console.log("montoqueda "+montoqueda)
                    $(this).closest('tr').find("td").eq(3).find(":input").val(montoqueda.toFixed(2));///diferencia
                    $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text(montoqueda.toFixed(2));///diferencia
                    let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                    let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                    let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                    $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
                    //$(this).closest('tr').find(".radioatotal").prop("disabled",true);
                    //recorido defilas distintas al actual
                    $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=$(this).find("td").eq(3).html();//saldo
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());
                        console.log("saldo fila "+idfila+" es "+saldofila);
                        var radiototalfila=$(this).find("td").eq(4).find("input").prop("checked");//check radiototal
                        console.log("checktotal fila "+idfila+" es "+( (radiototalfila)? 'si':'no') );
                        var radiosaldofila=$(this).find("td").eq(5).find("input").prop("checked");
                        console.log("checksaldo fila "+idfila+" es "+( (radiosaldofila)? 'si':'no') );
                        if(radiototalfila || radiosaldofila)
                        {
                          console.log("radiofila  "+index+" total o saldo esta checkedado")
                        }
                        if(!radiototalfila && !radiosaldofila){
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo menor igual a monto, bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }else{
                            console.log("saldo mayor monto, bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }
                      }
                      
                    });
                    
                  }
                  
              //fin validar

            }
            return;
           
          });

          //fin checkbox salo



        });

        ///para saldo


        /*$("#pcliente_id").change(function() {
          
        });*/

        $(document).on("change",'#diferencia',function(e){
          console.log("logica de diferencia");
          console.log($(this).val());
          console.log("actualizar tabla de pedidos a pagar")
        });

        $(document).on("click",'#add_pedido',function(){
          agregarPedido();

        });

        function Remove_options(Pedido_delete)
        {
          $("#ppedido_id option[value='" + Pedido_delete +"']").remove();
        }

        function diferenciaFaltante() {
          //diferencia = total_pedido - total_pago;
          //desmarcar todos los check total y adelanto
          //change del cliente
          
          console.log("total_pago "+total_pago+"  total_pedido "+total_pedido)
          diferencia = total_pago ;//- total_pedido;
          console.log('diferencia en fx diferenciaFaltante');
          console.log(diferencia);
         
          $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
            console.log(index+" posicion");
            //var saldofila=$(this).find("td").eq(3).html();
            console.log(  $(this).find("td").eq(2).html()  )
            var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());
            console.log(saldofila)
            
            console.log("resta1 "+diferencia);
            console.log("resta2 "+saldofila);
            let restogeneral=(parseFloat(diferencia)-parseFloat(saldofila)).toFixed(2);
            console.log("diferencia por fila "+restogeneral);
            if(saldofila<=total_pago)
            {
              $(this).find("td").eq(4).find("input").prop("disabled",false);
            }else{
              $(this).find("td").eq(5).find("input").prop("disabled",false);
            }

            /*if(restogeneral>0){
              console.log("bloqueo  1")
              $(this).find("td").eq(4).find("input").prop("disabled",false);
            }*/
          });

          //tabla_pedidos.ajax.reload();
          $("#diferencia").val(diferencia.toLocaleString("en-US"));
        }

        function eliminarPe(index) {
          total_pedido = total_pedido - subtotal_pedido[index];
          $("#total_pedido").html("S/. " + total_pedido.toLocaleString("en-US"));
          $("#total_pedido_pagar").val(total_pedido);
          $("#filasPe" + index).remove();
          evaluarPe();
        }

        ////////
        
        ///////

        $(document).on("keyup",'input.number',function(event){
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

        function limpiarPe() {
          $("#ppedido_id").val("");
          $("#total_pedido").val("");
        }


      $(document).ready(function () {

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

        
        $(document).on("click","#add_pago",function(){
              if ($('#pbanco').val() == ''){
                  Swal.fire(
                    'Error',
                    'Seleccione banco ',
                    'warning'
                  )
              }else if ($('#tipotransferencia').val() == '') {
                Swal.fire(
                  'Error',
                  'Seleccione tipo de transferencia',
                  'warning'
                )
              }else if ($('#titulares').val() == '') {
                Swal.fire(
                  'Error',
                  'Seleccione titular',
                  'warning'
                )
              }else if ($('#pmonto').val() == '') {
                Swal.fire(
                  'Error',
                  'Ingrese monto',
                  'warning'
                )
              }
              else if ($('#pfecha').val() == ''){
                Swal.fire(
                  'Error',
                  'Seleccione la fecha',
                  'warning'
                )
              }
              else {
                //$("#pcliente_id").trigger("change");
                //desmarcar total y adelanto
                $("#tabla_pedidos tbody tr .radiototal").prop("checked",false).trigger("change");
                $("#tabla_pedidos tbody tr .radioadelanto").prop("checked",false).trigger("change");
                //$("#tabla_pedidos tbody tr td .radiototal").reset();
                //$("#tabla_pedidos tbody tr .radioadelanto").prop("checked",false).checkboxradio('refresh');
                //$("#tabla_pedidos tbody tr td .radioadelanto").reset();

                //cargar table de
                //validar imagen cargada
                let files=$('#pimagen');
                var totalfilescarga = $('#pimagen').get(0).files.length;

                if(files.length!=totalfilescarga)
                {
                  Swal.fire(
                    'Error',
                    'Debe ingresar la imagen adjunta',
                    'warning'
                  )
                  return false;
                }else{
                  //imagen cargada
                  deuda = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
                  pagado = !isNaN($('#pstock').val()) ? parseInt($('#pstock').val(), 10) : 0;

                  

                  agregarPago();
                }
              }
        });

        

        /*$('#add_pago').click(function() {
          
        });*/
      });
        ///
        /*$(document).on("click","#tabla_pedidos input.radiototal",function(e){
          e.preventDefault();
          console.log("aaaa");
          var checked=$(this).is('checked');
          if(checked)
          {
            console.log("marcado");
          }else{
            console.log("desmarcado");
          }

        });*/
        
        /*$("").click(function(){
          var checked=$(this).is('checked');
          if(checked)
          {
            console.log("marcado");
          }else{
            console.log("desmarcado");
          }
        });*/

          //eventos de datatable
        /*$(document).on('click','.radiototal', function(e){
          e.preventDefault();
        });*/

        /*$(document).on('mousedown keydown ','.radiototal', function(e){
          e.preventDefault();
          e.stopPropagation();

          $checktotal=$(this);
          var checked=$(this).is('checked');
          if(checked){
            console.log("checked");
            $(this).trigger("change");
          }else{
            console.log("no");
            $(this).trigger("change");
            
          }
        });*/

          /*$checktotal=$(this);
          var checked=$(this).is('checked');
          if(checked){
            console.log("checked")
          }else{
            console.log("no")

            $checktotal.prop('checked', true);

            let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
            if(montopagos==0 || montopagos==null || isNaN(montopagos)){
              console.log("no hay pagos ingresados");
              return false;
            }

            console.log("estaba sin marcar");
            console.log("no estaba marcado radiototal");            

            let filedata=tabla_pedidos.row($(this).closest('tr')).data();
            console.log(filedata)
            let pedidosaldo=parseFloat(filedata.saldo);
            console.log("saldo "+pedidosaldo);
            console.log("montopagos "+montopagos);
            
            if(pedidosaldo<=montopagos)
            {
              console.log("pedido "+pedidosaldo+"   < pago "+montopagos);
              montopagos=(montopagos-pedidosaldo).toFixed(2);
              console.log("diferencia "+montopagos);
              $("#diferencia").val(montopagos);
              
              $(this).closest('tr').find(".radioadelanto").prop("disabled",true);
            }
          }
          return false;
          let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
          if(montopagos==0 || montopagos==null || isNaN(montopagos)){
            console.log("no hay pagos ingresados");
            return false;
          }
          console.log("monto "+montopagos)
          if(checked){            
            console.log("estaba marcado");
            $checktotal=$(this);

            let filedata=tabla_pedidos.row($(this).closest('tr')).data();
            console.log(filedata)
            let pedidosaldo=parseFloat(filedata.saldo);
            console.log("saldo "+pedidosaldo);
            console.log("montopagos "+montopagos);
            console.log("revertir");
            {              
              montopagos=(montopagos+pedidosaldo).toFixed(2);
              console.log("diferencia "+montopagos);
              $("#diferencia").val(montopagos);
              $(this).closest('tr').find(".radioadelanto").prop("disabled",false);
              $checktotal.prop("checked",false).attr("checked", false).removeAttr('checked');
            }

          }else{
            console.log("estaba sin marcar");
            console.log("no estaba marcado radiototal");
            $checktotal=$(this);

            let filedata=tabla_pedidos.row($(this).closest('tr')).data();
            console.log(filedata)
            let pedidosaldo=parseFloat(filedata.saldo);
            console.log("saldo "+pedidosaldo);
            console.log("montopagos "+montopagos);

            if(pedidosaldo<=montopagos)
            {
              console.log("pedido "+pedidosaldo+"   < pago "+montopagos);
              montopagos=(montopagos-pedidosaldo).toFixed(2);
              console.log("diferencia "+montopagos);
              $("#diferencia").val(montopagos);
              $(this).closest('tr').find(".radioadelanto").prop("disabled",true);
            }

          }

        });*/

    
        /*$(document).on( 'click', '#tabla_pedidos input.radiototal', function (e) {
          e.preventDefault();
          var checked=$(this).is('checked');
          let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
          if(montopagos==0 || montopagos==null || isNaN(montopagos)){
            console.log("no hay pagos ingresados");
            return false;
          }
          console.log("monto "+montopagos)
          if(checked){
            
            console.log("estaba marcado");
            $checktotal=$(this);

            let filedata=tabla_pedidos.row($(this).closest('tr')).data();
            console.log(filedata)
            let pedidosaldo=parseFloat(filedata.saldo);
            console.log("saldo "+pedidosaldo);
            console.log("montopagos "+montopagos);
            console.log("revertir");

            
            {
              
              montopagos=(montopagos+pedidosaldo).toFixed(2);
              console.log("diferencia "+montopagos);
              $("#diferencia").val(montopagos);
              $(this).closest('tr').find(".radioadelanto").prop("disabled",false);
              $checktotal.prop("checked",false).attr("checked", false).removeAttr('checked');
            }

          }else{
            console.log("estaba sin marcar");
            console.log("no estaba marcado radiototal");
            $checktotal=$(this);

            let filedata=tabla_pedidos.row($(this).closest('tr')).data();
            console.log(filedata)
            let pedidosaldo=parseFloat(filedata.saldo);
            console.log("saldo "+pedidosaldo);
            console.log("montopagos "+montopagos);

            if(pedidosaldo<=montopagos)
            {
              console.log("pedido "+pedidosaldo+"   < pago "+montopagos);
              montopagos=(montopagos-pedidosaldo).toFixed(2);
              console.log("diferencia "+montopagos);
              $("#diferencia").val(montopagos);
              $(this).closest('tr').find(".radioadelanto").prop("disabled",true);

            }

          }
          return false;
          


        });*/

        ////
        /*$("#tabla_pedidos").on( 'click', 'input.radioadelanto', function (e) {

          let montopagos=$("#diferencia").val().replace(",", "");
          if(montopagos==0 || montopagos==null){

            return false;
          }else{
            if($(this).is(':checked') )
            {
              console.log("no estaba marcado radioadelanto")
              $(this).closest('tr').find(".radiototal").prop("disabled",true);

            }else{
              console.log("si se encuentra marcado radioadelanto")
              $(this).closest('tr').find(".radiototal").prop("disabled",false);
              
            }
            return true;
          }

        });*/
        //////
        // AGREGANDO PAGOS

        function separateComma(val) {
          // remove sign if negative
          var sign = 1;
          if (val < 0) {
            sign = -1;
            val = -val;
          }
          // trim the number decimal point if it exists
          let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
          let len = num.toString().length;
          let result = '';
          let count = 1;

          for (let i = len - 1; i >= 0; i--) {
            result = num.toString()[i] + result;
            if (count % 3 === 0 && count !== 0 && i !== 0) {
              result = ',' + result;
            }
            count++;
          }

          // add number after decimal point
          if (val.toString().includes('.')) {
            result = result + '.' + val.toString().split('.')[1];
          }
          // return result with - sign if negative
          return sign < 0 ? '-' + result : result;
        }

        $(document).ready(function () {

          window.agregarPago = function(){  
            //alert('lol');
            console.log("en pagos")
            var strEx = $("#pmonto").val();
            strEx = strEx.replace(",","");
            var numFinal = parseFloat(strEx);
            monto = numFinal;
            tipomovimiento = $('#tipotransferencia option:selected').val();
            titular = $('#titulares option:selected').val();
            banco = $('#pbanco option:selected').val();
            fecha = $("#pfecha").val();

            if (monto != ""  && banco != "" && fecha != ""/*  && imagen != "" */) {
              subtotal_pago[contPa] = monto*1;
              total_pago = parseFloat(total_pago*1 + subtotal_pago[contPa]*1).toFixed(2);
              

              //var filasPa = ;

              //const tr = filasPa;//$("<tr><td>1</td><td>2</td></tr>");
              //ajax carga imagen de pago temporal

              //let files=$('#pimagen');
              //var totalfilescarga = $('#pimagen').get(0).files.length;
              var fd2 = new FormData();

              //let files=$('#pimagen');
              let files=$('input[name="pimagen')
              console.log(files.length);//1
             {
                //
                //var totalfilescarga = $('#pimagen').get(0).files.length;
                var totalfilescarga = $('input[name="pimagen"]').get(0).files.length;
                //var totalfilescarga = $('input[name="adjunto[]"]').get(0).files.length;
                console.log("totalfilescarga "+totalfilescarga);
                console.log(files.get(0).files[0]);
                {
                  console.log("cargo inputs y len "+files.length)
                  for (let i = 0; i < files.length; i++) {
                    fd2.append('adjunto', $('input[type=file][name="pimagen"]')[0].files[0]);
                  }
                  //console.log(fd2);

                  $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url:"{{ route('pagos.addImgTemp') }}",
                    success:function(data){
                      console.log(data);
                      if(data.html=='0'){

                        }else{
                          /*var urlpdf = '{{ route("pedidosPDF", ":id") }}';           
                          urlpdf = urlpdf.replace(':id', data.html);
                          window.open(urlpdf, '_blank');

                          $("#modal-copiar .textcode").text(data.html);
                          $("#modal-copiar").modal("show");*/


                          tabla_pagos.row.add( {
                              "item":       (contPa + 1),
                              "movimiento":   tipomovimiento,
                              "titular":     titular,
                              "banco": banco,
                              "fecha":     fecha,
                              "imagen":       data.html,
                              "monto":      monto,
                              "accion":      (contPa + 1)
                          } ).draw();

                          const fileInput = $("#pimagen");
                          const fileInputdestino = $("#imagen"+(contPa + 1));//aca que se carga la imagen
                          const myFile = new File(['Hello World!'], 'myFile.txt', {
                              type: 'text/plain',
                              lastModified: new Date(),
                          });

                          const dataTransfer = new DataTransfer();
                          dataTransfer.items.add(myFile);
                          fileInputdestino.files = dataTransfer.files;

                          //ahora cerrar modal
                          $("#modal-add-pagos").modal("hide");

                          //$("#diferencia").val();

                          contPa++;

                          console.log("sumatotalpagos <4")
                          total_pago = sumatotalpagos();

                          limpiarPa();
                          $("#total_pago").html("S/. " + separateComma(total_pago).toLocaleString("en-US"));
                          $("#total_pago_pagar").val(total_pago.toLocaleString("en-US"));
                          evaluarPa();
                          console.log("total_pago 1 "+total_pago);
                          diferenciaFaltante();



                        }
                    }
                  });


                }

              }

              
              //////
               
              
           

              /*tabla_pagos.row.add(
                '<tr class="selected" id="filasPa' + contPa + '">' +
                '<td>' + (contPa + 1) + '</td>' +
                '<td><input type="hidden" name="tipomovimiento[]" value="' + tipomovimiento + '">' + tipomovimiento + '</td>' +
                '<td><input type="hidden" name="titular[]" value="' + titular + '">' + titular + '</td>' +
                '<td><input type="hidden" name="banco[]" value="' + banco + '">' + banco + '</td>' +
                '<td><input type="hidden" name="fecha[]" value="' + fecha + '">' + fecha + '</td>' +
                '<td>@csrf<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/></td>' +      
                '<td><input type="hidden" name="monto[]" value="' + monto + '">' + monto + '</td>' +
                '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPa(' + contPa + ')"><i class="fas fa-trash-alt"></i></button></td>' +
                '</tr>'
              ).draw();*/
              //$(row).addClass("label-warning");


              //agregar fila al datatable
              /*contPa++;
              limpiarPa();
              console.log(total_pago);
              $("#total_pago").html("S/. " + separateComma(total_pago).toLocaleString("en-US"));
              $("#total_pago_pagar").val(total_pago.toLocaleString("en-US"));
              evaluarPa();
              diferenciaFaltante();*/
              //$('#tabla_pagos').append(filasPa);

            }else {
              Swal.fire(
                'Error!',
                'Información faltante del pago',
                'warning')
            }



          }

        });


        function agregarPago1() {
          console.log("agrega pago javascript")

          //valor de datatable la suma de columna total y columna saldo


          //var sumatotal=$("#tabla_pedidos").
          var strEx = $("#pmonto").val();//1,000.00
          //primer paso: fuera coma
          strEx = strEx.replace(",","");//1000.00
          var numFinal = parseFloat(strEx);
          monto = numFinal;

          tipomovimiento = $('#tipotransferencia option:selected').val();//agregados
          titular = $('#titulares option:selected').val();//agregados

          banco = $('#pbanco option:selected').val();
          fecha = $("#pfecha").val();
          /* imagen = $("#pimagen").val(); */


          if (monto != ""  && banco != "" && fecha != ""/*  && imagen != "" */) {
            subtotal_pago[contPa] = monto*1;
            total_pago = parseFloat(total_pago*1 + subtotal_pago[contPa]*1).toFixed(2);

            var filasPa = '<tr class="selected" id="filasPa' + contPa + '">' +
              '<td>' + (contPa + 1) + '</td>' +
              '<td><input type="hidden" name="tipomovimiento[]" value="' + tipomovimiento + '">' + tipomovimiento + '</td>' +
              '<td><input type="hidden" name="titular[]" value="' + titular + '">' + titular + '</td>' +
              '<td><input type="hidden" name="banco[]" value="' + banco + '">' + banco + '</td>' +
              '<td><input type="hidden" name="fecha[]" value="' + fecha + '">' + fecha + '</td>' +
              '<td>@csrf<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/></td>' + 
                /* <img id="picture" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" height="100px" width="100px"> */        
              '<td><input type="hidden" name="monto[]" value="' + monto + '">' + monto + '</td>' +
              '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPa(' + contPa + ')"><i class="fas fa-trash-alt"></i></button></td>' +
              '</tr>';
              

            contPa++;
            limpiarPa();
            console.log(total_pago);
            $("#total_pago").html("S/. " + separateComma(total_pago).toLocaleString("en-US"));
            $("#total_pago_pagar").val(total_pago.toLocaleString("en-US"));
            evaluarPa();
            diferenciaFaltante();
            $('#tabla_pagos').append(filasPa);
            //$("#diferencia")
            console.log("pago "+'pago');
            
          } else {
            Swal.fire(
              'Error!',
              'Información faltante del pago',
              'warning')
          }
        }
        //////
        

        
        



        //variables de calculos pedidos y pagos
        diferencia = 0;
        guardasaldo=0;
        console.log("diferencia inicial 0")
        total_pedido = 0;
        subtotal_pedido = [];
        var contPe = 1;

        total_pago = 0;
        subtotal_pago = [];
        var contPa = 0;


        /*$('#add_pedido').click(function() {
          agregarPedido();
        });*/

        function validarFormulario() {
          //evento.preventDefault();      
          var total_pedido_pagar = document.getElementById('total_pedido_pagar').value;
          var total_pedido = document.getElementById('total_pedido').value;
          var total_pago_pagar = document.getElementById('total_pago_pagar').value;
          var total_pago = document.getElementById('total_pago').value;
          var falta = total_pedido_pagar - total_pago_pagar;
          falta = falta.toFixed(2);

          //var items_pedidos=$('#tabla_pedidos > tbody  > tr').length;
          //var items_pagos=$('#tabla_pagos > tbody  > tr').length;

          var items_pedidos = $('#tabla_pedidos').DataTable().data().count();
          var items_pagos = $('#tabla_pagos').DataTable().data().count();

          //var table_length = table.data().count();

          console.log("pedidos "+items_pedidos)
          console.log("pagos "+items_pagos)

          if(items_pedidos==0)
          {
            Swal.fire(
                'Error',
                'No se puede ingresar un pago sin cargar pedidos',
                'warning'
              )
          }else if(items_pagos==0){
            Swal.fire(
                'Error',
                'No se puede ingresar un pago sin voucher',
                'warning'
              )
          }else if(total_pago_pagar  - total_pedido_pagar >= 10) {
              Swal.fire(
                'Error',
                'No se puede ingresar un pago mayor a la deuda (diferencia mayor a 10) que tiene el cliente',
                'warning'
              )

              /*Swal.fire({
                icon: 'warning',
                title: 'Pago incompleto ¿Estás seguro?',
                text: "Falta S/" + falta + " para cancelar",            
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar!'
              }).then((result) => {
                if (result.isConfirmed) {
                  this.submit();
                }
              })*/
          }
          else {
              ///this.submit();
              $("#formulario").trigger("submit");
          }      
        }
        //////

        ////fin  document ready
      });


      //function onclickradiototal(checkeds) {
        //get id..and check if checked
        //console.log(checkeds+' para total');
        //console.log($(checkeds).attr("id"), checkeds.checked)

      //}

      //function onclickradioadelanto(checkeds) {
        //get id..and check if checked
        //console.log(checkeds+' para saldo');
        //console.log($(checkeds).attr("id"), checkeds.checked)

      //}


      

    //});

    
    //VALIDAR CAMPO FECHAS MAX DIA ACTUAL
    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("pfecha")[0].setAttribute('max', today);

    //VALIDAR ANTES DE ENVIAR
    /*document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });*/

    // AGREGANDO PEDIDOS
    

    

    

    /*function agregarPedido() {
      datosPedido = document.getElementById('ppedido_id').value.split('_');
      Pedido_delete = document.getElementById('ppedido_id').value

      pedido_id = datosPedido[0];
      codigo = datosPedido[1];
      monto = datosPedido[2];
      saldo = datosPedido[3];

      if (pedido_id != "") {
        subtotal_pedido[contPe] = saldo*1;
        total_pedido = total_pedido + subtotal_pedido[contPe];

        var filasPe = '<tr class="selected" id="filasPe' + contPe + '">' +
          '<td>' + contPe + '</td>' +
          '<td style="display:none;" ><input type="hidden" name="pedido_id[]" value="' + pedido_id + '">' + pedido_id + '</td>' +
          '<td><input type="hidden" name="" value="">PED000' + pedido_id + '</td>' +
          '<td><input type="hidden" name="" value="">' + codigo + '</td>' +
          '<td><input type="hidden" name="" id= "numbermonto" value="">S/' + monto + '</td>' +
          '<td><input type="hidden" name="" id= "numbersaldo" value="">S/' + saldo + '</td>' +
          '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPe(' + contPe + ')"><i class="fas fa-trash-alt"></i></button></td>' +
          '</tr>';
        contPe++;
        limpiarPe();
        $("#total_pedido").html("S/. " + total_pedido.toLocaleString("en-US"));
        $("#total_pedido_pagar").val(total_pedido.toLocaleString("en-US"));
        evaluarPe();
        diferenciaFaltante();
        $('#tabla_pedidos').append(filasPe);
        Remove_options(Pedido_delete);

      } else {
        Swal.fire(
          'Error!',
          'Error al agregar el pedido',
          'warning')
      }
    }*/
    

    /* function evaluarPe() {
      if (total_pedido > 0 && total_pago > 0) {
        $("#guardar").show();
      } else {
        $("#guardar").hide();
      }
    } */

    

    

    //VALIDAR CAMPOS NUMERICO DE MONTO EN PAGOS
    
    

    //VALIDANDO CAMPOS DE PAGOS
    /*$(document).ready(function() {


    });*/
    // CAMBIAR IMAGEN
    /* document.getElementById("imagen").addEventListener('change', cambiarImagen());

    function cambiarImagen(event){
        var file = event.target.files[0];

        var reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById("picture").setAttribute('src', event.target.result);
        };

        reader.readAsDataURL(file);
    } */

    $(document).ready(function() {

      $(document).on("change","#pimagen",function(event){
        console.log("cambe image")
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = (event) => {
          //$("#picture").attr("src",event.target.result);
            document.getElementById("picture").setAttribute('src', event.target.result);
        };
        reader.readAsDataURL(file);

      });
        

    });
  

    
  </script>

@stop
