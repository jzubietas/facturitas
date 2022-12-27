<div class="text-center mb-4" style="font-family:'Times New Roman', Times, serif">
    <h2>
        <p>
            Bienvenido <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, eres el
            <b>{{ Auth::user()->rol }} del sistema</b>
        </p>
    </h2>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                @foreach ($pedidoxmes_total as $mpxm)
                    <h3>{{ $mpxm->total  }}</h3>
                @endforeach
                <p>META DE PEDIDOS DEL MES</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                @foreach ($montopedidoxmes_total as $mcxm)
                    <h3>{{number_format( ($mcxm->total)/10 ,2)}} %</h3>
                @endforeach
                <p>META DE COBRANZAS DEL MES</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                @foreach ($pagoxmes_total as $pxm)
                    <h3>{{ $pxm->pedidos }}</h3>
                @endforeach
                <p>PEDIDOS DEL MES</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-lg-3 col-6">
        <div class="small-box bg-default">
            <div class="inner">
                @foreach ($pagoxmes_total_solo_asesor_b as $pxm2)
                    <h3>{{ $pxm2->pedidos }}</h3>
                @endforeach
                <p>PEDIDOS DEL MES ASESOR B</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                @foreach ($montopagoxmes_total as $cxm)
                    <h3>S/@php echo number_format( ($cxm->total)/1000 ,2) @endphp </h3>
                @endforeach
                <p>COBRANZAS DEL MES</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <x-grafico-metas-mes></x-grafico-metas-mes>
    </div>
    <div class="col-lg-12">
        <div class="card" style="
    background-color: #a5a5a5;
">
            <div class="card-header">Buscar Cliente/RUC</div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">
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
                    <div class="col-md-2">
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-end">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Seleccionar Mes</span>
                                </div>
                                <select class="form-control" id="datepickerDashborad"
                                        aria-describedby="basic-addon3">

                                    @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $month)
                                        @php
                                            $currentMonth=now()->startOfYear()->addMonths($month-1);
                                        @endphp
                                        <option {{$currentMonth->monthName==request('selected_month','diciembre')?'selected':''}} value="{{$currentMonth->monthName}}">{{Str::ucfirst($currentMonth->monthName)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row" id="widget-container">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>
                            </div>
                            <div class="col-md-6">
                                <x-grafico-metas-progress-bar></x-grafico-metas-progress-bar>
                            </div>
                        </div>
                        {{--
                         <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="grafico_pedidos_vista1-tab" data-toggle="tab"
                                   href="#grafico_pedidos_vista1" role="tab"
                                   aria-controls="grafico_pedidos_vista1" aria-selected="true">
                                    Primera Vista
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="grafico_pedidos_vista2-tab" data-toggle="tab"
                                   href="#grafico_pedidos_vista2" role="tab"
                                   aria-controls="grafico_pedidos_vista2" aria-selected="false">
                                    Segunda Vista
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="grafico_pedidos_vista3-tab" data-toggle="tab"
                                   href="#grafico_pedidos_vista3" role="tab"
                                   aria-controls="grafico_pedidos_vista3" aria-selected="false">
                                    Segunda Vista
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="grafico_pedidos_vista1" role="tabpanel"
                                 aria-labelledby="grafico_pedidos_vista1-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>
                                    </div>
                                    <div class="col-md-6">
                                        <x-grafico-metas-progress-bar></x-grafico-metas-progress-bar>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="grafico_pedidos_vista2" role="tabpanel"
                                 aria-labelledby="grafico_pedidos_vista2-tab">
                                <x-grafico-pedidos-meta-progress></x-grafico-pedidos-meta-progress>
                            </div>
                            <div class="tab-pane fade" id="grafico_pedidos_vista3" role="tabpanel"
                                 aria-labelledby="grafico_pedidos_vista3-tab">
                                <x-grafico-meta_cobranzas></x-grafico-meta_cobranzas>
                            </div>
                        </div>
                         --}}
                    </div>

                    <div class="col-md-12">
                        <x-grafico-pedidos-por-dia rol="Administrador"
                                                   title="Cantidad de pedidos de los asesores por dia"
                                                   label-x="Asesores" label-y="Cant. Pedidos"
                                                   only-day></x-grafico-pedidos-por-dia>

                        <x-grafico-pedidos-por-dia rol="Administrador"
                                                   title="Cantidad de pedidos de los asesores por mes"
                                                   label-x="Asesores"
                                                   label-y="Cant. Pedidos"></x-grafico-pedidos-por-dia>
                    </div>
                </div>
            </div>
            {{--
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart tab-pane active w-100" id="pedidosxasesor" style="height: 550px;"></div>
                    </div>
                </div>
            </div>
            --}}
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none">
                <div class="card ">
                    <div class="card-body">
                        <div class="chart tab-pane active w-100" id="cobranzaxmes" style="height: 550px; "></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <x-grafico-top-clientes-pedidos top="10"></x-grafico-top-clientes-pedidos>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none">
                <div class="card">
                    <div class="card-body">
                        <div id="pagosxmes" class="w-100" style="height: 550px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
{{-- @include('dashboard.modal.alerta') --}}
