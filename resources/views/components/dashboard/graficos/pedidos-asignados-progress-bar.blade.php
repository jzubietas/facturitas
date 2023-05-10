<ul class="list-group">
    <li class="list-group-item" style=" min-width: 300px; ">
        <h5>PEDIDOS PAGADOS <br> {{Str::upper($title)}}</h5>
    </li>
    @if(data_get($general,'enabled'))
        <li class="list-group-item" style=" background: #b7b7b7; ">
            <b> {{data_get($general,'name')}}</b>
            <x-bs-progressbar :progress="data_get($general,'progress')">
                {{ data_get($general,'progress')}}% - {{data_get($general,'pagados')}}
                /{{data_get($general,'activos')}}
            </x-bs-progressbar>
        </li>
    @endif
    @foreach($progressData as $data)
        <li class="list-group-item">
            <b>{{$data['code']}}</b> <br> {{$data['name']}}

            <x-bs-progressbar :progress="data_get($data,'progress')">
                {{$data['progress']}}% - {{$data['pagados']}}/{{$data['activos']}}
            </x-bs-progressbar>
            <span>
                                    % - Pagados / Asignados
                                </span>
        </li>
    @endforeach
</ul>
