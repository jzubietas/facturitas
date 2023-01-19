<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PedidoMotorizadoHistory;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use DataTables;

class EscaneoController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }
    public function EscaneoQR(Request $request)
    {
        $pedido = Pedido::where("codigo", $request->id)->firstOrFail();

        return response()->json(['html' => $pedido->codigo, 'distrito' => $pedido->distrito, 'direccion' => $pedido->direccion]);
    }

    public function EstadoSobresScan(Request $request)
    {
        $detalle_pedido = Pedido::where('codigo',$request->codigo)->first();

        return response()->json(['pedido' => $detalle_pedido]);
    }


}
