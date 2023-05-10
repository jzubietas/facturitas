  <!-- Modal -->
  {{--<div class="modal fade" id="modal-asignarmetaencargado-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
<div class="modal fade" id="modal-asignarmetaencargado-id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          {{--<h5 class="modal-title" id="exampleModalLabel">Asignar meta al ENCARGADO: USER{{ $user->id }}</h5>--}}
          <h5 class="modal-title" id="exampleModalLabel">Asignar meta al ENCARGADO: USER</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{{ Form::Open(['route' => ['users.asignarmetaencargado', $user]]) }}--}}
        <form action="">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-lg-3">
              {!! Form::label('meta_pedido_1', 'META DE PEDIDOS 1') !!}
              {!! Form::number('meta_pedido_1', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 1 del mes']) !!}
              {{-- <input type="text" name="meta_pedido" id="meta_pedido" class="form-control number" placeholder="Ingrese meta del mes..."> --}}
            </div>
              <div class="form-group col-lg-3">
                  {!! Form::label('meta_pedido_2', 'META DE PEDIDOS 2') !!}
                  {!! Form::number('meta_pedido_2', null, ['class' => 'form-control', 'min' => 0, 'placeholder' => 'Ingrese meta 2 del mes']) !!}
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
          </form>
        {{--{{ Form::Close() }}--}}
      </div>
    </div>
  </div>
