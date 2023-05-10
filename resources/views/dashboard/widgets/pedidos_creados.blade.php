<div class="small-box bg-secondary">
    <div class="inner">
       <div class="d-flex align-items-center">
           <h5> PEDIDOS CREADOS {{  \Carbon\Carbon::now()->format('d-m-Y') }} </h5>
           <h3 class="ml-4">
               {{--}}<span class="badge badge-{{$_pedidos_totalpedidosdia==0?'danger':'light'}}">TOTAL: {{$_pedidos_totalpedidosdia}}</span></h3>}}{{--}}
       </div>
    </div>
    <div class="row">
        @foreach ($_pedidos as $identificador=>$total)
            <div class="col-md-2 col-6">
                <div class="p-4 border-top border-bottom">
                    <h5 class="text-center {{$total==0?'text-danger':''}}">ASESOR {{ $identificador }}</h5>
                    <h5 class="text-center {{$total==0?'text-danger':''}}"><b>{{ $total }}</b></h5>
                </div>
            </div>
        @endforeach
    </div>

</div>
