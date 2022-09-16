<?php

namespace App\Exports;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PedidosExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at as fecha',
                'pedidos.updated_at as fecha_mod',
                'pedidos.modificador',
                'pa.diferencia',                
                'pedidos.estado'
            )
            /* ->where('pedidos.estado', '1')
            ->where('dp.estado', '1') */
            ->where('pedidos.pago', '1')
            ->where('pa.estado', '1')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'pedidos.updated_at',
                'pedidos.modificador',
                'pa.diferencia',
                'pedidos.estado')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS SIN PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'pedidos.condicion as condiciones',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at as fecha',
                'pedidos.updated_at as fecha_mod',
                'pedidos.modificador',
                'pedidos.estado'
            )
            /* ->where('pedidos.estado', '1')
            ->where('dp.estado', '1') */
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
            ->where('pedidos.pago', '0')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'pedidos.updated_at',
                'pedidos.modificador',
                'pedidos.estado'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        return view('pedidos.excel.pedidos', compact('pedidos', 'pedidos2'));
    }
}