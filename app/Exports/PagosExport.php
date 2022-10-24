<?php

namespace App\Exports;

use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PagosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pagos($request) {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id', 
                    'u.identificador as id_asesor', 
                    'u.name as nombre_asesor', 
                    'dpe.codigo as codigo_pedido',                    
                    'pagos.observacion', 
                    'dpe.total as total_deuda', 
                    DB::raw('sum(dpa.monto) as total_pago'), 
                    'pagos.diferencia as diferencia',
                    'pagos.condicion as estado_pago',                   
                    'pagos.created_at as fecha'
                    )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy('pagos.id', 
                    'u.identificador',
                    'u.name', 
                    'dpe.codigo',                    
                    'pagos.observacion', 
                    'dpe.total',
                    'pagos.condicion', 
                    'pagos.created_at',
                    'pagos.diferencia'
                    )
            ->get();
        $this->pagos = $pagos;
        return $this;
    }            

    public function view(): View {
        return view('pagos.excel.pagos', [
            'pagos'=> $this->pagos
        ]);
    }  
}
