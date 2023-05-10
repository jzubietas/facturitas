<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste</b></p>
    </h2>
</div>
<br>
<br>

{{--<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog- modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img alt="Dia de la mujer" src="{{ asset('/img/diaMujer.jpg') }}" style="width: 100%">
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->--}}
<div class="container-fluid">
    <div class="row" style="color: #fff;">
        <div class="col-lg-1 col-1">
        </div>
        <div class="col-lg-5 col-5">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pagosxrevisar_administracion }}</h3>
                    <p>Sobres por enviar</p>
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
                    <p>Sobres por recibir</p>
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


@section('js-datatables')
    <script>
            $('#exampleModalCenter').modal('show');
    </script>
@endsection
