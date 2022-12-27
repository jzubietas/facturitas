<div class="card">
    <div class="card-header">
        <b>PEDIDOS PAGADOS -- {{Str::upper($title)}}</b>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @if(data_get($general,'enabled'))
                <li class="list-group-item" style=" background: #d5d5d5; ">
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

    </div>
</div>
