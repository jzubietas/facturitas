<div class="row">
    @foreach($rucs as $ruc)
        <div class="col-md-12">
            <h4>
                Empresa: <b>{{$ruc->empresa}}</b>
                <br>
                RUC: <b>{{$ruc->num_ruc}}</b>
            </h4>
        </div>
        <div class="col-md-6">
            <div class="card mt-4" style=" background: rgba(25,255,247,0.06); ">
                <div class="card-header">
                   <strong>Datos del Cliente</strong>
                </div>

                <div class="card-body">
                    <div>
                        @if($ruc->user!=null)
                            <ul class="list-group">
                                <li class="list-group-item">Nombre Cliente: <b>{{$ruc->cliente->nombre}}</b></li>
                                <li class="list-group-item">DNI: <b>{{$ruc->cliente->dni}}</b></li>
                                <li class="list-group-item">Celular: <b>{{$ruc->cliente->celular}}</b>
                                </li>
                            </ul>
                        @else
                            <div class="alert alert-warning" role="alert">
                                No hay cliente asignado
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-4" style=" background: #ffd81914; ">
                <div class="card-header">
                    <strong>Datos del Agente</strong>
                </div>
                <div class="card-body">
                    <div>
                        @if($ruc->user!=null)
                            <ul class="list-group">
                                <li class="list-group-item bg-success">Identificador: <b>{{$ruc->user->identificador}}</b></li>
                                <li class="list-group-item">Asesor: <b>{{$ruc->user->name}}</b></li>
                                <li class="list-group-item">Email: <b>{{$ruc->user->email}}</b></li>
                                <li class="list-group-item">Celular: <b>{{$ruc->user->celular}}</b>
                                </li>
                                <li class="list-group-item">Referencia:
                                    <b>{{$ruc->user->referencia}}</b></li>
                            </ul>
                        @else
                            <div class="alert alert-warning" role="alert">
                                No hay asesor asignado
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <hr>
        </div>
    @endforeach

</div>
