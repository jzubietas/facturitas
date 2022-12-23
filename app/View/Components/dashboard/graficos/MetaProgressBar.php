<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\HtmlString;

class MetaProgressBar extends Widgets
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
            $this->generalData();
            $this->asesores();
        } else {
            $this->asesores();
            $this->generalDataSupervisor['enabled'] = true;
            $this->general = (object)$this->generalDataSupervisor;
        }
        $title=$this->getDateTitle();
        return view('components.dashboard.graficos.meta-progress-bar',compact('title'));
    }

    public function generalData()
    {
        $pedidtosActivos = $this->applyFilter(Pedido::query())->activo()->count();
        $pedidtosPagados = $this->applyFilter(Pedido::query())->activo()->pagados()->count();
        if ($pedidtosActivos > 0) {
            $progress = intval(($pedidtosPagados / $pedidtosActivos) * 100);
        } else {
            $progress = 0;
        }
        $this->general = (object)[
            "enabled" => true,
            "code" => '',
            "name" => 'General',
            "progress" => $progress,
            "activos" => $pedidtosActivos,
            "pagados" => $pedidtosPagados,
        ];
    }

    public function asesores()
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

            $all = $this->applyFilter(Pedido::query())->whereUserId($asesor->id)->activo()->count();
            $pay = $this->applyFilter(Pedido::query())->whereUserId($asesor->id)->activo()->pagados()->count();

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
                    $newData[$identificador]['activos']+= data_get($item,'activos');
                    $newData[$identificador]['pagados']+= data_get($item,'pagados');
                }
            }
            $newData[$identificador]['name']=new HtmlString(collect($items)->pluck('name')->join(',<br> '));
        }
        $this->progressData = collect($newData)->values()->map(function ($item){
            $all = data_get($item,'activos');
            $pay = data_get($item,'pagados');
            if ($all > 0) {
                $p = intval(($pay / $all) * 100);
            } else {
                $p = 0;
            }
            $item['progress']=$p;
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
