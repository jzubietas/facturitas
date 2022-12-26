<?php

namespace App\Providers;

use App\View\Components\dashboard\graficos\borras\PedidosPorDia;
use App\View\Components\dashboard\graficos\GraficoMetaCobranzas;
use App\View\Components\dashboard\graficos\GraficoMetasDelMes;
use App\View\Components\dashboard\graficos\GraficoPedidosMetaProgress;
use App\View\Components\dashboard\graficos\MetaProgressBar;
use App\View\Components\dashboard\graficos\PedidosAsignadosProgressBar;
use App\View\Components\dashboard\graficos\TopClientesPedidos;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::component('grafico-metas-progress-bar',MetaProgressBar::class);
        \Blade::component('grafico-meta-pedidos-progress-bar',PedidosAsignadosProgressBar::class);
        \Blade::component('grafico-pedidos-por-dia',PedidosPorDia::class);
        \Blade::component('grafico-meta_cobranzas',GraficoMetaCobranzas::class);
        \Blade::component('grafico-pedidos-meta-progress',GraficoPedidosMetaProgress::class);
        \Blade::component('grafico-top-clientes-pedidos',TopClientesPedidos::class);
        \Blade::component('grafico-metas-mes',GraficoMetasDelMes::class);
    }
}
