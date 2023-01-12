<?php

namespace App\Listeners;

use App\Events\PedidoAnulledEvent;
use App\Models\DireccionGrupo;
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
        if($event->pedido->direcciongrupo!=null) {
            $message = ($event->pedido->pendiente_anulacion ? 'Pendiente de anulacion: ' : 'Anulado: ');
            $grupo = DireccionGrupo::desvincularPedido($event->pedido->direcciongrupo, $event->pedido, $message . $event->pedido->motivo);
            $grupo->update([
                'estado' => 0
            ]);
        }
        return $grupo;
    }
}
