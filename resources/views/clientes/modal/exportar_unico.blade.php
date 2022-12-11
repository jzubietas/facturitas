<!-- Modal -->
<div class="modal fade" id="modal-exportar-unico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Exportar clientes abandonos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      @if($key === '1')
        {!! Form::open(['route' => ['clientesabandonoExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
      @elseif($key === '2')
        {!! Form::open(['route' => ['entregadosporfechasexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '3')
        {!! Form::open(['route' => ['nuevosporfechasexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '4')
        {!! Form::open(['route' => ['entregadosporfechasexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
      @endif
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center; font-size:16px">                  
                  <div class="form-row">
                    <div class="col-lg-12">
                      {!! Form::label('anio', 'Elija el rango de fechas del reporte') !!} <br><br>
                      <div class="form-row">
                        <div class="col-lg-12">
                          {!! Form::label('anio', 'Elija un año del reporte') !!} <br><br>
                          {!! Form::select('anio', $anios, $dateY-1, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----', 'required'=>'required']) !!}
                        </div>
                      </div>
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