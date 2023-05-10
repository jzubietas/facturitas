<div class="row">
    @if(count($clientes)>0)
        @foreach($clientes as $cliente)
            <div class="col-lg-12 col-md-12">
                <div class="card m-0" style=" background: rgba(25,255,247,0.06); ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item bg-success">Cliente: <b>{{$cliente->nombre}}</b></li>
                                    <li class="list-group-item bg-success">DNI: <b>{{$cliente->dni}}</b></li>
                                    <li class="list-group-item bg-success">Celular: <b>{{$cliente->celular}}
                                            -{{$cliente->icelular}}</b></li>
                                    <li class="list-group-item bg-success">Situacion: <b>{{$cliente->situacion}}</b>
                                    </li>
                                    <li class="list-group-item bg-success">Tiene Deuda: <b
                                            class="{{$cliente->deuda_total>3?'bg-danger p-2 text-white':''}}"> {{$cliente->deuda_total>3?'SI':'NO'}}</b>
                                    </li>
                                    <li class="list-group-item bg-success">Deuda Total: <b
                                            class="{{$cliente->deuda_total>3?'bg-danger p-2 text-white':''}}">{{money_f($cliente->deuda_total)}}</b>
                                    </li>
                                    @if($cliente->user!=null)
                                        <li class="list-group-item bg-info">Asesor:
                                            <b>{{$cliente->user_clavepedido}}</b></li>
                                    @else
                                        <li class="list-group-item bg-info">
                                            <div class="alert alert-warning " role="alert">
                                                No hay asesor asignado
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    @if(count($cliente->porcentajes)>0)
                                        <li class="list-group-item bg-warning">
                                            <b>Porcentajes</b>
                                        </li>
                                    @endif
                                    @foreach($cliente->porcentajes as $porcentaje)
                                        <li class="list-group-item bg-warning">
                                            {{$porcentaje->nombre}}:<b>{{$porcentaje->porcentaje}}</b>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-12">
                                @if(count($cliente->rucs)>0)
                                    <div class=" px-4 py-2 mt-2 bg-dark rounded text-uppercase"><b>Empresas</b></div>
                                    <div class="col-lg-12 pl-0 pr-0">
                                        @foreach(collect($cliente->rucs)->chunk(2) as $rucs)
                                            <ul class="list-group mt-2 pr-0 d-flex flex-wrap" style="grid-gap: 10px !important">
                                                @foreach($rucs as $ruc)
                                                    <li class="list-group-item bg-dark border-bottom rounded">
                                                        RUC: <b> {{$ruc->num_ruc}}</b><br> Razon
                                                        social: <b> {{$ruc->empresa??"--"}}</b>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    </div>
                                @else
                                    <ul class="list-group mt-2">
                                        <li class="list-group-item bg-dark"><b>Empresas</b></li>
                                        <li class="list-group-item bg-dark border-bottom">
                                            -- NO HAY EMPRESAS ASIGNADAS --
                                        </li>
                                    </ul>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12">
            <h3 class="alert alert-warning m-0">No se encontraron clientes </h3>
        </div>
    @endif
</div>
