<?php

namespace App\Observers;

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
        ListadoResultado::query()->create([
            'id' => $cliente->id,
            'a_2021_11' => 0,
            's_2021_11' => 'BASE FRIA',
            'a_2021_12' => 0,
            's_2021_12' => 'BASE FRIA',
            'a_2022_01' => 0,
            's_2022_01' => 'BASE FRIA',
            'a_2022_02' => 0,
            's_2022_02' => 'BASE FRIA',
            'a_2022_03' => 0,
            's_2022_03' => 'BASE FRIA',
            'a_2022_04' => 0,
            's_2022_04' => 'BASE FRIA',
            'a_2022_05' => 0,
            's_2022_05' => 'BASE FRIA',
            'a_2022_06' => 0,
            's_2022_06' => 'BASE FRIA',
            'a_2022_07' => 0,
            's_2022_07' => 'BASE FRIA',
            'a_2022_08' => 0,
            's_2022_08' => 'BASE FRIA',
            'a_2022_09' => 0,
            's_2022_09' => 'BASE FRIA',
            'a_2022_10' => 0,
            's_2022_10' => 'BASE FRIA',
            'a_2022_11' => 0,
            's_2022_11' => 'BASE FRIA',
            'a_2022_12' => 0,
            's_2022_12' => 'BASE FRIA',
            'a_2023_01' => 0,
            's_2023_01' => 'BASE FRIA',
            'a_2023_02' => 0,
            's_2023_02' => 'BASE FRIA',
            'a_2023_03' => 0,
            's_2023_03' => 'BASE FRIA',
        ]);
    }

    /**
     * Handle the Cliente "updated" event.
     *
     * @param \App\Models\Cliente $cliente
     * @return void
     */
    public function updated(Cliente $cliente)
    {
        //
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
