<?php

namespace App\View\Components\common\courier;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\HtmlString;
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
        $zonemotorizados = [];
        $motorizadosAuthorizaciones = [];
        $reprogramados = [];
        if (auth()->user()->rol == User::ROL_JEFE_COURIER) {
            if (auth()->user()->rol == \App\Models\User::ROL_JEFE_COURIER) {
                $motorizados = User::query()->activo()->rol(User::ROL_MOTORIZADO)->get();
                $motorizadosAuthorizaciones = [];
                foreach ($motorizados as $motorizado) {
                    $zonemotorizados[$motorizado->id] = $motorizado->zona;
                    $count = count(DireccionGrupo::getSolicitudAuthorization($motorizado->id));
                    if ($count > 0) {
                        $motorizadosAuthorizaciones[$motorizado->id] = $count;
                    }
                }
            } else {
                $motorizadosAuthorizaciones = [];
            }
            $reprogramados = DireccionGrupo::query()->reprogramados()->activo()->get();
        }

        return view('components.common.courier.autorizar-ruta-motorizado', compact('motorizadosAuthorizaciones', 'zonemotorizados', 'reprogramados'));
    }
}
