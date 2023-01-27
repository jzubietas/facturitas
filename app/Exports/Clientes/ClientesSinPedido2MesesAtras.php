<?php

namespace App\Exports\Clientes;

use App\Abstracts\Export;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class ClientesSinPedido2MesesAtras extends Export
{

    public function collection()
    {
        //item|asesor-identificador|celular|mes|rucs,|estado_pago (deuda o no deuda)|importe total de la deuda
        $ultimos_pedidos=Clientes::activo()
            ->select([
                'clientes.id',
                'clientes.tipo',
DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido"),
DB::raw("(select DATE_FORMAT(dp1.created_at,'%m') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido_mes"),
DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido_anio"),
            ])->get();

        //$ultimos=$ultimos_pedidos->whereNotNull('fechaultimopedido')->get();
        $lista=[];
        foreach ($ultimos_pedidos as $procesada) {

            if($procesada->fechaultimopedido!=null)
            {
               $lista[]=$procesada->id;
            }
        }

        $clientes=Clientes::
            join('users as u','u.id','clientes.user_id')
        ->whereIn("id",$lista)
        ->select([
            'clientes.id as item',
            'u.identificador as asesor_identificador',
            'clientes.celular',
            DB::raw("(select group_concat(r.num_ruc) from rucs r where r.cliente_id=clientes.id) as rucs"),
            DB::raw("(select case when dp.pago=0 then 'DEUDA'
                                        when dp.pago=1 then 'DEUDA'
                                        else 'NO DUDA' from pedidos dp1
                                        where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido"),
            DB::raw(" select * from pedidos inner join detalle_pedidos ")

        ])
        ;




        //$listado=implode(',')



        Cliente::query()
            ->select([
                'clientes.id',
                'users.letra',
                'users.identificador',
                'clientes.celular',
                'clientes.created_at',
                \DB::raw('concat_group(detalle_pedidos.ruc)'),
            ])
            ->activo()
            ->join('pedidos', 'pedidos.cliente_id', '=', 'clientes.id')
            ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->join('users', 'users.id', '=', 'clientes.user_id')
            ->whereDate('pedidos.created_at', '>=', now()->startOfMonth()->subMonths(2))
            ->whereNotBetween('pedidos.created_at', [now()->startOfMonth()->subMonths(2), now()])
            ->activoJoin('pedidos')
            ->activoJoin('detalle_pedidos')
            ->get();
    }
}
