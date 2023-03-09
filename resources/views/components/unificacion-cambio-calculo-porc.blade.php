<section id="tabs" class="project-tab">
    <div class="container">
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
                    <div class="tab-pane fade show active" id="navCambioRuc" role="tabpanel"
                         aria-labelledby="navCambioRuctab">
                        <div class="card">
                            <div class="card-header">
                                <h3>Cambio de Ruc</h3>
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
                                            {!! Form::label('cbxRuc', 'RUC*') !!} &nbsp; &nbsp; &nbsp;
                                            <select name="cbxRuc"
                                                    class="border form-control border-secondary"
                                                    id="cbxRuc" data-live-search="true">
                                                <option value="">---- SELECCIONE RUC ----</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            {!! Form::label('pcantidad_tiempo', 'Empresa (Razon social)') !!}
                                            <input type="text" name="txtRazonSocial" id="txtRazonSocial"
                                                   class="form-control number" placeholder="Empresa (Razon social)...">
                                        </div>

                                        <button type="button" id="btnCambiarNombre" class="btn btn-success btn-lg">
                                            Cambiar Nombre
                                        </button>
                                        <button type="button" id="btnCrearNuevaRelacion" class="btn btn-info btn-lg">
                                            Crear nueva relacion
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navCambioAsesor" role="tabpanel"
                         aria-labelledby="navCambioAsesortab">
                        <table class="table" cellspacing="0">
                            <thead>
                            <tr>
                                <th>TABLA 1</th>
                                <th>Employer</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="#">Work 1</a></td>
                                <td>Doe</td>
                                <td>john@example.com</td>
                            </tr>
                            <tr>
                                <td><a href="#">Work 2</a></td>
                                <td>Moe</td>
                                <td>mary@example.com</td>
                            </tr>
                            <tr>
                                <td><a href="#">Work 3</a></td>
                                <td>Dooley</td>
                                <td>july@example.com</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="navCambioCliente" role="tabpanel"
                         aria-labelledby="navCambioClientetab">
                        <table class="table" cellspacing="0">
                            <thead>
                            <tr>
                                <th>TABLA 3</th>
                                <th>Date</th>
                                <th>Award Position</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="#">Work 1</a></td>
                                <td>Doe</td>
                                <td>john@example.com</td>
                            </tr>
                            <tr>
                                <td><a href="#">Work 2</a></td>
                                <td>Moe</td>
                                <td>mary@example.com</td>
                            </tr>
                            <tr>
                                <td><a href="#">Work 3</a></td>
                                <td>Dooley</td>
                                <td>july@example.com</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="navCambioEmresa" role="tabpanel"
                         aria-labelledby="navCambioEmresatab">
                        <table class="table" cellspacing="0">
                            <thead>
                            <tr>
                                <th>TABLA 3</th>
                                <th>Date</th>
                                <th>Award Position</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="#">Work 1</a></td>
                                <td>Doe</td>
                                <td>john@example.com</td>
                            </tr>
                            <tr>
                                <td><a href="#">Work 2</a></td>
                                <td>Moe</td>
                                <td>mary@example.com</td>
                            </tr>
                            <tr>
                                <td><a href="#">Work 3</a></td>
                                <td>Dooley</td>
                                <td>july@example.com</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="navBloqueoRuc" role="tabpanel" aria-labelledby="navBloqueoRuctab">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form_main">
                                    <h4 class="heading"><strong>Quick </strong> Contact <span></span></h4>
                                    <div class="form">
                                        <form action="contact_send_mail.php" method="post" id="contactFrm"
                                              name="contactFrm">
                                            <input type="text" required="" placeholder="Please input your Name" value=""
                                                   name="name" class="txt">
                                            <input type="text" required="" placeholder="Please input your mobile No"
                                                   value="" name="mob" class="txt">
                                            <input type="text" required="" placeholder="Please input your Email"
                                                   value="" name="email" class="txt">

                                            <textarea placeholder="Your Message" name="mess" type="text"
                                                      class="txt_3"></textarea>
                                            <input type="submit" value="submit" name="submit" class="txt2">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        $('#cbxRuc').html(data.html);
                        $("#cbxRuc").selectpicker("refresh");
                    }
                });
            }


            $("#cbxRuc").on("change", function () {
                var data_raz_soc = $("option[value=" + $(this).val() + "]", this).attr('data-raz-soc');
                $('#txtRazonSocial').val(data_raz_soc);
            });

            $("#btnCambiarNombre").click(function () {
                $("#btnCambiarNombre").attr('disabled', 'disabled')
                var data = {}
                data.cliente_id = $("#cbxRuc").val()
                data.cliente_nombre = $("#txtRazonSocial").val()
                if (data.cliente_nombre =='' ){
                    Swal.fire(
                        'Error',
                        'Ingrese la razon social',
                        'error'
                    )
                    $("#btnCambiarNombre").removeAttr('disabled')
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
                        $("#btnCambiarNombre").removeAttr('disabled')
                    })
            })
        })
    </script>
@endpush
