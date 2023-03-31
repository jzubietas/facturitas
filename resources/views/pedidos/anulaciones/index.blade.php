{{--pedidos.recojo--}}
@extends('adminlte::page')

@section('title', 'Anulaciones')

@section('content_header')
    <div class="row p-0">
        <div class="form-group col-md-3">
            <h3>Bandeja de Anulaciones</h3>
        </div>
        <div class="form-group col-md-6">
            @if(Auth::user()->rol == \App\Models\User::ROL_ADMIN || Auth::user()->rol == \App\Models\User::ROL_ENCARGADO || Auth::user()->rol == \App\Models\User::ROL_ASESOR || Auth::user()->rol == \App\Models\User::ROL_COBRANZAS || Auth::user()->rol == \App\Models\User::ROL_ASESOR_ADMINISTRATIVO  )
                <a class="btn btn-danger btn-sm m-0" href="#" data-target="#modal-agregar-anulacion" data-toggle="modal">
                    <b class="text-white font-weight-bold d-flex align-items-center justify-content-center">
                        <i class="fa fa-ban mr-1" aria-hidden="true"></i><p class="m-0 text-card-navbar">  Solicitar Anulacion</p>
                    </b>
                </a>
            @endif
        </div>
    </div>
@stop

@section('content')

    @include('modal.AgegarAnulacion.modalAgregarAnulacion')
    @include('pedidos.anulaciones.modal.confirmaAnulacion')
    @include('pedidos.anulaciones.modal.verMotivoAnulacion')
    @include('pedidos.anulaciones.modal.verSustentoRechazoEnc')
    <div class="card p-0" style="overflow: hidden !important;">

        <div class="tab-content" id="myTabContent" style="overflow-x: scroll !important;">

            <div class="tab-pane fade show active" id="enmotorizado" role="tabpanel" aria-labelledby="enmotorizado-tab">
                <table id="tblListadoAnulaciones" class="table table-striped">{{-- display nowrap  --}}
                    <thead>
                    <tr>
                        <th></th>
                        <th scope="col" class="align-middle">Tipo</th>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Cliente</th>
                        <th scope="col" class="align-middle">Razón social</th>
                        <th scope="col" class="align-middle">Motivo</th>
                        <th scope="col" class="align-middle">Total</th>
                        <th scope="col" class="align-middle">F. Anulacion</th>
                        <th scope="col" class="align-middle">Eliminar (S/)</th>
                        <th scope="col" class="align-middle">Accion</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                @include('operaciones.modal.confirmarRecepcionRecojo')
            </div>

        </div>


    </div>

@stop

