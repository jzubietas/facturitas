  <!-- Modal -->
  <div class="modal fade" id="modal-copiar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1000px!important;">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">COPIAR</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <span class="textcode d-none"></span>
            <div style="margin: 10px">
                <div class="card">
                    <div class="border rounded card-body border-secondary">
                        <div class="card-body">

                            <form id="formcopiar" name="formcopiar">
                                {!! Form::label('pedido_copiar', 'Copie el siguiente texto para compartirlo a los clientes') !!}
                                {!! Form::textarea('pedido_copiar', '', ['class' => 'form-control', 'rows' => '6', 'placeholder' => 'Descripcion Otros']) !!}
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal-footer">
            <a href="{{route('pedidos.index')}}" class="btn btn-primary">Mis pedidos</a>
          <button type="button" id="cerrar-modal-copiar" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          {{--<button type="button" id="btncopiar" class="btn btn-info">Copiar</button>--}}
        </div>

        </form>

      </div>
    </div>
  </div>
