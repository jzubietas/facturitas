@yield('css-datatables')

{{-- BIENVENIDA --}}
<div class="text-center mb-4" style="font-family:'Times New Roman', Times, serif">
    <h2>
        <p>
            Bienvenido <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, eres el
            <b>{{ Auth::user()->rol }} del sistema</b>
        </p>
    </h2>
</div>

{{-- INFO --}}
<div class="row d-none">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6 d-none">
        <div class="small-box bg-success">
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6 d-none">
        <div class="small-box bg-warning">

            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6 d-none">
        <div class="small-box bg-default">

            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6 d-none">
        <div class="small-box bg-danger">
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-9 col-12">
        {{--@include('dashboard.widgets.pedidos_creados')--}}
    </div>
</div>

{{-- BUSCAR / QUITAR VIDA --}}
<div class="row mb-3">
    @include('dashboard.widgets.buscar_cliente')
    @include('dashboard.partials.vista_quitar_vidas')
</div>

{{-- LLAMADA DE ATENCION --}}
<div class="col-md-12">
    <x-tabla-list-llamada-atencion></x-tabla-list-llamada-atencion>
</div>
<div class="col-lg-12">
    <x-common-activar-cliente-por-tiempo></x-common-activar-cliente-por-tiempo>
</div>

{{-- PEDIDOS PENDIENTES/ELECTRONICOS/ANULACION --}}
<div class="col-lg-12">
    <x-grafico-pedidos-elect-fisico></x-grafico-pedidos-elect-fisico>
</div>

<!-- MODAL -->
{{--<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img alt="Dia de la mujer" src="{{ asset('/img/diaMujer.jpg') }}" style="width: 100%">
            </div>
        </div>
    </div>
</div>--}}

{{-- FULLSCREEN --}}
<div class="col-lg-12 " id="contenedor-fullscreen">
    <div class="d-flex justify-content-center flex-column mb-2">
        <div class="d-flex justify-content-center">
            <h1 class="text-uppercase justify-center text-center">Metas del mes
                de {{\Carbon\Carbon::now()->startOfMonth()->translatedFormat('F')}}</h1>
            <button style="background: none; border: none" onclick="openFullscreen();">
                <i class="fas fa-expand-arrows-alt ml-3"
                   style="font-size: 20px"></i>
            </button>
        </div>
        <div class="d-flex justify-content-center align-items-center ml-5">
            <label class="p-0 m-0" for="ingresar">Fecha: </label>
            <input type="date" id="fechametames" class="border-0 ml-3"
                   value="{{\Carbon\Carbon::now()->startOfDay()->format('Y-m-d')}}">
        </div>
    </div>

    {{-- TABLA DUAL --}}
    <div class="" style=" overflow: hidden !important;">
        <div class=" " style=" overflow-x: scroll !important; overflow-y: scroll !important;">
            <div class="row">
                <div class="contain-table-dual">
                    <div class="col-lg-6" id="meta"></div>
                    <div class="col-lg-6" id="metas_dp"></div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div id="supervisor_total"></div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div id="supervisor_A"></div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div id="supervisor_B"></div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div id="metas_total"></div>
                </div>

            </div>

        </div>
    </div>
    {{-- FIN-TABLA-DUAL --}}

</div>

<br>

{{-- METAS ASESOR DE LLAMADAS --}}
<div class="container-fluid">
    <div class="col-md-12">
        <div class="d-flex justify-content-center">
            <h1 class="text-uppercase justify-center text-center">Metas Asesores de Llamadas</h1>
        </div>
        <div id="metas_situacion_clientes"></div>
    </div>
</div>

{{-- METAS COBRANZA --}}
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card bg-cyan">
            <div class="card-header">
                <h1 class="text-uppercase justify-center text-center">Metas Cobranzas</h1>
            </div>
            <div class="card-body">
                <div id="metas_cobranzas_general"></div>
            </div>
            <div class="card-fotter"></div>
        </div>

    </div>
</div>