@push('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    <style>
        .yellow_color_table {
            background-color: #ffd60a !important;
        }

        .blue_color_table {
            background-color: #3A98B9 !important;
        }
    </style>
    @include('partials.css.time_line_css')
@endpush

@section('js')

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

    <script>


        let tblListadoAnulaciones = null;
        let dataForm_agregaranulacion_f = {};
        let dataForm_agregaranulacion_pc = {};

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function renderButtomsDataTable(row, data) {
                if (data.destino == 'PROVINCIA') {
                    $('td', row).css('color', '#20c997')
                }
                if (data.estado == 0) {
                    $('td', row).css('color', 'red')
                }

            }

            tblListadoAnulaciones = $('#tblListadoAnulaciones').DataTable({
                dom: 'Blfrtip',
                processing: true,
                serverSide: true,
                searching: true,
                //stateSave: true,
                order: [[7, "desc"]],
                ajax: "{{ route('pedidosanulacionestabla') }}",
                createdRow: function (row, data, dataIndex) {
                    if (data.itipoanulacion=='C') {
                        $('td', row).css('background', '#FFAFB0').css('font-weight', 'bold');
                    }else if (data.itipoanulacion=='F'){
                        $('td', row).css('background', '#E1E0A8').css('font-weight', 'bold');
                    }else if (data.itipoanulacion=='Q'){
                        $('td', row).css('background', '#81B094').css('font-weight', 'bold');
                    }
                },
                rowCallback: function (row, data, index) {

                },
                initComplete: function (settings, json) {
                },
                columns: [
                    {
                        class: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        "searchable": false
                    },
                    {
                        data: 'tipoanulacion',
                        name: 'tipoanulacion',
                    },
                    {data: 'codigos', name: 'codigos',},
                    {
                        data: 'celulares',
                        name: 'celulares',
                        render: function (data, type, row, meta) {
                            if (row.icelulares != null) {
                                return row.celulares + '-' + row.icelulares + ' - ' + row.nombres;
                            } else {
                                return row.celulares + ' - ' + row.nombres;
                            }

                        },
                    },
                    {data: 'empresas', name: 'empresas',},
                    {data: 'motivo', name: 'motivo',},
                    {data: 'cantidad', name: 'cantidad',
                        render: function (data, type, row, meta) {
                        var montotal=row.total;
                            if (row.itipoanulacion == "Q") {
                                return montotal.toLocaleString() ;
                            } else {
                                montotal= row.cantidad;
                                return montotal.toLocaleString() ;
                            }

                        },
                    },
                    {
                        data: 'fechacreaanula',
                        name: 'fechacreaanula',
                    },
                    {
                        data: 'total_anular',
                        name: 'total_anular',
                        render: $.fn.dataTable.render.number(',', '.', 2, '')
                    },
                    {data: 'action', name: 'action',sWidth: '15%',},
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



            $('#modal-envio-recojo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var grupopedido = button.data('grupopedido')
                var codigos = button.data('codigos')

                $(".textcode").html(codigos);
                $("#hiddenIdGrupoPedido").val(grupopedido);
            });

            $(document).on("submit", "#modal-envio-recojo", function (evento) {
                evento.preventDefault();

                var data = new FormData();
                data.append('hiddenIdGrupoPedido', $("#hiddenIdGrupoPedido").val());

                $.ajax({
                    data: data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('envios.confirmar-recepcion-recojo') }}",
                    success: function (data) {
                        $("#modal-envio-recojo .textcode").text('');
                        $("#modal-envio-recojo").modal("hide");
                        Swal.fire('Mensaje', data.mensaje, 'success')
                        $('#tblListadoAnulaciones').DataTable().ajax.reload();
                    }
                });
            });

            tblListadoAnulaciones.on('responsive-display', function (e, datatable, row, showHide, update) {
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });

            $('#modal_recojomotorizado').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                $("#input_recojomotorizado").val(button.data('direccion_grupo'));
            });


            /*MODAL ANULACION PEDIDOS COMPLETOS*/
            $(document).on("keyup", '#codigoCodigoPc', function () {
                let tamanio = $.trim($(this).val()).length;
                var txtValorPc=$(this).val();
                if (tamanio > 8 && tamanio < 16) {
                    $.ajax
                    ({

                        type: "POST",
                        url: "{{ route('pedidosanulaciones.modal.agregaranulacion_pc') }}",
                        data: {
                            codigo: $(this).val(),tipo: 'F',
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            console.log(response);
                            if (response.contador>=1){
                                if (response.data.estado!=0){
                                    $('#tipoAnulacion').val("C");
                                    $('#txtIdPedidoCompleto').val(response.data.id);
                                    $('#asesorCodigoPc').val(response.data.name);
                                    var numberpt =parseFloat(response.data.total.replace(",", ".")) ;
                                    var formattedNumberpt = numberpt.toLocaleString('es-PE', {minimumFractionDigits: 2});
                                    $('#importeCodigoPc').val(formattedNumberpt);
                                    $('#anulacionCodigoPc').val(formattedNumberpt);
                                    $('#rucCodigoPc').val(response.data.ruc);
                                    $('#razonCodigoPc').val(response.data.nombre_empresa);
                                }else{
                                    Swal.fire('Error', 'El pedido ingresado se encuentra anulado. Ingrese otro codigo', 'warning');return false;
                                }
                            }else{
                                limpiarFormSolAnulCompl();
                                Swal.fire('Error', 'El pedido ingresado no te corresponde. Codigo = '+txtValorPc, 'warning');return false;
                            }

                        }
                    });
                }
            });
            /*VALIDACION DE NUMERO*/
            $(document).ready(function (){
                $('.type_number').keyup(function (){
                    this.value = (this.value + '').replace(/[^0-9]/g, '');
                });
            });

            $(document).on("submit", "#form-agregaranulacion-pc", function (e) {
                e.preventDefault();
                $(".btnEnviarPagoCompleto").attr('disabled', 'disabled');
                var txtIdPedidoCompleto     =$('#txtIdPedidoCompleto').val();
                var asesorCodigoPc          =$('#asesorCodigoPc').val();
                var txtMotivoPedComplet     =$('#txtMotivoPedComplet').val();
                var txtResponsablePedComplet     =$('#txtResponsablePedComplet').val();

                if (txtIdPedidoCompleto == '') {
                    Swal.fire('Error', 'No se puede ingresar una solicitud sin un pedido', 'warning');
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }
                if (asesorCodigoPc == '') {
                    Swal.fire('Error', 'No se puede ingresar un codigo vacio', 'warning');
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }

                if ($('#inputArchivoSubir').val() == '') {
                    Swal.fire('Error', 'No se puede ingresar sin archivos', 'warning');
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }
                if ($('#inputArchivoCapturaSubir').val() == '') {
                    Swal.fire('Error', 'No se puede ingresar sin archivos en captura', 'warning');
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }
                if (txtMotivoPedComplet.length < 1) {
                    Swal.fire('Error','Completa el sustento para la anulación ','warning'
                    ).then(function () {
                        $("#txtMotivoPedComplet").focus()
                    });
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }
                if (txtResponsablePedComplet.length < 1) {
                    Swal.fire('Error','Completa el responsable de la anulación ','warning'
                    ).then(function () {
                        $("#txtResponsablePedComplet").focus()
                    });
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }

                /*if (txtMotivoPedComplet.length > 250) {
                    Swal.fire('Error','El campo motivo no debe superar los 250 caracteres.','warning'
                    ).then(function () {
                        $("#txtMotivoPedComplet").focus()
                    });
                    $(".btnEnviarPagoCompleto").attr('disabled', false);
                    return false;
                }*/
                var data = new FormData(document.getElementById("form-agregaranulacion-pc"));

                $.ajax({
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    url: "{{ route('solicita_anulacion_pedido') }}",
                    data: data,
                    success: function (data) {
                        /*console.log('Solicitando Anulacion', data);*/
                        if (data.countpedidosanul==0){
                            if (data.pedidosinpago==1){
                                Swal.fire('Notificacion', 'Se registró la solicitud de anulacion, correctamente.', 'success');
                                limpiarFormSolAnulCompl();
                                $('#tblListadoAnulaciones').DataTable().ajax.reload();
                            }else {
                                Swal.fire('Error', 'El pedido tiene pagos o adelantos, verifique.', 'warning');
                            }
                        }else {
                            Swal.fire('Error', 'Ya existe un registro con los mismos datos, verifique.', 'warning');
                        }
                        $(".btnEnviarPagoCompleto").attr('disabled', false);
                    }
                });

            });

            function limpiarFormSolAnulCompl(){
                $('#txtIdPedidoCompleto').val('');
                $('#codigoCodigoPc').val('');
                $('#asesorCodigoPc').val('');
                $('#importeCodigoPc').val('');
                $('#anulacionCodigoPc').val('');
                $('#rucCodigoPc').val('');
                $('#razonCodigoPc').val('');
                $('#inputArchivoSubir').val('');
                $('#txtMotivoPedComplet').val('');
                $('#inputArchivoCapturaSubir').val('');
                $('#txtResponsablePedComplet').val('');
            }

            function limpiarFormSolAnulFact(){
                $('#txtIdPedidoFactura').val('');
                $('#codigoCodigoF').val('');
                $('#asesorCodigoF').val('');
                $('#importeCodigoF').val('');
                $('#anularCodigoF').val('');
                $('#rucCodigoF').val('');
                $('#razonCodigoF').val('');
                $('#inputArchivoSubirf').val('');
                $('#txtMotivoFactura').val('');
                $('#inputArchivoCapturaSubirf').val('');
                $('#txtResponsableFactura').val('');
            }

            function limpiarFormSolAnulCobr(){
                $('#txtIdPedidoCobranza').val('');
                $('#txtCodPedidoCobranza').val('');
                $('#txtCodAsesorCobranza').val('');
                $('#txtImporteCobranza').val('');
                $('#txtImporteAnularCob').val('');
                $('#txtRucCobranza').val('');
                $('#txtRazSocialCobranza').val('');
                $('#filesAddCobranza').val('');
                $('#txtMotivoCobranza').val('');
                $('#filesAddCapturaCobranza').val('');
                $('#txtResponsableCobranza').val('');
            }

            /*MODAL ANULACION - F*/
            $(document).on("keyup", '#codigoCodigoF', function () {
                let tamanio = $.trim($(this).val()).length;
                var txtValorFac=$(this).val();
                if (tamanio > 8 && tamanio < 16) {
                    $.ajax
                    ({
                        type: "POST",
                        url: "{{ route('pedidosanulaciones.modal.agregaranulacion_f') }}",
                        data: {
                            codigo: $(this).val(),tipo: 'F',

                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            console.log('Respuesta',response);
                            if (response.contador>=1){
                                if (response.contadorcodigo==0){
                                    if (response.data.estado!=0){
                                        $('#tipoAnulacion2').val("F");
                                        $('#txtIdPedidoFactura').val(response.data.id);
                                        $('#asesorCodigoF').val(response.data.name);

                                        var numberf =parseFloat(response.data.total.replace(",", ".")) ;
                                        var formattedNumberf = numberf.toLocaleString('es-PE', {minimumFractionDigits: 2});
                                        $('#importeCodigoF').val(formattedNumberf);
                                        $('#importeCodigoF').addClass("font-weight-bold");

                                        /*$('#anulacionCodigoF').val(response.data.total);*/
                                        $('#rucCodigoF').val(response.data.ruc);
                                        $('#razonCodigoF').val(response.data.nombre_empresa);
                                    }else{
                                        limpiarFormSolAnulFact();
                                        Swal.fire('Error', 'El pedido ingresado se encuentra anulado. Ingrese otro codigo al ingresado '+txtValorFac, 'warning');return false;
                                    }
                                }else{
                                    limpiarFormSolAnulFact();
                                    Swal.fire('Error', 'El pedido ingresado se encuentra en estado POR ATENDER - OPE, verifique. Codigo = '+txtValorFac, 'warning');return false;
                                }
                            }else{
                                limpiarFormSolAnulFact();
                                Swal.fire('Error', 'El pedido ingresado no te corresponde. Codigo = '+txtValorFac, 'warning');return false;
                            }

                        }
                    });
                }
            });

            $(document).on("keyup", '#txtCodPedidoCobranza', function () {
                let tamanio = $.trim($(this).val()).length;
                var txtCodPedidoCobranza=$(this).val();
                if (tamanio > 8 && tamanio < 16) {
                    $.ajax
                    ({
                        type: "POST",
                        url: "{{ route('pedidosanulaciones_cobranza') }}",
                        data: {
                            codigo: $(this).val(),tipo: 'Q',
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            console.log('Respuesta Cobranza',response);
                            if (response.contador>=1){
                                    if (response.data.estado!=0){
                                        $('#tipoCobranza2').val("Q");
                                        $('#txtIdPedidoCobranza').val(response.data.id);
                                        $('#txtCodAsesorCobranza').val(response.data.name);

                                        var numberdp =parseFloat(response.data.total.replace(",", ".")) ;
                                        var formattedNumberdp = numberdp.toLocaleString('es-PE', {minimumFractionDigits: 2});
                                        $('#txtImporteCobranza').val(formattedNumberdp);
                                        $('#txtImporteCobranza').addClass("font-weight-bold");

                                        var number =parseFloat(response.data.totaldp2.replace(",", ".")) ;
                                        var formattedNumber = number.toLocaleString('es-PE', {minimumFractionDigits: 2});
                                        $('#txtImporteAnularCob').val( formattedNumber );
                                        $('#txtImporteAnularCob').addClass("font-weight-bold");

                                        $('#txtRucCobranza').val(response.data.ruc);
                                        $('#txtRazSocialCobranza').val(response.data.nombre_empresa);
                                    }else{
                                        limpiarFormSolAnulCobr();
                                        Swal.fire('Error', 'El pedido ingresado se encuentra anulado. Ingrese otro codigo, '+txtCodPedidoCobranza, 'warning');return false;
                                    }
                            }else{
                                limpiarFormSolAnulCobr();
                                Swal.fire('Error', 'No se puede solicitar la anulacion del pedido ya que no tiene adelantos, verifique. Codigo = '+txtCodPedidoCobranza, 'warning');return false;
                            }

                        }
                    });
                }
            });

            /*CARGA DE ARCHIVOS*/
            $(document).on("click", "#form-agregaranulacion-f #cargaArchivosF", function () {
                var file = document.createElement('input');
                file.type = 'file';
                file.click()
                file.addEventListener('change', function (e) {
                    if (file.files.length > 0) {
                        $('#form-agregaranulacion-f').find('.result_picture').css('display', 'block');
                        console.log("ADD: ", URL.createObjectURL(file.files[0]))
                        dataForm_agregaranulacion_f.agregar_imagen_f = file.files[0]
                        $('#form-agregaranulacion-f').find('.result_picture>img').attr('src', URL.createObjectURL(file.files[0]))
                    }
                })
            });


            $(document).on("submit", "#form-agregaranulacion-f", function (e) {
                e.preventDefault();
                $(".btnEnviarFactura").attr('disabled', 'disabled')
                var txtIdPedidoFactura     =$('#txtIdPedidoFactura').val();
                var asesorCodigoF          =$('#asesorCodigoF').val();
                var btotal                 =$('#importeCodigoF').val();
                var bimporte               =$('#anularCodigoF').val();
                var txtMotivoFactura    =$('#txtMotivoFactura').val();
                var txtResponsableFactura    =$('#txtResponsableFactura').val();

                if (txtIdPedidoFactura == '') {
                    Swal.fire('Error', 'No se puede ingresar una solicitud sin un pedido', 'warning');
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }
                if (asesorCodigoF == '') {
                    Swal.fire('Error', 'No se puede ingresar un codigo vacio', 'warning');
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }

                if (bimporte== '') {
                    Swal.fire('Error', 'Debe ingresar el importe a la solicitud.', 'warning').then(function () {
                        $("#anularCodigoF").focus()
                    });
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }
                if (parseFloat(bimporte) >=parseFloat(btotal)) {
                    Swal.fire('Error', 'El valor del importe a eliminar debe ser menor al total.', 'warning').then(function () {
                        $("#anularCodigoF").focus()
                    });
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }

                if ($('#inputArchivoSubirf').val() == '') {
                    Swal.fire('Error', 'No se puede ingresar sin archivos', 'warning');
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }
                if ($('#inputArchivoCapturaSubirf').val() == '') {
                    Swal.fire('Error', 'No se puede ingresar sin archivos en capturas', 'warning');
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }
                if (txtMotivoFactura.length < 1) {
                    Swal.fire('Error','Completa el sustento para la anulación ','warning'
                    ).then(function () {
                        $("#txtMotivoFactura").focus()
                    });
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }
                if (txtResponsableFactura.length < 1) {
                    Swal.fire('Error','Completa el responsable para la anulación ','warning'
                    ).then(function () {
                        $("#txtMotivoFactura").focus()
                    });
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }
                /*if (txtMotivoFactura.length >250) {
                    Swal.fire('Error','El campo sustento no debe superar los 250 caracteres.','warning'
                    ).then(function () {
                        $("#txtMotivoFactura").focus()
                    });
                    $(".btnEnviarFactura").attr('disabled', false);
                    return false;
                }*/
                var datas = new FormData(document.getElementById("form-agregaranulacion-f"));
                /*console.log('FActuras',datas); return false;*/
                $.ajax({
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    url: "{{ route('solicita_anulacion_pedidof') }}",
                    data: datas,
                    success: function (data) {
                        /*console.log('Solicitando Anulacion', data);*/
                        if (data.countpedidosanul==0){
                            if (data.pedidosinpago==1){
                                Swal.fire('Notificacion', 'Se registró la solicitud de anulacion, correctamente.', 'success');
                                limpiarFormSolAnulFact();
                                $('#tblListadoAnulaciones').DataTable().ajax.reload();
                            }else {
                                Swal.fire('Error', 'El pedido tiene pagos o adelantos, verifique.', 'warning');
                            }
                        }else {
                            Swal.fire('Error', 'Ya existe un registro con los mismos datos, verifique.', 'warning');
                        }
                        $(".btnEnviarFactura").attr('disabled', false);

                    }
                });

            });

            $(document).on("submit", "#frmAgregaAnulacionCobranza", function (e) {
                e.preventDefault();
                $(".btnEnviarCobranza").attr('disabled', 'disabled')
                var txtIdPedidoCobranza  =$('#txtIdPedidoCobranza').val();
                var txtCodPedidoCobranza =$('#txtCodPedidoCobranza').val();
                var txtImporteCobranza   =$('#txtImporteCobranza').val();
                var txtImporteAnularCob  =$('#txtImporteAnularCob').val();
                var txtMotivoCobranza    =$('#txtMotivoCobranza').val();
                var txtResponsableCobranza    =$('#txtResponsableCobranza').val();

                if (txtIdPedidoCobranza == '') {
                    Swal.fire('Error', 'No se puede ingresar una solicitud sin un pedido', 'warning');
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }
                if (txtCodPedidoCobranza == '') {
                    Swal.fire('Error', 'No se puede ingresar un codigo vacio', 'warning');
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }

                if (txtImporteAnularCob== '') {
                    Swal.fire('Error', 'Debe ingresar el importe a la solicitud.', 'warning').then(function () {
                        $("#txtImporteAnularCob").focus()
                    });
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }
                /*if (parseFloat(txtImporteAnularCob) >=parseFloat(txtImporteCobranza)) {
                    Swal.fire('Error', 'El valor del importe a eliminar debe ser menor al total.', 'warning').then(function () {
                        $("#txtImporteAnularCob").focus()
                    });
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }*/

                if ($('#filesAddCobranza').val() == '') {
                    Swal.fire('Error', 'No se puede ingresar sin archivos', 'warning');
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }
                if ($('#filesAddCapturaCobranza').val() == '') {
                    Swal.fire('Error', 'No se puede ingresar sin archivos en capturas', 'warning');
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }
                if (txtMotivoCobranza.length < 1) {
                    Swal.fire('Error','Completa el sustento para la anulación ','warning'
                    ).then(function () {
                        $("#txtMotivoCobranza").focus()
                    });
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }
                if (txtResponsableCobranza.length < 1) {
                    Swal.fire('Error','Completa el responsable para la anulación ','warning'
                    ).then(function () {
                        $("#txtResponsableCobranza").focus()
                    });
                    $(".btnEnviarCobranza").attr('disabled', false);
                    return false;
                }

                var datacobranza = new FormData(document.getElementById("frmAgregaAnulacionCobranza"));
                $.ajax({
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    url: "{{ route('solicita_anulacion_pedidoq') }}",
                    data: datacobranza,
                    success: function (data) {
                        /*console.log('Solicitando Anulacion', data);*/
                        if (data.countpedidosanul==0){
                            if (data.pedidosinpago==0){
                                Swal.fire('Notificacion', 'Se registró la solicitud de anulacion, correctamente.', 'success');
                                limpiarFormSolAnulCobr();
                                $('#tblListadoAnulaciones').DataTable().ajax.reload();
                            }else {
                                Swal.fire('Error', 'El pedido tiene pagos o adelantos, verifique.', 'warning');
                            }
                        }else {
                            Swal.fire('Error', 'Ya existe un registro con los mismos datos, verifique.', 'warning');
                        }
                        $(".btnEnviarCobranza").attr('disabled', false);

                    }
                });

            });

            $('#tblListadoAnulaciones tbody').on('click', 'button.btnApruebaAsesor', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,1,1);
            })
            $('#tblListadoAnulaciones tbody').on('click', 'button.btnApruebaEncargado', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,2,1);
            })
            $('#tblListadoAnulaciones tbody').on('click', 'button.btnApruebaAdmin', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,3,1);
            })
            $('#tblListadoAnulaciones tbody').on('click', 'button.btnApruebaJefeOp', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,4,1);
            })
