<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\View\Component;

class GraficoPedidoCobranzasDelDia extends Widgets
{
    public $configChart = [];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->setConfig();
        return view('components.dashboard.graficos.grafico-pedido-cobranzas-del-dia');
    }

    public function setConfig()
    {
        $days = [];
        $currentDate = $this->startDate->clone();
        $endDay = $this->endDate->startOfDay();
        $now = now();
        if ($now->format('m-Y') == $endDay->format('m-Y')) {
            $endDay = $now->startOfDay();
        }

        do {
            if (!in_array(\Str::lower($currentDate->locale('es_PE')->translatedFormat('l')), ['sunday', 'domingo'])) {
                $days[] = $currentDate->clone();
            }
            $currentDate->addDay();
        } while ($currentDate <= $endDay);

        $series = [];
        foreach ($days as $day) {
            $series['p'][] = Pedido::query()->activo()->whereDate('created_at', $day)->count();
            $series['c'][] = Pedido::query()->activo()->pagados()->whereDate('created_at', $day)->count();
        }


        $this->configChart = [
            "series" => [
                [
                    "name" => \Str::upper("Pedidos creados"),
                    "data" => $series['p']
                ],
                [
                    "name" => \Str::upper("Cobranzas"),
                    "data" => $series['c']
                ]
            ],
            "chart" => [
                "height" => 350,
                "type" => "line",
                "zoom" => [
                    "enabled" => false
                ]
            ],
            "dataLabels" => [
                "enabled" => false
            ],
            "stroke" => [
                "curve" => "straight"
            ],
            "title" => [
                "text" => \Str::upper("Cantidad de pedidos y cobranzas de " . $this->getDateTitle()),
                "align" => "left"
            ],
            "grid" => [
                "row" => [
                    "colors" => [
                        "#f3f3f3",
                        "transparent"
                    ],
                    "opacity" => 0.5
                ]
            ],
            "xaxis" => [
                "categories" => collect($days)->map(fn($date) => $date->format('d-m-Y'))->all()
            ]
        ];
    }
}
