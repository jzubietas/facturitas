@extends('adminlte::page')

@section('title', 'Lista de Mi Personal')

@section('content_header')
  <h1>Mi Personal - Asignacion de cargos
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
@include('usuarios.modal.historialpersonal')

  <div class="card">
    <div class="card-body">
      <table id="tablaPrincipal" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">CODIGO</th>
            <th scope="col">NOMBRES Y APELLIDOS</th>
            <th scope="col">CORREO</th>
            <th scope="col">CARGO</th>
            <th scope="col">ESTADO</th>
            <th scope="col">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->rol }}</td>
              <td>{{ $user->estado }}
              <td><a href="" data-target="#modal-historial-personal" data-toggle="modal" data-personal="{{ $user->id }}"><button class="btn btn-danger btn-sm">Ver Asignados</button></a>     
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
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
  <script src="{{ asset('js/datatables.js') }}"></script>
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
    var tablepersonal=null;
    $(document).ready(function() {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      

      //AGREGANDO MODAL
    $('#modal-historial-personal').on('show.bs.modal', function (event) {
       
        console.log("aa")   
        var button = $(event.relatedTarget) 
        var personal = button.data('personal')
 
        tablepersonal.destroy();
 
        tablepersonal=$('#tablaPrincipalPersonal').DataTable({
          "bPaginate": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
           "pageLength":5,
          "order": [[ 0, "asc" ]],
          'ajax': {
            url:"{{ route('personaltablahistorial') }}",					
            'data': { "personal":personal}, 
            "type": "get",
          },
          /*"search": {
             "search": id
           },*/
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
         {data: 'name', name: 'name'},
         {data: 'email',name: 'email'},
         {data: 'rol',name: 'rol'},
        
         //estado de pago
         {       
            data: 'estado', name: 'estado',
                  render: function ( data, type, row, meta ) {
                      if(data=="1")
                      {
                          return '<span>Activo</span>';
                      }else if(data=="0"){
                          return '<span>Inactivo</span>';
                      }
                  }
        }, 
         ],
          language: {
            "decimal": "",
            "emptyTable": "No hay informaciÃ³n",
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
      
      tablepersonal=$('#tablaPrincipalPersonal').DataTable({
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
        "length": 3,
        columns: 
        [
          {data: 'id' },
          {data: 'name'},
          {data: 'email'},
          {data: 'rol'},
          {data: 'estado'}
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay informaciÃ³n",
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
        }
      });
    });

   </script>  

  <script>
    //VALIDAR CAMPOS NUMERICO DE MONTO EN PAGOS

    $('input.number').keyup(function(event) {

    if(event.which >= 37 && event.which <= 40){
      event.preventDefault();
    }

    $(this).val(function(index, value) {
      return value
        .replace(/\D/g, "")
        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
      ;
    });
    });

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $('#tablaPrincipal').DataTable({
        processing: true,
        //responsive:true,
        //autowidth:true,
        serverSide: true,
        searching: true,
        "order": [[ 0, "desc" ]],
        ajax: "{{ route('indextablapersonal') }}",
        "createdRow": function( row, data, dataIndex){        
          },
        initComplete:function(settings,json){          
          
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
        {data: 'name', name: 'name'},
        {data: 'email', name: 'email'},
        {data: 'rol', name: 'rol'},
        {data: 'estado', name: 'estado',
                render: function ( data, type, row, meta ) {
                    if(data=="1")
                    {
                        return '<span class="badge badge-success">Activo</span>';
                    }else if(data=="0"){
                        return '<span class="badge badge-danger">Inactivo</span>';
                    }
                }
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
              "emptyTable": "No hay informaciÃ³n",
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
    $('#tablaPrincipal_filter label input').on('paste', function(e) {
      var pasteData = e.originalEvent.clipboardData.getData('text')
      localStorage.setItem("search_tabla",pasteData);
    });
    $(document).on("keypress",'#tablaPrincipal_filter label input',function(){      
      localStorage.setItem("search_tabla",$(this).val());
      console.log( "search_tabla es "+localStorage.getItem("search_tabla") );
    });


  </script>
@stop



