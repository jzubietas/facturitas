  <!-- Modal -->
  <div class="modal fade" id="modal-editenviar-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 900px!important;">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">Editar entrega de pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{ Form::Open(['route' => ['envios.enviar', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) }}
        <div class="modal-body">
          <p>Edite los datos de la entrega del pedido: <strong>PED00{{ $pedido->id }}</strong></p>
        </div>
        <div style="margin: 10px">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h5>Información:</h5>
                      </div><br><br>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('fecha_envio_doc_fis', 'Fecha de envío') !!}
                            {!! Form::date('fecha_envio_doc_fis', '', ['class' => 'form-control', 'id' => 'fecha_envio_doc_fis', 'disabled']) !!}
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('fecha_recepcion', 'Fecha de entrega') !!}
                            {!! Form::date('fecha_recepcion', $pedido->fecha_recepcion, ['class' => 'form-control', 'id' => 'fecha_recepcion']) !!}
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('foto1', 'Foto de recibido 1') !!}
                              @csrf
                              {!! Form::file('foto1', ['class' => 'form-control-file', 'accept' =>'image/*']) !!}
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('foto2', 'Foto de recibido 2') !!}
                            @csrf
                            {!! Form::file('foto2', ['class' => 'form-control-file', 'accept' =>'image/*']) !!}
                          </div>
                          {!! Form::hidden('pfoto1', $pedido->foto1, ['class' => 'form-control', 'id' => 'pfoto1']) !!}
                          @if ($pedido->foto1 != null)
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><br>
                            <p>FOTO 1</p>
                            <img src="{{ asset('storage/entregas/' . $pedido->foto1) }}" alt="{{ $pedido->foto1 }}" height="400px" width="400px" class="img-thumbnail">
                            <p><a href="{{ route('envios.descargarimagen', $pedido->foto1) }}">Descargar</a></p>
                          </div>
                          @endif
                          {!! Form::hidden('pfoto2', $pedido->foto2, ['class' => 'form-control', 'id' => 'pfoto2']) !!}
                          @if ($pedido->foto2 != null)
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><br>
                            <p>FOTO 2</p>
                            <img src="{{ asset('storage/entregas/' . $pedido->foto2) }}" alt="{{ $pedido->foto2 }}" height="400px" width="400px" class="img-thumbnail">
                            <p><a href="{{ route('envios.descargarimagen', $pedido->foto2) }}">Descargar</a></p>
                          </div>
                          @endif
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('condicion', 'Estado') !!}
                            <select name="condicion" class="form-control" id="condicion">
                              <option value="1"  {{ ($pedido->condicion_envio == "1" ? "selected" : "") }}>PENDIENTE DE ENVIO</option>
                              <option value="2" {{ ($pedido->condicion_envio == "2" ? "selected" : "") }}>EN REPARTO</option>
                              @if($pedido->destino == "PROVINCIA")
                                <option value="REGISTRO" {{ ($pedido->condicion_envio == "REGISTRO" ? "selected" : "") }}>REGISTRO</option>
                                <option value="TRASLADO" {{ ($pedido->condicion_envio == "TRASLADO" ? "selected" : "") }}>TRASLADO</option>
                                <option value="EN TIENDA" {{ ($pedido->condicion_envio == "EN TIENDA" ? "selected" : "") }}>EN TIENDA</option>
                              @endif
                              <option value="3" {{ ($pedido->condicion_envio == "3" ? "selected" : "") }}>ENTREGADO</option>
                            </select>
                          </div>
                          @if($pedido->destino == "PROVINCIA")
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            {!! Form::label('trecking', 'N° Trecking/Guia') !!}
                            {!! Form::text('trecking', $pedido->trecking, ['class' => 'form-control', 'id' => 'cant_compro']) !!}
                          </div>
                          @endif
                          {!! Form::hidden('vista', 'ENTREGADOS', ['class' => 'form-control', 'id' => 'vsita']) !!}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
        </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>
