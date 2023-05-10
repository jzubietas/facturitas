  <!-- Modal -->
  <div class="modal fade" id="modal-asignarjefellamadas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar Jefe de Llamadas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- Form::Open(['route' => ['users.asignarsupervisor', $user]]) --}}
        <form id="formjefellamadas" name="formjefellamadas">
        <input type="hidden" id="hiddenIdjefellamadas" name="hiddenIdjefellamadas">
        <span class="textcode d-none"></span>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('jefellamadas', 'Jefe Llamadas') !!}
              {!! Form::select('jefellamadas', $jefellamadas, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'jefellamadas']) !!}
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
