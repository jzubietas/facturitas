<div class="row">
    @foreach($clientes as $cliente)

        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>
                        Asesor Identificador: <b>{{$cliente->user->identificador}}</b>
                    </h4>
                    <h5>
                        Cliente:{{$cliente->nombre}}
                    </h5>
                </div>

                <div class="card-body">
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item">Asesor: <b>{{$cliente->user->name}}</b></li>
                            <li class="list-group-item">Email: <b>{{$cliente->user->email}}</b></li>
                            <li class="list-group-item">Celular: <b>{{$cliente->user->celular}}</b>
                            </li>
                            <li class="list-group-item">Provincia: <b>{{$cliente->user->provincia}}</b></li>
                            <li class="list-group-item">Distrito: <b>{{$cliente->user->distrito}}</b>
                            </li>
                            <li class="list-group-item">Dirección:
                                <b>{{$cliente->user->direccion}}</b></li>
                            <li class="list-group-item">Referencia:
                                <b>{{$cliente->user->referencia}}</b></li>
                            <li class="list-group-item">ID: <b>{{$cliente->user->id}}</b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
