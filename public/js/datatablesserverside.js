$(document).ready(function () {


            // init datatable.
    $('#tablaserverside').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('basefriatabla') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'nombre', name: 'nombre'},
            {data: 'celular', name: 'celular'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    /*var dataTable = $('#tablaPrincipal').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 5,
        // scrollX: true,
        "order": [[ 0, "desc" ]],
        ajax: {
            url: "{{route('basefrialista')}}",
                data: function (d) {
                d.celular = $('input[name=celular]').val();
                //d.userid = $('input[name=userid]').val();
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'nombre', name: 'nombre'},
            {data: 'celular', name: 'celular'},
            {data: 'Actions', name: 'Actions',orderable:false,serachable:false,sClass:'text-center'},
        ]
    });*/
      

  /*$('#tablaPrincipal').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{route('basefria')}}",
            dataType: 'json',
            type: "POST",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre',
                },
                {
                    data: 'celular',
                    name: 'celular'
                },
                {
                    data: 'estado',
                    name: 'estado'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'identificador',
                    name: 'identificador'
                },
                /*{
                    data: 'phone_number',
                    name: 'phone_number',
                },*/
                /*{
                    data: 'salary',
                    name: 'salary',
                    searchable: false,
                    orderable: false
                },*/
                /*{
                    data: 'actions',
                    name: 'actions',
                    searchable: false,
                    orderable: false
                }*/
           /* ],
        })*/

});