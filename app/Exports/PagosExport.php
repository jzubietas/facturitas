<?php

namespace App\Exports;

use App\Models\Pago;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PagosExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id', 
                    'dpe.codigo as codigos', 
                    'u.name as users', 
                    'pagos.observacion', 
                    'dpe.total as total_deuda', 
                    DB::raw('sum(dpa.monto) as total_pago'), 
                    'pagos.condicion',                   
                    'pagos.created_at as fecha'
                    )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->groupBy('pagos.id', 
                    'dpe.codigo', 
                    'u.name', 
                    'pagos.observacion', 'dpe.total',
                    'pagos.condicion', 
                    'pagos.created_at')
            ->get();
        return view('pagos.excel.pagos', compact('pagos'));
    }
}
