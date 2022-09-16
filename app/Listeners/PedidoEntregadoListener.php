<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\PedidoAtendidoNotification;
use App\Notifications\PedidoEntregadoNotification;
use App\Notifications\PedidoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PedidoEntregadoListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        User::all()
            ->whereIn('rol', ['Asesor'])
            ->except($event->pedido->user_id)
            ->each(function (User $user) use ($event){
                Notification::send($user, new PedidoEntregadoNotification($event->pedido));
                
            });
    }
}
