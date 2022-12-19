  <!-- Modal -->
  <div class="modal fade" id="modal-editatender-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="exampleModalLabel">Editar atención de pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{ Form::Open(['route' => ['operaciones.editatender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) }}
        <div class="modal-body">
          <p>Editar <strong>ATENCION</strong> del pedido: <strong>PED00{{ $pedido->id }}</strong></p>
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
                          {{-- @if ($pedido->envio_doc) --}}
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('envio_doc', 'Documento adjuntado') !!}
                              @foreach($imagenes as $img)
                                @if ($img->pedido_id == $pedido->id)
                                  <p>
                                    {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}
                                      <a target="_blank" download href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
                                    <a href="" data-target="#modal-delete-adjunto-{{ $img->id }}" data-toggle="modal">  <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></a>
                                  </p>
                                @endif
                                @include('pedidos.modal.DeleteAdjunto')
                              @endforeach
                            </div>
                          {{-- @endif  --}}

                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('envio_doc', 'Documento enviado') !!}
                              @csrf
                              {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto', 'multiple']) !!}
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('fecha_envio_doc', 'Fecha de envío') !!}
                              {!! Form::text('fecha_envio_doc', $pedido->fecha_envio_doc, ['class' => 'form-control', 'id' => 'fecha_envio_doc', 'disabled']) !!}
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              {!! Form::label('cant_compro', 'Cantidad de comprobantes enviados') !!}
                              {!! Form::number('cant_compro', $pedido->cant_compro, ['class' => 'form-control', 'id' => 'cant_compro', 'step'=>'1', 'min' => '0']) !!}
                          </div>
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
