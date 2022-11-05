  <!-- Modal -->
  <div class="modal fade" id="modal-asignarllamadas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar Llamadas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- Form::Open(['route' => ['users.asignarsupervisor', $user]]) --}}
        <form id="formllamadas" name="formllamadas">
        <input type="hidden" id="hiddenIdllamadas" name="hiddenIdllamadas">
        <span class="textcode d-none"></span>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('llamadas', 'Llamadas') !!}
              {!! Form::select('llamadas', $llamadas, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'llamadas']) !!}
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Confirmar</button>
        </div>
        {{-- Form::Close() --}}
        </form>
      </div>
    </div>
  </div>
