<div class="row">
    @foreach($rucs as $ruc)
        <div class="col-md-12">
            <h4>
                Empresa: <b>{{$ruc->empresa}}</b>
                RUC: <b>{{$ruc->num_ruc}}</b>
            </h4>
        </div>
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">
                    Cliente <b>{{$ruc->cliente->nombre}}</b>
                </div>

                <div class="card-body">
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item">DNI: <b>{{$ruc->cliente->dni}}</b></li>
                            <li class="list-group-item">Celular: <b>{{$ruc->cliente->celular}}</b>
                            </li>
                            <li class="list-group-item">Provincia:
                                <b>{{$ruc->cliente->provincia}}</b></li>
                            <li class="list-group-item">Distrito: <b>{{$ruc->cliente->distrito}}</b>
                            </li>
                            <li class="list-group-item">Dirección:
                                <b>{{$ruc->cliente->direccion}}</b></li>
                            <li class="list-group-item">Referencia:
                                <b>{{$ruc->cliente->referencia}}</b></li>
                            <li class="list-group-item">ID: <b>{{$ruc->cliente->id}}</b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">
                    Identificador: <b>{{$ruc->user->identificador}}</b>
                </div>

                <div class="card-body">
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item">Asesor: <b>{{$ruc->user->name}}</b></li>
                            <li class="list-group-item">Email: <b>{{$ruc->user->email}}</b></li>
                            <li class="list-group-item">Celular: <b>{{$ruc->user->celular}}</b>
                            </li>
                            <li class="list-group-item">Provincia: <b>{{$ruc->user->provincia}}</b></li>
                            <li class="list-group-item">Distrito: <b>{{$ruc->user->distrito}}</b>
                            </li>
                            <li class="list-group-item">Dirección:
                                <b>{{$ruc->user->direccion}}</b></li>
                            <li class="list-group-item">Referencia:
                                <b>{{$ruc->user->referencia}}</b></li>
                            <li class="list-group-item">ID: <b>{{$ruc->user->id}}</b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
