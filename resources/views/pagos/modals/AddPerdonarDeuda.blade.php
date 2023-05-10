  <!-- Modal -->
  <div class="modal fade" id="modal-add-perdonar-deuda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">Perdonar Deudas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{--{!! Form::open(['route' => 'pagos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}--}}
        {{-- {{ Form::Open(['route' => ['clientes.destroy', $cliente], 'method' => 'delete']) }} --}}
        <div class="modal-body">

          <div class="form-row">
            <div class="form-group col-lg-4">
              {!! Form::label('pmontoperdonar', 'Monto pagado') !!}
              <input type="text" name="pmontoperdonar" id="pmontoperdonar" class="form-control number" placeholder="Monto pagado a perdonar...">
            </div>
            
            <div class="form-group col lg-4">
              {!! Form::label('pfechaperdonar', 'Fecha de voucher') !!}
              {!! Form::date('pfechaperdonar', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
              {{-- <input name="pfecha" type="date" class="form-control"> --}}
            </div>
          </div>
          <div class="form-row">

            <div class="form-group col-lg-4">
              {!! Form::label('pimagen1', 'Imagen 1') !!}              
              {!! Form::file('pimagen1', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('pimagen2', 'Imagen 2') !!}              
              {!! Form::file('pimagen2', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
            <div class="form-group col-lg-4">
              {!! Form::label('pimagen3', 'Imagen 3') !!}              
              {!! Form::file('pimagen3', ['class' => 'form-control-file', 'accept' => 'image/*']) !!}
            </div>
          </div>

          <div class="form-row">
             
            <div class="form-group col-lg-4">
              <div class="image-wrapper">
                <img id="picture1" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago 1" width="250px">
              </div>
            </div>

            <div class="form-group col-lg-4">
              <div class="image-wrapper">
                <img id="picture2" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago 2" width="250px">
              </div>
            </div>

            <div class="form-group col-lg-4">
              <div class="image-wrapper">
                <img id="picture3" src="{{asset('imagenes/logo_facturas.png')}}" alt="Imagen del pago 3" width="250px">
              </div>
            </div>
            
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-info" id="add_pago_perdonar" >Agregar</button>
        </div>
        {{-- {{ Form::Close() }} --}}
      </div>
    </div>
  </div>
