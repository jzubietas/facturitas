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
        <div class="border rounded card-body border-secondary" style="text-align: center">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-lg-4">
                <label for="id_ingresomaterial">Cliente</label>
                <p>{{ $pedido->nombres }} - {{ $pedido->celulares }}</p>
              </div>
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
                  <th scope="col">Código</th>
                  <th scope="col">Empresa</th>
                  <th scope="col">Mes</th>
                  <th scope="col">Año</th>
                  <th scope="col">RUC</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Tipo de comprobante<br>y banca</th>
                  <th scope="col">Porcentaje</th>
                  <th scope="col">Courier</th>
                  <th scope="col">Descripción</th>
                  <th scope="col">Nota</th>
                  <th scope="col">Adjunto</th>
                  <th scope="col">FT</th>
                  <th scope="col">Opciones</th>
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
                    <td>{{ $pedido->porcentaje }}</td>
                    <td>{{ $pedido->courier }}</td>
                    <td>{{ $pedido->descripcion }}</td>
                    <td>{{ $pedido->nota }}</td>
                    <td>
                      @foreach ($imagenes as $img)
                        <p><a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a></p>
                      @endforeach
                    </td>
                    {{-- <td><a href="{{ route('pedidos.descargaradjunto', $pedido->adjunto) }}">{{ $pedido->adjunto }}</a></td> --}}
                    <td>@php echo number_format($pedido->ft,2) @endphp</td>
                    <td>
                      {{--@can('pedidos.edit')
                        <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-warning btn-sm">Editar</a>
                      @endcan--}}
                    </td>
                  </tr>
              </tbody>
              <tfoot>
                <tr>
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
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <br>

        @if ($pedido->condiciones == "3")
          <div class="border rounded card-body border-secondary">
            <table id="tabla" class="table table-striped" style="text-align: center">
              <thead><h4 style="text-align: center"><strong>Detalle de atención</strong></h4>
                <tr>
                  <th scope="col">Documentos</th>
                  <th scope="col">Fecha de registro</th>
                  <th scope="col">Cantidad de comprobantes</th>
                  <th scope="col">Fecha de envío</th>
                  <th scope="col">Fecha de recepción</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>
                      @foreach($imagenesatencion as $img)
                          <p>
                            <a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>
                          </p>
                      @endforeach
                    </td>
                    <td>{{ $pedido->fecha_envio_doc }}</td>
                    <td>{{ $pedido->cant_compro }}</td>
                    <td>{{ $pedido->fecha_envio_doc_fis }}</td>
                    <td>{{ $pedido->fecha_recepcion }}</td>
                  </tr>
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
        @endif

          <div class="border rounded card-body border-secondary">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                          {!! Form::label('envio_doc', 'Documento adjuntado') !!}
                          @foreach($imagenes as $img)
                              @if ($img->pedido_id == $pedido->id)
                                  <p>
                                      <a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>
                                      <a href="#" data-target="#modal-delete-adjunto" data-toggle="modal" data-imgid="{{ $img->pedido_id }}" data-imgadjunto="{{ $img->adjunto }}">
                                          <button class="btn btn-danger btn-sm" data-imgid="{{ $img->pedido_id }}" data-imgadjunto="{{ $img->adjunto }}">
                                              <i class="fas fa-trash-alt"></i>
                                          </button>
                                      </a>
                                      <!--<a href="" data-target="#modal-delete-adjunto-{{ $img->id }}" data-toggle="modal">
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></a>-->
                                  </p>
                              @endif
                              @include('pedidos.modal.DeleteAdjuntoid')
                          @endforeach
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      </div>
                  </div>
              </div>
          </div>
<br>

        <textarea class="form-control" rows="6" placeholder="Cotizacion" name="copiar_cotizacion" cols="50" id="copiar_cotizacion">

        </textarea>


        <br>


        <!--<a href="{{ route('pedidos.index', $pedido) }}" class="btn btn-danger btn-sm">Cancelar</a>-->
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
  <script>

    console.log(localStorage.getItem("search_tabla"));

  let copydata="*S/."+"{{$cotizacion->cantidad}}"+" * "+"{{$cotizacion->porcentaje}}" +"% = S/."+"{{$cotizacion->ft}}"+"*\n"+
              "*ENVIO = S/."+"{{$cotizacion->courier}}"+"*\n"+
              "*TOTAL = S/."+"{{$cotizacion->total}}"+"*\n\n"+
              "*ES IMPORTANTE PAGAR EL ENVIO* \n";

              $("#copiar_cotizacion").val(copydata);

  </script>


  <script>




  </script>

@stop
