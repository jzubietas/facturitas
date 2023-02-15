<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class GraficoMetasDelMes extends Widgets
{
    public $novResult = [];
    public $dicResult = [];

    public $excludeNov = [];
    public $excludeDic = [];
    public $metas = [];


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->startDate = now();
        //$data_diciembre = $this->generarDataDiciembre();

        $now_submonth = $this->startDate->clone()->startOfMonth()->subMonth();
        $data_noviembre = $this->generarDataNoviembre($this->startDate, $now_submonth);


        if (\auth()->user()->rol == User::ROL_ASESOR) {
            $this->novResult = [];
            //$this->dicResult = [];
        }
        $now = $this->startDate->clone();

        return view('components.dashboard.graficos.grafico-metas-del-mes', compact('data_noviembre',  'now', 'now_submonth'));
    }

    public function applyFilterCustom($query, CarbonInterface $date = null, $column = 'created_at')
    {
        if ($date == null) {
            $date = $this->startDate->clone();
        }
        return $query->whereBetween($column, [
            $date->clone()->startOfMonth(),
            $date->clone()->endOfMonth()->endOfDay()
        ]);
    }

    public function generarDataNoviembre($date, $date_pagos)
    {
        if (auth()->user()->rol == User::ROL_LLAMADAS) {
            //$asesores = [];
            //$asesores = User::rolAsesor()->where('llamada', '=', auth()->user()->id)->get();
            $asesores = User::query()
                ->activo()
                ->rolAsesor()->get();
        } else if (auth()->user()->rol == User::ROL_FORMACION)
        {
            $asesores = User::query()
                ->activo()
                ->rolAsesor()->get();
        }else if (auth()->user()->rol == User::ROL_PRESENTACION)
        {
            $asesores = User::query()
                ->activo()
                ->rolAsesor()->get();
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
        //dd($asesores);
        /*return (object)[
            'usuarios'=>$asesores
        ];*/
        foreach ($asesores as $asesor) {
            if(in_array(auth()->user()->rol,[User::ROL_FORMACION,User::ROL_ADMIN,User::ROL_PRESENTACION,User::ROL_JEFE_LLAMADAS,User::ROL_LLAMADAS]))
            {

            }else{
                if (auth()->user()->rol != User::ROL_ADMIN /*|| auth()->user()->rol!=User::ROL_FORMACION*/){
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
            }

            $asesor_pedido_dia=Pedido::query()->join('users as u','u.id','pedidos.user_id')->where('u.identificador',$asesor->identificador)
                ->where('pedidos.codigo','not like',"%-C%")->whereDate('pedidos.created_at',now())->count();
            $metatotal = (float)$asesor->meta_pedido;
            $metatotal_2 = (float)$asesor->meta_pedido_2;
            $metatotal_cobro = (float)$asesor->meta_cobro;
            $total_pedido = $this->applyFilterCustom(Pedido::query()->where('user_id', $asesor->id)
                ->where('codigo', 'not like', "%-C%")->activo(), $date, 'created_at')
                ->count();

            $total_pedido_mespasado = $this->applyFilterCustom(Pedido::query()->where('user_id', $asesor->id)
                ->where('codigo', 'not like', "%-C%")->activo(), $date_pagos, 'created_at')
                ->count();

            $total_pagado = $this->applyFilterCustom(Pedido::query()->where('user_id', $asesor->id)
                ->where('codigo', 'not like', "%-C%")->activo()->pagados(), $date_pagos, 'created_at')
                ->count();

            $item = [
                "identificador" => $asesor->identificador,
                "code" => "Asesor {$asesor->identificador}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta" => $metatotal,
                "meta_2" => $metatotal_2,
                "meta_cobro" => $metatotal_cobro,
            ];
            if ($asesor->excluir_meta) {
                if ($metatotal_cobro > 0) {
                    $p_pagos = round(($total_pedido_mespasado / $total_pagado) * 100, 2);
                } else {
                    $p_pagos = 0;
                }

                if ($metatotal > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }

                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
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
                    $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                    $newData[$identificador]['total_pedido_pasado'] += data_get($item, 'total_pedido_mespasado');
                    $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                    $newData[$identificador]['meta_cobro'] += data_get($item, 'meta_cobro');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total_pedido');
            $all_mespasado = data_get($item, 'total_pedido_mespasado');
            $pay = data_get($item, 'total_pagado');
            $allmeta = data_get($item, 'meta');
            $allmeta_2 = data_get($item, 'meta_2');
            $allmeta_cobro = data_get($item, 'meta_cobro');

            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }

            if ($allmeta > 0) {
                $p_pedidos = round(($all / $allmeta) * 100, 2);
            } else {
                $p_pedidos = 0;
            }

            $item['progress_pagos'] = $p_pagos;
            $item['progress_pedidos'] = $p_pedidos;
            return $item;
        })->sortBy('progress_pedidos', SORT_NUMERIC, true)->all();

        $this->novResult = $progressData;

        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $meta_cobro = collect($progressData)->pluck('meta_cobro')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        if ($meta > 0) {
            $p_pedidos = round(($all / $meta) * 100, 2);
        } else {
            $p_pedidos = 0;
        }

        if ($pay > 0) {
            $p_pagos = round(($pay / $all_mespasado) * 100, 2);
        } else {
            $p_pagos = 0;
        }

        $object = (object)[
            "progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "meta_cobro" => $meta_cobro,
            "pedidos_dia" => $pedidos_dia
        ];

        return $object;
    }
}
