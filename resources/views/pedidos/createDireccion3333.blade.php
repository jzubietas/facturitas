@extends('adminlte::page')

@section('title', 'Agregar direccion')

@section('content_header')
  <h1>Agregar dirección</h1>
@stop

@section('content')
  <div class="card">
    {!! Form::open(['route' => 'envios.direccion','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
    <div class="card-header">
    
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor') !!}
            <input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">
            <input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('cliente_id', 'Cliente*') !!}{!! Form::hidden('cliente_id', '',['id' => 'cliente_id']) !!}
            {!! Form::text('cliente_id', $clientes->nombre, ['class' => 'form-control', 'id' => 'cliente_id', 'disabled']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group col-lg-6">
          <div class="form-row">
            <div class="form-group col-lg-6">
              <h2>PEDIDOS A PAGAR</h2>
            </div>
            <div class="form-group col-lg-6">
              <a data-target="#modal-add-pedidos" id="addpedido" data-toggle="modal"><button class="btn btn-info"><i class="fas fa-plus-circle"></i></button></a>  
            </div>
          </div>
          <div class="table-responsive">
            <table id="tabla_pedidos" class="table table-striped">
              <thead class="bg-info">
                <tr>
                  <th scope="col">ITEM</th>
                  <th scope="col">PEDIDO</th>
                  <th scope="col">CODIGO</th>
                  <th scope="col">MONTO</th>
                  <th scope="col">ACCIÓN</th>
                </tr>
              </thead>
              <tfoot>
                <th style="text-align: center">TOTAL</th>
                <th></th>
                <th></th>
                <th><h4 id="total_pedido">S/. 0.00</h4></th>
                <th><input type="hidden" name="total_pedido_pagar" requerid value="" id="total_pedido_pagar" class="form-control"></th>              
              </tfoot>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="form-group col-lg-6">
          <div class="form-row" style="margin:-2px">
            <div class="form-group col-lg-6">
              <h2>PAGOS - <b style="font-size:20px"> {!! Form::label('saldo', 'Saldo a favor') !!}</b></h2>
            </div>
            <div class="form-group col-lg-4">
              <input type="text" name="saldo" id="saldo" class="form-control number" placeholder="Saldo a favor...">
            </div>
            <div class="form-group col-lg-2">
              <a data-target="#modal-add-pagos" id="addpago" data-toggle="modal"><button class="btn btn-primary"><i class="fas fa-plus-circle"></i></button></a>
            </div>
          </div>       
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
              <tfoot>
                <th style="text-align: center">TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total_pago">S/. 0.00</h4></th>
                <th><input type="hidden" name="total_pago_pagar" requerid value="" id="total_pago_pagar" class="form-control"></th>  
              </tfoot>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      {{-- MODALS --}}
      @include('pedidos.modal.AddPedidos')
    </div>
    <div class="card-footer">
      <div class="form-row">
        <div id="guardar" class="form-group col-lg-1">
          <button id="registrar_pagos" type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="form-group col-lg-1">
          @if (Auth::user()->rol == "Asesor")
            <a class="btn btn-danger" href="{{ route('pagos.mispagos') }}"><i class="fas fa-times-circle"></i> ATRAS</a>
          @else
            <a class="btn btn-danger" href="{{ route('pagos.index') }}"><i class="fas fa-times-circle"></i> ATRAS</a>
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
  </div>
@stop

@section('js')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>  

    $("#addpago").hide();
    $("#pcliente_id").change(mostrarBotones);

    function mostrarBotones() {
      $("#addpedido").show();
      $("#addpago").show();
    }    

    // CARGAR PEDIDOS DE CLIENTE SELECCIONADO
    $("#pcliente_id").change(function() {
      datosCliente = document.getElementById('pcliente_id').value.split('_');

      cliente_id = datosCliente[0];
      saldo = datosCliente[1];

      $("#cliente_id").val(cliente_id);
      $("#saldo").val(saldo);      

      $.ajax({
        url: "{{ route('cargar.pedidoscliente') }}?cliente_id=" + $(this).val(),
        method: 'GET',
        success: function(data) {
          $('#ppedido_id').html(data.html);
        }
      });
    });
    
    // AGREGANDO PEDIDOS
    $('#add_pedido').click(function() {
      agregarPedido();
    });

    function Remove_options(Pedido_delete)
    {
      $("#ppedido_id option[value='" + Pedido_delete +"']").remove();
    }

    diferencia = 0;
    total_pedido = 0;
    subtotal_pedido = [];
    var contPe = 1;

    function agregarPedido() {
      datosPedido = document.getElementById('ppedido_id').value.split('_');
      Pedido_delete = document.getElementById('ppedido_id').value

      pedido_id = datosPedido[0];
      codigo = datosPedido[1];
      monto = datosPedido[2];

      if (pedido_id != "") {
        subtotal_pedido[contPe] = monto*1;
        total_pedido = total_pedido + subtotal_pedido[contPe];

        var filasPe = '<tr class="selected" id="filasPe' + contPe + '">' +
          '<td>' + contPe + '</td>' +
          '<td style="display:none;" ><input type="hidden" name="pedido_id[]" value="' + pedido_id + '">' + pedido_id + '</td>' +
          '<td><input type="hidden" name="" value="">PED000' + pedido_id + '</td>' +
          '<td><input type="hidden" name="" value="">' + codigo + '</td>' +
          '<td><input type="hidden" name="" id= "numbermonto" value="">S/' + monto + '</td>' +
          '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPe(' + contPe + ')"><i class="fas fa-trash-alt"></i></button></td>' +
          '</tr>';
        contPe++;
        limpiarPe();
        $("#total_pedido").html("S/. " + total_pedido.toLocaleString("en-US"));
        $("#total_pedido_pagar").val(total_pedido.toLocaleString("en-US"));
        $('#tabla_pedidos').append(filasPe);
        Remove_options(Pedido_delete);
      } else {
        Swal.fire(
          'Error!',
          'Error al agregar el pedido',
          'warning')
      }
    }    
    
    function limpiarPe() {
      $("#ppedido_id").val("");
      $("#total_pedido").val("");
    }


    function eliminarPe(index) {
      total_pedido = total_pedido - subtotal_pedido[index];
      $("#total_pedido").html("S/. " + total_pedido.toLocaleString("en-US"));
      $("#total_pedido_pagar").val(total_pedido);
      $("#filasPe" + index).remove();
      evaluarPe();
    }


    
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
