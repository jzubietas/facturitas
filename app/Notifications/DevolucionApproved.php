<?php

namespace App\Notifications;

use App\Models\Devolucion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DevolucionApproved extends Notification
{
    use Queueable;

    /**
     * @var Devolucion
     */
    protected $devolucion;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($devolucion)
    {
        $this->devolucion=$devolucion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  Devolucion  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'devolucion_id'=>$this->devolucion->id,
            'user_id'=>auth()->id(),
            'user_name'=>auth()->user()->name,
            'asunto'=>'Pago devuelto por <b>'.auth()->user()->name.'</b> a <b>'.$this->devolucion->cliente->nombre.'</b> un valor de <b>'.$this->devolucion->amount_format.'</b>',
            'tipo'=>'Devoluciones',
            'condicion'=>$this->devolucion->estado_text,
        ];
    }
}
