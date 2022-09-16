<div class="border rounded card-body border-secondary">
  <div class="card-body">
    <div class="form-row">
      <div class="form-group col-lg-6">
          {!! Form::label('razon_social', 'Razón Social*') !!}
          {!! Form::text('razon_social', null, ['class' => 'form-control']) !!}
          @error('razon_social')
            <small class="text-danger">{{$message}}</small>
          @enderror
      </div>
      <div class="form-group col-lg-6">
          {!! Form::label('ruc', 'Número de RUC*') !!}
          {!! Form::number('ruc', null, ['class' => 'form-control']) !!}
          @error('ruc')
            <small class="text-danger">{{$message}}</small>
          @enderror
      </div>
      <div class="form-group col-lg-6">
          {!! Form::label('direccion', 'Dirección*') !!}
          {!! Form::text('direccion', null, ['class' => 'form-control']) !!}
          @error('direccion')
            <small class="text-danger">{{$message}}</small>
          @enderror
      </div>
      <div class="form-group col-lg-6">
        {!! Form::label('contacto', 'Persona de contacto*') !!}
        {!! Form::text('contacto', null, ['class' => 'form-control']) !!}
        @error('contacto')
          <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
      <div class="form-group col-lg-6">
          {!! Form::label('telefono', 'Teléfono*') !!}
          {!! Form::number('telefono', null, ['class' => 'form-control']) !!}
          @error('telefono')
            <small class="text-danger">{{$message}}</small>
          @enderror
      </div>
      <div class="form-group col-lg-6">
          {!! Form::label('email', 'E-mail*') !!}
          {!! Form::email('email', null, ['class' => 'form-control']) !!}
          @error('email')
            <small class="text-danger">{{$message}}</small>
          @enderror
      </div>
    </div>  
  </div>
</div>
