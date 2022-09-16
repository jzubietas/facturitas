@extends('adminlte::page')

@section('title', 'Reporte - Ventas')

@section('content_header')
  <h1>Reportes Ventas</h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <div class="tab-content" id="nav-tabContent">
              {!! Form::open(['route' => ['VentaPorFechas'], 'target' => '_blank', 'class' => 'form-porFechas']) !!}
              <div class="row">
                <div class="col-lg-5">
                  {!! Form::label('desde', 'Desde') !!}
                  {!! Form::date('desde', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-lg-5">
                  {!! Form::label('hasta', 'Hasta') !!}
                  {!! Form::date('hasta', null, ['class' => 'form-control']) !!}
                </div>
                <div class="mt-2 col-lg-4">
                  {!! Form::button('<i class="fas fa-search"></i> Consultar', ['class' => 'btn btn-info', 'type' => 'submit']) !!}
                </div>
              </div>
              {!! Form::close() !!}
            {{-- <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div> --}}
          </div>
        </div>
      </div>
    </div>
  </div>

@stop

