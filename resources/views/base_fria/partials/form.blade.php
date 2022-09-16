<div class="border rounded card-body border-secondary">
  <div class="card-body">
    <div class="form-row">
      <div class="form-group col-lg-6">
          {!! Form::label('nombre', 'Nombre') !!}
          {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
          @error('nombre')
            <small class="text-danger">{{ $message }}</small>
          @enderror
      </div>
      <div class="form-group col-lg-6">
          {!! Form::label('celular', 'Celular*') !!}
          {!! Form::number('celular', null, ['class' => 'form-control']) !!}
          @error('celular')
            <small class="text-danger">{{ $message }}</small>
          @enderror
      </div>
      <div class="form-group col-lg-6">
        {!! Form::label('user_id', 'Recibido por*') !!}
        {!! Form::select('user_id', $users, null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE USUARIO ----']) !!}
        @error('user_id')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
      <div class="form-group col-lg-6">
          {!! Form::label('tipo', 'Tipo de cliente') !!}
          {!! Form::select('tipo', ['0' => 'Base fría', '1' => 'Cliente'] , null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
          @error('tipo')
            <small class="text-danger">{{ $message }}</small>
          @enderror
      </div>
    </div>  
  </div>
</div>
