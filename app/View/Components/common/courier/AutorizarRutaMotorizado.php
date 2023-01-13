<?php

namespace App\View\Components\common\courier;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\View\Component;

class AutorizarRutaMotorizado extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $grupos = DireccionGrupo::query()->activo()->where('motorizado_status', Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO)
            ->join('users', 'users.id', 'direccion_grupos.motorizado_id')
            ->select(['direccion_grupos.id', 'direccion_grupos.motorizado_id', 'users.zona'])
            ->get()
            ->groupBy('zona');
        return view('components.common.courier.autorizar-ruta-motorizado');
    }
}
