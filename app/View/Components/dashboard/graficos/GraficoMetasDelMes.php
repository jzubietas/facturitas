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
        $progressData = [];
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

            $all = Pedido::query()->whereUserId($asesor->id)->activo()->count();
            $pay = Pedido::query()->whereUserId($asesor->id)->activo()->pagados()->count();

            $progressData[] = [
                "identificador" => $asesor->identificador,
                "total" => $all,
                "pagados" => $pay,
            ];

        }

        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    $newData[$identificador]['total'] += data_get($item, 'total');
                    $newData[$identificador]['pagados'] += data_get($item, 'pagados');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $this->progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total');
            $pay = data_get($item, 'pagados');
            if ($all > 0) {
                $p = intval(($pay / $all) * 100);
            } else {
                $p = 0;
            }
            $item['progress'] = $p;
            return $item;
        })->sortBy('identificador')->all();


        $all = collect($this->progressData)->pluck('total')->sum();
        $pay = collect($this->progressData)->pluck('pagados')->sum();
        if ($all > 0) {
            $p = intval(($pay / $all) * 100);
        } else {
            $p = 0;
        }
        return (object)[
            "progress" => $p,
            "total" => $all,
            "current" => $pay,
        ];
    }

    public function generalData()
    {
        if (Auth::user()->id == "33") {
            $pagoxmes_total = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')//CANTIDAD DE PEDIDOS DEL MES
            ->activo()
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->whereBetween('dp.created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count('dp.id');
        } else {
            $pagoxmes_total = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')//CANTIDAD DE PEDIDOS DEL MES
            ->activo()
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->where('u.rol', "ASESOR")
                ->whereBetween('dp.created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count('dp.id');
        }
        if ($pagoxmes_total > 0) {
            $progress = intval(($pagoxmes_total / 1600) * 100);
        } else {
            $progress = 0;
        }
        return (object)[
            "progress" => $progress,
            "current" => $pagoxmes_total,
            "total" => 1600,
        ];
    }

}
