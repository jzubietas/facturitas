<div class="card">
    <div class="card-header">
        <b>Progreso total de pedidos pagados del mes {{$title}}</b>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @if(data_get($general,'enabled'))
                <li class="list-group-item">
                    <b> {{data_get($general,'name')}}</b>
                    <div class="progress">
                        <div class="progress-bar
                @if(data_get($general,'progress')<40)
                 bg-danger
                 @elseif( data_get($general,'progress')<70)
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
            @foreach(collect($progressData)->chunk(3) as $registros)
                <li class="list-group-item">
                    <ul class="list-group list-group-horizontal-lg w-100 d-flex">
                        @foreach($registros as $data)
                            <li class="list-group-item col-md-4">
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
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

    </div>
</div>
