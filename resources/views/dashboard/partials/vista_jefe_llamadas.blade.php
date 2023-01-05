<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste</b></p>
    </h2>
</div>
<br>
<br>

<div class="row">
    <div class="col-lg-6">
        <div class="card" style="background-color: #a5770f1a;">
            <div class="card-header">Buscar Cliente/RUC</div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text p-0">
                                    <select id="input_search_type" class="form-control">
                                        <option value="CLIENTE">CLIENTE</option>
                                        <option value="RUC">RUC</option>
                                    </select>
                                </div>
                            </div>
                            <input id="input_search_cliente" class="form-control" maxlength="11"
                                   placeholder="Buscar cliente">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-dark" id="buttom_search_cliente">
                                    <i class="fa fa-search"></i>
                                    Buscar
                                </button>
                                <button type="button" class="btn btn-light"
                                        id="buttom_search_cliente_clear">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="search_content_result">
                </div>
            </div>
        </div>
    </div>
{{--
    <div class="col-lg-12">
        <x-grafico-metas-mes></x-grafico-metas-mes>
    </div>

--}}

    <div class="col-md-12">
        <div class="card">
            <div class="d-flex justify-content-end align-items-center">
                <div class="card my-2 mx-2">
                    @php
                        try {
                             $currentDate=\Carbon\Carbon::createFromFormat('m-Y',request('selected_date',now()->format('m-Y')));
 }catch (Exception $ex){
                             $currentDate=\Carbon\Carbon::createFromFormat('m-Y',now()->format('m-Y'));
 }

                    @endphp
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> Seleccionar Mes</span>
                        </div>
                        <div class="input-group-prepend">
                            <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->startOfMonth()->subYear()->format('m-Y')])}}"
                               class="btn m-0 p-0"
                               data-toggle="tooltip" data-placement="top" title="Un año atras">
                            <span class="input-group-text">
                                <
                            </span>
                            </a>
                            <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->startOfMonth()->subMonth()->format('m-Y')])}}"
                               class="btn m-0 p-0"
                               data-toggle="tooltip" data-placement="top" title="Un mes atras">
                                <span class="input-group-text"><</span>
                            </a>
                        </div>
                        <select class="form-control" id="datepickerDashborad"
                                aria-describedby="basic-addon3">

                            @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $month)
                                @php
                                    $currentMonth=$currentDate->clone()->startOfYear()->addMonths($month-1);
                                @endphp
                                <option
                                    {{$currentMonth->format('m-Y')==request('selected_date',now()->format('m-Y'))?'selected':''}}
                                    value="{{$currentMonth->format('m-Y')}}"
                                >{{Str::ucfirst($currentMonth->monthName)}} {{$currentMonth->year}}</option>
                            @endforeach
                        </select>

                        <div class="input-group-append">
                            <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->addMonths()->format('m-Y')])}}"
                               class="btn m-0 p-0"
                               data-toggle="tooltip" data-placement="top" title="Un mes adelante">
                                <span class="input-group-text">></span>
                            </a>
                        </div>
                        <div class="input-group-append">
                            <a href="{{route('dashboard.index',['selected_date'=>$currentDate->clone()->addYear()->format('m-Y')])}}"
                               class="btn m-0 p-0"
                               data-toggle="tooltip" data-placement="top" title="Un año adelante">
                                <span class="input-group-text">></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4 pb-4">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-9">
                                    {{-- <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>--}}
                                    <x-grafico-cobranzas-meses-progressbar></x-grafico-cobranzas-meses-progressbar>
                                </div>
                                <div class="col-md-3">
                                    <x-grafico-pedidos-mes-count-progress-bar></x-grafico-pedidos-mes-count-progress-bar>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
