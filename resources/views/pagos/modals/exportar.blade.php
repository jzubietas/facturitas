<!-- Modal -->
<div class="modal fade" id="modal-exportar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- {!! Form::open(['route' => ['entregadosporfechasexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!} --}}
        @if($key === '1')
          {!! Form::open(['route' => ['pagosExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '2')
          {!! Form::open(['route' => ['mispagosExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '3')
          {!! Form::open(['route' => ['pagosincompletosExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '4')
          {!! Form::open(['route' => ['pagosobservadosExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '5')
          {!! Form::open(['route' => ['pagosaprobadosExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
          @elseif($key === '6')
          {!! Form::open(['route' => ['porrevisarExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
          @elseif($key === '7')
          {!! Form::open(['route' => ['pagosabonadosExcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @endif
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-lg-12" style="text-align: center; font-size:16px">                  
                    <div class="form-row">
                      <div class="col-lg-12">
                        {!! Form::label('anio', 'Elija el rango de fechas del reporte') !!} <br><br>
                        <div class="form-row">
                          <div class="col-lg-6">
                            <label>Fecha inicial&nbsp;</label>
                            {!! Form::date('desde', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                          </div>
                          <div class="col-lg-6">
                            <label>Fecha final&nbsp;</label>
                            {!! Form::date('hasta', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
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