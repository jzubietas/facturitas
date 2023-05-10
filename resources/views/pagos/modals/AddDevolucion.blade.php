  <!-- Modal -->
  <div class="modal fade" id="modal-add-devolucion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Devolucion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}--}}
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        <div class="modal-body">

          <div class="form-row">
              <div class="form-group col-lg-12">
                  {!! Form::label('bank_destino', 'Banco de la cuenta del cliente') !!}
                  {!! Form::select('bank_destino', $bancos , '0', ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
              </div>
              <div class="form-group col-lg-12">
                  {!! Form::label('bank_number', 'Numero de su cuenta bancaria') !!}
                  {!! Form::input('text','bank_number', null,  ['class' => 'form-control']) !!}
              </div>
              <div class="form-group col-lg-12">
                  {!! Form::label('bank_number', 'Titular') !!}
                  {!! Form::input('text','bank_titular', null,  ['class' => 'form-control']) !!}
              </div>
              <div id="devolucion_message_response">
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="btnDevolucion" >Registrar devolucion</button>
        </div>
        {{-- {{ Form::Close() }} --}}
      </div>
    </div>
  </div>
