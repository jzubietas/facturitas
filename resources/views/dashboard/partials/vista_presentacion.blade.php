<div class="text-center mb-4" style="font-family:'Times New Roman', Times, serif">
    <h2>
        <p>
            Bienvenido <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, eres el
            <b>{{ Auth::user()->rol }} del sistema</b>
        </p>
    </h2>
</div>

<div class="row">

    <div class="col-lg-9 col-12">
        @include('dashboard.widgets.pedidos_creados')
    </div>
</div>


<div class="row">

    <div class="col-lg-12">
        <x-grafico-metas-mes></x-grafico-metas-mes>
    </div>

    <div class="container-fluid">
        
    </div>
    <div class="container-fluid">

    </div>

</div>
{{-- @include('dashboard.modal.alerta') --}}
