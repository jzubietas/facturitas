@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
    <h1>Lista de pedidos sin direccion - ENVIOS

        <div class="float-right btn-group dropleft">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <div class="dropdown-menu">
                <a href="" data-target="#modal-exportar" data-toggle="modal" class="dropdown-item" target="blank_"><img
                        src="{{ asset('imagenes/icon-excel.png') }}"> Excel</a>
            </div>
        </div>
        @include('pedidos.modal.exportar', ['title' => 'Exportar pedidos POR ENVIAR', 'key' => '1'])
        {{-- @endcan --}}
    </h1>
    @if($superasesor > 0)
        <br>
        <div class="bg-4">
            <h1 class="t-stroke t-shadow-halftone2" style="text-align: center">
                asesores con privilegios superiores: {{ $superasesor }}
            </h1>
        </div>
    @endif
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- <table cellspacing="5" cellpadding="5">
              <tbody>
                <tr>
                  <td>Destino:</td>
                  <td>
                    <select name="destino" id="destino" class="form-control">
                      <option value="LIMA">LIMA</option>
                      <option value="PROVINCIA">PROVINCIA</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table><br> --}}
            <table id="tablaPrincipal" style="width:100%;" class="table table-striped">
                <thead>
                <tr>

                    <th scope="col">Código</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Fecha de Recepcion Courier</th>
                    <th scope="col">Razón social</th>
                    {{--
                    <th scope="col">Destino</th>
                    <th scope="col">Dirección de envío</th>
                    <th scope="col">Referencia</th>
                    --}}
                    <th scope="col">Dias</th>
                    <th scope="col">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('envios.modal.enviarid')
            @include('pedidos.modal.recibirid')
            {{--@include('sobres.modal.direccionid')--}}
            @include('pedidos.modal.verdireccionid')
            @include('pedidos.modal.editdireccionid')
            @include('pedidos.modal.destinoid')
            @include('envios.modal.distribuir')
            @include('operaciones.modal.confirmacion')
        </div>
    </div>

@stop

