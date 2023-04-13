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

<hr>
{{-- FULLSCREEN --}}

<div id="spinner" class="d-none"><!--position-relative d-flex justify-content-center -->


    <div class="position-relative top-50 start-50 translate-middle">
        <img src="{{asset('images/drawing-2802.gif')}}" alt="Your Spinner" class=" spinner " width="700px">
    </div>
</div>


<div class="col-lg-12 bg-white" id="contenedor-fullscreen">
    <div class="d-flex justify-content-center flex-column mb-2 bg-white">
        <div class="d-flex justify-content-center row bg-white">

            <div class="card col-lg-3 col-md-3 col-sm-12 d-flex align-items-center order-change-1 ">
                <div class="card-body d-flex justify-content-center align-items-center" style="grid-gap: 20px">
                    <h5 class="card-title text-uppercase">Total de cobranzas :</h5>
                    <p id="porcentaje_cobranzas_metas" class="card-text font-weight-bold" style="font-size: 25px"> --%</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center align-items-center order-change-2 ">
                <h1 class="text-uppercase justify-center text-center h1-change-day" style="color: #FFFFFF;
background: #FFFFFF;
text-shadow: 2px 2px 0 #242120, 2px -2px 0 #242120, -2px 2px 0 #242120, -2px -2px 0 #242120, 2px 0px 0 #242120, 0px 2px 0 #242120, -2px 0px 0 #242120, 0px -2px 0 #242120;">Metas del mes
                    de {{\Carbon\Carbon::now()->startOfMonth()->translatedFormat('F')}}</h1>
                <button style="background: none; border: none" onclick="openFullscreen();">
                    <i class="fas fa-expand-arrows-alt ml-3"
                       style="font-size: 20px"></i>
                </button>
            </div>
            <div class="card col-lg-3 col-md-3 col-sm-12 d-flex align-items-center order-change-3">
                <div class="card-body d-flex justify-content-center align-items-center" style="grid-gap: 20px">
                    <h5 class="card-title text-uppercase">Total de pedidos:</h5>
                    <p id="porcentaje_pedidos_metas" class="card-text font-weight-bold" style="font-size: 25px"> --%</p>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-center align-items-center ml-5 bg-white">
            <label class="p-0 m-0" for="ingresar">Fecha: </label>
            <input type="text" id="fechametames" class="border-0 ml-3" name="fechametames"
                   value="" readonly>
            <button class="btn btn-success btn-md" id="fechametames-button">Fecha hoy</button>


        </div>
    </div>


    {{-- TABLA DUAL --}}
    <div class="" style=" overflow: hidden !important;">
        <div class=" " style=" overflow-x: scroll !important; overflow-y: scroll !important;">
            <div class="row">
                <div class="contain-table-dual row" style="width: 100% !important;">
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

<div class ="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <br><br>
            <h1 class="text-center">CLIENTES LEVANTADOS/CAIDOS (%)</h1>
        </div>
        <div class="contain-table-dual row" style="width: 100% !important;">
            <div class="col-lg-6" id="grafico_dejaronpedir_left"></div>
            <div class="col-lg-6" id="grafico_dejaronpedir_right"></div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="dejaronpedir_supervisor_A"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="dejaronpedir_supervisor_B"></div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="dejaronpedir_supervisor_total"></div>
        </div>

    </div>
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

{{-- SPARKLINE PEDIDOS ACTUALES POR DÍA --}}
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
                        <span class="text-bold text-lg">{{$asesor_pedido_dia}}</span>
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
                    <canvas id="visitors-chart" style="display: block; height: 200px; max-width: 100%; "
                            class="chartjs-render-monitor" height="200"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">

                    <span class="text-uppercase">
                        <i class="fas fa-square text-gray"></i> #{{\Carbon\Carbon::now()->subMonth()->monthName}}

                    </span>
                    <span class="mr-2 text-uppercase">
                        <i class="fas fa-square text-primary"></i> #{{\Carbon\Carbon::now()->monthName}}
                    </span>

                </div>
            </div>
        </div>
    </div>
