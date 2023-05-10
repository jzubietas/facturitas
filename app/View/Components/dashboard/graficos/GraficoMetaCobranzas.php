<?php

namespace App\View\Components\dashboard\graficos;


use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\HtmlString;

class GraficoMetaCobranzas extends Widgets
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
        return view('components.dashboard.graficos.grafico-meta-cobranzas', compact('jsChart'));
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
        $this->progressData = collect($newData)->values()->sortBy('identificador')->all();
    }

    public function generateConfigChart()
    {
        $seriaA = [];
        $seriaB = [];
        $seriaC = [];
        $asesores = [];
        foreach ($this->progressData as $item) {
            $asignados = data_get($item, 'asignados');
            if ($asignados <= 0) {
                continue;
            }
            $seriaA[] = data_get($item, 'meta');
            $seriaB[] = $asignados;
            $seriaC[] = data_get($item, 'pagados');
            $asesores[] = 'Asesor ' . data_get($item, 'identificador');
        }
        $width = 900;
        if (count($seriaC) < 8) {
            $width = 100 * count($seriaC);
        }
        if (count($seriaC) == 1) {
            $width = 190;
        }
        return [
            "colors" => ['#464646', '#03a4f1', '#00E396'],
            'title' => [
                'text' => 'Progreso total de pedidos pagados y la meta asignada - ' . $this->getDateTitle(),
                'align' => 'left',
            ],
            'series' => [
                [
                    'name' => 'Meta Asignada',
                    'data' => $seriaA,
                ],
                [
                    'name' => 'Pedidos Asignados',
                    'data' => $seriaB,
                ],
                [
                    'name' => 'Pedidos Pagados',
                    'data' => $seriaC,
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
