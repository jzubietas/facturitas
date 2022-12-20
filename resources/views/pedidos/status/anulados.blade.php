@extends('adminlte::page')
@section('title', 'Pedidos en proceso de anulación')
@section('content_header')
    <h1>Pedidos en proceso de anulación</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <table id="tablaPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    {{-- <th scope="col">Razón social</th> --}}
                    <th scope="col">Asesor</th>
                    <th scope="col">Fecha de registro</th>{{--fecha hora--}}
                    <th scope="col">Tipo de Banca</th>
                    <th scope="col">Adjuntos</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('operaciones.modal.confirmarAnular')
@endsection

@section('css')

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#tablaPrincipal').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "order": [[ 0, "desc" ]],
                ajax: "{{ route('pedidos.estados.anulados',['ajax-datatable'=>1]) }}",
                createdRow: function( row, data, dataIndex){

                },
                rowCallback: function (row, data, index) {
                    $('td',row).css('background', 'red').css('font-weight','bold');
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function ( data, type, row, meta ) {
                            if(row.id<10){
                                return 'PED000'+row.id;
                            }else if(row.id<100){
                                return 'PED00'+row.id;
                            }else if(row.id<1000){
                                return 'PED0'+row.id;
                            }else{
                                return 'PED'+row.id;
                            }
                        }
                    },
                    {data: 'codigos', name: 'codigos', },
                    //{data: 'empresas', name: 'empresas', },
                    {data: 'users', name: 'users', },
                    {
                        data: 'fecha',
                        name: 'fecha',
                        render:$.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss' )
                        //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ).format('HH:mm:ss'),
                    },
                    {data: 'tipo_banca', name: 'tipo_banca', },
                    {
                        data: 'imagenes',
                        name: 'imagenes',
                        orderable: false,
                        searchable: false,
                        sWidth:'20%',
                        render: function ( data, type, row, meta ) {
                            if(data==null)
                            {
                                return '';
                            }else{
                                if(data>0)
                                {
                                    data = '<a href="" data-target="#modal-veradjunto" data-adjunto='+row.id+' data-toggle="modal" ><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</button></a>';
                                    return data;
                                }else{
                                    return '';
                                }
                            }

                        }
                    },
                    {data: 'condicion_code',
                        name: 'condicion_code',
                        render: function ( data, type, row, meta ) {
                            if(row.pendiente_anulacion==1) {
                                return '<span class="badge badge-success">' + '{{\App\Models\Pedido::PENDIENTE_ANULACION }}' + '</span>';
                            }
                            return ''
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth:'20%'
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


            $('#modal_confirmar_anular').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                console.log(event.relatedTarget)
                console.log(button.data('pedido_id_code'))
                $("#anular_pedido_id").html(button.data('pedido_id_code'))
                $("#motivo_anulacion_text").html(button.data('pedido_motivo'))
                $("#anular_pedido_id").val(button.data('pedido_id'))
            })

            $('#attachmentsButtomRechazar').click(function (event) {

                var data=new FormData();
                data.append("pedido_id",$("#anular_pedido_id").val())
                data.append("action",'confirm_anulled_cancel')
                $.ajax({
                    type: 'POST',
                    url: "{{ route('pedidos.confirmar.anular') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                }).done(function (data){
                    if(data.success) {
                        Swal.fire(
                            'Mensaje',
                            'Pedido restaurado correctamente',
                            'success'
                        )
                    }else{
                        Swal.fire(
                            'Mensaje',
                            'Pedido ya ha sido anulado',
                            'warning'
                        )
                    }
                }).always(function (){
                    $('#modal_confirmar_anular').modal('hide')
                    $("#anularAttachments").val(null)

                    $('#tablaPrincipal').DataTable().ajax.reload();
                });
            });
            $('#attachmentsButtom').click(function (event) {
                var files=Array.from($("#anularAttachments")[0].files);
                if(files.length==0){
                    Swal.fire(
                        'Error',
                        'Debe adjuntar almenos una nota de credito',
                        'warning'
                    )
                    return;
                }
                var data=new FormData();
                data.append("pedido_id",$("#anular_pedido_id").val())
                data.append("action",'confirm_anulled')
                for (var i in files){
                    if(files[i].name) {
                        data.append('attachments[' + i + ']', files[i],files[i].name)
                    }
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('pedidos.confirmar.anular') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                }).done(function (data){
                    if(data.success) {
                        Swal.fire(
                            'Mensaje',
                            'Pedido anulado correctamente',
                            'success'
                        )
                    }else{
                        Swal.fire(
                            'Mensaje',
                            'Pedido ya ha sido anulado',
                            'warning'
                        )
                    }
                }).always(function (){
                    $('#modal_confirmar_anular').modal('hide')
                    $("#anularAttachments").val(null)

                    $('#tablaPrincipal').DataTable().ajax.reload();
                });
            })

        });
    </script>
    <script>
    </script>
@stop
