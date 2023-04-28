<div class="row">
    @if(count($rucs)>0)
        @foreach($rucs as $ruc)
            <div class="col-md-4">
                <div class="card mt-4" style=" background: rgba(25,255,247,0.06); ">
                    <div class="card-body">
                        <div>
                            <ul class="list-group">
                                <li class="list-group-item  bg-dark">Empresa: <b>{{$ruc->empresa}}</b></li>
                                <li class="list-group-item  bg-dark">RUC: <b>{{$ruc->num_ruc}}</b></li>
                                <li class="list-group-item  bg-dark">Deuda Ruc: <b
                                        class="{{$ruc->cliente->deuda_total_ruc>3?'bg-danger p-2 text-white':''}}">{{money_f($ruc->cliente->deuda_total_ruc)}}</b>
                                </li>

                                <li class="list-group-item  bg-success">Cliente: <b>{{$ruc->cliente->nombre}}</b></li>
                                <li class="list-group-item bg-success">DNI: <b>{{$ruc->cliente->dni}}</b></li>
                                <li class="list-group-item bg-success">Celular: <b>{{$ruc->cliente->celular}}
                                        -{{$ruc->cliente->icelular}}</b></li>
                                <li class="list-group-item bg-success">Situacion: <b>{{$ruc->cliente->situacion}}</b>
                                </li>
                                <li class="list-group-item bg-success">Tiene Deuda: <b
                                        class="{{$ruc->cliente->deuda_total>3?'bg-danger p-2 text-white':''}}"> {{$ruc->cliente->deuda_total>3?'SI':'NO'}}</b>
                                </li>
                                <li class="list-group-item bg-success">Deuda Cliente: <b
                                        class="{{$ruc->cliente->deuda_total>3?'bg-danger p-2 text-white':''}}">{{money_f($ruc->cliente->deuda_total)}}</b>
                                </li>
                                @if($ruc->user!=null)
                                    <li class="list-group-item bg-info">Asesor:
                                        <b>{{$ruc->user->clave_pedidos}}</b>
                                    </li>
                                @else
                                    <li class="list-group-item bg-info">
                                        <div class="alert alert-warning " role="alert">
                                            No hay asesor asignado
                                        </div>
                                    </li>
                                @endif
                                @if(count($ruc->cliente->porcentajes)>0)
                                    <li class="list-group-item bg-warning">
                                        <b>Porcentajes</b>
                                    </li>
                                @endif
                                @foreach($ruc->cliente->porcentajes as $porcentaje)
                                    <li class="list-group-item bg-warning">
                                        {{$porcentaje->nombre}}:<b>{{$porcentaje->porcentaje}}</b>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12">
            <h1 class="alert alert-warning">No se encontraron rucs </h1>
        </div>
    @endif
</div>
