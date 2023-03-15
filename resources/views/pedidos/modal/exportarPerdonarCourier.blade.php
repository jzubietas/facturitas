<!-- Modal -->
<div class="modal fade" id="modal-exportar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        {{-- <h5 class="modal-title" id="exampleModalLabel">Exportar pedidos ENTREGADOS</h5> --}}
        <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      @if($key === '1')
        {!! Form::open(['route' => ['pedidosPerdonarCourierExcel'], 'method' => 'GET', 'target' => 'blanck_']) !!}
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
