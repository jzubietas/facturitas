<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class PedidosAsignadosProgressBar extends Widgets
{

    public $general = [];
    public $progressData = [];

    public $generalDataSupervisor = [];


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (auth()->user()->rol == User::ROL_ADMIN) {
            //$this->generalData();
            $this->asesores();
            $this->generalDataSupervisor['name'] = 'General';
            $this->generalDataSupervisor['enabled'] = true;
            $this->general = (object)$this->generalDataSupervisor;
        } else {
            $this->asesores();
            $this->generalDataSupervisor['enabled'] = true;
            $this->general = (object)$this->generalDataSupervisor;
        }
        $title = $this->getDateTitle();
        if (\auth()->user()->rol == User::ROL_ASESOR) {
            $this->progressData = [];
        }
        return view('components.dashboard.graficos.pedidos-asignados-progress-bar', compact('title'));
    }

    public function generalData()
    {
        if (Auth::user()->id == "33") {
            $pagoxmes_total = $this->applyFilter(Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')//CANTIDAD DE PEDIDOS DEL MES
            ->activo()
                ->join('users as u', 'pedidos.user_id', 'u.id')
                , 'dp.created_at')
                ->count('dp.id');
        } else {
            $pagoxmes_total = $this->applyFilter(Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')//CANTIDAD DE PEDIDOS DEL MES
            ->activo()
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->where('u.rol', "ASESOR")
                , 'dp.created_at')
                ->count('dp.id');
        }

        if ($pagoxmes_total > 0) {
            $progress = intval(($pagoxmes_total / 1600) * 100);
        } else {
            $progress = 0;
        }
        $this->general = (object)[
            "enabled" => true,
            "code" => '',
            "name" => 'General',
            "progress" => $progress,
            "activos" => 1600,
            "pagados" => $pagoxmes_total,
        ];
    }

    public function asesores()
    {
        if (auth()->user()->rol == User::ROL_LLAMADAS) {//HASTA MAÑANA
            $id = auth()->user()->id;
            $asesores = User::rolAsesor()->where('llamada', '=', $id)->get();
        } else {
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
        }

        $progressData = [];
        foreach ($asesores as $asesor) {
            if (auth()->user()->rol != User::ROL_ADMIN
                && auth()->user()->rol != User::ROL_JEFE_LLAMADAS//HASTA MAÑANA
                && auth()->user()->rol != User::ROL_LLAMADAS) {//HASTA MAÑANA
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

            $all = $this->applyFilter(Pedido::query()->where('user_id', $asesor->id)->activo())->count();
            $pay = $this->applyFilter(Pedido::query()->where('user_id', $asesor->id)->activo()->pagados())->count();

            $progressData[] = [
                "identificador" => $asesor->identificador,
                "code" => "Asesor {$asesor->identificador}",
                "name" => $asesor->name,
                "activos" => $all,
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
                    $newData[$identificador]['activos'] += data_get($item, 'activos');
                    $newData[$identificador]['pagados'] += data_get($item, 'pagados');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();//new HtmlString(collect($items)->pluck('name')->join(',<br> '));
        }
        $this->progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'activos');
            $pay = data_get($item, 'pagados');
            if ($all > 0) {
                $p = intval(($pay / $all) * 100);
            } else {
                $p = 0;
            }
            $item['progress'] = $p;
            return $item;
        })->sortBy('identificador')->all();


        $all = collect($this->progressData)->pluck('activos')->sum();
        $pay = collect($this->progressData)->pluck('pagados')->sum();
        if ($all > 0) {
            $p = intval(($pay / $all) * 100);
        } else {
            $p = 0;
        }
        $this->generalDataSupervisor = [
            "code" => '',
            "name" => auth()->user()->name,
            "progress" => $p,
            "activos" => $all,
            "pagados" => $pay,
        ];
    }

}
