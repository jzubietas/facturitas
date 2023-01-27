@extends('adminlte::page')
{{-- @extends('layouts.admin') --}}

@section('title', 'Dashboard')

@section('content_header')
    <h3>Configuración del administrador</h3>
@endsection

@section('content')
    <div class="row m-4">
        @if(auth()->user()->rol==\App\Models\User::ROL_ADMIN)
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Definir contraseña para anular pedidos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input id="pedido_password" type="password" class="form-control"
                                           placeholder="Generar contraseña">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" id="pedido_change_password">
                                        Guardar contraseña
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-8">
            <x-common-activar-cliente-por-tiempo></x-common-activar-cliente-por-tiempo>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Botones de accion
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <a href="{{ route("courierregistro") }}" class="btn btn-warning" type="button" id="courierregistros">
                            Bandeja de Registros de Courier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Adjuntar imagenes para mostrar en ver deuda de cliente
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="attachment_file_one">Imagen para loas asesores del <b>1 al 5</b></label>
                        <input type="file" class="form-control" id="attachment_file_one" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="attachment_file_two">Imagen para loas asesores del <b>6 al 12</b></label>
                        <input type="file" class="form-control" id="attachment_file_two" accept="image/*">
                    </div>

                    <div class="progress" id="attachment_progress" style="display: none">
                        <div id="progress_bar" class="progress-bar" role="progressbar" style="width: 0%;"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-outline-info" type="button" id="buttom_attachment_save">
                        Guardar fotos
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $oldDisk = setting('administracion.attachments.1_5.disk');
       $oldPath = setting('administracion.attachments.1_5.path');

       $oldDisk2 = setting('administracion.attachments.6_12.disk');
       $oldPath2 = setting('administracion.attachments.6_12.path');
                        @endphp

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Imagen para asesores de 1 al 5</h3>
                                </div>
                                <div class="card-body" id="imagecontent1">
                                    @if($oldDisk && $oldPath)
                                        <img src="{{Storage::disk($oldDisk)->url($oldPath)}}" class="w-100"/>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Imagen para asesores de 6 al 12</h3>
                                </div>
                                <div class="card-body" id="imagecontent2">
                                    @if($oldDisk2 && $oldDisk2)
                                        <img src="{{Storage::disk($oldDisk2)->url($oldPath2)}}" class="w-100"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Titulares</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>TITULAR</th>
                                <th>ACCIÓN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>b</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>TITULAR</th>
                                <th>ACCIÓN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        {{--
        $(document).on("submit", "#formulariotiempo", function (evento) {
            evento.preventDefault();

            var formData = $("#formulariotiempo").serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('pedidostiempo') }}",
                data: formData,
            }).done(function (data) {
                Swal.fire(
                    'Activacion temporal realizada',
                    '',
                    'success'
                )
                $("#modal-activartiempo").modal("hide");
                $("#user_id").trigger("change");
            });
        });
        --}}

        $("#pedido_change_password").click(function () {
            var password = $("#pedido_password").val();
            if (!password) {
                Swal.fire(
                    'El campo de contraseña no debe estar vacio',
                    '',
                    'warning'
                )
            }
            $.post('{{route('settings.store-setting')}}', {
                key: 'pedido_password',
                value: password
            }).done(function (a, b, c) {
                if (c.status === 200) {
                    Swal.fire(
                        'Contraseña cambiada',
                        '',
                        'success'
                    )
                }
            }).always(function () {
                $("#pedido_password").val("");
            })
        })
    </script>
    <script>
        function uploadProgressHandler(event) {
            // $("#loaded_n_total").html("Uploaded " + event.loaded + " bytes of " + event.total);
            var percent = (event.loaded / event.total) * 100;
            var progress = Math.round(percent);
            $("#progress_bar").attr('aria-valuenow', progress);
            $("#progress_bar").text(progress);
            $("#progress_bar").css("width", progress + "%");
        }

        function loadHandler(event) {
            console.log(event)
            //$("#status").html(event.target.responseText);
            var progress = 0;
            $("#progress_bar").attr('aria-valuenow', progress);
            $("#progress_bar").text(progress);
            $("#progress_bar").css("width", progress + "%");
        }

        function errorHandler(event) {
            console.log(event)
            //$("#status").html("Upload Failed");
        }

        function abortHandler(event) {
            console.log(event)
            //$("#status").html("Upload Aborted");
        }

        $("#buttom_attachment_save").click(function (event) {
            event.preventDefault();
            var file = $("#attachment_file_one")[0].files[0];
            var file2 = $("#attachment_file_two")[0].files[0];
            if (!file && !file2) {
                console.log(!file, !file2)
                Swal.fire(
                    'Debes adjuntar almenos un archivo en uno de los campos',
                    '',
                    'warning'
                )
                return;
            }
            var formData = new FormData();
            if (file) {
                formData.append("attachment_one", file);
            }
            if (file2) {
                formData.append("attachment_two", file2);
            }

            $("#attachment_progress").show()
            $.ajax({
                url: '{{route('settings.store-admin-settings')}}',
                method: 'POST',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress",
                        uploadProgressHandler,
                        false
                    );
                    xhr.addEventListener("load", loadHandler, false);
                    xhr.addEventListener("error", errorHandler, false);
                    xhr.addEventListener("abort", abortHandler, false);

                    return xhr;
                }
            }).done(function (data) {
                if (data.attachment_one) {
                    $("#imagecontent1").html('<img src="' + data.attachment_one + '" class="w-100"/>')
                }
                if (data.attachment_two) {
                    $("#imagecontent2").html('<img src="' + data.attachment_two + '" class="w-100"/>')
                }
            }).always(function () {
                $("#attachment_progress").hide()
                $("#attachment_file_one").val(null)
                $("#attachment_file_two").val(null)
            });
        });
    </script>
@endsection
