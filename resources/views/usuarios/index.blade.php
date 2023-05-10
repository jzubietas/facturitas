@extends('adminlte::page')

@section('title', 'Lista de Usuarios')

@section('content_header')
    <h1>Lista de Usuarios
        @can('users.create')
            <a href="{{ route('users.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
        @endcan

        @if($mirol=='Administrador')
            @can('clientes.exportar')
                <div class="float-right btn-group dropleft">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Exportar
                    </button>
                    <div class="dropdown-menu">
                        <a href="" data-target="#modal-exportar-2" data-toggle="modal" class="dropdown-item"
                           target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Usuarios</a>
                    </div>
                </div>
                @include('usuarios.modal.exportar2', ['title' => 'Exportar Lista de Usuarios', 'key' => '1'])
            @endcan
        @endif

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
        <div class="btn-group card-footer" role="group" aria-label="Basic example" id="radioBtnDiv3">
            <div class="form-check  d-flex gap-5 mr-4">
                <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnAllUser" checked="" value="1">
                <label class="form-check-label" for="rbnAllUser"> Todos </label>
            </div>
            @foreach ($roles as $rol)
                <div class="form-check  d-flex gap-5 mr-4">
                    <input class="form-check-input " type="radio" name="rbnTipo3" id="rbn{{$rol->name}}User"
                           value="{{$rol->id}}">
                    <label class="form-check-label" for="rbn{{$rol->name}}User"> {{$rol->name}} </label>
                </div>
            @endforeach
        </div>
        <div class="card-body" style="overflow-x: scroll !important;">
            <table id="tblUsuariosPrincipal" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" width="1%" class="align-middle">CODIGO</th>
                    <th scope="col" width="12.5%" class="align-middle">NOMBRES Y APELLIDOS</th>
                    <th scope="col" width="12.5%" class="align-middle">CORREO</th>
                    <th scope="col" width="1%" class="align-middle">META QUINCENA</th>
                    <th scope="col" width="1%" class="align-middle">META PEDIDOS 1</th>
                    <th scope="col" width="1%" class="align-middle">META PEDIDOS 2</th>
                    <th scope="col" width="8%" class="align-middle">ROL</th>
                    <th scope="col" width="3%" class="align-middle">ESTADO</th>
                    <th scope="col" width="15%" class="align-middle">ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('usuarios.modal.desactivar')
            @include('usuarios.modal.activar')
            @include('usuarios.modal.reset')
            @include('usuarios.modal.asignarmetaencargado')
        </div>
    </div>

@stop



@section('js')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado')
        <script>
            Swal.fire(
                'Usuario {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#tblUsuariosPrincipal').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "order": [[2, "asc"]],
                ajax: "{{ route('tableUsuarios') }}",
                createdRow: function (el, data, dataIndex) {},
                rowCallback: function (el, data, index) {
                    /*$(el).find(".meta_checkbox_active").change(function (e) {
                        console.log($(e.target).data('excluir_meta'))
                        console.log($(e.target).data('user_id'))
                        $.post('{{route('users.asesorestabla.updatemeta',':id')}}'.replace(':id',data.id),{
                            excluir:$(e.target).prop('checked'),
                            user_id:$(e.target).data('user_id'),
                        }).always(function () {
                            $("#tablaPrincipal").DataTable().ajax.reload();
                        })
                    })*/
                },
                columns: [
                    {data: 'id',name: 'id',},
                    {data: 'name',name: 'name',sWidth: '20%',},
                    {data: 'email', name: 'email',},
                    {data: 'meta_quincena', name: 'meta_quincena',},
                    {data: 'meta_pedido', name: 'meta_pedido',},
                    {data: 'meta_pedido_2',name: 'meta_pedido_2',},
                    {data: 'rol',name: 'rol',},
                    {data: 'estado',name: 'estado',
                        /*render: function (data, type, row, meta) {
                            if (data == "1") {
                                return '<a href=""><span class="badge badge-success">Activo</span></a>';
                            } else if (data == "0") {
                                return '<span class="badge badge-secondary">Inactivo</span>';
                            }
                        },*/
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        sWidth: '20%',
                        render: function (data, type, row, meta) {
                            return data;
                        }
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

            $('#modal-reset-id').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var user_id = button.data('user_id')
                var user_name = button.data('user_mame')
                console.log('userID=',user_id,'userNAME=',user_name)
                $("#txtIdUsuario").html(user_id);
                $("#txtNameUsuario").html(user_name);
                $("#hiddenIdUsuario").val(user_id);
                $("#hiddenNameUsuario").val(user_name);
            });

            $(document).on("submit", "#frmResetUser", function (evento) {
                evento.preventDefault();
                var formData = $("#frmResetUser").serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.reset') }}",
                    data: formData
                }).done(function (data) {
                    $("#modal-reset-id").modal("hide");
                    Swal.fire(
                        'Mensaje',
                        'Se reseteo la clave correctamente',
                        'success'
                    )
                    $('#tblUsuariosPrincipal').DataTable().ajax.reload();
                });
            });

            $('#modal-desactivar-id').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var user_id = button.data('user_id')
                var user_name = button.data('user_mame')
                console.log('userID=',user_id,'userNAME=',user_name)
                $("#txtDesIdUsuario").html(user_id);
                $("#txtDesNameUsuario").html(user_name);
                $("#hidDesIdUsuario").val(user_id);
                $("#hidDesNameUsuario").val(user_name);
            });

            $(document).on("submit", "#frmDesactivarUsuario", function (evento) {
                evento.preventDefault();
                /*var formData = $("#frmDesactivarUsuario").serialize();*/
                var user_id=$("#hidDesIdUsuario").val();
                var user_name=$("#hidDesNameUsuario").val();
                var estadoDesact=$("#hidEstado").val();

                var formData = new FormData();
                formData.append("user_id", user_id);
                formData.append("user_name", user_name);
                formData.append("estado", estadoDesact);
                $.ajax({
                    async: false,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('user.cambiarestado') }}",
                }).done(function (data) {
                    console.log(data);
                    $("#modal-desactivar-id").modal("hide");
                    Swal.fire(
                        'Mensaje',
                        'Se desactivo el usuario de '+user_name+' correctamente',
                        'success'
                    )
                    $('#tblUsuariosPrincipal').DataTable().ajax.reload();
                });
            });

            $('#modal-activar-id').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var user_id = button.data('user_id')
                var user_name = button.data('user_mame')
                console.log('userID=',user_id,'userNAME=',user_name)
                $("#txtActIdUsuario").html(user_id);
                $("#txtActNameUsuario").html(user_name);
                $("#hidActIdUsuario").val(user_id);
                $("#hidActNameUsuario").val(user_name);
            });

            $(document).on("submit", "#frmActivarUsuario", function (evento) {
                evento.preventDefault();
                /*var formData = $("#frmDesactivarUsuario").serialize();*/
                var user_id=$("#hidActIdUsuario").val();
                var user_name=$("#hidActNameUsuario").val();
                var estadoAct=$("#hidActEstado").val();

                var formData = new FormData();
                formData.append("user_id", user_id);
                formData.append("user_name", user_name);
                formData.append("estado", estadoAct);
                $.ajax({
                    async: false,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('user.cambiarestado') }}",
                }).done(function (data) {
                    console.log(data);
                    $("#modal-activar-id").modal("hide");
                    Swal.fire(
                        'Mensaje',
                        'Se activo el usuario de '+user_name+' correctamente',
                        'success'
                    )
                    $('#tblUsuariosPrincipal').DataTable().ajax.reload();
                });
            });
        });
    </script>
@stop
