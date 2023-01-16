<button type="button" class="btn btn-option" data-toggle="modal" data-target="#modal-escanear"
        data-backdrop="static" style="margin-right:16px;" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-barcode" aria-hidden="true"></i> Escanear
</button>
@push('js')
    <!-- Modal -->
    <div class="modal fade" id="modal-escanear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content br-16 cnt-shw border-0">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fa fa-barcode mr-12"
                                                                                       aria-hidden="true"></i> <span
                            id="titulo-scan">Escanear {{$moduleTitle}}</span></h5>
                    <div id="option-modal-extra">
                        @if($withFecha)
                            Seleccione una fecha para el escaneo:
                            <input id="fecha_escaneo" type="date" value="{{now()->format('Y-m-d')}}"
                                   class="form-control">
                        @endif

                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">

                    <div class="row">
                        <div class="col-lg-6 border-right">
                            <h4 class="font-16">Por favor, escanee el documento para confirmarlo:</h4>

                            <img src="{{asset('imagenes/scan.gif')}}" width="80%"><br>

                            <input type="text" value="" id="codigo_confirmar" name="hiddenCodigo" style="opacity: 0">
                            <input type="text" value="12" id="codigo_accion" name="accion" style="opacity: 0">

                            <p id="respuesta_barra"></p>
                        </div>
                        <div class="col-lg-6 text-left pl-20">
                            <h4 class="font-16 font-weight-bold">{{$moduleTitle}} procesados:</h4>
                            <ul id="pedidos-procesados">

                            </ul>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="close-scan">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        /************
         * ESCANEAR PEDIDO
         */

        $('#modal-escanear').on('shown.bs.modal', function () {
            $('#codigo_confirmar').focus();
            $('#codigo_accion').val("fernandez");
            $('#titulo-scan').html("Escanear para confirmar - <span class='text-success'>{{$moduleTitle}}</span>");
            $('#pedidos-procesados').html('')
            $('#respuesta_barra').html('');
            $('#modal-escanear').on('click', function () {
                console.log("focus");
                $('#codigo_confirmar').focus();
                return false;
            });
        })
        $('#modal-escanear').on('hidden.bs.modal', function () {
            $('#respuesta_barra').html('')
            $('#pedidos-procesados').html('')
            $('#modal-escanear').unbind()
        })
        var codigos_agregados = [];
        $('#codigo_confirmar').change(function (event) {
            event.preventDefault();
            var codigo_caturado = ($(this).val() || '').trim();
            $('#codigo_confirmar').val('')
            var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-');
            if (!codigo_mejorado) {
                return
            }
            var data = {{\Illuminate\Support\Js::from($ajaxparams)}};
            data.hiddenCodigo = codigo_mejorado
            data.ducument_code = codigo_mejorado
            @if($withFecha)
                data.fecha_salida = $('#fecha_escaneo').val()
            @endif
            codigos_agregados.push(codigo_mejorado)
            codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)

            $('#pedidos-procesados').html(`<ul>${codigos_agregados.map(function (codigo) {
                return `<li><i class="fa fa-check text-success"></i>${codigo}</li>`
            }).join('')}</ul>`);
            $(this).val("");
            return false;
        });

        /***********
         * FIN ESCANEAR MOUSE
         */

        $("#close-scan").click(function (e) {
            e.preventDefault();
            console.log(codigos_agregados)
            if (codigos_agregados.length === 0) {
                return;
            }

            var data = {{\Illuminate\Support\Js::from($ajaxparams)}};
            data.codigos = codigos_agregados
            @if($withFecha)
                data.fecha_salida = $('#fecha_escaneo').val()
            @endif

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('operaciones.confirmaropbarras') }}",
                success: function (data) {
                    codigos_agregados=[]
                    $('#respuesta_barra').removeClass("text-danger");
                    $('#respuesta_barra').removeClass("text-success");
                    $('#respuesta_barra').addClass(data.class);
                    $('#respuesta_barra').html(data.html);
                    @foreach($tablesIds as $table)
                    $('{{$table}}').DataTable().draw(false)
                    @endforeach
                }
            });

            $(this).val("");
            return false;
        })
    </script>
@endpush
