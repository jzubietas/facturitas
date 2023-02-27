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
                <div class="col-md-12">
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR DIA"
                                               label-x="Asesores" label-y="Cant. Pedidos"
                                               only-day></x-grafico-pedidos-por-dia>
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR MES"
                                               label-x="Asesores" label-y="Cant. Pedidos"></x-grafico-pedidos-por-dia>
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

      cargaNueva(1);
      cargaNueva(2);
      cargaNueva(3);

      setInterval(myTimer, 10000);

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
