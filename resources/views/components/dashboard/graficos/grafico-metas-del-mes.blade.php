<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex justify-content-center">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="text-center"><b>METAS DEL MES</b></h4>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item" style=" min-width: 300px; ">
                                            <h5>COBRANZAS {{Str::upper($now_submonth->monthName)}}</h5>
                                        </li>
                                        <li class="list-group-item" style=" background-color: #b7b7b7; ">
                                            <b>GENERAL </b>
                                            <x-bs-progressbar :progress="$data_noviembre->progress">
                                                <span> <b>  {{$data_noviembre->progress}}%</b> - {{$data_noviembre->current}}/{{$data_noviembre->total}}</span>
                                            </x-bs-progressbar>
                                        </li>
                                        @foreach($novResult as $data)
                                            <li class="list-group-item">
                                                <b>{{$data['code']}}</b> <br> {{$data['name']}}
                                                <x-bs-progressbar :progress="$data['progress']">
                                                    <span> <b>{{$data['progress']}}%</b> - {{$data['current']}}/{{$data['total']}}</span>
                                                </x-bs-progressbar>
                                                <span>
                                    % - Asignados / Meta
                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item" style=" min-width: 300px; ">
                                            <h4>PEDIDOS {{Str::upper($now->monthName)}}</h4>
                                        </li>
                                        <li class="list-group-item" style=" background-color: #b7b7b7; ">
                                            <b>GENERAL </b>
                                            <x-bs-progressbar :progress="$data_diciembre->progress">
                                                <span> <b>{{$data_diciembre->progress}}%</b> - {{$data_diciembre->total}}/{{$data_diciembre->meta}}</span>
                                            </x-bs-progressbar>
                                        </li>
                                        @foreach($dicResult as $data)
                                            <li class="list-group-item">
                                                <b>{{$data['code']}}</b> <br> {{$data['name']}}
                                                <x-bs-progressbar :progress="$data['progress']">
                                                    <span><b>{{$data['progress']}}%</b> - {{$data['total']}}/{{$data['meta']}}</span>
                                                </x-bs-progressbar>
                                                <span>
                                    % - Asignados / Meta
                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @if(count($excludeNov)>0)
                <div class="col-md-6">
                    <div class="d-flex justify-content-center">
                        <ul class="list-group">
                            <li class="list-group-item" style=" min-width: 300px; ">
                                <h4 class="text-center"><b>EXCLUIDOS</b></h4>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-group">
                                            <li class="list-group-item" style=" min-width: 300px; ">
                                                <h5>COBRANZAS {{Str::upper($now_submonth->monthName)}}</h5>
                                            </li>
                                            @foreach($excludeNov as $index=>$data)
                                                <li class="list-group-item">
                                                    <b>{{$data['code']}}</b> <br> {{$data['name']}}
                                                    <x-bs-progressbar :progress="$data['progress']">
                                                        <span><b>{{$data['progress']}}%</b> - {{$data['total']}}/{{$data['meta']}}</span>
                                                    </x-bs-progressbar>
                                                    <span>% - Asignados / Meta</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-group">
                                            <li class="list-group-item" style=" min-width: 300px; ">
                                                <h4>PEDIDOS {{Str::upper($now->monthName)}}</h4>
                                            </li>
                                            @foreach($excludeDic as $index=>$data)
                                                <li class="list-group-item">
                                                    <b>{{$data['code']}}</b> <br> {{$data['name']}}
                                                    <x-bs-progressbar :progress="$data['progress']">
                                                        <span><b>{{$data['progress']}}%</b> - {{$data['total']}}/{{$data['meta']}}</span>
                                                    </x-bs-progressbar>
                                                    <span>% - Asignados / Meta</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('css')
    <style>
        .list-group .list-group-item {
            background: #a5770f1a;
        }
    </style>
@endpush
