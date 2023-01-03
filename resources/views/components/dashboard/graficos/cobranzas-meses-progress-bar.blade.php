<ul class="list-group">
    <li class="list-group-item" style=" min-width: 300px; ">
        <div class="row">
            <div class="col-md-6">
                <h5>COBRANZAS <br>{{Str::upper($title)}} a {{Str::upper($startDate->clone()->addMonths($total_dias-1)->monthName)}}
                    - {{$startDate->clone()->addMonths($total_dias-1)->year}}</h5>
            </div>
            <div class="col-md-6">
                <x-bs-progressbar :progress="data_get($totalMonths,'progress')">
                    <p><b> {{$totalMonths['progress']}}% - {{$totalMonths['pagados']}}/{{$totalMonths['total']}}</b></p>
                </x-bs-progressbar>
            </div>
        </div>
    </li>
    <li class="list-group-item" style=" background: #b7b7b7; ">
        <b>{{collect($general)->values()->get(0)['name']}}</b>

        <div class="row">
            @foreach($general as $datestr=>$data)
                <div class="col-md-3">
                    <x-bs-progressbar :progress="data_get($data,'progress')">
                        <p><b>{{$datestr}} | {{$data['progress']}}% - {{$data['pagados']}}/{{$data['activos']}}</b></p>
                    </x-bs-progressbar>
                </div>
            @endforeach
        </div>
    </li>
    @foreach($progressData as $identificador=>$dataall)
        <li class="list-group-item" @if($loop->index%2==0) style="background: #ffffff4f" @endif>
            <b>{{$identificador}}</b> <br> {{collect($dataall)->values()->get(0)['name']}}

            <div class="row">
                @foreach($dataall as $datestr=>$data)
                    <div class="col-md-3">
                        <x-bs-progressbar :progress="data_get($data,'progress')">
                            <p><b>{{$datestr}} | {{$data['progress']}}% - {{$data['pagados']}}/{{$data['activos']}}</b>
                            </p>
                        </x-bs-progressbar>
                    </div>
                @endforeach
            </div>
            <span>% - Cobrados / Asignados</span>
            <div class="mt-4">
                <x-bs-progressbar :progress="data_get($totales[$identificador],'progress')">
                    <p><b>{{$totales[$identificador]['progress']}}% - {{$totales[$identificador]['pagados']}}
                            /{{$totales[$identificador]['activos']}}</b></p>
                </x-bs-progressbar>
            </div>
        </li>
    @endforeach
</ul>
