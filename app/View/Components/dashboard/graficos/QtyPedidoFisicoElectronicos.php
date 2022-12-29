<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\User;

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
        $resultados = $this->jsConfig();
        return view('components.dashboard.graficos.qty-pedido-fisico-electronicos', compact('resultados'));
    }

    public function jsConfig()
    {
        $pedidosAtendidosFisicos = Pedido::query()
            ->activo()
            ->porAtenderEstatus()
            ->whereIn(
                'id',
                DetallePedido::query()->select('pedido_id')
                    ->activo()
                    ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                    ->where('detalle_pedidos.tipo_banca', 'like', '%FISICO%')
            )
            ->count();

        $pedidosAtendidosElectronica = Pedido::query()
            ->activo()
            ->porAtenderEstatus()
            ->whereIn(
                'id',
                DetallePedido::query()->select('pedido_id')
                    ->activo()
                    ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                    ->where('detalle_pedidos.tipo_banca', 'like', '%ELECTRONICA%')
            )
            ->count();

        $jefesOpe = User::activo()->where('rol', '=', User::ROL_JEFE_OPERARIO)->get();

        $dataFi = [[
            "count" => $pedidosAtendidosFisicos,
            "title" => "Total"
        ]];
        $dataEl = [[
            "count" => $pedidosAtendidosElectronica,
            "title" => "Total"
        ]];
        foreach ($jefesOpe as $user) {

            $operario = User::activo()
                ->where('rol', '=', User::ROL_OPERARIO)
                ->where('jefe', $user->id)
                ->pluck('id');

            $asesores = User::activo()
                ->whereIn('rol', [User::ROL_ASESOR, User::ROL_ASESOR_ADMINISTRATIVO])
                ->whereIn('operario', $operario)
                ->pluck('id');
            $fi = Pedido::query()
                ->activo()
                ->porAtenderEstatus()
                ->whereIn('user_id', $asesores)
                ->whereIn(
                    'id',
                    DetallePedido::query()->select('pedido_id')
                        ->activo()
                        ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                        ->where('detalle_pedidos.tipo_banca', 'like', '%FISICO%')
                )
                ->count();

            $el = Pedido::query()
                ->activo()
                ->whereIn('user_id', $asesores)
                ->porAtenderEstatus()
                ->whereIn(
                    'id',
                    DetallePedido::query()->select('pedido_id')
                        ->activo()
                        ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                        ->where('detalle_pedidos.tipo_banca', 'like', '%ELECTRONICA%')
                )
                ->count();
            $dataFi[] = [
                "title" => $user->name,
                "count" => $fi,
            ];
            $dataEl[] = [
                "title" => $user->name,
                "count" => $el
            ];
        }
        return [
            "fisico" => $dataFi,
            "electronic" => $dataEl
        ];
    }
}