0
            $('#tblListadoAnulaciones tbody').on('click', 'button.btnDesapruebaEncargado', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,2,2);
            })

            $('#tblListadoAnulaciones tbody').on('click', 'button.btnDesapruebaAdministrador', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,3,2);
            })

            function aprobacionAnulacion(idAnulacion,accion,estado){
                var v_url="";
                var v_text="";
                var v_estado="";
                var v_button="";
                var v_respuesta="";
                if (estado==1){
                    v_estado="Aprobando "
                    v_button="Si, aprobar"
                    v_respuesta=" aprobó "
                }else  if (estado==2){
                    v_estado="Rechazando "
                    v_button="Si, rechazar"
                    v_respuesta=" rechazó "
                }
                if (accion==1){
                    v_url="{{ route('anulacionAprobacionAsesor') }}";
                    v_text=v_estado+"- Asesor";
                }
                if (accion==2){
                    v_url="{{ route('anulacionAprobacionEncargado') }}";
                    v_text=v_estado+"- Encargado";
                }
                if (accion==3){
                    v_url="{{ route('anulacionAprobacionAdmin') }}";
                    v_text=v_estado+"- Administrador";
                }
                if (accion==4){
                    v_url="{{ route('anulacionAprobacionJefeOp') }}";
                    v_text=v_estado+"- Jefe de Operaciones";
                }

                if ($.trim(accion) =="2" && $.trim(estado) =="1"){
                    Swal.fire({
                        title:'Cancelar',
                        html:'<textarea id="txt_responsableanulacion_enc" placeholder="Ingrese el responsable para la anulacion de pedido." class="form-control"></textarea>',
                        preConfirm: () => {
                            const txt_responsableanulacion_enc = Swal.getPopup().querySelector('#txt_responsableanulacion_enc').value
                            if(!txt_responsableanulacion_enc){
                                Swal.showValidationMessage(`Completa el responsable de la anulacion`);
                            }
                            return { responsableanulacion_enc:txt_responsableanulacion_enc }
                        },
                        icon: 'warning',
                        title: '¿Estás seguro?',
                        text: v_text,
                        showDenyButton: true,
                        confirmButtonText: v_button,
                        denyButtonText: 'No, cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.isConfirmed) {
                                /*console.log("BAGAN BAGAN:",result.value.responsableanulacion_enc);*/
                                if(result.value.responsableanulacion_enc!='')
                                {
                                    console.log("Aca no ingresa, anda paya bobo")
                                    var formIdAnulacion = new FormData();
                                    formIdAnulacion.append("pedidoAnulacionId", idAnulacion);
                                    formIdAnulacion.append("estado", estado);
                                    formIdAnulacion.append("sustento", result.value.sustento);
                                    formIdAnulacion.append("responsableanulacion_enc", result.value.responsableanulacion_enc);
                                    $.ajax({
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        url: v_url,
                                        data: formIdAnulacion,
                                        success: function (data) {
                                            console.log(data);
                                            Swal.fire('Notificacion', 'Se '+v_respuesta+' correctamente.', 'success');
                                            $('#tblListadoAnulaciones').DataTable().ajax.reload();
                                        }
                                    });
                                }else{
                                    Swal.fire('Notificacion', 'El responsable para la anulacion de pedido no puede estar vacio.', 'danger');
                                }

                            }
                        }
                    })
                }else if ($.trim(accion)!="2" && $.trim(estado)=="1"){
                    Swal.fire({
                        title:'Cancelar',
                        icon: 'warning',
                        title: '¿Estás seguro?',
                        text: v_text,
                        showDenyButton: true,
                        confirmButtonText: v_button,
                        denyButtonText: 'No, cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var formIdAnulacion = new FormData();
                            formIdAnulacion.append("pedidoAnulacionId", idAnulacion);
                            formIdAnulacion.append("estado", estado);
                            formIdAnulacion.append("sustento", "");
                            $.ajax({
                                processData: false,
                                contentType: false,
                                type: 'POST',
                                url: v_url,
                                data: formIdAnulacion,
                                success: function (data) {
                                    console.log(data);
                                    Swal.fire('Notificacion', 'Se '+v_respuesta+' correctamente.', 'success');
                                    $('#tblListadoAnulaciones').DataTable().ajax.reload();
                                }
                            });
                        }
                    })
                }else  if ($.trim(estado)=="2"){
                    Swal.fire({
                        title:'Cancelar',
                        html:'<textarea id="txt_sustento" placeholder="Complete el sustento para la anulacion." class="form-control"></textarea>',
                        preConfirm: () => {
                            const sustento = Swal.getPopup().querySelector('#txt_sustento').value
                            if(!sustento){
                                Swal.showValidationMessage(`Por favor ingrese sustento`);
                            }
                            return { sustento:sustento }
                        },
                        icon: 'warning',
                        title: '¿Estás seguro?',
                        text: v_text,
                        showDenyButton: true,
                        confirmButtonText: v_button,
                        denyButtonText: 'No, cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.isConfirmed) {
                                console.log(result.value.sustento);
                                if(result.value.sustento!='')
                                {
                                    var formIdAnulacion = new FormData();
                                    formIdAnulacion.append("pedidoAnulacionId", idAnulacion);
                                    formIdAnulacion.append("estado", estado);
                                    formIdAnulacion.append("sustento", result.value.sustento);
                                    $.ajax({
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        url: v_url,
                                        data: formIdAnulacion,
                                        success: function (data) {
                                            console.log(data);
                                            Swal.fire('Notificacion', 'Se '+v_respuesta+' correctamente.', 'success');
                                            $('#tblListadoAnulaciones').DataTable().ajax.reload();
                                        }
                                    });
                                }else{
                                    Swal.fire('Notificacion', 'Sustento no puede estar vacio.', 'danger');
                                }

                            }
                        }
                    })
                }

            }

            $('#modal-confirma-anulacion').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var pedido_id = button.data('pedido-id');
                var idanulacion = button.data('idanulacion');
                var codigopedido= button.data('codigo-pedido');
                var nameresponsable= button.data('responsable-anula');
                var registraSolicitud= button.data('registra_solicitud');
                console.log('idanulacion => ',idanulacion,'codigoanulacion => ',codigopedido);
                $("#motivo").val('');
                $("#anulacion_password").val('');
                $("#inputFilesAdmin").val('');
                $("#responsable").val(nameresponsable);
                $("#txtPedidoId").val(pedido_id );
                $("#txtPedidoAnulacionId").val(idanulacion );
                $(".textcodepedido").html(codigopedido);
                $(".txtNombreRegistraAnulacion").html(registraSolicitud);

                var formConfirAdmin = new FormData();
                formConfirAdmin.append("idpedidoanulacion", idanulacion);

                $.ajax({
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('getcbxculpables') }}",
                    data: formConfirAdmin,
                    success: function (data) {
                        console.log('BLoqueando', data);
                        $('#cbxCulpables').html(data.datoscombo).selectpicker("refresh");
                    }
                });
            });

            $(document).on("change", "#cbxCulpables", function () {
                if ($(this).val()=="-1"){
                    $("#txtOtrosCulpables").attr('disabled', false);
                }else {
                    $("#txtOtrosCulpables").attr('disabled', true);
                }
            });

            $(document).on("submit", "#frmConfirmaAnulacion", function (evento) {
                evento.preventDefault();
                var motivo = $("#motivo").val();
                var responsable = $("#responsable").val();
                var anulacion_password = $("#anulacion_password").val();
                var inputFilesAdmin = $('#inputFilesAdmin').val();
                var culpable="";
                var cbxCulpables = $('#cbxCulpables').val();
                var txtOtrosCulpables = $('#txtOtrosCulpables').val();
                if (cbxCulpables=="-1"){
                    culpable=txtOtrosCulpables;
                }else{
                    culpable=cbxCulpables;
                }
                if (motivo.length < 1) {
                    Swal.fire(
                        'Error',
                        'Ingrese el motivo para confirmar la solicitud la anulacion del pedido',
                        'warning'
                    )
                    $(".btnConfirmaSolicitudAdmin").attr('disabled', false);
                    return false;
                } else if (responsable == '') {
                    Swal.fire(
                        'Error',
                        'Ingrese el responsable de la anulación',
                        'warning'
                    )
                    $(".btnConfirmaSolicitudAdmin").attr('disabled', false);
                    return false;
                } else if (!anulacion_password) {
                    Swal.fire(
                        'Error',
                        'Ingrese la contraseña para autorizar la anulación',
                        'warning'
                    );
                    $(".btnConfirmaSolicitudAdmin").attr('disabled', false);
                    return false;
                }else if (inputFilesAdmin == '') {
                    Swal.fire('Error', 'No se puede confirmar la anulacion sin archivos', 'warning');
                    $(".btnConfirmaSolicitudAdmin").attr('disabled', false);
                    return false;
                }else if (culpable == '') {
                    Swal.fire('Error', 'Seleccione un culpable', 'warning');
                    $(".btnConfirmaSolicitudAdmin").attr('disabled', false);
                    return false;
                }
                else {
                    ejecutarForSolicituAnulacion();
                }
            })

            function ejecutarForSolicituAnulacion() {
                var datafrmanula = new FormData(document.getElementById("frmConfirmaAnulacion"));
                /*console.log('FActuras',datas); return false;*/
                $.ajax({
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    url: "{{ route('confirmaSolicitudAnulacion') }}",
                    data: datafrmanula,
                    success: function (data) {
                        console.log('Confirmacion Admin Anulacion', data);
                        if (data.contpedanulacions==1){
                            Swal.fire('Notificacion', 'Se confirmo la solicitud de anulacion, correctamente.', 'success');
                            $("#modal-confirma-anulacion").modal("hide");
                            $('#tblListadoAnulaciones').DataTable().ajax.reload();
                        }else {
                            Swal.fire('Error', 'No existe la anulacion para confirmar, verifique.', 'warning');
                        }
                        $(".btnConfirmaSolicitudAdmin").attr('disabled', false);

                    }
                }).done(function (data) {

                    console.log('Confirmacion Admin Anulacion', data);
                    if (data.contpedanulacions==1){
                        Swal.fire('Notificacion', 'Se confirmo la solicitud de anulacion, correctamente.', 'success');
                        $("#modal-confirma-anulacion").modal("hide");
                        resetearcampossolanu();
                        $('#tblListadoAnulaciones').DataTable().ajax.reload();
                    }else {
                        Swal.fire('Error', 'No existe la anulacion para confirmar, verifique.', 'warning');
                    }
                    $(".btnConfirmaSolicitudAdmin").attr('disabled', false);
                }).fail(function (err, error, errMsg) {
                    console.log(arguments, err, errMsg)
                    if (err.status == 401) {
                        Swal.fire(
                            'Error',
                            'No autorizado para poder anular el pedido, ingrese una contraseña correcta',
                            'error'
                        )
                    } else {
                        Swal.fire(
                            'Error',
                            'Ocurrio un error: ' + errMsg,
                            'error'
                        )
                    }
                });

            }

            function resetearcampossolanu() {
                $('#motivo').val("");
                $('#responsable').val("");
                $('#inputFilesAdmin').val("");
            }

            $('#modal-ver_motivoanulacion').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                console.log(button);
                var idPedidoAnulacion= button.data('idanulacion')
                var pedido_motivo= button.data('pedido-motivo')
                var pedido_codigo= button.data('codigos')
                var resposable_create_asesor= button.data('responsable_create_asesor')
                var responsable_aprob_encarg= button.data('responsable_aprob_encarg')

                $("#txtMotivoAnulacion").html(pedido_motivo);
                $(".lblTitleMotivoAnulacion").html("Motivo Anulacion : "+pedido_codigo);
                console.log("respo_ase=",resposable_create_asesor.length," respo_ennc=",responsable_aprob_encarg.length)
                if (resposable_create_asesor.length>=1){
                    $(".divResponsableAsesor").show();
                    $("#txtResponsableAsesor").html(resposable_create_asesor);
                }else{
                    $(".divResponsableAsesor").hide();
                    $("#txtResponsableAsesor").html("")
                }

                if (responsable_aprob_encarg.length>0){
                    $(".divResponsableEncarg").show();
                    $("#txtResponsableEncarg").html(responsable_aprob_encarg);
                }else{
                    $(".divResponsableEncarg").hide();
                    $("#txtResponsableEncarg").html("")
                }

                //datos form
                /*var formVerImagenAnularSol = new FormData();
                formVerImagenAnularSol.append("pedidoAnulacionId", data.idanulacion);*/

                /*cargar imagenes*/
                $.ajax({
                    url: "{{ route('operaciones.veratencionanulacion',':id') }}".replace(':id', idPedidoAnulacion),
                    data: idPedidoAnulacion,
                    method: 'POST',
                    success: function (data) {
                        console.log(data)
                        console.log("obtuve las imagenes atencion del pedido " + idPedidoAnulacion)
                        $('#imagenAnulacionUsuario').html("");
                        $('#imagenAnulacionUsuario').html(data);
                    }
                });
            });

            $('#modal-ver_rechazo_encargado').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var motivo_rechazo= button.data('motivo-rechazo')
                $("#txtMotivoRechazo").html(motivo_rechazo);
            });

            $('#tblListadoAnulaciones tbody').on('click', 'button.btnAnularSolicitudByAsesor', function () {
                var data = tblListadoAnulaciones.row($(this).parents('tr')).data();
                /*console.log(data.idanulacion); return false;*/
                Swal.fire({
                    title:'Cancelar',
                    icon: 'warning',
                    title: '¿Estás seguro?',
                    text: 'Esta anulando la solicitud. Recuerde que ya no podra visualizar el registro.',
                    showDenyButton: true,
                    confirmButtonText: 'Si, anular',
                    denyButtonText: 'No, cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formAnularSol = new FormData();
                        formAnularSol.append("pedidoAnulacionId", data.idanulacion);
                        $.ajax({
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            url: '{{ route('anulacionSolicitud') }}',
                            data: formAnularSol,
                            success: function (data) {
                                console.log(data);
                                Swal.fire('Notificacion', 'Se anuló la solicitud correctamente.', 'success');
                                $('#tblListadoAnulaciones').DataTable().ajax.reload();
                            }
                        });
                    }
                })
            })
            limpiarFormSolAnulCompl();
            $('a[data-toggle="tab"]').on('click', function(e) {
                console.log('Tab clicked:', $(this).attr('href'));
                var frmtab=$(this).attr('href');
                if (frmtab=="#tabPedidoCompleto"){
                    limpiarFormSolAnulCompl();
                }else if (frmtab=="#tabFactura"){
                    limpiarFormSolAnulFact();
                }
            });
            /*FIN DEL SCRIPT*/
        });
    </script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
