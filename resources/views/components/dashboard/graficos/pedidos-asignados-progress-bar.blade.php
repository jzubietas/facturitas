<div class="card">
    <div class="card-header">
        <b>PEDIDOS PAGADOS -- {{Str::upper($title)}}</b>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @if(data_get($general,'enabled'))
                <li class="list-group-item" style=" background: #d5d5d5; ">
                    <b> {{data_get($general,'name')}}</b>
                    <div class="progress">
                        <div class="progress-bar
                @if(data_get($general,'progress')<50)
                 bg-danger
                 @elseif( data_get($general,'progress')<80)
                 bg-warning
                  @else
                  bg-success
                  @endif
                 "
                             role="progressbar"
                             style="width: {{ data_get($general,'progress')}}%"
                             aria-valuenow="{{ data_get($general,'progress')}}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ data_get($general,'progress')}}% - {{data_get($general,'pagados')}}
                            /{{data_get($general,'activos')}}
                        </div>
                    </div>
                </li>
            @endif
            @foreach($progressData as $data)
                <li class="list-group-item">
                    <b>{{$data['code']}}</b> <br> {{$data['name']}}
                    <div class="progress">
                        <div class="progress-bar
                @if($data['progress']<40)
                 bg-danger
                 @elseif($data['progress']<70)
                 bg-warning
                  @else
                  bg-success
                  @endif
                 "
                             role="progressbar"
                             style="width: {{$data['progress']}}%"
                             aria-valuenow="{{$data['progress']}}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{$data['progress']}}% - {{$data['pagados']}}/{{$data['activos']}}
                        </div>
                    </div>
                    <span>
                                    % - Pagados / Asignados
                                </span>
                </li>
            @endforeach

        </ul>

    </div>
</div>
