<?php

namespace App\View\Components\dashboard\graficos;

use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class GraficoMetasDelMes extends Component
{
    public $novResult = [];
    public $dicResult = [];

    public $excludeNov = [];
    public $excludeDic = [];

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
        $now = now();
        $now_submonth =now()->startOfMonth()->subMonth();

        $data_noviembre = $this->generarDataNoviembre($now_submonth);

        $data_diciembre = $this->generarDataDiciembre();

        if (\auth()->user()->rol == User::ROL_ASESOR) {
            $this->novResult = [];
            $this->dicResult = [];
        }

        return view('components.dashboard.graficos.grafico-metas-del-mes', compact('data_noviembre', 'data_diciembre', 'now', 'now_submonth'));
    }

    public function applyFilter($query, CarbonInterface $date = null, $column = 'created_at')
    {
        if ($date == null) {
            $date = now();
        }
        return $query->whereBetween($column, [
            $date->clone()->startOfMonth(),
            $date->clone()->endOfMonth()->endOfDay()
        ]);
    }

    public function generarDataNoviembre($date)
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
                //->incluidoMeta()
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

            $metatotal = (float)$asesor->meta_pedido;
            $all = $this->applyFilter(Pedido::query()->where('user_id', $asesor->id)->activo(), $date, 'created_at')
                ->count();

            $pay = $this->applyFilter(Pedido::query()->where('user_id', $asesor->id)->activo()->pagados(), $date, 'created_at')
                ->count();

            $item = [
                "identificador" => $asesor->identificador,
                "code" => "Asesor {$asesor->identificador}",
                "name" => $asesor->name,
                "total" => $all,
                "current" => $pay,
                "meta" => $metatotal,
            ];
            if ($asesor->excluir_meta) {
                if ($all > 0) {
                    $p = round(($pay / $all) * 100,2);
                } else {
                    $p = 0;
                }
                $item['progress']=$p;
                $this->excludeNov[] = $item;
            } else {
                $progressData[] = $item;
            }
        }
        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    $newData[$identificador]['total'] += data_get($item, 'total');
                    $newData[$identificador]['current'] += data_get($item, 'current');
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total');
            $pay = data_get($item, 'current');
            if ($all > 0) {
                $p = round(($pay / $all) * 100,2);
            } else {
                $p = 0;
            }
            $item['progress'] = $p;
            return $item;
        })->sortBy('identificador')->all();

        $this->novResult = $progressData;

        $all = collect($progressData)->pluck('total')->sum();
        $pay = collect($progressData)->pluck('current')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        if ($all > 0) {
            $p = round(($pay / $all) * 100,2);
        } else {
            $p = 0;
        }
        return (object)[
            "progress" => $p,
            "total" => $all,
            "current" => $pay,
            "meta" => $meta,
        ];
    }

    public function generarDataDiciembre()
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
                //->incluidoMeta()
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

            $meta = (float)$asesor->meta_pedido;
            $asignados = $this->applyFilter(Pedido::query()->whereUserId($asesor->id)->activo())->count();
            //$pay = $this->applyFilter(Pedido::query())->whereUserId($asesor->id)->activo()->pagados()->count();

            $item =  [
                "identificador" => $asesor->identificador,
                "code" => "Asesor {$asesor->identificador}",
                "name" => $asesor->name,
                "meta" => $meta,
                "total" => $asignados,
                //"current" => $pay,
            ];
            if ($asesor->excluir_meta) {
                if ($meta > 0) {
                    $p = round(($asignados / $meta) * 100,2);
                } else {
                    $p = 0;
                }
                $item['progress']=$p;
                $this->excludeDic[] = $item;
            } else {
                $progressData[] = $item;
            }
        }

        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['total'] += data_get($item, 'total');
                    //$newData[$identificador]['current'] += data_get($item, 'current');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $dicResult = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'meta');
            $asignados = data_get($item, 'total');
            if ($all > 0) {
                $p = round(($asignados / $all) * 100,2);
            } else {
                $p = 0;
            }
            $item['progress'] = $p;
            return $item;
        })->sortBy('identificador')->all();

        $this->dicResult = $dicResult;

        $metaTotal = collect($dicResult)->pluck('meta')->sum();
        $asignados = collect($dicResult)->pluck('total')->sum();
        //$pagados = collect($dicResult)->pluck('current')->sum();
        if ($metaTotal > 0) {
            $p = intval(($asignados / $metaTotal) * 100);
        } else {
            $p = 0;
        }
        return (object)[
            "progress" => $p,
            "meta" => $metaTotal,
            "total" => $asignados,//$metaTotal,
            "current" => $asignados,
        ];
    }

}
