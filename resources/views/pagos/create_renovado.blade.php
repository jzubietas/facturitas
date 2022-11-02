@extends('adminlte::page')

@section('title', 'Agregar Pago')

@section('content_header')
  <h1>Agregar pago</h1>
@stop

@section('content')
  <div class="card">
    {!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
      <div class="border rounded card-body border-secondary" style="margin: 1%">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor') !!}
            <input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">
            <input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('cliente_id', 'Cliente*') !!}{!! Form::hidden('cliente_id', '',['id' => 'cliente_id']) !!}
              <select name="pcliente_id" class="border form-control selectpicker border-secondary" id="pcliente_id" data-live-search="true">
                <option value="">---- SELECCIONE CLIENTE ----</option>
                  @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}_{{ $cliente->saldo }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                @endforeach
              </select>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="form-row">

        <div class="form-group col-lg-6">
            <div class="form-row" style="margin:-2px">
              <div class="form-group col-lg-6">
                <h2>PAGOS  <b style="font-size:20px"> {!! Form::label('', '') !!}</b></h2>
              </div>
              <div class="form-group col-lg-4">
                <input type="hidden" name="saldo" id="saldo" class="form-control number" placeholder="Saldo a favor...">
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
                    <th scope="col">ITEM</th> 
                    <th scope="col">TIPO MOVIMIENTO</th>
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
                  <th><input type="hidden" name="total_pago_pagar" requerid value="" id="total_pago_pagar" class="form-control"></th>  
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
                {{-- <tfoot>
                  <th style="text-align: center">TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th><h4 id="total_pedido">S/. 0.00</h4></th>
                  <th><input type="hidden" name="total_pedido_pagar" requerid value="" id="total_pedido_pagar" class="form-control"></th>              
                </tfoot> 
                --}}             
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
        <div id="guardar" class="form-group col-lg-1">
          <button id="registrar_pagos" type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="form-group col-lg-1">
          @if (Auth::user()->rol == "Asesor")
            <a class="btn btn-danger" href="{{ route('pagos.mispagos') }}"><i class="fas fa-times-circle"></i> Cancelar</a>
          @else
            <a class="btn btn-danger" href="{{ route('pagos.index') }}"><i class="fas fa-times-circle"></i> Cancelar</a>
          @endif
        </div>
        <div class="form-group col-lg-3"></div>
        <div class="form-group col-lg-4" style="text-align: center;">
          <div class="input-group">            
            <input type="text" name="" value="SALDO S/:" disabled class="form-control" style="color: red; font-weight:bold; font-weight: 900; font-size:21px">
            <input type="text" name="diferencia" value="" disabled id="diferencia" class="form-control" style="color: red; font-weight:bold; font-weight: 900; font-size:21px">   
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
    var tabla_pagos=null;
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

      function evaluarPa() {
          if (total_pago > 0) {//total_pedido > 0 && 
            $("#guardar").show();
          } else {
            $("#guardar").hide();
          }
        }

        $(document).ready(function (e) {
 
   
          $('#pimagen').change(function(){
                    
            let reader = new FileReader();

            reader.onload = (e) => { 

              $('#picture').attr('src', e.target.result); 
            }

            reader.readAsDataURL(this.files[0]); 
          
          });
      

          /*$(document).on("click",".eliminarpa",function(event){
              var obtenerindex=1
            tabla_pagos.row.del("");


          });*/

          $(document).on("click",".eliminarpa",function(event){

            let montofilapago=$(this).parents('tr').find(".montopago").html();
            console.log("mont "+montofilapago);
            let calcu_=  $("#diferencia").val()*1- montofilapago*1;
            $("#diferencia").val(calcu_);
            tabla_pagos
                .row( $(this).parents('tr') )
                .remove()
                .draw();
              //restar a saldo pagos
          });
         
          
          /*function eliminarPa(index) {
            total_pago = total_pago - subtotal_pago[index];
            $("#total_pago").html("S/. " + total_pago.toLocaleString("en-US"));
            $("#total_pago_pagar").val(total_pago);
            $("#filasPa" + index).remove();
            evaluarPa();
          }*/
          
        });

      $(document).ready(function() {

        /* codigo para cargar pagos datatable
        
        */
        var counter_pagos = 1;

        tabla_pagos=$('#tabla_pagos').DataTable({
            scrollY: '200px',
            scrollCollapse: true,
            paging: false,
            fixedHeader: true,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            /*columns: [
              {
                data: null,
                render: function ( data, type, row ) {
                    return '';
                }
              },
              {
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },{
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },{
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },{
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },{
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },{
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },{
                data: null,
                render: function ( data, type, row ) {
                  return '';
                }
              },
            ],*/
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = 4;api.columns().nodes().length;
                var j = 2;

                /*var pageTotal = api
                      .column( 6, { page: 'current'} )
                      .data()
                      .reduce( function (a, b) {
                          return Number(a) + Number(b);
                      }, 0 );*/
                
                //$( api.column( 7 ).footer() ).html('<input type="text" name="total_pago" id="total_pago" value="'+pageTotal+'"/>'+pageTotal);

                /*var pageSaldo = api
                      .column( 3, { page: 'current'} )
                      .data()
                      .reduce( function (a, b) {
                          return Number(a) + Number(b);
                      }, 0 );
                $( api.column( 3 ).footer() ).html('<input type="text" name="total_pago_pagar" id="total_pago_pagar" value="'+pageSaldo+'" />'+pageSaldo);
                     */ 
              },

        });

        $(document).on("click","#add_pago",function(event){

          if ($('#tipotransferencia').val() == '') {
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
          else if ($('#pbanco').val() == ''){
            Swal.fire(
              'Error',
              'Seleccione banco ',
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
          else if ($('#pimagen').val() == ''){
            Swal.fire(
              'Error',
              'Seleccione la imagen',
              'warning'
            )
          }
          else {

            var strEx = $("#pmonto").val();
            strEx = strEx.replace(",","");
            var numFinal = parseFloat(strEx);
            monto = numFinal;
            tipomovimiento = $('#tipotransferencia option:selected').val();
            titular = $('#titulares option:selected').val();
            banco = $('#pbanco option:selected').val();
            fecha = $("#pfecha").val();
            imagen = $("#pimagen").val();

            if (monto != ""  && banco != "" && fecha != ""  && imagen != "" && tipomovimiento != "" && titular != "" ) 
            {
              /*subtotal_pago[counter_pagos] = monto*1;
              total_pago = parseFloat(total_pago*1 + subtotal_pago[counter_pagos]*1).toFixed(2);

              total_pago*/

              console.log($('#pimagen').val());
            
              deuda = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
              pagado = !isNaN($('#pstock').val()) ? parseInt($('#pstock').val(), 10) : 0;

              console.log("aaaaa");

              var col1=(counter_pagos + 1);
              var col2='<input type="hidden" name="tipomovimiento[]" value="'+tipomovimiento+'" ><span class="tipomovimiento">'+tipomovimiento+'</span>';
              var col3='<input type="hidden" name="titular[]" value="'+titular+'"><span class="titular">'+titular+'</span>';
              var col4='<input type="hidden" name="banco[]" value="'+banco+'"><span class="banco">'+banco+'</span>';
              var col5='<input type="hidden" name="fecha[]" value="'+fecha+'"><span class="fecha">'+fecha+'</span>';
              var col6= '<img id="picture'+(counter_pagos+1)+'" src="'+imagen+'" alt="Imagen del pago" height="100px" width="100px">';
              var col7 = '<input type="hidden" name="monto[]" value="'+monto+'"><span class="montopago">'+monto+'</span>';
              var col8 = '<button type="button" class="btn btn-danger btn-sm eliminarpa"><i class="fas fa-trash-alt"></i></button>'

              tabla_pagos.row.add([col1,col2,col3,col4,col5,col6,col7,col8]).draw(false);    
              counter_pagos++;

              let calcu_=  $("#diferencia").val()*1+ monto*1;
              $("#diferencia").val(calcu_);
              var montofila=0;
              $('#tabla_pagos > tbody  > tr').each(function(index,tr) {
                console.log(index+" posicion");
                aa1=($(this).find("td").eq(6).find(".montopago").text());
                montofila=montofila*1+aa1*1;
                console.log(montofila)
                $("#total_pago").text(montofila).html(montofila);


               // $(tfoot).find('input').eq(0).val(data);
               
              });

              $("#tabla_pagos tfoot").find('tr').eq(6).find("h4").html("44");

              //$("#total_pago").text("123").html("123");
              //.text($(e.currentTarget).data('text'));
              //$("#total_pago_pagar").val(montofila.toLocaleString("en-US"));


              //console.log("agregando fila a la tabla del formulario modal agregar pago")

            }


            
          }

          



        });
        /*$('#addRow').on('click', function () {
            
        });*/



        $(document).on("change","#pcliente_id",function(){
          $('#tabla_pedidos').DataTable().clear().destroy();
          datosCliente = document.getElementById('pcliente_id').value.split('_');
          cliente_id = datosCliente[0];
          saldo = datosCliente[1];
          console.log("diferencia en change pcliente_id");
          console.log(diferencia);
          $("#diferencia").prop("disabled",false);
          let diferenciaval=$("#diferencia").val();
          console.log(diferenciaval);
          $("#cliente_id").val(cliente_id);
          $("#saldo").val(saldo);          
          
          tabla_pedidos=$('#tabla_pedidos').DataTable({
            scrollY: '200px',
            scrollCollapse: true,
            paging: false,
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
                      return '<input type="hidden" name="pedido_id['+row.id+']" value="' + data + '">PED000' + data + '</td>';
                    }else if(row.id<100){
                      return '<input type="hidden" name="pedido_id['+row.id+']" value="' + data + '">PED00' + data + '</td>';
                    }else if(row.id<1000){
                      return '<input type="hidden" name="pedido_id['+row.id+']" value="' + data + '">PED0' + data + '</td>';
                    }else{
                      return '<input type="hidden" name="pedido_id['+row.id+']" value="' + data + '">PED' + data + '</td>';
                    } 
                    //return '<input type="hidden" name="pedido_id[]" value="' + data + '">PED000' + data + '</td>';
                  }
                },
                {data: 'codigo', name: 'codigo',},
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
                $( api.column( 3 ).footer() ).html('<input type="hidden" name="total_pedido_pagar" id="total_pedido_pagar" value="'+pageSaldo+'" />'+pageSaldo);

              },
              
            });//fin datatable

        });
      });//fin ready

  </script>
    <script src="{{ asset('js/createpago_radiototal.js') }}"></script>
    <script src="{{ asset('js/createpago_radioadelanto.js') }}"></script>
  <script>
      $(document).ready(function() {

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
          diferencia = total_pago - total_pedido;
          console.log('diferencia en fx diferenciaFaltante');
          console.log(diferencia);
         
          $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
            console.log(index+" posicion");
            //var saldofila=$(this).find("td").eq(3).html();
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


        /*$(document).on("click","#add_pago",function(event){
          if ($('#tipotransferencia').val() == '') {
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
          else if ($('#pbanco').val() == ''){
            Swal.fire(
              'Error',
              'Seleccione banco ',
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
          else if ($('#pimagen').val() == ''){
            Swal.fire(
              'Error',
              'Seleccione la imagen',
              'warning'
            )
          }
          else {

            console.log($('#pimagen').val());
            
            deuda = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
            pagado = !isNaN($('#pstock').val()) ? parseInt($('#pstock').val(), 10) : 0;

            agregarPago();
            
          }
        });*/

        
        // AGREGANDO PAGOS

        var imgtemporal='';

        

        function agregarPago() {

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
          imagen = $("#pimagen").val();

          if (monto != ""  && banco != "" && fecha != ""  && imagen != "" ) {
            subtotal_pago[contPa] = monto*1;
            total_pago = parseFloat(total_pago*1 + subtotal_pago[contPa]*1).toFixed(2);

            var filasPa = '<tr class="selected" id="filasPa' + contPa + '">' +
              '<td>' + (contPa + 1) + '</td>' +
              '<td><input type="hidden" name="tipomovimiento[]" value="' + tipomovimiento + '">' + tipomovimiento + '</td>' +
              '<td><input type="hidden" name="titular[]" value="' + titular + '">' + titular + '</td>' +
              '<td><input type="hidden" name="banco[]" value="' + banco + '">' + banco + '</td>' +
              '<td><input type="hidden" name="fecha[]" value="' + fecha + '">' + fecha + '</td>' +
              '<td>@csrf<input type="file" id="imagen'+(contPa+1)+'" name="imagen[]" accept= "image/*" style="width:150px;" src="'+imagen+'"/>' + 
                 '<img id="picture'+(contPa+1)+'" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" height="100px" width="100px">'+
              '</td>'+
              '<td><input type="hidden" name="monto[]" value="' + monto + '">' + monto + '</td>' +
              '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPa(' + contPa + ')"><i class="fas fa-trash-alt"></i></button></td>' +
              '</tr>';

            contPa++;
            limpiarPa();
            console.log(total_pago);
            $("#total_pago").html("S/. " + (total_pago).toLocaleString("en-US"));
            //$("#total_pago").html("S/. " + separateComma(total_pago).toLocaleString("en-US"));
            $("#total_pago_pagar").val(total_pago.toLocaleString("en-US"));
            evaluarPa();
            //diferenciaFaltante();
            $('#tabla_pagos').append(filasPa);

            //agregar imagen src 
            //let nuevosrcimg=$("#pimagen").attr('src');
            //console.log(nuevosrcimg);
          
            //$("#imagen"+contPa).attr("src",nuevosrcimg);

            ///
            $imgsrc = $('#picture').attr('src');
            console.log($imgsrc);
            console.log(contPa+" contador");

            $("#imagen"+contPa).attr("src",$imgsrc).hide();
            $("#picture"+contPa).attr("src",$imgsrc);
            //////
            
            //$("#diferencia")
            ////console.log("pago "+'pago');
            
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

        function validarFormulario(evento) {
          evento.preventDefault();      
          var total_pedido_pagar = document.getElementById('total_pedido_pagar').value;
          var total_pedido = document.getElementById('total_pedido').value;
          var total_pago_pagar = document.getElementById('total_pago_pagar').value;
          var total_pago = document.getElementById('total_pago').value;
          var falta = total_pedido_pagar - total_pago_pagar;
          falta = falta.toFixed(2);
          /* var imagen = document.getElementById('imagen').value; */

          //Obtengo todos los campos con el nombre cantidad[]
          imagen = document.getElementsByName("imagen[]");

          //Creo el arreglo donde almaceno sus valores
          var img = [];

          //Recorro todos los nodos que encontre que coinciden con ese nombre
          for(var i=0;i<imagen.length;i++){
          //Añado el valor que contienen los campos
              img.push(imagen[i].value);
          }

          /* console.info(img.includes('')); */

          //valido si hay una imagen vacia
          if(img.includes('') == true)
          {
            Swal.fire(
                'Error',
                'Seleccione una imagen para cada pago agregado',
                'warning'
              )
          }
          else if (total_pedido_pagar*1 < total_pago_pagar*1 ) {
              Swal.fire(
                'Error',
                'No se puede ingresar un pago mayor a la deuda que tiene el cliente',
                'warning'
              )
          }
          else if(total_pedido_pagar - total_pago_pagar > 1) {
              Swal.fire({
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
              })
          }
          else {
              this.submit();
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

    
  

    
  </script>

@stop
