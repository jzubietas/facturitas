<?php

namespace App\Observers;

use App\Jobs\PostCreatePedido;
use App\Jobs\PostCreatePedidoClienteUltimoPedido;
use App\Jobs\PostUpdatePedido;
use App\Jobs\PostUpdateSituacion;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class PedidoObserver
{
    /**
     * Handle the Pedido "created" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function created(Pedido $pedido)
    {
        \Log::info("PostCreatePedido -> ".$pedido->cliente_id);
        PostUpdateSituacion::dispatchSync($pedido->cliente_id);
        PostCreatePedidoClienteUltimoPedido::dispatchSync($pedido->cliente_id);
    }

    /**
     * Handle the Pedido "updated" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function updated(Pedido $pedido)
    {
      /*\Log::info("PostCreatePedido -> ".$pedido->cliente_id);*/
        PostUpdateSituacion::dispatchSync($pedido->cliente_id);
    }

    /**
     * Handle the Pedido "deleted" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function deleted(Pedido $pedido)
    {
        PostUpdateSituacion::dispatchSync($pedido->cliente_id);
        //
    }

    /**
     * Handle the Pedido "restored" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function restored(Pedido $pedido)
    {
        PostUpdateSituacion::dispatchSync($pedido->cliente_id);
    }

    /**
     * Handle the Pedido "force deleted" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function forceDeleted(Pedido $pedido)
    {
        PostUpdateSituacion::dispatchSync($pedido->cliente_id);
    }
}
