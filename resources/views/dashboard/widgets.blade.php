<div class="col-md-12">
    <x-dashboard.graficos.meta-progress-bar></x-dashboard.graficos.meta-progress-bar>
</div>
<div class="col-md-12">
    <x-dashboard.graficos.borras.pedidos-por-dia :rol="auth()->user()->rol"
                                                 title="Cantidad de pedidos de los asesores por dia"
                                                 label-x="Asesores"
                                                 label-y="Cant. Pedidos"
                                                 only-day
    ></x-dashboard.graficos.borras.pedidos-por-dia>
    <x-dashboard.graficos.borras.pedidos-por-dia :rol="auth()->user()->rol"
                                                 title="Cantidad de pedidos de los asesores por mes"
                                                 label-x="Asesores"
                                                 label-y="Cant. Pedidos"></x-dashboard.graficos.borras.pedidos-por-dia>
</div>

