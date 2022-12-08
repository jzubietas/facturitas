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

class PagosObservadosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pagos($request) {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
        ->join('clientes as c', 'pagos.cliente_id', 'c.id')
        ->select('pagos.id as id',
                'u.identificador as users',
                'c.icelular',
                'c.celular',
                'pagos.observacion',
                'pagos.total_cobro',
                'pagos.condicion',
                DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%d/%m/%Y %H:%i:%s")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                DB::raw('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha_timestamp'),
                DB::raw(" (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) ) as cantidad_voucher "),
                DB::raw(" (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  ) as cantidad_pedido "),
                DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ppp.estado=1 and ped.pagado in (1,2)) as codigos "),

                DB::raw(" ( select GROUP_CONCAT(ppp2.codigo) from pago_pedidos ped2 inner join pedidos ppp2 on ped2.pedido_id =ppp2.id where pagos.id=ped2.pago_id and ped2.estado in (0) and ppp2.estado=1 and ped2.pagado in (1,2)) as codigos_anulados_1 "),
                DB::raw(" ( select GROUP_CONCAT(ppp3.codigo) from pago_pedidos ped2 inner join pedidos ppp3 on ped2.pedido_id =ppp3.id where pagos.id=ped2.pago_id and ped2.estado in (1) and ppp3.estado=0 and ped2.pagado in (1,2)) as codigos_anulados_2 "),
                DB::raw(" ( select GROUP_CONCAT(ppp4.codigo) from pago_pedidos ped2 inner join pedidos ppp4 on ped2.pedido_id =ppp4.id where pagos.id=ped2.pago_id and ped2.estado in (0) and ppp4.estado=0 and ped2.pagado in (1,2)) as codigos_anulados_3 "),
                DB::raw(" ( select GROUP_CONCAT(ppp5.codigo) from pago_pedidos ped2 inner join pedidos ppp5 on ped2.pedido_id =ppp5.id where pagos.id=ped2.pago_id and ped2.estado in (0) and ppp5.estado=0 and ped2.pagado in (0)) as codigos_anulados_4 "),
                DB::raw(" ( select GROUP_CONCAT(ppp6.codigo) from pago_pedidos ped2 inner join pedidos ppp6 on ped2.pedido_id =ppp6.id where pagos.id=ped2.pago_id and ped2.estado in (0) and ppp6.estado=1 and ped2.pagado in (0)) as codigos_anulados_5 "),
                DB::raw(" ( select GROUP_CONCAT(ppp7.codigo) from pago_pedidos ped2 inner join pedidos ppp7 on ped2.pedido_id =ppp7.id where pagos.id=ped2.pago_id and ped2.estado in (1) and ppp7.estado=0 and ped2.pagado in (0)) as codigos_anulados_6 "),
                DB::raw(" ( select GROUP_CONCAT(ppp8.codigo) from pago_pedidos ped2 inner join pedidos ppp8 on ped2.pedido_id =ppp8.id where pagos.id=ped2.pago_id and ped2.estado in (1) and ppp8.estado=1 and ped2.pagado in (0)) as codigos_anulados_7 "),

                DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago ")   ,
                DB::raw(" (select sum(ped3.abono) from pago_pedidos ped3 where ped3.pago_id =pagos.id and ped3.estado in (0) and ped3.pagado in (1,2) ) as total_pago_anulados ")
                )
        ->whereIn('pagos.condicion', [Pago::OBSERVADO])
        ->where('pagos.estado', '1')
        ->whereBetween(DB::raw('( (select DATE( MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1)  )'), [$request->desde, $request->hasta])
        //->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
        //->orderBy('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1)', 'DESC')
        ->get();
        $this->pagos = $pagos;
        return $this;
    }

    public function view(): View {
        return view('pagos.excel.pagosobservados', [
            'pagos'=> $this->pagos
        ]);
    }
}
