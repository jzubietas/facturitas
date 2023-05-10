  <!-- Modal -->
  <div class="modal fade" id="modal-asignarasesor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="exampleModalLabel">Asignar Asesor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{ Form::Open(['route' => ['users.asignaroperario', $user]]) --}}
        <form id="formasesor" name="formasesor">
        <input type="hidden" id="hiddenIdasesor" name="hiddenIdasesor">
        <span class="textcode d-none"></span>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('asesor', 'Asesor') !!}
              {!! Form::select('asesor', $asesores, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'asesor']) !!}
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
