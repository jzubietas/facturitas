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
                        <table id="tabla" class="table table-striped"
                               style="text-align: center">{{-- tablaPrincipal --}}
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
                                        <p>
                                            <a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>
                                        </p>
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
                                <h4 style="font-weight:bold;">Detalle de atención</h4>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p><b>Cantidad de comprobantes enviados:</b></p>
                                        <p>{{ $pedido->cant_compro }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><b>Archivos Adjuntados:</b></p>
                                        @foreach($imagenesatencion as $img_at)
                                            @if ($img_at->pedido_id == $pedido->id)
                                                <p>
                                                    <a href="{{ route('pedidos.descargaradjunto', $img_at->adjunto) }}">{{ $img_at->adjunto }}</a>
                                                </p>
                                            @endif
                                            @include('pedidos.modal.DeleteAdjuntoid')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                        </div>
                    </div>
                </div>

                @if($pedido->condicion_code==\App\Models\Pedido::ANULADO_INT)
                <div class="card mt-4 border rounded card-body border-secondary">
                    <div class="card-header">
                        <h4 class="text-bold">Detalle de anulación</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                Responsable <B>{{$pedido->responsable}}</B>
                            </li>
                            <li class="list-group-item">
                                Fecha de anulacion <b>{{optional($pedido->fecha_anulacion)->format('d-m-Y h:i')}}</b>
                            </li>
                            <li class="list-group-item">
                                Fecha de anulacion
                                confirmada <b>{{optional($pedido->fecha_anulacion_confirm)->format('d-m-Y h:i')}}</b>
                            </li>
                            @if(count($pedido->adjuntosFiles())>0)
                                <li class="list-group-item bg-danger">
                                    Adjuntos
                                </li>
                            @endif
                            @foreach($pedido->adjuntosFiles() as $file)
                                <li class="list-group-item">
                                    <a target="_blank"
                                       href="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                        {{basename($file)}}
                                    </a>
                                </li>
                            @endforeach

                            @if(count($pedido->notasCreditoFiles())>0)
                                <li class="list-group-item bg-danger">
                                    Notas de Credito
                                </li>
                            @endif

                            <li class="list-group-item">
                                <div class="row">
                                    @foreach($pedido->notasCreditoFiles() as $file)
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <a target="_blank"
                                                       href="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                        <img class="w-100"
                                                             src="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                        {{basename($file)}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                <br>

                <textarea class="form-control" rows="6" placeholder="Cotizacion" name="copiar_cotizacion" cols="50"
                          id="copiar_cotizacion"></textarea>


                <br>


                <!--<a href="{{ route('pedidos.index', $pedido) }}" class="btn btn-danger btn-sm">Cancelar</a>-->
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" onClick="history.back()" class="btn btn-danger btn-lg"><i
                                class="fas fa-arrow-left"></i>ATRAS
                        </button>
                        <h3 class="text-danger">
                            *TOTAL DE DEUDAS {{money_f($deudaTotal)}}*
                        </h3>
                    </div>
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

        let copydata = "{{$cotizacion->nombre_empresa}}" + "\n\n" +
            "*S/." + "{{$cotizacion->cantidad}}" + " * " + "{{$cotizacion->porcentaje}}" + "% = S/." + "{{$cotizacion->ft}}" + "*\n" +
            "*ENVIO = S/." + "{{$cotizacion->courier}}" + "*\n" +
            @if($adelanto>0)
                "*ADELANTO = S/." + "{{$adelanto}}" + "*\n" +
            "*TOTAL = S/." + "{{$cotizacion->total-$adelanto}}" + "*\n\n" +
            @else
                "*TOTAL = S/." + "{{$cotizacion->total}}" + "*\n\n" +
            @endif
                "*ES IMPORTANTE PAGAR EL ENVIO* \n";

        $("#copiar_cotizacion").val(copydata);

    </script>


    <script>


    </script>

@stop
