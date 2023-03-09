<section id="tabs" class="project-tab">
        <div class="row">
            <div class="col-md-12">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="navCambioRuctab" data-toggle="tab" href="#navCambioRuc"
                           role="tab" aria-controls="navCambioRuc" aria-selected="true">Cambio RUC</a>
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
                    <div class="tab-pane fade show active" id="navCambioRuc" role="tabpanel" aria-labelledby="navCambioRuctab">
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
                        {{--navCambioClientetab--}}
                    </div>
                    <div class="tab-pane fade" id="navCambioEmresa" role="tabpanel" aria-labelledby="navCambioEmresatab">
                        {{--navCambioEmresatab--}}
                        <div class="tab-pane fade show active" id="navCambioRuc" role="tabpanel" aria-labelledby="navCambioRuctab">
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

            /*$("#btnCrearNuevaRelacion").click(function () {
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
            })*/
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
        })
    </script>
@endpush
