<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;

class GraficoPedidosAtendidoAnulados extends Widgets
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $configuration = $this->generateJs();
        return view('components.dashboard.graficos.grafico-pedidos-atendido-anulados', compact('configuration'));
    }

    public function generateJs()
    {
        $pedidos = $this->applyFilter(Pedido::activo())->count();
        $pedidosAnulados = $this->applyFilter(Pedido::activo('0'))->count();
        return [
            'series' => [
                [
                    'name' => 'Pedidos Activos',
                    'data' => [
                        $pedidos,
                    ],
                ],
                [
                    'name' => 'Pedidos Anulados',
                    'data' => [
                        $pedidosAnulados,
                    ],
                ],
            ],
            'chart' => [
                'type' => 'bar',
                'height' => 200,
                'stacked' => true,
                'stackType' => '100%',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                ],
            ],
            'stroke' => [
                'width' => 1,
                'colors' => [
                    0 => '#fff',
                ],
            ],
            'title' => [
                'text' => \Str::upper('Cantidad de pedidos activos y anulados'),
            ],
            'xaxis' => [
                'categories' => [
                    'Pedidos',
                ],
            ],
            'tooltip' => [
                'y' => [
                ],
            ],
            'fill' => [
                'opacity' => 1,
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'left',
                'offsetX' => 40,
            ],
        ];
    }
}
