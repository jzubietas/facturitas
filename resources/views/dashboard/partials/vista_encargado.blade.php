<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, donde
            cumples la función de <b>{{ Auth::user()->rol }}</b></p>
    </h2>
</div>
<br>
<br>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            {{--            @include('dashboard.widgets.pedidos_creados')--}}
        </div>
        @include('dashboard.widgets.buscar_cliente')
        @include('dashboard.partials.vista_quitar_vidas')
        <div class="col-md-12">
            <x-tabla-list-llamada-atencion></x-tabla-list-llamada-atencion>
        </div>
        {{--<div class="col-md-12">
            <div class="card">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="card my-2 mx-2">
                        @php
                            try {
                                 $currentDate=\Carbon\Carbon::createFromFormat('m-Y',request('selected_date',now()->format('m-Y')));
                            } catch (Exception $ex){
                                 $currentDate=\Carbon\Carbon::createFromFormat('m-Y',now()->format('m-Y'));
                            }
                        @endphp
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> Seleccionar Mes</span>
                            </div>
                            <div class="input-group-prepend">
                                <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->startOfMonth()->subYear()->format('m-Y')])}}"
                                   class="btn m-0 p-0"
                                   data-toggle="tooltip" data-placement="top" title="Un año atras">
                            <span class="input-group-text">
                                <
                            </span>
                                </a>
                                <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->startOfMonth()->subMonth()->format('m-Y')])}}"
                                   class="btn m-0 p-0"
                                   data-toggle="tooltip" data-placement="top" title="Un mes atras">
                                    <span class="input-group-text"><</span>
                                </a>
                            </div>
                            <select class="form-control" id="datepickerDashborad"
                                    aria-describedby="basic-addon3">

                                @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $month)
                                    @php
                                        $currentMonth=$currentDate->clone()->startOfYear()->addMonths($month-1);
                                    @endphp
                                    <option
                                        {{$currentMonth->format('m-Y')==request('selected_date',now()->format('m-Y'))?'selected':''}}
                                        value="{{$currentMonth->format('m-Y')}}"
                                    >{{Str::ucfirst($currentMonth->monthName)}} {{$currentMonth->year}}</option>
                                @endforeach
                            </select>

                            <div class="input-group-append">
                                <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->addMonths()->format('m-Y')])}}"
                                   class="btn m-0 p-0"
                                   data-toggle="tooltip" data-placement="top" title="Un mes adelante">
                                    <span class="input-group-text">></span>
                                </a>
                            </div>
                            <div class="input-group-append">
                                <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->addYear()->format('m-Y')])}}"
                                   class="btn m-0 p-0"
                                   data-toggle="tooltip" data-placement="top" title="Un año adelante">
                                    <span class="input-group-text">></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
        {{--        <div class="col-lg-12">
                    <x-grafico-metas-mes></x-grafico-metas-mes>
                </div>--}}

        {{--      DATATABLE--}}
        <div class="col-lg-12 " id="contenedor-fullscreen">

            <div class="d-flex justify-content-center flex-column mb-2 bg-white">
                <div class="d-flex justify-content-center row bg-white">
                    <div class="card col-lg-3 col-md-3 col-sm-12 d-flex align-items-center order-change-1 ">
                        <div class="card-body d-flex justify-content-center align-items-center" style="grid-gap: 20px">
                            <h5 class="card-title text-uppercase">Total de cobranzas:</h5>
                            <p id="porcentaje_cobranzas_metas" class="card-text font-weight-bold" style="font-size: 25px"> --%</p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center align-items-center order-change-2 ">
                        <h1 class="text-uppercase justify-center text-center " style="color: #FFFFFF;
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

            </div>

            <div class="d-flex justify-content-center">
                <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
                <button style="background: none; border: none" onclick="openFullscreen();">
                    <i class="fas fa-expand-arrows-alt ml-3"
                       style="font-size: 20px"></i>
                </button>
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
        {{--FIN-DATATABLE--}}

