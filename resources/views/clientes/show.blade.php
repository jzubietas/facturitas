@extends('adminlte::page')

@section('title', 'Ver Cliente')

@section('content_header')
  <h1>Ver cliente</h1>
@stop

@section('content')

  <div class="card">
    

    <div class="border rounded card-body border-secondary">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('tipo', 'Tipo de cliente') !!}
              <input type="hidden" name="tipo" requerid value="1" class="form-control">
              <input type="text" name="cliente" value="Cliente" class="form-control" disabled>
            @error('tipo')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor*') !!}
            
            {!! Form::select('user_id', $users, $cliente->user_id, ['class' => 'form-control  border border-secondary', 'id' => 'user_id','data-live-search' => 'false', 'placeholder' => '---- SELECCIONE USUARIO ----','disabled']) !!}
            @error('user_id')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('nombre', 'Nombre*') !!}
            {!! Form::text('nombre', $cliente->nombre, ['class' => 'form-control', 'id' => 'nombre','readonly']) !!}
            @error('nombre')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-3">        
            {!! Form::label('dni', 'DNI') !!}
            {!! Form::number('dni', $cliente->dni, ['class' => 'form-control', 'id' => 'dni', 'min' =>'0', 'max' => '99999999', 'maxlength' => '8', 'oninput' => 'maxLengthCheck(this)','readonly']) !!}
            @error('dni')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-3">
            {!! Form::label('celular', 'Celular*') !!}
            {!! Form::number('celular', $cliente->celular, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','readonly']) !!}
            @error('celular')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('provincia', 'Provincia*') !!}
            {!! Form::text('provincia', $cliente->provincia, ['class' => 'form-control', 'id' => 'provincia','readonly']) !!}
            @error('provincia')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('distrito', 'Distrito*') !!}
            {!! Form::text('distrito', $cliente->distrito, ['class' => 'form-control', 'id' => 'distrito','readonly']) !!}
            @error('distrito')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('direccion', 'Dirección*') !!}
            {!! Form::text('direccion', $cliente->direccion, ['class' => 'form-control', 'id' => 'direccion','readonly']) !!}
            @error('direccion')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('referencia', 'Referencia*') !!}
            {!! Form::text('referencia', $cliente->referencia, ['class' => 'form-control', 'id' => 'referencia','readonly']) !!}
            @error('referencia')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>  
      </div>
    </div>
    <br>
    <div class="card">
      <div class="border rounded card-body border-secondary">
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-lg-12">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <h5 style="text-align: center"><b>Porcentajes</b></h5>
                </div>
                @foreach ($porcentajes as $porcentaje)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <label>{{ $porcentaje->nombre }}</label>
                        <input type="hidden" name="idporcentaje[]" value={{ $porcentaje->id }}>
                        <input type="number" step="0.1" name="porcentaje[]" id="porcentaje1" min="0" class="form-control" value={{ $porcentaje->porcentaje}} readonly>
                      </div>
                    </div>
                @endforeach              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer">
      {{--<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>--}}
      <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
    </div>
    
  </div>

@stop
