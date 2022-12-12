<?php

namespace App\Exports;
/*use App\Models\User;
use Carbon\Carbon;*/
use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PedidosOperacionesExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidos($request) {

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')

            ->select(
                'pedidos.id',
                'dpe.fecha_envio_doc_fis',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dpe.codigo as codigos',
                'dpe.nombre_empresa as empresas',
                'dpe.cantidad as total',
                'pedidos.condicion',
                DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d")) as fecha'),
                'dpe.envio_doc',
                'dpe.fecha_envio_doc',
                'dpe.cant_compro',
                'dpe.fecha_envio_doc_fis',
                'dpe.fecha_recepcion',
                'dpe.atendido_por',
                'dpe.descripcion',
                'dpe.ruc',
                'dpe.mes',
                'dpe.tipo_banca'
            )
            ->where('pedidos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('pedidos.condicion',[Pedido::ATENDIDO])
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]);


            if(Auth::user()->rol == "Administrador"){

            }else if(Auth::user()->rol == "Jefe de Operaciones"){
                $operarios = User::where('users.rol', 'Operario')
                -> where('users.estado', '1')
                -> where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                ->WhereIn('users.operario',$operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
                $pedidos=$pedidos->WhereIn('u.identificador',$asesores);

            }else if(Auth::user()->rol == "Operario"){
                $asesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
                $pedidos=$pedidos->WhereIn('u.identificador',$asesores);
            }else{
            }
            $pedidos = $pedidos -> get();
            $this->pedidos = $pedidos;
            return $this;
    }

    public function view(): View {
        return view('reportes.PedidosOperacionesExcel', [
            'pedidos'=> $this->pedidos,
        ]);
    }
}

/*class PedidosOperacionesExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidos($request) {

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'dpe.fecha_envio_doc_fis',
                'u.identificador as users',
                'dpe.codigo as codigos',
                'dpe.nombre_empresa as empresas',
                'dpe.ruc',
                'dpe.mes',
                'dpe.tipo_banca',
                'dpe.total',
                'dpe.atendido_por',
                'dpe.cant_compro',
                'dpe.envio_doc',
                'dpe.fecha_envio_doc',
                'dpe.fecha_recepcion',
                'dpe.descripcion',
                'pa.total_cobro',
                'pa.total_pagado'
            )
            ->where('pedidos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('pedidos.pago', '1')
            ->where('pedidos.condicion', 3)
            ->where('u.jefe', Auth::user()->id) // Filtrar por operarios a cargo del jefe actual
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'dpe.fecha_envio_doc_fis',
                'u.identificador',
                'dpe.codigo',
                'dpe.nombre_empresa',
                'dpe.ruc',
                'dpe.mes',
                'dpe.tipo_banca',
                'dpe.total',
                'dpe.atendido_por',
                'dpe.cant_compro',
                'dpe.envio_doc',
                'dpe.fecha_envio_doc',
                'dpe.fecha_recepcion',
                'dpe.descripcion',
                'pa.total_cobro',
                'pa.total_pagado'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $this->pedidos = $pedidos;

        return $this;
    }

    public function pedidos2($request) {

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dpe', 'pedidos.id', 'dpe.pedido_id')
            ->select(
                'pedidos.id',
                'dpe.fecha_envio_doc_fis',
                'u.identificador as users',
                'dpe.codigo as codigos',
                'dpe.nombre_empresa as empresas',
                'dpe.ruc',
                'dpe.mes',
                'dpe.tipo_banca',
                'dpe.total',
                'dpe.atendido_por',
                'dpe.cant_compro',
                'dpe.envio_doc',
                'dpe.fecha_envio_doc',
                'dpe.fecha_recepcion',
                'dpe.descripcion'
            )
            ->where('pedidos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('pedidos.pago', '0')
            ->where('pedidos.condicion', 3)
            ->where('u.jefe', Auth::user()->id) // Filtrar por operarios a cargo del jefe actual
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'dpe.fecha_envio_doc_fis',
                'u.identificador',
                'dpe.codigo',
                'dpe.nombre_empresa',
                'dpe.ruc',
                'dpe.mes',
                'dpe.tipo_banca',
                'dpe.total',
                'dpe.atendido_por',
                'dpe.cant_compro',
                'dpe.envio_doc',
                'dpe.fecha_envio_doc',
                'dpe.fecha_recepcion',
                'dpe.descripcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $this->pedidos2 = $pedidos2;

        return $this;
    }

    public function view(): View {
        return view('reportes.PedidosOperacionesExcel', [
            'pedidos'=> $this->pedidos,
            'pedidos2'=> $this->pedidos2
        ]);
    }
}*/
