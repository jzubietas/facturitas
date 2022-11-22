<?php

namespace App\Exports;

use App\Models\MovimientoBancario;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MovimientosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function movimientos1($request) {
        $movimientos1 = MovimientoBancario::leftjoin("pagos as p","movimiento_bancarios.cabpago","p.id")//where("movimiento_bancarios.estado","1")
            //->leftjoin("pagos as p","movimiento_bancarios.cabpago","p.id")
            ->join('users as u', 'p.user_id', 'u.id')
            ->select(
                'movimiento_bancarios.id',
                'movimiento_bancarios.banco',
                'movimiento_bancarios.titular',
                'movimiento_bancarios.importe',
                'movimiento_bancarios.tipo',
                'movimiento_bancarios.fecha',
                'movimiento_bancarios.pago',
                'p.id as pagoid',
                'u.identificador as users',
                DB::raw(" (CASE WHEN (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=p.id and dpago.estado in (1) )>1 then 'V' else 'I' end) as cantidad_voucher "),

                DB::raw(" (CASE WHEN (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=p.id and ppedidos.estado in (1)  )>1 then 'V' else 'I' end) as cantidad_pedido "),
                DB::raw(" (select count(dp.id) from detalle_pagos dp where dp.pago_id=p.id) as cant "),
            )
            ->get();

        $this->movimientos1 = $movimientos1;
        return $this;
    }
    public function view(): View {
        return view('movimientos.excel.index', [
            'movimientos1'=> $this->movimientos1,
        ]);
    }

}