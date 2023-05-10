  <!-- Modal -->
  <div class="modal fade" id="modal-add-pagos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Pagos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}--}}
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        <div class="modal-body">

          <div class="form-row">
            <div class="form-group col-lg-4">
              {!! Form::label('pbanco', 'Banco') !!}
              {!! Form::select('pbanco', $bancos , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>

            <div class="form-group col-lg-4">
              {!! Form::label('titulares', 'Titulares') !!}
              {!! Form::select('titulares', $titulares , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>

            <div class="form-group col-lg-4">
              {!! Form::label('tipotransferencia', 'Tipo Movimiento') !!}
              {!! Form::select('tipotransferencia[]', $tipotransferencia, '', ['class' => 'form-control selectpicker border border-secondary', 'id'=>'tipotransferencia','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>


            <div class="form-group col-lg-6 banco_procedencia">
              {!! Form::label('pbancoprocedencia', 'Banco Procedencia') !!}
              {!! Form::select('pbancoprocedencia', $bancos_procedencia , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>

            <div class="form-group col lg-6 banco_procedencia_otro" style="font-size: 18px">
              {!! Form::label('otro_bancoprocedencia', 'Otro Banco') !!}
              {!! Form::textarea('otro_bancoprocedencia', '', ['class' => 'form-control', 'rows' => '1', 'placeholder' => 'Otro banco de procedencia']) !!} {{--, 'required' => 'required'--}}
            </div>




          </div>
          <div class="form-row">
            <div class="form-group col-lg-4">
              {!! Form::label('pmonto', 'Monto pagado') !!}
              <input type="text" name="pmonto" id="pmonto" class="form-control number" placeholder="Monto pagado...">
            </div>

            <div class="form-group col lg-4">
              {!! Form::label('pfecha', 'Fecha de voucher') !!}
              {!! Form::date('pfecha', '', ['class' => 'form-control']) !!}
              {{-- <input name="pfecha" type="date" class="form-control">      \Carbon\Carbon::now()  --}}
            </div>

            <div class="form-group col-lg-4 operacion_contenedor">
              {!! Form::label('poperacion', 'Núm. operación') !!}
                <input type="text" name="poperacion" id="poperacion" class="form-control" placeholder="Operacion..." required>
            </div>
          </div>
          <div class="form-row">

            <div class="form-group col-lg-12 ">
              {!! Form::label('pnota', 'Nota') !!}
                <input type="text" name="pnota" id="pnota" class="form-control" placeholder="Nota...">
            </div>

            <div class="form-group col-lg-6">
              <div class="image-wrapper">
                <img id="picture" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago" width="250px">
              </div>
            </div>

            <div class="form-group col-lg-6">
              {!! Form::label('pimagen', 'Imagen') !!}
              {{--<input type="file" id="pimagen" name="pimagen[]" multiple=""/>--}}

              {!! Form::file('pimagen', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="add_pago" >Agregar</button>
        </div>
        {{-- {{ Form::Close() }} --}}
      </div>
    </div>
  </div>
