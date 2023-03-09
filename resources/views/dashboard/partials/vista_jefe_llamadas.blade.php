<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste</p>
    </h2>
</div>
<br>
<br>

<div class="row">

    @include('dashboard.widgets.buscar_cliente')
    @include('dashboard.partials.vista_quitar_vidas')
  <div class="col-md-12">
    <x-tabla-list-llamada-atencion></x-tabla-list-llamada-atencion>
  </div>
{{--
    <div class="col-lg-12">
        <x-grafico-metas-mes></x-grafico-metas-mes>
    </div>

--}}

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
        <div class="card">
            <div class="card-body pl-0">
                <div class="mb-4 pb-4">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-9">
                                    {{-- <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>--}}
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
</div>

{{--<!-- Modal -->
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
      cargReporteMetasSituacionClientes();

        cargReporteMetasCobranzasGeneral();

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
