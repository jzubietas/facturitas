<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class PedidosMesCountProgressBar extends Widgets
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
        $title = $this->getDateTitle();
        if (\auth()->user()->rol == User::ROL_ASESOR) {
            $this->progressData = [];
        }
        return view('components.dashboard.graficos.meta-progress-bar', compact('title'));
    }

    public function generalData()
    {
         $pagoxmes_total = $this->applyFilter(
                Pedido::activo()->whereIn('user_id',
                    User::query()->select('users.id')->rolAsesor()->whereNotIn('users.id',[51])
                )->where('pedidos.codigo', 'not like', "%-C%")
                , 'pedidos.created_at'
            )->count();

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
            "meta" => 2000,
            "pagados" => $pagoxmes_total,
            "asignados" => $pagoxmes_total,
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
            ->rolAllAsesor()
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

            if ($asesor->rol!= User::ROL_ASESOR_ADMINISTRATIVO){
              $meta = (float)$asesor->meta_pedido;
            }else{
              $meta=100;
            }

            $asignados = $this->applyFilter(Pedido::query()->whereUserId($asesor->id)->activo()->where('pedidos.codigo', 'not like', "%-C%"))->count();
            $pay = $this->applyFilter(Pedido::query()->whereUserId($asesor->id)->activo()->pagados()->where('pedidos.codigo', 'not like', "%-C%"))->count();

            $progressData[] = [
                "identificador" => $asesor->identificador,
                "code" => "Asesor {$asesor->identificador}",
                "name" => $asesor->name,
                "meta" => $meta,
                "asignados" => $asignados,
                "pagados" => $pay,
                "rol" => $asesor->rol,
            ];

        }

        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['asignados'] += data_get($item, 'asignados');
                    $newData[$identificador]['pagados'] += data_get($item, 'pagados');
                    $newData[$identificador]['rol'] += data_get($item, 'rol');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();//new HtmlString(collect($items)->pluck('name')->join(',<br> '));
        }
        $this->progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'meta');
            $asignados = data_get($item, 'asignados');
          if (data_get($item, 'rol')!= User::ROL_ASESOR_ADMINISTRATIVO){
            if ($all > 0) {
              $p = intval(($asignados / $all) * 100);
            } else {
              $p = 0;
            }
          }else{
            $p = intval(($asignados / 100) * 100);
          }

            $item['progress'] = $p;
            return $item;
        })->sortBy('identificador')->all();


        $metaTotal = collect($this->progressData)->pluck('meta')->sum();
        $asignados = collect($this->progressData)->pluck('asignados')->sum();
        $pagados = collect($this->progressData)->pluck('pagados')->sum();
        if (data_get($item, 'rol')!= User::ROL_ASESOR_ADMINISTRATIVO) {
          if ($metaTotal > 0) {
            $p = intval(($asignados / $metaTotal) * 100);
          } else {
            $p = 0;
          }
        }else{
          $p = intval(($asignados / 100) * 100);
        }
        $this->generalDataSupervisor = [
            "code" => '',
            "name" => auth()->user()->name,
            "progress" => $p,
            "meta" => $metaTotal,
            "asignados" => $asignados,
            "pagados" => $pagados,
        ];
    }
}
