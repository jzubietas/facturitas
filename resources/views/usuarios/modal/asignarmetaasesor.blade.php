  <!-- Modal -->
  <div class="modal fade" id="modal-asignarmetaasesor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar meta a asesor: USER</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

          <form id="formasignarmetaasesor" name="formasignarmetaasesor">
              <input type="hidden" id="asesor" name="asesor">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-3">
              {!! Form::label('meta_quincena', 'META QUINCENA') !!}
              {!! Form::number('meta_quincena', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 1 de la quincena']) !!}
            </div>
            <div class="form-group col-lg-3">
              {!! Form::label('meta_pedido_1', 'META DE PEDIDOS 1') !!}
              {!! Form::number('meta_pedido_1', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 1 del mes']) !!}
            </div>
              <div class="form-group col-lg-3">
                  {!! Form::label('meta_pedido_2', 'META DE PEDIDOS 2') !!}
                  {!! Form::number('meta_pedido_2', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 2 del mes']) !!}
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
