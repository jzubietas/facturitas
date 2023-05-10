<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>
            Bienvenido(a) <b>{{ Auth::user()->name }}</b> del equipo de <b>OPERACIONES</b> al software
            empresarial de Ojo Celeste
        </p>
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

@include('dashboard.partials.vista_quitar_vidas')
<div class="col-md-12">
  <x-tabla-list-llamada-atencion></x-tabla-list-llamada-atencion>
</div>
<div class="col-lg-12">
    <x-grafico-pedidos-elect-fisico></x-grafico-pedidos-elect-fisico>
</div>


{{--
<div class="col-md-8">

    <div class="card">

        <table class="table">
            <thead>
            <tr>
                <th style="background-color: #4c5eaf; text-align: left; color: white;">Nº</th>
                <th style="background-color: #4c5eaf; text-align: left; color: white;">ASESOR</th>
                <th style="background-color: #4c5eaf; text-align: left; color: white;">ELECTRONICA</th>
                <th style="background-color: #4c5eaf; text-align: left; color: white;">FISICA</th>
                <th style="background-color: #4c5eaf; text-align: left; color: white;">PEDIDOS</th>


            </tr>
            </thead>
            <tbody>
            <?php $cont = 0; ?>
            @foreach ($_pedidos_mes_op as $b)
                <tr>
                    <td>{{ $cont + 1 }}</td>
                    <td>{{ $b->name }}</td>
                    <td>{{ $b->electronico }}</td>
                    <td>{{ $b->fisico }}</td>
                    <td>{{ $b->total }}</td>

                </tr>
                    <?php $cont++; ?>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
--}}

<div class="col-md-5">
    <x-tabla-jef-operaciones-fis-elect></x-tabla-jef-operaciones-fis-elect>
</div>

@section('js-datatables')
    <script>
            $('#exampleModalCenter').modal('show');
    </script>
@endsection
