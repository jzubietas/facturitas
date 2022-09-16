<?php

namespace App\Exports;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class MisPedidosExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO'])
            ->where('pedidos.pago', '0')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        return view('pedidos.excel.mispedidos', compact('pedidos', 'pedidos2'));
    }
}