<div class="modal fade" id="modal-add-movimientos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Agregar movimientos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      
      {!! Form::open(['route' => 'movimientos.store','enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}

      <div class="modal-body">

        <div id="multi-step-form-container">
          <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0 d-none">
            <!-- Step 1 -->
            <li class="form-stepper-active text-center form-stepper-list" step="1">
                <a class="mx-2">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
                    <div class="label">TITULAR</div>
                </a>
            </li>
            <!-- Step 2 -->
            <li class="form-stepper-unfinished text-center form-stepper-list" step="2">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>2</span>
                    </span>
                    <div class="label text-muted">Social Profiles</div>
                </a>
            </li>
            <!-- Step 3 -->
            <li class="form-stepper-unfinished text-center form-stepper-list" step="3">
                <a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>3</span>
                    </span>
                    <div class="label text-muted">Personal Details</div>
                </a>
            </li>
          </ul>
          <!-- Step Wise Form Content -->

          <section id="step-1" class="form-step">
            
            <!-- Step 1 input fields -->

            <div class="form-row">
              <div class="form-group col-lg-6 mx-auto" style="font-size: 18px">

                    <div class="btn-group " role="group" aria-label="Basic example">
                      @foreach($titulares as $titular)
                        <button class="button mx-2 p-2 btn-navigate-titular"  type="button" step_number="2" titular="{{$titular}}">{{$titular}}</button>
                      @endforeach
                      
                    </div>
                    
              </div>
            </div>
            
            <div class="mt-3">
                <button class="button btn-navigate-form-step d-none" type="button" step_number="2">Siguiente</button>
            </div>
        </section>

        <section id="step-2" class="form-step d-none">
          <h2 class="font-normal "></h2>
          <!-- Step 2 input fields -->

          <div class="form-row">

              <div class="form-group col-lg-12" style="font-size: 18px">

                <div class="btn-group " role="group" aria-label="Basic example">
                  @foreach($bancos as $banco)
                    <button class="button mx-2 p-3 btn-navigate-banco"  type="button" step_number="3" banco="{{$banco}}">{{$banco}}</button>
                  @endforeach
                  
                </div>

              </div>

          </div>

          <div class="mt-3">
              <button class="button btn-navigate-form-step d-none" type="button" step_number="1">Atras</button>
              <button class="button btn-navigate-form-step btn-navigate-banco d-none" type="button" step_number="3">Siguiente</button>
          </div>
        </section>

        <section id="step-3" class="form-step d-none">
          
          <!-- Step 3 input fields -->
          <div class="mt-3">

            <div class="form-row">
              <div class="form-group col-lg-6" style="font-size: 18px">
                {!! Form::label('banco', 'Banco') !!}
                {!! Form::select('banco', $bancos , '0', ['readonly' => 'readonly','class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
              </div>
              <div class="form-group col-lg-6" style="font-size: 18px">
                {!! Form::label('titulares', 'Titulares') !!}
                {!! Form::select('titulares', $titulares , '0', ['readonly' => 'readonly','class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
              </div> 
              <div class="form-group col-lg-12" style="font-size: 18px">
                {!! Form::label('tipotransferencia', 'Tipo Movimiento') !!}              
                {!! Form::select('tipotransferencia', $tipotransferencia, '', ['class' => 'form-control selectpicker border border-secondary', 'id'=>'tipotransferencia','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}  
              </div>
              <div class="form-group col lg-12 descrip_otros" style="font-size: 18px">
                {!! Form::label('descrip_otros', 'Ingrese la descripcion para Movimiento Otros (Max. 70 caracteres)') !!}
                {!! Form::textarea('descrip_otros', '', ['class' => 'form-control', 'rows' => '1', 'placeholder' => 'Descripcion Otros']) !!} {{--, 'required' => 'required'--}}
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
              </div>
            </div>

            
              

          </div>
          <div class="mt-3">
              <button class="button btn-navigate-form-step" type="button" step_number="2">Atras</button>
              <button id="registrar_movimientos" type="button" class="btn btn-success button  submit-btn"><i class="fas fa-save"></i> Guardar</button>
          </div>
        </section>


        </div>

      </div>


      <div class="modal-footer d-none">

      </div>
      {!! Form::close() !!}

    </div>
  </div>
</div>
