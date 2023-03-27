@extends('adminlte::page')
{{-- @extends('layouts.admin') --}}

@section('title', 'Dashboard')
@push('css')
    <style>
        .card {
            background: rgb(241 241 241 / 80%);
        }
        .h-40{
            height: 40px !important;
        }

        .h-60{
            height: 60px !important;
        }

        @media screen and (max-width: 992px){
            .responsive-table{
                display: flex !important;
                flex-wrap: wrap !important;
            }
            .h-50-res{
                height: 50px !important;
            }
        }
        @media screen and (max-width: 767px){
            .table-total{
                overflow: hidden !important;
            }
            .scrollbar-x{
                overflow-x: scroll !important;
            }
        }
        #spinner {
            display: none;
        }

    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
@section('content_header')
    <div><h1>Dashboard</h1>
        <!-- Right navbar links -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!--ADMINISTRADOR-->
@stop

@section('content')


    <div class="container-fluid">
        @if(Auth::user()->rol == 'Administrador')
            @include('dashboard.partials.vista_administrador')
        @elseif(Auth::user()->rol == 'PRESENTACION')
            @include('dashboard.partials.vista_presentacion')
        @elseif(Auth::user()->rol == 'Apoyo administrativo')
            @include('dashboard.partials.apoyo_administrativo')
        @elseif (Auth::user()->rol == 'Encargado')
            @include('dashboard.partials.vista_encargado')
        @elseif (Auth::user()->rol =='FORMACIÓN')
            @include('dashboard.partials.vista_formacion')
        @elseif (Auth::user()->rol == 'Asesor')
            @include('dashboard.partials.vista_asesor')
        @elseif (Auth::user()->rol == 'Operacion')
            @include('dashboard.partials.vista_operacion')
        @elseif (Auth::user()->rol == 'Jefe de operaciones')
            @include('dashboard.partials.vista_jefeoperacion')
        @elseif (Auth::user()->rol == 'Administracion')
            @include('dashboard.partials.vista_administracion')
        @elseif (Auth::user()->rol == 'Jefe de llamadas')
            @include('dashboard.partials.vista_jefe_llamadas')
        @elseif (Auth::user()->rol == 'Llamadas')
            @include('dashboard.partials.vista_llamadas')
        @elseif (Auth::user()->rol == 'COBRANZAS')
            @include('dashboard.partials.vista_cobranzas')
        @elseif (Auth::user()->rol == 'Logística')
            @include('dashboard.partials.vista_logistica')
        @else
            @include('dashboard.partials.vista_otros')
        @endif
    </div>


@stop

@push('css')
    <style>
        .content-header {
            background-color: white !important;
        }

        .content {
            background-color: white !important;
        }
        canvas {
            max-width: 768px;
            margin: 40px auto;
        }
    </style>
@endpush

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()

        })
    </script>

    @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_ENCARGADO,\App\Models\User::ROL_ASESOR]))
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript"
                src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
        <script>
            $(function () {

                $('#datepickerDashborad').change(function (e) {
                    const value = e.target.value;
                    console.log(value)
                    if (value) {
                        window.location.replace('{{route('dashboard.index')}}?selected_date=' + value)
                    }
                })

            });
        </script>
    @endif

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#buttom_search_cliente_clear").click(function () {
                $("#search_content_result").html('');
                $("#input_search_cliente").val('');
            });
            $("#input_search_type").on("change", function () {
                $("#search_content_result").html('');
                $("#input_search_cliente").val('');
            })

            $("#buttom_search_cliente").click(function () { console.log('U- buttom_search_cliente');
                var tipo = $("#input_search_type").val()

                if (!document.getElementById("input_search_cliente").value) {
                    Swal.fire(
                        'El campo de texto del buscador esta vacio, ingrese valores para poder buscar',
                        '',
                        'warning'
                    )
                    return;
                }
                if (tipo == "CLIENTE") {
                    $.ajax({
                        url: "{{route('dashboard.search-cliente')}}",
                        data: {q: document.getElementById("input_search_cliente").value},
                        context: document.body
                    }).done(function (a) {
                        console.log(a)
                        $("#search_content_result").html(a);
                    });
                } else if (tipo == "RUC") {
                    $.ajax({
                        url: "{{route('dashboard.search-ruc')}}",
                        data: {
                            q: document.getElementById("input_search_cliente").value
                        },
                        context: document.body
                    }).done(function (a) {
                        console.log(a)
                        $("#search_content_result").html(a);
                    });
                }
            })

        });
    </script>

@endsection



