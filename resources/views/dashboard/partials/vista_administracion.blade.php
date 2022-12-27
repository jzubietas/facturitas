<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> del equipo de <b>ADMINISTRACION</b> al software
            empresarial de Ojo Celeste
        </p>
    </h2>
</div>
<br>
<br>
<div class="container-fluid">
    <div class="row" style="color: #fff;">
        <div class="col-lg-1 col-1">
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pagosxrevisar_administracion }}</h3>
                    <p>PAGOS POR REVISAR</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('administracion.porrevisar') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pagosobservados_administracion }}</h3>
                    <p>PAGOS OBSERVADOS</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('administracion.porrevisar') }}" class="small-box-footer">Más info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-1 col-1">
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
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
