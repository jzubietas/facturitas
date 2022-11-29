<div class="modal fade" id="modal-update" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Agregar movimientos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

       {!! Form::open(['route' => 'movimientos.actualiza','enctype'=>'multipart/form-data', 'id'=>'formularioedit','files'=>true]) !!} 
      <!--<form id="formularioedit" name="formularioedit" enctype="multipart/form-data">*-->

      <div class="modal-body">

        
        <div class="row">
          <div class="col">

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
        </div>
          
      </div>
      <div class="modal-footer">

        <button type="submit" id="actualizar_movimientos" class="btn btn-info btn-sm" >Actualizar</button>
        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
      {!! Form::close() !!}

    </div>
  </div>
</div>
