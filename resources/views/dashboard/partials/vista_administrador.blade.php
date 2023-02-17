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

  {{--MODAL--}}
  <div class="modal modal-fullscreen p-0" id="myModal"  role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-fullscreen bg-blue" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding: 2px 16px;">
          <h5 class="modal-title text-black" id="exampleModalLabel">METAS DEL MES</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="color: black">
          <table id="meta_duplicat_tot_modal" class="table table-bordered border-2 col-lg-12 col-md-12 col-sm-12" style="width:100%">
            <thead style="background: #e4dbc6; border: 1px solid red; justify-content: center">
            <tr class="font-weight-bold">
              <th class="h6 font-weight-bold">Asesor</th>
              <th class="h6 font-weight-bold">Identificador</th>
              <th class="h6 font-weight-bold">Pedidos del día {{Str::upper(\Carbon\Carbon::now()->add(1,'day')->sub('1 day')->isoFormat('D - M'))}} </th>
              <th class="h6 font-weight-bold animated-progress text-uppercase">Cobranza
                {{Str::upper(\Carbon\Carbon::now()->subMonth()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                {{-- {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}
                <br>
                {{-- {{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}} --}}

              </th>
              <th class="h6 font-weight-bold text-uppercase">Pedidos
                {{Str::upper(\Carbon\Carbon::now()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                <br>
                {{-- {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}} --}}
              </th>
            </tr>
            </thead>
            <tbody style="background: #e4dbc6; font-weight: bold">
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
  {{--FIN-MODAL--}}

    <div class="col-lg-12">
      <div class="d-flex justify-content-center">
        <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
        <button type="button" class="btn" data-toggle="modal" data-target="#myModal" id="myModal">
          <i class="fas fa-expand-arrows-alt"></i>
        </button>
      </div>

      {{--TABLA DUAL--}}
      <div class="">
        <div class="d-flex flex-wrap">
          <table id="metas" class="table table-bordered border-2 col-lg-6 col-md-12 col-sm-12" style="width:100%">
            <thead style="background: #e4dbc6; border: 1px solid red; justify-content: center">
            <tr class="font-weight-bold">
              <th class="h6 font-weight-bold" id="#asesor">Asesor</th>
              <th class="h6 font-weight-bold" id="#identificador">Identificador</th>
              <th class="h6 font-weight-bold" id="#pedido_dia">Pedidos del día {{Str::upper(\Carbon\Carbon::now()->add(1,'day')->sub('1 day')->isoFormat('D - M'))}} </th>
              <th class="h6 font-weight-bold animated-progress text-uppercase" id="#cobranza_mes">Cobranza
                {{Str::upper(\Carbon\Carbon::now()->subMonth()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                {{-- {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}
                <br>
                {{-- {{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}} --}}

              </th>
              <th class="h6 font-weight-bold text-uppercase" id="#pedidos_mes">Pedidos
                {{Str::upper(\Carbon\Carbon::now()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                <br>
                {{-- {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}} --}}
              </th>
            </tr>
            </thead>
            <tbody style="background: #e4dbc6; font-weight: bold">

            </tbody>
          </table>
          <table id="metas_dp" class="table table-bordered border-2 col-lg-6 col-md-12 col-sm-12" style="width:100%">
            <thead style="background: #e4dbc6; border: 1px solid red; justify-content: center">
            <tr class="font-weight-bold">
              <th class="h6 font-weight-bold">Asesor</th>
              <th class="h6 font-weight-bold">Identificador</th>
              <th class="h6 font-weight-bold">Pedidos del día {{Str::upper(\Carbon\Carbon::now()->add(1,'day')->sub('1 day')->isoFormat('D - M'))}} </th>
              <th class="h6 font-weight-bold animated-progress text-uppercase">Cobranza
                {{Str::upper(\Carbon\Carbon::now()->subMonth()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                {{-- {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}
                <br>
                {{-- {{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}} --}}

              </th>
              <th class="h6 font-weight-bold text-uppercase">Pedidos
                {{Str::upper(\Carbon\Carbon::now()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                <br>
                {{-- {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}} --}}
              </th>
            </tr>
            </thead>
            <tbody style="background: #e4dbc6; font-weight: bold">
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div>
          <table id="total" class="table table-bordered border-2 col-lg-12 col-md-12 col-sm-12">
            <thead>
            <tr>
              <th>Asesores</th>
              <th>Identificadores</th>
              <th>Pedidos Totales del {{Str::upper(\Carbon\Carbon::now()->add(1,'day')->sub('1 day')->isoFormat('D - M'))}}  </th>
              <th>Cobranza total {{Str::upper(\Carbon\Carbon::now()->subMonth()->monthName)}} - {{\Carbon\Carbon::now()->year}} </th>
              <th>Pedidos totales
                {{Str::upper(\Carbon\Carbon::now()->monthName)}} - {{\Carbon\Carbon::now()->year}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      {{--FIN-TABLA-DUAL--}}

        {{-- TABLA TOTAL --}}
        <div class="">
            <table id="meta_duplicat_tot" class="table table-bordered border-2 col-lg-12 col-md-12 col-sm-12" style="width:100%">
              <thead style="background: #e4dbc6; border: 1px solid red; justify-content: center">
              <tr class="font-weight-bold">
                <th class="h6 font-weight-bold">Asesor</th>
                <th class="h6 font-weight-bold">Identificador</th>
                <th class="h6 font-weight-bold">Pedidos del día {{Str::upper(\Carbon\Carbon::now()->add(1,'day')->sub('1 day')->isoFormat('D - M'))}} </th>
                <th class="h6 font-weight-bold animated-progress text-uppercase">Cobranza
                  {{Str::upper(\Carbon\Carbon::now()->subMonth()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                  {{-- {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}
                  <br>
                  {{-- {{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}} --}}

                </th>
                <th class="h6 font-weight-bold text-uppercase">Pedidos
                  {{Str::upper(\Carbon\Carbon::now()->monthName)}} - {{\Carbon\Carbon::now()->year}}
                  <br>
                  {{-- {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}} --}}
                </th>
              </tr>
              </thead>
                <tbody style="background: #e4dbc6; font-weight: bold">

                </tbody>
            </table>
        </div >
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

        td:nth-child(3) {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #meta_duplicat_tot{
            flex:100%;
            display: none !important;
        }

        @media screen and (max-width:1486px){
            #metas_dp{
                display: none;
            }
            #metas{
                display: none;
            }
            #meta_duplicat_tot{
                display: initial !important;
            }
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
            dom: "",
            lengthMenu: [8,15],
            "order": [[0, "asc"]],
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
                {data: 'pedidos_dia', name: 'pedidos_dia'},

                {data: 'progress_pagos', name: 'progress_pagos'},
                {data: 'progress_pedidos', name: 'progress_pedidos'},

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
        let meta_duplicat =  null;
        meta_duplicat = $('#metas_dp').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            dom: "",
            lengthMenu: [7,15],
            "order": [[0, "des"]],
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
                {data: 'pedidos_dia', name: 'pedidos_dia',},

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
        let meta_duplicat_tot =  null;
        meta_duplicat = $('#meta_duplicat_tot').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            dom: "",
            lengthMenu: [15,20],
            "order": [[0, "des"]],
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
                {data: 'pedidos_dia', name: 'pedidos_dia',},

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
          // footerCallback: function (row, data, start, end, display) {
          //   var api = this.api();
          //   nb_cols = 5;
          //   api.columns().nodes().length;
          //   var j = 2;
          //
          //   //para footer  monto
          //   var pageTotal = api
          //     .column(5, {page: 'current'})
          //     .data()
          //     .reduce(function (a, b) {
          //       return Number(a) + Number(b);
          //     }, 0);
          //   // Update footer
          //   $(api.column(5).footer('<p>Evento prueba</p>'));
          //   $("#asesor");
          //   $("#identificador");
          //   $("#pedido_mes")
          //   $("#pedidos_dia");
          //   $("#cobranza_mes");
          //   $("#pedido_mes")
          //   let uncliente = $("#pcliente_id").val();
          //   //calcular
          //   diferenciaFaltante();
          // },


        });
        let meta_duplicat_tot_modal =  null;
        meta_duplicat = $('#meta_duplicat_tot_modal').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          dom: "",
          lengthMenu: [15,20],
          "order": [[0, "des"]],
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
            {data: 'pedidos_dia', name: 'pedidos_dia',},

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
          // footerCallback: function (row, data, start, end, display) {
          //   var api = this.api();
          //   nb_cols = 5;
          //   api.columns().nodes().length;
          //   var j = 2;
          //
          //   //para footer  monto
          //   var pageTotal = api
          //     .column(5, {page: 'current'})
          //     .data()
          //     .reduce(function (a, b) {
          //       return Number(a) + Number(b);
          //     }, 0);
          //   // Update footer
          //   $(api.column(5).footer('<p>Evento prueba</p>'));
          //   $("#asesor");
          //   $("#identificador");
          //   $("#pedido_mes")
          //   $("#pedidos_dia");
          //   $("#cobranza_mes");
          //   $("#pedido_mes")
          //   let uncliente = $("#pcliente_id").val();
          //   //calcular
          //   diferenciaFaltante();
          // },


        });
        let total = null
        total = $('#total').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          dom: "",
          lengthMenu: [8,15],
          "order": [[0, "asc"]],
          ajax: {
            url: "{{ route('dashboard.graficoMetaTable') }}",
          },
          columns: [
            {
              data: 'asesores',
              name: ' asesores',
            },
            {
              data: 'identificador'
              , name: 'identificador'
            },
            {data: 'pedidos_totales', name: 'pedidos_totales'},

            {data: 'progress_pagos', name: 'progress_pagos'},
            {data: 'progress_pedidos', name: 'progress_pedidos'},

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
        setInterval(function recargametasxmes() {
          $("#metas").DataTable().ajax.reload();
          $("#metas_dp").DataTable().ajax.reload();
          $("#meta_duplicat_tot").DataTable().ajax.reload();
          // $("#total").DataTable().ajax.reload();
        }, 3000);
    </script>

<script>

  $('a[href$="#myModal"]').on( "click", function() {
    $('#myModal').modal();
  });

</script>


@endsection
