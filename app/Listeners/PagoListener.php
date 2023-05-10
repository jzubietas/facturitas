<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\PagoNotification;
use App\Notifications\PedidoAtendidoNotification;
use App\Notifications\PedidoEntregadoNotification;
use App\Notifications\PedidoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PagoListener
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
            ->whereIn('rol', ['Administracion', 'Encargado'])
            ->except($event->pago->user_id)
            ->each(function (User $user) use ($event){
                Notification::send($user, new PagoNotification($event->pago));
            });
    }
}
