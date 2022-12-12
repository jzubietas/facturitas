<?php

namespace App\Exports;

use App\Models\Pedido;
use App\Models\User;
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

    public function pedidos($request)
    {
        /*$pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->join('detalle_pagos as dpa', 'pa.id', 'dpa.pago_id')
            ->select(
                'pedidos.id',
                'pedidos.condicion_envio',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'pedidos.updated_at',
                'pedidos.modificador',
                'pedidos.estado',
                'pedidos.codigo as codigos',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                'pa.condicion as condicion_pa',
                'pa.diferencia',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as asesor_identificador',
                'u.name as name',
                'u.id as asesor_id',
                'dpe.ft',
                'dpe.mes',
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.courier',
                'dpe.nombre_empresa as empresas',
                'dpe.total as total',
                'dpe.cant_compro',
                DB::raw('DATE_FORMAT(dpe.fecha_envio_doc_fis, "%d/%m/%Y (%H:%i:%s)") as fecha_envio_doc_fis'),
                DB::raw('DATE_FORMAT(dpe.fecha_recepcion, "%d/%m/%Y (%H:%i:%s)") as fecha_recepcion'),
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),

                DB::raw('sum(dpa.monto) as total_pago'),
                'dpa.banco',
                'dpa.fecha as fecha_pago',
                'dpa.titular'
                )
            ->whereIn('pedidos.estado', ['0','1'])
            ->where('pedidos.pago', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta])*/
        /*->groupBy(
            'pedidos.id',
            'c.nombre',
            'c.celular',
            'c.icelular',
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
        )*/
        //->orderBy('pedidos.created_at', 'DESC');


        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            //->join('detalle_pagos as dpa', 'pedidos.id', 'dpa.pago_id')

            //->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->select(
                'pedidos.id',
                'u.name as name',
                'u.identificador as asesor_identificador',
                'pedidos.codigo as codigos',
                'c.celular as celulares',
                'dpe.nombre_empresa as empresas',
                'dpe.ft',
                'dpe.mes',
                'dpe.ruc',
                'dpe.cantidad',
                'dpe.tipo_banca',
                'dpe.porcentaje',
                'dpe.courier',
                'dpe.total as total',
                'pedidos.created_at',
                'pedidos.updated_at',
                'pedidos.condicion',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                /*DB::raw('sum(dpa.monto) as total_pago'),
                'dpa.banco',
                'dpa.fecha as fecha_pago',
                'dpa.titular'*/


                DB::raw('(select sum(pa.abono) from pago_pedidos as pa inner join pedidos dpa on dpa.id=pa.pedido_id where dpa.id = pedidos.id group by dpa.id) as total_pago'),

                DB::raw('(select dpa.banco from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id
                and pedido.id=pedidos.id
                inner join pagos pago on pagopedido.pago_id=pago.id
                inner join detalle_pagos dpa on dpa.pago_id=pago.id
                where pagopedido.estado=1
                and pago.estado=1 order by pagopedido.created_at desc limit 1) as banco'),

                DB::raw('(select dpa.fecha from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id
                and pedido.id=pedidos.id
                inner join pagos pago on pagopedido.pago_id=pago.id
                inner join detalle_pagos dpa on dpa.pago_id=pago.id
                where pagopedido.estado=1
                and pago.estado=1 order by pagopedido.created_at desc limit 1) as fecha_pago'),

                DB::raw('(select dpa.titular from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id
                and pedido.id=pedidos.id
                inner join pagos pago on pagopedido.pago_id=pago.id
                inner join detalle_pagos dpa on dpa.pago_id=pago.id
                where pagopedido.estado=1
                and pago.estado=1 order by pagopedido.created_at desc limit 1) as titular'),

                /*DB::raw('(select dpa.banco from detalle_pagos as dpa inner join pagos pa on dpa.pago_id=pa.id)
                as banco'),*/


                'pedidos.condicion_envio'
            )
            ->whereIn('pedidos.condicion', [Pedido::POR_ATENDER, Pedido::EN_PROCESO_ATENCION, Pedido::ATENDIDO, Pedido::ANULADO])
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]);

        switch (Auth::user()->rol) {
            case User::ROL_JEFE_OPERARIO:
                $operarios = User::where('users.rol', User::ROL_OPERARIO)
                    ->where('users.estado', '1')
                    ->where('users.jefe', Auth::user()->id)
                    ->select(
                        DB::raw("users.id as id")
                    )
                    ->pluck('users.id');

                $asesores = User::where('users.rol', User::ROL_ASESOR)
                    ->where('users.estado', '1')
                    ->WhereIn('users.operario', $operarios)
                    ->select(
                        DB::raw("users.identificador")
                    )
                    ->pluck('identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
                break;
            case User::ROL_OPERARIO:
                $asesores = User::where('users.rol', User::ROL_ASESOR)
                    ->where('users.estado', '1')
                    ->Where('users.operario', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador")
                    )
                    ->pluck('identificador');
                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
                break;
            case User::ROL_ENCARGADO:
                $asesores = User::where('users.rol', User::ROL_ASESOR)
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador")
                    )
                    ->pluck('identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
                break;
        }

        $pedidos = $pedidos->get();

        $this->pedidos = $pedidos;

        return $this;
    }


    /*public function pedidos2($request) {
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
            ->whereIn('pedidos.condicion', [1, 2, 3, 'ANULADO'])
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
    }*/

    public function view(): View
    {
        return view('reportes.PedidosPagosGeneralExcel', [
            'pedidos' => $this->pedidos
        ]);
    }
}
