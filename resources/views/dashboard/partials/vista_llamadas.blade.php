<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste</b></p>
    </h2>
</div>
<br>
<br>

<div class="row">
    @include('dashboard.widgets.buscar_cliente')
{{--
    <div class="col-lg-12">
        <x-grafico-metas-mes></x-grafico-metas-mes>
    </div>
--}}

</div>

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