@section('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <style>
        img:hover {
            transform: scale(1.03)
        }

        .bg-4 {
            background: linear-gradient(to right, rgb(240, 152, 25), rgb(237, 222, 93));
        }

        .t-stroke {
            color: transparent;
            -moz-text-stroke-width: 2px;
            -webkit-text-stroke-width: 2px;
            -moz-text-stroke-color: #000000;
            -webkit-text-stroke-color: #ffffff;
        }

        .t-shadow-halftone2 {
            position: relative;
        }

        .t-shadow-halftone2::after {
            content: "AWESOME TEXT";
            font-size: 10rem;
            letter-spacing: 0px;
            background-size: 100%;
            -webkit-text-fill-color: transparent;
            -moz-text-fill-color: transparent;
            -webkit-background-clip: text;
            -moz-background-clip: text;
            -moz-text-stroke-width: 0;
            -webkit-text-stroke-width: 0;
            position: absolute;
            text-align: center;
            left: 0px;
            right: 0;
            top: 0px;
            z-index: -1;
            background-color: #ff4c00;
            transition: all 0.5s ease;
            text-shadow: 10px 2px #6ac7c2;
        }
    </style>
@stop

@section('js')
    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>


        $(document).ready(function () {

            $('#modal-confirmacion').on('show.bs.modal', function (event) {


                var button = $(event.relatedTarget)
                var idunico = button.data('ide')
                var codigos = button.data('codigos')

                $('.titulo-confirmacion').html("Entregado sin envio");
                $('#msj-modal').html("Esta seguro de confirmar la entrega sin envio del pedido <b>" + codigos + "</b>");

                $('.titulo-confirmacion').html("Enviar sobre a Motorizado");

                $("#hiddenCodigo").val(idunico)
                $("#modal-confirmacion .textcode").html(codigos);
            });

            $(document).on("submit", "#formulario_confirmacion", function (evento) {
                evento.preventDefault();

                var fd2 = new FormData();

                fd2.append('hiddenCodigo', $('#hiddenCodigo').val());

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('operaciones.confirmarentregasinenvio') }}",
                    success: function (data) {
                        $("#modal-confirmacion").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();
                    }
                });
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on("submit", "#formulario", function (evento) {
                evento.preventDefault();
                var fd = new FormData();
            });

            $('#modal-enviar').on('show.bs.modal', function (event) {
                //cuando abre el form de anular pedido
                var button = $(event.relatedTarget)
                var idunico = button.data('enviar')//pedido
                var destino = button.data('destino')//pedido
                var dfecha = button.data('fechaenvio')//pedido

                var newOption = $('<option value="REGISTRADO">REGISTRADO</option>');
                var newOption2 = $('<option value="NO ENTREGADO">NO ENTREGADO</option>');
                var newOption3 = $('<option value="ENTREGADO">ENTREGADO</option>');

                var newOption4 = $('<option value="EN CAMINO">EN CAMINO</option>');
                var newOption5 = $('<option value="EN TIENDA/AGENTE">EN TIENDA/AGENTE</option>');
                console.log(dfecha)
                $('#condicion').empty().append(newOption3);
                $("#fecha_envio_doc_fis").val(dfecha);

                // if (destino=='LIMA')
                // $('#condicion').empty().append(newOption).append(newOption2).append(newOption3);
                //  else
                //  $('#condicion').empty().append(newOption).append(newOption2).append(newOption4).append(newOption5).append(newOption3);

                console.log(destino);

                $("#hiddenEnviar").val(idunico)
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }
                $("#modal-enviar .textcode").html(idunico);

            });


            $(document).on("change", "#foto1", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture1").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });

            $(document).on("change", "#foto2", function (event) {
                console.log("cambe image")
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    //$("#picture").attr("src",event.target.result);
                    document.getElementById("picture2").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);

            });


            $('#modal-recibir').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('recibir')//pedido
                $("#hiddenRecibir").val(idunico)
                if (idunico < 10) {
                    idunico = 'PED000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PED00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PED0' + idunico;
                } else {
                    idunico = 'PED' + idunico;
                }
                $("#modal-recibir .textcode").html(idunico);


            });

            $(document).on("submit", "#formularioenviar", function (evento) {
                evento.preventDefault();
                console.log("form enviarid")
                //validacion

                var fd2 = new FormData();
                let files = $('input[name="pimagen')
                var fileitem = $("#DPitem").val();

                fd2.append('hiddenEnviar', $('#hiddenEnviar').val());
                fd2.append('fecha_envio_doc_fis', $('#fecha_envio_doc_fis').val());
                fd2.append('fecha_recepcion', $('#fecha_recepcion').val());
                fd2.append('foto1', $('input[type=file][id="foto1"]')[0].files[0]);
                fd2.append('foto2', $('input[type=file][id="foto2"]')[0].files[0]);
                fd2.append('condicion', $('#condicion').val());

                $.ajax({
                    data: fd2,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('envios.enviarid') }}",
                    success: function (data) {
                        $("#modal-enviar").modal("hide");
                        $('#tablaPrincipal').DataTable().ajax.reload();

                    }
                });

            });

            $(document).on("submit", "#formulariorecibir", function (evento) {
                evento.preventDefault();
            });

            $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[3, "asc"]],
                ajax: "{{ route('envios.sindirecciontabla') }}",
                createdRow: function (row, data, dataIndex) {
                },
                rowCallback: function (row, data, index) {
                    if (data.destino2 == 'PROVINCIA') {
                        $('td', row).css('color', 'red')
                    }
                    $("[data-toggle=jqconfirm]", row).click(function () {
                        const action = $(this).data('target')
                        $.dialog({
                            title: '<h3 class="font-weight-bold">Marcar como enviado al cliente</h3>',
                            type: 'green',
                            columnClass: 'xlarge',
                            content: `<div>
    <form enctype="multipart/form-data" class="card">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5>Sobre de pedido: <b>${data.codigo}</b></h5>
                </div>
            </div>
             <div class="row mt-2">
                                <div class="col-12 col-md-4">
                                    <div class="input-group w-80">
                                        <div class="custom-file w-90">
                                            <input type="file" class="custom-file-input form-control-file" id="adjunto1" name="adjunto1" lang="es">
                                            <label class="custom-file-label" for="adjunto1">Foto de los sobres</label>
                                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" id="trash_adjunto1" type="button"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="input-group w-80">
                                        <div class="custom-file w-90">
                                            <input type="file" class="custom-file-input form-control-file" id="adjunto2" name="adjunto2" lang="es">
                                            <label class="custom-file-label" for="adjunto2">Foto del domicilio</label>
                                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" id="trash_adjunto2" type="button"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="input-group w-80">
                                        <div class="custom-file w-90">
                                            <input type="file" class="custom-file-input form-control-file" id="adjunto3" name="adjunto3" lang="es">
                                            <label class="custom-file-label" for="adjunto3">Foto de quien recibe</label>
                                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" id="trash_adjunto3" type="button"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 mt-12">
                                    <div class="form-group">
                                        <div class="image-wrapper">
                                            <img id="picture1"
                                            src="{{ asset('imagenes/motorizado_preview/sobres.png') }}"
                                            data-src="{{ asset('imagenes/motorizado_preview/sobres.png') }}"
                                alt="Imagen del pago" class="img-fluid" style="display: block;height: 300px;width: auto;">
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-12">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture2"
                            src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                            data-src="{{ asset('imagenes/motorizado_preview/domicilio.png') }}"
                                 alt="Imagen del pago" class="img-fluid" style="display: block;height: 300px;width: auto;">
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-12">
                    <div class="form-group">
                        <div class="image-wrapper">
                            <img id="picture3"
                            src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                            data-src="{{ asset('imagenes/motorizado_preview/recibe_sobre.png') }}"
                                 alt="Imagen del pago" class="img-fluid" style="display: block;height: 300px;width: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
        </div>
    </form>
</div>`,
                            onContentReady: function () {
                                const self = this
                                self.$content.find("#adjunto1").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture1").show();
                                        self.$content.find("#picture1").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#adjunto2").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture2").show();
                                        self.$content.find("#picture2").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#adjunto3").change(function (e) {
                                    const [file] = e.target.files
                                    if (file) {
                                        self.$content.find("#picture3").show();
                                        self.$content.find("#picture3").attr('src', URL.createObjectURL(file))
                                    }
                                })
                                self.$content.find("#trash_adjunto1").click(function (e) {
                                    self.$content.find("#picture1").attr('src',self.$content.find("#picture1").data('src'))
                                    self.$content.find("#adjunto1").val(null)
                                })
                                self.$content.find("#trash_adjunto2").click(function (e) {
                                    self.$content.find("#picture2").attr('src', self.$content.find("#picture2").data('src'))
                                    self.$content.find("#adjunto2").val(null)
                                })
                                self.$content.find("#trash_adjunto3").click(function (e) {
                                    self.$content.find("#picture3").attr('src', self.$content.find("#picture2").data('src'))
                                    self.$content.find("#adjunto3").val(null)
                                })

                                self.$content.find("form").on('submit', function (e) {
                                    e.preventDefault()
                                    /*if (!e.target.fecha_recepcion.value) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Ingresa la fecha de Entrega</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }*/
                                    if (e.target.adjunto1.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la Foto de los sobres</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto2.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la Foto del domicilio</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    if (e.target.adjunto3.files.length === 0) {
                                        $.confirm({
                                            title: '¡Advertencia!',
                                            content: '<b>Adjunta la Foto de quien recibe</b>',
                                            type: 'orange'
                                        })
                                        return false;
                                    }
                                    var fd2 = new FormData(e.target);
                                    fd2.set('pedido_id', data.id)
                                    self.showLoading(true)
                                    $.ajax({
                                        data: fd2,
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        url: action
                                    }).done(function () {
                                        self.close()
                                        $('#tablaPrincipal').DataTable().draw(false);
                                    }).always(function () {
                                        self.hideLoading(true)
                                    });
                                })
                            },
                        })
                    })
                },
                columns: [
                    {
                        data: 'codigo',
                        name: 'codigo',
                    },
                    {data: 'identificador', name: 'u.identificador',},
                    {
                        data: 'celular',
                        name: 'c.celular',
                        render: function (data, type, row, meta) {
                            return row.celular + '-' + row.icelular + '<br>' + row.nombres
                        },
                    },
                    {
                        data: 'fecha_recepcion_courier',
                        name: 'pedidos.fecha_recepcion_courier',
                        //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' )
                    },
                    {
                        data: 'empresas',
                        name: 'dp.nombre_empresa',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return 'SIN EMPRESA';
                            } else {
                                return data;
                            }
                        }
                    },
                    /*
                    {data: 'env_destino', name: 'env_destino', },
                    {
                      data:'env_direccion',
                      name:'env_direccion',
                      render: function ( data, type, row, meta ) {
                        //console.log(data);
                        datas='';
                        if(data!=null)
                        {
                          return data;

                        }else{
                          return '<span class="badge badge-info">REGISTRE DIRECCION</span>';
                        }
                        return '';
                      },
                    },
                    {
                      data: 'env_referencia',
                      name: 'env_referencia',
                      sWidth:'10%',
                      render: function ( data, type, row, meta ) {
                        var datal="";
                        if(data!=null)
                        {
                          return data;

                        }else{
                          return ''
                        }

                      }
                    },
                      */
                    {data: 'dias', name: 'fecha_recepcion_courier',},
                    {data: 'condicion_envio', name: 'condicion_envio',},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '10%',
                    },
                ],
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


        });
    </script>

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Pedido {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>

        //VALIDAR CAMPO CELULAR
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }

        //VALIDAR ANTES DE ENVIAR
        /*document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("formulario").addEventListener('submit', validarFormulario);
        });*/

        function validarFormulario(evento) {
            evento.preventDefault();
            var condicion = document.getElementById('condicion').value;
            var foto1 = document.getElementById('foto1').value;
            var pfoto1 = document.getElementById('pfoto1').value;
            var foto2 = document.getElementById('foto2').value;
            var pfoto2 = document.getElementById('pfoto2').value;

            if (condicion == 3) {
                if (foto1 == '' && pfoto1 == '') {
                    Swal.fire(
                        'Error',
                        'Para dar por ENTREGADO debe registrar la foto 1',
                        'warning'
                    )
                } else if (foto2 == '' && pfoto2 == '') {
                    Swal.fire(
                        'Error',
                        'Para dar por ENTREGADO debe registrar la foto 2',
                        'warning'
                    )
                } else {
                    this.submit();
                }
            } else {
                this.submit();
            }
        }
    </script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        /* Custom filtering function which will search data in column four between two values */
        $(document).ready(function () {


            $("#destino", this).on('keyup change', function () {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(8)
                        .search(this.value)
                        .draw();
                }
            });

        });
    </script>

@stop
