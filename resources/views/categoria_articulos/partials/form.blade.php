<div class="card-body">
  <div class="form-row">
    <div class="form-group col-lg-6">
      {!! Form::label('nombre', 'Nombre*') !!}
      {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
        @error('nombre')
          <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="form-group col-lg-6">
      {!! Form::label('descripcion', 'Descripcion') !!}
      {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
    </div>
  </div>
</div>
