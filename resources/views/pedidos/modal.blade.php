<!-- Modal -->
<div class="modal fade" id="modal-delete-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px!important;">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel">Anular pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::Open(['route' => ['pedidos.destroy', $pedido], 'method' => 'delete', 'id'=>'formulario']) }}
      <div class="modal-body">
        {{-- <div class="form-row"> --}}
          <div class="form-group col-lg-12">
            <p style="text-align: justify;">Confirme si desea <strong>ANULAR</strong> el pedido: <strong>PED000{{ $pedido->id }}</strong>.</p>
          </div>
          <div class="form-group col lg-12">
            {!! Form::label('motivo', 'Ingrese el motivo de la anulación del pedido(Max. 250 caracteres)') !!}
            {!! Form::textarea('motivo', $pedido->motivo, ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Motivo', 'required' => 'required']) !!}
          </div>
          <div class="form-group col lg-12">
            {!! Form::label('responsable', 'Responsable de la anulación') !!}
            {!! Form::text('responsable', $pedido->responsable, ['class' => 'form-control', 'placeholder' => 'Responsable', 'required' => 'required','readonly']) !!}
          </div>
        {{-- </div> --}}
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>
