<div class="row">
    @foreach($clientes as $cliente)

        <div class="col-md-6">
            <div class="card mt-4" style=" background: rgba(25,255,247,0.06); ">
                <div class="card-header">
                    <strong>Datos del Cliente</strong>
                </div>

                <div class="card-body">
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item">Nombre Cliente: <b>{{$cliente->nombre}}</b></li>
                            <li class="list-group-item">DNI: <b>{{$cliente->dni}}</b></li>
                            <li class="list-group-item">Celular: <b>{{$cliente->celular}}</b>
                            </li>
                        </ul>
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
                        @if($cliente->user!=null)
                            <ul class="list-group">
                                <li class="list-group-item bg-success">Identificador:
                                    <b>{{$cliente->user->identificador}}</b></li>
                                <li class="list-group-item">Asesor: <b>{{$cliente->user->name}}</b></li>
                                <li class="list-group-item">Email: <b>{{$cliente->user->email}}</b></li>
                                <li class="list-group-item">Celular: <b>{{$cliente->user->celular}}</b>
                                </li>
                                <li class="list-group-item">Referencia:
                                    <b>{{$cliente->user->referencia}}</b>
                                </li>
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
