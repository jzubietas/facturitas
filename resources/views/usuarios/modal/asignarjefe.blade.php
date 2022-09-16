  <!-- Modal -->
  <div class="modal fade" id="modal-asignarjefe-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar jefe de operaciones</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{ Form::Open(['route' => ['users.asignarjefe', $user]]) }}
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('supervisor', 'Jefe de operación') !!}
              {!! Form::select('supervisor', $jefes, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'jefe']) !!}
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Confirmar</button>
        </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>
