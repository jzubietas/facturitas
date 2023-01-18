@extends('adminlte::page')

@section('title', 'Lista de pedidos por enviar')

@section('content_header')
    <h1>Lista de pedidos por enviar - ENVIOS
        {{-- <div class="float-right btn-group dropleft">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Exportar
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('pedidosporenviarExcel') }}" class="dropdown-item"><img src="{{ asset('imagenes/icon-excel.png') }}"> EXCEL</a>
          </div>
        </div> --}}
        {{-- @can('clientes.exportar') --}}
        <div class="float-right btn-group dropleft">

            <?php if (Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Logística'){ ?>
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Exportar
            </button>
            <?php } ?>

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
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Fecha de Envio</th>
                    <th scope="col">Razón social</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Referencia</th>
                    <th scope="col">Rotulo</th>
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
    <link rel="stylesheet" href="{{asset('/css/admin_custom.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush

@section('js')
    {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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


            $('#tablaPrincipal').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                searching: true,
                "order": [[0, "desc"]],
                ajax: "{{ route('envios.seguimientoprovinciatabla') }}",
                createdRow: function (row, data, dataIndex) {
                    //console.log(row);
                },
                rowCallback: function (row, data, index) {
                    renderEventButtonAction(row, data, index)
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
                        data: 'direccion',
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
                        data: 'referencia',
                        name: 'referencia',
                        sWidth: '10%'
                    },
                    {
                        data: 'condicion_envio',
                        name: 'condicion_envio',
                        render: function (data, type, row, meta) {
                            var badge_estado = ''
                            badge_estado += '<span class="badge badge-success" style="background-color: ' + row.condicion_envio_color + '!important;">' + row.condicion_envio + '</span>';
                            return badge_estado;
                        }

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
                $.dialog({
                    columnClass: 'large',
                    title: 'Cambiar estado',
                    content: `
<div class="p-3">
<div class="row">
    <div class="col-md-12">
        <span>Adjuntar estado de olva</span>
        <div id="attachmentfiles" class="border border-dark rounded d-flex justify-content-center align-items-center mb-4" style="height: 50px">
            <i class="fa fa-upload"></i>
            <span></span>
        </div>
    </div>
    <div class="col-md-12 result_picture">
        <img src="" style="max-height: 350px">
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>Seguimiento de envio</label>
            <select class="form-control" >
                <option>RECEPCIONADO</option>
                <option>EN CAMINO</option>
                <option>EN TIENDA/AGENTE</option>
                <option>ENTREGADO</option>
                <option>NO ENTREGADO</option>
            </select>
        </div>
    </div>
</div>
</div>
<div class="jconfirm-buttons">
<button type="button" class="btn-ok btn btn-success" disabled="">Cambiar estado</button>
<button type="button" class="btn-cancel btn btn-default">cancelar</button>
</div>
`,
                    onContentReady: function () {
                        var self = this;
                        this.$content.find('.result_picture').hide()
                        this.$content.find('#attachmentfiles').click(function () {
                            var file = document.createElement('input');
                            file.type = 'file';
                            file.click()
                            file.addEventListener('change', function (e) {
                                if (file.files.length > 0) {
                                    self.$content.find('.result_picture').css('display', 'block')
                                    console.log(URL.createObjectURL(file.files[0]))
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
                            }
                        }

                        this.$content.find('button.btn-ok').click(function () {

                        })
                    },
                    onDestroy: function () {
                        window.document.onpaste = null
                    },
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
