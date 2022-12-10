<?php

namespace App\Exports;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PagosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pagos($request)
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select('pagos.id as id',
                'u.identificador as users',
                'u.name as usersname',
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
                //DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ped.pagado in (1,2)) as codigos "),
                DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago ")
            )
            ->whereIn('pagos.condicion', [Pago::PAGO, Pago::ADELANTO, Pago::ABONADO])
            ->where('pagos.estado', '1')
            ->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta]);

        if (Auth::user()->rol == 'Llamadas') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == 'Jefe de llamadas') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn('u.identificador', $usersasesores);

        }

        $pagos = $pagos->get();

        /*$pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
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
            ->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta])
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
            ->get();*/

        $this->pagos = $pagos;
        return $this;
    }

    public function view(): View
    {
        return view('pagos.excel.pagos', [
            'pagos' => $this->pagos
        ]);
    }
}
