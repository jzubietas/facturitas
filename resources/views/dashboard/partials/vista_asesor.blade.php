<div class="container-fluid">
    <div class="row" style="text-align: center; font-family:Georgia, 'Times New Roman', Times, serif">
        <div class="col-lg-9 col-9" style="margin-top:20px">
            <h2>
                <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de sisFacturas,
                    donde cumples la función de <b>{{ Auth::user()->rol }}</b></p>
            </h2>
        </div>
        <div class="col-lg-3 col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pagosobservados_cantidad }}</h3>
                    <p>PAGOS OBSERVADOS</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('pagos.pagosobservados') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
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
                <a href="{{ route('pedidos.mispedidos') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>S/{{ (int)Auth::user()->meta_cobro }}</h3>
                    <p>META DE COBRANZAS</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('pagos.mispagos') }}" class="small-box-footer">Más info <i
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
                    <h3>{{ $meta_pedidoasesor }}</h3>
                    <p>TUS PEDIDOS DEL MES</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('pedidos.mispedidos') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>S/{{number_format( ($meta_pagoasesor->pagos)/1000 ,2)}} </h3>
                    <p>MIS COBRANZAS DEL MES</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('pagos.mispagos') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-1 col-1">
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Seleccionar Mes</span>
                        </div>
                        <input type="text" class="form-control date-picker" id="datepickerDashborad" aria-describedby="basic-addon3">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>
                    </div>
                    <div class="col-md-6">
                        <x-grafico-metas-progress-bar></x-grafico-metas-progress-bar>
                    </div>
                </div>
                <div class="col-md-12">
                    <x-grafico-meta_cobranzas></x-grafico-meta_cobranzas>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <div class="chart tab-pane active" id="mispedidosxasesorxdia"
                                 style="width: 100%; height: 550px;">
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <br>
            <div class="table-responsive">
                <img src="imagenes/logo_facturas.png" alt="Logo" width="100%">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
        </div>
    </div>
</div>
@include('dashboard.modal.asesoralerta')
