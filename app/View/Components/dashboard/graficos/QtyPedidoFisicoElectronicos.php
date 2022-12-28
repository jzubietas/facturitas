<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\DetallePedido;
use App\Models\Pedido;

class QtyPedidoFisicoElectronicos extends Widgets
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $jsChart = $this->jsConfig();
        return view('components.dashboard.graficos.qty-pedido-fisico-electronicos', compact('jsChart'));
    }

    public function jsConfig()
    {
        $pedidosAtendidosFisicos=Pedido::query()->activo()->porAtenderEstatus()
            ->whereIn(
                'id',
                DetallePedido::query()->select('pedido_id')
                    ->activo()
                    ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                    ->where('detalle_pedidos.tipo_banca','like','%FISICO%')
            )->count();

        $pedidosAtendidosElectronica=Pedido::query()->activo()->porAtenderEstatus()
            ->whereIn(
                'id',
                DetallePedido::query()->select('pedido_id')
                    ->activo()
                    ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                    ->where('detalle_pedidos.tipo_banca','like','%ELECTRONICA%')
            )->count();

        return [
          "fisico"=>[
              "count"=>$pedidosAtendidosFisicos,
              "title"=>"Pedidos por atender Fisicos"
          ],
          "electroinco"=>[
              "count"=>$pedidosAtendidosElectronica,
              "title"=>"Pedidos por atender Electrónico"
          ]
        ];
    }
}
