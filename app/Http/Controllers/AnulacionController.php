<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AnulacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function pedidosanulaciones()
    {
        return view('pedidos.anulaciones.index');
    }





    public function modalsAnulacionPCSave(Request $request)
    {
        $codigopedido=$request->codigoCodigoPc;
        $idpedido=$request->codigoCodigoPcId;

        //grabar imagen



        $listado_codigo_pedido = Pedido::query()
            ->join('detalle_pedidos as dp', 'dp.codigo', 'pedidos.codigo')
            ->join('users as u', 'u.id', 'pedidos.user_id')
            ->where('u.rol', 'Asesor')
            ->where('pedidos.codigo',  $request->codigo)
            ->select([
                'pedidos.codigo',
                'u.name',
                'dp.cantidad as total',
                'dp.ruc',
                'dp.nombre_empresa',
                'dp.adjunto'
            ])
            ->first();
        return response()->json(['data'=>$listado_codigo_pedido]);
    }
}
