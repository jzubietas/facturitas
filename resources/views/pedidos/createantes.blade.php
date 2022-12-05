@extends('adminlte::page')

@section('title', 'Agregar pedidos')

@section('content_header')
  <h1>Agregar pedidos</h1>
  {{-- @error('num_ruc')
    <small class="text-danger" style="font-size: 16px">{{ $message }}</small>
  @enderror --}}  
@stop

@section('content')
    {!! Form::open(['route' => 'pedidos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
      @include('pedidos.partials.form')
      <div class="card-footer" id=guardar>
        <button type="submit" class="btn btn-success" id="btnImprimir" target="_blank"><i class="fas fa-save"></i> Guardar</button>

        @if (Auth::user()->rol == "Asesor" || Auth::user()->rol == "Super asesor")
          <a class="btn btn-danger" href="{{ route('pedidos.mispedidos') }}"><i class="fas fa-times-circle"></i> ATRAS</a>
        @else
          <a href="{{ route('pedidos.index') }}" class="btn btn-danger"><i class="fas fa-times-circle"></i> ATRAS</a>
        @endif        
      </div>
    {!! Form::close() !!}
  @include('pedidos.modal.AddRuc')
@stop

@section('css')
  {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> --}}
  <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
@stop

@section('js')
  <script src="{{ asset('js/datatables.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  
  @if (session('info') == 'registrado')
    <script>
      Swal.fire(
        'RUC {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif

  <script>
    $('#cliente_id').select2({    
      language: {

        noResults: function() {

          return "No se encontró al cliente” ";        
        },
        searching: function() {

          return "Buscando..";
        }
      }
    });
  </script>

  <script>
    $(document).ready(function() {
      $("form").keypress(function(e) {
        if (e.which == 13) {
          return false;
        }
      });
    });

    $(document).ready(function() {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });


      $(document).on("change","#user_id",function(){
        console.log("aaaaa "+$(this).val());
        $.ajax({
          url: "{{ route('cargar.clientedeasesor') }}?user_id=" + $(this).val(),
          method: 'GET',
          //before
          success: function(data) {
            //console.log(data.html);
            $('#cliente_id').html(data.html);
            $("#cliente_id").selectpicker("refresh");

            $('#cliente_id_ruc').html(data.html);
            let c_cliente_id=$('#cliente_id').val();
            console.log(c_cliente_id);
            
            $('#cliente_id_ruc').selectpicker('refresh');
            $('#cliente_id_ruc').val(c_cliente_id);

          }
        });
      });

       
      $('#modal-add-ruc').on('show.bs.modal', function (event) {
        let c_cliente_id=$('#cliente_id').val();//
        console.log(c_cliente_id+"id carga cliente para ruc");

        $('#cliente_id_ruc').val(c_cliente_id);
        $('#cliente_id_ruc').selectpicker('refresh');
      });

      $(document).on("submit", "#formulario2", function (evento) {
          evento.preventDefault();
          var formData = $("#formulario2").serialize();
          $.ajax({
              type:'POST',
              url:"{{ route('pedidos.agregarruc') }}",
              data:formData,
          }).done(function (data) {
            console.log(data.html);
            if(data.html=='true'){
              //ya paso
              Swal.fire(
                  'Ruc registrado correctamente',
                  '',
                  'success'
              );
              $("#modal-add-ruc").modal("hide"); 
            }else if(data.html=='false'){
              Swal.fire(
                  'Se actualizo razon social',
                  '',
                  'success'
              );
              $("#modal-add-ruc").modal("hide"); 
              //no paso
            }
          });
      });

      $(document).on("change","#cliente_id",function(){
          $.ajax({
            url: "{{ route('cargar.tipobanca') }}?cliente_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
              $('#ptipo_banca').html(data.html);
            }
          });
      });

      $(document).on("change","#cliente_id",function(){
          $.ajax({
            url: "{{ route('cargar.ruc') }}?cliente_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
              $('#pruc').html(data.html);
              $("#pruc").selectpicker("refresh");

            }
          });
      });

      /*$("#cliente_id").change(function() {
        $.ajax({
          url: "{{ route('cargar.ruc') }}?cliente_id=" + $(this).val(),
          method: 'GET',
          success: function(data) {
            $('#pruc').html(data.html);
            $("#pruc").selectpicker("refresh");

          }
        });
      });*/

      /*$("#cliente_id").change(function() {
        $.ajax({
          url: "{{ route('cargar.tipobanca') }}?cliente_id=" + $(this).val(),
          method: 'GET',
          success: function(data) {
            $('#ptipo_banca').html(data.html);
          }
        });
      });*/

    });
    ///fin

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

    //VALIDAR CAMPO RUC
    function maxLengthCheck(object)
    {
      if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
    }

    // CARGAR RUCS DE CLIENTE SELECCIONADO
    

    // CARGAR CLIENTES DE ASESOR
   
    
    // CARGAR TIPO DE COMPROBANTE Y BANCA/PORCENTAJES DE CLIENTE SELECCIONADO
    

    //VALIDACION DE CAMPOS
    $(document).ready(function() {
      $('#bt_add').click(function() {
        if ($('#pcliente_id').val() == '') {
          Swal.fire(
            'Error',
            'Seleccione Cliente',
            'warning'
          )
        }
        /*else if ($('#pempresa').val() == ''){
          Swal.fire(
            'Error',
            'Agregue nombre de empresa',
            'warning'
          )
        }*/
        else if ($('#pmes').val() == '') {
          Swal.fire(
            'Error',
            'Seleccione mes',
            'warning'
          )
        }
        else if ($('#panio').val() == '') {
          Swal.fire(
            'Error',
            'Agregue el año',
            'warning'
          )
        }
        else if ($('#pruc').val() == '') {
          Swal.fire(
            'Error',
            'Agregue número de RUC',
            'warning'
          )
        }
        else if ($('#pruc').val()<0) {
          Swal.fire(
            'Error',
            'El número de RUC no puede ser negativo',
            'warning'
          )
        }
        else if ($('#pruc').val().length < 11) {
          Swal.fire(
            'Error',
            'Número de RUC incompleto',
            'warning'
          )
        }
        else if ($('#pruc').val().length > 11) {
          Swal.fire(
            'Error',
            'Número de RUC debe teber máximo 11 dígitos',
            'warning'
          )
        }    
        else if ($('#pcantidad').val() == '') {
          Swal.fire(
            'Error',
            'Agregue cantidad',
            'warning'
          )
        }
        else if ($('#pcantidad').val()<0) {
          Swal.fire(
            'Error',
            'Ingrese una cantidad válida',
            'warning'
          )
        }
        else if ($('#ptipo_banca').val() == '') {
          Swal.fire(
            'Error',
            'Seleccione tipo de comprobante y banca',
            'warning'
          )
        }
        else if ($('#pporcentaje').val() == '') {
          Swal.fire(
            'Error',
            'Agregue porcentaje(%)',
            'warning'
          )
        }
        else if ($('#pporcentaje').val()<0) {
          Swal.fire(
            'Error',
            'Ingrese un porcentaje(%) válido',
            'warning'
          )
        }
        else if ($('#pcourier').val() == '') {
          Swal.fire(
            'Error',
            'Agregue costo de courier (S/)',
            'warning'
          )
        }
        else if ($('#pcourier').val()<0) {
          Swal.fire(
            'Error',
            'Ingrese un costo de courier (S/) válido',
            'warning'
          )
        }
        else if ($('#pdescripcion').val() == ''){
          Swal.fire(
            'Error',
            'Agregue descripción del pedido',
            'warning'
          )
        }
        else if ($('#pdescripcion').val().length > 250){//
          Swal.fire(
            'Error',
            'Se acepta máximo 200 caracteres',
            'warning'
          )
        }
        else if ($('#pnota').val() == ''){
          Swal.fire(
            'Error',
            'Agregue nota del pedido',
            'warning'
          )
        }
        else if ($('#pnota').val().length > 250){
          Swal.fire(
            'Error',
            'Se acepta máximo 200 caracteres',
            'warning'
          )
        }
        else {
          cantidad = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
            agregar();
        }
      })
    });

    var cont = 0;
    total = 0;
    subtotal = [];
    $("#guardar").hide();
    $("#ptipo_banca").change(mostrarValores);

    function mostrarValores() {
      datosTipoBanca = document.getElementById('ptipo_banca').value.split('_');
      $("#pporcentaje").val(datosTipoBanca[1]);
    }

    function agregar() {
      datosTipoBanca = document.getElementById('ptipo_banca').value.split('_');
      datosCodigo = document.getElementById('pcodigo').value.split('-');

      var strEx = $("#pcantidad").val();//1,000.00
      //primer paso: fuera coma
      strEx = strEx.replace(",","");//1000.00
      var numFinal = parseFloat(strEx);
      cantidad = numFinal*1;

      var strEx = $("#pcourier").val();//1,000.00
      //primer paso: fuera coma
      strEx = strEx.replace(",","");//1000.00
      var numFinal = parseFloat(strEx);
      courier = numFinal*1;

      codigo = $("#pcodigo").val();
      numped = datosCodigo[1];
      nombre_empresa = $("#pempresa").val();
      mes = $("#pmes").val();
      anio = $("#panio").val();
      ruc = $("#pruc").val();
      /* cantidad = $("#pcantidad").val(); */
      tipo_banca = datosTipoBanca[0];
      porcentaje = $("#pporcentaje").val();
      /* courier = $("#pcourier").val(); */
      descripcion = $("#pdescripcion").val();
      nota = $("#pnota").val();

      if (nombre_empresa != "" && mes != "") {
        subtotal[cont] = (cantidad * porcentaje)/100;
        total = Number(courier) + subtotal[cont];

        var fila = '<tr class="selected" id="fila' + cont + '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont + ');">X</button></td>' +
          '<td><input type="hidden" name="codigo[]" value="' + codigo + '">' + codigo + '</td>' +
          '<td><input type="hidden" name="nombre_empresa[]" value="' + nombre_empresa + '">' + nombre_empresa + '</td>' +
          '<td><input type="hidden" name="mes[]" value="' + mes + '">' + mes + '</td>' + 
          '<td><input type="hidden" name="anio[]" value="' + anio + '">' + anio + '</td>' +
          '<td><input type="hidden" name="ruc[]" value="' + ruc + '">' + ruc + '</td>' + 
          '<td><input type="hidden" name="cantidad[]" value="' + cantidad + '">' + cantidad.toLocaleString("en-US") + '</td>' + 
          '<td><input type="hidden" name="tipo_banca[]" value="' + tipo_banca + '">' + tipo_banca + '</td>' + 
          '<td><input type="hidden" name="porcentaje[]" value="' + porcentaje + '">' + porcentaje + '</td>' + 
          '<td><input type="hidden" name="courier[]" value="' + courier + '">' + courier + '</td>' +
          '<td><input type="hidden" name="descripcion[]" value="' + descripcion + '">' + descripcion + '</td>' + 
          '<td><input type="hidden" name="nota[]" value="' + nota + '">' + nota + '</td>' + 
          '<td>@csrf<input type="file" id="adjunto" name="adjunto[]" multiple=""/></td>' + 
          '<td>' + subtotal[cont].toLocaleString("en-US") + '</td></tr>';
        cont++; //accept= ".zip, .rar" 
        limpiar();
        $("#total").html("S/. " + total.toLocaleString("en-US"));
        evaluar();
        $('#detalles').append(fila);        
      } else {
        alert("error al ingresar el detalle del pedido, revise los datos");
      }
    }

    function limpiar() {
      /* $("#pcodigo").val("{{ $fecha }}-"+( Number(numped)+1 )); */
      $("#pcodigo").val("");
      $("#pempresa").val("");
      $('#pmes').val('').change();
      $('#panio').val('').change();
      $("#pruc").val("");
      $("#pcantidad").val("");
      $('#ptipo_banca').val('').change();
      $("#pporcentaje").val("");
      $("#pcourier").val("");
      $("#pdescripcion").val("");
      $("#pnota").val("");
    }

    function evaluar() {
      if (total > 0) {
        $("#guardar").show();
      } else {
        $("#guardar").hide();
      }

      if(cont>0){
        $("#bt_add").hide();
      }else {
        $("#bt_add").show();
      }
    }

    function eliminar(index) {
      $("#total").html("S/. 0.00");
      $("#fila" + index).remove();
      cont--;
      evaluar();
      $("#pcodigo").val("{{ Auth::user()->identificador }}-{{ $fecha }}-{{ $numped}}");
    }
  </script>

  <script>
    //VALIDAR ANTES DE ENVIAR
    /*document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });*/

    /*function validarFormulario(evento) {
      evento.preventDefault();
        
        window.open('https://sistema.ojoceleste.com/pedidos.mispedidos', '_blank');//CAMBIAR A LINK DE PRODUCCION//************
        this.submit();
    }*/
  </script>

  <script>
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
@stop
