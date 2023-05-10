<!-- Modal -->
<div class="modal fade" id="modal-exportar2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Exportar lista de base fría por asesor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => ['basefriaporasesorExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center; font-size:16px">
                  <div class="form-row">
                    <div class="col-lg-12">
                      {!! Form::label('user_id', 'Elija un asesor') !!} <br><br>
                      {!! Form::select('user_id', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'required'=>'required']) !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
