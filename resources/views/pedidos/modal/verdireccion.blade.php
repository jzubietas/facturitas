  <!-- Modal -->
  <div class="modal fade" id="modal-verdireccion-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px!important;">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Ver dirección de envío del pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Dirección para el pedido: <strong>PED00{{ $pedido->id }}</strong></p>
        </div>
        <div style="margin: 10px">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                      @foreach($direcciones as $direccion)
                        @if ($direccion->pedido_id == $pedido->id)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          {!! Form::label('nombre', 'Nombre de quien recibe el sobre') !!}                      
                          {!! Form::text('direccion', $direccion->nombre, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          {!! Form::label('celular', 'Número de contacto') !!}                    
                          {!! Form::text('direccion', $direccion->celular, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          {!! Form::label('distrito', 'Distrito') !!}                      
                          {!! Form::text('direccion', $direccion->distrito, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          {!! Form::label('direccion', 'Dirección') !!}                      
                          {!! Form::text('direccion', $direccion->direccion, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          {!! Form::label('referencia', 'Referencia') !!}                      
                          {!! Form::text('referencia', $direccion->referencia, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        @endif
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
