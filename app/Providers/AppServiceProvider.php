<?php

namespace App\Providers;

use App\View\Components\common\BsProgressbar;
use App\View\Components\dashboard\graficos\borras\PedidosPorDia;
use App\View\Components\dashboard\graficos\GraficoMetaCobranzas;
use App\View\Components\dashboard\graficos\GraficoMetasDelMes;
use App\View\Components\dashboard\graficos\GraficoPedidosMetaProgress;
use App\View\Components\dashboard\graficos\MetaProgressBar;
use App\View\Components\dashboard\graficos\PedidosAsignadosProgressBar;
use App\View\Components\dashboard\graficos\QtyPedidoFisicoElectronicos;
use App\View\Components\dashboard\graficos\TopClientesPedidos;
use App\View\Components\dashboard\tablas\FisElecJefeOperaciones;
use Carbon\Carbon;
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
        \Blade::component('bs-progressbar',BsProgressbar::class);
        \Blade::component('grafico-pedidos-elect-fisico',QtyPedidoFisicoElectronicos::class);
        \Blade::component('tabla-jef-operaciones-fis-elect',FisElecJefeOperaciones::class);

        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
    }
}
