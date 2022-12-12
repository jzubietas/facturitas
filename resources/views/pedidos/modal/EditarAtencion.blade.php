  <!-- Modal -->
  <div class="modal fade" id="modal-editar-atencion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">Editar Atención</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- Form::Open(['route' => ['pedidos.atender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
        <form id="formularioatender" name="formularioatender" enctype="multipart/form-data">
          <input type="hidden" id="hiddenAtender" name="hiddenAtender">
        <div class="modal-body">
          <p>Detalles del pedido: <strong class="textcode">PED00</strong></p>
        </div>
        <div style="margin: 10px">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <div class="row">
                      <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <h5><b>Archivos adjuntos:</b></h5>
                      </div>
                      <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" id="listado_adjuntos">

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6" style="padding: 24px;">
                {!! Form::label('envio_doc', 'Documento enviado') !!}
                @csrf
                {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto', 'multiple']) !!}
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6" style="padding: 24px;">
                {!! Form::label('fecha_envio_doc', 'Fecha de envío') !!}
                {!! Form::text('fecha_envio_doc', '11-12-2022', ['class' => 'form-control', 'id' => 'fecha_envio_doc', 'disabled']) !!}
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6" style="padding: 24px;">
                {!! Form::label('cant_compro', 'Cantidad de comprobantes enviados') !!}
                {!! Form::number('cant_compro', '1', ['class' => 'form-control', 'id' => 'cant_compro', 'step'=>'1', 'min' => '0']) !!}
            </div>
            <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6 text-center" style="padding: 24px;">
              <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
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
