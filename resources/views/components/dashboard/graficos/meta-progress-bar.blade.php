<ul class="list-group">
    <li class="list-group-item" style=" min-width: 300px; ">
        <h5>TOTAL DE PEDIDOS <br> {{Str::upper($title)}}</h5>
    </li>
    @if(data_get($general,'enabled'))
        <li class="list-group-item" style=" background: #b7b7b7; ">
            <b> {{data_get($general,'name')}}</b>
            <x-bs-progressbar :progress="data_get($general,'progress')">
                {{ data_get($general,'progress')}}% - {{data_get($general,'asignados')}}
                /{{data_get($general,'meta')}}
            </x-bs-progressbar>
        </li>
    @endif
    @foreach($progressData as $data)
        <li class="list-group-item" @if($loop->index%2==0) style="background: #ffffff4f" @endif>
            <b>{{$data['code']}}</b> <br> {{$data['name']}}

            <x-bs-progressbar :progress="data_get($data,'progress')">
                {{$data['progress']}}% - {{$data['asignados']}}/{{$data['meta']}}
            </x-bs-progressbar>
            <span>% - Asignados / Meta</span>
        </li>
    @endforeach
</ul>
