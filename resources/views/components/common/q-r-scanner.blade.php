<button type="button" class="btn btn-option" data-toggle="modal" data-target="#modal-escanear"
        data-backdrop="static" style="margin-right:16px;" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-barcode" aria-hidden="true"></i> Escanear
</button>
<style>
    .switch_box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    /* Switch 1 Specific Styles Start */

    input[type="checkbox"].switch_1 {
        font-size: 16px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 3.5em;
        height: 1.5em;
        background: #ddd;
        border-radius: 3em;
        position: relative;
        cursor: pointer;
        outline: none;
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
    }

    input[type="checkbox"].switch_1:checked {
        background: #0ebeff;
    }

    input[type="checkbox"].switch_1:after {
        position: absolute;
        content: "";
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        background: #fff;
        -webkit-box-shadow: 0 0 .25em rgba(0, 0, 0, .3);
        box-shadow: 0 0 .25em rgba(0, 0, 0, .3);
        -webkit-transform: scale(.7);
        transform: scale(.7);
        left: 0;
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
    }

    input[type="checkbox"].switch_1:checked:after {
        left: calc(100% - 1.5em);
    }

    img:hover {
        transform: initial !important;
    }

    /* Switch 1 Specific Style End */
</style>
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
                            Seleccione una fecha de ruta:
                            <input id="fecha_escaneo" type="date"
                                   class="form-control">
                        @endif

                    </div>

                    <div class="switch_box box_1">
                        <input type="checkbox" class="switch_1" id="modo_fast" @if($reparto == 1) checked @endif>
                        <i class="fa fa-bolt ml-8 text-gray" aria-hidden="true"></i>
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

                            <input type="text" value="" id="codigo_confirmar" placeholder="00-0000-0"
                                   name="hiddenCodigo" style="    opacity: 0.5;
    border: 1px solid #bbbbbb;
    border-radius: 4px;
    padding: 8px;
    font-size: 20px;">
                            <input type="text" value="12" id="codigo_accion" name="accion" style="opacity: 0">

                            <p id="respuesta_barra"></p>
                        </div>
                        <div class="col-lg-6 text-left pl-20">
                            <h4 class="font-16 font-weight-bold">{{$moduleTitle}} procesados:</h4>
                            <div id="pedidos-procesados" class="text-center mt-36"></div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-lg-6">

                        </div>
                        <div class="col-lg-6 text-right">
                            <button class="btn btn-success" id="close-scan">Aceptar</button>
                            <button class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
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


            if ($('#modo_fast').is(':checked')) {
                console.log("modo speed");
                $('#close-scan').fadeOut();
            }

            $('#modo_fast').on('change', function () {
                if ($(this).is(':checked')) {
                    console.log("desaparece aceptar");
                    $('#close-scan').fadeOut();
                } else {
                    console.log("aparece aceptar");
                    $('#close-scan').fadeIn();
                }
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

            var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-').replaceAll("'", '-').replaceAll("(", '*');
            var codigo_accion = $('#codigo_accion').val();
            var codigo_responsable = $('#codigo_responsable').val();
            $('#codigo_confirmar').val('')

            var data = {{\Illuminate\Support\Js::from($ajaxparams)}};
            data.codigo = codigo_mejorado
            @if($withFecha)
                data.fecha_salida = $('#fecha_escaneo').val()
            @endif

            if ($('#modo_fast').is(':checked')) {


                console.log("Modo fast activado");

                @if($withFecha)
                var fecha_salida_validacion = $('#fecha_escaneo').val();
                if (fecha_salida_validacion == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Debe ingresar una fecha',
                        showConfirmButton: false,
                        timer: 1000
                    })

                    return false;
                } else {
                    data.fecha_salida = $('#fecha_escaneo').val()
                }
                @endif

                codigos_agregados.push(data.codigo);
                codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)
                data.codigos = codigos_agregados

                ConfirmarOPBarra(data)
                return false;
            }

            /*********
             * CONFIRMAMOS CODIGO
             * @type {string}
             */

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('operaciones.validaropbarras') }}",
                success: function (data) {
                    /*****************
                     * VERIFICACIONES DE RESPUESTAS
                     */
                    if (data.error == 1) {

                        Swal.fire({
                            icon: 'error',
                            title: 'El Pedido ya se procesó anteriormente',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</b></span>');

                    } else if (data.error == 4) {

                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido no se encontró en el sistema',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                    } else if (data.error == 5) {

                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido No tiene direcion',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                    } else if (data.error == 6) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Pedido Pendiente de anulación',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                    } else if (data.error == 0) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido identificado',
                            color: '#FFF',
                            background: '#79b358',
                            showConfirmButton: false,
                            timer: 600
                        })

                        codigos_agregados.push(data.codigo);
                        codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)

                        $('#pedidos-procesados').html(`<p><b class="text-success w-100">codigos Escaneados (${codigos_agregados.length}):</b></p><ul>${codigos_agregados.map(function (codigo) {
                            return `<li><i class="fa fa-check text-success"></i> ${codigo}</li>`
                        }).join('')}</ul><br>`);


                    } else if (data.error == 3) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido identificado',
                            color: '#FFF',
                            background: '#79b358',
                            showConfirmButton: false,
                            timer: 600
                        })
                        console.log(data);
                        codigos_agregados.push(data.codigo);
                        codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)

                        $('#pedidos-procesados').append('<table class="table ' + data.clase_confirmada + ' mb-0"><tr><td class="pb-8 pt-8">' + data.codigo + '</td><td class="pb-8 pt-8">' + data.zona + '</td><td class="pb-8 pt-8">' + data.cantidad_recibida + '/' + data.cantidad + '</td></tr></table>');
                    }


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
            }).always(function () {
                //$('#codigo_confirmar').focus();
            });

        });

        $("#close-scan").click(function (e) {
            e.preventDefault();

            var data = {{\Illuminate\Support\Js::from($ajaxparams)}};
            data.codigos = codigos_agregados

            var fecha_salida_validacion = $('#fecha_escaneo').val();

            @if($withFecha)
            if (fecha_salida_validacion == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Debe ingresar una fecha',
                    showConfirmButton: false,
                    timer: 1000
                })

                return false;
            } else {
                data.fecha_salida = $('#fecha_escaneo').val()
            }
            @endif

            //MAndamos el Ajax

            ConfirmarOPBarra(data)

            $(this).val("");
            return false;
        });

        /***********
         * FIN ESCANEAR MOUSE
         */
        function ConfirmarOPBarra(data) {

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('operaciones.confirmaropbarras') }}",
                success: function (data) {

                    console.log(data.error);
                    codigos_agregados = []

                    var codigos_procesados = data.codigos_procesados
                    var codigos_no_procesados = data.codigos_no_procesados

                    /*****************
                     * VERIFICACIONES DE RESPUESTAS
                     */
                    if (data.error == 1) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'El Pedido ya se procesó anteriormente',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</b></span>');

                    } else if (data.error == 3) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido identificado',
                            color: '#FFF',
                            background: '#79b358',
                            showConfirmButton: false,
                            width: '1000px',
                            height: '800px',
                            timer: 1300
                        })
                        console.log(data);
                        codigos_agregados.push(data.codigo);
                        codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)

                        $('#pedidos-procesados').html('<table class="table ' + data.clase_confirmada + ' mb-0"><tr><td class="pb-8 pt-8">' + data.codigo + '</td><td class="pb-8 pt-8">' + data.zona + '</td><td class="pb-8 pt-8">' + data.cantidad_recibida + '/' + data.cantidad + '</td></tr></table>');
                    } else if (data.error == 4) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido no se encontró en el sistema',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                    } else if (data.error == 5) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'El pedido no tiene dirección',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                    } else if (data.error == 6) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'Pedido Pendiente de anulación',
                            color: '#FFF',
                            background: '#9f2916',
                            showConfirmButton: false,
                            timer: 500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                        $('#tablaPrincipal').DataTable().ajax.reload();

                    } else if (data.error == 0) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido Procesado',
                            color: '#FFF',
                            background: '#79b358',
                            showConfirmButton: false,
                            timer: 1000
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');

                    } else if (data.error == 7) {
                        $('#respuesta_barra').html("");
                        Swal.fire({
                            icon: 'error',
                            title: 'El Paquete de pedidos ya fue enviado',
                            color: '#FFF',
                            background: '#ffc107',
                            showConfirmButton: false,
                            timer: 500
                        })

                        $('#respuesta_barra').html('<span class="' + data.class + '">' + data.html + '</span>');
                    }
                    /*
                                        setTimeout(function (){
                                            $('#respuesta_barra').fadeOut();
                                        },2200);

                     */

                    /*
                    $('#pedidos-procesados').html(`<p><b class="text-success w-100">codigos procesados (${codigos_procesados.length}):</b></p><ul>${codigos_procesados.map(function (codigo) {
                        return `<li><i class="fa fa-check text-success"></i> ${codigo}</li>`
                    }).join('')}</ul><br>`);*/
                    /*
                                        if(data.error == 0){
                                            $('#pedidos-procesados').html(`<h2 class="font-weight-bold"><i class="fa fa-check text-success" aria-hidden="true"></i> ${codigos_procesados.length} </h2><h4>Pedidos Procesados</h4><p>Siga Escaneando pedidos</p>`);

                                            $('#respuesta_barra').removeClass("text-danger");
                                            $('#respuesta_barra').removeClass("text-success");
                                            $('#respuesta_barra').addClass(data.class);
                                            $('#respuesta_barra').html(data.html);


                                            setTimeout(function(){
                                                console.log("cerrar modal");
                                                //$('#pedidos-procesados').html("");
                                                //$('#modal-escanear').modal('hide');
                                            },300);


@foreach($tablesIds as $table)
                    $('{{$table}}').DataTable().draw(false)
                        @endforeach
                    if (codigos_agregados.length === 0) {
                        //$('#modal-escanear').modal('hide')
                    }

                }else if(data.error == 3){

                    Swal.fire({
                        icon: 'success',
                        title: 'Pedido identificado',
                        color: '#FFF',
                        background: '#79b358',
                        showConfirmButton: false,
                        timer: 600
                    })
                    console.log(data);
                    codigos_agregados.push(data.codigo);
                    codigos_agregados = codigos_agregados.filter((v, i, a) => a.indexOf(v) === i)

                    $('#pedidos-procesados').append('<table class="table '+ data.clase_confirmada +' mb-0"><tr><td class="pb-8 pt-8">'+ data.codigo +'</td><td class="pb-8 pt-8">'+ data.zona +'</td><td class="pb-8 pt-8">'+ data.cantidad_recibida +'/'+ data.cantidad + '</td></tr></table>');
                }*/

                    /*
                                        $('#pedidos-procesados').append(`<p><b class="text-danger w-100">codigos no procesados (${codigos_no_procesados.length}): </b></p><ul>${codigos_no_procesados.map(function (codigo) {
                                            return `<li><i class="fa fa-window-close text-danger"></i> ${codigo}</li>`
                                        }).join('')}</ul><br>`);

                     */


                }
            }).always(function () {
                $('#codigo_confirmar').focus();
            });


            return false;
        }

    </script>
@endpush
