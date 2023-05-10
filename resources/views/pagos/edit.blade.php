@extends('adminlte::page')

@section('title', 'Editar Pago')

@section('content_header')
  <h1>EDITAR <b>PAGO: PAG000{{ $pago->id }}</b></h1>
@stop

@section('content')
  {!! Form::model($pago, ['route' => ['pagos.update', $pago], 'method' => 'put','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
    <div class="card">
      <div class="card-body">
        <div class="border rounded card-body border-secondary">
          <div class="form-row">
            <table class="table table-active">
              <thead>
                <tr>
                  <td>
                    <th scope="col" class="col-lg-2" style="text-align: right;">ASESOR:</th>
                    <th scope="col">{{ $pagos->users }}</th>
                  </td>
                  <td>
                    <th scope="col" class="col-lg-2" style="text-align: right;">CLIENTE:</th>
                    <th scope="col">{{ $pagos->celular }} - {{ $pagos->nombre }}</th>
                  </td>
                  <td>
                    <th scope="col" colspan="" class="col-lg-2" style="text-align: right;">ESTADO:</th>
                  <th scope="col">{{ $pagos->condicion }}</th>
                  </td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-12">
            <h2>PAGOS
              <a data-target="#modal-add-pagos" id="addpago" data-toggle="modal"><button class="btn btn-primary"><i class="fas fa-plus-circle"></i></button></a>
            </h2>
            @error('imagen')
              {{$message}}
            @enderror
            <div class="table-responsive">
              <table id="tabla_pagos" class="table table-striped">
                <thead class="bg-primary">
                  <tr>
                    <th scope="col">ITEM</th>                
                    <th scope="col">BANCO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">IMAGEN</th>
                    <th scope="col">MONTO</th>
                    <th scope="col">ACCIÓN</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <th style="text-align: center">TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th><h4 id="total_pago">S/. 0.00</h4></th>
                  <th><input type="hidden" name="total_pago_pagar" value="" id="total_pago_pagar"class="form-control"></th>  
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        {{-- MODALS --}}
        @include('pagos.modals.AddPedidosEdit')
        @include('pagos.modals.AddPagos')
      </div>
      <div class="card-footer">
        <div class="form-row">
          <div id="guardar" class="form-group col-lg-1">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
          </div>
          <div class="form-group col-lg-1">
            @if (Auth::user()->rol == "Asesor")
              <a class="btn btn-danger" href="{{ route('pagos.mispagos') }}"><i class="fas fas fa-arrow-left"></i> ATRAS</a>
            @else
            <a class="btn btn-danger" href="{{ route('pagos.index') }}"><i class="fas fas fa-arrow-left"></i> ATRAS</a>
            @endif            
          </div>
          <div class="form-group col-lg-3"></div>
          <div class="form-group col-lg-4" style="text-align: center;">
            <div class="input-group">            
              <input type="text" name="" value="DIFERENCIA FALTANTE S/:" disabled class="form-control" style="color: red; font-weight:bold; font-weight: 900; font-size:21px">
              <input type="text" name="diferencia" value="" disabled id="diferencia" class="form-control" style="color: red; font-weight:bold; font-weight: 900; font-size:21px">   
            </div>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-6">
            <h2>LISTA DE PEDIDOS</h2>
            <div class="table-responsive">
              <table class="table table-striped table-sm">
                <thead class="bg-info">
                  <tr>
                    <th scope="col">ITEM</th>
                    <th scope="col">PEDIDO</th>
                    <th scope="col">CODIGO</th>
                    <th scope="col">MONTO</th>
                    <th scope="col">ACCIÓN</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPe = 0;
                    $sumPe = 0;
                  @endphp
                  @foreach ($listaPedidos as $listaPe)
                    <tr>
                      <td>{{ $contPe + 1 }}</td>
                      <td>PED000{{ $listaPe->pedidos }}</td>
                      <td>{{ $listaPe->codigo }}</td>
                      <td>@php echo number_format($listaPe->total,2) @endphp</td>
                      <td>
                        <a href="" data-target="#modal-delete-Pedido-{{ $listaPe->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm" id="delete_pedido_t">Eliminar</button></a>
                      </td>
                    </tr>
                    @php
                    $sumPe = $sumPe + $listaPe->total;
                    $contPe++;
                    @endphp
                  @endforeach
                </tbody>
                <tfoot>
                  <th style="text-align: center">TOTAL</th>
                  <th><input type="hidden" name="delete_pedido" requerid value="{{ $contPe }}" id="delete_pedido" class="form-control"></th>
                  <th></th>
                  <th><h4><?php echo number_format($sumPe, 2, '.', ' ')?></h4></th>
                  <th><input type="hidden" name="total_pedidos" requerid value="{{ $sumPe }}" id="total_pedidos" class="form-control"></th>  
                </tfoot>   
                {{-- @include('pagos.modals.DeletePedido')  --}}         
              </table>
            </div>
          </div>
          <div class="form-group col-lg-6">
            <div class="form-row" style="margin:-10px">
              <div class="form-group col-lg-9">
                <h2>LISTA DE PAGOS - <b style="font-size:20px"> {!! Form::label('saldo', 'Saldo a favor') !!}</b></h2>
              </div>
              <div class="form-group col-lg-3">
                <input type="text" name="saldo" id="saldo" value={{ $pagos->saldo }} class="form-control number" placeholder="Saldo a favor...">
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-sm">
                <thead class="bg-primary">
                  <tr>
                    <th scope="col">ITEM</th>
                    <th scope="col">PAGO</th>
                    <th scope="col">BANCO</th>                
                    <th scope="col">MONTO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">OBSERVACION</th>
                    <th scope="col">IMAGEN</th>
                    <th scope="col">ACCIÓN</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPa = 0;
                    $sumPa = 0;
                  @endphp
                  @foreach ($listaPagos as $listaPa)
                    <tr>
                      <td>{{ $contPa + 1 }}</td>
                      <td>PAG000{{ $listaPa->id }}</td>
                      <td>{{ $listaPa->banco }}</td>                  
                      <td>@php echo number_format($listaPa->monto,2) @endphp</td>
                      <td>{{ $listaPa->fecha }}</td>
                      <td>{{ $listaPa->observacion }}</td>
                      <td>
                        <img src="{{ asset('storage/pagos/' . $listaPa->imagen) }}" alt="{{ $listaPa->imagen }}" height="200px" width="200px" class="img-thumbnail">
                      </td>
                      <td>
                        <a href="" data-target="#modal-delete-Pago-{{ $listaPa->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm" id="delete_pago_t">Eliminar</button></a>
                      </td>
                    </tr>
                    @php
                      $sumPa = $sumPa + $listaPa->monto;
                      $contPa++;
                    @endphp
                    @include('pagos.modals.DeletePago')
                  @endforeach
                </tbody>
                <tfoot>
                  <th style="text-align: center">TOTAL</th>
                  <th><input type="hidden" name="delete_pago" requerid value="{{ $contPa }}" id="delete_pago" class="form-control"></th>
                  <th></th>
                  <th><h4><?php echo number_format($sumPa, 2, '.', ' ')?></h4></th>
                  <th><input type="hidden" name="total_pagos" requerid value="{{ $sumPa }}" id="total_pagos" class="form-control"></th>  
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop

@section('js')

  @if (session('info') == 'actualizado')
    <script>
      Swal.fire(
        'Pago {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  @if (session('info') == 'Eliminado')
    <script>
      Swal.fire(
        '{{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
    $(document).ready(function() {
      var delete_pedido = $("#delete_pedido").val();
      if (delete_pedido<2){
      $('#delete_pedido_t').hide();
      }else $('#delete_pedido_t').show();

      var delete_pago = $("#delete_pago").val();
      if (delete_pago<2){
      $('#delete_pago_t').hide();
      }else $('#delete_pago_t').show();

      diferenciaFaltante()
    });

    $("#guardar").hide();

    // AGREGANDO PEDIDOS
    $('#add_pedido').click(function() {
      agregarPedido();
    });  

    function eliminarPe(index) {
      total_pedido = total_pedido - subtotal_pedido[index];
      $("#total_pedido").html("S/. " + total_pedido.toLocaleString("en-US"));
      $("#total_pedido_pagar").val(total_pedido);
      $("#filasPe" + index).remove();
      evaluarPe();
    }

    //VALIDAR CAMPO FECHAS MAX DIA ACTUAL
    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("pfecha")[0].setAttribute('max', today);
    
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

    //VALIDANDO CAMPOS DE PAGOS
    $(document).ready(function() {

      $(".banco_procedencia").hide();
      $(".banco_procedencia_otro").hide();

      $(document).on("change","#tipotransferencia",function(event){
        console.log($(this).val());
        if($(this).val()=='INTERBANCARIO'){
          $("#pbancoprocedencia").val("").selectpicker("refresh");
          $("#otro_bancoprocedencia").val("");
          $(".banco_procedencia").show();
          $(".banco_procedencia_otro").hide();           
        }else{
          $(".banco_procedencia").hide();
          $(".banco_procedencia_otro").hide();
        }
      });

      $(document).on("change","#pbancoprocedencia",function(event){
          console.log($(this).val());
          if($(this).val()=='OTROS'){
            $("#otro_bancoprocedencia").val("");
            $(".banco_procedencia_otro").show();           
          }else{
            $(".banco_procedencia_otro").hide();
          }
        });


      $('#add_pago').click(function() {
        if ($('#pmonto').val() == '') {
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
        else if ($('#tipotransferencia').val() == 'INTERBANCARIO') {
          if ($('#pbancoprocedencia').val() == '') {
            Swal.fire(
              'Error',
              'Seleccione Banco de procedencia',
              'warning'
            )
          }else if ($('#pbancoprocedencia').val() == 'OTROS') {
            if ($('#otro_bancoprocedencia').val() == '') 
            {
              Swal.fire(
                'Error',
                'Seleccione Banco de procedencia',
                'warning'
              )
            }

          }
        }
        else if ($('#pfecha').val() == ''){
          Swal.fire(
            'Error',
            'Seleccione la fecha',
            'warning'
          )
        }
        else {
            agregarPago();
        }
      })
    });

    diferencia = 0;
    total_pago = 0;
    subtotal_pago = [];
    var contPa = 0;

    // AGREGANDO PAGOS
    function agregarPago() {
      var strEx = $("#pmonto").val();//1,000.00
      //primer paso: fuera coma
      strEx = strEx.replace(",","");//1000.00
      var numFinal = parseFloat(strEx);
      monto = numFinal;
      banco = $('#pbanco option:selected').val();
      fecha = $("#pfecha").val();


      if (monto != ""  && banco != "" && fecha != "") {
        subtotal_pago[contPa] = monto*1;
        total_pago = total_pago + subtotal_pago[contPa];

        var filasPa = '<tr class="selected" id="filasPa' + contPa + '">' +
          '<td>' + (contPa + 1) + '</td>' +          
          '<td><input type="hidden" name="banco[]" value="' + banco + '">' + banco + '</td>' +
          '<td><input type="hidden" name="fecha[]" value="' + fecha + '">' + fecha + '</td>' +
          '<td>@csrf<input type="file" id="imagen" name="imagen[]" accept= "image/*" /></td>' +      
          '<td><input type="hidden" name="monto[]" value="' + monto + '">' + monto + '</td>' +
          '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPa(' + contPa + ')"><i class="fas fa-trash-alt"></i></button></td>' +
          '</tr>';          

        contPa++;
        limpiarPa();
        $("#total_pago").html("S/. " + total_pago.toLocaleString("en-US"));
        $("#total_pago_pagar").val(total_pago);
        evaluarPa();
        diferenciaFaltante()
        $('#tabla_pagos').append(filasPa);
      } else {
        Swal.fire(
          'Error!',
          'Información faltante del pago',
          'warning')
      }
    }

    function limpiarPa() {
      $("#pmonto").val("");
      $("#pbanco").val('').change();
      $("#pfecha").val("");
      $("#pimagen").val("");
    }

    function evaluarPa() {
      if (total_pago > 0) {
        $("#guardar").show();
      } else {
        $("#guardar").hide();
      }
    }

    function diferenciaFaltante() {
      var deuda_total = $("#total_pedidos").val();
        var total_pagos = $("#total_pagos").val();
        var total_pago_pagar = $("#total_pago_pagar").val();
      var pagado_total = 0;
      pagado_total = total_pagos*1 + total_pago_pagar*1;
      diferencia = deuda_total - pagado_total;
      
      $("#diferencia").val(diferencia.toLocaleString("en-US"));
    }

    function eliminarPa(index) {
      total_pago = total_pago - subtotal_pago[index];
      $("#total_pago").html("S/. " + total_pago.toLocaleString("en-US"));
      $("#total_pago_pagar").val(total_pago);
      $("#filasPa" + index).remove();
      evaluarPa();
    }

    //VALIDAR ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });

    function validarFormulario(evento) {
      evento.preventDefault();      
      var total_pedidos = document.getElementById('total_pedidos').value;
      var total_pago_pagar = document.getElementById('total_pago_pagar').value;
      var total_pagos = document.getElementById('total_pagos').value;
      imagen = document.getElementsByName("imagen[]");

      var img = [];

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
      else if (total_pedidos*1 < ((total_pago_pagar*1)+(total_pagos*1)) ) {
          Swal.fire(
            'Error',
            'No se puede ingresar un pago mayor al que tiene el cliente',
            'warning'
          )
        }
        else if((total_pedidos*1) - ((total_pago_pagar*1)+(total_pagos*1)) > 1) {
            Swal.fire({
              title: 'Estás seguro?',
              text: "Vas a guardar un pago menor a la deuda del cliente!",
              icon: 'warning',
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
  </script>

@stop
