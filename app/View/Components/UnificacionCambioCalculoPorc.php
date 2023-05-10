<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UnificacionCambioCalculoPorc extends Component
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
        $resultados = $this->genListadoLlamadoAtencion();
        return view('components.unificacion-cambio-calculo-porc', compact('resultados'));
    }
    public function genListadoLlamadoAtencion()
    {
        $mirol = Auth::user()->rol;
        $users = User::where('cant_vidas_cero', '>', 0)
            ->select([
                'users.*',
                DB::raw("(CASE WHEN users.rol ='Asesor' THEN (select u.name  from users u where u.id=users.supervisor limit 1) ELSE
        (select us.name  from users us where us.id=users.jefe limit 1)
         END) AS nombre_jefe"),
            ]);
        if ($mirol == User::ROL_JEFE_LLAMADAS) {
            $users = $users->where('jefe', Auth::user()->id)->where("rol", User::ROL_LLAMADAS);
            $users = $users->orderBy('name', 'ASC')->get();
        } else if ($mirol == User::ROL_JEFE_OPERARIO) {
            $users = $users->where('jefe', Auth::user()->id)->where("rol", User::ROL_OPERARIO);
            $users = $users->orderBy('name', 'ASC')->get();
        } else if ($mirol == User::ROL_ENCARGADO) {
            $users = $users->where('supervisor', Auth::user()->id)->where("rol", User::ROL_ASESOR);
            $users = $users->orderBy('exidentificador', 'ASC')->get();
        }else{
            $users = $users->orderBy('name', 'ASC')->get();
        }
        /*foreach ($operarios as $user) {}*/
        return $users;
    }
}
