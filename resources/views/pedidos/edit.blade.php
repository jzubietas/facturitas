@extends('adminlte::page')

@section('title', 'Editar pedido')

@section('content_header')
    @foreach ($pedidos as $pedido)
        @if ($pedido->id < 10)
            <h1>Editar pedido: PED000{{ $pedido->id }}</h1>
        @elseif($pedido->id < 100)
            <h1>Editar pedido: PED00{{ $pedido->id }}</h1>
        @elseif($pedido->id < 1000)
            <h1>Editar pedido: PED0{{ $pedido->id }}</h1>
        @else
            <h1>Editar pedido: PED{{ $pedido->id }}</h1>
            @endif
            @endforeach
            </h1>
            @stop

            @section('content')

                {{-- <div class="card"> --}}
                {!! Form::model($pedido, ['route' => ['pedidos.update', $pedido], 'method' => 'put','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}

                <div class="card">
                    <div class="card-body">
                        <div class="border rounded card-body border-secondary">
                            <div class="card-body">
                                <div class="form-row">
                                  <input type="hidden" id="txtValidaSobre" name="txtValidaSobre" value="Si">
                                    @foreach ($pedidos as $pedido)
                                        <div class="form-group col-lg-6">
                                            {!! Form::label('user_id', 'Asesor') !!}
                                            <input type="text" name="user_name" value="{{ $pedido->users }}"
                                                   class="form-control" disabled>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            {!! Form::label('pcliente_id', 'Cliente*') !!}
                                            <input type="text" name="user_name"
                                                   value="{{ $pedido->nombres }} - {{ $pedido->celulares }}"
                                                   class="form-control" disabled>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="border rounded card-body border-secondary" id="vertabla">
                            <div class="card-body">
                                <div class="form-row">
                                    @foreach ($pedidos as $pedido)
                                        <div class="form-group col-lg-2">
                                            {!! Form::label('pcodigo', 'Codigo') !!}
                                            {{Form::input('text','pcodigo',$pedido->codigos,['id'=>'pcodigo','class'=>'form-control','disabled'=>'disabled'])}}
                                        </div>
                                        <div class="form-group col-lg-4">
                                            {!! Form::label('pempresa', 'Nombre de empresa') !!}
                                            {{Form::input('text','pempresa',$pedido->empresas,['id'=>'pempresa','placeholder'=>'Nombre de empresa...','class'=>'form-control','disabled'=>'disabled'])}}
                                        </div>
                                        <div class="form-group col-lg-3">
                                            {!! Form::label('pmes', 'Mes') !!}
                                            {!! Form::select('pmes', $meses , $pedido->mes, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                        </div>
                                        <div class="form-group col-lg-3">
                                            {!! Form::label('panio', 'Año') !!}
                                            {{-- {!! Form::number('panio', $pedido->anio, ['class' => 'form-control', 'id' => 'panio', 'min' =>'0']) !!} --}}
                                            {!! Form::select('panio', $anios , $pedido->anio, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                        </div>
                                        <div class="form-group col-lg-2">
                                            {!! Form::label('pruc', 'RUC') !!}

                                            @if ($mirol =='Administrador')
                                                {!! Form::select('pruc', $rucs , $pedido->ruc, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                                            @else
                                                <input type="number" name="pruc" id="pruc" value="{{ $pedido->ruc}}"
                                                       step="1" min="0" max="99999999999" maxlength="11"
                                                       oninput="maxLengthCheck(this)" class="form-control"
                                                       placeholder="RUC..." readonly>
                                            @endif

                                        </div>
                                        <div class="form-group col-lg-2">
                                            {!! Form::label('pcantidad', 'Cantidad') !!}
                                            {{-- <input type="number" name="pcantidad" id="pcantidad"  step="0.01" min="0" class="form-control" placeholder="Cantidad...">  --}}
                                            <input type="text" name="pcantidad" id="pcantidad"
                                                   value="{{ $pedido->cantidad}}" step="0.01" min="0"
                                                   class="form-control number" placeholder="Cantidad...">
                                        </div>
                                        <div class="form-group col-lg-3">
                                            {!! Form::label('ptipo_banca', 'Tipo de comprobante y banca') !!}
                                            {{-- {!! Form::select('ptipo_banca', $porcentajes['nombre'] , $pedido->porcentaje, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!} --}}
                                            {{-- @php($valor_porcentaje = $pedido->tipo_banca) --}}
                                            <select name="ptipo_banca"
                                                    class="border form-control selectpicker border-secondary"
                                                    id="ptipo_banca" data-live-search="true">
                                                <option value="">---- SELECCIONE ----</option>
                                                @foreach($porcentajes as $porcentaje)
                                                    <option
                                                        value="{{ $porcentaje->nombre }}_{{ $porcentaje->porcentaje }}" {{ ($porcentaje->nombre == $pedido->tipo_banca ? "selected" : "") }}>{{$porcentaje->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            {!! Form::label('pporcentaje', 'Porcentaje(%)') !!}

                                            @if ($mirol =='Administrador' || $mirol =='Jefe de llamadas')
                                                <input type="number" name="pporcentaje" id="pporcentaje"
                                                       value="{{ $pedido->porcentaje}}" step="0.1" min="0"
                                                       class="form-control" placeholder="Porcentaje...">
                                            @else
                                                <input type="number" name="pporcentaje" id="pporcentaje"
                                                       value="{{ $pedido->porcentaje}}" step="0.1" min="0"
                                                       class="form-control" placeholder="Porcentaje..."
                                                       readonly="readonly">
                                            @endif
                                        </div>
                                        <div class="form-group col-lg-3">
                                            {!! Form::label('pcourier', 'Courier(S/)') !!}
                                            {{-- <input type="number" name="pcourier" id="pcourier" value="{{ $pedido->courier}}" step="0.01" min="0" class="form-control" placeholder="Courier..."> --}}
                                            <input type="text" name="pcourier" id="pcourier"
                                                   value="{{ $pedido->courier}}" step="0.01" min="0"
                                                   class="form-control number" placeholder="Courier...">
                                        </div>
                                        <div class="form-group col-lg-4">
                                            {!! Form::label('pdescripcion', 'Descripción') !!}
                                            {{Form::input('text','pdescripcion',$pedido->descripcion,['id'=>'pdescripcion','class'=>'form-control','placeholder'=>'Descripción...'])}}
                                        </div>
                                        <div class="form-group col-lg-4">
                                            {!! Form::label('pnota', 'Nota') !!}
                                            {{Form::input('text','pnota',$pedido->nota,['id'=>'pnota','class'=>'form-control','placeholder'=>'Nota...'])}}
                                        </div>
                                        <div class="form-group col-lg-2">
                                            {!! Form::label('padjunto', 'Adjuntos actuales') !!}
                                            @foreach ($imagenes as $img)
                                                @if($img->adjunto <> "logo_facturas.png")
                                                    <p>
                                                        {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}

                                                        <a target="_blank" download
                                                           href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
                                                    </p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="form-group col-lg-2" style="margin-top: 30px;text-align: center;">
                                            <div class="form-group">
                                                <button type="button" id="bt_add" class="btn btn-primary"><i
                                                        class="fas fa-plus-circle"></i> Agregar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="text-align: center">
                                        <div class="table-responsive">
                                            <table id="detalles"
                                                   class="table table-striped table-bordered table-condensed table-hover ">
                                                <thead style="background-color: #A9D0F5">
                                                <th style="vertical-align: middle">Opciones</th>
                                                <th style="vertical-align: middle">Código</th>
                                                <th style="vertical-align: middle">Empresa</th>
                                                <th style="vertical-align: middle">Mes</th>
                                                <th style="vertical-align: middle">Año</th>
                                                <th style="vertical-align: middle">RUC</th>
                                                <th style="vertical-align: middle">Cantidad</th>
                                                <th style="vertical-align: middle">Tipo de comprobante<br>y banca</th>
                                                <th style="vertical-align: middle">Porcentaje</th>
                                                <th style="vertical-align: middle">Courier</th>
                                                <th style="vertical-align: middle">Descripción</th>
                                                <th style="vertical-align: middle">Nota</th>
                                                <th style="vertical-align: middle">Adjunto</th>
                                                <th style="vertical-align: middle">FT</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                <th style="text-align: center">TOTAL</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center"><h4 id="total">S/. 0.00</h4></th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" id=guardar>
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                    <div class="form-group col-lg-2" style="margin-top: 10px;margin-left: -75px;text-align: center;">
                        <button type="button" onClick="history.back()" class="btn btn-danger btn-lg"><i
                                class="fas fa-arrow-left"></i>ATRAS
                        </button>
                    </div>
                    {{-- </div> --}}
                    {!! Form::close() !!}
                </div>

            @stop

            @section('css')

            @stop

            @section('js')
                <script>
                    $(document).ready(function () {
                        $("form").keypress(function (e) {
                            if (e.which == 13) {
                                return false;
                            }
                        });
                    });

                    //VALIDAR CAMPOS NUMERICO DE MONTO EN PAGOS

                    $('input.number').keyup(function (event) {

                        if (event.which >= 37 && event.which <= 40) {
                            event.preventDefault();
                        }

                        $(this).val(function (index, value) {
                            return value
                                .replace(/\D/g, "")
                                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
                                ;
                        });
                    });

                    //VALIDAR CAMPO RUC
                    function maxLengthCheck(object) {
                        if (object.value.length > object.maxLength)
                            object.value = object.value.slice(0, object.maxLength)
                    }

                    $(document).ready(function () {
                        $('#bt_add').click(function () {
                            if ($('#pempresa').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Agregue nombre de empresa',
                                    'warning'
                                )
                            } else if ($('#pmes').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Seleccione mes',
                                    'warning'
                                )
                            } else if ($('#panio').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Agregue el año',
                                    'warning'
                                )
                            } else if ($('#pruc').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Agregue número de RUC',
                                    'warning'
                                )
                            } else if ($('#pruc').val() < 0) {
                                Swal.fire(
                                    'Error',
                                    'El número de RUC no puede ser negativo',
                                    'warning'
                                )
                            } else if ($('#pruc').val().length < 11) {
                                Swal.fire(
                                    'Error',
                                    'Número de RUC incompleto',
                                    'warning'
                                )
                            } else if ($('#pruc').val().length > 11) {
                                Swal.fire(
                                    'Error',
                                    'Número de RUC debe teber máximo 11 dígitos',
                                    'warning'
                                )
                            } else if ($('#pcantidad').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Agregue cantidad',
                                    'warning'
                                )
                            } else if ($('#pcantidad').val() < 0) {
                                Swal.fire(
                                    'Error',
                                    'Ingrese una cantidad válida',
                                    'warning'
                                )
                            } else if ($('#ptipo_banca').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Seleccione tipo de comprobante y banca',
                                    'warning'
                                )
                            } else if ($('#pporcentaje').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Agregue porcentaje(%)',
                                    'warning'
                                )
                            } else if ($('#pporcentaje').val() < 0) {
                                Swal.fire(
                                    'Error',
                                    'Ingrese un porcentaje(%) válido',
                                    'warning'
                                )
                            } else if ($('#pcourier').val() == '') {
                                Swal.fire(
                                    'Error',
                                    'Agregue costo de courier (S/)',
                                    'warning'
                                )
                            } else if ($('#pcourier').val() < 0) {
                                Swal.fire(
                                    'Error',
                                    'Ingrese un costo de courier (S/) válido',
                                    'warning'
                                )
                            } else {
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
                        strEx = strEx.replace(",", "");//1000.00
                        var numFinal = parseFloat(strEx);
                        cantidad = numFinal * 1;

                        var strEx = $("#pcourier").val();//1,000.00
                        //primer paso: fuera coma
                        strEx = strEx.replace(",", "");//1000.00
                        var numFinal = parseFloat(strEx);
                        courier = numFinal * 1;


                        codigo = $("#pcodigo").val();
                        nombre_empresa = $("#pempresa").val();
                        mes = $("#pmes").val();
                        anio = $("#panio").val();
                        ruc= $("#pruc").val();
                        console.log(ruc)
                        /* cantidad = $("#pcantidad").val(); */
                        tipo_banca = datosTipoBanca[0];
                        porcentaje = $("#pporcentaje").val();
                        /* courier = $("#pcourier").val(); */
                        descripcion = $("#pdescripcion").val();
                        nota = $("#pnota").val();

                        if (nombre_empresa != "" && mes != "") {
                            subtotal[cont] = (cantidad * porcentaje) / 100;
                            total = Number(courier) + subtotal[cont];

                            var fila = '<tr class="selected" id="fila' + cont + '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont + ');">X</button></td>' +
                                '<td><input type="hidden" name="codigo[]" value="' + codigo + '">' + codigo + '</td>' +
                                '<td><textarea class="d-none" name="nombre_empresa[]">' + nombre_empresa + '</textarea>' + nombre_empresa + '</td>' +
                                '<td><input type="hidden" name="mes[]" value="' + mes + '">' + mes + '</td>' +
                                '<td><input type="hidden" name="anio[]" value="' + anio + '">' + anio + '</td>' +
                                '<td><input type="hidden" name="ruc[]" value="' + ruc + '">' + ruc + '</td>' +
                                '<td><input type="hidden" name="cantidad[]" value="' + cantidad + '">' + cantidad.toLocaleString("en-US") + '</td>' +
                                '<td><input type="hidden" name="tipo_banca[]" value="' + tipo_banca + '">' + tipo_banca + '</td>' +
                                '<td><input type="hidden" name="porcentaje[]" value="' + porcentaje + '">' + porcentaje + '</td>' +
                                '<td><input type="hidden" name="courier[]" value="' + courier + '">' + courier + '</td>' +
                                '<td><textarea class="d-none" name="descripcion[]">' + descripcion + '</textarea>' + descripcion + '</td>' +
                                '<td><textarea class="d-none" name="nota[]">' + nota + '</textarea>' + nota + '</td>' +
                                '<td>@csrf<input type="file" id="adjunto" name="adjunto[]" multiple=""/></td>' +
                                '<td>' + subtotal[cont].toLocaleString("en-US") + '</td></tr>';
                            cont++;
                            limpiar();
                            $("#total").html("S/. " + total.toLocaleString("en-US"));
                            evaluar();
                            $('#detalles').append(fila);
                        } else {
                            alert("error al ingresar el detalle del pedido, revise los datos");
                        }
                    }

                    function limpiar() {
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

                        if (cont > 0) {
                            $("#bt_add").hide();
                        } else {
                            $("#bt_add").show();
                        }
                    }

                    function eliminar(index) {
                        location.reload();
                    }

                </script>
            @stop
