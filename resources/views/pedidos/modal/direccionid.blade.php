<!-- Modal -->
<div class="modal fade" id="modal-direccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" >
    <div class="modal-content" style="width:1100px;margin:0 auto;">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Dirección de envío</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formdireccion" name="formdireccion">
      <div class="modal-body">
        <p>Ingrese la dirección de envío del pedido: <strong class="textcode">PED000</strong></p>

        <input id="cliente_id" name="cliente_id" value="" type="hidden">

        <div class="row">
          <div class="col">
  
            <div class="table-responsive">
  
              <table id="tablaPrincipalpedidosagregar" class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Codigo Pedido</th>
                    <th scope="col">Producto</th>                  
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
  
  
            </div>
  
          </div>
          <div class="col">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('limaprovincia', 'Seleccione') !!} 
                {!! Form::select('limaprovincia', array('L'=>'Lima','P'=>'Provincia')  , null, ['class' => 'form-control border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
              </div>
  
            </div>
            <!---->

            
            <div class="lima d-none">
              <div class="row">
                <div class="col">
  
                  <div class="row">
                    <div class="col">
                      <h1>LIMA</h1>

                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('nombre', 'Nombre') !!}                   
                        {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                      </div>

                  
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('celular', 'Número de contacto') !!}                     
                        {!! Form::number('celular', null, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)']) !!}
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
                        {!! Form::label('distrito', 'Distrito') !!} 
                        {!! Form::select('distrito', $distritos , null, ['class' => 'form-control border selectpicker border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
                      </div>
  
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('observacion', 'Observacion') !!}                   
                        {!! Form::text('observacion', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                      </div>
                      
                    </div>
                  </div>
      
                </div>
              </div>
              
            </div>
            <!---->
            <div class="provincia d-none">
              <div class="row">
                <div class="col">
                  <h1>PROVINCIA</h1>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('departamento', 'departamento') !!} 
                    {!! Form::select('departamento', $departamento  , null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}   
                  </div>
      
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('oficina', 'Oficina') !!} 
                    {!! Form::text('oficina', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                  </div>
      
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('tracking', 'Tracking') !!}                   
                    {!! Form::text('tracking', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                  </div>
  
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('numregistro', 'numregistro') !!}                   
                    {!! Form::text('numregistro', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                  </div>
  
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    {!! Form::label('rotulo', 'Rotulo') !!}
                    @csrf
                    {!! Form::file('rotulo', ['class' => 'form-control-file', 'accept' =>'pdf/*']) !!}  
                  </div>
      
                 
                </div>
              </div>
            </div>
  
          </div>
        </div>
      </div>
     

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="direccionConfirmar">Confirmar</button>
      </div>
      {{ Form::Close() }}
    </div>
  </div>
</div>