<?php

namespace App\Exports;

use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MisPagosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pagos($request) {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id',
                    'dpe.codigo as codigos', 
                    'u.identificador as users', 
                    'pagos.observacion', 
                    'dpe.total as total_deuda', 
                    DB::raw('sum(dpa.monto) as total_pago'), 
                    'pagos.condicion',                   
                    'pagos.created_at as fecha'
                    )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy('pagos.id', 
                    'dpe.codigo', 
                    'u.identificador',
                    'pagos.observacion', 'dpe.total',
                    'pagos.total_cobro',
                    'pagos.condicion', 
                    'pagos.created_at')
            ->get();
        $this->pagos = $pagos;
        return $this;
    }

    public function view(): View {
        return view('pagos.excel.mispagos', [
            'pagos'=> $this->pagos
        ]);
    }  
}