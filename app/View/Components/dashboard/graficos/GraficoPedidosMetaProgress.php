<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class GraficoPedidosMetaProgress extends Widgets
{
    public $progressData;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->asesores();
        $jsChart = $this->generateConfigChart();
        return view('components.dashboard.graficos.grafico-pedidos-meta-progress', compact('jsChart'));
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

            $meta = (float)$asesor->meta_pedido;
            $asignados = $this->applyFilter(Pedido::query())->whereUserId($asesor->id)->activo()->count();
            $pay = $this->applyFilter(Pedido::query())->whereUserId($asesor->id)->activo()->pagados()->count();

            $progressData[] = [
                "identificador" => $asesor->identificador,
                "code" => "Asesor {$asesor->identificador}",
                "name" => $asesor->name,
                "meta" => $meta,
                "asignados" => $asignados,
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
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['pagados'] += data_get($item, 'pagados');
                    $newData[$identificador]['asignados'] += data_get($item, 'asignados');
                }
            }
            $newData[$identificador]['name'] = new HtmlString(collect($items)->pluck('name')->join(',<br> '));
        }
        $this->progressData = collect($newData)->values()->map(function ($item) {
            $meta = data_get($item, 'meta');
            $pagados = data_get($item, 'pagados');
            $asignados = data_get($item, 'asignados');

            if ($meta > 0) {
                $p1 = intval(($asignados / $meta) * 100);
            } else {
                $p1 = 0;
            }
            if ($asignados > 0) {
                $p2 = intval(($pagados / $asignados) * 100);
            } else {
                $p2 = 0;
            }
            data_set($item, 'progress', $p1);
            data_set($item, 'progress_meta', $p2);
            return $item;
        })->sortBy('identificador')->all();
    }

    public function generateConfigChart()
    {
        $seriaA = [];
        $seriaB = [];
        $asesores = [];
        foreach ($this->progressData as $item) {
            $seriaA[] = data_get($item, 'progress');
            $seriaB[] = data_get($item, 'progress_meta');
            $asesores[] = 'Asesor ' . data_get($item, 'identificador');
        }
        $width = 900;
        if (count($seriaB) < 8) {
            $width = 100 * count($seriaB);
        }
        if (count($seriaB) == 1) {
            $width = 190;
        }
        return [
            "colors" => ['#03a4f1', '#00E396'],
            'title' => [
                'text' => 'Progreso total de pedidos pagados y la meta asignada - ' . $this->getDateTitle(),
                'align' => 'left',
            ],
            'series' => [
                [
                    'name' => 'Pagados / Asignados',
                    'data' => $seriaA,
                ],
                [
                    'name' => 'Asignados / Meta',
                    'data' => $seriaB,
                ],
            ],
            'chart' => [
                'type' => 'bar',
                'height' => $width,
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'offsetX' => -6,
                'style' => [
                    'fontSize' => '12px',
                    'colors' => [
                        '#fff',
                    ],
                ],
            ],
            'stroke' => [
                'show' => true,
                'width' => 0,
                'colors' => [
                    '#fff',
                ],
            ],
            'tooltip' => [
                'shared' => true,
                'intersect' => false,
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Asesores',
                ],
            ],
            'xaxis' => [
                'categories' => $asesores,
            ],
        ];
    }
}
