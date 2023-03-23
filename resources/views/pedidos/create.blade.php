@extends('adminlte::page')
@section('title', 'Agregar pedidos')
@section('content_header')

    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar pedidos</h1>

        <button type="button" class="btn btn-warning btn-lg"
                data-toggle="jqconfirm"
                data-type="history"
                data-target="{{route('pedidos.histories.index')}}">
            <i class="fa fa-history"></i>
            Cargar Historial
        </button>
    </div>
    {{-- @error('num_ruc')
<small class="text-danger" style="font-size: 16px">{{ $message }}</small>
@enderror --}}
@stop

@section('content')
    {{--  {!! Form::open(['route' => 'pedidos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
    <form id="formulario" name="formulario" enctype="multipart/form-data"> --}}
    {{Form::open(['files'=>true,'name'=>'formulario','id'=>'formulario'])}}
    @include('pedidos.partials.form')
    <div class="card-footer" id="guardar">
        <button type="submit" class="btn btn-success" id="btnImprimir" target="_blank">
            <i class="fas fa-save"></i>
            Guardar
        </button>
        <button type="button" onClick="history.back()" class="btn btn-danger btn-lg">
            <i class="fas fa-arrow-left"></i>
            ATRAS
        </button>
    </div>
    {!! Form::close() !!}
    @include('pedidos.modal.AddRuc')
    @include('pedidos.modal.copiarinfo')
    @include('pedidos.modal.historial')
    @include('pedidos.modal.historial2')
    @include('pedidos.modal.modal_direccion_createpedido')

@endsection

@push('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> --}}

    <style>
        select option:disabled {
            color: #000;
            font-weight: bold;
        }

        .highlight {
            color: red !important;
            background: white !important;
        }
    </style>
    <script>
        window.copyElement = function (el) {
            $(el).select();
            window.document.execCommand("copy");
        }
    </script>
@endpush

@section('js')
    {{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {

            $(document).on('paste', '.bs-searchbox input.form-control', function (e) {
                /*console.log("sadsaad")
                var variable = e.target.id;
                console.log("variable", variable)
                var pastedData = e.originalEvent.clipboardData.getData('text');
                pastedData=pastedData.replace(/ /g, "");
                $(this).val(pastedData)
                console.log("sadsaad")*/
            })

            $(document).on('click', '[data-toggle=jqconfirm][data-type=previsualizar]', function () {
                let pruc = $('#pruc').val();
                let pempresa = $('#pempresa').val();
                let pmes = $('#pmes').val();
                let panio = $('#panio').val();
                let pcantidad = $('#pcantidad').val();
                let ptipo_banca = $('#ptipo_banca').val().split('_')[0];
                let pdescripcion = $('#pdescripcion').val();
                let pnota = $('#pnota').val();
                let pcourier = $('#pcourier').val();
                let user_id = $('#user_id').val();
                let cliente_id = $('#cliente_id').val();

                var insertData = `
PEDIDO
__________________________________
*CANTIDAD* ${pcantidad}
*RUC* ${pruc}
*RAZON SOCIAL* ${pempresa}
*MES* ${pmes}
*AÑO* ${panio}
*TIPO* ${ptipo_banca}
*DESCRIPCIÓN*
    ${pdescripcion}
*NOTA*
    ${pnota}
__________________________________
`;
                const tpl = `<div class="">
                <button type="button" onclick="copyElement('#pedido_visualizar_content')" class="btn btn-outline-dark">
                    <i class="fa fa-copy"></i> Copiar
                </button>
                <textarea class="form-control w-100" cols="20" rows="15" id="pedido_visualizar_content">${insertData}</textarea>
            </div>`;
                const target = $(this).data('target')
                if (user_id && cliente_id && pruc && pempresa && pmes && panio && pcantidad && ptipo_banca && pdescripcion && pnota && pcourier) {
                    $.confirm({
                        theme: 'material',
                        type: 'dark',
                        icon: 'fa fa-copy',
                        backgroundDismiss: true,
                        title: 'Previsualizar Pedido',
                        columnClass: 'large',
                        buttons: {
                            cerrar: {
                                btnClass: 'btn-secondary'
                            }
                        },
                        content: function () {
                            const self = this
                            return $.post(target, {
                                identificador: user_id,
                                cliente_id: cliente_id,
                                ruc: pruc,
                                empresa: pempresa,
                                year: panio,
                                mes: pmes,
                                cantidad: pcantidad,
                                tipo_banca: ptipo_banca,
                                descripcion: pdescripcion,
                                nota: pnota,
                                courier_price: pcourier,
                            })
                                .done(function () {
                                    self.setContent(tpl)
                                })
                                .fail(function () {
                                    self.setContent(tpl + `<div class="mt-4 alert alert-danger">No se guardo en historial por un error</div>`)
                                })
                        }
                    })
                } else {
                    $.confirm({
                        theme: 'material',
                        type: 'dark',
                        icon: 'fa fa-copy',
                        backgroundDismiss: true,
                        title: 'Advertencia',
                        columnClass: 'large',
                        content: `Rellene todos los campos para mostrar la información`,
                        buttons: {
                            cerrar: {
                                btnClass: 'btn-secondary'
                            }
                        },
                    })
                }
            })
            $(document).on('click', '[data-toggle=jqconfirm][data-type=history]', function () {
                const target = $(this).data('target')
                $.confirm({
                    theme: 'material',
                    type: 'dark',
                    icon: 'fa fa-save',
                    backgroundDismiss: true,
                    title: 'Pedido Guardados',
                    columnClass: 'xlarge',
                    buttons: {
                        cerrar: {
                            btnClass: 'btn-secondary'
                        }
                    },
                    loadContent: function () {
                        const self = this
                        return $.get(target)
                            .done(function (data) {
                                self.$ajaxdata = data;
                                if (data.length > 0) {
                                    self.setContent(`<div class="">
<table class="table table-striped table-bordered table-sm" cellspacing="0"
  width="100%" id="tblListadoHistorial">
<thead>
<tr>
<th style="vertical-align: middle">CLIENTE</th>
<th style="vertical-align: middle">RUC - EMPRESA</th>
<th style="vertical-align: middle">MES/AÑO</th>
<th style="vertical-align: middle">CANTIDAD</th>
<th style="vertical-align: middle">TIPO BANCA</th>
<th style="vertical-align: middle">COURIER</th>
<th style="vertical-align: middle">DESCRIPCIÓN</th>
<th style="vertical-align: middle">NOTA</th>
<th style="vertical-align: middle">ACCIONES</th>
</tr>
</thead>
<tbody>
${data.map(function (data, index) {
                                        return `<tr>
<td>${data.celular}</td>
<td>${data.ruc} - ${data.empresa}</td>
<td>${data.mes}/${data.year}</td>
<td>${data.cantidad}</td>
<td>${data.tipo_banca}</td>
<td>${data.courier_price}</td>
<td>${data.descripcion}</td>
<td>${data.nota}</td>
<td>
<button data-add class="btn btn-dark btn-sm btn-block" data-index="${index}"><i class="fa fa-arrow-down"></i>Agregar</button>
<button data-delete class="btn btn-danger btn-sm btn-block" data-id="${data.id}" data-index="${index}"><i class="fa fa-window-close"></i></button>
</td>
</tr>`
                                    }).join('')}

</tbody>
</table>
            </div>`)
                                } else {
                                    self.setContent(`<div class="mt-4 alert alert-danger">No tienes pedidos guardados</div>`)
                                }
                            })
                            .fail(function () {
                                self.setContent(`<div class="mt-4 alert alert-danger">No hay items</div>`)
                            })
                    },
                    content: function () {
                        return this.loadContent()
                    },
                    onContentReady: function () {
                        const self = this
                        self.$content.find('[data-add]').click(function () {
                            const index = $(this).data('index')
                            const data = self.getItemByIndex(index)
                            $.confirm({
                                title: 'Advertencia',
                                icon: 'fa fa-exclamation-triangle text-warning',
                                content: 'Se remplazara todos los campos del formulario',
                                buttons: {
                                    confirmar: {
                                        btnClass: 'btn-success',
                                        action: function () {
                                            console.log(data)
                                            const self2 = this
                                            $('#user_id').val(data.identificador).selectpicker('refresh').trigger('change');
                                            self.showLoading(true)
                                            $('#pempresa').val(data.empresa);
                                            setTimeout(function () {
                                                $('#cliente_id').val(data.cliente_id).selectpicker('refresh').trigger('change');
                                                setTimeout(function () {
                                                    $('#pruc').val(data.ruc).selectpicker('refresh').trigger('change');
                                                    setTimeout(function () {
                                                        $('#ptipo_banca').val(data.tipo_banca).selectpicker('refresh').trigger('change');

                                                        $('#pmes').val(data.mes).selectpicker('refresh').trigger('change');

                                                        $('#panio').val(data.year).selectpicker('refresh').trigger('change');

                                                        $('#pcantidad').val(data.cantidad);

                                                        $('#pdescripcion').val(data.descripcion);
                                                        $('#pnota').val(data.nota);
                                                        $('#pcourier').val(data.courier_price);
                                                        self.close()
                                                    }, 1000)
                                                }, 1000)
                                            }, 2000)
                                            return true
                                        }
                                    },
                                    cancelar: {
                                        btnClass: 'btn-secundary'
                                    }
                                }
                            })
                        })
                        self.$content.find('[data-delete]').click(function () {
                            const index = $(this).data('index')
                            const data = self.getItemByIndex(index)
                            $.confirm({
                                title: 'Advertencia',
                                type: 'red',
                                icon: 'fa fa-exclamation-triangle text-danger',
                                content: 'Se eliminara el registro',
                                buttons: {
                                    confirmar: {
                                        btnClass: 'btn-danger',
                                        action: function () {
                                            const self2 = this
                                            self2.showLoading(true)
                                            $.post('{{route('pedidos.store.delete-history')}}', {
                                                history_id: data.id
                                            }).done(function () {
                                                self2.close();
                                                //recarga la tabla del metodo definido anteriormente
                                                self.loadContent();
                                            }).always(function () {
                                                self2.hideLoading(true)
                                            })
                                            return false
                                        }
                                    },
                                    cancelar: {
                                        btnClass: 'btn-secondary'
                                    }
                                }
                            })
                        })
                    },
                    getItemByIndex: function (index) {
                        return this.$ajaxdata[index]
                    }
                })
            })
        })
    </script>

    <script>
        var tabladeudores = null;
        var tablahistorial = null;
    </script>

    @if (session('info') == 'registrado')
        <script>
            Swal.fire(
                'RUC {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>
        ///fin

        //VALIDAR CAMPOS NUMERICO DE MONTO EN PAGOS

        $('input.number').keyup(function (event) {

            if (event.which >= 37 && event.which <= 40) {
                event.preventDefault();
            }

            $(this).val(function (index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        });

        //VALIDAR CAMPO RUC
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }

        // CARGAR RUCS DE CLIENTE SELECCIONADO


        // CARGAR CLIENTES DE ASESOR


        // CARGAR TIPO DE COMPROBANTE Y BANCA/PORCENTAJES DE CLIENTE SELECCIONADO


        //VALIDACION DE CAMPOS


        var cont = 0;
        total = 0;
        subtotal = [];
        $("#guardar").hide();
        $("#ptipo_banca").change(mostrarValores);

        function mostrarValores() {
            datosTipoBanca = document.getElementById('ptipo_banca').value.split('_');
            $("#pporcentaje").val(datosTipoBanca[1]);
        }

        function ValidarDatosPedido() {
            pruc = $("#pruc option:selected").val();
            nombre_empresa = $("#pempresa").val();
            //ASESOR
            asesor_ide = $("#user_id option:selected").val();

            //CLIENTE
            cliente_ide = $("#cliente_id option:selected").val();

            //MES
            mes = $("#pmes").val();

            //AÑO
            anio = $("#panio").val();

            // CANTIDAD
            var cant_strEx = $("#pcantidad").val(); //1,000.00
            cant_strEx = cant_strEx.replace(",", ""); //1000.00
            var can_numFinal = parseFloat(cant_strEx);

            // BANCA
            var selectedTipoBanca = document.getElementById('ptipo_banca').value.split('_');
            var ptipo_banca = selectedTipoBanca[0];

            // PORCENTAJE
            //porcentaje = $("#pporcentaje").val();

            // COURIER
            /*var strEx = $("#pcourier").val(); //1,000.00
            strEx = strEx.replace(",", ""); //1000.00
            var numFinal = parseFloat(strEx);
            courier = numFinal * 1;

            var respuesta_validacion = "";*/


            $.ajax({
                data: {
                    ruc: pruc,
                    nombre_empresa: nombre_empresa,
                    asesor: asesor_ide,
                    cliente: cliente_ide,
                    mes: mes,
                    ano: anio,
                    ptipo_banca: ptipo_banca,
                    cantidad: cant_strEx
                    //banca : tipo_banca,
                    //porcentaje : porcentaje,
                    //courier : courier
                },
                type: 'POST',
                url: "{{ route('validarpedido') }}",
                success: function (data) {
                    console.log(data.html);

                    if (!data.is_repetido) {
                        agregar();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            html: 'Este pedido ya se encuentra regitrado con el codigo <b>' + data.codigos + '</b>',
                            showDenyButton: true,
                            confirmButtonText: 'Estoy de acuerdo',
                            denyButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //agregar();
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Advertencia',
                                        text: 'Recuerda que tienes que revisar si es un pedido duplicado',
                                        showDenyButton: true,
                                        confirmButtonText: 'Estoy de acuerdo, Agregar',
                                        denyButtonText: 'Cancelar, No Agregar',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            agregar();
                                        }
                                    })
                                    //agregar();
                                }
                            }
                        })
                    }
                }
            });
            return false;
        };

        function agregar() {
            datosTipoBanca = document.getElementById('ptipo_banca').value.split('_');
            datosCodigo = document.getElementById('pcodigo').value.split('-');

            var strEx = $("#pcantidad").val(); //1,000.00
            //primer paso: fuera coma
            strEx = strEx.replace(",", ""); //1000.00
            var numFinal = parseFloat(strEx);
            cantidad = numFinal * 1;

            var strEx = $("#pcourier").val(); //1,000.00
            //primer paso: fuera coma
            strEx = strEx.replace(",", ""); //1000.00
            var numFinal = parseFloat(strEx);
            courier = numFinal * 1;

            //codigo = $("#pcodigo").val();
            numped = datosCodigo[1];
            nombre_empresa = $("#pempresa").val();
            mes = $("#pmes").val();
            anio = $("#panio").val();
            ruc = $("#pruc").val();
            /* cantidad = $("#pcantidad").val(); */
            tipo_banca = datosTipoBanca[0];
            porcentaje = $("#pporcentaje").val();
            /* courier = $("#pcourier").val(); */
            descripcion = $("#pdescripcion").val();
            nota = $("#pnota").val();
            var files = Array.from($("#adjunto")[0].files)
                .map(function (file) {
                    return {
                        name: file.name.toLowerCase(),
                        url: URL.createObjectURL(file)
                    }
                })

            validasobre = $("#txtValidaSobre").val(); //
            if (nombre_empresa != "" && mes != "") {
                subtotal[cont] = (cantidad * porcentaje) / 100;
                total = Number(courier) + subtotal[cont];


                var fila = `<tr class="selected" id="fila${cont}"><td><button type="button" class="btn btn-warning" onclick="eliminar(${cont});">X</button></td>
<td><textarea class="d-none" name="nombre_empresa[]">${nombre_empresa}</textarea>${nombre_empresa}</td>
<td><input type="hidden" name="mes[]" value="${mes}">${mes}</td>
<td><input type="hidden" name="anio[]" value="${anio}">${anio}</td>
<td><input type="hidden" name="ruc[]" value="${ruc}">${ruc}</td>
<td><input type="hidden" name="cantidad[]" value="${cantidad}">${cantidad.toLocaleString("en-US")}</td>
<td><input type="hidden" name="tipo_banca[]" value="${tipo_banca}">${tipo_banca}</td>
<td><input type="hidden" name="porcentaje[]" value="${porcentaje}">${porcentaje}</td>
<td><input type="hidden" name="courier[]" value="${courier}">${courier}</td>
<td style=''width: 93px;'' ><textarea class="d-none" name="descripcion[]">${descripcion}</textarea>${descripcion}</td>
<td ><textarea class="d-none" name="nota[]" >${nota}</textarea>${nota}</td>
<td><input type="hidden" name="validasobres[]" value="${validasobre}">${validasobre}</td>
<td>
<div class="list-group">
${files.map(function (file) {
                    if (/(.jpg|.jpeg|.png|.webp)/i.test(file.name)) {
                        return `<a class="list-group-item" href="${file.url}" target="_blank"><img src="${file.url}" class="w-100"></a>`
                    } else {
                        return `<a class="list-group-item" style="background: rgb(108 117 125 / 11%)" href="${file.url}" target="_blank"><i class="fa fa-file"></i> ${file.name}</a>`
                    }
                }).join('')}
</div>
</td>
<td>${subtotal[cont].toLocaleString("en-US")}</td></tr>
`
                cont++;
                limpiar();
                $("#total").html("S/. " + total.toLocaleString("en-US"));
                evaluar();
                $('#detalles').append(fila);
                $("#bt_add_dir").removeClass("d-none")
                $("#section_content_address").show()
            } else {
                alert("error al ingresar el detalle del pedido, revise los datos");
            }
        }

        function limpiar() {
            /* $("#pcodigo").val("{{ $fecha }}-"+( Number(numped)+1 )); */
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
            $("#adjunto").val(null);
            $("#total").html("S/. 0.00");
            $("#fila" + index).remove();
            cont--;
            evaluar();
            $("#pcodigo").val("{{ Auth::user()->identificador }}-{{ $fecha }}-{{ $numped }}");
            $("#section_content_address").hide()
            $("#table_direccion_body").html('')
        }
    </script>

    <script>
        //VALIDAR ANTES DE ENVIAR
        /*document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("formulario").addEventListener('submit', validarFormulario);
        });*/

        /*function validarFormulario(evento) {
          evento.preventDefault();

            window.open('https://sistema.ojoceleste.com/pedidos.mispedidos', '_blank');//CAMBIAR A LINK DE PRODUCCION//************
            this.submit();
        }*/
    </script>

    <script>
        //VALIDAR ANTES DE ENVIAR 2
        /*document.addEventListener("DOMContentLoaded", function() {
        var form = document.getElementById("formulario2")
          if(form)
          {
            form.addEventListener('submit', validarFormulario2);
          }
        });*/

        /*function validarFormulario2(evento) {
          evento.preventDefault();
          var agregarruc = document.getElementById('agregarruc').value;

          if (agregarruc == '') {
              Swal.fire(
                'Error',
                'Debe ingresar el número de RUC',
                'warning'
              )
          }
          else if (agregarruc.length < 11){
            Swal.fire(
                'Error',
                'El número de RUC debe tener 11 dígitos',
                'warning'
              )
          }
          else {
            this.submit();
          }
        }*/
    </script>

    <script>
        $(document).ready(function () {

            $(document).on("click", ".eliminar_dir", function () {
                let row_tr = $(this).closest('tr').remove();
                console.log(row_tr)
                $("#bt_add_dir").removeClass("d-none");
            })

            $('#env_pedido_quienrecibe_nombre').on('input', function () {
                this.value = this.value.replace(/[^a-zA-Z >]/g, '');
            });

            $('#env_pedido_quienrecibe_celular').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#env_pedido_direccion,#env_pedido_referencia,#env_pedido_observacion').on('input', function () {
                this.value = this.value.replace(/[^0-9 a-zA-Z]/g, '');
            });

            window.agregar_direccion = function () {
                console.log("agregar direccion event")
                let observacion = null
                if ($("#recojo_destino").val() == "LIMA") {
                    observacion = $("#env_pedido_observacion").val();
                } else {
                    observacion = '@csrf<input type="file" name="observacion_env" />'
                }

                var fila = '<tr class="selected"' +
                    '><td><button type="button" class="btn btn-warning eliminar_dir">X</button></td>' +
                    '<td><input type="hidden" id="destino_env" name="destino_env" value="' + $("#recojo_destino").val() + '">' + $("#recojo_destino").val() + '</td>' +
                    '<td><input type="hidden" id="distrito_env" name="distrito_env" value="' + $("#distrito_recoger").val() + '">' + $("#distrito_recoger").val() + '</td>' +
                    '<td><input type="hidden" id="zona_env" name="zona_env" >' + 'ZONA' + '</td>' +
                    '<td><input type="hidden" id="contacto_nom_env" name="contacto_nom_env" value="' + $("#env_pedido_quienrecibe_nombre").val() + '">' + $("#env_pedido_quienrecibe_nombre").val() + '</td>' +
                    '<td><input type="hidden" id="contacto_cel_env" name="contacto_cel_env" value="' + $("#env_pedido_quienrecibe_celular").val() + '">' + $("#env_pedido_quienrecibe_celular").val() + '</td>' +
                    '<td><input type="hidden" id="direccion_env" name="direccion_env" value="' + $("#env_pedido_direccion").val() + '">' + $("#env_pedido_direccion").val() + '</td>' +
                    '<td><input type="hidden" id="referencia_env" name="referencia_env" value="' + $("#env_pedido_referencia").val() + '">' + $("#env_pedido_referencia").val() + '</td>';

                if ($("#recojo_destino").val() == "LIMA") {
                    fila = fila + '<td><input type="hidden" id="observacion_env" name="observacion_env" >' + observacion + '</td>';
                } else {
                    fila = fila + '<td>' + observacion + '</td>';
                }
                str_importe = parseFloat($("#env_pedido_importe").val().replace(",", "")) * 1;
                fila = fila + '<td><input type="hidden" id="maps_env" name="maps_env" value="' + $("#env_pedido_map").val() + '">' + $("#env_pedido_map").val() + '</td>'
                fila = fila + '<td><input type="hidden" id="importe_env" name="importe_env" value="' + str_importe + '">' + str_importe + '</td>'
                fila = fila + '</tr>';


                $('#table_direccion').append(fila);
                $("#modal-direccion_crearpedido").modal("hide");
                $("#bt_add_dir").addClass("d-none");

            }

            function agregar() {
                datosTipoBanca = document.getElementById('ptipo_banca').value.split('_');
                datosCodigo = document.getElementById('pcodigo').value.split('-');

                var strEx = $("#pcantidad").val(); //1,000.00
                //primer paso: fuera coma
                strEx = strEx.replace(",", ""); //1000.00
                var numFinal = parseFloat(strEx);
                cantidad = numFinal * 1;

                var strEx = $("#pcourier").val(); //1,000.00
                //primer paso: fuera coma
                strEx = strEx.replace(",", ""); //1000.00
                var numFinal = parseFloat(strEx);
                courier = numFinal * 1;

                //codigo = $("#pcodigo").val();
                numped = datosCodigo[1];
                nombre_empresa = $("#pempresa").val();
                mes = $("#pmes").val();
                anio = $("#panio").val();
                ruc = $("#pruc").val();
                /* cantidad = $("#pcantidad").val(); */
                tipo_banca = datosTipoBanca[0];
                porcentaje = $("#pporcentaje").val();
                /* courier = $("#pcourier").val(); */
                descripcion = $("#pdescripcion").val();
                nota = $("#pnota").val();

                if (nombre_empresa != "" && mes != "") {
                    subtotal[cont] = (cantidad * porcentaje) / 100;
                    total = Number(courier) + subtotal[cont];

                    var fila = '<tr class="selected" id="fila' + cont +
                        '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont +
                        ');">X</button></td>' +
                        //'<td><input type="hidden" name="codigo[]" value="' + codigo + '">' + codigo + '</td>' +
                        '<td><textarea class="d-none" name="nombre_empresa[]">' + nombre_empresa + '</textarea>' + nombre_empresa +
                        '</td>' +
                        '<td><input type="hidden" name="mes[]" value="' + mes + '">' + mes + '</td>' +
                        '<td><input type="hidden" name="anio[]" value="' + anio + '">' + anio + '</td>' +
                        '<td><input type="hidden" name="ruc[]" value="' + ruc + '">' + ruc + '</td>' +
                        '<td><input type="hidden" name="cantidad[]" value="' + cantidad + '">' + cantidad.toLocaleString(
                            "en-US") + '</td>' +
                        '<td><input type="hidden" name="tipo_banca[]" value="' + tipo_banca + '">' + tipo_banca + '</td>' +
                        '<td><input type="hidden" name="porcentaje[]" value="' + porcentaje + '">' + porcentaje + '</td>' +
                        '<td><input type="hidden" name="courier[]" value="' + courier + '">' + courier + '</td>' +
                        '<td><textarea class="d-none" name="descripcion[]">' + descripcion + '</textarea>' + descripcion + '</td>' +
                        '<td><textarea class="d-none" name="nota[]" >' + nota + '</textarea>' + nota + '</td>' +
                        '<td>@csrf<input type="file" id="adjunto" name="adjunto[]" multiple /></td>' +
                        '<td>' + subtotal[cont].toLocaleString("en-US") + '</td></tr>';
                    cont++; //accept= ".zip, .rar"
                    limpiar();
                    $("#total").html("S/. " + total.toLocaleString("en-US"));
                    evaluar();
                    $('#detalles').append(fila);
                } else {
                    alert("error al ingresar el detalle del pedido, revise los datos");
                }
            }

            $("#modal-direccion_crearpedido").on('show.bs.modal', function () {

                $("#recojo_destino").val("").selectpicker("refresh").trigger("change");
                $("#env_pedido_quienrecibe_nombre")
                $("#env_pedido_quienrecibe_celular")
                $("#env_pedido_direccion").val("")
                $("#env_pedido_referencia").val("")
                $("#env_pedido_observacion").val("")

            })


            $(document).on("change", "#recojo_destino", function () {
                ///solo distritos de la ubicacion

                $.ajax({
                    data: {
                        destino: $("#recojo_destino").val()
                    },
                    type: 'POST',
                    url: "{{ route('pedidos.cargardistritos') }}",
                    success: function (data) {
                        //relleno cmb
                        console.log(data)
                        let opcion = null;
                        $('#distrito_recoger').html("")
                        $.each(data, function (i, item) {
                            opcion = $('<option>').attr('data-subtext', data[i].zona).attr('value', data[i].distrito).text(data[i].distrito)
                            $('#distrito_recoger').append(opcion);
                        });
                        $('#distrito_recoger').selectpicker("refresh")

                        if ($("#recojo_destino").val() == 'OLVA') {
                            $(".s_observacion").hide();
                            $("#recojo_pedido_direccion").html("Tracking")
                            $("#recojo_pedido_referencia").html("Num Registro")
                            $("#env_pedido_quienrecibe_nombre").val("OLVA");
                            $("#env_pedido_quienrecibe_celular").val("OLVA");
                            //direccio n tracking
                            //referencia numr
                        } else {
                            $(".s_observacion").show();
                            $("#lbl_recojo_pedido_direccion").text("Direccion")
                            $("#lbl_recojo_pedido_referencia").text("Referencia")
                            $("#env_pedido_quienrecibe_nombre").val("");
                            $("#env_pedido_quienrecibe_celular").val("");
                        }


                    }
                });

                $("#distrito").val("").selectpicker("refresh")
            });

            /*$(document).on("change", "#recojo_destino", function () {
                $("#distrito").val("").selectpicker("refresh")
            });*/

            //$(document).on("change", "#recojo_destino", function () {
            $("#distrito_recoger").val("").selectpicker("refresh")
            //});

            //$('#pmes').selectpicker('refresh');
            //$('#panio').selectpicker('refresh');

            /*$(document).on("change","#pmes",function(){
                console.log("sss")
                let ptipo_banca=$("#ptipo_banca").val().split('-')[0];
                console.log(ptipo_banca);
            })*/

            $(document).on("change", "#panio", function () {
                //obtengo banca

                let ptipo_banca = $("#ptipo_banca").val().split('-')[0];

                //obtengo anio
                console.log("banca " + ptipo_banca)
                let anno_filter = parseInt($(this).val());
                console.log(anno_filter)

                if (ptipo_banca == '') {
                } else {
                    if (ptipo_banca == 'FISICO ') {
                        if (isNaN(anno_filter)) {
                            console.log("anno is nan")
                        } else {
                            if (anno_filter != '') {
                                console.log({{$anno_selected}});
                                if (anno_filter == {{$anno_selected}} || anno_filter == ({{$anno_selected}} - 1) || anno_filter == ({{$anno_selected}} - 2)) {
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No puede seleccionar este año para banca fisica, elija otra opcion por favor',
                                        'warning'
                                    );
                                    $("#panio").val('').selectpicker('refresh')
                                }
                            }
                        }


                    } else if (ptipo_banca == 'ELECTRONICA') {
                        if (isNaN(anno_filter)) {
                            console.log("anno is nan")
                        } else {
                            if (anno_filter != '') {
                                if (anno_filter == {{$anno_selected}}) {

                                } else {
                                    //2023= 2023
                                    Swal.fire(
                                        'Error',
                                        'No puede seleccionar este año para banca eletronica',
                                        'warning'
                                    );
                                    //return false;
                                    $("#panio").val('').selectpicker('refresh')
                                }
                            }
                        }
                    }
                }

            })

            $(document).on("change", "#ptipo_banca", function () {
                //obtengo banca
                let ptipo_banca = $.trim($(this).val().split('-')[0]);
                //obtengo anio
                console.log("banca " + ptipo_banca)
                let anno_filter = parseInt($("#panio").val());
                console.log(anno_filter)

                if (ptipo_banca == '') {
                } else {
                    if (ptipo_banca == 'FISICO') {
                        if (isNaN(anno_filter)) {
                            console.log("anno is nan")
                        } else {
                            if (anno_filter != '') {
                                if (anno_filter == {{$anno_selected}} || anno_filter == ({{$anno_selected}} - 1) || anno_filter == ({{$anno_selected}} - 2)) {
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No puede seleccionar este año para banca fisica, elija otra opcion por favor',
                                        'warning'
                                    );
                                    //return false;
                                    $("#panio").val('').selectpicker('refresh')
                                }
                            }
                        }

                    } else if (ptipo_banca == 'ELECTRONICA') {
                        if (isNaN(anno_filter)) {
                            console.log("anno is nan")
                        } else {
                            if (anno_filter != '') {
                                if (anno_filter == {{$anno_selected}}) {

                                } else {
                                    if (anno_filter)
                                        //2023= 2023
                                        Swal.fire(
                                            'Error',
                                            'No puede seleccionar este año para banca eletronica',
                                            'warning'
                                        );
                                    //return false;
                                    $("#panio").val('').selectpicker('refresh');
                                }
                            }
                        }

                    }
                }
            })
            $(document).on("submit", "#formulario", function (event) {
                event.preventDefault();
                //console.log("abrir")

                var fd = new FormData();
                //var data = new FormData(document.getElementById("formulario"));


                $('[name="nombre_empresa[]"]').each(function () {
                    fd.append("nombre_empresa[]", this.value);
                });
                $('[name="mes[]"]').each(function () {
                    fd.append("mes[]", this.value);
                });
                $('[name="anio[]"]').each(function () {
                    fd.append("anio[]", this.value);
                });
                $('[name="ruc[]"]').each(function () {
                    fd.append("ruc[]", this.value);
                });
                $('[name="cantidad[]"]').each(function () {
                    fd.append("cantidad[]", this.value);
                });
                $('[name="tipo_banca[]"]').each(function () {
                    fd.append("tipo_banca[]", this.value);
                });
                $('[name="porcentaje[]"]').each(function () {
                    fd.append("porcentaje[]", this.value);
                });
                $('[name="courier[]"]').each(function () {
                    fd.append("courier[]", this.value);
                });
                $('[name="descripcion[]"]').each(function () {
                    fd.append("descripcion[]", this.value);
                });
                $('[name="nota[]"]').each(function () {
                    fd.append("nota[]", this.value);
                });
                $('[name="validasobres[]"]').each(function () {
                    fd.append("validasobres[]", this.value);
                });
                let files = $('[name="adjunto[]');
                if (files[0].files.length > 0) {
                    for (let i in files[0].files) {
                        fd.append('adjunto[]', files[0].files[i]);
                    }
                }
                $("#btnImprimir").prop("disabled", true);
                fd.append('user_id', $("#user_id").val());
                fd.append('cliente_id', $("#cliente_id").val());

                fd.append('destino_env', (($("#destino_env").val()) ? $("#destino_env").val() : ''))
                fd.append('distrito_env', (($("#distrito_env").val()) ? $("#distrito_env").val() : ''))
                fd.append('zona_env', (($("#zona_env").val()) ? $("#zona_env").val() : ''))
                fd.append('contacto_nom_env', (($("#contacto_nom_env").val()) ? $("#contacto_nom_env").val() : ''))
                fd.append('contacto_cel_env', (($("#contacto_cel_env").val()) ? $("#contacto_cel_env").val() : ''))
                fd.append('direccion_env', (($("#direccion_env").val()) ? $("#direccion_env").val() : ''))
                fd.append('referencia_env', (($("#referencia_env").val()) ? $("#referencia_env").val() : ''))
                if ($("#destino_env").val() == "LIMA") {
                    fd.append('observacion_env', (($("#observacion_env").val()) ? $("#observacion_env").val() : ''))
                } else if ($("#destino_env").val() == "OLVA") {
                    if ($("#observacion_env").val()) {
                        var file_data = $('input[type=file][name="observacion_env"]')[0].files[0]
                        fd2.append('observacion_env', file_data, file_data.name);
                    }
                }
                fd.append('maps_env', (($("#maps_env").val()) ? $("#maps_env").val() : ''))
                fd.append('importe_env', (($("#importe_env").val()) ? $("#importe_env").val() : ''))

                $.ajax({
                    data: fd,
                    //data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('pedidoss.store') }}",
                    success: function (data) {
                        console.log(data);
                        if (data.html == '|2') {
                            Swal.fire(
                                'Error',
                                'Cliente supero el limite de pedidos (3) en el mes.',
                                'warning'
                            )

                        } else if (data.html == '|4') {
                            Swal.fire(
                                'Error',
                                'Cliente supero el limite de pedidos (5) en el mes.',
                                'warning'
                            )

                        } else if (data.html == '|0') {
                            Swal.fire(
                                'Error',
                                'Cliente mantiene deudas meses atras.',
                                'warning'
                            )

                        } else if (data.html == '|tmp_time') {
                            Swal.fire(
                                'Error',
                                'El tiempo dado para registrar pedidos expiro para el cliente seleccionado.',
                                'warning'
                            )
                        } else if (data.html == '|tmp_count') {
                            Swal.fire(
                                'Error',
                                'El cliente supero la cantidad dada para crearle pedidos extras.',
                                'warning'
                            )
                        } else {
                            var urlpdf = '{{ route('pedidosPDF', ':id') }}';
                            urlpdf = urlpdf.replace(':id', data.html);
                            window.open(urlpdf, '_blank');

                            $("#modal-copiar .textcode").text(data.html);

                            $("#modal-copiar").modal("show");
                        }
                    }
                });
            });

            $(document).on("change", "#user_id", function () {
                var uid = $(this).val();
                console.log('uid ' + uid)

                var clientedeasesor = new FormData();
                clientedeasesor.append('user_id', uid);
                $.ajax({
                    data: clientedeasesor,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('cargar.clientedeasesor') }}",
                    success: function (data) {
                        $('#cliente_id').html(data.html);
                        $("#cliente_id").selectpicker("refresh");
                        $('#cliente_id_ruc').html(data.html);
                        let c_cliente_id = $('#cliente_id').val();
                        $('#cliente_id_ruc').selectpicker('refresh');
                        $('#cliente_id_ruc').val(c_cliente_id);
                    }
                });

            });

            /*console.log(" {{ Auth::user()->id }} ")
                $('#user_id option').attr("disabled", true);
                $("#user_id").val( "{{ Auth::user()->id }}" ).trigger("change");
                $("#user_id").selectpicker("refresh");*/


            $(document).on("submit", "#formrecojo", function (evento) {
                evento.preventDefault();
                let recojo_distrito = $("#distrito_recoger").val()
                let recojo_pedido_quienrecibe_nombre = $("#env_pedido_quienrecibe_nombre").val()
                let recojo_pedido_quienrecibe_celular = $("#env_pedido_quienrecibe_celular").val()
                let recojo_pedido_direccion = $("#env_pedido_direccion").val()
                let recojo_pedido_referencia = $("#env_pedido_referencia").val()
                let recojo_pedido_observacion = $("#env_pedido_observacion").val()
                let recojo_pedido_rotulo = $("#env_pedido_rotulo").val()
                let recojo_pedido_map = $("#env_pedido_map").val()

                if (recojo_distrito == "") {
                    Swal.fire('Debe elegir un distrito', '', 'warning');
                    return false;
                }//datos de envio
                else if (recojo_pedido_quienrecibe_nombre == "") {
                    Swal.fire('Debe ingresar quien recibe', '', 'warning');
                    return false;
                } else if (recojo_pedido_quienrecibe_celular == "") {
                    Swal.fire('Debe ingresar celular de quien recibe', '', 'warning');
                    return false;
                } else if (recojo_pedido_direccion == "") {
                    Swal.fire('Debe ingresar direccion', '', 'warning');
                    return false;
                } else if (recojo_pedido_referencia == "") {
                    Swal.fire('Debe ingresar referencia', '', 'warning');
                    return false;
                }
                //cantidad = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;   para el importe
                agregar_direccion();
            });

            $(document).on("click", "#bt_add", function () {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                if ($('#pcliente_id').val() == '') {
                    Swal.fire(
                        'Error',
                        'Seleccione Cliente',
                        'warning'
                    )
                } else if ($('#pempresa').val() == '') {
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
                } else if ($('#pdescripcion').val() == '') {
                    Swal.fire(
                        'Error',
                        'Agregue descripción del pedido',
                        'warning'
                    )
                } else if ($('#pnota').val() == '') {
                    Swal.fire(
                        'Error',
                        'Agregue nota del pedido',
                        'warning'
                    )
                } else {
                    const arrayCombo = ['{{\App\Models\DetallePedido::ELECTRONICA_SIN_BANCA_DESC}}'];
                    const valorTipoBanca = $('#ptipo_banca').val().split('_')[0];
                    if (arrayCombo.includes(valorTipoBanca)) {
                        swalWithBootstrapButtons.fire({
                            title: 'EL SIGUIENTE PEDIDO SE VA A REALIZAR <b>SIN SOBRE</b>',
                            text: "ESTAS DE ACUERDO",
                            icon: 'warning',
                            showCancelButton: true,
                            cancelButtonText: 'SI, SIN SOBRE',
                            confirmButtonText: 'NO, CON SOBRE',

                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                swalWithBootstrapButtons.fire({
                                    title: 'ESTAS SEGURO DE CREAR UN PEDIDO <b>CON SOBRE</b>',
                                    text: "¿Estás seguro (a)? Revisa antes de Continuar.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    cancelButtonText: 'NO',
                                    confirmButtonText: 'SI',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $("#txtValidaSobre").val('Si');
                                        swalWithBootstrapButtons.fire(
                                            'Confirmado',
                                            'Pedido agregado correctamente',
                                            'success'
                                        )
                                        cantidad = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
                                        ValidarDatosPedido();
                                    } else if (
                                        result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                        $("#txtValidaSobre").val('No');
                                        swalWithBootstrapButtons.fire(
                                            'No',
                                            'Hiciste lo correcto, al revisar.',
                                            'success'
                                        )
                                        cantidad = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
                                        ValidarDatosPedido();
                                    }
                                })
                            } else if (
                                result.dismiss === Swal.DismissReason.cancel
                            ) {
                                $("#txtValidaSobre").val('No');
                                swalWithBootstrapButtons.fire(
                                    'No',
                                    'Se agrega pedido sin sobre.',
                                    'success'
                                )
                                cantidad = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
                                ValidarDatosPedido();
                            }
                        })
                    } else {
                        cantidad = !isNaN($('#pcantidad').val()) ? parseInt($('#pcantidad').val(), 10) : 0;
                        ValidarDatosPedido();
                    }
                }
            });

            $(document).on("change", "#cliente_id", function () {
                $.ajax({
                    url: "{{ route('cargar.tipobanca') }}?cliente_id=" + $(this).val(),
                    method: 'GET',
                    success: function (data) {
                        $('#ptipo_banca').html(data.html);
                    }
                });
            });

            $(document).on("change", "#cliente_id", function () {

                $.ajax({
                    url: "{{ route('cargar.ruc') }}?cliente_id=" + $(this).val(),
                    method: 'GET',
                    success: function (data) {
                        $('#pruc').html(data.html);
                        $("#pruc").selectpicker("refresh");

                    }
                });
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //$("#user_id").val("").trigger("change");
            //$("#cliente_id").val("").trigger("change");
            //$("#pruc").val("");//.trigger("change");
            //$("#user_id").selectpicker("refresh");


            $(document).on("change", "#pruc", function () {
                //al cambiar el ruc que hacer
                $.ajax({
                    url: "{{ route('rucnombreempresa') }}?ruc=" + $(this).val(),
                    method: 'GET',
                    //before
                    success: function (data) {
                        $('#pempresa').val((data.html));
                    }
                });
            });

            $('#modal-add-ruc').on('show.bs.modal', function (event) {

                $("#agregarruc").val("");
                $("#pempresaruc").val("");
                $("#porcentajeruc").val("");


                //limpiar datos

                let c_cliente_id = $('#cliente_id').val(); //
                //console.log(c_cliente_id+"id carga cliente para ruc");


                $('#cliente_id_ruc').val(c_cliente_id);
                $('#cliente_id_ruc option').attr("disabled", true);
                $('#cliente_id_ruc option[value="' + c_cliente_id + '"]').attr("disabled", false);

                $('#cliente_id_ruc').selectpicker('refresh');
            });

            $('#modal-historial').on('show.bs.modal', function (event) {
                //let c_cliente_id=$('#cliente_id').val();//
                //let c_ruc=$('#pruc').val();

                $('#tablaPrincipal').DataTable().clear().destroy();

                $('#tablaPrincipal').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    "order": [
                        [0, "desc"]
                    ],
                    ajax: {
                        url: "{{ route('deudoresoncreate') }}",
                        data: function (d) {
                            //d.buscarpedidocliente = c_cliente_id;
                            //d.buscarpedidoruc = c_ruc;

                        },
                    },
                    "createdRow": function (row, data, dataIndex) {
                    },
                    "autoWidth": false,
                    rowCallback: function (row, data, index) {
                    },
                    columns: [/*{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        sWidth: '10%',
                    },*/
                        {
                            data: 'celular',
                            name: 'celular',
                            sWidth: '70%',
                            render: function (data, type, row, meta) {
                                return row.celular + " - " + row.nombre;
                            }
                        },
                        {
                            data: 'estado',
                            name: 'estado',
                            sWidth: '20%',
                            render: function (data, type, row, meta) {
                                return '<span class="badge badge-danger">Deudor</span>';
                            }
                        },
                    ],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay informaciÃ³n",
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
                //recree mi tabla

            });

            $('#modal-copiar').on('show.bs.modal', function (event) {

                //cargar informacion para copiar del pedido
                //consulta traer datos
                let string_copiar = $("#modal-copiar .textcode").html();
                //console.log(string_copiar)
                var fd = new FormData();
                fd.append('infocopiar', string_copiar); //4721
                //fd.append("infocopiar", string_copiar);

                $.ajax({
                    //async:false,
                    type: 'POST',
                    url: "{{ route('pedidos.infopdf') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    console.log(data);
                    //console.log(data.codigo);
                    let copydata = "*S/." + data.cantidad + " * " + data.porcentaje + "% = S/." +
                        data.ft + "*\n" +
                        "*ENVIO = S/." + data.courier + "*\n" +
                        "*TOTAL = S/." + data.total + "*\n\n" +
                        "*ES IMPORTANTE PAGAR EL ENVIO* \n";

                    $("#pedido_copiar").val(copydata);
                    $("#pedido_copiar_2").val(copydata).removeClass("d-none");

                    const textarea = document.createElement('textarea');
                    document.body.appendChild(textarea);
                    textarea.value = $("#pedido_copiar").val();
                    textarea.select();
                    textarea.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    $("#pedido_copiar").after("Copiado");

                    $("#formulario").trigger("reset");

                    $("#total").html("S/. 0.00");

                    //var rowCount = $('#detalles tr').length;
                    $("#detalles > tbody").empty();
                    //$("#fila" + index).remove();
                    $("#user_id").val("").trigger("change");
                    $("#cliente_id").val("").trigger("change");
                    $("#pruc").val(""); //.trigger("change");
                    $("#user_id").selectpicker("refresh");
                    //$("#cliente_id").selectpicker("refresh");
                    $("#pruc").selectpicker("refresh");
                    $("#pedido_copiar_2").val(copydata);
                    cont--;
                    evaluar();

                    $('#cerrar-modal-copiar').on('click', function () {
                        console.log("Test");
                        location.reload();
                    });

                });
            });

            $('#modal-historial-2').on('show.bs.modal', function (event) {
                let c_cliente_id = $('#cliente_id').val(); //
                let c_ruc = $('#pruc').val();

                $('#tablaPrincipalHistorial').DataTable().clear().destroy();
                $('#tablaPrincipalHistorial').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    "order": [
                        [0, "desc"]
                    ],
                    ajax: {
                        url: "{{ route('pedidostablahistorial') }}",
                        data: function (d) {
                            d.buscarpedidocliente = c_cliente_id;
                            d.buscarpedidoruc = c_ruc;

                        },
                    },
                    "createdRow": function (row, data, dataIndex) {
                    },
                    "autoWidth": false,
                    rowCallback: function (row, data, index) {
                    },
                    columns: [

                        {
                            data: 'id',
                            name: 'id',
                            "visible": false,
                            render: function (data, type, row, meta) {
                                if (row.id < 10) {
                                    return 'PED000' + row.id;
                                } else if (row.id < 100) {
                                    return 'PED00' + row.id;
                                } else if (row.id < 1000) {
                                    return 'PED0' + row.id;
                                } else {
                                    return 'PED' + row.id;
                                }
                            }
                        },
                        {
                            data: 'descripcion',
                            name: 'descripcion',
                            sWidth: '70%',
                        },
                        {
                            data: 'nota',
                            name: 'nota',
                        },
                        {
                            data: 'adjunto',
                            name: 'adjunto',
                            render: function (data, type, row, meta) {
                                var str = "storage/pagos/" + data;
                                var urlimage = '{{ asset(':id') }}';

                                urlimage = urlimage.replace(':id', str);
                                data = '<img src="' + urlimage + '" alt="' + urlimage +
                                    '" height="200px" width="200px" class="img-thumbnail">';
                                return data
                            }

                        },
                    ],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay informaciÃ³n",
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
            });


            $("#formulario2").submit(function (event) {
                event.preventDefault();
                //console.log("fdormulario 2")
                var agregarruc = $("#agregarruc").val();
                if (agregarruc == '') {
                    Swal.fire(
                        'Error',
                        'Debe ingresar el número de RUC',
                        'warning'
                    )
                    return;
                } else if (agregarruc.length < 11) {
                    Swal.fire(
                        'Error',
                        'El número de RUC debe tener 11 dígitos',
                        'warning'
                    )
                    return;
                }

                var formData = $("#formulario2").serialize();
                //validar primero
                $.ajax({
                    type: 'POST',
                    url: "{{ route('validarrelacionruc') }}",
                    data: formData + '&user_id=' + $("#user_id").val(),
                }).done(function (data) {
                    //console.log(data.html);
                    var sese = data.html.split("|");
                    if (sese[0] == '1') {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('pedidos.agregarruc') }}",
                            data: formData,
                        }).done(function (data) {
                            // console.log(data.html);
                            if (data.html == 'true') {
                                //ya paso
                                Swal.fire(
                                    'Ruc registrado correctamente',
                                    '',
                                    'success'
                                );
                                $("#cliente_id").trigger("change");
                                $("#modal-add-ruc").modal("hide");
                            } else if (data.html == 'false') {
                                Swal.fire(
                                    'Se actualizo razon social',
                                    '',
                                    'success'
                                );
                                $("#cliente_id").trigger("change");
                                $("#modal-add-ruc").modal("hide");
                                //no paso
                            }

                        });

                    } else if (sese[0] == '0') {
                        //
                        if (sese[1] == 'A') {
                            Swal.fire(
                                'El ruc ya se encuentra relacionado con el asesor ' + sese[2],
                                '',
                                'warning'
                            );
                        } else if (sese[1] == "C") {
                            Swal.fire(
                                'El ruc ya se encuentra relacionado con el cliente ' + sese[2],
                                '',
                                'warning'
                            );
                        }

                    }

                });


            });

            $("form").keypress(function (e) {
                if (e.which == 13) {
                    return false;
                }
            });

        });
    </script>

    <script>
        $(document).ready(function () {

            /************************************
             * CARGAMOS LOS ASESORES EN EL COMBO
             * *********************************/
            $.ajax({
                type: 'POST',
                url: "{{ route('asesorcombo') }}",
            }).done(function (data) {
                $("#user_id").html('');
                $("#user_id").html(data.html);
                $("#user_id").selectpicker("refresh").trigger("change");
            });

        });


    </script>
@stop
