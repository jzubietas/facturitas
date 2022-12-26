<?php

namespace App\View\Components\dashboard\graficos;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class GraficoMetasDelMes extends Component
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
        $data_noviembre = $this->generalDataAsignados();
        $data_diciembre = $this->generalData();
        return view('components.dashboard.graficos.grafico-metas-del-mes', compact('data_noviembre', 'data_diciembre'));
    }

    public function generalDataAsignados()
    {
        $encargado = null;
        if (auth()->user()->rol == User::ROL_ENCARGADO) {
            $encargado = auth()->user()->id;
        }
        $asesores = User::query()
            ->activo()
            ->rolAsesor()
            ->when($encargado != null, function ($query) use ($encargado) {
                return $query->where('supervisor', '=', $encargado);
            })
            ->get();
        $metatotal = 0;
        $users = [];
        foreach ($asesores as $asesor) {
            if (auth()->user()->rol != User::ROL_ADMIN) {
                if (auth()->user()->rol != User::ROL_ENCARGADO) {
                    if (auth()->user()->id != $asesor->id) {
                        continue;
                    }
                } else {
                    if (auth()->user()->id != $asesor->supervisor) {
                        continue;
                    }
                }
            }
            $users[] = $asesor->id;
            $metatotal += (float)$asesor->meta_pedido;
        }

        $all = Pedido::query()->whereIn('user_id', $users)
            ->activo()
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $pay = Pedido::query()->whereIn('user_id', $users)->activo()->pagados()
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        if ($all > 0) {
            $p = intval(($pay / $all) * 100);
        } else {
            $p = 0;
        }
        return (object)[
            "total" => $all,
            "current" => $pay,
            "meta" => $metatotal,
            "progress" => $p,
        ];

    }

    public function generalData()
    {
        $encargado = null;
        if (auth()->user()->rol == User::ROL_ENCARGADO) {
            $encargado = auth()->user()->id;
        }
        $asesores = User::query()
            ->activo()
            ->rolAsesor()
            ->when($encargado != null, function ($query) use ($encargado) {
                return $query->where('supervisor', '=', $encargado);
            })
            ->get();
        $metatotal = 0;
        $users = [];
        foreach ($asesores as $asesor) {
            if (auth()->user()->rol != User::ROL_ADMIN) {
                if (auth()->user()->rol != User::ROL_ENCARGADO) {
                    if (auth()->user()->id != $asesor->id) {
                        continue;
                    }
                } else {
                    if (auth()->user()->id != $asesor->supervisor) {
                        continue;
                    }
                }
            }
            $users[] = $asesor->id;
            $metatotal += (float)$asesor->meta_pedido;
        }

        $all = Pedido::query()
            ->whereIn('user_id', $users)
            ->activo()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $t=1600;
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $t=$metatotal;
        }
        if ($t > 0) {
            $p = intval(($all / $t) * 100);
        } else {
            $p = 0;
        }
        return (object)[
            "total" => $t,
            "current" => $all,
            "meta" => $metatotal,
            "progress" => $p,
        ];

    }


}
