$(document).ready(function () {
   

  $('.basefria_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{route('basefria')}}",
            dataType: 'json',
            type: "GET",
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
            ],
        })

});