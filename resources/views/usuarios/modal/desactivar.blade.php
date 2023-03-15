  <!-- Modal -->
  {{--<div class="modal fade" id="modal-desactivar-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
  <div class="modal fade" id="modal-desactivar-id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="exampleModalLabel">Desactivar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{{ Form::Open(['route' => ['users.destroy', $user], 'method' => 'delete']) }}--}}
        <form id="frmDesactivarUsuario">
        <div class="modal-body">
          {{--<p style="text-align: center; font-size:20px;">Confirme si desea <strong>DESACTIVAR</strong> usuario: <br> <strong>USER00{{ $user->id }} - {{ $user->name }}</strong></p>--}}
          <p style="text-align: center; font-size:20px;">Confirme si desea <strong>DESACTIVAR</strong> usuario: <br> <strong>USER00<span id="txtDesIdUsuario"></span> - <span id="txtDesNameUsuario"></span></strong></p>
          {{--{!! Form::hidden('estado', '0') !!}--}}
            <input type="hidden" id="hidEstado" name="hidEstado" value="0">
            <input type="hidden" id="hidDesIdUsuario" name="hidDesIdUsuario">
            <input type="hidden" id="hidDesNameUsuario" name="hidDesNameUsuario">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-danger">Confirmar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
