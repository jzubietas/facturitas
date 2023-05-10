  <!-- Modal -->
  <div class="modal fade" id="modal-editar-get" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Actualizar Detalle de Pagos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}--}}
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        <div class="modal-body">

            <input type="hidden" id="conciliarupdate" name="conciliarupdate">
            <input type="hidden" id="itemupdate" name="itemupdate">

          <div class="form-row">
            <div class="form-group col-lg-4">
              {!! Form::label('pbanco', 'Banco') !!}
              {!! Form::select('pbanco', $bancos , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>

            <div class="form-group col-lg-4">
              {!! Form::label('titulares', 'Titulares') !!}
              {!! Form::select('titulares', $titulares , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>

            <div class="form-group col lg-4">
                {!! Form::label('pfecha', 'Fecha de voucher') !!}
                {!! Form::date('pfecha', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                {{-- <input name="pfecha" type="date" class="form-control"> --}}
              </div>
          </div>
          

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="edit_dp" >Actualizar</button>
        </div>
        {{-- {{ Form::Close() }} --}}
      </div>
    </div>
  </div>
