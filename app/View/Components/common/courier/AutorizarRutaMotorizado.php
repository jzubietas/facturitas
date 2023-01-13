<?php

namespace App\View\Components\common\courier;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
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
        $zonemotorizados=[];
        if (auth()->user()->rol == \App\Models\User::ROL_JEFE_COURIER) {
            $motorizados = User::query()->activo()->rol(User::ROL_MOTORIZADO)->get();
            $motorizadosAuthorizaciones = [];
            foreach ($motorizados as $motorizado) {
                $zonemotorizados[$motorizado->id] = $motorizado->zona;
                if (!isset($motorizadosAuthorizaciones[$motorizado->id])) {
                    $motorizadosAuthorizaciones[$motorizado->id] = 0;
                }
                $motorizadosAuthorizaciones[$motorizado->id] += count(DireccionGrupo::getNoRecibidoAuthorization($motorizado->id));
            }

            foreach ($motorizadosAuthorizaciones as $zona => $cantidad) {
                if ($cantidad == 0) {
                    unset($motorizadosAuthorizaciones[$zona]);
                }
            }
        } else {
            $motorizadosAuthorizaciones = [];
        }
        return view('components.common.courier.autorizar-ruta-motorizado', compact('motorizadosAuthorizaciones','zonemotorizados'));
    }
}
