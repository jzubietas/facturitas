<?php

namespace App\Exports\Clientes;

use App\Abstracts\Export;
use App\Models\Cliente;

class ClientesSinPedido2MesesAtras extends Export
{

    public function collection()
    {
        //item|asesor-identificador|celular|mes|rucs,|estado_pago (deuda o no deuda)|importe total de la deuda
        Cliente::query()
            ->select([
                'clientes.id',
                'users.letra',
                'users.identificador',
                'clientes.celular',
                'clientes.created_at',
                \DB::raw('concat_group(detalle_pedidos.ruc)'),
            ])
            ->activo()
            ->join('pedidos', 'pedidos.cliente_id', '=', 'clientes.id')
            ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->join('users', 'users.id', '=', 'clientes.user_id')
            ->whereDate('pedidos.created_at', '>=', now()->startOfMonth()->subMonths(2))
            ->whereNotBetween('pedidos.created_at', [now()->startOfMonth()->subMonths(2), now()])
            ->activoJoin('pedidos')
            ->activoJoin('detalle_pedidos')
            ->get();
    }
}
