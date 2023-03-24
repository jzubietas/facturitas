<!-- Modal -->
<div class="modal fade" id="modal-exportar-unico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">
          Exportar clientes
          @if($key === '1')
          abandonos
          @elseif($key === '2')
          recurrentes
            @elseif($key === '3')
            nuevos
            @elseif($key === '4')
            recuperado
            @elseif($key === '5')
            ABANDONO
          @elseif($key === '8')
            NULOS
            @elseif($key === '9')
                ACTIVOS

          @endif

      </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      @if($key === '1')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'ABANDONO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
      @elseif($key === '2')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'RECURRENTE','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '3')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'NUEVO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '4')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'RECUPERADO RECIENTE','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '5')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'ABANDONO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '6')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'RECUPERADO_ABANDONO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '7')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'CASI_ABANDONO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '8')
        {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'ABANDONO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}
        @elseif($key === '9')
            {!! Form::open(['route' => ['excel.clientes.reporte.multiple',['situacion'=>'ACTIVO','anio'=>'2022']], 'method' => 'POST', 'target' => 'blanck_']) !!}

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
                        @if($key === '1')
                          <input type="hidden" id="situacion" name="situacion" value="ABANDONO">
                        @elseif($key === '2')
                        <input type="hidden" id="situacion" name="situacion" value="RECURRENTE">
                          @elseif($key === '3')
                          <input type="hidden" id="situacion" name="situacion" value="NUEVO">
                          @elseif($key === '4')
                          <input type="hidden" id="situacion" name="situacion" value="RECUPERADO RECIENTE">
                          @elseif($key === '5')
                          <input type="hidden" id="situacion" name="situacion" value="ABANDONO">
                          @elseif($key === '6')
                          <input type="hidden" id="situacion" name="situacion" value="RECUPERADO_ABANDONO">
                          @elseif($key === '7')
                          <input type="hidden" id="situacion" name="situacion" value="CASI_ABANDONO">
                        @endif
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
