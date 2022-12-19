<?php

namespace App\Providers;

use App\Models\Pedido;
use App\Observers\PedidoObserver;
use App\View\Components\dashboard\graficos\borras\PedidosPorDia;
use App\View\Components\dashboard\graficos\MetaProgressBar;
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
        Pedido::observe(PedidoObserver::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::component('grafico-metas-progress-bar',MetaProgressBar::class);
        \Blade::component('grafico-pedidos-por-dia',PedidosPorDia::class);
    }
}
