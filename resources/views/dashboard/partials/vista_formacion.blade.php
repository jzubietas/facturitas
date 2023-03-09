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

{{--    <!-- Modal -->
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

    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <x-grafico-top-clientes-pedidos top="10"></x-grafico-top-clientes-pedidos>
      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none">
        <div class="card">
          <div class="card-body pl-0">
            <div id="pagosxmes" class="w-100" style="height: 550px;"></div>
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
    @media screen and (max-width: 2249px) {
        .contain-table-dual {
            display: flex !important;
            width: 100% !important;
        }

        #meta,
        #metas_dp {
            max-width: 100% !important;
            width: 100% !important;
        }

        #supervisor_total table tbody tr th:nth-child(n),
        #supervisor_A table tbody tr th:nth-child(n),
        #metas_total table tbody tr th:nth-child(n) {
            width: 33.33333% !important;
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
      cargaNueva(1);
      cargaNueva(2);
      cargaNueva(3);

      setInterval(myTimer, 50000);

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
