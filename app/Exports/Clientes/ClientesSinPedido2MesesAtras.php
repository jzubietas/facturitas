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
        $ultimos_pedidos=Cliente::activo()
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

        $clientes=Cliente::
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
            DB::raw("(select dp1.total from pedidos dp1
                                        where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as importeultimopedido")

        ])->get();

    }
}
