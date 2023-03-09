@section('js')
    <script type="text/javascript">
        function filterFloat(evt,input){
            // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
            var key = window.Event ? evt.which : evt.keyCode;
            var chark = String.fromCharCode(key);
            var tempValue = input.value+chark;
            var isNumber = (key >= 48 && key <= 57);
            var isSpecial = (key == 8 || key == 13 || key == 0 ||  key == 46);
            if(isNumber || isSpecial){
                return filter(tempValue);
            }

            return false;

        }
        function filter(__val__){
            var preg = /^([0-9]+\.?[0-9]{0,1})$/;
            return (preg.test(__val__) === true);
        }
    </script>
@endsection

<section id="tabs" class="project-tab">
        <div class="row">
            <div class="col-md-12">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="navCambioPorcentajetab" data-toggle="tab" href="#navCambioPorcentaje"
                           role="tab" aria-controls="navCambioPorcentaje" aria-selected="true">Cambio Porcentaje</a>
                        <a class="nav-item nav-link" id="navCambioAsesortab" data-toggle="tab" href="#navCambioAsesor"
                           role="tab" aria-controls="navCambioAsesor" aria-selected="false">Cambio Asesor</a>
                        <a class="nav-item nav-link" id="navCambioClientetab" data-toggle="tab" href="#navCambioCliente"
                           role="tab" aria-controls="navCambioCliente" aria-selected="false">Cambio Cliente</a>
                        <a class="nav-item nav-link" id="navCambioEmresatab" data-toggle="tab" href="#navCambioEmresa"
                           role="tab" aria-controls="navCambioEmresa" aria-selected="false">Cambio Nombre Empresa</a>
                        <a class="nav-item nav-link" id="navBloqueoRuctab" data-toggle="tab" href="#navBloqueoRuc"
                           role="tab" aria-controls="navBloqueoRuc" aria-selected="false">Bloqueo RUC</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="navCambioPorcentaje" role="tabpanel" aria-labelledby="navCambioPorcentajetab">
                        <div class="card">
                            <div class="card-header">
                                <h3>Cambio de Porcentaje</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body border border-secondary rounded">
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            {!! Form::label('cbxChangePorc', 'CLIENTE*') !!} &nbsp; &nbsp; &nbsp;
                                            <select name="cbxChangePorc"
                                                    class="border form-control border-secondary"
                                                    id="cbxChangePorc" data-live-search="true">
                                                <option value="-1">---- SELECCIONE CLIENTE ----</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            {!! Form::label('porcentaje', 'Porcentaje') !!}
                                            <table id="tabla_pagos" class="table table-striped">
                                                <thead class="bg-primary">
                                                <tr>
                                                    <th scope="col">ITEM</th>
                                                    <th scope="col"></th>
                                                    <th scope="col">TIPO</th>
                                                    <th scope="col">%</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                <tr class="selected" id="filas2">
                                                    <td>1</td>
                                                    <td><input type="hidden" name="nombreporcentaje[]" requerid value="FISICO - sin banca" class="form-control"></td>
                                                    <td><input type="text" name="nporcentaje[]" value="FISICO - sin banca" disabled class="form-control"></td>
                                                    <td>
                                                        <input type="number" step="0.1" name="porcentaje1" id="porcentaje1" value="0" min="1.3"  class="form-control" onkeypress="return filterFloat(event,this);">
                                                    </td>
                                                </tr>
                                                <tr class="selected" id="filas1">
                                                    <td>2</td>
                                                    <td><input type="hidden" name="nombreporcentaje[]" requerid value="FISICO - banca" class="form-control"></td>
                                                    <td><input type="text" name="nporcentaje[]" value="FISICO - banca" disabled class="form-control"></td>
                                                    <td><input type="number" step="0.1" name="porcentaje[]" id="porcentaje2" value="0" min="1.3"  class="form-control" onkeypress="return filterFloat(event,this);"></td>
                                                </tr>
                                                <tr class="selected" id="filas4">
                                                    <td>3</td>
                                                    <td><input type="hidden" name="nombreporcentaje[]" requerid value="ELECTRONICA - sin banca" class="form-control"></td>
                                                    <td><input type="text" name="nporcentaje[]" value="ELECTRONICA - sin banca" disabled class="form-control"></td>
                                                    <td><input type="number" step="0.1" name="porcentaje[]" id="porcentaje3" value="0" min="1.3"  class="form-control" onkeypress="return filterFloat(event,this);"></td>
                                                </tr>
                                                <tr class="selected" id="filas3">
                                                    <td>4</td>
                                                    <td><input type="hidden" name="nombreporcentaje[]" requerid value="ELECTRONICA - banca" class="form-control"></td>
                                                    <td><input type="text" name="nporcentaje[]" value="ELECTRONICA - banca" disabled class="form-control"></td>
                                                    <td><input type="number" step="0.1" name="porcentaje[]" id="porcentaje4" value="0" min="1.3"  class="form-control" onkeypress="return filterFloat(event,this);"></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" id="btnChangeProc" class="btn btn-info btn-lg">
                                            Actualizar Porcentaje
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navCambioAsesor" role="tabpanel" aria-labelledby="navCambioAsesortab">
                        <div class="card">
                            <div class="card-header">
                                <h3>Cambio de Asesor</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body border border-secondary rounded">
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            {!! Form::label('cbxAsesor', 'Asesor*') !!} &nbsp; &nbsp;
                                            <select name="cbxAsesor" class="border form-control border-secondary"
                                                    id="cbxAsesor" data-live-search="true">
                                                <option value="">---- SELECCIONE ASESOR ----</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            {!! Form::label('cbxCliente', 'Cliente*') !!} &nbsp; &nbsp; &nbsp;
                                            <select name="cbxCliente"
                                                    class="border form-control border-secondary"
                                                    id="cbxCliente" data-live-search="true">
                                                <option value="">---- SELECCIONE CLIENTE ----</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            {!! Form::label('cbxRucRel', 'RUC*') !!} &nbsp; &nbsp; &nbsp;
                                            <select name="cbxRucRel"
                                                    class="border form-control border-secondary"
                                                    id="cbxRucRel" data-live-search="true">
                                                <option value="">---- SELECCIONE RUC ----</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            {!! Form::label('pcantidad_tiempo', 'Empresa (Razon social)') !!}
                                            <input type="text" name="txtRazonSocialRel" id="txtRazonSocialRel"
                                                   class="form-control number" placeholder="Empresa (Razon social)..." readonly>
                                        </div>
                                        <button type="button" id="btnCrearNuevaRelacion" class="btn btn-info btn-lg">
                                            Crear nueva relacion
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navCambioCliente" role="tabpanel" aria-labelledby="navCambioClientetab">
                        <div class="card">
                            <div class="card-header">
                                <h3>Cambio de Ruc</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body border border-secondary rounded">
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            {!! Form::label('cbxChangeRuc', 'RUC*') !!} &nbsp; &nbsp; &nbsp;
                                            <select name="cbxChangeRuc"
                                                    class="border form-control border-secondary"
                                                    id="cbxChangeRuc" data-live-search="true">
                                                <option value="-1">---- SELECCIONE RUC ----</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            {!! Form::label('txtChangeRuc', 'Ingrese RUC') !!}
                                            {{--<input type="number" name="txtChangeRuc" id="txtChangeRuc" class="form-control number" placeholder="Ingrese RUC..." maxlength="11" minlength="11">--}}

                                            {!! Form::number('Ingrese RUC', null, ['class' => 'form-control number', 'id' => 'txtChangeRuc', 'name' => 'txtChangeRuc', 'min' =>'11', 'max' => '11', 'maxlength' => '11', 'oninput' => 'maxLengthCheck(this)']) !!}
                                        </div>
                                        <button type="button" id="btnChangeRuc" class="btn btn-info btn-lg">
                                            Actualizar RUC
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navCambioEmresa" role="tabpanel" aria-labelledby="navCambioEmresatab">
                        {{--navCambioEmresatab--}}
                        <div class="tab-pane fade show active" id="navCambioPorcentaje" role="tabpanel" aria-labelledby="navCambioPorcentajetab">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Cambio de Nombre de empresa</h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-body border border-secondary rounded">
                                        <div class="form-row">
                                            <div class="form-group col-lg-6">
                                                {!! Form::label('cbxRucChangeName', 'RUC*') !!} &nbsp; &nbsp; &nbsp;
                                                <select name="cbxRucChangeName"
                                                        class="border form-control border-secondary"
                                                        id="cbxRucChangeName" data-live-search="true">
                                                    <option value="-1">---- SELECCIONE RUC ----</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                {!! Form::label('pcantidad_tiempo', 'Empresa (Razon social)') !!}
                                                <input type="text" name="txtRazonSocialChangeName" id="txtRazonSocialChangeName"
                                                       class="form-control number" placeholder="Empresa (Razon social)...">
                                            </div>
                                            <button type="button" id="btnCambiarNombreChangeName" class="btn btn-success btn-lg">
                                                Cambiar Nombre
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navBloqueoRuc" role="tabpanel" aria-labelledby="navBloqueoRuctab">
                        {{--navBloqueoRuctab--}}
                    </div>
                </div>
            </div>
        </div>
