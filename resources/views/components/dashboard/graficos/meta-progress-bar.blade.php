<div class="card">
    <div class="card-header">
        <b>TOTAL DE PEDIDOS -- {{Str::upper($title)}}</b>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @if(data_get($general,'enabled'))
                <li class="list-group-item" style=" background: #d5d5d5; ">
                    <b> {{data_get($general,'name')}}</b>
                    <x-bs-progressbar :progress="data_get($general,'progress')">
                        {{ data_get($general,'progress')}}% - {{data_get($general,'asignados')}}
                        /{{data_get($general,'meta')}}
                    </x-bs-progressbar>
                </li>
            @endif
            @foreach($progressData as $data)
                            <li class="list-group-item">
                                <b>{{$data['code']}}</b> <br> {{$data['name']}}

                                <x-bs-progressbar :progress="data_get($data,'progress')">
                                    {{$data['progress']}}% - {{$data['asignados']}}/{{$data['meta']}}
                                </x-bs-progressbar>
                                <span>
                                    % - Asignados / Meta
                                </span>
                            </li>
                        @endforeach

        </ul>

    </div>
</div>
