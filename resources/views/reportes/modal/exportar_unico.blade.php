<!-- Modal -->
<div class="modal fade" id="modal-exportar-unico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">
          Exportar Analisis
         
      </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
            @if($key === '1')
              {!! Form::open(['route' => ['analisisExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            @endif
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center; font-size:16px">                  
                  <div class="form-row">
                    
                  </div>
                </div>
              </div>
            </div>    
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar Analisis</button>
            </div>
      {!! Form::close() !!}

    </div>
  </div>
</div>