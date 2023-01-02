@extends('adminlte::page')
{{-- @extends('layouts.admin') --}}

@section('title', 'Dashboard')
@push('css')
    <style>
        .card {
            background: rgb(241 241 241 / 80%);
        }
    </style>
@endpush
@section('content_header')
    <div><h1>Dashboard</h1>
        <!-- Right navbar links -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!--ADMINISTRADOR-->
    <script type="text/javascript">
        /*google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            console.log("1454588");
            var data = new google.visualization.arrayToDataTable([
                ['Asesores', 'Pedidos'],
                    @foreach ($pedidosxasesor as $vxa)
        ['{{ $vxa->users }}', {{ $vxa->pedidos }}],
                @endforeach
        ]);

        var options = {
            chart: {
                title: 'PEDIDOS DEL MES DE TODOS LOS ASESORES',
                subtitle: 'PEDIDO/ASESOR'
            }
        };

        var chart = new google.charts.Bar(document.getElementById('pedidosxasesor'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }*/
    </script>

    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBasic);


        function drawBasic() {
            var data = new google.visualization.arrayToDataTable([
                ['Cobranza', 'Pedidos'],
                    @foreach ($cobranzaxmes as $vxax)
                ['{{ $vxax->usuarios }}', {{ $vxax->total }}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'COBRANZA POR MES',
                    subtitle: 'COBRANZA/PEDIDOS'
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('cobranzaxmes'));
            chart.draw(data, options);
        };
    </script>
    <!--<script type="text/javascript">
      google.charts.load('current', {
        'packages': ['bar']
      });
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Cobranza', 'Pedidos'],
          @foreach ($cobranzaxmes as $vxax)
        ['{{ $vxax->users }}', {{ $vxax->total }}],


    @endforeach
    ]);

    var options = {
      chart: {
        title: 'COBRANZA POR MES',
        subtitle: 'COBRANZA/PEDIDOS'
      }
    };

    var chart = new google.charts.Bar(document.getElementById('cobranzaxmes'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
  };
</script>-->
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Clientes', 'Monto'],
                    @foreach ($pagosxmes as $pxm)
                ['{{ $pxm->cliente }}', {{ $pxm->pagos }}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'MONTO DE PEDIDO POR CLIENTE EN EL MES',
                    subtitle: 'TOP 30',
                }
            };

            var chart = new google.charts.Bar(document.getElementById('pagosxmes'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
    <script type="text/javascript">
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Asesor', 'Pedidos por día'],
                    @foreach($pedidosxasesorxdia as $pxad)
                ['{{$pxad->users}}', {{$pxad->pedidos}}],
                @endforeach
            ]);

            var options = {
                title: '',
                is3D: true,
            };

            if (document.getElementById('pedidosxasesorxdia')) {
                var chart = new google.visualization.PieChart(document.getElementById('pedidosxasesorxdia'));
                chart.draw(data, options);
            }
        }
    </script>
    <!--ENCARGADO-->

    {{--
        <script type="text/javascript">
            google.charts.load('current', {
                'packages': ['bar']
            });
            google.charts.setOnLoadCallback(drawStuff);

            function drawStuff() {
                var data = new google.visualization.arrayToDataTable([
                    ['Asesores', 'Pedidos'],
                        @foreach ($pedidosxasesor_3meses_encargado as $vxa)
                    ['{{ $vxa->users }} - {{ $vxa->fecha }}', {{ $vxa->pedidos }}],
                    @endforeach
                ]);

                var options = {
                    chart: {
                        title: 'HISTORIAL DE PEDIDOS DE LOS ULTIMOS 3 MESES DE MIS ASESORES',
                        subtitle: 'PEDIDO/ASESOR'
                    }
                };

                if (document.getElementById('pedidosxasesor_3meses_encargado')) {
                    var chart = new google.charts.Bar(document.getElementById('pedidosxasesor_3meses_encargado'));
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                }
            };
        </script>


        <script type="text/javascript">
            google.charts.load('current', {
                'packages': ['bar']
            });
            google.charts.setOnLoadCallback(drawStuff);

            function drawStuff() {
                var data = new google.visualization.arrayToDataTable([
                    ['Asesores', 'Pedidos'],
                        @foreach ($pedidosxasesor_encargado as $vxa)
                    ['{{ $vxa->users }}', {{ $vxa->pedidos }}],
                    @endforeach
                ]);

                var options = {
                    chart: {
                        title: 'PEDIDOS DEL MES DE MIS ASESORES',
                        subtitle: 'PEDIDO/ASESOR'
                    }
                };

                if (document.getElementById('pedidosxasesor_encargado')) {
                    var chart = new google.charts.Bar(document.getElementById('pedidosxasesor_encargado'));
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                }
            };
        </script>



    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Clientes', 'Monto'],
                    @foreach ($pagosxmes_encargado as $pxm)
                ['{{ $pxm->cliente }}', {{ $pxm->pagos }}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'MONTO DE PAGOS POR CLIENTE DE MIS ASESORES EN EL MES',
                    subtitle: 'TOP 30',
                }
            };

            if (document.getElementById('pagosxmes_encargado')) {
                var chart = new google.charts.Bar(document.getElementById('pagosxmes_encargado'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        }
    </script>

    <script type="text/javascript">
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Asesor', 'Pedidos por día'],
                    @foreach($pedidosxasesorxdia_encargado as $pxad)
                ['{{$pxad->users}}', {{$pxad->pedidos}}],
                @endforeach
            ]);

            var options = {
                title: '',
                is3D: true,
            };

            if (document.getElementById('pedidosxasesorxdia_encargado')) {
                var chart = new google.visualization.PieChart(document.getElementById('pedidosxasesorxdia_encargado'));
                chart.draw(data, options);
            }
        }
    </script>

    <!--ASESOR-->
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            var data = new google.visualization.arrayToDataTable([
                ['Fecha', 'Pedidos'],
                    @foreach ($pedidosxasesorxdia_asesor as $vxa)
                ['{{ $vxa->fecha }}', {{ $vxa->pedidos }}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'HISTORIAL DE MIS PEDIDOS EN EL MES',
                    subtitle: 'PEDIDO/ASESOR'
                }
            };

            if (document.getElementById('mispedidosxasesorxdia')) {
                var chart = new google.charts.Bar(document.getElementById('mispedidosxasesorxdia'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        };
    </script>--}}
@stop

@section('content')
    <div class="container-fluid">
        @if(Auth::user()->rol == 'Administrador')
            @include('dashboard.partials.vista_administrador')
        @elseif(Auth::user()->rol == 'Apoyo administrativo')
            @include('dashboard.partials.apoyo_administrativo')
        @elseif (Auth::user()->rol == 'Encargado')
            @include('dashboard.partials.vista_encargado')
        @elseif (Auth::user()->rol == 'Asesor')
            @include('dashboard.partials.vista_asesor')
        @elseif (Auth::user()->rol == 'Operacion')
            @include('dashboard.partials.vista_operacion')
        @elseif (Auth::user()->rol == 'Jefe de operaciones')
            @include('dashboard.partials.vista_jefeoperacion')
        @elseif (Auth::user()->rol == 'Administracion')
            @include('dashboard.partials.vista_administracion')
        @elseif (Auth::user()->rol == 'Jefe de llamadas')
            @include('dashboard.partials.vista_llamadas')
        @elseif (Auth::user()->rol == 'Llamadas')
            @include('dashboard.partials.vista_llamadas')
        @elseif (Auth::user()->rol == 'Logística')
            @include('dashboard.partials.vista_logistica')
        @elseif (Auth::user()->rol == \App\Models\User::ROL_FORMACION)
            @include('dashboard.partials.formacion')
        @else
            @include('dashboard.partials.vista_otros')
        @endif
    </div>
@stop

@section('css')
    <style>
        .content-header {
            background-color: white !important;
        }

        .content {
            background-color: white !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    @if (!$pedidossinpagos == null)
        <script>
            $('#staticBackdrop').modal('show')
        </script>
    @endif

    <script>
        // CARGAR PEDIDOS DE CLIENTE SELECCIONADO
        {{--
        window.onload = function () {
          $.ajax({
            url: "{{ route('notifications.getpedidosatender') }}",
            method: 'GET',
            success: function(data) {

              //$('#my-notification').html(data.html);
            }
          });
        };
        --}}
    </script>
    <script>
        (function () {

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
            $("#buttom_search_cliente").click(function () {
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
        })()
    </script>
@endsection
@push('js')
    @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_ENCARGADO,\App\Models\User::ROL_ASESOR]))
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript"
                src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
        <script>
            $(function () {
                {{--
                //const startYear = moment().startOf('year');
                //const momentStart = moment('{{request('start_date',now()->startOfMonth()->format('Y-m-d'))}}', 'YYYY-MM-DD');
                //const momentEnd = moment('{{request('end_date',now()->endOfMonth()->format('Y-m-d'))}}', 'YYYY-MM-DD');
                //$('#datepickerDashborad').val(momentStart.format('DD/MM/YYYY') + ' - ' + momentEnd.format('DD/MM/YYYY'))
                --}}
                $('#datepickerDashborad').change(function (e) {
                    const value = e.target.value;
                    console.log(value)
                    if (value) {
                        window.location.replace('{{route('dashboard.index')}}?selected_date=' + value)
                    }
                })
                {{--
                  /*$('#datepickerDashborad').daterangepicker({
                    "alwaysShowCalendars": false,
                    "showDropdowns": false,
                    "startDate": momentStart.format('DD/MM/YYYY'),
                    "endDate": momentEnd.format('DD/MM/YYYY'),
                    "autoUpdateInput": false,
                    ranges: {
                    'Enero': [startYear.clone(), startYear.clone().endOf('month')],
                    'Febrero': [startYear.clone().add(1, 'month'), startYear.clone().add(1, 'month').endOf('month')],
                    'Marzo': [startYear.clone().add(2, 'month'), startYear.clone().add(2, 'month').endOf('month')],
                    "Abril": [startYear.clone().add(3, 'month'), startYear.clone().add(3, 'month').endOf('month')],
                    "Mayo": [startYear.clone().add(4, 'month'), startYear.clone().add(4, 'month').endOf('month')],
                    "Junio": [startYear.clone().add(5, 'month'), startYear.clone().add(5, 'month').endOf('month')],
                    "Julio": [startYear.clone().add(6, 'month'), startYear.clone().add(6, 'month').endOf('month')],
                    "Agosto": [startYear.clone().add(7, 'month'), startYear.clone().add(7, 'month').endOf('month')],
                    "Septiembre": [startYear.clone().add(8, 'month'), startYear.clone().add(8, 'month').endOf('month')],
                    "Octubre": [startYear.clone().add(9, 'month'), startYear.clone().add(9, 'month').endOf('month')],
                    "Noviembre": [startYear.clone().add(10, 'month'), startYear.clone().add(10, 'month').endOf('month')],
                    "Diciembre": [startYear.clone().add(11, 'month'), startYear.clone().add(11, 'month').endOf('month')],
                    /*'Hoy': [moment(), moment()],
                    'El dia de ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Los últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Los últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                    "locale": {
                        "format": "DD/MM/YYYY",
                        "separator": " - ",
                        "applyLabel": "Aplicar",
                        "cancelLabel": "Cancelar",
                        "fromLabel": "De",
                        "toLabel": "Hasta",
                        "customRangeLabel": "Custom",
                        "weekLabel": "W",
                        "daysOfWeek": [
                            "Do",
                            "Lu",
                            "Ma",
                            "Mi",
                            "Ju",
                            "Vi",
                            "Sa"
                        ],
                        "monthNames": [
                            "Enero",
                            "Febrero",
                            "Marzo",
                            "Abril",
                            "Mayo",
                            "Junio",
                            "Julio",
                            "Agosto",
                            "Septiembre",
                            "Octubre",
                            "Noviembre",
                            "Diciembre"
                        ],
                        "firstDay": 1
                    },
                }, function (start, end, label) {
                    window.location.replace('{{route('dashboard.index')}}?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD'))
                });*/
                 --}}
            });
        </script>
    @endif
@endpush


