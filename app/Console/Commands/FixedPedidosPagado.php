<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Console\Command;

class FixedPedidosPagado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixed:pedidos:pagados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pedidos = Pedido::query()->activo()
            ->where('pedidos.pago', '=', 0)
            ->where('pedidos.pagado', '=', 2)
            ->get();

        $count = 0;
        $result = [];
        foreach ($pedidos as $pedido) {
            $pagopedidos = $pedido->pagoPedidos()
                ->select([
                    'pagos.*',
                    'pago_pedidos.pagado as pago_pedidos_pagado_status',
                    'pago_pedidos.abono as pago_pedidos_abono',
                    \DB::raw('(SELECT sum(detalle_pagos.monto) FROM detalle_pagos WHERE
	detalle_pagos.pago_id=pagos.id
	and detalle_pagos.estado=1
	AND detalle_pagos.imagen is NOT NULL) as detalle_pagos_monto'),
                ])
                ->activo()
                ->pagado()
                ->join('pagos', 'pagos.id', '=', 'pago_pedidos.pago_id')
                ->where('pagos.condicion', Pago::ABONADO)
                ->where('pagos.estado', '1')
                ->get();
            if ($pagopedidos->count() > 0) {
                $count++;
                $result[] = [
                    "nro" => $count,
                    "id" => $pedido->id,
                    "correlativo" => $pedido->correlativo,
                    "codigo" => $pedido->codigo,
                    "condicion" => $pedido->condicion,
                    "condicion_code" => $pedido->condicion_code,
                    "total_cobro" => $pagopedidos->sum('total_cobro'),
                    "total_pagado" => $pagopedidos->sum('total_pagado'),
                    "condicion_pago" => $pagopedidos->pluck('condicion')->join(', '),
                    'pagado_status' => $pagopedidos->pluck('pago_pedidos_pagado_status')->join(', '),
                    'pago_pedidos_abono' => $pagopedidos->sum('pago_pedidos_abono'),
                    'detalle_pagos_monto' => $pagopedidos->sum('detalle_pagos_monto'),
                ];
            }
        }

       collect($result)
            ->filter(function ($result) {
                return ($result['total_cobro'] == $result['total_pagado'])
                    && ($result['detalle_pagos_monto'] == $result['pago_pedidos_abono'])
                    && ($result['total_cobro'] == $result['pago_pedidos_abono']);
            })
            ->values()
            ->each(function ($result) {
                Pedido::query()
                    ->where('id', '=', data_get($result, 'id'))
                    ->update([
                        'pago' => 1
                    ]);
                return $result;
            });

        $result = collect($result)
            ->filter(function ($result) {
                return !(($result['total_cobro'] == $result['total_pagado'])
                    && ($result['detalle_pagos_monto'] == $result['pago_pedidos_abono'])
                    && ($result['total_cobro'] == $result['pago_pedidos_abono']));
            })
            ->values()
            ->map(function ($result, $index) {
                $result['nro'] = $index + 1;
                return $result;
            });
        $this->info($count);
        $this->table([
            "nro",
            "id",
            "correlativo",
            "codigo",
            "condicion",
            "condicion_code",
            "total_cobro",
            "total_pagado",
            "condicion_pago",
            'pagado_status',
            'pago_pedidos_abono',
            'detalle_pagos_monto',
        ], $result);
        return 0;
    }
}
