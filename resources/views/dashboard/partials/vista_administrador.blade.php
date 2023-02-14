@yield('css-datatables')

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

    <div class="col-lg-9 col-12">
        @include('dashboard.widgets.pedidos_creados')
    </div>
</div>


<div class="row">
    @include('dashboard.widgets.buscar_cliente')
    <div class="col-lg-12">
        <x-common-activar-cliente-por-tiempo></x-common-activar-cliente-por-tiempo>
    </div>
    <div class="col-lg-12">
        <x-grafico-pedidos-elect-fisico></x-grafico-pedidos-elect-fisico>
    </div>
    {{-- <div class="col-lg-12">
        <x-grafico-metas-mes></x-grafico-metas-mes>
    </div> --}}

    <div class="col-lg-12">
        <div class="">
            <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
    <table id="metas" class="table table-bordered border-2" style="width:100%">
        <thead style="background: #e4dbc6; border: 1px solid red; justify-content: center">
            <tr class="font-weight-bold">
                <th>Asesor</th>
                <th>Identificador</th>
                <th>Pedidos</th>
                <th class="animated-progress"> TITULO
                    {{-- {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}} --}}
                <br>
                {{-- {{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}} --}}

            </th>
                <th>
                    {{Str::upper(\Carbon\Carbon::now()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                <br>
                {{-- {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}} --}}
                </th>
            </tr>
        </thead>
        <tbody style="background: #e4dbc6">

        </tbody>
    </table>
    </div>
</div>

    <div class="container-fluid">
        <div class="row">
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
                <div class="row" id="widget-container">
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
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <x-grafico-pedidos-atendidos-anulados></x-grafico-pedidos-atendidos-anulados>
                            </div>

                            <div class="col-lg-12">
                                <x-grafico-pedido_cobranzas-del-dia></x-grafico-pedido_cobranzas-del-dia>
                            </div>
                        </div>
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

            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none">
                <div class="card ">
                    <div class="card-body">
                        <div class="chart tab-pane active w-100" id="cobranzaxmes" style="height: 550px; "></div>
                    </div>
                </div>
            </div> --}}
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

@yield('js-datatables')

@push('css')
    <style>
        .list-group .list-group-item {
            background: #a5770f1a;
        }

        .animated-progress {
  width: 300px;
  height: 30px;
  border-radius: 5px;
  margin: 20px 10px;
  border: 1px solid rgb(189, 113, 113);
  overflow: hidden;
  position: relative;
}

.animated-progress span {
  height: 100%;
  display: block;
  width: 0;
  color: rgb(255, 251, 251);
  line-height: 30px;
  text-align: end;
  padding-right: 5px;
}

td:nth-child(3){
    display: flex;
    justify-content: center;
    align-items: center;
}
.gradient-yellow-to-green{
    background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(219,214,29,1) 0%, rgba(144,209,3,1) 75%, rgba(121,255,0,1) 100%) !important;
}

    </style>
@endpush

@section('css-datatables')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
@endsection

@section('js-datatables')
<script>
    $(".animated-progress span").each(function () {
  $(this).animate(
    {
      width: $(this).attr("data-progress") + "%",
    },
    1000
  );
  $(this).text($(this).attr("data-progress") + "%");
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>
<script>
  let meta = null
    meta = $('#metas').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                //dom: "",
                "order": [[0, "desc"]],
                ajax: {
                    url: "{{ route('dashboard.graficoMetaTable') }}",
                },
                columns: [
                    {
                        data: 'name',
                        name: ' name',
                    },
                    {
                        data: 'code'
                        , name: 'code'
                    },
                    {data: 'total_pedido', name: 'total_pedido',},

                    {data: 'progress_pagos', name: 'progress_pagos'},
                    {data: 'progress_pedidos', name: 'progress_pedidos',},

                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },

            });
</script>
<script>
setInterval(recargametasxmes, 100000000);
function recargametasxmes(){
    $("#metas").DataTable().ajax.reload();
}
</script>

@endsection
