@extends('adminlte::page')

@section('title', 'Pagina sin Accesso')
@section('content_header')
    {{--<div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Calendar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
                </ol>
            </div>
        </div>
    </div>--}}
@stop

@push('css')
    <style>
        a, a:hover{
            color:#333
        }
    </style>
@endpush

@section('content')

   {{-- <div class="container ">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card p-3 mt-10">
                    <div class="mt-4">
                        <div class="mt-2">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>--}}

    <section id="cover">
        <div id="cover-caption">
            <div id="container" class="container">
                <div class="row">
                    <div class="col-sm-10 offset-sm-1 text-center">
                        <div class="info-form">
                            <form id="formulario" class="mt-60">
                                <div class="form-group">

                                    <label class="float-left">Contraseña de accesso</label>
                                    <div class="input-group mb-3">
                                        <input class="form-control" type="password" id="clave">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit" id="acceder">Acceder</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js')
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src=" {{asset('plugins/moment/moment.min.js')}}"></script>

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_password input').attr("type") == "text"){
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass( "fa-eye-slash" );
                    $('#show_hide_password i').removeClass( "fa-eye" );
                }else if($('#show_hide_password input').attr("type") == "password"){
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass( "fa-eye-slash" );
                    $('#show_hide_password i').addClass( "fa-eye" );
                }
            });

            $(document).on('keypress','#clave',function(e) {
                if(e.which == 13)
                {
                    $("#formulario").trigger('submit');
                }
            });

            $(document).on("submit", "#formulario", function (event) {
                //validar clave
                event.preventDefault();

                let formData = new FormData();
                let clave=$('#clave').val();
                if(clave=='')
                {
                    Swal.fire('Error', 'Debe ingresar una contraseña.', 'error');
                    return false;
                }
                formData.append('clave',clave);
                formData.append('type', 'load');
                $.ajax({
                    url: "{{ route('agenda_token') }}",
                    type: 'POST',
                    dataType:'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if(res=='1')
                        {
                            window.location = "/fullcalendar";
                        }
                        else
                        {
                            Swal.fire('Error', 'Debe ingresar una contraseña correcta.', 'error');
                        }


                    }
                });


            })

        });
    </script>
@endsection
