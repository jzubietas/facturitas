<!-- Modal -->
<div class="modal fade" id="modal-direccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Dirección de envío</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Ingrese la dirección de envío del pedido: <strong class="textcode">PED000</strong></p>
      </div>

      <div class="row">
        <div class="col">

          <div class="table-responsive">

            <table id="tablaPrincipalpedidosagregar" class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">Item</th>
                  <th scope="col">Codigo Pedido</th>
                  <th scope="col">Opcion</th>                  
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>


          </div>

        </div>
        <div class="col">

          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <select id="limaprovincia" name="limaprovincia">
              <option value="">Seleccione</option>
              <option value="L">Lima</option>
              <option value="P">Provincia</option>
            </select>
          </div>

          <div class="lima">
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('celular', 'Número de contacto') !!}                     
              {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('cliente', 'Cliente') !!} 
              {!! Form::select('cliente', array()  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Producto') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Cantidad') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('direccion', 'Direccion') !!}                   
              {!! Form::text('direccion', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('referencia', 'Referencia') !!}                   
              {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('cliente', 'Distrito') !!} 
              {!! Form::select('cliente', array()  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('referencia', 'Onbservacion') !!}                   
              {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>



          </div>
          <div class="provincia">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('cliente', 'Cliente') !!} 
              {!! Form::select('cliente', array()  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('cliente', 'Oficina') !!} 
              {!! Form::select('cliente', array()  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Cantidad') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>




          </div>
          




        </div>
      </div>


          

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Cliente') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Codigo') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Producto') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Cantidad') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Cantidad') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>


      <div class="provincia">
        <div class="row">
          <div class="col">
            <h1>PROVINCIA</h1>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('provincia', 'Provincia') !!} 
              {!! Form::select('provincia', array()  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('provincia', 'Oficina') !!} 
              {!! Form::select('provincia', array() , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              {!! Form::label('nombre', 'Datos') !!}                   
              {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
            </div>

          </div>
        </div>
      </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>