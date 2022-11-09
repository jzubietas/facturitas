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

class PagosPorRevisarExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pagos($request) {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
        ->join('clientes as c', 'pagos.cliente_id', 'c.id')
        ->select('pagos.id as id',
                'u.identificador as users',
                'c.celular',
                'pagos.observacion',                        
                'pagos.total_cobro',
                'pagos.condicion',
                DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%d/%m/%Y %H:%i:%s")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                DB::raw('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha_timestamp'),
                DB::raw(" (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) ) as cantidad_voucher "),
                DB::raw(" (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  ) as cantidad_pedido "),
                DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ped.pagado in (1,2)) as codigos "),
                DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago ")   
                )
        ->whereIn('pagos.condicion', ['PAGO','ADELANTO'])
        ->orderBy('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1)', 'DESC')
        ->get();
        $this->pagos = $pagos;
        return $this;
    }
    
    public function view(): View {
        return view('pagos.excel.pagosporrevisar', [
            'pagos'=> $this->pagos
        ]);
    }

}