</div>

<br>

{{-- SPARKLINE OLVA --}}
<div class="conatiner-fluid">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title text-uppercase">TOTAL RECAUDADO DE OLVA POR DÍA</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">{{$gasto_total_olva}}</span>
                        <span>Cantidad total del día</span>
                    </p>
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
                    <canvas id="visitors-chart-olva" style="display: block; height: 200px;  max-width: 100%;"
                            class="chartjs-render-monitor"  height="200"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2 text-uppercase">
                        <i class="fas fa-square" style="background: #17a2b8; color: #17a2b8"></i> #{{\Carbon\Carbon::now()->monthName}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="far fa-chart-bar"></i>
            Cuadro comparativo de Pedidos Anulados
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool disabled" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="my-chart-pedidosporasesorpar1"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="my-chart-pedidosporasesorpar2"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                        </div>

                    </div>
        <div class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="my-chart-pedidosporasesorpar1"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="my-chart-pedidosporasesorpar2"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<div class="container-fluid">
    <h1> Cuadro comparativo de Pedidos Anulados</h1>

</div>--}}

<div class="container-fluid">
    <canvas id="my-chart-dejaronpedir"  style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
</div>

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

    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('js/datepicker-es.js')}}" charset="UTF-8"></script>

    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('js/datepicker-es.js')}}" charset="UTF-8"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var date = new Date();
            var currentMonth = date.getMonth();
            var currentDate = date.getDate();
            var currentYear = date.getFullYear();

            $('#fechametames').datepicker({
                dateFormat: 'dd-mm-yy'
            });

            $("#fechametames-button").click(function() {
                //$("#fechametames").datepicker("show");

                //$('#fechametames').datepicker('setDate', new Date());
                $('#fechametames').datepicker('setDate', new Date());
                $('#fechametames').trigger('change');
            });

            $('#fechametames').datepicker('setDate', new Date());
            //console.log($('#fechametames').datepicker({ dateFormat: 'dd-mm-yy' }).val());

            $.get("{{ route('chart-pedidos-asesores') }}", function(data) {
                var ctxpedidosporasesor1 = document.getElementById('my-chart-pedidosporasesorpar1').getContext('2d');
                var chartpedidosporasesor1 = new Chart(ctxpedidosporasesor1, {
                    type: 'horizontalBar',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets,
                    },
                    options: {
                        responsive              : true,
                        aintainAspectRatio     : false,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                max: 100,
                                ticks: {
                                    beginAtZero: false,
                                    callback: function (value) {
                                        return value + '%';
                                    },
                                },
                            }],
                            yAxes: [{
                                stacked: true,
                            }]
                        },
                        plugins: {
                            datalabels: {
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: function(value, context) {
                                    return Math.round(value) + '%';
                                }
                            },
                        },
                    }
                });
            });

            $.get("{{ route('chart-pedidos-asesores-faltantes') }}", function(data) {
                var ctxpedidosporasesor2 = document.getElementById('my-chart-pedidosporasesorpar2').getContext('2d');
                var chartpedidosporasesor2 = new Chart(ctxpedidosporasesor2, {
                    type: 'horizontalBar',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets,
                    },
                    options: {
                        responsive              : true,
                        aintainAspectRatio     : false,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                max: 100,
                                ticks: {
                                    beginAtZero: false,
                                    callback: function (value) {
                                        return value + '%';
                                    },
                                },
                            }],
                            yAxes: [{
                                stacked: true,
                            }]
                        },
                        plugins: {
                            datalabels: {
                                color: 'red',
                                anchor: 'end',
                                align: 'end',
                                formatter: function(value, context) {
                                    console.log('aaaaaaaaaaa:',value,context)
                                    return value + '%';
                                }
                            },
                        },
                    }
                });
            });

            $.get("{{ route('chart-data') }}", function(data) {
                var ctxpedidosdejaronpedir = document.getElementById('my-chart-dejaronpedir').getContext('2d');
                var chartpedidosdejaronpedir = new Chart(ctxpedidosdejaronpedir, {
                    type: 'horizontalBar',
                    data: {
                        labels  : data.labels,
                        datasets: data.datasets
                    },
                    options: {
                        responsive              : true,
                        maintainAspectRatio     : false,
                        datasetFill             : false
                    }
                });
            });


            $('#exampleModalCenter').modal('show');

            $(document).on('change', '#fechametames', function () {
                //const value = e.target.value;
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);
                //cargaNueva(4);
                //cargaNueva(5);
                cargaNueva(6);//totales porcentajes arriba de metas cobranzas
                cargaNueva(7);//totales porcentajes arriba de metas pedidos

                cargaNueva(8);
                cargaNueva(9);

                cargReporteMetasCobranzasGeneral();

            });

            window.cargaNueva = function (entero) {
                console.log(' ' + entero)
                var fd = new FormData();
                //$('#fechametames').datepicker( "option", "dateFormat", "yy-mm-dd" );
                let valorr=$('#fechametames').val();
                var parts = valorr.split("-");
                valorr=parts[2]+'-'+parts[1]+'-'+parts[0]

                const ddd = new Date();
                ddd_1=(ddd.getFullYear()+'-'+(ddd.getMonth()+1).toString().padStart(2, "0")+'-'+ddd.getDate().toString().padStart(2, "0"))
                console.log(" "+ddd_1)

                fd.append('fechametames', valorr);
                console.log()
                fd.append('ii', entero);

                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    url: "{{ route('dashboard.viewMetaTable') }}",
                    /*beforeSend: function() {
                        $('#contenedor-fullscreen').hide()
                        $('.spinner').show()
                        $('#spinner').show()
                    },
                    complete: function() {
                        $('#contenedor-fullscreen').show()
                        $('.spinner').hide()
                        $('#spinner').hide()
                    },*/
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle the error
                    },
                    success: function (resultado) {
                        if(entero==1 || entero==2)
                        {
                            console.log("cambiar color")
                            //$(".h1-change-day").css("color","blue");
                            if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                        }
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
                        }else if (entero == 6) {
                            $('#porcentaje_cobranzas_metas').html(resultado);
                        }else if (entero == 7) {
                            $('#porcentaje_pedidos_metas').html(resultado);
                        }
                        else if (entero == 8) {/*izquierda*/
                            $('#grafico_dejaronpedir_right').html(resultado);
                        }
                        else if (entero == 9) {/*derecha*/

                            $('#grafico_dejaronpedir_left').html(resultado);
                        }
                        else if (entero == 13) {
                            $('#dejaronpedir_supervisor_total').html(resultado);
                        }
                        else if (entero == 14) {
                            $('#dejaronpedir_supervisor_A').html(resultado);
                        } else if (entero == 15) {
                            $('#dejaronpedir_supervisor_B').html(resultado);
                        }

                    }
                })
            }

            window.cargaNuevaRecurrenteActivo = function (entero) {
                console.log(' ' + entero)
                var fd = new FormData();
                //$('#fechametames').datepicker( "option", "dateFormat", "yy-mm-dd" );
                let valorr=$('#fechametames').val();
                var parts = valorr.split("-");
                valorr=parts[2]+'-'+parts[1]+'-'+parts[0]

                fd.append('fechametames', valorr);
                console.log()
                fd.append('ii', entero);
                $.ajax({
                    data: fd,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    url: "{{ route('dashboard.viewMetaTable.Recurrente.Activo') }}",
                    /*beforeSend: function() {
                        $('#contenedor-fullscreen').hide()
                        $('.spinner').show()
                        $('#spinner').show()
                    },
                    complete: function() {
                        $('#contenedor-fullscreen').show()
                        $('.spinner').hide()
                        $('#spinner').hide()
                    },*/
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle the error
                    },
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
                        }else if (entero == 6) {
                            $('#porcentaje_cobranzas_metas').html(resultado);
                        }else if (entero == 7) {
                            $('#porcentaje_pedidos_metas').html(resultado);
                        }
                        else if (entero == 8) {/*izquierda*/
                            $('#grafico_dejaronpedir_right').html(resultado);
                        }
                        else if (entero == 9) {/*derecha*/

                            $('#grafico_dejaronpedir_left').html(resultado);
                        }
                        else if (entero == 13) {
                            $('#dejaronpedir_supervisor_total').html(resultado);
                        }
                        else if (entero == 14) {
                            $('#dejaronpedir_supervisor_A').html(resultado);
                        } else if (entero == 15) {
                            $('#dejaronpedir_supervisor_B').html(resultado);
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
            cargaNueva(3);//totales porcentajes debajo de metas
            cargaNueva(6);//totales porcentajes arriba de metas cobranzas
            cargaNueva(7);//totales porcentajes arriba de metas pedidos
            //cargaNueva(4);//fernando
            //cargaNueva(5);//paola

            cargaNuevaRecurrenteActivo(8);
            cargaNuevaRecurrenteActivo(9);

            cargaNueva(14);//fernando
            cargaNueva(15);//paola
            cargaNueva(13);//totales porcentajes debajo de metas

            cargReporteAnalisis();
            cargReporteMetasSituacionClientes();
            cargReporteMetasCobranzasGeneral();

            setInterval(myTimer, 30000);

            function myTimer() {
                console.log("recargando")
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);
                cargaNueva(6);//totales porcentajes arriba de metas cobranzas
                cargaNueva(7);//totales porcentajes arriba de metas pedidos
                //cargaNueva(4);
                //cargaNueva(5);

                cargaNuevaRecurrenteActivo(8);
                cargaNuevaRecurrenteActivo(9);

                cargaNueva(14);//fernando
                cargaNueva(15);//paola
                cargaNueva(13);//totales porcentajes debajo de metas

                cargReporteMetasSituacionClientes();

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
            let $arrr = [{{$contadores_arr}}]
            let $mes_actual = [{{$contadores_mes_actual}}]
            let $mes_anterior = [{{$contadores_mes_anterior}}]
            var visitorsChart = new Chart($visitorsChart, {
                data: {
                    /*eje x: dias*/
                    labels: $arrr,
                    datasets: [{
                        /*azul*/
                        type: 'line',
                        data: $mes_actual,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                    }, {
                        /*plomo*/
                        type: 'line',
                        data: $mes_anterior,
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
                            }, ticks: $.extend({beginAtZero: true, suggestedMax: 100}, ticksStyle)
                        }], xAxes: [{display: true, gridLines: {display: false}, ticks: ticksStyle}]
                    }
                }
            })
        })

        $(function () {
            var ticksStyle = {fontColor: '#495057', fontStyle: 'bold'}
            var mode = 'index'
            var intersect = true

            var $visitorsChartOlva = $('#visitors-chart-olva')
            let $arrr = [{{$contadores_arr}}]
            let $gasto_olva_dia = [{{$contadores_mes_actual_olva}}]

            var $visitorsChartOlva = new Chart($visitorsChartOlva, {
                data: {
                    labels: $arrr,
                    datasets: [{
                        type: 'line',
                        data: $gasto_olva_dia,
                        backgroundColor: 'transparent',
                        borderColor: '#17a2b8',
                        pointBorderColor: '#17a2b8',
                        pointBackgroundColor: '#17a2b8',
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
                            }, ticks: $.extend({beginAtZero: true, suggestedMax: 400}, ticksStyle)
                        }], xAxes: [{display: true, gridLines: {display: false}, ticks: ticksStyle}]
                    }
                }
            })
        })
    </script>


@endsection
