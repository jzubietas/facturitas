<?php

namespace App\Observers;

use App\Jobs\PostCreatePedido;
use App\Jobs\PostUpdatePedido;
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
        PostCreatePedido::dispatchSync($pedido->cliente_id);
    }

    /**
     * Handle the Pedido "updated" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function updated(Pedido $pedido)
    {
        //
      \Log::info("PostCreatePedido -> ".$pedido->codigo);
      PostUpdatePedido::dispatchSync($pedido->codigo);
    }

    /**
     * Handle the Pedido "deleted" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function deleted(Pedido $pedido)
    {
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
        //
    }

    /**
     * Handle the Pedido "force deleted" event.
     *
     * @param \App\Models\Pedido $pedido
     * @return void
     */
    public function forceDeleted(Pedido $pedido)
    {
        //
    }
}
