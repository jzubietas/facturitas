  <!-- Modal -->
  <div class="modal fade" id="modal-asignarmetaasesor-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Asignar meta a asesor: USER{{ $user->id }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{ Form::Open(['route' => ['users.asignarmetaasesor', $user]]) }}
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-6">
              {!! Form::label('meta_pedido', 'META DE PEDIDOS') !!}
              {!! Form::number('meta_pedido', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta del mes']) !!}
              {{-- <input type="text" name="meta_pedido" id="meta_pedido" class="form-control number" placeholder="Ingrese meta del mes..."> --}}
            </div>
            <div class="form-group col-lg-6">
              {!! Form::label('meta_cobro', 'META DE PAGOS') !!}
              {{-- {!! Form::number('meta_cobro', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta del mes']) !!} --}}
              <input type="text" name="meta_cobro" id="meta_cobro" class="form-control number" placeholder="Ingrese meta del mes...">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Confirmar</button>
        </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>
