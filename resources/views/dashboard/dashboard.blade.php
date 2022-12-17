@extends('adminlte::page')
{{-- @extends('layouts.admin') --}}

@section('title', 'Dashboard')

@section('content_header')
    <div><h1>Dashboard</h1>
        <!-- Right navbar links -->
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!--ADMINISTRADOR-->
    <script type="text/javascript">
        google.charts.load('current', {
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
        }
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

            var chart = new google.visualization.PieChart(document.getElementById('pedidosxasesorxdia'));
            chart.draw(data, options);
        }
    </script>
    <!--ENCARGADO-->
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

            var chart = new google.charts.Bar(document.getElementById('pedidosxasesor_3meses_encargado'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
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

            var chart = new google.charts.Bar(document.getElementById('pedidosxasesor_encargado'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
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

            var chart = new google.charts.Bar(document.getElementById('pagosxmes_encargado'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
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

            var chart = new google.visualization.PieChart(document.getElementById('pedidosxasesorxdia_encargado'));
            chart.draw(data, options);
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

            var chart = new google.charts.Bar(document.getElementById('mispedidosxasesorxdia'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        };
    </script>
@stop

@section('content')
    @if(Auth::user()->rol == 'Administrador')
        <div style="text-align: center; font-family:'Times New Roman', Times, serif">
            <h2>
                <p>Bienvenido <b>{{ Auth::user()->name }}</b> al software empresarial de sisFacturas, eres el
                    <b>{{ Auth::user()->rol }} del sistema</b></p>
            </h2>
        </div>
        <br>
        <br>
        <div class="container-fluid">
            <div class="row" style="color: #fff">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            @foreach ($pedidoxmes_total as $mpxm)
                                <h3>{{ $mpxm->total  }}</h3>
                            @endforeach
                            <p>META DE PEDIDOS DEL MES</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            @foreach ($montopedidoxmes_total as $mcxm)
                                <h3>@php echo number_format( ($mcxm->total)/10 ,2) @endphp %</h3>
                            @endforeach
                            <p>META DE COBRANZAS DEL MES</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            @foreach ($pagoxmes_total as $pxm)
                                <h3>{{ $pxm->pedidos }}</h3>
                            @endforeach
                            <p>PEDIDOS DEL MES</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @foreach ($montopagoxmes_total as $cxm)
                                <h3>S/@php echo number_format( ($cxm->total)/1000 ,2) @endphp </h3>
                            @endforeach
                            <p>COBRANZAS DEL MES</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="container-fluid">
        @if(Auth::user()->rol == 'Administrador')
            @include('dashboard.partials.vista_administrador')
        @elseif (Auth::user()->rol == 'Encargado')
            @include('dashboard.partials.vista_encargado')
        @elseif (Auth::user()->rol == 'Asesor')
            @include('dashboard.partials.vista_asesor')
        @elseif (Auth::user()->rol == 'Operacion')
            @include('dashboard.partials.vista_operacion')
        @elseif (Auth::user()->rol == 'Administracion')
            @include('dashboard.partials.vista_administracion')
        @elseif (Auth::user()->rol == 'Logística')
            @include('dashboard.partials.vista_logistica')
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
    <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    @if (!$pedidossinpagos == null)
        <script>
            $('#staticBackdrop').modal('show')
        </script>
    @endif

    {{-- <script>
      // CARGAR PEDIDOS DE CLIENTE SELECCIONADO
      window.onload = function () {
        $.ajax({
          url: "{{ route('notifications.get') }}"
          method: 'GET',
          success: function(data) {
            $('#my-notification').html(data.html);
          }
        });
      };
    </script> --}}
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
