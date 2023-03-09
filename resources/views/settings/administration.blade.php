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
            <x-frm-unif-cambio-calculo-porc></x-frm-unif-cambio-calculo-porc>
        </div>
        <div class="col-md-12">
            @if(auth()->user()->rol==\App\Models\User::ROL_ADMIN)
                @foreach($jefe_operaciones_courier as $jefe_op)
                    <form class="form-group" id="form_direccion_JFO{{$jefe_op->id}}">
                        <div class="card">
                            <div class="card-header">
                                <h3>Agregar dirección {{$jefe_op->name}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">

                                    <label for="formGroupExampleInput">Distrito</label>
                                    <input type="text" class="form-control" id="distrito{{$jefe_op->id}}" name="distrito" value="{{$jefe_op->distrito}}" placeholder="Los Olivos">

                                    <label class="mt-2" for="formGroupExampleInput" >Ingresar direccion</label>
                                    <input type="text" value="{{$jefe_op->direccion_recojo}}" name="direccion" class="form-control" id="ingreso_adminD_{{$jefe_op->id}}" placeholder="Dirección" autocomplete="off">

                                    <label class="mt-2" for="formGroupExampleInput">Celular</label>
                                    <input type="text" value="{{$jefe_op->numero_recojo}}"   name="numero_jfo" class="form-control" id="ingreso_telefonoA{{$jefe_op->id}}" autocomplete="off" placeholder="Celular">

                                    <label class="mt-2" for="formGroupExampleInput">Destino</label>
                                    <input type="text" value="{{$jefe_op->destino}}" name="destino" class="form-control" id="destino{{$jefe_op->id}}" autocomplete="off" placeholder="Destino" disabled>

                                    <label class="mt-2" for="formGroupExampleInput">Referencia</label>
                                    <input type="text" value="{{$jefe_op->referencia}}" name="{{$jefe_op->referencia}}" class="form-control" id="referencia{{$jefe_op->id}}" autocomplete="off" placeholder="Referencia">

                                    <label class="mt-2" for="formGroupExampleInput">Cliente</label>
                                    <input type="text" value="{{$jefe_op->cliente}}" name="cliente" class="form-control" id="cliente{{$jefe_op->id}}" autocomplete="off" placeholder="Cliente">

                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Enviar
                            </button>
                        </div>
                    </form>
                @endforeach
            @endif
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

        $(document).ready(function () {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

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

          $('#modal-recojo-pedidos').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $('#Cliente').val(button.data('pedidoid'))
            $('#Id-Cliente').val(button.data('clienteid'))
            $('#cod_Cliente').val(button.data('clientenombre'))
            $('#cod_pedido').val(button.data('pedidocodigo'))
            console.log(button.data('direccionreco'))
            $('#direccion_recojo').val(button.data('direccionreco'))
            $('#nombre_recojo').val(button.data('nombreresiv'))
            $('#celular_recojo').val(button.data('telefonoresiv'))
            $('#referencia_recojo').val(button.data('referenciareco'))
            $('#observacion_recojo').val(button.data('observacionreco'))
            $('#gmlink_recojo').val(button.data('gmclink'))

            $('button:submit').prop("disabled",false)
            ocultar_div_modal_correccion_pedidos();
          })

          @foreach($jefe_operaciones_courier as $jefe_op)

          $(document).on("submit", "#form_direccion_JFO{{$jefe_op->id}}", function (event) {
            event.preventDefault();

            var form = $(this)[0];
            var formData = new FormData(form);
            let direccion_Joperaciones = $("#ingreso_adminD_{{$jefe_op->id}}").val();
            let numero_Joperaciones = $("#ingreso_telefonoA{{$jefe_op->id}}").val();
            let referencia = $("#referencia{{$jefe_op->id}}").val();
            let cliente = $("#cliente{{$jefe_op->id}}").val();
            let distrito = $("#distrito{{$jefe_op->id}}").val();

            $("#destino{{$jefe_op->id}}").prop('disableb', false)
            let destino = $("#destino{{$jefe_op->id}}").val();
            $("#destino{{$jefe_op->id}}").prop('disableb', false)

            //validaciones
            if (direccion_Joperaciones == "") {
              Swal.fire('Debe colocar una direccion de del jefe de operaciones', '', 'warning');
              return false;
            } else if (numero_Joperaciones == "") {
              Swal.fire('Debe colocar el numero del jefe de operaciones', '', 'warning');
              return false;
            }

            formData.append('direccion_jfo', direccion_Joperaciones);
            formData.append('user_id', "{{$jefe_op->id}}");
            formData.append('referencia_jfo', referencia);
            formData.append('destino_jfo', destino);
            formData.append('distrito', distrito);
            formData.append('cliente_jfo', cliente);

           /* // console.log(formData);
            return false;*/
            $.ajax({
              type: 'POST',
              url: "{{ route('agregardireccionjefeoperaciones.post') }}",
              data: formData,
              processData: false,
              contentType: false,
              success: function (value) {
                console.log(value);
                Swal.fire(
                  'Se envio correctamente los datos',
                  '',
                  'success'
                )
              }
            });
          });

          $('#ingreso_telefonoA{{$jefe_op->id}}').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
          });
          @endforeach
        });

    </script>
@endsection