{{--        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog- modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img alt="Dia de la mujer" src="{{ asset('/img/diaMujer.jpg') }}" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal -->--}}

        <div class="col-md-12">
            <div class="row" id="widget-container">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-9">
                                                {{-- <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>--}}
                                                <x-grafico-cobranzas-meses-progressbar></x-grafico-cobranzas-meses-progressbar>
                                            </div>
                                            <div class="col-md-3">
                                                <x-grafico-pedidos-mes-count-progress-bar></x-grafico-pedidos-mes-count-progress-bar>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    {{--<x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR DIA"
                                               label-x="Asesores" label-y="Cant. Pedidos"
                                               only-day></x-grafico-pedidos-por-dia>
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR MES"
                                               label-x="Asesores" label-y="Cant. Pedidos"></x-grafico-pedidos-por-dia>--}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        .list-group .list-group-item {
            background: #a5770f1a;
        }

        .animated-progress {
            width: 300px;
            height: 30px;
            border-radius: 5px;
            margin: 20px 10px;
            border: 1px solid rgb(189, 113, 113);
            overflow: hidden;
            position: relative;
        }

        .animated-progress span {
            height: 100%;
            display: block;
            width: 0;
            color: rgb(255, 251, 251);
            line-height: 30px;
            text-align: end;
            padding-right: 5px;
        }

        td:nth-child(1),
        td:nth-child(2),
        td:nth-child(3) {
            font-weight: bold;
        }

        td:nth-child(3) {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table_analisis {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        }

        /*        .tabla-metas_pagos_pedidos{
                  font-size: 12px;
                }*/
        .format-size {
            padding: 0;
            font-weight: bold;
            font-size: 18px;
            margin-left: 8px;
        }

        .bold-size {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .bold-size-total {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .name-size {
            font-size: 14px;
        }

        .center-around {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }


        @media screen and (max-width: 1440px) {
            .tabla-metas_pagos_pedidos {
                font-size: 12px;
            }

            .name-size {
                font-size: 12px;
            }

            .format-size {
                font-size: 14px;
                margin-left: 3px;
            }

            .bold-size {
                font-size: 15px;
            }

            .bold-size-total {
                font-size: 11px;
            }

        }

        @media screen and (max-width: 1345px) {
            .input-column {
                flex-direction: column;
            }
        }

        @media screen and (max-width: 991px) {
            .input-column {
                flex-direction: row;
            }

            .margen {
                margin-left: 4px;
                margin-right: 4px;
            }
        }


    </style>
@endpush

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
                        }else if (entero == 6) {
                            $('#porcentaje_cobranzas_metas').html(resultado);
                        }else if (entero == 7) {
                            $('#porcentaje_pedidos_metas').html(resultado);
                        }
                    }
                })
            }

            cargaNueva(1);
            cargaNueva(2);


            console.log('ROL: ',"{{$mirol}}");
            console.log('ID: ',"{{$idEncargado}}");
            @if($mirol == 'Encargado' && $idEncargado == 46)
            cargaNueva(4);

            @endif


            @if($mirol == 'Encargado' && $idEncargado == 24)
            cargaNueva(5);
            @endif

            cargaNueva(6);//totales porcentajes arriba de metas cobranzas
            cargaNueva(7);//totales porcentajes arriba de metas pedidos


            setInterval(myTimer, 10000);
            function myTimer() {
                cargaNueva(1);
                cargaNueva(2);

                @if($mirol == 'Encargado' && $idEncargado == 46)
                    cargaNueva(4);
                @endif

                @if($mirol == 'Encargado' && $idEncargado == 24)
                    cargaNueva(5);
                @endif

                cargaNueva(6);//totales porcentajes arriba de metas cobranzas
                cargaNueva(7);//totales porcentajes arriba de metas pedidos
            }

            $('a[href$="#myModal"]').on("click", function () {
                $('#myModal').modal();
            });

            var elem = document.querySelector("#contenedor-fullscreen");
            window.openFullscreen = function () {
                console.log("openFullscreen();")
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

@endsection
