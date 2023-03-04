@yield('css-datatables')

<div class="text-center mb-4" style="font-family:'Times New Roman', Times, serif">
    <h2>
        <p>
            Bienvenido <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, eres el
            <b>{{ Auth::user()->rol }} del sistema</b>
        </p>
    </h2>
</div>

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


<div class="row">

    @include('dashboard.widgets.buscar_cliente')
    @include('dashboard.partials.vista_quitar_vidas')
    <div class="col-md-12">
      <x-tabla-list-llamada-atencion></x-tabla-list-llamada-atencion>
    </div>
    <div class="col-lg-12">
        <x-common-activar-cliente-por-tiempo></x-common-activar-cliente-por-tiempo>
    </div>
    <div class="col-lg-12">
        <x-grafico-pedidos-elect-fisico></x-grafico-pedidos-elect-fisico>
    </div>



    <div class="col-lg-12 " id="contenedor-fullscreen">

      <div class="d-flex justify-content-center">
        <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
        <button style="background: none; border: none" onclick="openFullscreen();"><i class="fas fa-expand-arrows-alt ml-3" style="font-size: 20px"></i></button>
        <div class="d-flex justify-content-center align-items-center ml-5">
          <label class="p-0 m-0" for="ingresar">Fecha: </label>
          <input type="date" id="fecha" class="border-0 ml-3" min="{{\Carbon\Carbon::now()->startOfDay()->startOfMonth()->format('Y-m-d')}}" max="{{\Carbon\Carbon::now()->endOfDay()->format('Y-m-d')}}" >
        </div>
      </div>

      {{--TABLA DUAL--}}
      <div class="" style=" overflow: hidden !important;">
        <div class=" " style=" overflow-x: scroll !important; overflow-y: scroll !important;">
          <div class="row">
            <div class="col-md-6">
              <div id="meta"></div>
            </div>
            <div class="col-md-6">
              <div id="metas_dp"></div>
            </div>

            <div class="col-md-12">
              <div id="supervisor_total" ></div>
            </div>
            <div class="col-md-12">
              <div id="supervisor_A" ></div>
            </div>
            <div class="col-md-12">
              <div id="supervisor_B" ></div>
            </div>
            <div class="col-md-12">
              <div id="metas_total" ></div>
            </div>

            <div class="col-md-12">
              <div class="d-flex justify-content-center">
                <h1 class="text-uppercase justify-center text-center">Metas Asesores de Llamadas</h1>
                </div>
              <div id="metas_situacion_clientes"></div>
            </div>

            <div class="col-md-12">
              <div class="d-flex justify-content-center">
                <h1 class="text-uppercase justify-center text-center">Metas Asesores de Llamadas</h1>
              </div>
              <div id="metas_cobranzas_general"></div>
            </div>

          </div>

        </div>
      </div>
      {{--FIN-TABLA-DUAL--}}
    </div>

{{--    <div class="container-fluid">--}}
{{--      <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--          <div id="reporteanalisis"></div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
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
            </div>
            <div class="col-md-12">
                <div class="row" id="widget-container">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4 pb-4">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-9">
                                                     {{--<x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>--}}
                                                    {{--<x-grafico-cobranzas-meses-progressbar></x-grafico-cobranzas-meses-progressbar>--}}
                                                </div>
                                                <div class="col-md-3">
                                                    {{--<x-grafico-pedidos-mes-count-progress-bar></x-grafico-pedidos-mes-count-progress-bar>--}}
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                {{--<x-grafico-pedidos-atendidos-anulados></x-grafico-pedidos-atendidos-anulados>--}}
                            </div>

                            <div class="col-lg-12">
                                {{--<x-grafico-pedido_cobranzas-del-dia></x-grafico-pedido_cobranzas-del-dia>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {{--<x-grafico-pedidos-por-dia rol="Administrador"
                                                   title="Cantidad de pedidos de los asesores por dia"
                                                   label-x="Asesores" label-y="Cant. Pedidos"
                                                   only-day></x-grafico-pedidos-por-dia>--}}

                        {{--<x-grafico-pedidos-por-dia rol="Administrador"
                                                   title="Cantidad de pedidos de los asesores por mes"
                                                   label-x="Asesores"
                                                   label-y="Cant. Pedidos"></x-grafico-pedidos-por-dia>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                {{--<x-grafico-top-clientes-pedidos top="10"></x-grafico-top-clientes-pedidos>--}}
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none">
                <div class="card">
                    <div class="card-body pl-0">
                        {{--<div id="pagosxmes" class="w-100" style="height: 550px;"></div>--}}
                    </div>
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
        td:nth-child(3){
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

        $('#fecha').val("{{\Carbon\Carbon::parse($fecha)->format('Y-m-d')}}");

        $(document).on('change','#fecha',function(){
          const value = e.target.value;
          console.log(value)
          if (value) {
            window.location.replace('{{route('dashboard.index')}}?fecha=' + value)
          }
        });

        window.cargaNueva = function (entero) {
          console.log(' '+entero)
          var fd=new FormData();
          fd.append('fecha',$('#fecha').val());
          fd.append('ii',entero);
          $.ajax({
            data: fd,
            processData: false,
            contentType: false,
            method: 'POST',
            url: "{{ route('dashboard.viewMetaTable') }}",
            success: function (resultado){
              if(entero==1)
              {
                $('#metas_dp').html(resultado);
              }else if(entero==2){
                $('#meta').html(resultado);
              }
              else if(entero==3){
                $('#metas_total').html(resultado);
              }else if(entero==4){
                $('#supervisor_total').html(resultado);
              }else if(entero==5){
                $('#supervisor_A').html(resultado);
              }
            }
          })
        }


        window.cargReporteAnalisis = function () {
          var fd=new FormData();
          $.ajax({
            data: fd,
            processData: false,
            contentType: false,
            method: 'POST',
            url: "{{ route('dashboard.viewAnalisis') }}",
            success: function (resultado){
                $('#reporteanalisis').html(resultado);
            }
          })
        }

        window.cargReporteMetasSituacionClientes = function () {
          var fd=new FormData();
          $.ajax({
            data: fd,
            processData: false,
            contentType: false,
            method: 'POST',
            url: "{{ route('dashboard.graficoSituacionClientes') }}",
            success: function (resultado){
              $('#metas_situacion_clientes').html(resultado);
            }
          })
        }

        window.cargReporteMetasCobranzasGeneral = function () {
          var fd=new FormData();
          $.ajax({
            data: fd,
            processData: false,
            contentType: false,
            method: 'POST',
            url: "{{ route('dashboard.graficoCobranzasGeneral') }}",
            success: function (resultado){
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

        setInterval(myTimer, 500000);

        function myTimer() {
          cargaNueva(1);
          cargaNueva(2);
          cargaNueva(3);
          cargaNueva(4);
          cargaNueva(5);
        }

        $('a[href$="#myModal"]').on( "click", function() {
          $('#myModal').modal();
        });

        var elem = document.querySelector("#contenedor-fullscreen");
        window.openFullscreen =function () {
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
