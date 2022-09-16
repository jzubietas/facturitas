<?php

namespace App\Exports;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PedidosPorEnviarExport implements FromView
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
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'pedidos.condicion_envio',
                'pedidos.envio',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.envio', '1')
            ->whereIn('pedidos.condicion_envio', ['PENDIENTE DE ENVIO', 'EN REPARTO'])
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'pedidos.condicion_envio',
                'pedidos.envio',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        return view('pedidos.excel.pedidosporenviar', compact('pedidos'));
    }
}