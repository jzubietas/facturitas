@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
  <h1>Analisis<i><b>Ojo Celeste</b></i></h1>
@stop

@section('content')

  <div class="card">
    <div class="card-header bg-primary">PEDIDOS {{ $mes_month }}  {{ $mes_anio }}   {{ $mes_mes }}</div>
    <div class="form-group">

      <div class="row">
        <div class="form-group col-lg-1"></div>
        <div class="form-group col-lg-12"><br>
          <div class="card">
            
            <div class="card-body">
              <?php //echo "<pre>";print_r($_pedidos_mes_pasado);echo "</pre>"; ?>
             
              <div class="row ">
                @foreach ($_pedidos_mes_pasado as $pedido)
                <div class="col-2 ">
                  <div class="card card-warning">
                    <div class="card-header">
                        <h5>LLAMADA {{ $pedido->name }}</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="text-center">
                          <b>RECUPERADO.REC : {{ $pedido->recuperado_reciente }} </b>
                        </h4>
                        <h4 class="text-center">
                          <b>RECUPERADO.REC : {{ $pedido->recuperado_abandono }} </b>
                        </h4>
                        <h4 class="text-center">
                          <b>NUEVO : {{ $pedido->nuevo }} </b>
                        </h4>
                    </div>
                  </div>
                  
                </div>
                @endforeach
              </div>



              <div class="form-row d-none">
                <div class="form-group col-lg-12" style="text-align: center">
                  {!! Form::label('servicio_id', 'Complssete sus parámetros') !!} <br><br>
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
            <div class="card-footer d-none">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Consultar</button>
            </div>
            
          </div>
        </div>
        <div class="form-group col-lg-1"></div>
      </div>
    </div>
  </div>

@stop
