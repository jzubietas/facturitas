<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class PdfController extends Controller
{
    public function index()
    {
        $users = User::where('estado', '1')->pluck('name', 'id');

        return view('reportes.index', compact('users'));
    }

    public function MisAsesores()
    {
        $users = User::where('estado', '1')
                    ->where('supervisor', Auth::user()->id)
                    ->pluck('name', 'id');

        return view('reportes.misasesores', compact('users'));
    }

    public function Operaciones()
    {
        $users = User::where('estado', '1')->pluck('name', 'id');

        return view('reportes.operaciones', compact('users'));
    }

    public function PedidosPorFechas(Request $request)
    {
        $fecha = Carbon::now('America/Lima')->format('d-m-Y');
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO'])
            ->where('pedidos.pago', '0')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
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

        $pdf = PDF::loadView('reportes.PedidosPorFechasPDF', compact('pedidos', 'pedidos2', 'fecha', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pedidos desde ' . $request->desde . ' hasta ' . $request->hasta . '.pdf');
    }

    public function PedidosPorAsesor(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', $request->user_id)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
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
            ->where('u.id', $request->user_id)
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO'])
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

        $pdf = PDF::loadView('reportes.PedidosPorAsesorPDF', compact('pedidos', 'pedidos2', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pedidos del asesor' . $request->desde . '.pdf');
    }

    public function PedidosPorAsesores(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('u.id', [$request->user_id1, $request->user_id2, $request->user_id3, $request->user_id4])
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
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
            ->whereIn('u.id', [$request->user_id1, $request->user_id2, $request->user_id3, $request->user_id4])
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO'])
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

        $pdf = PDF::loadView('reportes.PedidosPorAsesoresPDF', compact('pedidos', 'pedidos2', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pedidos del asesor' . $request->desde . '.pdf');
    }

    public function PagosPorFechas(Request $request)
    {
        $fecha = Carbon::now('America/Lima')->format('d-m-Y');
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
        ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id') 
        ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
        ->rightjoin('pedidos as p', 'pp.pedido_id', 'p.id')
        ->rightjoin('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
        ->select('pagos.id',
                'dpe.codigo as codigos',
                'u.name as users',
                'pagos.observacion',
                'dpe.total as total_deuda',
                'pagos.total_cobro',
                DB::raw('sum(dpa.monto) as total_pago'),
                'pagos.condicion',
                'pagos.created_at as fecha'
                )
        ->where('pagos.estado', '1')
        ->where('dpe.estado', '1')
        ->where('dpa.estado', '1')
        ->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
        ->groupBy('pagos.id',
                'dpe.codigo',
                'u.name',
                'pagos.observacion','dpe.total',
                'pagos.total_cobro',
                'pagos.condicion',
                'pagos.created_at')
        ->get();

        $pdf = PDF::loadView('reportes.PagosPorFechasPDF', compact('pagos', 'fecha', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pagos desde ' . $request->desde . ' hasta ' . $request->hasta . '.pdf');
    }

    public function PagosPorAsesor(Request $request)
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
        ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
        ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
        ->join('pedidos as p', 'pp.pedido_id', 'p.id')
        ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
        ->select('pagos.id', 
                'dpe.codigo as codigos', 
                'u.name as users', 
                'pagos.observacion', 
                'dpe.total as total_deuda',
                DB::raw('sum(dpa.monto) as total_pago'), 
                'pagos.condicion',                   
                'pagos.created_at as fecha'
                )
        ->where('pagos.estado', '1')
        ->where('dpe.estado', '1')
        ->where('dpa.estado', '1')
        ->where('u.id', $request->user_id)
        ->groupBy('pagos.id', 
                'dpe.codigo', 
                'u.name',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion', 
                'pagos.created_at')
        ->get();

        $pdf = PDF::loadView('reportes.PagosPorAsesorPDF', compact('pagos', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pago por asesor.pdf');
    }

    public function PagosPorAsesores(Request $request)
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
        ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
        ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
        ->join('pedidos as p', 'pp.pedido_id', 'p.id')
        ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
        ->select('pagos.id', 
                'dpe.codigo as codigos', 
                'u.name as users', 
                'pagos.observacion', 
                'dpe.total as total_deuda',
                DB::raw('sum(dpa.monto) as total_pago'), 
                'pagos.condicion',                   
                'pagos.created_at as fecha'
                )
        ->where('pagos.estado', '1')
        ->where('dpe.estado', '1')
        ->where('dpa.estado', '1')
        ->whereIn('u.id', [$request->user_id1, $request->user_id2, $request->user_id3, $request->user_id4])
        ->groupBy('pagos.id', 
                'dpe.codigo', 
                'u.name',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion', 
                'pagos.created_at')
        ->get();

        $pdf = PDF::loadView('reportes.PagosPorAsesoresPDF', compact('pagos', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pago por asesores.pdf');
    }

    public function ticketVentaPDF(Pedido $venta)
    {
        $fecha = Carbon::now();
        $ventas = Pedido::join('clientes as c', 'ventas.cliente_id', 'c.id')
            ->join('users as u', 'ventas.user_id', 'u.id')
            ->join('detalle_ventas as dv', 'ventas.id', 'dv.venta_id')
            ->select(
                'ventas.id',
                'c.nombre as clientes',
                'u.name as users',
                'ventas.tipo_comprobante',
                DB::raw('sum(dv.cantidad*dv.precio) as total'),
                'ventas.created_at as fecha',
                'ventas.estado'
            )
            ->where('ventas.id', $venta->id)
            ->groupBy(
                'ventas.id',
                'c.nombre',
                'u.name',
                'ventas.tipo_comprobante',
                'ventas.created_at',
                'ventas.estado'
            )
            ->get();
        $detalleVentas = DetallePedido::join('articulos as a', 'detalle_ventas.articulo_id', 'a.id')
            ->select(
                'detalle_ventas.id',
                'a.nombre as articulos',
                'detalle_ventas.cantidad',
                'detalle_ventas.precio',
                DB::raw('detalle_ventas.cantidad*detalle_ventas.precio as subtotal'),
                'detalle_ventas.estado'
            )
            ->where('detalle_ventas.estado', '1')
            ->where('detalle_ventas.venta_id', $venta->id)
            ->get();

        /* $pdf = PDF::loadView('ventas.reportes.ticketPDF', compact('ventas', 'detalleVentas', 'fecha'))->setPaper('a4')/* ->setPaper(array(0,0,220,500), 'portrait') ;*/
        /* return $pdf->stream('productos ingresados.pdf'); */
        return view('ventas.reportes.ticketPDF', compact('ventas', 'detalleVentas', 'fecha'));
    }

    public function pedidosPDFpreview(Request $request)
    {
        $mirol=Auth::user()->rol;
        $identificador=Auth::user()->identificador;
        $fecha = Carbon::now('America/Lima')->format('Y-m-d');

        $pruc=$request->pruc;
        $pempresa=$request->pempresa;
        $pmes=$request->pmes;
        $panio=$request->panio;
        $pcantidad=$request->pcantidad;
        $ptipo_banca=$request->ptipo_banca;
        $pdescripcion=$request->pdescripcion;
        $pnota=$request->pnota;

        $pdf = PDF::loadView('pedidos.reportes.pedidosPDFpreview', compact('fecha','mirol','identificador','pruc','pempresa','pmes','panio','pcantidad','ptipo_banca','pdescripcion','pnota'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream('pedido ' . 'id' . '.pdf');

    }

    public function pedidosPDF(Pedido $pedido)
    {
        $mirol=Auth::user()->rol;
        $identificador=Auth::user()->identificador;

        //para pedidos anulados y activos
        $fecha = Carbon::now('America/Lima')->format('Y-m-d');

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
        ->join('users as u', 'pedidos.user_id', 'u.id')
        ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.mes',
                'dp.anio',
                'dp.ruc',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.courier',
                'dp.ft',
                'dp.descripcion',
                'dp.nota',
                'dp.total',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            //->where('pedidos.estado', '1')
            ->where('pedidos.id', $pedido->id)
            //->where('dp.estado', '1')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.mes',
                'dp.anio',
                'dp.ruc',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.courier',
                'dp.ft',
                'dp.descripcion',
                'dp.nota',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
                
        $pdf = PDF::loadView('pedidos.reportes.pedidosPDF', compact('pedidos', 'fecha','mirol','identificador'))
            ->setPaper('a4', 'portrait');
        //$canvas = PDF::getDomPDF(); 
        //return $canvas;
        return $pdf->stream('pedido ' . $pedido->id . '.pdf');
    }
}
