@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
  <h1>REPORTES DE MIS ASESORES <i><b>Ojo Celeste</b></i></h1>
@stop

@section('content')

  <div class="card">
    <div class="card-header bg-primary">PEDIDOS</div>
    <div class="form-group">
      <div class="row">
        <div class="form-group col-lg-1"></div>
        <div class="form-group col-lg-10"><br>
          <div class="card">
            {!! Form::open(['route' => ['pedidosporasesorexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center">
                  {!! Form::label('servicio_id', 'Complete sus parámetros') !!} <br><br>
                  <div class="form-row">
                    <div class="col-lg-4">
                      <label>Fecha inicial&nbsp;</label>
                      {!! Form::date('desde', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                    </div>
                    <div class="col-lg-4">
                      <label>Fecha final&nbsp;</label>
                      {!! Form::date('hasta', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                    </div>
                    <div class="col-lg-4">
                      <label>Asesor </label>
                      {!! Form::select('user_id', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----']) !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="form-group col-lg-1"></div>
      </div>
    </div>
  </div>

  {{-- <div class="card">
    <div class="card-header bg-primary">PAGOS</div>
    <div class="form-group">
      <div class="row">
        <div class="form-group col-lg-1"></div>
        <div class="form-group col-lg-5"><br>
          <div class="card">
            {!! Form::open(['route' => ['pagosporfechasexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center">
                  {!! Form::label('servicio_id', 'Rango de fechas') !!}
                  <div class="form-row">
                    <div class="col-lg-6">
                      <label>Fecha inicial&nbsp;</label>
                      {!! Form::date('desde', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                    </div>
                    <div class="col-lg-6">
                      <label>Fecha final&nbsp;</label>
                      {!! Form::date('hasta', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="form-group col-lg-5"><br>
          <div class="card">
            {!! Form::open(['route' => ['pagosporasesorexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center">
                  {!! Form::label('servicio_id', 'Pagos por asesor') !!} <br><br>
                  <div class="form-row">
                    <div class="col-lg-3">
                      <label>Asesor </label>
                    </div>
                    <div class="col-lg-9">
                      {!! Form::select('user_id', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ----']) !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="form-group col-lg-1"></div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-primary">ASESORES</div>
    <div class="form-group">
      <div class="row">
        <div class="form-group col-lg-1"></div>
        <div class="form-group col-lg-10"><br>
          <div class="card">
            {!! Form::open(['route' => ['pedidosporasesoresexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center">
                  {!! Form::label('servicio_id', 'Pedidos por asesores') !!}
                  <div class="form-row">
                    <div class="col-lg-3">
                      <label>Asesor 1</label>
                      {!! Form::select('user_id1', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 1 ----']) !!}
                    </div>
                    <div class="col-lg-3">
                      <label>Asesor 2</label>
                      {!! Form::select('user_id2', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 2 ----']) !!}
                    </div>
                    <div class="col-lg-3">
                      <label>Asesor 3</label>
                      {!! Form::select('user_id3', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 3 ----']) !!}
                    </div>
                    <div class="col-lg-3">
                      <label>Asesor 4</label>
                      {!! Form::select('user_id4', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 4 ----']) !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="form-group col-lg-1"></div>
      </div>
      <div class="row">
        <div class="form-group col-lg-1"></div>
        <div class="form-group col-lg-10"><br>
          <div class="card">
            {!! Form::open(['route' => ['pagosporasesoresexcel'], 'method' => 'POST', 'target' => 'blanck_']) !!}
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-lg-12" style="text-align: center">
                  {!! Form::label('servicio_id', 'Pagos por asesores') !!}
                  <div class="form-row">
                    <div class="col-lg-3">
                      <label>Asesor 1</label>
                      {!! Form::select('user_id1', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 1 ----']) !!}
                    </div>
                    <div class="col-lg-3">
                      <label>Asesor 2</label>
                      {!! Form::select('user_id2', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 2 ----']) !!}
                    </div>
                    <div class="col-lg-3">
                      <label>Asesor 3</label>
                      {!! Form::select('user_id3', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 3 ----']) !!}
                    </div>
                    <div class="col-lg-3">
                      <label>Asesor 4</label>
                      {!! Form::select('user_id4', $users, null, ['class' => 'form-control', 'placeholder' => '---- SELECCIONE ASESOR 4 ----']) !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="form-group col-lg-1"></div>
      </div>
    </div>
  </div> --}}
@stop
