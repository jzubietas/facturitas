<?php

namespace App\Exports;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PedidosPorAsesorExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidos($request) {
        if ($request->user_id == null) {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->join('detalle_pagos as dpa', 'pa.id', 'dpa.pago_id')
            ->select(
                'pedidos.id',
                'pedidos.created_at as fecha',
                'u.name as users',
                'dpe.codigo as codigos',
                'c.celular as celulares',
                'c.nombre as nombres',
                'dpe.nombre_empresa as empresas',
                'dpe.mes',
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.ft',
                'dpe.courier',
                'dpe.total',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                DB::raw('sum(dpa.monto) as total_pago'),
                'dpa.banco',
                'dpa.fecha as fecha_pago',
                'pa.condicion as condicion_pa',
                'dpa.titular'
            )
            ->where('pedidos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->where('u.supervisor', Auth::user()->id)
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'pedidos.created_at',
                'u.name',
                'dpe.codigo',
                'c.celular',
                'c.nombre',
                'dpe.nombre_empresa',
                'dpe.mes',
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.ft',
                'dpe.courier',
                'dpe.total',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                'dpa.banco',
                'dpa.fecha',
                'pa.condicion',
                'dpa.titular'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

            $this->pedidos = $pedidos;
        }
        else {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
                ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                ->join('pagos as pa', 'pp.pago_id', 'pa.id')
                ->join('detalle_pagos as dpa', 'pa.id', 'dpa.pago_id')
                ->select(
                    'pedidos.id',
                    'pedidos.created_at as fecha',
                    'u.name as users',
                    'dpe.codigo as codigos',
                    'c.celular as celulares',
                    'c.nombre as nombres',
                    'dpe.nombre_empresa as empresas',
                    'dpe.mes',
                    'dpe.ruc',
                    'dpe.cantidad',
                    'dpe.tipo_banca',
                    'dpe.porcentaje',
                    'dpe.ft',
                    'dpe.courier',
                    'dpe.total',
                    'dpe.envio_doc',
                    'dpe.cant_compro',
                    'dpe.fecha_envio_doc_fis',
                    'dpe.fecha_recepcion',
                    DB::raw('sum(dpa.monto) as total_pago'),
                    'dpa.banco',
                    'dpa.fecha as fecha_pago',
                    'pa.condicion as condicion_pa',
                    'dpa.titular'
                )
                ->where('pedidos.estado', '1')
                ->where('dpe.estado', '1')
                ->where('dpa.estado', '1')
                ->where('u.id', $request->user_id)
                ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                ->groupBy(
                    'pedidos.id',
                    'pedidos.created_at',
                    'u.name',
                    'dpe.codigo',
                    'c.celular',
                    'c.nombre',
                    'dpe.nombre_empresa',
                    'dpe.mes',
                    'dpe.ruc',
                    'dpe.cantidad',
                    'dpe.tipo_banca',
                    'dpe.porcentaje',
                    'dpe.ft',
                    'dpe.courier',
                    'dpe.total',
                    'dpe.envio_doc',
                    'dpe.cant_compro',
                    'dpe.fecha_envio_doc_fis',
                    'dpe.fecha_recepcion',
                    'dpa.banco',
                    'dpa.fecha',
                    'pa.condicion',
                    'dpa.titular'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();

            $this->pedidos = $pedidos;
            }
        return $this;
    }

    public function pedidos2($request) {
        if ($request->user_id == null) {
            $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->join('detalle_pagos as dpa', 'pa.id', 'dpa.pago_id')
            ->select(
                'pedidos.id',
                'pedidos.created_at as fecha',
                'u.name as users',
                'dpe.codigo as codigos',
                'c.celular as celulares',
                'c.nombre as nombres',
                'dpe.nombre_empresa as empresas',
                'dpe.mes',
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.ft',
                'dpe.courier',
                'dpe.total',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                DB::raw('sum(dpa.monto) as total_pago'),
                'dpa.banco',
                'dpa.fecha as fecha_pago',
                'pa.condicion as condicion_pa',
                'dpa.titular'
            )
                ->where('pedidos.estado', '1')
                ->where('dpe.estado', '1')
                ->where('dpa.estado', '1')
                ->where('u.supervisor', Auth::user()->id)
                ->whereIn('pedidos.condicion', [1, 2, 3])
                ->where('pedidos.pago', '0')
                ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                ->groupBy(
                    'pedidos.id',
                    'pedidos.created_at',
                    'u.name',
                    'dpe.codigo',
                    'c.celular',
                    'c.nombre',
                    'dpe.nombre_empresa',
                    'dpe.mes',
                    'dpe.ruc',
                    'dpe.cantidad',
                    'dpe.tipo_banca',
                    'dpe.porcentaje',
                    'dpe.ft',
                    'dpe.courier',
                    'dpe.total',
                    'dpe.envio_doc',
                    'dpe.cant_compro',
                    'dpe.fecha_envio_doc_fis',
                    'dpe.fecha_recepcion',
                    'dpa.banco',
                    'dpa.fecha',
                    'pa.condicion',
                    'dpa.titular'
                    )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();

                $this->pedidos2 = $pedidos2;
        }
        else{
            $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->join('detalle_pagos as dpa', 'pa.id', 'dpa.pago_id')
            ->select(
                'pedidos.id',
                'pedidos.created_at as fecha',
                'u.name as users',
                'dpe.codigo as codigos',
                'c.celular as celulares',
                'c.nombre as nombres',
                'dpe.nombre_empresa as empresas',
                'dpe.mes',
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.ft',
                'dpe.courier',
                'dpe.total',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                DB::raw('sum(dpa.monto) as total_pago'),
                'dpa.banco',
                'dpa.fecha as fecha_pago',
                'pa.condicion as condicion_pa',
                'dpa.titular'
            )
                ->where('pedidos.estado', '1')
                ->where('dpe.estado', '1')
                ->where('dpa.estado', '1')
                ->whereIn('pedidos.condicion', [1, 2, 3])
                ->where('pedidos.pago', '0')
                ->where('u.id', $request->user_id)
                ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                ->groupBy(
                    'pedidos.id',
                    'pedidos.created_at',
                    'u.name',
                    'dpe.codigo',
                    'c.celular',
                    'c.nombre',
                    'dpe.nombre_empresa',
                    'dpe.mes',
                    'dpe.ruc',
                    'dpe.cantidad',
                    'dpe.tipo_banca',
                    'dpe.porcentaje',
                    'dpe.ft',
                    'dpe.courier',
                    'dpe.total',
                    'dpe.envio_doc',
                    'dpe.cant_compro',
                    'dpe.fecha_envio_doc_fis',
                    'dpe.fecha_recepcion',
                    'dpa.banco',
                    'dpa.fecha',
                    'pa.condicion',
                    'dpa.titular'
                    )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();

                $this->pedidos2 = $pedidos2;
        }
        return $this;
    }

    public function view(): View {
        return view('reportes.PedidosPorFechasExcel', [
            'pedidos'=> $this->pedidos,
            'pedidos2' => $this->pedidos2
        ]);
    }
}
