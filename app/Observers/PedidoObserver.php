<?php

namespace App\Observers;

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

        //NO EXISTE --> PEDIDOS EN CERO CON ESTADO "1"

        //NUEVO --> tiene pedidos mayor a cero en un rango de 1 mes
        //--> si no registra en el mes siguiente pasa a RECURRENTE

        //ABANDONO
        //RECUPERADO ABANDONO

        //ABANDONO RECIENTE -> si
        //RECUPERADO ABANDONO RECIENTE

        //RECURRENTE
        //
        /*
                S_2022_12
        ABANDONO RECIENTE=RECUPERADO RECIENTE
        RECURRENTE=RECURRENTE
        RECUPERADO=RECURRENTE
        RECUPERADO ABANDONO=RECURRENTE
        ABANDONO PERMANENTE=RECUPERADO ABANDONO
        NO EXISTE=NUEVO
        NUEVO=RECURRENTE
        */
        if (now()->format("Y-m") == "2022-12") {
            $status = "";
            switch ($pedido->cliente->situacion) {
                case "ABANDONO RECIENTE":
                    $status = "RECUPERADO RECIENTE";
                    break;
                case "RECURRENTE":
                case "RECUPERADO":
                case "RECUPERADO ABANDONO":
                case "NUEVO":
                    $status = "RECURRENTE";
                    break;
                case "ABANDONO PERMANENTE":
                    $status = "RECUPERADO ABANDONO";
                case "NO EXISTE":
                    $status = "NUEVO";
                    break;
            }

            if (!empty($status)) {
                $pedido->cliente->update([
                    "situacion" => $status
                ]);
            }
        }
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
