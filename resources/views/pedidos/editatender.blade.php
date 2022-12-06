@extends('adminlte::page')

@section('title', 'Detalle de pedido')

@section('content_header')
  @foreach ($pedidos as $pedido)
    @if ($pedido->id < 10)
      <h1>Pedido: PED000{{ $pedido->id }}</h1>
    @elseif($pedido->id < 100)
      <h1>Pedido: PED00{{ $pedido->id }}</h1>
    @elseif($pedido->id < 1000)
      <h1>>Pedido: PED0{{ $pedido->id }}</h1>
    @else
      <h1>Pedido: PED{{ $pedido->id }}</h1>
    @endif
  @endforeach
@stop

@section('content')
  @foreach ($pedidos as $pedido)
    <div class="card">
      <div class="card-body">
        <div class="border rounded card-body border-secondary" style="text-align: center; padding:0rem">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-lg-6">
                <label for="id_ingresomaterial">Asesor</label>
                <p>{{ $pedido->users }}</p>
              </div>
              <div class="form-group col-lg-6">
                <label for="id_ingresomaterial">Estado</label>
                <p>{{ $pedido->condiciones }}</p>
              </div>
            </div>  
          </div>
        </div>

        <br>

        <div class="border rounded card-body border-secondary">
          <div class="table-responsive">
            <table id="tabla" class="table table-striped"  style="text-align: center">{{-- tablaPrincipal --}}
              <thead><h4 style="text-align: center"><strong>Detalle de pedido</strong></h4>
                <tr>
                  <th scope="col">Código</th>
                  <th scope="col">Empresa</th>
                  <th scope="col">Mes</th>
                  <th scope="col">Año</th>
                  <th scope="col">RUC</th>
                  <th scope="col">Tipo de comprobante<br>y banca</th>
                  <th scope="col">Descripción</th>
                  <th scope="col">Nota</th>
                  <th scope="col">Adjunto</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>{{ $pedido->codigos }}</td>
                    <td>{{ $pedido->empresas }}</td>
                    <td>{{ $pedido->mes }}</td>
                    <td>{{ $pedido->anio }}</td>
                    <td>{{ $pedido->ruc }}</td>
                    <td>{{ $pedido->tipo_banca }}</td>
                    <td>{{ $pedido->descripcion }}</td>
                    <td>{{ $pedido->nota }}</td>
                    <td>
                      @foreach ($imagenespedido as $img)
                        @if($img->adjunto <> "logo_facturas.png")
                          <p>
                            <a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>
                          </p>
                        @endif
                      @endforeach
                    </td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>      
        <br>

        <div class="border rounded card-body border-secondary">                  
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">           
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
                {!! Form::label('envio_doc', 'Documento adjuntado') !!}              
                @foreach($imagenes as $img)
                  @if ($img->pedido_id == $pedido->id)
                    <p>
                      <a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>
                      <a href="" data-target="#modal-delete-adjunto-{{ $img->id }}" data-toggle="modal">  <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></a>
                    </p>
                  @endif
                  @include('pedidos.modal.DeleteAdjunto')
                @endforeach
              </div>              
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                {{ Form::Open(['route' => ['operaciones.updateatender', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) }}
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('envio_doc', 'Documento enviado') !!}
                    @csrf
                    {!! Form::file('adjunto[]', ['class' => 'form-control-file', 'id'=>'adjunto', 'multiple']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('fecha_envio_doc', 'Fecha de envío') !!}
                    {!! Form::text('fecha_envio_doc', $pedido->fecha_envio_doc, ['class' => 'form-control', 'id' => 'fecha_envio_doc', 'disabled']) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('cant_compro', 'Cantidad de comprobantes enviados') !!}
                    {!! Form::number('cant_compro', $pedido->cant_compro, ['class' => 'form-control', 'id' => 'cant_compro', 'step'=>'1', 'min' => '0']) !!}
                </div>
              </div>
            </div>
          </div>                    
        </div>
        <div class="col-2 text-left" class="modal-footer">
          <button type="submit" class="btn btn-success" id="atender">Confirmar</button>
          <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fas fa-arrow-left"></i>ATRAS</a>
        </div>        
      </div>
    </div>
  @endforeach
@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop