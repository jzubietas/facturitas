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

class PedidosPagosGeneralExport implements FromView, ShouldAutoSize
{
    use Exportable;
    
    public function pedidos($request) {        
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->join('detalle_pagos as dpa', 'pa.id', 'dpa.pago_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as asesor_identificador',
                'u.name as asesor_nombre',
                'u.id as asesor_id',
                'pedidos.codigo as codigos',
                'dpe.nombre_empresa as empresas',
                'dpe.total as total',
                'dpe.ft',
                'dpe.mes',           
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.courier',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                'pedidos.condicion_envio as condicion_env',
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                'pedidos.modificador',
                'pa.diferencia',
                'pedidos.estado',
                DB::raw('sum(dpa.monto) as total_pago'),
                'dpa.banco',
                'dpa.fecha as fecha_pago',
                'dpa.titular'
                )
            ->where('pedidos.estado', '1')
            ->where('pedidos.pago', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'u.name',
                'u.id',
                'pedidos.codigo',
                'dpe.nombre_empresa',
                'dpe.total',
                'dpe.ft',
                'dpe.mes',           
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.courier',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                'pedidos.condicion_envio',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.modificador',
                'pa.diferencia',
                'pedidos.estado',
                'dpa.banco',
                'dpa.fecha',
                'dpa.titular',
                'pedidos.created_at',
                'pedidos.updated_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        
        $this->pedidos = $pedidos;
            
        return $this;
    }
    
    public function pedidos2($request) {        
        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS SIN PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as asesor_identificador',
                'u.name as asesor_nombre',
                'u.id as asesor_id',
                'pedidos.codigo as codigos',
                'dpe.nombre_empresa as empresas',
                'dpe.total as total',
                'dpe.ft',
                'dpe.mes',           
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.courier',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                'pedidos.condicion_envio as condicion_env',
                'pedidos.condicion as condiciones',
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                'pedidos.modificador',
                'pedidos.estado',
            )
            ->where('pedidos.estado', '1')
            ->where('dpe.estado', '1')
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
            ->where('pedidos.pago', '0')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'u.name',
                'u.id',
                'pedidos.codigo',
                'dpe.nombre_empresa',
                'dpe.total',
                'dpe.ft',
                'dpe.mes',           
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.courier',
                'dpe.envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                'pedidos.condicion_envio',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'pedidos.updated_at',
                'pedidos.modificador',
                'pedidos.estado',
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        
            $this->pedidos2 = $pedidos2;
        
        return $this;
    }
    
    public function view(): View {
        return view('reportes.PedidosPagosGeneralExcel', [
            'pedidos'=> $this->pedidos,
            'pedidos2' => $this->pedidos2
        ]);
    }
}
