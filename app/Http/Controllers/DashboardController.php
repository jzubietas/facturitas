<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Ruc;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $mytime = Carbon::now('America/Lima');
        $afecha = $mytime->year;
        $mfecha = $mytime->month;
        $dfecha = $mytime->day;

        //DASHBOARD ADMINISTRADOR
        $pedidoxmes_total = User::select(DB::raw('sum(users.meta_pedido) as total'))//META PEDIDOS
        ->where('users.rol', "ENCARGADO")
            ->where('users.estado', '1')
            /* ->whereMonth('pedidos.created_at', $mfecha) */
            ->get();

        if (Auth::user()->id == "33") {
            $pagoxmes_total = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')//CANTIDAD DE PEDIDOS DEL MES
            ->join('users as u', 'pedidos.user_id', 'u.id')
                ->select(DB::raw('count(dp.id) as pedidos'))
                ->whereMonth('dp.created_at', $mfecha)
                ->whereYear('dp.created_at', $afecha)
                ->get();
        } else {
            $pagoxmes_total = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')//CANTIDAD DE PEDIDOS DEL MES
            ->join('users as u', 'pedidos.user_id', 'u.id')
                ->select(DB::raw('count(dp.id) as pedidos'))
                ->where('u.rol', "ASESOR")
                ->whereMonth('dp.created_at', $mfecha)
                ->whereYear('dp.created_at', $afecha)
                ->get();
        }
        //$montopedidoxmes_total = User::select(DB::raw('sum(users.meta_cobro) as total'))
        //META DE COBRANZAS DEL MES
        $montopedidoxmes_total = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users', 'pedidos.user_id', 'users.id')
            ->select(DB::raw('(sum(dp.total))/(count(dp.pedido_id)) as total'))
            //->where('users.rol', "ASESOR")
            ->where('users.estado', '1')
            ->where('pedidos.created_at', $mfecha)
            ->whereYear('pedidos.created_at', $afecha)
            ->get();
        //return $montopedidoxmes_total;
        if (Auth::user()->id == "33") {
            $montopagoxmes_total = Pago::join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')//CANTIDAD DE PAGOS DEL MES
            ->select(DB::raw('sum(dpa.monto) as total'))
                ->where('pagos.estado', '1')
                ->where('dpa.estado', '1')
                ->whereMonth('dpa.created_at', $mfecha)
                ->whereYear('dpa.created_at', $afecha)
                ->get();
        } else {
            $montopagoxmes_total = Pago::join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')//CANTIDAD DE PAGOS DEL MES
            ->join('users as u', 'pagos.user_id', 'u.id')
                ->select(DB::raw('sum(dpa.monto) as total'))
                ->where('u.rol', 'ASESOR')
                ->where('pagos.estado', '1')
                ->where('dpa.estado', '1')
                ->whereMonth('dpa.created_at', $mfecha)
                ->whereYear('dpa.created_at', $afecha)
                ->get();
        }
        //GRAFICO DE BARRAS IMPORTE/PEDIDOS
        $cobranzaxmes = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->select(
                'u.identificador as usuarios',
                DB::raw('((sum(dp.total)/count(dp.id))) as total'))
            //->whereIn('u.rol', ['ENCARGADO', 'Super asesor','ASESOR'])
            ->whereMonth('dp.created_at', $mfecha)
            ->whereYear('dp.created_at', $afecha)
            ->groupBy('u.identificador')
            //->orderBy((DB::raw('count(dp.id)')), 'DESC')
            ->get();
        //return $cobranzaxmes;
        //PEDIDOS POR ASESOR EN EL MES
        $pedidosxasesor = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->select('u.identificador as users', DB::raw('count(dp.id) as pedidos'))
            ->whereIn('u.rol', ['ASESOR', 'Super asesor'])
            ->whereMonth('dp.created_at', $mfecha)
            ->whereYear('dp.created_at', $afecha)
            ->groupBy('u.identificador')
            ->orderBy((DB::raw('count(dp.id)')), 'DESC')
            ->get();

        //PEDIDOS X MES

        $pedidos_mes_ = Pedido::select(DB::raw('count(*) as total'))//META PEDIDOS
        ->whereMonth('created_at', $mfecha)
            ->get();


        //MONTO DE PAGO X CLIENTE EN EL MES TOP 30
        $pagosxmes = Pago::join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->join('users as u', 'pagos.user_id', 'u.id')
            ->select('c.nombre as cliente', DB::raw('sum(pagos.total_cobro) as pagos'))
            ->whereIn('u.rol', ['ASESOR', 'Super asesor'])
            ->where('pagos.estado', '1')
            ->whereMonth('pagos.created_at', $mfecha)
            ->whereYear('pagos.created_at', $afecha)
            ->groupBy('c.nombre')
            ->orderBy(DB::raw('sum(pagos.total_cobro)'), 'DESC')
            ->offset(0)
            ->limit(30)
            ->get();
        //PEDIDOS POR ASESOR EN EL DIA
        $pedidosxasesorxdia = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->select('u.name as users', DB::raw('count(dp.id) as pedidos'))
            ->whereIn('u.rol', ['ASESOR', 'Super asesor'])
            ->whereDay('dp.created_at', $dfecha)
            ->whereMonth('dp.created_at', $mfecha)
            ->whereYear('dp.created_at', $afecha)
            ->groupBy('u.name')
            ->orderBy((DB::raw('count(dp.id)')), 'DESC')
            ->get();
        //DASHBOARD ENCARGADO
        $meta_pedidoencargado = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->where('u.supervisor', Auth::user()->id)
            ->where('pedidos.estado', '1')
            ->whereMonth('pedidos.created_at', $mfecha)
            ->whereYear('pedidos.created_at', $afecha)
            ->count();
        $meta_pagoencargado = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->select(DB::raw('sum(dpa.monto) as pagos'))
            ->where('u.supervisor', Auth::user()->id)
            ->where('pagos.estado', '1')
            ->whereMonth('pagos.created_at', $mfecha)
            ->whereYear('pagos.created_at', $afecha)
            ->first();
        //PEDIDOS DE MIS ASESORES EN EL MES
        $pedidosxasesor_encargado = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->select('u.name as users', DB::raw('count(dp.id) as pedidos'))
            ->where('u.supervisor', Auth::user()->id)
            ->whereMonth('dp.created_at', $mfecha)
            ->whereYear('dp.created_at', $afecha)
            ->groupBy('u.name')
            ->orderBy((DB::raw('count(dp.id)')), 'DESC')
            ->get();
        //HISTORIAL DE PEDIDOS DE MIS ASESORES EN LOS ULTIMOS 3 MES
        $pedidosxasesor_3meses_encargado = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->select('u.name as users', DB::raw('count(pedidos.id) as pedidos'), DB::raw('DATE(pedidos.created_at) as fecha'))
            ->where('u.supervisor', Auth::user()->id)
            ->whereDay('pedidos.created_at', $dfecha)
            ->WhereIn(DB::raw('DATE_FORMAT(pedidos.created_at, "%m")'), [$mfecha, $mfecha - 1, $mfecha - 2])
            ->whereYear('pedidos.created_at', $afecha)
            ->groupBy('u.name', 'u.id', DB::raw('DATE(pedidos.created_at)'))
            ->orderBy('u.id', 'ASC')
            ->get();
        //MONTO DE PAGO X CLIENTE DE MIS ASESORES EN EL MES TOP 30
        $pagosxmes_encargado = Pago::join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->join('users as u', 'pagos.user_id', 'u.id')
            ->select('c.nombre as cliente', DB::raw('sum(pagos.total_cobro) as pagos'))
            ->where('u.supervisor', Auth::user()->id)
            ->where('pagos.estado', '1')
            ->whereMonth('pagos.created_at', $mfecha)
            ->whereYear('pagos.created_at', $afecha)
            ->groupBy('c.nombre')
            ->offset(0)
            ->limit(30)
            ->get();
        //PEDIDOS DE MIS ASESORES EN EL DIA
        $pedidosxasesorxdia_encargado = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->select('u.name as users', DB::raw('count(dp.id) as pedidos'))
            ->where('u.supervisor', Auth::user()->id)
            ->whereDay('dp.created_at', $dfecha)
            ->whereMonth('dp.created_at', $mfecha)
            ->whereYear('dp.created_at', $afecha)
            ->groupBy('u.name')
            ->orderBy((DB::raw('count(dp.id)')), 'DESC')
            ->get();
        //DASHBOARD ASESOR
        $meta_pedidoasesor = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->where('u.id', Auth::user()->id)
            ->where('pedidos.estado', '1')
            ->whereMonth('pedidos.created_at', $mfecha)
            ->whereYear('pedidos.created_at', $afecha)
            ->count();
        $meta_pagoasesor = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->select(DB::raw('sum(dpa.monto) as pagos'))
            ->where('u.id', Auth::user()->id)
            ->where('pagos.estado', '1')
            ->where('dpa.estado', '1')
            ->whereMonth('pagos.created_at', $mfecha)
            ->whereYear('pagos.created_at', $afecha)
            ->first();
        $pagosobservados_cantidad = Pago::where('user_id', Auth::user()->id)//PAGOS OBSERVADOS
        ->where('estado', '1')
            ->where('condicion', Pago::OBSERVADO)
            ->count();
        //HISTORIAL DE MIS PEDIDOS EN EL MES
        $pedidosxasesorxdia_asesor = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->select('u.name as users', DB::raw('count(pedidos.id) as pedidos'), DB::raw('DATE(pedidos.created_at) as fecha'))
            ->where('u.id', Auth::user()->id)
            ->whereMonth('pedidos.created_at', $mfecha)
            ->whereYear('pedidos.created_at', $afecha)
            ->groupBy('u.name', DB::raw('DATE(pedidos.created_at)'))
            ->orderBy(DB::raw('DATE(pedidos.created_at)'), 'ASC')
            ->get();
        //ALERTA DE PEDIDOS SIN PAGOS
        $pedidossinpagos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->where('pedidos.pago', '0')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        //DASHBOARD OPERACION
        $pedidoxatender = Pedido::where('condicion', 'REGISTRADO')
            ->where('pedidos.estado', '1')
            ->count();
        $pedidoenatencion = Pedido::where('condicion', 2)
            ->where('pedidos.estado', '1')
            ->count();
        //DASHBOARD ADMINISTRACION
        $pagosxrevisar_administracion = Pago::where('estado', '1')
            ->where('condicion', 'PAGO')
            ->count();
        $pagosobservados_administracion = Pago::where('estado', '1')
            ->where('condicion', Pago::OBSERVADO)
            ->count();
        //DASHBOARD Logística
        //sobres por enviar
        //sobres por recibir


        $conteo = count(auth()->user()->unreadNotifications);

        return view('dashboard.dashboard', compact('pedidoxmes_total',
                'pedidos_mes_',
                'pagoxmes_total',
                'montopedidoxmes_total',
                'montopagoxmes_total',
                'pedidossinpagos',
                'pedidosxasesor',
                'pagosxmes',
                'pedidosxasesorxdia',
                'meta_pedidoencargado',
                'meta_pagoencargado',
                'pedidosxasesor_encargado',
                'pedidosxasesor_3meses_encargado',
                'pagosxmes_encargado',
                'pedidosxasesorxdia_encargado',
                'meta_pedidoasesor',
                'meta_pagoasesor',
                'pagosobservados_cantidad',
                'pedidosxasesorxdia_asesor',
                'pedidoxatender',
                'pedidoenatencion',
                'pagosxrevisar_administracion',
                'pagosobservados_administracion',
                'conteo',
                'cobranzaxmes',

            )
        );
    }

    public function searchCliente(Request $request)
    {
        $q = $request->get("q");
        $clientes = Cliente::query()
            ->with(['user', 'rucs', 'porcentajes'])
            ->where('celular', 'like', '%' . $q . '%')
            ->orwhere(DB::raw("concat(clientes.celular,'-',clientes.icelular)"), 'like', '%' . $q . '%')
            ->orWhere('nombre', 'like', '%' . join("%", explode(" ", trim($q))) . '%')
            ->orWhere('dni', 'like', '%' . $q . '%')
            ->limit(10)
            ->get()
            ->map(function (Cliente $cliente) {
                $cliente->deuda_total=DetallePedido::query()->whereIn('pedido_id',$cliente->pedidos()->where('estado','1')->pluck("id"))->sum("saldo");
                return $cliente;
            });

        return view('dashboard.searchs.search_cliente', compact('clientes'));
    }

    public function searchRuc(Request $request)
    {
        $q = $request->get("q");
        $rucs = Ruc::query()
            ->with(['cliente', 'user'])
            ->where('num_ruc', 'like', '%' . $q . '%')
            ->limit(10)
            ->get()
            ->map(function (Ruc $ruc) {
                $ruc->cliente->deuda_total=DetallePedido::query()->whereIn('pedido_id',$ruc->cliente->pedidos()->where('estado','1')->pluck("id"))->sum("saldo");
                return $ruc;
            });
        return view('dashboard.searchs.search_rucs', compact('rucs'));
    }
}
