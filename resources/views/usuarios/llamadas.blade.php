@extends('adminlte::page')

@section('title', 'Lista de Usuarios Llamadas')

@section('content_header')
  <h1>Lista de usuario llamadas - Asignar JEFE y ASESOR
    {{-- @can('users.create')
      <a href="{{ route('users.create') }}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Agregar</a>
    @endcan --}}
      <a href="" data-target="#modal-asignarmetallamada" data-toggle="modal" data-llamada="">
          <button class="btn btn-info btn-sm"> Asignar metas del mes</button>
      </a>
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
            <th scope="col">ESTADO</th>
            <th scope="col">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
        @include('usuarios.modal.asignarjefellamadas')
        @include('usuarios.modal.asignarasesor')
        @include('usuarios.modal.asignarmetallamada')
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

  $(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#modal-asignarmetallamada').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var idunico = button.data('llamada')
      $("#cliente_nuevo").val(0);
      $("#cliente_nuevo_2").val(0);
      $("#cliente_recuperado_abandono").val(0);
      $("#cliente_recuperado_abandono_2").val(0);
      $("#cliente_recuperado_reciente").val(0);
      $("#cliente_recuperado_reciente_2").val(0);
      $("#meta_quincena").val(0);

      $("#llamada").val(idunico);
      if (idunico < 10) {
        idunico = 'USER000' + idunico;
      } else if (idunico < 100) {
        idunico = 'USER00' + idunico;
      } else if (idunico < 1000) {
        idunico = 'USERG0' + idunico;
      } else {
        idunico = 'USER' + idunico;
      }
      $(".textcode").html(idunico);
      //carga ajax
      let datosform = new FormData();
      datosform.append('rol','Jefe de llamadas');
      $.ajax({
        data: datosform,
        processData: false,
        contentType: false,
        method: 'POST',
        url: "{{ route('users.getmetallamadas') }}",
        success: function (resultado){
          console.log(resultado)
          //console.log(resultado.html['user_id'])
        }
      });

    });

    $(document).on("submit", "#formasignarmetallamada", function (evento) {
      evento.preventDefault();
      var formData = $("#formasignarmetallamada").serialize();
      $.ajax({
        type: 'POST',
        url: "{{ route('users.asignarmetallamadaPost') }}",
        data: formData
      }).done(function (data) {
        $("#modal-asignarmetallamada").modal("hide");
        Swal.fire(
          'Meta asignado correctamente',
          '',
          'success'
        )
        $('#tablaPrincipal').DataTable().ajax.reload();
      });
    });

    $('#modal-asignarjefellamadas').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var idunico = button.data('jefellamadas')
      $("#hiddenIdjefellamadas").val(idunico);
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

    $(document).on("submit", "#formasesor", function (evento) {
      evento.preventDefault();
      //var form=FormData();
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
      });


    });

    $(document).on("submit", "#formjefellamadas", function (evento) {
      evento.preventDefault();
      console.log("asd")
      //var form=FormData();
      var formData = $("#formjefellamadas").serialize();

      $.ajax({
        type:'POST',
        url:"{{ route('users.asignarjefellamadaspost') }}",
        data:formData,
      }).done(function (data) {
        Swal.fire(
          'Usuario asignado correctamente',
          '',
          'success'
        )
        $("#modal-asignarjefellamadas").modal("hide");
        $('#tablaPrincipal').DataTable().ajax.reload();

      });

    });

    $('#tablaPrincipal').DataTable({
        processing: true,
        stateSave:true,
		serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('users.llamadastabla') }}",
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
                data: 'supervisor',
                name: 'supervisor',
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
