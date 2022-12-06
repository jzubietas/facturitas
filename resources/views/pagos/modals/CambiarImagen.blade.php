  <!-- Modal -->
  <div class="modal fade" id="modal-cambiar-imagen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header ">
          <h5 class="modal-title" id="exampleModalLabel">Cambiar Imagen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}--}}
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        
        <div class="modal-body">

        <input type="hidden" value="" name="DPConciliar" id="DPConciliar">
        <input type="hidden" value="" name="DPitem" id="DPitem">

        <div class="form-row">

          <div class="col">
            Titular
            <input type="text" class="form-control modalimagen_titular bg-primary" readonly>
          </div>
          <div class="col">
            Banco
            <input type="text" class="form-control modalimagen_banco bg-primary" readonly>
          </div>
          <div class="col">
            Fecha
            <input type="text" class="form-control modalimagen_fecha bg-primary" readonly>
          </div>
          <div class="col">
            Monto
            <input type="text" class="form-control modalimagen_monto bg-primary" readonly>
          </div>


          <div class="col d-none">
            item
            <input type="text" class="form-control modalimagen_item" readonly>
          </div>
          <div class="col d-none">
            pago
            <input type="text" class="form-control modalimagen_pago" readonly>
          </div>
          
        </div>
        <br>

          <div class="form-row">
            
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
          <button type="button" class="btn btn-info" id="change_imagen" >Agregar</button>
        </div>
        
      </div>
    </div>
  </div>
