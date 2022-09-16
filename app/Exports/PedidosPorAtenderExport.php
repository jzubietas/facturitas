<?php

namespace App\Exports;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PedidosPorAtenderExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion', 'POR ATENDER')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        return view('pedidos.excel.pedidosporatender', compact('pedidos'));
    }
}