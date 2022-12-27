<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, donde
            cumples la función de <b>{{ Auth::user()->rol }}</b></p>
    </h2>
</div>
<br>
<br>
<div class="container-fluid">
    <div class="row" style="color: #fff;">
        <div class="col-lg-1 col-1">
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>@php echo number_format(Auth::user()->meta_pedido)@endphp</h3>
                    <p>META DE PEDIDOS</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-success">
                <div class="inner">
                    {{-- @foreach ($montoventadia as $mvd) --}}
                    <h3>S/{{ Auth::user()->meta_cobro }}</h3>
                    {{-- @endforeach --}}
                    <p>META DE COBRANZAS</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-1 col-1">
        </div>
        <div class="col-lg-1 col-1">
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $meta_pedidoencargado }}</h3>
                    <p>TUS PEDIDOS DEL MES</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('pedidos.index') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>S/@php echo number_format( ($meta_pagoencargado->pagos)/1000 ,2) @endphp </h3>

                    <p>MIS COBRANZAS DEL MES</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('pagos.index') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-1 col-1">
        </div>
    </div>
</div>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-12">
                <x-grafico-metas-mes></x-grafico-metas-mes>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="card my-2 mx-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Seleccionar Mes</span>
                            </div>
                            <select class="form-control" id="datepickerDashborad"
                                    aria-describedby="basic-addon3">

                                @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $month)
                                    @php
                                        $currentMonth=now()->startOfYear()->addMonths($month-1);
                                    @endphp
                                    <option
                                        {{$currentMonth->monthName==request('selected_month','diciembre')?'selected':''}} value="{{$currentMonth->monthName}}">{{Str::ucfirst($currentMonth->monthName)}}</option>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>
                                            </div>
                                            <div class="col-md-6">
                                                <x-grafico-metas-progress-bar></x-grafico-metas-progress-bar>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
                <div class="col-md-12">
                    <x-grafico-meta_cobranzas></x-grafico-meta_cobranzas>
                </div>
                --}}
                <div class="col-md-12">
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR DIA"
                                               label-x="Asesores" label-y="Cant. Pedidos"
                                               only-day></x-grafico-pedidos-por-dia>
                    <x-grafico-pedidos-por-dia rol="Encargado" title="CANTIDAD DE PEDIDOS DE LOS ASESORES POR MES"
                                               label-x="Asesores" label-y="Cant. Pedidos"></x-grafico-pedidos-por-dia>
                </div>
            </div>
        </div>
        {{--
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover"><br><h4>
                        PEDIDOS DEL DIA POR ASESOR</h4>
                    <div id="pedidosxasesorxdia_encargado" style="width: 100%; height: 500px;"></div>
                </table>
            </div>
        </div>
        --}}
    </div>
</div>
{{--
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <div class="chart tab-pane active" id="pedidosxasesor_encargado"
                         style="width: 100%; height: 550px;">
                    </div>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <div class="chart tab-pane active" id="pedidosxasesor_3meses_encargado"
                         style="width: 100%; height: 550px;">
                    </div>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <div id="pagosxmes_encargado" style="width: 100%; height: 550px;">
                    </div>
                </table>
            </div>
        </div>
    </div>
</div>
--}}