{{-- SPARKLINE --}}
<div class="conatiner-fluid">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title text-uppercase">Pedidos actuales por día</h3>
                    {{--<a href="javascript:void(0);">View Report</a>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">820</span>
                        <span>Cantidad de pedidos del día</span>
                    </p>
                    {{--
                    <p class="ml-auto d-flex flex-column text-right">
                                            <span class="text-success">
                                                <i class="fas fa-arrow-up"></i> 12.5%
                                            </span>
                                            <span class="text-muted">Since last week</span>
                                        </p>
                    --}}
                </div>

                <div class="position-relative mb-4">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="visitors-chart" style="display: block; width: 764px; height: 200px;"
                            class="chartjs-render-monitor" width="764" height="200"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2 text-uppercase">
                        <i class="fas fa-square text-primary"></i> #{{\Carbon\Carbon::now()->monthName}}
                    </span>
                    <span class="text-uppercase">
                        <i class="fas fa-square text-gray"></i> #{{\Carbon\Carbon::now()->subMonth()->monthName}}

                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<br>




@section('js-datatables')
    <script>
        $(".animated-progress span").each(function () {
            $(this).animate(
                {
                    width: $(this).attr("data-progress") + "%",
                },
                1000
            );
            $(this).text($(this).attr("data-progress") + "%");
        });
    </script>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#exampleModalCenter').modal('show');


            $('#fechametames').val("{{\Carbon\Carbon::parse($fechametames)->format('Y-m-d')}}");

            $(document).on('change', '#fechametames', function () {
                //const value = e.target.value;
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);
                cargaNueva(4);
                cargaNueva(5);

                cargReporteMetasCobranzasGeneral();


            });

            window.cargaNueva = function (entero) {
                console.log(' ' + entero)
                var fd = new FormData();
                fd.append('fechametames', $('#fechametames').val());
                fd.append('ii', entero);
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    url: "{{ route('dashboard.viewMetaTable') }}",
                    success: function (resultado) {
                        if (entero == 1) {
                            $('#metas_dp').html(resultado);
                        } else if (entero == 2) {
                            $('#meta').html(resultado);
                        } else if (entero == 3) {
                            $('#metas_total').html(resultado);
                        } else if (entero == 4) {
                            $('#supervisor_total').html(resultado);
                        } else if (entero == 5) {
                            $('#supervisor_A').html(resultado);
                        }
                    }
                })
            }


            window.cargReporteAnalisis = function () {
                var fd = new FormData();
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    url: "{{ route('dashboard.viewAnalisis') }}",
                    success: function (resultado) {
                        $('#reporteanalisis').html(resultado);
                    }
                })
            }

            window.cargReporteMetasSituacionClientes = function () {
                var fd = new FormData();
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    url: "{{ route('dashboard.graficoSituacionClientes') }}",
                    success: function (resultado) {
                        $('#metas_situacion_clientes').html(resultado);
                    }
                })
            }

            window.cargReporteMetasCobranzasGeneral = function () {
                var fd = new FormData();
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    url: "{{ route('dashboard.graficoCobranzasGeneral') }}",
                    success: function (resultado) {
                        $('#metas_cobranzas_general').html(resultado);
                    }
                })
            }

            cargaNueva(1);
            cargaNueva(2);
            cargaNueva(3);
            cargaNueva(4);
            cargaNueva(5);
            cargReporteAnalisis();
            cargReporteMetasSituacionClientes();
            cargReporteMetasCobranzasGeneral();

            setInterval(myTimer, 5000);

            function myTimer() {
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);
                cargaNueva(4);
                cargaNueva(5);
                cargReporteMetasCobranzasGeneral();
            }

            $('a[href$="#myModal"]').on("click", function () {
                $('#myModal').modal();
            });

            var elem = document.querySelector("#contenedor-fullscreen");
            window.openFullscreen = function () {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) { /* Safari */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE11 */
                    elem.msRequestFullscreen();
                }
            }
        });
    </script>

    <script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script>
    <script>
        $(function () {
            var ticksStyle = {fontColor: '#495057', fontStyle: 'bold'}
            var mode = 'index'
            var intersect = true

            var $visitorsChart = $('#visitors-chart')
            var visitorsChart = new Chart($visitorsChart, {
                data: {
                    /*eje x: dias*/
                    labels: $arrr,
                    datasets: [{
                        /*azul*/
                        type: 'line',
                        data: [100,200,300],
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                    }, {
                        /*plomo*/
                        type: 'line',
                        data: [400,500,800],
                        backgroundColor: 'tansparent',
                        borderColor: '#ced4da',
                        pointBorderColor: '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill: false
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {mode: mode, intersect: intersect},
                    hover: {mode: mode, intersect: intersect},
                    legend: {display: false},
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            }, ticks: $.extend({beginAtZero: true, suggestedMax: 250}, ticksStyle)
                        }], xAxes: [{display: true, gridLines: {display: false}, ticks: ticksStyle}]
                    }
                }
            })
        })
    </script>
@endsection
