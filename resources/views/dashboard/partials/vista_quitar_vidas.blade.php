<div class="col-lg-6 rounded margen" style="background: #d4edda;height: fit-content;">
  <div class="card-header">Quitar vida</div>
  <div class="card-header">
    <div class="row align-items-center">
      <div class="col-md-9">
        <div class="input-group-text p-0">
          <select name="cbx_user_id_vidas" class="form-control border-secondary" id="cbx_user_id_vidas"
                  data-live-search="true">
            @if(count($lst_users_vida)>0)
              @foreach($lst_users_vida as $usuario)
                <option style="color:black"
                        value="{{$usuario->id}}">{{$usuario->identificador." - ". $usuario->name." (Vidas restantes: ". $usuario->vidas_restantes.")"}}</option>
              @endforeach
            @else
              <option style="color:black" value="">---- NO EXISTEN DATOS ----</option>
            @endif
          </select>
        </div>

      </div>
      <div class="col-md-3">
        <div class="input-group justify-content-center">
          <div class="input-group-append">
            <button type="button" class="btn btn-danger" id="buttom_quita_vida_cliente">
              <i class="fa fa-male"></i>
              Quitar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@section('js')
  <script>
    $(document).ready(function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $("#cbx_user_id_vidas").selectpicker("refresh");
      $("#buttom_quita_vida_cliente").prop('disabled', true);
      $(document).on("change", "#cbx_user_id_vidas", function () {
        $("#buttom_quita_vida_cliente").prop('disabled', false);
      })
      $(document).on("click", "#buttom_quita_vida_cliente", function () {
        $("#buttom_quita_vida_cliente").prop('disabled', true);
        var data = {}
        data.user_id = $("#cbx_user_id_vidas").val();
        $.post('{{route('quitarvidasusuario')}}', data)
          .done(function (data) {
            $("#buttom_quita_vida_cliente").prop('disabled', false);
            if (data.vidas_anteriores > 0) {
              Swal.fire(
                '',
                'Vida quitada correctamente',
                'success'
              );
              return false;
            } else {
              Swal.fire(
                'Error',
                'Ya no tiene vidas',
                'warning'
              );
              return false;
            }
          })
          .fail(function (data) {
            console.log(data)
            if (data.responseJSON.errors) {
              Swal.fire(
                '',
                Object.keys(data.responseJSON.errors).map(function (key) {
                  return `<b>${data.responseJSON.errors[key][0]}</b>`
                }).join('<hr class="my-1"><br>'),
                'error'
              )
            } else {
              Swal.fire(
                '',
                'Ocurrio un error al intentar guardar la información',
                'error'
              )
            }
          })
          .always(function () {
            $("#buttom_quita_vida_cliente").removeAttr('disabled')
          })
      })
    });
  </script>
@endsection
