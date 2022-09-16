@extends('adminlte::page')

@section('title', 'Reporte de Contratos')

@section('content_header')
  <h1>Reportes de Contratos</h1>
@stop

@section('content')

  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-3">
          <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Por cliente</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Por Fechas</a>
            {{-- <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Por Condición</a> --}}
          </div>
        </div>
        <div class="col-9">
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
              {!! Form::open(['route' => ['ContratoPorCliente'], 'target' => '_blank', 'class' => 'form-porCliente']) !!}
              <div class="col-lg-8">
                {!! Form::label('cliente_id', 'Clientes') !!}
                <select name="cliente_id" id="cliente_id" class="form-control selectpicker border border-secondary" data-live-search="true">
                  <option value=" ">----SELECCIONE----</option>
                  @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->numero_documento }} - {{ $cliente->razon_social }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-4 mt-2">
                {!! Form::button('<i class="fas fa-search"></i> Consultar', ['class' => 'btn btn-info', 'type' => 'submit']) !!}
              </div>
              {!! Form::close() !!}
            </div>
            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
              {!! Form::open(['route' => ['ContratoPorFechas'], 'target' => '_blank', 'class' => 'form-porFechas']) !!}
              <div class="row">
                <div class="col-lg-5">
                  {!! Form::label('desde', 'Desde') !!}
                  {!! Form::date('desde', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-lg-5">
                  {!! Form::label('hasta', 'Hasta') !!}
                  {!! Form::date('hasta', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-lg-4 mt-2">
                  {!! Form::button('<i class="fas fa-search"></i> Consultar', ['class' => 'btn btn-info', 'type' => 'submit']) !!}
                </div>
              </div>
              {!! Form::close() !!}
            </div>
            {{-- <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div> --}}
          </div>
        </div>
      </div>
    </div>
  </div>

@stop

@section('js')

  <script>
    $('.form-porCliente').submit(function(e) {
      e.preventDefault();
      if ($('#cliente_id option:selected').val() === ' ') {
        Swal.fire(
          'Alerta!',
          'Por favor seleccione un cliente',
          'warning')
      } else {
        this.submit();
      }
    });

    $('.form-porFechas').submit(function(e) {
      e.preventDefault();
      if ($('#desde').val() === '') {
        Swal.fire(
          'Alerta!',
          'Por favor seleccione una fecha de inicio',
          'warning')
      } else if ($('#hasta').val() === '') {
        Swal.fire(
          'Alerta!',
          'Por favor seleccione una fecha final',
          'warning')
      } else {
        this.submit();
      }
    });
  </script>

@stop
