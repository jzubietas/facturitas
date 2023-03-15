<div class="container-fluid">
    <div class="row" style="text-align: center; font-family:Georgia, 'Times New Roman', Times, serif">
        <div class="col-lg-9 col-9" style="margin-top:20px">
            <h2>
                <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste,
                    donde cumples la función de <b>{{ Auth::user()->rol }}</b></p>
            </h2>
        </div>
        {{--        <div class="col-lg-3 col-3">
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
                </div>--}}
    </div>
</div>
<br>
<br>
{{--<div class="container-fluid">
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
                    <h3>S/{{ (int)Auth::user()->meta_cobro }}</h3>
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
                    <h3>S/{{number_format( ($meta_pagoasesor->pagos)/1000 ,2)}} </h3>
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
</div>--}}
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <div class="row">
                {{-- <div class="col-md-12">
                     <div class="card">
                         <div class="d-flex justify-content-end align-items-center">
                             <div class="card my-2 mx-2">
                                 @php
                                     try {
                                          $currentDate=\Carbon\Carbon::createFromFormat('m-Y',request('selected_date',now()->format('m-Y')));
              }catch (Exception $ex){
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


                {{--DATATABLE--}}
                <div class="col-lg-12 " id="contenedor-fullscreen">

                    <div class="d-flex justify-content-center">
                        <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
                        <button style="background: none; border: none" onclick="openFullscreen();">
                            <i class="fas fa-expand-arrows-alt ml-3"
                               style="font-size: 20px"></i>
                        </button>
                        <div class="d-flex justify-content-center align-items-center ml-5">
                            <label class="p-0 m-0" for="ingresar">Fecha: </label>
                            <input type="date" id="fechametames" class="border-0 ml-3" value="{{\Carbon\Carbon::now()->startOfDay()->format('Y-m-d')}}">
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
               {{-- <!-- Modal -->
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
                {{--<div class="col-md-12">
                   <x-grafico-meta_cobranzas></x-grafico-meta_cobranzas>
               </div>
               {{             <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                   <br>
                   <div class="table-responsive">
                       <table class="table table-striped table-bordered table-condensed table-hover">
                           <div class="chart tab-pane active" id="mispedidosxasesorxdia"
                                style="width: 100%; height: 550px;">
                           </div>
                       </table>
                   </div>
               </div>
               --}}
            </div>
        </div>
        {{--
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
        --}}
    </div>
</div>
@include('dashboard.modal.asesoralerta')
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
                        }
                    }
                })
            }
            setInterval(myTimer, 10000);


            cargaNueva(1);
            cargaNueva(2);
            cargaNueva(3);

            function myTimer() {
                cargaNueva(1);
                cargaNueva(2);
                cargaNueva(3);
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
