<!-- Modal -->
<div class="modal fade" id="modal-asignarmetallamada" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title" id="exampleModalLabel">Asignar meta a llamada: USER</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formasignarmetallamada" name="formasignarmetallamada">
        <input type="hidden" id="llamada" name="llamada">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-4">
              {!! Form::label('meta_quincena_nuevo', 'META QUINCENA NUEVO') !!}
              {!! Form::number('meta_quincena_nuevo', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta quincena nuevo']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('cliente_nuevo', 'META DE NUEVOS') !!}
              {!! Form::number('cliente_nuevo', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta de nuevos']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('cliente_nuevo_2', 'META 2 DE NUEVOS') !!}
              {!! Form::number('cliente_nuevo_2', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 2 de nuevos']) !!}
            </div>

            <div class="form-group col-lg-4">
              {!! Form::label('meta_quincena_recuperado_abandono', 'META QUINCENA REC. ABANDONO') !!}
              {!! Form::number('meta_quincena_recuperado_abandono', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta quincena recuperado abandono']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('cliente_recuperado_abandono', 'META DE REC. ABANDONO') !!}
              {!! Form::number('cliente_recuperado_abandono', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta de recuperados abandono']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('cliente_recuperado_abandono_2', 'META 2 DE REC. ABANDONO') !!}
              {!! Form::number('cliente_recuperado_abandono_2', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 2 de recuperados abandono']) !!}
            </div>

            <div class="form-group col-lg-4">
              {!! Form::label('meta_quincena_recuperado_reciente', 'META QUINCENA REC. RECIENTE') !!}
              {!! Form::number('meta_quincena_recuperado_reciente', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta quincena recuperado reciente']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('cliente_recuperado_reciente', 'META DE REC. RECIENTE') !!}
              {!! Form::number('cliente_recuperado_reciente', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta de recuperados reciente']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('cliente_recuperado_reciente_2', 'META 2 DE REC. RECIENTE') !!}
              {!! Form::number('cliente_recuperado_reciente_2', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 2 de recuperados reciente']) !!}
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Confirmar</button>
        </div>
      </form>
    </div>
  </div>
</div>
