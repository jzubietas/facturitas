<?php

namespace App\Exports;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PedidosPorEnviarPorFechasExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidosLima($request) {
        $pedidosLima = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('direccion_pedidos as dip', 'pedidos.id', 'dip.pedido_id')
            ->join('direccion_envios as die', 'dip.direccion_id', 'die.id')
            ->join('distritos as di', 'die.distrito', 'di.distrito')
            ->select(
                'pedidos.id',
                'u.identificador as id_asesor',
                'u.name as nombre_asesor',
                'pedidos.codigo as codigo',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha_registro'),
                'c.nombre as nombre_cliente',
                'c.icelular as icelular_cliente',
                'c.celular as celular_cliente',
                'dp.nombre_empresa as empresa',
                'dp.cantidad as cantidad',
                'dp.fecha_envio_doc as fecha_elaboracion',
                'die.distrito as distrito',
                'die.direccion as direccion',
                'die.referencia as referencia',
                'die.nombre as nombre_recibe',
                'die.celular as celular_contacto',
                'di.zona as zona',
                'pedidos.condicion as estado_pedido',
                'pedidos.condicion_envio as estado_envio'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.envio', '<>', '0')
            ->where('pedidos.direccion', '1')
            ->where('pedidos.destino', 'LIMA')
            ->where('di.provincia', 'LIMA')
            ->whereIn('pedidos.condicion_envio', [2])//1,
            ->whereBetween(DB::raw('DATE(dp.fecha_recepcion)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'u.identificador',
                'u.name',
                'pedidos.codigo',
                'pedidos.created_at',
                'c.nombre',
                'c.icelular',
                'c.celular',
                'dp.nombre_empresa',
                'dp.cantidad',
                'dp.fecha_envio_doc',
                'die.distrito',
                'die.direccion',
                'die.referencia',
                'die.nombre',
                'die.celular',
                'di.zona',
                'pedidos.condicion',
                'pedidos.condicion_envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $this->pedidosLima = $pedidosLima;
        return $this;
    }


    public function pedidosProvincia($request) {
        $pedidosProvincia = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
        ->join('users as u', 'pedidos.user_id', 'u.id')
        ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
        ->join('gasto_pedidos as gp', 'pedidos.id', 'gp.pedido_id')
        ->join('gasto_envios as ge', 'gp.gasto_id', 'ge.id')
        ->select(
                'pedidos.id',
                'u.identificador as id_asesor',
                'u.name as nombre_asesor',
                'pedidos.codigo as codigo',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha_registro'),
                'c.nombre as nombre_cliente',
                'c.icelular as icelular_cliente',
                'c.celular as celular_cliente',
                'dp.nombre_empresa as empresa',
                'dp.cantidad as cantidad',
                'dp.fecha_envio_doc as fecha_elaboracion',
                'ge.tracking as tracking',
                'ge.registro as registro',
                'ge.importe as importe',
                'pedidos.condicion as estado_pedido',
                'pedidos.condicion_envio as estado_envio'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.envio', '<>', '0')
            ->where('pedidos.direccion', '1')
            ->where('pedidos.destino', 'PROVINCIA')
            ->whereIn('pedidos.condicion_envio', [2])//1
            ->whereBetween(DB::raw('DATE(dp.fecha_recepcion)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'u.identificador',
                'u.name',
                'pedidos.codigo',
                'pedidos.created_at',
                'c.nombre',
                'c.icelular',
                'c.celular',
                'dp.nombre_empresa',
                'dp.cantidad',
                'dp.fecha_envio_doc',
                'ge.tracking',
                'ge.registro',
                'ge.importe',
                'pedidos.condicion',
                'pedidos.condicion_envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $this->pedidosProvincia = $pedidosProvincia;
        return $this;
    }

    public function view(): View {
        return view('pedidos.excel.pedidosporenviar', [
            'pedidosLima'=> $this->pedidosLima,
            'pedidosProvincia' => $this->pedidosProvincia
        ]);
    }
}
