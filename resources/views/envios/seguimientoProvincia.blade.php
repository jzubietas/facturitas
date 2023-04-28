@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
    <h1>Lista de SEGUIMIENTO A PROVINCIA</h1>
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

    <div class="card" style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
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
            <table id="tablaCourierSeguimientoProvincia" style="width:100%;" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Fecha de Envio</th>
                    <th scope="col">Razón social</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Tracking</th>
                    <th scope="col">Numero de registro</th>
                    <th scope="col">Estado de envio</th><!--ENTREGADO - RECIBIDO-->
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@stop

@push('css')
    {{--<link rel="stylesheet" href="{{asset('/css/admin_custom.css')}}">--}}
@endpush

@section('js')
    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#tablaCourierSeguimientoProvincia').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                /*  "order": [[0, "desc"]],*/
                ajax: "{{ route('envios.seguimientoprovinciatabla') }}",
                createdRow: function (row, data, dataIndex) {
                    //console.log(row);
                },
                rowCallback: function (row, data, index) {
                    renderEventButtonAction(row, data, index)
                    if (data.courier_failed_sync_at != null || data.courier_estado==null) {
                        $('td', row).css('background', 'var(--red,red)')
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            if (row.id < 10) {
                                return 'ENV000' + row.id;
                            } else if (row.id < 100) {
                                return 'ENV00' + row.id;
                            } else if (row.id < 1000) {
                                return 'ENV0' + row.id;
                            } else {
                                return 'ENV' + row.id;
                            }
                        }
                    },
                    {
                        data: 'codigos',
                        name: 'codigos',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return 'SIN PEDIDOS';
                            } else {
                                var returndata = '';
                                var jsonArray = data.split(",");
                                $.each(jsonArray, function (i, item) {
                                    returndata += item + '<br>';
                                });
                                return returndata;
                            }
                        },
                    },
                    {data: 'identificador', name: 'identificador',},
                    {
                        data: 'celular',
                        name: 'celular',
                        render: function (data, type, row, meta) {
                            return row.cliente_celular + ' - ' + row.cliente_nombre
                        },
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'producto',
                        name: 'producto',
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return 'SIN RUCS';
                            } else {
                                var numm = 0;
                                var returndata = '';
                                var jsonArray = data.split(",");
                                $.each(jsonArray, function (i, item) {
                                    numm++;
                                    returndata += numm + ": " + item + '<br>';

                                });
                                return returndata;
                            }
                        }
                    },
                    {
                        data: 'destino',
                        name: 'destino',
                        "visible": false,
                    },

                    {
                        data: 'direccion_format',
                        name: 'direccion',
                        render: function (data, type, row, meta) {
                            if (data != null) {
                                return data;
                            } else {
                                return '<span class="badge badge-info">REGISTRE DIRECCION</span>';
                            }
                        },
                    },
                    {
                        data: 'referencia_format',
                        name: 'referencia',
                        sWidth: '10%'
                    },
                    {
                        data: 'condicion_envio_format',
                        name: 'condicion_envio',
                    },
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


        function renderEventButtonAction(row, data, index) {
            $('[data-toggle="jqconfirm"]', row).click(function () {
                const target = $(this).data('target')
                var condiciones = [];

                if (data.condicion_envio_code == '{{\App\Models\Pedido::RECEPCIONADO_OLVA_INT}}') {
                    condiciones = [
                        {
                            label: '{{\App\Models\Pedido::EN_CAMINO_OLVA}}',
                            value: '{{\App\Models\Pedido::EN_CAMINO_OLVA_INT}}',
                        },
                        {
                            label: '{{\App\Models\Pedido::EN_TIENDA_AGENTE_OLVA}}',
                            value: '{{\App\Models\Pedido::EN_TIENDA_AGENTE_OLVA_INT}}',
                        },
                    ];
                } else if (data.condicion_envio_code == '{{\App\Models\Pedido::EN_CAMINO_OLVA_INT}}') {
                    condiciones = [
                        {
                            label: '{{\App\Models\Pedido::EN_TIENDA_AGENTE_OLVA}}',
                            value: '{{\App\Models\Pedido::EN_TIENDA_AGENTE_OLVA_INT}}',
                        },
                    ];
                } else if (data.condicion_envio_code == '{{\App\Models\Pedido::EN_TIENDA_AGENTE_OLVA_INT}}') {
                    condiciones = [
                        {
                            label: '{{\App\Models\Pedido::ENTREGADO_PROVINCIA}}',
                            value: '{{\App\Models\Pedido::ENTREGADO_PROVINCIA_INT}}',
                        },
                        {
                            label: '{{\App\Models\Pedido::NO_ENTREGADO_OLVA}}',
                            value: '{{\App\Models\Pedido::NO_ENTREGADO_OLVA_INT}}',
                        },
                    ];
                } else {
                    condiciones = [
                        {
                            label: '{{\App\Models\Pedido::RECEPCIONADO_OLVA}}',
                            value: '{{\App\Models\Pedido::RECEPCIONADO_OLVA_INT}}',
                        },
                    ]
                }

                $.dialog({
                    columnClass: 'large',
                    title: 'Cambiar estado',
                    content: `
<div class="p-3">
<div class="row">
    <div class="col-md-12">
            <div class="form-group">
                <label>Seguimiento de envio</label>
                <select class="form-control select_subcondicion_envio">
                    ${condiciones.map(function (subcond) {
                        return `<option ${data.condicion_envio_code == subcond.value ? 'selected' : ''} value="${subcond.value}">${subcond.label}</option>`
                    }).join('')}
                </select>
            </div>
    </div>
${data.condicion_envio_code == '{{\App\Models\Pedido::EN_TIENDA_AGENTE_OLVA_INT}}' ? `
<div class="col-md-12">
        <strong>Adjuntar estado de olva</strong>
        <div id="attachmentfiles" class="border border-dark rounded d-flex justify-content-center align-items-center mb-4 position-relative" style="height: 400px">
            <i class="fa fa-upload"></i>
            <div class="result_picture position-absolute" style="display: block;top: 0;left: 0;bottom: 0;right: 0;text-align: center;">
                <img src="" class="h-100">
            </div>
        </div>
        <div class="alert alert-warning">Puede copiar y pegar la imagen o hacer click en el recuadro para seleccionar un archivo</div>
</div>
` : ''}
</div>
</div>
<div class="jconfirm-buttons">
<button type="button" class="btn-ok btn btn-success">Cambiar estado</button>
<button type="button" class="btn-cancel btn btn-default">cancelar</button>
</div>
`,
                    onContentReady: function () {
                        var self = this;
                        const dataForm = {
                            direccion_grupo_id: data.id
                        }
                        this.$content.find('.result_picture').hide()
                        this.$content.find('#attachmentfiles').click(function () {
                            var file = document.createElement('input');
                            file.type = 'file';
                            file.click()
                            file.addEventListener('change', function (e) {
                                if (file.files.length > 0) {
                                    self.$content.find('.result_picture').css('display', 'block')
                                    console.log(URL.createObjectURL(file.files[0]))
                                    dataForm.file = file.files[0]
                                    self.$content.find('.result_picture>img').attr('src', URL.createObjectURL(file.files[0]))
                                }
                            })
                        });
                        window.document.onpaste = function (event) {
                            var items = (event.clipboardData || event.originalEvent.clipboardData).items;
                            console.log(items);
                            console.log((event.clipboardData || event.originalEvent.clipboardData));
                            var files = []
                            for (index in items) {
                                var item = items[index];
                                if (item.kind === 'file') {
                                    // adds the file to your dropzone instance
                                    var file = item.getAsFile()
                                    files.push(file)
                                }
                            }
                            if (files.length > 0) {
                                self.$content.find('.result_picture').css('display', 'block')
                                console.log(URL.createObjectURL(files[0]))
                                self.$content.find('.result_picture>img').attr('src', URL.createObjectURL(files[0]))
                                dataForm.file = files[0]
                            }
                        }

                        this.$content.find('button.btn-cancel').click(function () {
                            self.close()
                        })
                        this.$content.find('button.btn-ok').click(function () {
                            dataForm.condicion_envio_code = self.$content.find('select.select_subcondicion_envio').val()

                            if (dataForm.condicion_envio_code == '{{\App\Models\Pedido::ENTREGADO_PROVINCIA_INT}}' ||
                                dataForm.condicion_envio_code == '{{\App\Models\Pedido::NO_ENTREGADO_OLVA_INT}}'
                            ) {
                                if (!dataForm.file) {
                                    $.alert('Imagen requerida: Seleccione o pegue una imagen para continuar')
                                    return;
                                }
                            } else {
                                delete dataForm.file;
                            }

                            function submitEvent() {
                                self.showLoading(true)
                                var fd = new FormData();
                                Object.keys(dataForm).forEach(function (key) {
                                    if (key == 'file' && dataForm[key]) {
                                        fd.append(key, dataForm[key], dataForm[key].name);
                                    } else {
                                        fd.append(key, dataForm[key]);
                                    }
                                })

                                $.ajax({
                                    url: '{{route('envios.seguimientoprovincia.update')}}',
                                    data: fd,
                                    method: 'POST',
                                    processData: false,
                                    contentType: false,
                                })
                                    .done(function () {
                                        self.close()
                                    })
                                    .always(function () {
                                        self.hideLoading(true)
                                        $(row).parents('table').DataTable().draw(false)
                                    })
                            }

                            if (dataForm.condicion_envio_code == '{{\App\Models\Pedido::NO_ENTREGADO_OLVA_INT}}') {
                                $.confirm({
                                    theme: 'material',
                                    title: '¡Confirmación!',
                                    content: `ESTAS SEGURO QUE EL SOBRE <b>${data.codigos}</b> NO A SIDO RECIVIDO POR EL CLIENTE`,
                                    buttons: {
                                        confirmar: {
                                            btnClass: 'btn-success',
                                            action: function () {
                                                $.confirm({
                                                    theme: 'material',
                                                    title: '¡Confirmación!',
                                                    content: `YA REVISASTE SI VERDADERAMENTE EL SOBRE <b>${data.codigos}</b> NO A SIDO RECIBIDO`,
                                                    buttons: {
                                                        confirmar: {
                                                            text: 'Si, Confirmar',
                                                            btnClass: 'btn-success',
                                                            action: function () {
                                                                submitEvent()
                                                            }
                                                        },
                                                        cancelar: function () {

                                                        },
                                                    }
                                                });
                                            }
                                        },
                                        cancelar: function () {

                                        },
                                    }
                                });
                            } else if (dataForm.condicion_envio_code == '{{\App\Models\Pedido::ENTREGADO_PROVINCIA_INT}}') {
                                $.confirm({
                                    theme: 'material',
                                    title: '¡Confirmación!',
                                    content: `ESTAS SEGURO QUE EL SOBRE <b>${data.codigos}</b> A SIDO RECIVIDO POR EL CLIENTE`,
                                    buttons: {
                                        confirmar: {
                                            text: 'Si, Confirmar y finalizar',
                                            btnClass: 'btn-success',
                                            action: function () {
                                                submitEvent()
                                            }
                                        },
                                        cancelar: function () {

                                        },
                                    }
                                });
                            } else {
                                submitEvent()
                            }

                        })
                    },
                    onDestroy: function () {
                        window.document.onpaste = null
                    },
                })
            })

            $('[data-jqconfirm="edit_tracking"]', row).click(function () {
                const action = $(this).data('action');
                $.confirm({
                    theme: 'material',
                    title: 'Editar Tracking',
                    type: 'red',
                    content: `<div class="p-2">
<div class="form-group">
<label>Número de registro</label>
<input class="form-control" type="text" placeholder="000000000000" name="numregistro" value="${data.referencia}">
</div>
<div class="form-group">
<label>Ingresar Tracking</label>
<input class="form-control" type="text" placeholder="00000000-{{now()->format('y')}}" name="tracking" value="${data.direccion}" data-inputmask-regex="\\d+-\\d{2}">
</div>
</div>`,
                    buttons: {
                        actualizar: {
                            btnClass: 'btn-success',
                            action: function () {
                                const self = this

                                const data = {
                                    tracking: self.$content.find('input[name=tracking]').val(),
                                    numregistro: self.$content.find('input[name=numregistro]').val(),
                                }
                                if (data.tracking.includes('__')) {
                                    $.alert(`El tracking ingresado no tiene el formato correcto`);
                                    return false;
                                }
                                if (!data.numregistro) {
                                    $.alert(`El numero de registro ingresado no tiene el formato correcto`);
                                    return false;
                                }
                                self.showLoading(true)
                                $.post(action, data).done(function (data) {
                                    if (data.success) {
                                        self.close()
                                    } else {
                                        if (data.existencias) {
                                            $.alert(`El codigo que intentas actualizar ya se encuentra registrado en otro pedido con codigo (<b>${data.codigos.join(', ')}</b>)`);
                                        } else {
                                            $.alert(`Ocurrio un error al actualizar, revise si ha ingresado correctamente los datos`);
                                        }
                                    }
                                }).always(function () {
                                    self.hideLoading(true)
                                    $('#tablaCourierSeguimientoProvincia').DataTable().draw(false)
                                })
                                return false
                            }
                        },
                        cancelar: {}
                    },
                    onContentReady: function () {
                        const self = this
                        self.$content.find('input[name=tracking]').inputmask();
                        const value = data.direccion
                        const year = parseInt(value.substring(value.length - 2, value.length))
                        const currentyear = parseInt((new Date()).getFullYear().toString().substring(2, 4))
                        if (!isNaN(year) && year > 19 && year <= currentyear) {
                            self.$content.find('input[name=tracking]').val(value.substring(value.length - 2, 0) + '-' + year)
                        }
                    }

                })
            })
        }

        /*

        document.onpaste = function(event){
      var items = (event.clipboardData || event.originalEvent.clipboardData).items;console.log(items);
      for (index in items) {
        var item = items[index];
        if (item.kind === 'file') {
          // adds the file to your dropzone instance
          console.log(item.getAsFile())
        }
      }
    }
         */
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
@stop
