<?php

namespace App\Observers;

use App\Jobs\PostUpdateSituacion;
use App\Models\Cliente;
use App\Models\ListadoResultado;

class ClienteObserver
{
    /**
     * Handle the Cliente "created" event.
     *
     * @param \App\Models\Cliente $cliente
     * @return void
     */
    public function created(Cliente $cliente)
    {
        PostUpdateSituacion::dispatchSync($cliente->id);
    }

    /**
     * Handle the Cliente "updated" event.
     *
     * @param \App\Models\Cliente $cliente
     * @return void
     */
    public function updated(Cliente $cliente)
    {
        PostUpdateSituacion::dispatchSync($cliente->id);
    }

    /**
     * Handle the Cliente "deleted" event.
     *
     * @param \App\Models\Cliente $cliente
     * @return void
     */
    public function deleted(Cliente $cliente)
    {
        //
    }

    /**
     * Handle the Cliente "restored" event.
     *
     * @param \App\Models\Cliente $cliente
     * @return void
     */
    public function restored(Cliente $cliente)
    {
        //
    }

    /**
     * Handle the Cliente "force deleted" event.
     *
     * @param \App\Models\Cliente $cliente
     * @return void
     */
    public function forceDeleted(Cliente $cliente)
    {
        //
    }
}
