  <!-- Modal -->
  <div class="modal fade" id="modal-observacion-{{ $contrato->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">OBSERVACIONES</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{ Form::Open(['route' => ['contrato.observacion', $contrato]]) }}
        <div class="modal-body">
          <p>INGRESE LAS OBSERVACIONES PERTINENTES SOBRE EL PRESENTE CONTRATO <b>CON00{{ $contrato->id }}</b></p>
          {!! Form::textarea('observacion', $contrato->observacion, ['class' => 'form-control', 'rows' => '10', 'placeholder' => 'Ingrese observaciones']) !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Confirmar</button>
        </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>
