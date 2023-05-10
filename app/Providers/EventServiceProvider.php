<?php

namespace App\Providers;

use App\Events\PagoEvent;
use App\Events\PedidoAnulledEvent;
use App\Events\PedidoAtendidoEvent;
use App\Events\PedidoEntregadoEvent;
use App\Events\PedidoEvent;
use App\Listeners\PagoListener;
use App\Listeners\PedidoAnulledListener;
use App\Listeners\PedidoAtendidoListener;
use App\Listeners\PedidoEntregadoListener;
use App\Listeners\PedidoListener;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Observers\ClienteObserver;
use App\Observers\PedidoObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        /*PedidoEvent::class => [
            PedidoListener::class,
        ],
        PedidoAtendidoEvent::class => [
            PedidoAtendidoListener::class,
        ],
        PedidoEntregadoEvent::class => [
            PedidoEntregadoListener::class,
        ],*/
        PagoEvent::class => [
            PagoListener::class,
        ],
        PedidoAnulledEvent::class => [
            PedidoAnulledListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Pedido::observe(PedidoObserver::class);
        Cliente::observe(ClienteObserver::class);
    }
}