@stop

@push('css')
    <style>
        @media screen and (max-width: 2249px) {
            .dis-grid {
                display: flex;
                justify-content: center;
                align-items: center;
                align-self: center;
                flex-direction: column;
            }

            .btn-fontsize {
                font-size: 15px;
            }

            .etiquetas_asignacion {
                background-color: #b0deb3 !important;
                font-size: 12px;
                padding: 4px;
                font-weight: 700;
                line-height: 1;
                white-space: nowrap;
                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                color: #4a604b !important;
                margin-left: 2px;
            }

            .sorting:before,
            .sorting:after {
                top: 20px;
            }

        }

        @media screen and (max-width: 2144px) {
            thead,
            tr,
            td {
                vertical-align: middle !important;
            }

            .btn-fontsize {
                font-size: 11px;
                min-width: 85px;
                max-width: 130px;
            }

            .dis-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 2fr));
                gap: 0.7rem
            }
        }

        @media screen and (max-width: 2039px) {
            .dis-grid {
                display: flex;
                justify-content: center;
                align-items: center;
                align-self: center;
                flex-direction: column;
            }

            .btn-fontsize {
                min-width: 75px;
                width: 100px;
            }
        }

        @media screen and (max-width: 1440px) {
            .etiquetas_asignacion {
                font-size: 9px;
                padding: 2px;
                white-space: pre-line !important;
            }
        }
    </style>
@endpush

