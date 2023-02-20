<style>
    .bg-celeste{
        background-color:#9ff1ed !important;"
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="@if(count($excludeNov)>0)col-md-12 @else col-md-12 @endif">
                <div class="@if(count($excludeNov)==0)  justify-content-center @endif">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="text-center"><b>METAS DEL MES</b></h4>
                        </li>
                        <li class="list-group-item">
                            <ul class="list-group">
                                <li class="list-group-item" style=" min-width: 300px; ">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-6"><h5 class="text-center">COBRANZAS {{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}</h5></div>
                                        <div class="col-4"><h5 class="text-center">PEDIDOS {{Str::upper($now->monthName)}} - {{$now->year}}</h5></div>
                                    </div>
                                </li>
                                <li class="list-group-item" style=" background-color: #b7b7b7; ">
                                    <div class="row">
                                        <div class="col-2">Asesor</div>
                                        <div class="col-1">Identificador</div>
                                        <div class="col-1">Pedidos del dia {{  \Carbon\Carbon::now()->format('d-m') }}</div>
                                        <div class="col-4">

                                                <x-bs-progressbar :progress="$data_noviembre->progress_pagos">
                                                    <span> <b>  {{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}}</span>
                                                </x-bs-progressbar>

                                            <sub class="d-none">% -  Pagados/ Asignados</sub>
                                        </div>

                                        <div class="col-4">
                                            @if ($data_noviembre->progress_pedidos<'100')
                                                <x-bs-progressbar :progress="$data_noviembre->progress_pedidos">
                                                    <span> <b>  {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}}</span>
                                                </x-bs-progressbar>
                                            @else
                                                <div class="position-relative">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                             style="width: {{$data_noviembre->progress_pedidos}}%"
                                                             aria-valuenow="{{$data_noviembre->progress_pedidos}}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
                                                        <span> <b>  {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}}</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <sub class="d-none">% -  Pagados/ Asignados</sub>
                                        </div>

                                    </div>
                                </li>
                                @foreach($novResult as $key=>$data)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-2">
                                                {{data_get($data,'name')}}
                                            </div>
                                            <div class="col-1">
                                                <b>{{data_get($data,'code')}}</b>
                                            </div>
                                            <div class="col-1 text-center">
                                                @if (data_get($data,'pedidos_dia')==0)
                                                    <span class="text-white d-block bg-danger rounded">
                                                    <b>{{data_get($data,'pedidos_dia')}}</b>
                                                </span>
                                                @else
                                                    <span class="text-dark  d-block bg-white rounded">
                                                    <b>{{data_get($data,'pedidos_dia')}}</b>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-4">

                                                    <x-bs-progressbar :progress="$novResult[$key]['progress_pagos']">
                                                        <span><b>{{$novResult[$key]['progress_pagos']}}% </b> - {{$novResult[$key]['total_pagado']}}/{{$novResult[$key]['total_pedido_mespasado']}}</span>

                                                    </x-bs-progressbar>

                                                <sub class="d-none">% -  Pagados/ Asignados</sub>
                                            </div>
                                            <div class="col-4">
                                                @if ($novResult[$key]['progress_pedidos']<100)
                                                    <x-bs-progressbar :progress="$novResult[$key]['progress_pedidos']">
                                                        <span><b>{{$novResult[$key]['progress_pedidos']}}% </b> - {{$novResult[$key]['total_pedido']}}/{{$novResult[$key]['meta']}}</span>

                                                    </x-bs-progressbar>
                                                @else
                                                    <div class="position-relative">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-celeste"  role="progressbar"
                                                                 style="width: {{$novResult[$key]['progress_pedidos']}}%"
                                                                 aria-valuenow="{{$novResult[$key]['progress_pedidos']}}"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
                                                            <span><b>{{$novResult[$key]['progress_pedidos']}}%</b> - {{$novResult[$key]['total_pedido']}}/{{$novResult[$key]['meta_2']}}</span>
                                                        </div>
                                                    </div>
                                                @endif


                                                <sub class="d-none">% - Asignados / Meta</sub>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="list-group-item" style=" min-width: 300px; ">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-1"></div>
                                        <div class="col-1 text-center">
                                            @if ($data_noviembre->pedidos_dia==0)
                                                <span class="text-white d-block bg-danger rounded">
                                                    <b>{{$data_noviembre->pedidos_dia}}</b>
                                                </span>
                                            @else
                                                <span class="text-dark  d-block bg-white rounded">
                                                    <b>{{$data_noviembre->pedidos_dia}}</b>
                                                </span>
                                            @endif

                                        </div>
                                        <div class="col-4"></div>
                                        <div class="col-4"></div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>


    <div class="">
        <div class="@if(count($excludeNov)==0)  justify-content-center @endif">
            <h1 class="text-uppercase justify-center text-center">Metas del mes</h1>
    <table id="metas" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Asesor</th>
                <th>Identificador</th>
                <th>Pedidos</th>
                <th class="animated-progress">{{Str::upper($now_submonth->monthName)}} - {{$now_submonth->year}}
                <br>
                <span data-progress={{$data_noviembre->progress_pagos}}%</b> - {{$data_noviembre->total_pagado}}/{{$data_noviembre->total_pedido_mespasado}}> </span>

            </th>
                <th> {{Str::upper($now->monthName)}} - {{$now->year}}
                <br>
                {{$data_noviembre->progress_pedidos}}%</b> - {{$data_noviembre->total_pedido}}/{{$data_noviembre->meta}}
                </th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    </div>
</div>


</div>

@push('css')
    <style>
        .list-group .list-group-item {
            background: #a5770f1a;
        }

        .animated-progress {
  /* width: 300px;
  height: 30px; */
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

    </style>
@endpush

{{--@section('css-datatables')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
@endsection--}}

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
  var meta = null
    meta = $('#metas').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                dom: "",
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

                    {data: 'progress_pagos', name: 'progress_pagos',},
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
// function refresh() {
//     setTimeout(function () {
//         meta.DataTable().ajax.reload()
//     }, 1000);
// }
</script>

@endsection
