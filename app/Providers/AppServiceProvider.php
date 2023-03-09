<?php

namespace App\Providers;

use App\View\Components\common\ActivarClientePorTiempo;
use App\View\Components\common\BsProgressbar;
use App\View\Components\common\courier\AutorizarRutaMotorizado;
use App\View\Components\common\QRScanner;
use App\View\Components\dashboard\graficos\borras\PedidosPorDia;
use App\View\Components\dashboard\graficos\CobranzasMesesProgressBar;
use App\View\Components\dashboard\graficos\GraficoMetaCobranzas;
use App\View\Components\dashboard\graficos\GraficoMetasDelMes;
use App\View\Components\dashboard\graficos\GraficoPedidoCobranzasDelDia;
use App\View\Components\dashboard\graficos\GraficoPedidosAtendidoAnulados;
use App\View\Components\dashboard\graficos\GraficoPedidosMetaProgress;
use App\View\Components\dashboard\graficos\PedidosMesCountProgressBar;
use App\View\Components\dashboard\graficos\PedidosAsignadosProgressBar;
use App\View\Components\dashboard\graficos\QtyPedidoFisicoElectronicos;
use App\View\Components\dashboard\graficos\TopClientesPedidos;
use App\View\Components\dashboard\tablas\FisElecJefeOperaciones;
use App\View\Components\dashboard\tablas\ListaUsuariosLlamadasAtencion;
use App\View\Components\UnificacionCambioCalculoPorc;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
        Carbon::setLocale(config('app.locale'));

        Collection::macro('trim',function (){
            $data= $this->map(function ($item){
                if(is_string($item)){
                    return trim($item);
                }
               return  $item;
            })->filter(fn($item) => !!$item);
            return collect($data);
        });
        \Blade::component('grafico-pedidos-mes-count-progress-bar', PedidosMesCountProgressBar::class);
        \Blade::component('grafico-meta-pedidos-progress-bar', PedidosAsignadosProgressBar::class);
        \Blade::component('grafico-pedidos-por-dia', PedidosPorDia::class);
        \Blade::component('grafico-meta_cobranzas', GraficoMetaCobranzas::class);
        \Blade::component('grafico-pedidos-meta-progress', GraficoPedidosMetaProgress::class);
        \Blade::component('grafico-top-clientes-pedidos', TopClientesPedidos::class);
        \Blade::component('grafico-metas-mes', GraficoMetasDelMes::class);
        \Blade::component('bs-progressbar', BsProgressbar::class);
        \Blade::component('grafico-pedidos-elect-fisico', QtyPedidoFisicoElectronicos::class);
        \Blade::component('tabla-jef-operaciones-fis-elect', FisElecJefeOperaciones::class);
        \Blade::component('tabla-list-llamada-atencion', ListaUsuariosLlamadasAtencion::class);
        \Blade::component('frm-unif-cambio-calculo-porc', UnificacionCambioCalculoPorc::class);

        \Blade::component('grafico-pedidos-atendidos-anulados', GraficoPedidosAtendidoAnulados::class);

        \Blade::component('grafico-cobranzas-meses-progressbar', CobranzasMesesProgressBar::class);

        \Blade::component('grafico-pedido_cobranzas-del-dia', GraficoPedidoCobranzasDelDia::class);

        \Blade::component('common-autorizar-ruta-motorizado', AutorizarRutaMotorizado::class);
        \Blade::component('common-button-qr-scanner', QRScanner::class);

        \Blade::component('common-activar-cliente-por-tiempo', ActivarClientePorTiempo::class);

    }
}
