@extends('adminlte::page')

@section('title', 'Agregar Pago')

@section('content_header')
  <h1>Agregar pago2 </h1>
@stop

@section('content')
  <div class="card">
    {!! Form::open(['route' => 'envios.direccion','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
    <div class="card-header">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <div class="form-group col-lg-4">
            {!! Form::label('user_id', 'Asesor') !!}
            <input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">
            <input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>
          </div>
          <div class="form-group col-lg-4">
            {!! Form::label('cliente_id', 'Cliente') !!}
            <input type="hidden" name="cliente_id" requerid value="{{ $clientes->id }}" class="form-control">
            {!! Form::text('cliente_id', $clientes->nombre, ['class' => 'form-control', 'id' => 'cliente_id', 'disabled']) !!}
          </div>
          <div class="form-group col-lg-4">
            {!! Form::label('destino', 'Destino*') !!}
            {!! Form::select('destino', $destinos, null, ['class' => 'form-control', 'id' => 'destino', 'placeholder' => '-----SELECCIONE------']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group col-lg-6">
          <div class="form-row">
            <div class="form-group col-lg-6">
              <h2>PEDIDOS A ENVIAR</h2>
            </div>
            <div class="form-group col-lg-6">
              <a data-target="#modal-add-pedidos" id="addpedido" data-toggle="modal"><button class="btn btn-info"><i class="fas fa-plus-circle"></i></button></a>
            </div>
          </div>
          <div class="table-responsive">
            <table id="tabla_pedidos" class="table table-striped">
              <thead class="bg-info">
                <tr>
                  <th scope="col" style="vertical-align: middle">ITEM</th>
                  <th scope="col" style="vertical-align: middle">PEDIDO</th>
                  <th scope="col" style="vertical-align: middle">CODIGO</th>
                  <th scope="col" style="vertical-align: middle">ACCIÓN</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
        </div>
        <div id="lima" class="form-group col-lg-6">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="form-row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center; font-weight: bold;">
                  <p>Ingrese la dirección de envío - LIMA</p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('nombre', 'Nombre de quien recibe el sobre') !!}
                  {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('celular', 'Número de contacto') !!}
                  {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('distrito', 'Distrito') !!}
                  {!! Form::select('distrito', $distritos , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('direccion', 'Dirección') !!}
                  {!! Form::text('direccion', null, ['class' => 'form-control', 'placeholder' => 'Dirección']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('referencia', 'Referencia') !!}
                  {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referencia']) !!}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="provincia" class="form-group col-lg-6">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="form-row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center; font-weight: bold;">
                  <p>Ingrese datos del envío - PROVINCIA</p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('tracking', 'Número de tracking') !!}
                  {!! Form::text('tracking', null, ['class' => 'form-control', 'placeholder' => 'tracking']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('registro', 'Número de registro') !!}
                  {!! Form::number('registro', null, ['class' => 'form-control', 'placeholder' => 'Número de registro', 'id' => 'registro', 'min' =>'0', 'max' => '999999999999999', 'maxlength' => '15', 'oninput' => 'maxLengthCheck(this)']) !!}
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  {!! Form::label('foto', 'Foto') !!}
                  @csrf
                  {!! Form::file('foto', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="image-wrapper">
                    <img id="picture" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del gasto" height="200px" width="200px">
                  </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  {!! Form::label('importe', 'Importe(S/.)') !!}
                  {!! Form::text('importe', null, ['class' => 'form-control number', 'placeholder' => 'Importe pagado', 'id' => 'importe']) !!}
                </div>
              </div>
            </div>
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
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
        </div>
        <div class="form-group col-lg-3"></div>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
@stop

@section('js')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>

    $("#guardar").hide();
    $("#addpedido").hide();
    $("#lima").hide();
    $("#provincia").hide();
    $("#destino").change(mostrarBotones);

    function mostrarBotones() {
      destino = document.getElementById('destino').value

      $("#addpedido").show();
      if(destino == "LIMA"){
        $("#lima").show();
        $("#provincia").hide();
      }
      if(destino == "PROVINCIA"){
        $("#provincia").show();
        $("#lima").hide();
      }
    }

    //VALIDAR CAMPO CELULAR
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

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
      Pedido_delete = document.getElementById('ppedido_id').value;

      pedido_id = datosPedido[0];
      codigo = datosPedido[1];

      if (pedido_id != "") {

        var filasPe = '<tr class="selected" id="filasPe' + contPe + '">' +
          '<td>' + contPe + '</td>' +
          '<td style="display:none;" ><input type="hidden" name="pedido_id[]" value="' + pedido_id + '">' + pedido_id + '</td>' +
          '<td><input type="hidden" name="" value="">PED000' + pedido_id + '</td>' +
          '<td><input type="hidden" name="" value="">' + codigo + '</td>' +
          '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPe(' + contPe + ')"><i class="fas fa-trash-alt"></i></button></td>' +
          '</tr>';
        contPe++;
        limpiarPe();
        evaluarPe();
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

    function evaluarPe() {
      if (contPe > 1) {
        $("#guardar").show();
      } else {
        $("#guardar").hide();
      }
    }

    function eliminarPe(index) {
      total_pedido = total_pedido - subtotal_pedido[index];
      $("#total_pedido").html("S/. " + total_pedido.toLocaleString("en-US"));
      $("#total_pedido_pagar").val(total_pedido);
      $("#filasPe" + index).remove();
      evaluarPe();
    }

    //CAMBIAR IMAGEN
    document.getElementById("foto").addEventListener('change', cambiarImagen);

    function cambiarImagen(event){
        var file = event.target.files[0];

        var reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById("picture").setAttribute('src', event.target.result);
        };

        reader.readAsDataURL(file);
    }

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

@stop
