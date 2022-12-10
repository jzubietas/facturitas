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
        };
    </script>


    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            console.log("1454588");
            var data = new google.visualization.arrayToDataTable([
                ['Mes', 'Total'],
                    @foreach ($pedidos_mes_ as $vxa)
                ['Diciembre', {{ $vxa->total }}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'PEDIDOS DEL MES',
                    subtitle: 'PEDIDOS/MES'
                }
            };

            var chart = new google.charts.Bar(document.getElementById('pedidos-subiendo-mes'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        };
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">

                    <div class="card">
                        <div class="card-header">Buscar Cliente</div>
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input id="input_search_cliente" class="form-control" placeholder="Buscar cliente">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-light btn-block" id="buttom_search_cliente">
                                        <i class="fa fa-search"></i>
                                        Buscar Cliente
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input id="input_search_ruc" class="form-control" placeholder="Buscar Ruc">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-light btn-block" id="buttom_search_ruc">
                                        <i class="fa fa-search"></i>
                                        Buscar Ruc
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="search_content_result">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <br>
                    <h4>CANTIDAD DIARIA DE PEDIDOS POR ASESOR</h4>

                    <table>
                        <span>Setiembre</span>
                        <thead>
                        <th>Asesor</th>
                        <th>Cantidad</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <table>
                        <span>Octubre</span>
                        <thead>
                        <th>Asesor</th>
                        <th>Cantidad</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <table>
                        <span>Noviembre</span>
                        <thead>
                        <th>Asesor</th>
                        <th>Cantidad</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>


                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover"><br>
                            <div id="pedidosxasesorxdia" style="width: 100%; height: 500px;"></div>
                        </table>


                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div class="chart tab-pane active" id="pedidosxasesor"
                                         style="width: 100%; height: 550px;">
                                    </div>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div class="chart tab-pane active" id="pedidos-subiendo-mes"
                                         style="width: 50%; height: 550px;">
                                    </div>
                                </table>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div class="chart tab-pane active" id="cobranzaxmes"
                                         style="width: 100%; height: 550px;">
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div id="pagosxmes" style="width: 100%; height: 550px;">
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- @include('dashboard.modal.alerta') --}}

            @elseif (Auth::user()->rol == 'Encargado')
                <div style="text-align: center; font-family:'Times New Roman', Times, serif">
                    <h2>
                        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de sisFacturas, donde
                            cumples la función de <b>{{ Auth::user()->rol }}</b></p>
                    </h2>
                </div>
                <br>
                <br>
                <div class="container-fluid">
                    <div class="row" style="color: #fff;">
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>@php echo number_format(Auth::user()->meta_pedido)@endphp</h3>
                                    <p>META DE PEDIDOS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    {{-- @foreach ($montoventadia as $mvd) --}}
                                    <h3>S/{{ Auth::user()->meta_cobro }}</h3>
                                    {{-- @endforeach --}}
                                    <p>META DE COBRANZAS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $meta_pedidoencargado }}</h3>
                                    <p>TUS PEDIDOS DEL MES</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>S/@php echo number_format( ($meta_pagoencargado->pagos)/1000 ,2) @endphp </h3>

                                    <p>MIS COBRANZAS DEL MES</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover"><br><h4>
                                        PEDIDOS DEL DIA POR ASESOR</h4>
                                    <div id="pedidosxasesorxdia_encargado" style="width: 100%; height: 500px;"></div>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <img src="imagenes/logo_facturas.png" alt="Logo" width="80%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div class="chart tab-pane active" id="pedidosxasesor_encargado"
                                         style="width: 100%; height: 550px;">
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div class="chart tab-pane active" id="pedidosxasesor_3meses_encargado"
                                         style="width: 100%; height: 550px;">
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <div id="pagosxmes_encargado" style="width: 100%; height: 550px;">
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif (Auth::user()->rol == 'Asesor')
                <div class="container-fluid">
                    <div class="row" style="text-align: center; font-family:Georgia, 'Times New Roman', Times, serif">
                        <div class="col-lg-9 col-9" style="margin-top:20px">
                            <h2>
                                <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de sisFacturas,
                                    donde cumples la función de <b>{{ Auth::user()->rol }}</b></p>
                            </h2>
                        </div>
                        <div class="col-lg-3 col-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $pagosobservados_cantidad }}</h3>
                                    <p>PAGOS OBSERVADOS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('pagos.pagosobservados') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="container-fluid">
                    <div class="row" style="color: #fff;">
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>@php echo number_format(Auth::user()->meta_pedido)@endphp</h3>
                                    <p>META DE PEDIDOS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('pedidos.mispedidos') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>S/{{ Auth::user()->meta_cobro }}</h3>
                                    <p>META DE COBRANZAS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('pagos.mispagos') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $meta_pedidoasesor }}</h3>
                                    <p>TUS PEDIDOS DEL MES</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('pedidos.mispedidos') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>S/@php echo number_format( ($meta_pagoasesor->pagos)/1000 ,2) @endphp </h3>
                                    <p>MIS COBRANZAS DEL MES</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('pagos.mispagos') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-condensed table-hover">
                                            <div class="chart tab-pane active" id="mispedidosxasesorxdia"
                                                 style="width: 100%; height: 550px;">
                                            </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <img src="imagenes/logo_facturas.png" alt="Logo" width="100%">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        </div>
                    </div>
                </div>
                @include('dashboard.modal.asesoralerta')

            @elseif (Auth::user()->rol == 'Operacion')
                <div style="text-align: center; font-family:'Times New Roman', Times, serif">
                    <h2>
                        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> del equipo de <b>OPERACIONES</b> al software
                            empresarial de sisFacturas</b></p>
                    </h2>
                </div>
                <br>
                <br>
                <div class="container-fluid">
                    <div class="row" style="color: #fff;">
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $pedidoxatender }}</h3>
                                    <p>PEDIDOS POR ATENDER</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('operaciones.poratender') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $pedidoenatencion }}</h3>
                                    <p>PEDIDOS EN PROCESO DE ATENCION</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('operaciones.enatencion') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <img src="imagenes/logo_facturas.png" alt="Logo" width="100%">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        </div>
                    </div>
                </div>
            @elseif (Auth::user()->rol == 'Administracion')
                <div style="text-align: center; font-family:'Times New Roman', Times, serif">
                    <h2>
                        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> del equipo de <b>ADMINISTRACION</b> al software
                            empresarial de sisFacturas</b></p>
                    </h2>
                </div>
                <br>
                <br>
                <div class="container-fluid">
                    <div class="row" style="color: #fff;">
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pagosxrevisar_administracion }}</h3>
                                    <p>PAGOS POR REVISAR</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('administracion.porrevisar') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $pagosobservados_administracion }}</h3>
                                    <p>PAGOS OBSERVADOS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('administracion.porrevisar') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                            <br>
                            <div class="table-responsive">
                                <img src="imagenes/logo_facturas.png" alt="Logo" width="100%">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        </div>
                    </div>
                </div>
            @elseif (Auth::user()->rol == 'Logística')

                <div style="text-align: center; font-family:'Times New Roman', Times, serif">
                    <h2>
                        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de sisFacturas</b></p>
                    </h2>
                </div>
                <br>
                <br>
                <div class="container-fluid">
                    <div class="row" style="color: #fff;">
                        <div class="col-lg-1 col-1">
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pagosxrevisar_administracion }}</h3>
                                    <p>Sobres por enviar</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('administracion.porrevisar') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-5">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $pagosobservados_administracion }}</h3>
                                    <p>Sobres por recibir</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('administracion.porrevisar') }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1">
                        </div>
                    </div>
                </div>

            @else
                <div style="text-align: center; font-family:'Times New Roman', Times, serif">
                    <h2>
                        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de sisFacturas</b></p>
                    </h2>
                </div>
                <br>
                <br>
                <div class="col-lg-12 col-12" style="text-align: center">
                    <img src="imagenes/logo_facturas.png" alt="Logo" width="50%">
                </div>
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
                    $("#buttom_search_cliente").click(function () {
                        $.ajax({
                            url: "{{route('dashboard.search-cliente')}}",
                            data:{q:document.getElementById("input_search_cliente").value},
                            context: document.body
                        }).done(function (a) {
                            console.log(a)
                            $("#search_content_result").html(a);
                        });
                    })
                    $("#buttom_search_ruc").click(function () {
                        $.ajax({
                            url: "{{route('dashboard.search-ruc')}}",
                            data:{
                                q:document.getElementById("input_search_ruc").value
                            },
                            context: document.body
                        }).done(function (a) {
                            console.log(a)
                            $("#search_content_result").html(a);
                        });
                    })
                })()
            </script>
        @stop
