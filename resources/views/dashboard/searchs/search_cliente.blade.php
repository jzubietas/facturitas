<div class="row">
    @foreach($clientes as $cliente)

        <div class="col-md-6">
            <div class="card mt-4" style=" background: rgba(25,255,247,0.06); ">
                <div class="card-body">
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item bg-success">Cliente: <b>{{$cliente->nombre}}</b></li>
                            <li class="list-group-item bg-success">DNI: <b>{{$cliente->dni}}</b></li>
                            <li class="list-group-item bg-success">Celular: <b>{{$cliente->celular}}-{{$cliente->icelular}}</b></li>
                            <li class="list-group-item bg-success">Situacion: <b>{{$cliente->situacion}}</b></li>
                            <li class="list-group-item bg-success">Tiene Deuda: <b> No</b></li>
                            <li class="list-group-item bg-success">Deuda Total: <b>S/0.00</b></li>
                            @if($cliente->user!=null)
                                <li class="list-group-item bg-info">Asesor:
                                    <b>{{$cliente->user->identificador}}</b></li>
                                <li class="list-group-item bg-info">Asesor Email: <b>{{$cliente->user->email}}</b></li>
                            @else
                                <li class="list-group-item bg-info">
                                    <div class="alert alert-warning " role="alert">
                                        No hay asesor asignado
                                    </div>
                                </li>
                            @endif
                            @foreach($cliente->rucs as $ruc)
                                <li class="list-group-item bg-dark">
                                    RUC:<b>{{$ruc->num_ruc}}</b>
                                </li>
                                <li class="list-group-item bg-dark border-bottom">Razon social: <b>{{$ruc->empresa??"--"}}</b></li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
