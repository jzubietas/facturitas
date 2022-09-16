<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\PedidoAtendidoNotification;
use App\Notifications\PedidoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PedidoAtendidoListener
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
            ->whereIn('rol', ['Asesor', 'Encargado'])
            ->except($event->pedido->user_id)
            ->each(function (User $user) use ($event){
                Notification::send($user, new PedidoAtendidoNotification($event->pedido));
                
            });
    }
}
