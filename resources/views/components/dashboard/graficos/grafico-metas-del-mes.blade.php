<style>
    .bg-celeste{
        background-color:#9ff1ed !important;"
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="@if(count($excludeNov)>0)col-md-12 @else col-md-12 @endif">
                <div class="@if(count($excludeNov)==0)  justify-content-center @endif">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="text-center"><b>METAS DEL MES</b></h4>
                        </li>
                        <li class="list-group-item">
                            <ul class="list-group">
                                <li class="list-group-item" style=" min-width: 300px; ">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-6"><h5 class="text-center">COBRANZAS {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}</h5></div>
                                        <div class="col-4"><h5 class="text-center">PEDIDOS {{Str::upper($now->monthName)}} - {{$now->year}}</h5></div>
                                    </div>
                                </li>
                                <li class="list-group-item" style=" background-color: #b7b7b7; ">
                                    <div class="row">
                                        <div class="col-2">Asesor</div>
                                        <div class="col-1">Identificador</div>
                                        <div class="col-1">Pedidos</div>
                                        <div class="col-4">
                                            @if ($data_noviembre->progress<'100')
                                            <x-bs-progressbar :progress="$data_noviembre->progress">
                                                <span> <b>  {{$data_noviembre->progress}}%</b> - {{$data_noviembre->current}}/{{$data_noviembre->total}}</span>
                                            </x-bs-progressbar>
                                            @else
                                                <div class="position-relative">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                             style="width: {{$data_noviembre->progress_2}}%"
                                                             aria-valuenow="{{$data_noviembre->progress_2}}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
                                                        <span> <b>  {{$data_noviembre->progress_2}}%</b> - {{$data_noviembre->current}}/{{$data_noviembre->total_2}}</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <sub class="d-none">% -  Pagados/ Asignados</sub>
                                        </div>
                                        <div class="col-4">
                                            @if ($data_diciembre->progress<'100')
                                            <x-bs-progressbar :progress="$data_diciembre->progress">
                                                <span> <b>{{$data_diciembre->progress}}%</b> - {{$data_diciembre->total}}/{{$data_diciembre->meta}}</span>
                                            </x-bs-progressbar>
                                            @else
                                                <div class="position-relative">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                             style="width: {{$data_diciembre->progress_2}}%"
                                                             aria-valuenow="{{$data_diciembre->progress_2}}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
                                                        <span> <b>  {{$data_diciembre->progress_2}}%</b> - {{$data_diciembre->current}}/{{$data_diciembre->total_2}}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <sub class="d-none">% -  Pagados/ Meta</sub>
                                        </div>
                                    </div>
                                </li>
                                @foreach($novResult as $key=>$data)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-2">
                                                 {{data_get($data,'name')}}
                                            </div>
                                            <div class="col-1">
                                                <b>{{data_get($data,'code')}}</b>
                                            </div>
                                            <div class="col-1 border border-danger bg-danger rounded text-center">
                                                @if (data_get($data,'pedidos_dia')==0)
                                                <span class="text-white d-block bg-success">
                                                    <b>{{data_get($data,'pedidos_dia')}}</b>
                                                </span>
                                                @else
                                                <span class="text-dark">
                                                    <b>{{data_get($data,'pedidos_dia')}}</b>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-4">
                                                <x-bs-progressbar :progress="$data['progress']">
                                                    <span> <b>{{$data['progress']}}%</b> - {{$data['current']}}/{{$data['total']}}</span>
                                                </x-bs-progressbar>
                                                <sub class="d-none">% -  Pagados/ Asignados</sub>
                                            </div>
                                            <div class="col-4">
                                                @if ($dicResult[$key]['progress']<100)
                                                    <x-bs-progressbar :progress="$dicResult[$key]['progress']">
                                                        <span><b>{{$dicResult[$key]['progress']}}%</b> - {{$dicResult[$key]['total']}}/{{$dicResult[$key]['meta']}}</span>

                                                    </x-bs-progressbar>
                                                @else
                                                    <div class="position-relative">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-celeste"  role="progressbar"
                                                                 style="width: {{$dicResult[$key]['progress_2']}}%"
                                                                 aria-valuenow="{{$dicResult[$key]['progress_2']}}"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
                                                            <span><b>{{$dicResult[$key]['progress_2']}}%</b> - {{$dicResult[$key]['total']}}/{{$dicResult[$key]['meta_2']}}</span>
                                                        </div>
                                                    </div>
                                                @endif


                                                <sub class="d-none">% - Asignados / Meta</sub>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            @if(count($excludeNov)>0)
                <div class="col-md-6">
                    <div class="">
                        <ul class="list-group">
                            <li class="list-group-item" style="background: #0000001a; min-width: 300px; ">
                                <h4 class="text-center"><b>EXCLUIDOS</b></h4>
                            </li>
                            <li class="list-group-item" style="background: #0000001a; min-width: 300px; ">
                                <ul class="list-group">
                                    <li class="list-group-item" style="background: #0000001a; min-width: 300px; ">
                                        <div class="row">
                                            <div class="col-2"></div>
                                            <div class="col-6"><h5 class="text-center">COBRANZAS {{Str::upper($now_submonth->monthName)}} {{$now_submonth->year}}</h5></div>
                                            <div class="col-4"><h5 class="text-center">PEDIDOS {{Str::upper($now->monthName)}} {{$now->year}}</h5></div>
                                        </div>
                                    </li>
                                    @foreach($excludeNov as $index=>$data)
                                        <li class="list-group-item" style="background: #0000001a;">
                                            <div class="row">
                                                <div class="col-2">
                                                   {{$data['name']}}
                                                </div>
                                                <div class="col-2">
                                                    <b>{{$data['code']}}</b>
                                                </div>
                                                <div class="col-4">
                                                    <x-bs-progressbar :progress="$data['progress']">
                                                        <span><b>{{$data['progress']}}%</b> - {{$data['total']}}/{{$data['meta']}}</span>
                                                    </x-bs-progressbar>
                                                    <sub>% - Pagados / Asignados</sub>
                                                </div>
                                                <div class="col-4">
                                                    <x-bs-progressbar :progress="$excludeDic[$index]['progress']">
                                                        <span><b>{{$excludeDic[$index]['progress']}}%</b> - {{$excludeDic[$index]['total']}}/{{$excludeDic[$index]['meta']}}</span>
                                                    </x-bs-progressbar>
                                                    <sub class="d-none">% - Asignados / Meta</sub>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
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
