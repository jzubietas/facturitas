<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\GraficosComponent\GraficosComponent;
use App\Models\Cliente;
use App\Models\User;

class TopClientesPedidos extends GraficosComponent
{
    protected $top = 10;
    public $dataChart = [];

    /**
     * @param int $top
     */
    public function __construct(int $top, $title = null, $labelX = null, $labelY = null, string $height = '370')
    {
        parent::__construct($title ?: "Top 10 clientes con monto mas alto en pedidos ", $labelX ?: "Clientes", $labelY ?: "Monto total", $height);
        $this->top = $top;

    }

    public function generateData()
    {
        /**
         * SELECT c.id,c.nombre,SUM(dt.total) AS cliente_total FROM clientes AS c
         * INNER JOIN pedidos as p ON p.cliente_id=c.id
         * INNER JOIN detalle_pedidos as dt ON dt.pedido_id=p.id
         * GROUP BY c.id,c.nombre
         * ORDER BY cliente_total DESC
         * LIMIT 10
         */
        $userb = User::query()->where('users.identificador', '=', 'B')->first();
        $clientes = $this->applyFilter(Cliente::query()
            ->activo()
            ->select(['clientes.id', 'clientes.nombre', \DB::raw('SUM(detalle_pedidos.total) AS cliente_total')])
            ->join('pedidos', 'pedidos.cliente_id', '=', 'clientes.id')
            ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->groupBy(['clientes.id', 'clientes.nombre'])
            ->orderByDesc('cliente_total')
            ->when($userb != null, function ($query) use ($userb) {
                return $query->where('clientes.user_id', '<>', $userb->id);
            })
            ->limit(10), 'pedidos.created_at')
            ->get()
            ->map(function ($cliente) {
                return [
                    'label' => $cliente->nombre,
                    'y' => floatval($cliente->cliente_total),
                ];
            });
        $this->dataChart = $clientes->values()->all();
    }

    public function generateConfig()
    {
        return [
            'animationEnabled' => true,
            'title' => [
                'text' => $this->title
            ],
            'axisY' => [
                'title' => $this->labelY,
                'prefix' => 'S/ ',
            ],
            'axisX' => [
                'title' => $this->labelX,
                'suffix' => '',
            ],
            'data' => [
                [
                    'type' => 'column',
                    'dataPoints' => $this->dataChart,
                ]
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->generateData();
        $settings = $this->generateConfig();
        return view('components.dashboard.graficos.top-clientes-pedidos', compact('settings'));
    }
}
