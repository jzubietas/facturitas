@extends('adminlte::page')

@section('title', 'Detalle de pedido')

@section('content_header')
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                @if ($pedido->id < 10)
                    <h1 class="text-left">Pedido: PED000{{ $pedido->id }}</h1>
                @elseif($pedido->id < 100)
                    <h1 class="text-left">Pedido: PED00{{ $pedido->id }}</h1>
                @elseif($pedido->id < 1000)
                    <h1 class="text-left">>Pedido: PED0{{ $pedido->id }}</h1>
                @else
                    <h1 class="text-left">Pedido: PED{{ $pedido->id }}</h1>
                @endif
            </div>
            <div class="col-sm-6">
                @if(count($pedido->notasCreditoFiles())>0)
                    <button class="btn btn-danger" onclick="window.scrollTo(0, document.body.scrollHeight);"><i class="fas fa-angle-double-down"></i> Ver Nota de Crédito <i class="fas fa-angle-double-down"></i></button>
                @endif
            </div>
        </div>
    </div>



@stop

@section('content')

    <div class="card">
        <div class="border rounded card-body border-secondary">
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

    <div class="card">
        <div class="border rounded card-body border-secondary">
            <div class="table-responsive">
                <table id="tabla" class="table table-striped"
                       style="text-align: center">{{-- tablaPrincipal --}}
                    <thead><h4 style="text-align: center"><strong>Detalle de pedido</strong></h4>
                    <tr>
                        <th scope="col" class="align-middle">Código</th>
                        <th scope="col" class="align-middle">Empresa</th>
                        <th scope="col" class="align-middle">Mes</th>
                        <th scope="col" class="align-middle">Año</th>
                        <th scope="col" class="align-middle">RUC</th>
                        <th scope="col" class="align-middle">Cantidad</th>
                        <th scope="col" class="align-middle">Tipo de comprobante<br>y banca</th>
                        <th scope="col" class="align-middle">Porcentaje</th>
                        <th scope="col" class="align-middle">Courier</th>
                        <th scope="col" class="align-middle">Descripción</th>
                        <th scope="col" class="align-middle">Nota</th>
                        <th scope="col" class="align-middle">Adjunto</th>
                        <th scope="col" class="align-middle">FT</th>
                        <th scope="col" class="align-middle">Opciones</th>
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
                                    {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}
                                    <a target="_blank" download
                                       href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
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
    </div>

    @if ($pedido->condiciones == "3")
    <div class="card">
        <div class="border rounded card-body  border-secondary">
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
                                {{--<a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a>--}}

                                <a target="_blank" download
                                   href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">{{ $img->adjunto }}</a>
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
    </div>
    @endif

    <div class="row">
        <div class="card col-md-6">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <h4 style="font-weight:bold;">Detalle de atención</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <p><b>Cantidad de comprobantes enviados:</b></p>
                                <b class="h4">{{ $pedido->cant_compro }}</b>
                            </div>
                            <div class="col-lg-6">
                                <p><b>Archivos Adjuntados:</b></p>
                                @foreach($imagenesatencion as $img_at)
                                    <p>
                                        <a class="{{$img_at->estado==0?'text-danger':''}}" target="_blank"
                                           download
                                           href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img_at->adjunto) }}">
                                            {{ $img_at->adjunto }}
                                        </a>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class=""></div>
                </div>
            </div>
        </div>

        @if($pedido->condicion==\App\Models\Pedido::ANULADO_PARCIAL)
            <div class="card col-md-6">
                <div class="card-header bg-danger text-white">
                    Motivos Anulado Parcial
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($motivo_anulado_parcial as $motivo_anulado_p)
                            <li class="list-group-item">
                                {{$motivo_anulado_p["usu_motivo"]}}: {{$motivo_anulado_p["motivo"]}}
                            </li>
                        @endforeach

                    </ul>


                </div>
            </div>
        @endif

    </div>




    @if($pedido->condicion_code==\App\Models\Pedido::ANULADO_INT)
        <div class="card">
            <div class="card-header">
                <h4 class="text-bold">Detalle de anulación</h4>
            </div>
            <div class="border rounded card-body border-secondary">
                <ul class="list-group">
                    <li class="list-group-item">
                        Responsable <B>{{$pedido->responsable}}</B>
                    </li>
                    <li class="list-group-item">
                        Fecha de anulacion
                        <b>{{optional($pedido->fecha_anulacion)->format('d-m-Y h:i')}}</b>
                    </li>
                    <li class="list-group-item">
                        Fecha de anulacion
                        confirmada
                        <b>{{optional($pedido->fecha_anulacion_confirm)->format('d-m-Y h:i')}}</b>
                    </li>
                    <li class="list-group-item">
                        Motivo de anulacion:
                        <b>{{$pedido->motivo}}</b>
                    </li>
                    @if(count($pedido->adjuntosFiles())>0)
                        <li class="list-group-item bg-success">
                            Adjuntos
                        </li>
                    @endif
                    <li class="list-group-item">
                        <div class="row">
                            @foreach($pedido->adjuntosFiles() as $file)
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <a target="_blank"
                                               href="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                @if(!Str::contains(Str::lower($file),'.pdf'))
                                                    <img class="w-100"
                                                         src="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                @endif
                                                {{basename($file)}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </li>

                    @if(count($pedido->notasCreditoFiles())>0)
                        <li class="list-group-item bg-danger">
                            <h1 class="font-weight-bolder font-" style="letter-spacing: 5px;"> Notas de Credito</h1>
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
                                                @if(!Str::contains(Str::lower($file),'.pdf'))
                                                    <img class="w-100"
                                                         src="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                @endif
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

    @if($pedido->condicion==\App\Models\Pedido::ANULADO_PARCIAL)
        <div class="card">
            <div class="card-header">
                <h4 class="text-bold">Detalle de Anulación Parcial</h4>
            </div>
            <div class="border rounded card-body border-secondary">
                <ul class="list-group">
                    <li class="list-group-item">
                        Responsable <B>{{$pedido->usersanulpar}}</B>
                    </li>
                    <li class="list-group-item">
                        Fecha de anulacion
                        <b>{{$pedido->fecsolicitudaaaa}}</b>
                    </li>
                    <li class="list-group-item">
                        Fecha de anulacion
                        confirmada
                        <b>{{($pedido->fecconfirmaaaaa)}}</b>
                    </li>
                    <li class="list-group-item">
                        Motivo de anulacion:
                        <b>{{$pedido->motivo_sol_admin}}</b>
                    </li>
                    @if(count($pedido->adjuntosFiles())>0)
                        <li class="list-group-item bg-danger">
                            Adjuntos
                        </li>
                    @endif
                    <li class="list-group-item">
                        <div class="row">
                            @foreach($pedido->adjuntosFiles() as $file)
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <a target="_blank"
                                               href="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                @if(!Str::contains(Str::lower($file),'.pdf'))
                                                    <img class="w-100"
                                                         src="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                @endif
                                                {{basename($file)}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </li>

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
                                                @if(!Str::contains(Str::lower($file),'.pdf'))
                                                    <img class="w-100"
                                                         src="{{Storage::disk($pedido->path_adjunto_anular_disk)->url($file)}}">
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                @endif
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

        <div class="card">
            <div class="card-body">
                <textarea class="form-control" rows="6" placeholder="Cotizacion" name="copiar_cotizacion" cols="50"
                          id="copiar_cotizacion"></textarea>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
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

@stop

@push('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        #tabla{
            width: 100% !important;
        }

        #tabla td{
            text-align: start !important;
            vertical-align: middle !important;
        }
    </style>

@endpush

@section('js')

    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>

        console.log(localStorage.getItem("search_tabla"));

        let copydata = "{{$pedido->empresas}}" + "\n\n" +
            "*S/." + "{{$pedido->cantidad}}" + " * " + "{{$pedido->porcentaje}}" + "% = S/." + "{{$pedido->ft}}" + "*\n" +
            "*ENVIO = S/." + "{{$pedido->courier}}" + "*\n" +
            @if($adelanto>0)
                "*ADELANTO = S/." + "{{$adelanto}}" + "*\n" +
            "*TOTAL = S/." + "{{$pedido->total-$adelanto}}" + "*\n\n" +
            @else
                "*TOTAL = S/." + "{{$pedido->total}}" + "*\n\n" +
            @endif
                "*ES IMPORTANTE PAGAR EL ENVIO* \n";

        $("#copiar_cotizacion").val(copydata);

    </script>


    <script>


    </script>

@stop
