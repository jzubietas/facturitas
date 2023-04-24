@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Analisis<i><b>Ojo Celeste</b></i></h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header bg-primary">
            PEDIDOSS {{ $mes_month }}  {{-- $mes_anio --}}   {{-- $mes_mes --}}
            <div class="float-right btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    Exportar
                </button>
                <div class="dropdown-menu">
                    <a href="" data-target="#modal-exportar-unico" data-toggle="modal" class="dropdown-item"
                       target="blank_"><img
                            src="{{ asset('imagenes/icon-excel.png') }}"> Analisis</a>
                    {{--<a href="" data-target="#modal-exportar-v2" data-toggle="modal" class="dropdown-item" target="blank_"><img src="{{ asset('imagenes/icon-excel.png') }}"> Clientes - Situacion</a>--}}
                </div>
            </div>
            @include('reportes.modal.exportar_unico', ['title' => 'Exportar Analisis', 'key' => '1'])
        </div>
        <div class="form-group m-0">
            <div class="row">
                <div class="form-group col-lg-12 m-0">
                    <div class="card mx-3 my-3">
                        <div class="card-body">
                            <div class="row ">
                                @foreach ($_pedidos_mes_pasado as $pedido)
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <h5> {{ $pedido->name }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="card">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <span
                                                                class="badge badge-light">RECUPERADO.RECIENTE</span><br>
                                                            <span
                                                                class="badge badge-secondary">{{ $pedido->recuperado_reciente }}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <span
                                                                class="badge badge-light">RECUPERADO.ABANDONO</span><br>
                                                            <span
                                                                class="badge badge-secondary">{{ $pedido->recuperado_abandono }}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <span class="badge badge-light">NUEVO</span><br>
                                                            <span
                                                                class="badge badge-secondary">{{ $pedido->nuevo }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-row d-none">
                                <div class="form-group col-lg-12" style="text-align: center">
                                    {!! Form::label('servicio_id', 'Complssete sus parámetros') !!} <br><br>
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <label>Fecha inicial&nbsp;</label>
                                            {!! Form::date('desde', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Fecha final&nbsp;</label>
                                            {!! Form::date('hasta', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-none">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="far fa-chart-bar"></i>
                Cuadro comparativo de clientes caidos
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
                                <canvas id="my-chart-caidosconsindeuda"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="my-chart-caidosvienende"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="my-chart-metasasesores"  style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js-datatables')
    <script src="{{asset('js/Chart.min.js.js')}}"></script>
    <script src="{{asset('js/chartjs-plugin-datalabels.js')}}"></script>

    <script>
        function grafico_condeuda_sindeuda()
        {
            $.ajax({
                method:'GET',
                dataType: "json",
                url:"{{ route('chart/clientes.caidos/condeuda.sindeuda') }}",
                success:function(data){
                    //console.log(data)
                    var data_consindeuda = [{
                        data: data.datasets[0].data,
                        backgroundColor: [
                            "#4b77a9",
                            "#5f255f"
                        ],
                        borderColor: "#fff"
                    }];

                    var options_consindeuda = {
                        title: {
                            display: true,
                            text: data.title
                        },
                        plugins: {
                            datalabels: {
                                formatter: (value, ctx) => {
                                    //console.log(value);
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                        sum += data;
                                    });
                                    let percentage = (value * 100 / sum).toFixed(2) + "%";
                                    return percentage + '('+value+')';


                                },
                                color: '#fff',
                            }
                        }
                    };

                    var ctx_consindeuda = document.getElementById("my-chart-caidosconsindeuda").getContext('2d');
                    var myChart_consindeuda = new Chart(ctx_consindeuda, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: data_consindeuda
                        },
                        options: options_consindeuda
                    });
                }
            });
        }

        function grafico_vienen_de()
        {
            $.ajax({
                method:'GET',
                dataType: "json",
                url:"{{ route('chart/clientes.caidos/vienen.de') }}",
                success:function(data){
                    //console.log(data)
                    var data_vienende = [{
                        data: data.datasets[0].data,
                        backgroundColor: data.datasets[0].backgroundColor,
                        borderColor: "#fff"
                    }];

                    var options_vienende = {
                        title: {
                            display: true,
                            text: data.title
                        },
                        plugins: {
                            datalabels: {
                                formatter: (value, ctx) => {
                                    //console.log(value);
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                        sum += data;
                                    });
                                    let percentage = (value * 100 / sum).toFixed(2) + "%";
                                    return percentage + '('+value+')';


                                },
                                color: '#fff',
                            },
                        }
                    };

                    var ctx_vienende = document.getElementById("my-chart-caidosvienende").getContext('2d');
                    var myChart_vienende = new Chart(ctx_vienende, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: data_vienende
                        },
                        options: options_vienende
                    });
                }
            });
        }

        function grafico_metas_asesores()
        {
            $.ajax({
                method:'GET',
                dataType: "json",
                url:"{{ route('chart/metas/asesores') }}",
                success:function(data){
                    //console.log(data)
                    //var ctx = document.getElementById('my-chart-metasasesores').getContext('2d');
                    data = [
                        { label: 'Asesor 01', value: 75, superar:100 },
                        { label: 'Asesor 02', value: 50, superar:100 },
                        { label: 'Asesor 03', value: 25, superar:100 }
                    ];

                    var ctx = document.getElementById('my-chart-metasasesores').getContext('2d');

                    var myChart = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: {
                            labels: data.map(function(item) {
                                return item.label;
                            }),
                            datasets: [{
                                data: data.map(function(item) {
                                    return (item.value/item.superar)*100;
                                }),
                                backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Desarrollo (actualizacion metas asesores)'
                            },
                            plugins: {
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        //console.log(value);
                                        let sum = 0;
                                        let dataArr = ctx.chart.data.datasets[0].data;
                                        dataArr.map(data => {
                                            sum += data;
                                        });
                                        let superar_=ctx.dataIndex;
                                        let percentage = (value * 100 / sum).toFixed(2) + "%";
                                        return percentage + '('+value+')';


                                    },
                                    color: '#fff',
                                },
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        max: 100
                                    },
                                    gridLines: {
                                        display: false
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    }
                                }]
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                enabled: false
                            },
                            animation: {
                                duration: 2000
                            }
                        }
                    });

                    setInterval(function() {
                        data.forEach(function(item, index) {
                            //console.log(item);
                            //item.value = Math.floor(Math.random() * 100) + 1;
                            myChart.data.datasets[0].data[index] = item.value;
                        });
                        myChart.update();
                    }, 3000);

                }
            });
        }

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            grafico_condeuda_sindeuda();
            grafico_vienen_de();
        });


    </script>



@endsection
