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
{{--        @include('dashboard.widgets.buscar_cliente')--}}

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
        <div class="d-flex justify-content-center">
          <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
          <button style="background: none; border: none" onclick="openFullscreen();"><i class="fas fa-expand-arrows-alt ml-3" style="font-size: 20px"></i></button>
        </div>
        {{--TABLA DUAL--}}
        <div class="">
          <div class=" ">
            <div class="row">
              <div class="col-md-6">
                <div id="meta"></div>
              </div>
              <div class="col-md-6">
                <div id="metas_dp"></div>
              </div>
              <div class="col-md-12">
                <div id="metas_total"></div>
              </div>
            </div>
          </div>
        </div>
        {{--FIN-TABLA-DUAL--}}
      </div>
      {{--FIN-DATATABLE--}}


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
                {{--
                <div class="col-md-12">
                    <x-grafico-meta_cobranzas></x-grafico-meta_cobranzas>
                </div>
                --}}
                <div class="col-md-12">
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR DIA"
                                               label-x="Asesores" label-y="Cant. Pedidos"
                                               only-day></x-grafico-pedidos-por-dia>
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR MES"
                                               label-x="Asesores" label-y="Cant. Pedidos"></x-grafico-pedidos-por-dia>
                </div>
            </div>
        </div>
        {{--
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover"><br><h4>
                        PEDIDOS DEL DIA POR ASESOR</h4>
                    <div id="pedidosxasesorxdia_encargado" style="width: 100%; height: 500px;"></div>
                </table>
            </div>
        </div>
        --}}
    </div>
</div>
{{--
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
--}}

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
      window.cargaNueva = function (entero) {
        console.log(' '+entero)
        var fd=new FormData();
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
            }
          }
        })
      }

      /*cargaNueva(1);
      cargaNueva(2);
      cargaNueva(3);

      setInterval(myTimer, 10000);*/

      function myTimer() {
        cargaNueva(1);
        cargaNueva(2);
        cargaNueva(3);
      }

      $('a[href$="#myModal"]').on( "click", function() {
        $('#myModal').modal();
      });

      var elem = document.querySelector("#contenedor-fullscreen");
      window.openFullscreen =function () {
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
