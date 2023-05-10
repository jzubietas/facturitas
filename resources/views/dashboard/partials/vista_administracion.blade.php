@push('css')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.4.2/main.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timeline@4.4.2/main.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@4.4.2/main.min.css" rel="stylesheet" />
    <style>
        .table td, .table th
        {
            padding: .25rem !important;
        }
    </style>
@endpush

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

<div class="card">
    <div class="card-header">
        Eleccion de Fecha Calendario
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center align-items-center ml-5">
            <label class="p-0 m-0" for="fechametames">Fecha: </label>
            <input type="text" id="fechametames" class="border-0 ml-3" name="fechametames"
                   value="" readonly>
            <button class="btn btn-success btn-md" id="fechametames-button">Fecha hoy</button>
        </div>
    </div>
    <div class="card-footer text-center">
        <buton style="background: none; border: none;" onclick="openFullscreen();">
            <i class="fas fa-expand-arrows-alt ml-3" style="font-size: 20px"></i>
        </buton>
    </div>
</div>

<!--grafico metas de asesor de pedidos-->
<div class="row">
    <div class="col-lg-12 bg-white" id="contenedor-fullscreen">

        <div class="" style=" overflow: hidden !important;">
            <div class=" " style=" overflow-x: scroll !important; overflow-y: scroll !important;">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6" id="metas_dp_1"></div>
                    <div class="col-lg-6 col-md-6 col-sm-6" id="metas_dp_2"></div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="metas_dp_3"></div>
                </div>

            </div>
        </div>
        {{-- FIN-TABLA-DUAL --}}

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="metas_total_general"></div>
        </div>

        <div class="col-lg-12" id="metas_dp_99"></div>

        <div class="col-md-12 bg-white">
            <div id="metas_situacion_clientes"></div>
        </div>



    </div>



</div>

<div class="modal" id="modal-publicidad-calendario-add">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Modal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <x-publicidad-calendario-add></x-publicidad-calendario-add>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">

            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 bg-white" id="contenedor-fullscreen-g2">

        <div class="row">
            <div class="col-3 bg-white">
                <div class="d-flex justify-content-center align-items-center">
                    <h5 class="card-title text-uppercase">Total de cobranzas :</h5>
                    <p id="porcentaje_cobranzas_metas_g2" class="card-text font-weight-bold" style="font-size: 25px"> --%</p>
                </div>
            </div>
            <div class="col-6 bg-white">
                <div class="d-flex justify-content-center align-items-center">
                    <h2 class="text-uppercase justify-center text-center h1-change-day" style="color: #FFFFFF;
background: #FFFFFF;
text-shadow: 2px 2px 0 #242120, 2px -2px 0 #242120, -2px 2px 0 #242120, -2px -2px 0 #242120, 2px 0 0 #242120, 0 2px 0 #242120, -2px 0 0 #242120, 0 -2px 0 #242120;">Metas del mes
                        de {{\Carbon\Carbon::now()->startOfMonth()->translatedFormat('F')}}</h2>
                    <button style="background: none; border: none" onclick="openFullscreen2();">
                        <i class="fas fa-expand-arrows-alt ml-3"
                           style="font-size: 20px"></i>
                    </button>
                </div>
            </div>
            <div class="col-3 bg-white">
                <div class="d-flex justify-content-center align-items-center">
                    <h5 class="card-title text-uppercase">Total de pedidos:</h5>
                    <p id="porcentaje_pedidos_metas_g2" class="card-text font-weight-bold" style="font-size: 25px"> --%</p>
                </div>
            </div>

        </div>

        {{-- TABLA DUAL --}}
        <div class="" style=" overflow: hidden !important;">
            <div class=" " style=" overflow-x: scroll !important; overflow-y: scroll !important;">
                <div class="row">
                    <div class="contain-table-dual row" style="width: 100% !important;">
                        <div class="col-lg-6" id="metas_asesores_g2_a"></div>
                        <div class="col-lg-6" id="metas_asesores_g2_b"></div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div id="metas_asesores_total_g2"></div>
                    </div>

                </div>

            </div>
        </div>
        {{-- FIN-TABLA-DUAL --}}
        <div class="row">

            <div class="col-lg-12" id="metas_dp_17"></div>

            <div class="col-lg-12" id="metas_dp_17_calendario"></div>

            <div class="col-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-publicidad-calendario-add">
                    Agregar
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="metas_asesores_total_dp17"></div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="metas_situacion_clientes_metasasesores"></div>
        </div>

    </div>

