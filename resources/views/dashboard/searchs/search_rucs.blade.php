<div class="row">
    @foreach($rucs as $ruc)
        <div class="col-md-6">
            <div class="card mt-4" style=" background: rgba(25,255,247,0.06); ">
                <div class="card-body">
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item  bg-dark">Empresa: <b>{{$ruc->empresa}}</b></li>
                            <li class="list-group-item  bg-dark">RUC: <b>{{$ruc->num_ruc}}</b></li>

                            <li class="list-group-item  bg-success">Cliente: <b>{{$ruc->cliente->nombre}}</b></li>
                            <li class="list-group-item bg-success">DNI: <b>{{$ruc->cliente->dni}}</b></li>
                            <li class="list-group-item bg-success">Celular: <b>{{$ruc->cliente->celular}}-{{$ruc->cliente->icelular}}</b></li>
                            <li class="list-group-item bg-success">Situacion: <b>{{$ruc->cliente->situacion}}</b></li>
                            <li class="list-group-item bg-success">Tiene Deuda: <b> No</b></li>
                            <li class="list-group-item bg-success">Deuda Total: <b>S/0.00</b></li>
                            @if($ruc->user!=null)
                                <li class="list-group-item bg-info">Asesor:
                                    <b>{{$ruc->user->identificador}}</b>
                                </li>
                                <li class="list-group-item bg-info">Asesor Email: <b>{{$ruc->user->email}}</b></li>
                            @else
                                <li class="list-group-item bg-info">
                                    <div class="alert alert-warning " role="alert">
                                        No hay asesor asignado
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
