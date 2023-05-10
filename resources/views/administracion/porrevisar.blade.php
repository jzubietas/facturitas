@extends('adminlte::page')

@section('title', 'Lista de Pagos')

@section('content_header')
    <h1>Lista de Voucher POR REVISAR
        @can('pagos.create')
            {{--<a href="{{ route('pagos.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>--}}
        @endcan

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
        @include('pagos.modals.exportar', ['title' => 'Exportar Lista de pagos', 'key' => '6'])
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

    <div class="card" style="overflow: hidden !important;">
        <div class="card-body" style="overflow-x: scroll !important;">
            <div class="form-group col-lg-6">

                <select name="asesores_pago" class="border form-control selectpicker border-secondary"
                        id="asesores_pago" data-live-search="true">
                    <option value="">---- SELECCIONE ASESOR ----</option>
                </select>
            </div>

            <table cellspacing="5" cellpadding="5" class="table-responsive">
                <tbody>
                <tr>
                    <td>Fecha Minima:</td>
                    <td><input type="text" value={{ $dateMin }} id="min" name="min" class="form-control"></td>
                    <td></td>
                    <td>Fecha Máxima:</td>
                    <td><input type="text" value={{ $dateMax }} id="max" name="max" class="form-control"></td>
                </tr>
                </tbody>
            </table>
            <br>
            <table id="tablaPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="align-middle">COD.</th>
                    <th scope="col" class="align-middle">COD.</th>
                    <th scope="col" class="align-middle">COD2</th>
                    <th scope="col" class="align-middle">Cliente</th>
                    <th scope="col" class="align-middle">Codigo pedido</th>
                    <th scope="col" class="align-middle">Fecha Voucher</th>
                    <th scope="col" class="align-middle">Asesor</th>
                    <th scope="col" class="align-middle">Observacion</th>
                    {{--<th scope="col">Total cobro</th>--}}
                    <th scope="col" class="align-middle">Total pagado</th>
                    <th scope="col" class="align-middle">Estado</th>
                    <th scope="col" class="align-middle">Subcondicion</th>
                    <th scope="col" class="align-middle">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('pagos.modals.modalDeleteId')
        </div>
    </div>

@stop

@push('css')
    {{-- <link rel="stylesheet" href="../css/admin_custom.css">--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .yellow {
            color: #fcd00e !important;
        }

        .red {
            background-color: red !important;
        }

        .white {
            background-color: white !important;
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

        #tablaPrincipal {
            width: 100% !important;
        }

        #tablaPrincipal td {
            text-align: start !important;
            vertical-align: center !important;
        }

        #tablaPrincipal td:nth-child(9) {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            grid-gap: 5px;
        }

    </style>
@endpush

@section('js')

    <!--<script src="{{ asset('js/datatables.js') }}"></script>-->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

    <script src="https://rawgit.com/digitalBush/jquery.maskedinput/master/dist/jquery.maskedinput.min.js"></script>

    <script>
        function clickformdelete() {
            console.log("action delete action")
            var formData = $("#formdelete").serialize();
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('pagodeleteRequest.post') }}",
                data: formData,
            }).done(function (data) {
                $("#modal-delete").modal("hide");
                //resetearcamposdelete();
                $('#tablaPrincipal').DataTable().ajax.reload();
            });
        }
    </script>



    <script>
        $(document).ready(function () {

            //$.fn.dataTable.moment( 'DD/MM/YYYY' );


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //$('#asesorespago').change(function(){

            $.ajax({
                url: "{{ route('asesorespago') }}",
                method: 'GET',
                success: function (data) {
                    console.log(data.html);
                    $('#asesores_pago').html(data.html);
                    $('#asesores_pago').selectpicker('refresh');

                    if (localStorage.getItem('asesor')) {
                        $('#asesores_pago').val(localStorage.getItem('asesor'));
                        $('#asesores_pago').selectpicker("refresh").trigger("change");
                    }

                }
            });

            $(document).on("change", "#asesores_pago", function () {
                localStorage.setItem('asesor', $(this).val());
                $('#tablaPrincipal').DataTable().ajax.reload();
            });

            /*$(document).on("change","#min",function(){
              $('#tablaPrincipal').DataTable().ajax.reload();
            });
            $(document).on("change","#max",function(){
              $('#tablaPrincipal').DataTable().ajax.reload();
            });*/

            //});

            //para opcion eliminar  pagos
            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('delete')
                $("#hiddenId").val(idunico);
                if (idunico < 10) {
                    idunico = 'PAG000' + idunico;
                } else if (idunico < 100) {
                    idunico = 'PAG00' + idunico;
                } else if (idunico < 1000) {
                    idunico = 'PAG0' + idunico;
                } else {
                    idunico = 'PAG' + idunico;
                }
                $(".textcode").html(idunico);
            });

            $(document).on("submit", "#formdelete", function (evento) {
                evento.preventDefault();
                console.log("validar delete");
                clickformdelete();
            })

            //$.fn.dataTable.ext


            //administracion.porrevisartabla
            $('#tablaPrincipal').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                stateSave:true,
                "order": [[0, "asc"]],
                ajax: {
                    url: "{{ route('administracion.porrevisartabla') }}",
                    data: function (d) {
                        d.asesores = $("#asesores_pago").val();
                        d.min = $("#min").val();
                        d.max = $("#max").val();
                        // d.custom = $('#myInput').val();
                        // etc
                    },
                },
                rowCallback: function (row, data, index) {
                    console.log(data);
                    if (data.subcondicion != null && data.subcondicion == 'COURIER PERDONADO') {
                        $('td', row).css('color', '#cf0a0a');
                    } else if (data.subcondicion != null && data.subcondicion == 'DEUDA PERDONADA') {
                        $('td', row).css('color', '#cf0a0a');
                    }
                },
                initComplete: function (settings, json) {

                },
                columns: [
                    {
                        data: 'fecha_timestamp',
                        name: 'fecha_timestamp',
                        "visible": false
                    },
                    {
                        data: 'id2',
                        name: 'id2',
                    },
                    {
                        data: 'id',
                        name: 'id', "visible": false
                    },
                    {//cliente
                        data: 'cliente',
                        name: 'cliente',
                        render: function (data, type, row, meta) {
                            return data;

                        },
                    },
                    {
                        data: 'codigos'
                        , name: 'codigos'
                        , render: function (data, type, row, meta) {
                            /*var jsonArray = JSON.parse(JSON.stringify(data));*/
                            var returndata = '';
                            if (data == null) {
                                return "SIN PEDIDOS";
                            } else {
                                var jsonArray = data.split(",");
                                $.each(jsonArray, function (i, item) {
                                    returndata += item + '<br>';
                                });
                            }

                            return returndata;
                            //return data;
                        }
                    },
                    {
                        data: 'fecha',
                        name: 'fecha2',
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },////asesor
                    {data: 'users', name: 'users'},////asesor
                    {data: 'observacion', name: 'observacion'},//observacion
                    //{ data: 'total_deuda', name: 'total_deuda'},//total_deuda
                    {data: 'total_pago', name: 'total_pago'},//total_pago
                    {
                        data: 'condicion',
                        name: 'condicion',
                        render: function (data, type, row, meta) {
                            if (row.subcondicion != null) {
                                return '<span class="badge badge-dark">' + row.subcondicion + '</span>' + data;
                            } else {
                                return data;
                            }

                        }
                    },//estado
                    {
                        data: 'subcondicion',
                        name: 'subcondicion', "visible": false,
                        render: function (data, type, row, meta) {
                            return data;
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false, sWidth: '20%'},
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

            $(document).on("keypress", '#tablaPrincipal_filter label input', function () {
                console.log("aaaaa")

                localStorage.setItem("search_tabla", $(this).val());
                console.log("search_tabla es " + localStorage.getItem("search_tabla"));

            });

            $('#tablaPrincipal_filter label input').on('paste', function (e) {
                var pasteData = e.originalEvent.clipboardData.getData('text')
                localStorage.setItem("search_tabla", pasteData);
            });


        });
    </script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>--}}
    {{--<script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/datetime-moment.js"></script>--}}



    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        $(document).ready(function () {

            //$.fn.dataTable.moment( 'DD/MM/YYYY' );

            $("#min").datepicker({
                onSelect: function () {
                    $('#tablaPrincipal').DataTable().ajax.reload();
                    console.log("minimo " + $(this).val());
                    //localStorage.setItem('dateMin', $(this).datepicker('getDate') );
                    //localStorage.setItem('dateMin', $(this).val() );
                },
                onFocus: function () {
                    var me = $("#date");
                    me.mask('99/99/9999');
                }
                , changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy",
            });

            //});

            $("#max").datepicker({
                onSelect: function () {
                    $('#tablaPrincipal').DataTable().ajax.reload();
                    console.log("maximo " + $(this).val());
                    //console.log("maximo "+$(this).datepicker('getDate'));
                    //localStorage.setItem('dateMax', $(this).datepicker('getDate')  );
                    //localStorage.setItem('dateMax', $(this).val() );
                }, changeMonth: true, changeYear: true, dateFormat: "dd/mm/yy"
            });
        });
    </script>

    <script>

        /*if (localStorage.getItem('dateMin') )
        {
          $( "#min" ).val(localStorage.getItem('dateMin')).trigger("change");
        }else{
          localStorage.setItem('dateMin', "{{$dateMin}}" );
    }
    if (localStorage.getItem('dateMax') )
    {
      $( "#max" ).val(localStorage.getItem('dateMax')).trigger("change");
    }else{
      localStorage.setItem('dateMax', "{{$dateMax}}" );
    }*/

        //if( localstorage.getItem() )


        //localStorage.setItem('dateMax', "{{$dateMax}}" );
        //console.log(localStorage.getItem('dateMin'));
        //console.log(localStorage.getItem('dateMax'));
    </script>

    @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'actualizado')
        <script>
            Swal.fire(
                'Pago {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    @if (session('info2') == 'error')
        <script>
            Swal.fire(
                'Pago no se compleo',
                '',
                'success'
            )
        </script>
    @endif

@stop