</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="calendario1" style="padding:2px"></div>
        </div>
    </div>
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
            <div id="dejaronpedir_supervisor_total"></div>
        </div>

    </div>
</div>


<div class="col-md-12 bg-white">
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
<br>

{{-- METAS ASESOR DE LLAMADAS --}}
<div class="container-fluid bg-white" id="contenedor-fullscreen-llamadas">
    <div class="col-md-12 d-flex justify-content-center align-items-center">
        <h1 class="text-uppercase justify-center text-center h1-change-day" style="color: #FFFFFF;
background: #FFFFFF;
text-shadow: 2px 2px 0 #242120, 2px -2px 0 #242120, -2px 2px 0 #242120, -2px -2px 0 #242120, 2px 0 0 #242120, 0px 2px 0 #242120, -2px 0px 0 #242120, 0px -2px 0 #242120;">
            Metas Llamadas/Cobranzas
            de {{\Carbon\Carbon::now()->startOfMonth()->translatedFormat('F')}}</h1>
        <button style="background: none; border: none" onclick="openFullscreenllamadas();">
            <i class="fas fa-expand-arrows-alt ml-3"
               style="font-size: 20px"></i>
        </button>
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
                <div class="row">
                    <div class="col">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">{{$asesor_pedido_dia}}</span>
                            <span>Cantidad de pedidos del día</span>
                        </p>
                    </div>
                </div>

                <canvas id="visitors-chart" style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>

                <div class="row">
                    <div class="col">
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
                <div class="row">
                    <div class="col">
                        <span class="text-bold text-lg">{{$gasto_total_olva}}</span>
                        <span>Cantidad total del día</span>
                    </div>
                </div>

                <canvas id="visitors-chart-olva" style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>

                <div class="row">
                    <div class="col">
                        <span class="mr-2 text-uppercase">
                            <i class="fas fa-square" style="background: #17a2b8; color: #17a2b8"></i> #{{\Carbon\Carbon::now()->monthName}}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
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

    <script src="{{asset('js/Chart.min.js.js')}}"></script>
    <script src="{{asset('js/chartjs-plugin-datalabels.js')}}"></script>

    <script>
        window.cargaNuevaGeneral = function (entero) {
            console.log(' ' + entero)
            var fd = new FormData();
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
                url: "{{ route('dashboard.viewMetaTable.General') }}",
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color")
                        //$(".h1-change-day").css("color","blue");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }
                    if (entero === 0)
                    {
                        $('#metas_total_general').html(resultado);
                    }

                }
            })
        }

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
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color")
                        //$(".h1-change-day").css("color","blue");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }
                    if (entero === 1) {
                        $('#metas_dp_1').html(resultado);
                    } else if (entero === 2) {
                        $('#metas_dp_2').html(resultado);
                    } else if (entero === 3) {
                        $('#metas_dp_3').html(resultado);
                    } else if (entero === 4) {
                        $('#supervisor_total').html(resultado);
                    } else if (entero === 5) {
                        $('#supervisor_A').html(resultado);
                    }
                    else if (entero === 14) {
                        $('#dejaronpedir_supervisor_A').html(resultado);
                    } else if (entero === 15) {
                        $('#dejaronpedir_supervisor_B').html(resultado);
                    }
                    //otro bloque segun virginia
                    else if (entero === 21) {
                        $('#metas_asesores_g2_a').html(resultado);
                    } else if (entero === 22) {
                        $('#metas_asesores_g2_b').html(resultado);
                    }

                }
            })
        }

        window.cargaNueva2 = function (entero) {
            console.log(' ' + entero)
            var fd = new FormData();
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
                url: "{{ route('dashboard.viewMetaTable_G2') }}",
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color")
                        //$(".h1-change-day").css("color","blue");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }

                    //otro bloque segun virginia
                    if (entero === 21) {
                        $('#metas_asesores_g2_a').html(resultado);
                    } else if (entero === 22) {
                        $('#metas_asesores_g2_b').html(resultado);
                    }
                    else if (entero === 26) {
                        $('#porcentaje_cobranzas_metas_g2').html(resultado);
                    }else if (entero === 27) {
                        $('#porcentaje_pedidos_metas_g2').html(resultado);
                    }

                }
            })
        }

        window.cargaNueva23 = function (entero) {
            console.log(' ' + entero)
            var fd = new FormData();
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
                url: "{{ route('dashboard.viewMetaTable_G3') }}",
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color")
                        //$(".h1-change-day").css("color","blue");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }

                    //otro bloque segun virginia
                    if (entero === 23) {
                        $('#metas_asesores_total_g2').html(resultado);
                    }

                }
            })
        }

        window.cargaNueva17 = function (entero) {
            console.log(' ' + entero)
            var fd = new FormData();
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
                url: "{{ route('dashboard.viewMetaTable_G17') }}",
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }
                    if (entero === 17) {
                        $('#metas_dp_17').html(resultado);
                    }else if (entero === 37) {
                        $('#metas_asesores_total_dp17').html(resultado);
                    }
                }
            })
        }

        window.cargaNuevaCalendario = function (entero) {
            var fd = new FormData();
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
                url: "{{ route('dashboard.viewMetaTable_G17_Calendario') }}",
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }
                    if (entero === 17) {
                        $('#metas_dp_17_calendario').html(resultado);
                    }else if (entero === 37) {
                        $('#metas_asesores_total_dp17').html(resultado);
                    }
                }
            })
        }

        window.cargaNueva99 = function (entero) {
            console.log(' ' + entero)
            var fd = new FormData();
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
                url: "{{ route('dashboard.viewMetaTable_G99') }}",
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the error
                },
                success: function (resultado) {
                    if(entero===1 || entero===2)
                    {
                        console.log("cambiar color");
                        if(valorr!=ddd_1)
                            $(".h1-change-day").attr('style', 'color: blue !important');
                    }
                    if (entero === 99) {
                        $('#metas_dp_99').html(resultado);
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
                    if (entero === 8) {/*izquierda*/
                        $('#grafico_dejaronpedir_right').html(resultado);
                    }
                    else if (entero === 9) {/*derecha*/
                        $('#grafico_dejaronpedir_left').html(resultado);
                    }
                    else if (entero === 13) {
                        $('#dejaronpedir_supervisor_total').html(resultado);
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
                    $('#metas_situacion_clientes_metasasesores').html(resultado);
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

            //$('#exampleModalCenter').modal('show');

            $(document).on('change', '#fechametames', function () {
                //const value = e.target.value;

                cargaNuevaGeneral(0);

                //grupo 1
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);

                //porcentajes grupo 2
                cargaNueva2(26);
                cargaNueva2(27);

                cargaNueva(8);
                cargaNueva(9);

                cargaNueva17(17);
                cargaNueva17(37);

                cargaNuevaCalendario(17);

                cargaNueva99(99);

                cargReporteMetasCobranzasGeneral();

            });

            cargaNuevaGeneral(0);

            //grupo 1
            cargaNueva(1);
            cargaNueva(2);
            cargaNueva(3);

            //porcentaje grupo 2
            cargaNueva2(26);
            cargaNueva2(27);

            cargaNuevaRecurrenteActivo(8);
            cargaNuevaRecurrenteActivo(9);
            cargaNuevaRecurrenteActivo(13);
            //cargaNueva(14);//fernando
            //cargaNueva(15);//paola
            //totales porcentajes debajo de metas

            cargaNueva17(17);



            cargaNueva17(37);

            cargaNuevaCalendario(17);

            cargaNueva99(99);

            cargReporteAnalisis();
            cargReporteMetasSituacionClientes();
            cargReporteMetasCobranzasGeneral();


            //grafico_metas_asesores();

            setInterval(myTimer, 90000);

            function myTimer() {
                console.log("recargando")

                cargaNuevaGeneral(0);

                //grupo 1
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);

                //porcentaje grupo 2
                cargaNueva2(26);
                cargaNueva2(27);

                cargaNuevaRecurrenteActivo(8);
                cargaNuevaRecurrenteActivo(9);
                cargaNuevaRecurrenteActivo(13);

                //cargaNueva(14);//fernando
                //cargaNueva(15);//paola
                //totales porcentajes debajo de metas
                cargaNueva17(17);
                cargaNueva17(37);

                cargaNuevaCalendario(17);

                cargaNueva99(99);

                cargReporteMetasSituacionClientes();

                cargReporteMetasCobranzasGeneral();
            }

            /*$('a[href$="#myModal"]').on("click", function () {
                $('#myModal').modal();
            });*/

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

            var elem_g2 = document.querySelector("#contenedor-fullscreen-g2");
            window.openFullscreen2 = function () {
                if (elem_g2.requestFullscreen) {
                    elem_g2.requestFullscreen();
                } else if (elem_g2.webkitRequestFullscreen) { /* Safari */
                    elem_g2.webkitRequestFullscreen();
                } else if (elem_g2.msRequestFullscreen) { /* IE11 */
                    elem_g2.msRequestFullscreen();
                }
            }

            var elem_llamada = document.querySelector("#contenedor-fullscreen-llamadas");
            window.openFullscreenllamadas = function () {
                if (elem_llamada.requestFullscreen) {
                    elem_llamada.requestFullscreen();
                } else if (elem_llamada.webkitRequestFullscreen) { /* Safari */
                    elem_llamada.webkitRequestFullscreen();
                } else if (elem_llamada.msRequestFullscreen) { /* IE11 */
                    elem_llamada.msRequestFullscreen();
                }
            }

        });
    </script>

    <script src=" {{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.4.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.4.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timeline@4.4.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-common@4.4.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@4.4.2/main.min.js"></script>

    <script>

        $(document).ready(function () {

            /*let calendarEl = document.getElementById('calendario1');

            let calendario1 = new FullCalendar.Calendar(calendarEl, {
                plugins: ['resourceTimeline', 'interaction'],
                droppable: true,
                locale: 'es',
                showNonCurrentDates: false,
                header: {
                    left: 'today,prev,next',
                    center: 'title',
                    right: 'resourceTimelineWeek'
                },
                aspectRatio: 1.5,
                defaultView: 'resourceTimelineWeek',
                resourceAreaWidth: '40%',
                resourceColumns: [
                    {
                        group: false,
                        labelText: 'Publicidad',
                        field: 'users'
                    },
                ],
                resources: [
                    { id: 'a', users: 'Publicidad 1' },
                    { id: 'b', users: 'Publicidad 2' },
                    { id: 'g', users: 'Publicidad 3' },
                    { id: 'h', users: 'Publicidad 4' },
                    { id: 'z', users: 'Publicidad 5' }
                ]
            });*/

            //calendario1.render();

        });
    </script>

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
                type: 'line',
                data: {
                    /*eje x: dias*/
                    labels: $arrr,
                    datasets: [{
                        label:'Mes actual',
                        /*azul*/
                        type: 'line',
                        data: $mes_actual,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false,
                        tension:0.1
                    }, {
                        label:'Mes anterior',
                        /*plomo*/
                        type: 'line',
                        data: $mes_anterior,
                        backgroundColor: 'tansparent',
                        borderColor: '#ced4da',
                        pointBorderColor: '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill: false,
                        tension:0.1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {mode: mode, intersect: intersect},
                    hover: {mode: mode, intersect: intersect},
                    legend: {display: false},
                    /*scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            }, ticks: $.extend({beginAtZero: true, suggestedMax: 100}, ticksStyle)
                        }], xAxes: [{display: true, gridLines: {display: false}, ticks: ticksStyle}]
                    }*/
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
                type: 'line',
                data: {
                    labels: $arrr,
                    datasets: [{
                        label:'Gasto Olva',
                        type: 'line',
                        data: $gasto_olva_dia,
                        backgroundColor: 'transparent',
                        borderColor: '#17a2b8',
                        pointBorderColor: '#17a2b8',
                        pointBackgroundColor: '#17a2b8',
                        fill: false,
                        tension:0.1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {mode: mode, intersect: intersect},
                    hover: {mode: mode, intersect: intersect},
                    legend: {display: true},
                    /*scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            }, ticks: $.extend({beginAtZero: true, suggestedMax: 400}, ticksStyle)
                        }], xAxes: [{display: true, gridLines: {display: false}, ticks: ticksStyle}]
                    }*/
                    plugins:{
                        legend:{
                            display:true,
                            labels:{
                                color: 'rgb(255,99,132)'
                            }
                        }
                    }
                }
            })
        })
    </script>


@endsection
