<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class PublicidadCalendarioAdd extends Component
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
        //cargar asistente publicicad
        $publicidad=User::query()->where('rol',User::ROL_ASISTENTE_PUBLICIDAD)->activo()
            ->whereIn('id',[72,95])
            ->pluck('name','id');

        return view('components.publicidad-calendario-add',compact('publicidad'));
    }
}
