<div class="card">
    <div class="card-header">
        <h3>Activar al cliente por tiempo</h3>
    </div>
    <div class="card-body">
        <div class="card-body border border-secondary rounded">

            <div class="form-row">

                <div class="form-group col-lg-6">
                    {!! Form::label('user_id_tiempo', 'Asesor*') !!} &nbsp; &nbsp; &nbsp;

                    <select name="user_id_tiempo" class="border form-control border-secondary"
                            id="user_id_tiempo" data-live-search="true">
                        <option value="">---- SELECCIONE ASESOR ----</option>
                    </select>

                </div>

                <div class="form-group col-lg-6">
                    {!! Form::label('cliente_id_tiempo', 'Cliente*') !!} &nbsp; &nbsp; &nbsp;

                    <select name="cliente_id_tiempo" class="border form-control border-secondary"
                            id="cliente_id_tiempo" data-live-search="true">
                        <option value="">---- SELECCIONE CLIENTE ----</option>
                    </select>

                </div>

                <div class="form-group col-lg-6">
                    {!! Form::label('pcantidad_pedido', 'Cantidad por pedidos (unidad)') !!}
                    <input type="text" name="pcantidad_pedido" id="pcantidad_pedido" step="0.01" min="0"
                           class="form-control number" placeholder="Cantidad por pedidos...">

                </div>

                <div class="form-group col-lg-6">
                    {!! Form::label('pcantidad_tiempo', 'Tiempo (min)') !!}
                    <input type="text" name="pcantidad_tiempo" id="pcantidad_tiempo" step="0.01" min="0"
                           class="form-control number" placeholder="Cantidad por tiempo...">

                </div>

                <button type="button" id="activar_tiempo" class="btn btn-info btn-sm">Establecer</button>

            </div>

        </div>
    </div>
</div>
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
              url: "{{ route('asesorcombo') }}",
              method: 'POST',
              success: function (data) {
                $('#user_id_tiempo').html(data.html);
                $("#user_id_tiempo").selectpicker("refresh").trigger("change");
              }
            });
            $(document).on("change", "#user_id_tiempo", function () {
                let userid = $(this).val();
                $.ajax({
                    url: "{{ route('cargar.clientedeudaparaactivar') }}?user_id=" + userid,
                    method: 'GET',
                    success: function (data) {
                        $('#cliente_id_tiempo').html(data.html);
                        $("#cliente_id_tiempo").selectpicker("refresh");
                    }
                });
            });

            $("#activar_tiempo").click(function () {
                $("#activar_tiempo").attr('disabled', 'disabled')
                var data = {}
                data.user_id = $("#user_id_tiempo").val()
                data.cliente_celular = $("#cliente_id_tiempo").val()
                data.cantidad_pedido = $("#pcantidad_pedido").val()
                data.cantidad_tiempo = $("#pcantidad_tiempo").val()
                $.post('{{route('settings.store-time-clientes')}}', data)
                    .done(function (data) {
                        console.log(data)
                        if (data.success) {
                            Swal.fire(
                                '',
                                'Activacion temporal realizada',
                                'success'
                            )
                            $("#user_id_tiempo").val('')
                            $("#cliente_id_tiempo").val('')
                            $("#pcantidad_pedido").val('')
                            $("#pcantidad_tiempo").val('')
                            $("#user_id_tiempo").selectpicker("refresh").trigger("change");
                        } else {
                            Swal.fire(
                                '',
                                'Los datos no fueron guardados',
                                'warning'
                            )
                        }
                    })
                    .fail(function (data) {
                        console.log(data)
                        if (data.responseJSON.errors) {
                            Swal.fire(
                                '',
                                Object.keys(data.responseJSON.errors).map(function (key) {
                                    return `<b>${data.responseJSON.errors[key][0]}</b>`
                                }).join('<hr class="my-1"><br>'),
                                'error'
                            )
                        } else {
                            Swal.fire(
                                '',
                                'Ocurrio un error al intentar guardar la información',
                                'error'
                            )
                        }
                    })
                    .always(function () {
                        $("#activar_tiempo").removeAttr('disabled')
                    })
            })


        })
    </script>
@endpush
