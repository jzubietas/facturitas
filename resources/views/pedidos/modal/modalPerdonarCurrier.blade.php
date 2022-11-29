<!-- Modal -->
<div class="modal fade" id="modal-perdonar_currier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        
        <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formperdonarcurrier" name="formperdonarcurrier">
      
            <div class="card-body">
              <div class="form-row">
                

                <div class="form-group col lg-12">
                  {!! Form::label('motivo', 'Ingrese el motivo para perdonar el currier(Max. 250 caracteres)') !!}
                  {!! Form::textarea('motivo',null, ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Motivo']) !!}
                </div>

              </div>
            </div>    
            <div class="card-footer text-center">
              <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Perdonar deuda</button>
            </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>