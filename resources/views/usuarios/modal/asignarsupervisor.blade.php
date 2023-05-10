  <!-- Modal -->
  <div class="modal fade" id="modal-asignarsupervisor-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar encargado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>}}
         Form::Open(['route' => ['users.asignarsupervisor', $user]]) 
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
               {!! Form::label('supervisor', 'Encargado') !!} 
               {!! Form::select('supervisor', $supervisores, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'supervisor']) !!} 
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Confirmar</button>
        </div>
         Form::Close() 
      </div>
    </div>
  </div>


  <div class="modal fade" id="modal-asignarsupervisor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar Supervisor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        Form::Open(['route' => ['users.asignarsupervisor', $user]]) 
        <form id="formsupervisor" name="formsupervisor">
        <input type="hidden" id="hiddenIdsupervisor" name="hiddenIdsupervisor">
        <span class="textcode d-none"></span>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              {!! Form::label('supervisores', 'Supervisor') !!}
              {!! Form::select('supervisores', $supervisores, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'id' => 'supervisores']) !!}
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Confirmar</button>
        </div>
         Form::Close() 
        </form>
      </div>
    </div>
  </div>
