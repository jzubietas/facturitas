  <!-- Modal -->
  <div class="modal fade" id="modal-veradjunto-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px!important;">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Ver adjuntos de atención de pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Adjuntos del pedido: <strong>PED00{{ $pedido->id }}</strong></p>
        </div>
        <div style="margin: 10px">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                        {!! Form::label('envio_doc', 'Documento(s) adjuntado(s) del pedido') !!}
                          @foreach($imagenespedido as $img)
                            @if ($img->pedido_id == $pedido->id)
                              @if($img->adjunto <> "logo_facturas.png")
                                <p>
                                  {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}

                                    <a target="_blank" download href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
                                </p>
                              @endif
                            @endif
                          @endforeach
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
        </div>
      </div>
    </div>
  </div>
