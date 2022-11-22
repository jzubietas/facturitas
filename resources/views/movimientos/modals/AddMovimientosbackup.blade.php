  <!-- Modal -->
  <div class="modal fade" id="modal-add-movimientos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Agregar movimientos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        {!! Form::open(['route' => 'movimientos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
        <div class="modal-body">

          <div class="form-row">
          <div class="form-group col-lg-6" style="font-size: 18px">
              {!! Form::label('banco', 'Banco') !!}
              {!! Form::select('banco', $bancos , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>
            <div class="form-group col-lg-6" style="font-size: 18px">
              {!! Form::label('tipotransferencia', 'Tipo Movimiento') !!}              
              {!! Form::select('tipotransferencia', $tipotransferencia, '', ['class' => 'form-control selectpicker border border-secondary', 'id'=>'tipotransferencia','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}  
            </div>
            <div class="form-group col lg-12 descrip_otros" style="font-size: 18px">
              {!! Form::label('descrip_otros', 'Ingrese la descripcion para Movimiento Otros (Max. 70 caracteres)') !!}
              {!! Form::textarea('descrip_otros', '', ['class' => 'form-control', 'rows' => '1', 'placeholder' => 'Descripcion Otros']) !!} {{--, 'required' => 'required'--}}
            </div>
            <div class="form-group col-lg-12" style="font-size: 18px">
              {!! Form::label('titulares', 'Titulares') !!}
              {!! Form::select('titulares', $titulares , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>            
          </div>          
          <div class="form-row">
            <div class="form-group col-lg-6" style="font-size: 18px">
              {!! Form::label('monto', 'Monto pagado') !!}
              <input type="text" name="monto" id="monto" class="form-control number" placeholder="Monto pagado...">
            </div>
            
            <div class="form-group col lg-6" style="font-size: 18px">
              {!! Form::label('fecha', 'Fecha de voucher') !!}
              {!! Form::date('fecha', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
              {{-- <input name="pfecha" type="date" class="form-control"> --}}
            </div>
            {{-- <div class="form-group col-lg-6">
              {!! Form::label('pimagen', 'Imagen') !!}
              {!! Form::file('pimagen', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-6">
              <div class="image-wrapper">
                <img id="picture" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" height="250px" width="250px">
              </div>
            </div> --}}
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          {{-- <button type="submit" class="btn btn-success" id="add_pago" data-dismiss="modal">Agregar</button> --}}
          <button id="registrar_movimientos" type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
