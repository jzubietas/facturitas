@extends('adminlte::page')

@section('title', 'Bandeja de Llamados de Atencion')

@section('content_header')
    <div class="row p-0">
        <div class="form-group col-md-3">
            <h3>Bandeja de Llamados de atencion</h3>
        </div>
        <div class="form-group col-md-6">
           
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
                <table id="tblLlamadosAtencion" class="table table-striped">{{-- display nowrap  --}}
                    <thead>
                    <tr>
                        <th scope="col" class="align-middle">Tipo</th>
                        <th scope="col" class="align-middle">Identificador</th>
                        <th scope="col" class="align-middle">Accion</th>
                        <th scope="col" class="align-middle">Responsable</th>
                        <th scope="col" class="align-middle">Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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


        let tblLlamadosAtencion = null;
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

            tblLlamadosAtencion = $('#tblLlamadosAtencion').DataTable({
                dom: 'Blfrtip',
                processing: true,
                serverSide: true,
                searching: true,
                //stateSave: true,
                order: [[2, "desc"]],
                ajax: "{{ route('llamados.atencion.tabla') }}",
                rowCallback: function (row, data, index) {
                },
                initComplete: function (settings, json) {
                },
                columns: [
                    {
                        data: 'tipo',
                        name: 'tipo',
                    },
                    {data: 'user_identificador', name: 'user_identificador',},
                    {
                        data: 'accion',
                        name: 'accion',
                    },
                    {data: 'responsable', name: 'responsable',},
                    {data: 'created_at', name: 'created_at',},
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

            /*tblLlamadosAtencion.on('responsive-display', function (e, datatable, row, showHide, update) {
                if (showHide) {
                    renderButtomsDataTable($(row.node()).siblings('.child'), row.data())
                }
            });*/

            /*MODAL ANULACION PEDIDOS COMPLETOS*/

            /*VALIDACION DE NUMERO*/
            $(document).ready(function (){
                $('.type_number').keyup(function (){
                    this.value = (this.value + '').replace(/[^0-9]/g, '');
                });
            });

            $('#tblLlamadosAtencion tbody').on('click', 'button.btnApruebaAsesor', function () {
                var data = tblLlamadosAtencion.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,1,1);
            })
            $('#tblLlamadosAtencion tbody').on('click', 'button.btnApruebaEncargado', function () {
                var data = tblLlamadosAtencion.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,2,1);
            })
            $('#tblLlamadosAtencion tbody').on('click', 'button.btnApruebaAdmin', function () {
                var data = tblLlamadosAtencion.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,3,1);
            })
            $('#tblLlamadosAtencion tbody').on('click', 'button.btnApruebaJefeOp', function () {
                var data = tblLlamadosAtencion.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,4,1);
            })

            $('#tblLlamadosAtencion tbody').on('click', 'button.btnDesapruebaEncargado', function () {
                var data = tblLlamadosAtencion.row($(this).parents('tr')).data();
                aprobacionAnulacion(data.idanulacion,2,2);
            })

            $('#tblLlamadosAtencion tbody').on('click', 'button.btnDesapruebaAdministrador', function () {
                var data = tblLlamadosAtencion.row($(this).parents('tr')).data();
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
                                            $('#tblLlamadosAtencion').DataTable().ajax.reload();
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
                                    $('#tblLlamadosAtencion').DataTable().ajax.reload();
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
                                            $('#tblLlamadosAtencion').DataTable().ajax.reload();
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

            $('#modal-ver_rechazo_encargado').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var motivo_rechazo= button.data('motivo-rechazo')
                $("#txtMotivoRechazo").html(motivo_rechazo);
            });

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

