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


      @if($key === '1')
        {!! Form::open(['route' => ['envios.motorizadoconfirmar.Excel'], 'method' => 'POST', 'target' => '_blank','id'=>'form_motorizadoconfirmar']) !!}
      @elseif($key === '2')
        {!! Form::open(['route' => ['envios.recepcionmotorizado.Excel'], 'method' => 'POST', 'target' => '_blank','id'=>'form_recepcionmotorizado']) !!}
        @elseif($key === '3')
        {!! Form::open(['route' => ['envios.recepcionmotorizado.Excel',['historial'=>1]], 'method' => 'POST', 'target' => '_blank','id'=>'form_recepcionmotorizado']) !!}
      @endif


            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center; font-size:16px">
                  <div class="form-row">
                    <div class="col-lg-12">
                      {!! Form::label('anio', 'Seleccion de Motorizado y fechas') !!} <br><br>
                      <div class="form-row">
                        <div class="col-lg-6">
                          <label>Motorizado&nbsp;</label>

                          {!! Form::select('user_motorizado', $users_motorizado, '', ['id'=>'user_motorizado','class' => 'form-control selectpicker border border-secondary', 'placeholder' => ' SELECCIONE USUARIO ']) !!}
                        </div>
                        <div class="col-lg-6">
                          <label>Fecha&nbsp;</label>
                          {!! Form::date('fecha_envio', \Carbon\Carbon::now(), ['id'=>'fecha_envio','class' => 'form-control']); !!}
                        </div>
                          <select id="condicion_envio" name="condicion_envio" class="d-none">

                              <option value="19">19</option>
                              <option value="18">18</option>
                          </select>
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