</section>
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('getComboAsesor') }}",
                method: 'GET',
                success: function (data) {
                    console.log('Asesor',data);
                    $('#cbxAsesor').html(data.html);
                    $("#cbxAsesor").selectpicker("refresh").trigger("change");
                }
            });

            $.ajax({
                url: "{{ route('getComboCliente') }}",
                method: 'GET',
                success: function (data) {
                    console.log('Cliente',data);
                    $('#cbxCliente').html(data.html);
                    $("#cbxCliente").selectpicker("refresh");
                }
            });
            cargaComboRuc();
            function cargaComboRuc(){
                $.ajax({
                    url: "{{ route('getComboRuc') }}",
                    method: 'GET',
                    success: function (data) {
                        console.log('Ruc',data);
                        $('#cbxRucChangeName').html(data.html);
                        $("#cbxRucChangeName").selectpicker("refresh");

                        $('#cbxRucRel').html(data.html);
                        $("#cbxRucRel").selectpicker("refresh");

                        $('#cbxChangeRuc').html(data.html);
                        $("#cbxChangeRuc").selectpicker("refresh");
                    }
                });
            }
            cargaComboClientes();
            function cargaComboClientes(){
                $.ajax({
                    url: "{{ route('getComboClientes') }}",
                    method: 'GET',
                    success: function (data) {
                        console.log('Clientes',data);
                        $('#cbxChangePorc').html(data.html);
                        $("#cbxChangePorc").selectpicker("refresh");
                    }
                });
            }

            $(document).on("change", "#cbxChangePorc", function () {
                var frmPorc = new FormData();
                frmPorc.append('cliente_id', $('#cbxChangePorc').val());

                $.ajax({
                    processData: false,
                    contentType: false,
                    data: frmPorc,
                    type: 'POST',
                    url: "{{ route('getPorcClientes') }}",
                    success: function (data) {
                        $('#porcentaje1').val(data.html[0].porcentaje);
                        $('#porcentaje2').val(data.html[2].porcentaje);
                        $('#porcentaje3').val(data.html[1].porcentaje);
                        $('#porcentaje4').val(data.html[3].porcentaje);
                    }
                });

            });

            $(document).on("click", "#btnChangeProc", function () {
                var cbChangePor=$('#cbxChangePorc').val();
                var porcentaje1=$('#porcentaje1').val();
                var porcentaje2=$('#porcentaje2').val();
                var porcentaje3=$('#porcentaje3').val();
                var porcentaje4=$('#porcentaje4').val();
                if (cbChangePor=='-1'){
                    Swal.fire('Error','Debe seleccionar el cliente.','error'); return false;
                }
                if (porcentaje1<1.3){
                    Swal.fire('Error','El porcentaje [FISICO - sin banca] debe ser mayor a 1.3%','error'); return false;
                }
                if (porcentaje2<1.3){
                    Swal.fire('Error','El porcentaje [FISICO - banca] debe ser mayor a 1.3%','error'); return false;
                }
                if (porcentaje3<1.3){
                    Swal.fire('Error','El porcentaje [ELECTRONICA - sin banca] debe ser mayor a 1.3%','error'); return false;
                }
                if (porcentaje4<1.3){
                    Swal.fire('Error','El porcentaje [ELECTRONICA - banca] debe ser mayor a 1.3%','error'); return false;
                }
                var frmPorcentaje = new FormData();
                frmPorcentaje.append('cliente_id', cbChangePor);
                frmPorcentaje.append('porcentaje1', porcentaje1);
                frmPorcentaje.append('porcentaje2', porcentaje2);
                frmPorcentaje.append('porcentaje3', porcentaje3);
                frmPorcentaje.append('porcentaje4', porcentaje4);

                $.ajax({
                    processData: false,
                    contentType: false,
                    data: frmPorcentaje,
                    type: 'POST',
                    url: "{{ route('uptPorcClientes') }}",
                    success: function (data) {
                        if (data.success){
                            Swal.fire('Mensaje','Se actualizaron los porcentajes correctamente','success')
                            limpiarformporcentaje();
                        }

                    }
                });
            })
            function  limpiarformporcentaje(){
                $('#cbxChangePorc').val('-1');
                $('#porcentaje1').val(0);
                $('#porcentaje2').val(0);
                $('#porcentaje3').val(0);
                $('#porcentaje4').val(0);
                $("#cbxChangePorc").selectpicker("refresh");
            }
            $("#cbxRucChangeName").on("change", function () {
                var data_raz_soc = $("option[value=" + $(this).val() + "]", this).attr('data-raz-soc');
                $('#txtRazonSocialChangeName').val(data_raz_soc);
            });

            $("#cbxRucRel").on("change", function () {
                var data_raz_soc = $("option[value=" + $(this).val() + "]", this).attr('data-raz-soc');
                $('#txtRazonSocialRel').val(data_raz_soc);
            });

            $("#cbxChangeRuc").on("change", function () {
                var data_ruc = $("option[value=" + $(this).val() + "]", this).attr('data-ruc');
                $('#txtChangeRuc').val(data_ruc);
            });

            $("#btnCambiarNombreChangeName").click(function () {
                $("#btnCambiarNombreChangeName").attr('disabled', 'disabled')
                var data = {}
                data.cliente_id = $("#cbxRucChangeName").val()
                data.cliente_nombre = $("#txtRazonSocialChangeName").val()
                if (data.cliente_nombre =='' ){
                    Swal.fire(
                        'Error',
                        'Ingrese la razon social',
                        'error'
                    )
                    $("#btnCambiarNombreChangeName").removeAttr('disabled')
                    return false;
                }
                $.post('{{route('updateNameEmpresa')}}', data)
                    .done(function (data) {
                        console.log(data)
                        if (data.success) {
                            Swal.fire(
                                'Notificacion',
                                'Se actualizo el nombre correctamente',
                                'success'
                            )
                            cargaComboRuc();
                            $("#txtRazonSocialChangeName").val('')
                        } else {
                            Swal.fire(
                                'Notificacion',
                                'Los datos no fueron guardados',
                                'warning'
                            )
                        }
                    })
                    .fail(function (data) {
                        console.log(data)
                        if (data.responseJSON.errors) {
                            Swal.fire(
                                'Error',
                                Object.keys(data.responseJSON.errors).map(function (key) {
                                    return `<b>${data.responseJSON.errors[key][0]}</b>`
                                }).join('<hr class="my-1"><br>'),
                                'error'
                            )
                        } else {
                            Swal.fire(
                                'Error',
                                'Ocurrio un error al intentar guardar la información',
                                'error'
                            )
                        }
                    })
                    .always(function () {
                        $("#btnCambiarNombreChangeName").removeAttr('disabled')
                    })
            })

            /*
            $("#btnCrearNuevaRelacion").click(function () {
             $("#btnCrearNuevaRelacion").attr('disabled', 'disabled')
                var data = {}
                data.cliente_id = $("#cbxRuc").val()
                data.cliente_nombre = $("#txtRazonSocial").val()
                if (data.cliente_nombre =='' ){
                    Swal.fire(
                        'Error',
                        'Ingrese la razon social',
                        'error'
                    )
                    $("#btnCrearNuevaRelacion").removeAttr('disabled')
                    return false;
                }
                $.post('route('updateNameEmpresa')', data)
                    .done(function (data) {
                        console.log(data)
                        if (data.success) {
                            Swal.fire(
                                'Notificacion',
                                'Se actualizo el nombre correctamente',
                                'success'
                            )
                            cargaComboRuc();
                            $("#txtRazonSocial").val('')
                        } else {
                            Swal.fire(
                                'Notificacion',
                                'Los datos no fueron guardados',
                                'warning'
                            )
                        }
                    })
                    .fail(function (data) {
                        console.log(data)
                        if (data.responseJSON.errors) {
                            Swal.fire(
                                'Error',
                                Object.keys(data.responseJSON.errors).map(function (key) {
                                    return `<b>${data.responseJSON.errors[key][0]}</b>`
                                }).join('<hr class="my-1"><br>'),
                                'error'
                            )
                        } else {
                            Swal.fire(
                                'Error',
                                'Ocurrio un error al intentar guardar la información',
                                'error'
                            )
                        }
                    })
                    .always(function () {
                        $("#btnCrearNuevaRelacion").removeAttr('disabled')
                    })
            })
            */
            $("#btnChangeRuc").click(function () {
                $("#btnChangeRuc").attr('disabled', 'disabled')
                var data = {}
                data.cliente_id = $("#cbxChangeRuc").val()
                data.cliente_ruc= $("#txtChangeRuc").val()
                if (data.cliente_ruc =='' ){
                    Swal.fire(
                        'Error',
                        'Ingrese el RUC',
                        'error'
                    )
                    $("#btnChangeRuc").removeAttr('disabled')
                    return false;
                }
                $.post('{{route('updateRuc')}}', data)
                    .done(function (data) {
                        console.log(data)
                        if (data.success) {
                            Swal.fire(
                                'Notificacion',
                                'Se actualizo el RUC correctamente',
                                'success'
                            )
                            cargaComboRuc();
                            $("#txtChangeRuc").val('')
                        } else {
                            Swal.fire(
                                'Notificacion',
                                'Los datos no fueron guardados',
                                'warning'
                            )
                        }
                    })
                    .fail(function (data) {
                        console.log(data)
                        if (data.responseJSON.errors) {
                            Swal.fire(
                                'Error',
                                Object.keys(data.responseJSON.errors).map(function (key) {
                                    return `<b>${data.responseJSON.errors[key][0]}</b>`
                                }).join('<hr class="my-1"><br>'),
                                'error'
                            )
                        } else {
                            Swal.fire(
                                'Error',
                                'Ocurrio un error al intentar guardar la información',
                                'error'
                            )
                        }
                    })
                    .always(function () {
                        $("#btnChangeRuc").removeAttr('disabled')
                    })
            })

            tblPorcentajes = $('#tblPorcentajes').DataTable({
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                columns:
                    [
                        {
                            data: 'accion',
                            name: 'accion',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<button type="button" class="btn btn-danger btn-sm remove" item="' + row.item + '"><i class="fas fa-trash-alt"></i>' + row.item + '</button>';
                            }
                        },
                        {
                            data: 'item',
                            name: 'item',
                            sWidth: '10%',
                        },
                        {
                            data: 'movimiento',
                            name: 'movimiento',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="tipomovimiento[' + row.item + ']" value="' + data + '"><span class="tipomovimiento">' + data + '</span></td>';
                            }
                        },
                        {
                            data: 'titular',
                            name: 'titular',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="titular[' + row.item + ']" value="' + data + '"><span class="titular">' + data + '</span></td>';
                            }
                        },
                        {
                            data: 'banco',
                            name: 'banco',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="banco[' + row.item + ']" value="' + data + '"><span class="banco">' + data + '</span></td>';
                            }
                        },
                        {
                            data: 'bancop',
                            name: 'bancop',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="bancop[' + row.item + ']" value="' + data + '"><span class="bancop">' + data + '</span></td>';
                            },
                            "visible": false,
                        },
                        {
                            data: 'obanco',
                            name: 'obanco',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="obanco[' + row.item + ']" value="' + data + '"><span class="obanco">' + data + '</span></td>';
                            },
                            "visible": false,
                        },
                        {
                            data: 'fecha',
                            name: 'fecha',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="fecha[' + row.item + ']" value="' + data + '"><span class="fecha">' + data + '</span></td>';
                            }
                        },
                        {
                            data: 'imagen',
                            name: 'imagen',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                //return '<input type="hidden" name="fecha['+row.item+']" value="' + data + '"><span class="fecha">' + data + '</span></td>';
                                //<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/>
                                //<input type="file" id="imagen" name="imagen[]" accept= "image/*" style="width:150px;"/>
                                var str = "storage/pagos/" + data;
                                var urlimage = '{{ asset(":id") }}';

                                urlimage = urlimage.replace(':id', str);
                                data = '<input type="hidden" name="imagen[' + row.item + ']" value="' + data + '"></td><img src="' + urlimage + '" alt="' + urlimage + '" height="200px" width="200px" class="img-thumbnail">';
                                //'<input type="file" id="imagen'+row.item+'" name="imagen['+row.item+']" accept="image/*" style="width:150px;">';
                                return data

                            }
                        },
                        {
                            data: 'monto',
                            name: 'monto',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {

                                //$( api.column( 2 ).footer() ).html('<input type="hidden" name="total_pedido" id="total_pedido" value="'+pageTotal.toFixed(2)+'"/>'+
                                //'S/. '+separateComma(pageTotal.toFixed(2)).toLocaleString("en-US")  );


                                return '<input type="hidden" name="monto[' + row.item + ']" value="' + data + '"><span class="monto">' + data + '</span></td>';
                            }
                        },
                        {
                            data: 'operacion',
                            name: 'operacion',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="operacion[' + row.item + ']" value="' + data + '"><span class="operacion">' + data + '</span></td>';
                            },
                            "visible": false,
                        },
                        {
                            data: 'nota',
                            name: 'nota',
                            sWidth: '10%',
                            render: function (data, type, row, meta) {
                                return '<input type="hidden" name="nota[' + row.item + ']" value="' + data + '"><span class="nota">' + data + '</span></td>';
                            },
                            "visible": false,
                        },

                    ],
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    nb_cols = 4;
                    api.columns().nodes().length;
                    var j = 2;

                    //para footer  monto
                    var pageTotal = api
                        .column(8, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return Number(a) + Number(b);
                        }, 0);
                    // Update footer

                    $(api.column(8).footer()).html('<input type="hidden" name="total_pago" id="total_pago" value="' + pageTotal.toFixed(2) + '"/>' +
                        'S/. ' + separateComma(pageTotal.toFixed(2)).toLocaleString("en-US"));


                    $("#diferencia").val(pageTotal);
                    $("#saldo").val(0.00);
                    let uncliente = $("#pcliente_id").val();
                    //calcular
                    diferenciaFaltante();

                },
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

        })
    </script>
@endpush
