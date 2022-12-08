@extends('adminlte::page')

@section('title', 'Lista de Usuarios')

@section('content_header')
  <h1>Lista de asesores - Asignar ENCARGADO
    {{-- @can('users.create')
      <a href="{{ route('users.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan --}}
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
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">CODIGO</th>
            <th scope="col">NOMBRES Y APELLIDOS</th>
            <th scope="col">CORREO</th>
            <th scope="col">ENCARGADO</th>
            <th scope="col">OPERARIO</th>
            <th scope="col">LLAMADA</th>
            <th scope="col">ESTADO</th>
            <th scope="col">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      @include('usuarios.modal.asignarencargado')
      @include('usuarios.modal.asignaroperario')
      @include('usuarios.modal.asignarllamada')
    </div>
  </div>

@stop

@section('css')
  <link rel="stylesheet" href="../css/admin_custom.css">
  <style>
    .bg-4{
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

  <script>
    $(document).ready(function(){
        $('#modal-asignarencargado').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var idunico = button.data('encargado')
            $("#hiddenIdencargado").val(idunico);
            if(idunico<10){
                idunico='USER000'+idunico;
            }else if(idunico<100){
                idunico= 'USER00'+idunico;
            }else if(idunico<1000){
                idunico='USERG0'+idunico;
            }else{
                idunico='USER'+idunico;
            }
            $(".textcode").html(idunico);
        });

        $('#modal-asignaroperario').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('operario')
                $("#hiddenIdoperario").val(idunico);
                if(idunico<10){
                    idunico='USER000'+idunico;
                }else if(idunico<100){
                    idunico= 'USER00'+idunico;
                }else if(idunico<1000){
                    idunico='USER0'+idunico;
                }else{
                    idunico='USER'+idunico;
                }
                $(".textcode").html(idunico);
        });

        $('#modal-asignarasesor').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('asesor')
                $("#hiddenIdasesor").val(idunico);
                if(idunico<10){
                    idunico='USER000'+idunico;
                }else if(idunico<100){
                    idunico= 'USER00'+idunico;
                }else if(idunico<1000){
                    idunico='USER0'+idunico;
                }else{
                    idunico='USER'+idunico;
                }
                $(".textcode").html(idunico);

        });

        $('#modal-asignarllamadas').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var idunico = button.data('llamadas')
                $("#hiddenIdllamadas").val(idunico);
                if(idunico<10){
                    idunico='USER000'+idunico;
                }else if(idunico<100){
                    idunico= 'USER00'+idunico;
                }else if(idunico<1000){
                    idunico='USER0'+idunico;
                }else{
                    idunico='USER'+idunico;
                }
                $(".textcode").html(idunico);

        });

        $(document).on("submit", "#formencargado", function (evento) {
            evento.preventDefault();
            var formData = $("#formencargado").serialize();
            $.ajax({
                type:'POST',
                url:"{{ route('users.asignarencargadopost') }}",
                data:formData,
            }).done(function (data) {
                Swal.fire(
                    'Usuario asignado correctamente',
                    '',
                    'success'
                )
                $("#modal-asignareencargado").modal("hide");
                $('#tablaPrincipal').DataTable().ajax.reload();
            });
        });
        $(document).on("submit", "#formoperario", function (evento) {
            evento.preventDefault();
            var formData = $("#formoperario").serialize();
            $.ajax({
                type:'POST',
                url:"{{ route('users.asignaroperariopost') }}",
                data:formData,
            }).done(function (data) {
                Swal.fire(
                        'Usuario asignado correctamente',
                        '',
                        'success'
                    )
                    $("#modal-asignaroperario").modal("hide");
                    $('#tablaPrincipal').DataTable().ajax.reload();
            });
        });
        $(document).on("submit", "#formasesor", function (evento) {
            evento.preventDefault();
            var formData = $("#formasesor").serialize();
            $.ajax({
                type:'POST',
                url:"{{ route('users.asignarasesorpost') }}",
                data:formData,
            }).done(function (data) {
                Swal.fire(
                        'Usuario asignado correctamente',
                        '',
                        'success'
                    )
                    $("#modal-asignarasesor").modal("hide");
                    $('#tablaPrincipal').DataTable().ajax.reload();
            });
        });
        $(document).on("submit", "#formllamadas", function (evento) {
            evento.preventDefault();
            var formData = $("#formllamadas").serialize();
            $.ajax({
                type:'POST',
                url:"{{ route('users.asignarllamadaspost') }}",
                data:formData,
            }).done(function (data) {
                Swal.fire(
                        'Usuario asignado correctamente',
                        '',
                        'success'
                    )
                    $("#modal-asignarllamadas").modal("hide");
                    $('#tablaPrincipal').DataTable().ajax.reload();
            });
        });

      });

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
          ajax: "{{ route('users.asesorestabla') }}",
          "createdRow": function( row, data, dataIndex){
          },

          columns: [
              {
                  data: 'id',
                  name: 'id',
                  render: function ( data, type, row, meta ) {
                      if(row.id<10){
                          return 'USER000'+row.id;
                      }else if(row.id<100){
                          return 'USER00'+row.id;
                      }else if(row.id<1000){
                          return 'USER0'+row.id;
                      }else{
                          return 'USER'+row.id;
                      }
                  }
              },
              {data: 'name', name: 'name', },
              {data: 'email', name: 'email', },
              {
                  data: 'encargado',
                  name: 'encargado',
                  render: function ( data, type, row, meta ) {
                      if(data==null)
                      {
                          return 'SIN ASIGNAR';
                      }else{
                          return data;
                      }
                  }
              },
              {
                  data: 'operario',
                  name: 'operario',
                  sWidth:'20%',
                  render: function ( data, type, row, meta ) {
                      if(data==null)
                      {
                          return 'SIN ASIGNAR';
                      }else{
                          return data;
                      }
                  }
              },
              {
                  data: 'llamada',
                  name: 'llamada',
                  render: function ( data, type, row, meta ) {
                      if(data==null)
                      {
                          return 'SIN ASIGNAR';
                      }else{
                          return data;
                      }
                  }
              },
              {
                  data: 'estado',
                  name: 'estado',
                  render: function ( data, type, row, meta ) {
                      if(data=="1")
                      {
                          return '<span class="badge badge-success">Activo</span>';
                      }else if(data=="0"){
                          return '<span class="badge badge-danger">Inactivo</span>';
                      }
                  },
              },
              {
                  data: 'action',
                  name: 'action',
                  orderable: false,
                  searchable: false,
                  sWidth:'20%',
                  render: function ( data, type, row, meta ) {
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

      });


  </script>


  @if (session('info') == 'registrado' || session('info') == 'actualizado' || session('info') == 'eliminado' || session('info') == 'asignado')
    <script>
      Swal.fire(
        'Usuario {{ session('info') }} correctamente',
        '',
        'success'
      )
    </script>
  @endif
@stop
