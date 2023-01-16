<?php

namespace App\Listeners;

use App\Events\PedidoAnulledEvent;
use App\Models\DireccionGrupo;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PedidoAnulledListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\PedidoAnulledEvent $event
     * @return DireccionGrupo
     */
    public function handle(PedidoAnulledEvent $event)
    {
        if ($event->pedido->direcciongrupo != null) {
            $message = ($event->pedido->pendiente_anulacion ? 'Pendiente de anulacion: ' : 'Anulado: ');
            $grupo = DireccionGrupo::desvincularPedido($event->pedido->direcciongrupo, $event->pedido, $message . $event->pedido->motivo);
            $grupo->update([
                'estado' => 0
            ]);

            if (in_array($event->pedido->condicion_envio_code, [Pedido::ENTREGADO_SIN_SOBRE_OPE_INT, Pedido::ENTREGADO_CLIENTE_INT])) {
                $grupo->update([
                    'motorizado_status' => 0,
                    'motorizado_sustento_text' => 'Anulado',
                ]);
            } else {
                if ($event->pedido->direcciongrupo->fecha_salida == null || $event->pedido->direcciongrupo->fecha_salida->startOfDay() != now()->startOfDay()) {
                    $grupo->update([
                        'motorizado_status' => 0,
                        'motorizado_sustento_text' => '',
                    ]);
                }
            }
            $ids = GrupoPedido::query()
                ->join('grupo_pedido_items', 'grupo_pedido_items.grupo_pedido_id', 'grupo_pedidos.id')
                ->where('grupo_pedido_items.pedido_id', $event->pedido->id)
                ->pluck('grupo_pedidos.id');
            if ($ids->count() > 0) {
                GrupoPedido::query()->whereIn('id', $ids)->delete();
            }
            return $grupo;
        } else {
            GrupoPedido::desvincularPedido($event->pedido);
        }
        return null;
    }
}
