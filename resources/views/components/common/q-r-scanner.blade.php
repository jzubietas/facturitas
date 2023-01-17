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

                            <input type="text" value="" id="codigo_confirmar" placeholder="00-0000-0" name="hiddenCodigo" style="    opacity: 0.5;
    border: 1px solid #bbbbbb;
    border-radius: 4px;
    padding: 8px;
    font-size: 20px;">
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
                    <button class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        /************
         * ESCANEAR PEDIDO
         */
        var codigos_agregados = [];
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
            codigos_agregados = [];
        })


        codigos_agregados = []

        $('#codigo_confirmar').change(function (event) {
            event.preventDefault();

            var codigo_caturado = ($(this).val() || '').trim();
            var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-');
            var codigo_accion = $('#codigo_accion').val();
            var codigo_responsable = $('#codigo_responsable').val();
            $('#codigo_confirmar').val('')

            var data = {{\Illuminate\Support\Js::from($ajaxparams)}};
            data.codigo = codigo_mejorado
            @if($withFecha)
                data.fecha_salida = $('#fecha_escaneo').val()
            @endif

            /*********
             * CONFIRMAMOS CODIGO
             * @type {string}
             */

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('operaciones.validaropbarras') }}",
                success: function (data) {

                    if(data.error == 1){
                        $('#respuesta_barra').html('<span class="'+ data.class +'">El Pedido ya se procesó anteriormente.</span>');
                    }else if(data.error == 0){

                        codigos_agregados.push(data.codigo);
                        codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)
                    }

                    $('#pedidos-procesados').html(`<p><b class="text-success w-100">codigos Escaneados (${codigos_agregados.length}):</b></p><ul>${codigos_agregados.map(function (codigo) {
                        return `<li><i class="fa fa-check text-success"></i> ${codigo}</li>`
                    }).join('')}</ul><br>`);

                    /*
                                        $('#pedidos-procesados').append(`<p><b class="text-danger w-100">codigos no procesados (${codigos_no_procesados.length}): </b></p><ul>${codigos_no_procesados.map(function (codigo) {
                                            return `<li><i class="fa fa-window-close text-danger"></i> ${codigo}</li>`
                                        }).join('')}</ul><br>`);


                                        $('#respuesta_barra').removeClass("text-danger");
                                        $('#respuesta_barra').removeClass("text-success");
                                        $('#respuesta_barra').addClass(data.class);
                                        $('#respuesta_barra').html(data.html);


                    @foreach($tablesIds as $table)
                    $('{{$table}}').DataTable().draw(false)
                    @endforeach
                    */
                }
            }).always(function(){
                $('#codigo_confirmar').focus();
            });




            /*
            var data = {{\Illuminate\Support\Js::from($ajaxparams)}};
            data.hiddenCodigo = codigo_mejorado
            data.ducument_code = codigo_mejorado
            @if($withFecha)
                data.fecha_salida = $('#fecha_escaneo').val()
            @endif


            $('#pedidos-procesados').html(`<p><b class="text-success w-100">Codigos Escaneados (${codigos_agregados.length}):</b></p> <ul>${codigos_agregados.map(function (codigo) {
                return `<li><i class="fa fa-check text-success"></i>${codigo}</li>`
            }).join('')}</ul>`);
            $(this).val("");
             */
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
                    codigos_agregados = []

                    var codigos_procesados = data.codigos_procesados
                    var codigos_no_procesados = data.codigos_no_procesados

                    $('#pedidos-procesados').html(`<p><b class="text-success w-100">codigos procesados (${codigos_procesados.length}):</b></p><ul>${codigos_procesados.map(function (codigo) {
                        return `<li><i class="fa fa-check text-success"></i> ${codigo}</li>`
                    }).join('')}</ul><br>`);
/*
                    $('#pedidos-procesados').append(`<p><b class="text-danger w-100">codigos no procesados (${codigos_no_procesados.length}): </b></p><ul>${codigos_no_procesados.map(function (codigo) {
                        return `<li><i class="fa fa-window-close text-danger"></i> ${codigo}</li>`
                    }).join('')}</ul><br>`);

 */

                    $('#respuesta_barra').removeClass("text-danger");
                    $('#respuesta_barra').removeClass("text-success");
                    $('#respuesta_barra').addClass(data.class);
                    $('#respuesta_barra').html(data.html);
                    @foreach($tablesIds as $table)
                    $('{{$table}}').DataTable().draw(false)
                    @endforeach
                    if (codigos_agregados.length === 0) {
                        //$('#modal-escanear').modal('hide')
                    }
                }
            }).always(function(){
                $('#codigo_confirmar').focus();
            });

            $(this).val("");
            return false;
        })
    </script>
@endpush
