<?php

namespace App\Http\Controllers\Pedidos;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anulados(Request $request)
    {
        if(!\auth()->user()->can('pedidos.pendiente.anulacion')){//if (!in_array(\auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_OPERARIO])) {
            abort(401, 'No autorizado');
        }
        if ($request->has('ajax-datatable')) {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
                    'pedidos.id',
                    DB::raw(" (CASE WHEN pedidos.id<10 THEN concat('PED000',pedidos.id)
                                WHEN pedidos.id<100 THEN concat('PED00',pedidos.id)
                                WHEN pedidos.id<1000 THEN concat('PED0',pedidos.id)
                                ELSE concat('PED',pedidos.id) END) AS id2"),
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.pendiente_anulacion',
                    'pedidos.condicion',
                    'pedidos.condicion_code',
                    DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                    //DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%Y-%m-%d")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                    //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion',
                    'dp.tipo_banca',
                    'pedidos.motivo',
                    DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
                ])
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.pendiente_anulacion', '1');

            return datatables()->query(DB::table($pedidos))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) {
                    $btn = '';
                    if ($pedido->pendiente_anulacion == 1) {
                        $btn .= '<button data-toggle="modal" data-target="#modal_confirmar_anular" data-confirm_anular_pedido="' . $pedido->id . '"  data-pedido_id="' . $pedido->id . '" data-pedido_motivo="' . $pedido->motivo . '" data-pedido_id_code="' . Pedido::generateIdCode($pedido->id) . '" type="button" class="btn btn-danger btn-sm" >EMITIR N/C</button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'action2'])
                ->toJson();
        }

        return view('pedidos.status.anulados');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
