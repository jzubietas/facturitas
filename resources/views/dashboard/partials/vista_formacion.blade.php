<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, donde
            cumples la función de <b>{{ Auth::user()->rol }}</b></p>
    </h2>
</div>
<br>
<br>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            @include('dashboard.widgets.pedidos_creados')
        </div>
        <div class="col-12">
            @include('dashboard.widgets.buscar_cliente')
        </div>
    </div>
</div>
