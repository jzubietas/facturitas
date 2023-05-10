@extends('adminlte::page')

@section('title', 'Ver  pedido')

@section('content_header')
    <h1>{{$pedido->correlativo}}</h1>

@stop

@section('content')
  @foreach ($pedidos as $pedido)
    <div class="card">
      <div class="card-body">
        <div class="border rounded card-body border-secondary" style="text-align: center">
          <div class="card-body">
            <div class="form-row">
              <!--<div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Cliente</label>
                <p>{{ $pedido->nombres }} - {{ $pedido->celulares }}</p>
              </div>-->
              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Asesor</label>
                <p>{{ $pedido->users }}</p>
              </div>
              <div class="form-group col-lg-4">
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
                  <th scope="col" style="vertical-align: middle">Código</th>
                  <th scope="col" style="vertical-align: middle">Empresa</th>
                  <th scope="col" style="vertical-align: middle">Mes</th>
                  <th scope="col" style="vertical-align: middle">Año</th>
                  <th scope="col" style="vertical-align: middle">RUC</th>
                  <th scope="col" style="vertical-align: middle">Cantidad</th>
                  <th scope="col" style="vertical-align: middle">Tipo de comprobante<br>y banca</th>
                  <th scope="col" style="vertical-align: middle">Descripción</th>
                  <th scope="col" style="vertical-align: middle">Nota</th>
                  <th scope="col" style="vertical-align: middle">Adjunto</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>{{ $pedido->codigos }}</td>
                    <td>{{ $pedido->empresas }}</td>
                    <td>{{ $pedido->mes }}</td>
                    <td>{{ $pedido->anio }}</td>
                    <td>{{ $pedido->ruc }}</td>
                    <td>@php echo number_format($pedido->cantidad,2) @endphp</td>
                    <td>{{ $pedido->tipo_banca }}</td>
                    {{-- <td>{{ $pedido->porcentaje }}</td>
                    <td>{{ $pedido->courier }}</td> --}}
                    <td>{{ $pedido->descripcion }}</td>
                    <td>{{ $pedido->nota }}</td>
                    <td>
                      @foreach ($imagenes as $img)
                        @if($img->adjunto <> "logo_facturas.png")
                          <p>
                            {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}

                              <a target="_blank" download href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
                          </p>
                        @endif
                      @endforeach
                    </td>
                    {{-- <td><a href="{{ route('pedidos.descargaradjunto', $pedido->adjunto) }}">{{ $pedido->adjunto }}</a></td> --}}
                    {{-- <td>@php echo number_format($pedido->ft,2) @endphp</td> --}}
                  </tr>
              </tbody>
              <tfoot>
               {{--  <tr>
                  <td><b><h3>TOTAL</h3></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><h3>{{ $pedido->total }}</h3></td>
                </tr> --}}
              </tfoot>
            </table>
          </div>
        </div>

        <br>

        @if ($pedido->condicion == "ATENDIDO")
          <div class="border rounded card-body border-secondary">
            <table id="tabla" class="table table-striped" style="text-align: center">
              <thead><h4 style="text-align: center"><strong>Detalle de atención</strong></h4>
                <tr>
                  <th scope="col">Documentos</th>
                  <th scope="col">Fecha de registro</th>
                  <th scope="col">Cantidad de comprobantes</th>
                  {{-- <th scope="col">Fecha de envío</th>
                  <th scope="col">Fecha de recepción</th> --}}
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>
                      @foreach($imagenesatencion as $img)
                          <p>
                            {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}
                              <a target="_blank" download href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
                          </p>
                      @endforeach
                    </td>
                    <td>{{ $pedido->fecha_envio_doc }}</td>
                    <td>{{ $pedido->cant_compro }}</td>
                    {{-- <td>{{ $pedido->fecha_envio_doc_fis }}</td>
                    <td>{{ $pedido->fecha_recepcion }}</td> --}}
                  </tr>
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
        @endif
        <br>
        <!--<a href="{{ route('operaciones.atendidos') }}" class="btn btn-danger btn-sm">Cancelar</a>-->
        <div class="card-footer">
        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
        </div>
      </div>
    </div>
  @endforeach
@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

  <script src="{{ asset('js/datatables.js') }}"></script>

@stop
