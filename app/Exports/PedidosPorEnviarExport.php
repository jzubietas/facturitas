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

class PedidosPorEnviarExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidosLima($request) {
        $pedidosLima = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('distritos as di', 'di.distrito', 'pedidos.env_distrito')
            ->select([
                'pedidos.id',
                'u.identificador as identificador_asesor',
                'u.name as nombre_asesor',
                'pedidos.codigo as codigo',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha_registro'),
                'c.nombre as nombre_cliente',
                'c.icelular as icelular_cliente',
                'c.celular as celular_cliente',
                'dp.nombre_empresa as empresa',
                'dp.cantidad as cantidad',
                'dp.fecha_envio_doc as fecha_elaboracion',
                'pedidos.env_distrito as distrito',
                'pedidos.env_direccion as direccion',
                'pedidos.env_referencia as referencia',
                'pedidos.env_nombre_cliente_recibe as nombre_recibe',
                'pedidos.env_celular_cliente_recibe as celular_contacto',
                'pedidos.env_zona as zona',
                'pedidos.condicion as estado_pedido',
                'pedidos.condicion_envio as estado_envio'
            ])
            ->where('pedidos.estado', '1')
            //->where('dp.estado', '1')
            //->where('pedidos.envio', '<>', '0')
            //->where('pedidos.direccion', '1')
            ->where('pedidos.destino', 'LIMA')
            //->where('di.provincia', 'LIMA')
            ->whereIn('pedidos.condicion_envio_code', [
                Pedido::EN_ATENCION_OPE_INT,
                Pedido::POR_ATENDER_OPE_INT,Pedido::ATENDIDO_OPE_INT,Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                Pedido::RECIBIDO_JEFE_OPE_INT,
                Pedido::RECEPCION_COURIER_INT,]
            )
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta])
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $this->pedidosLima = $pedidosLima;
        return $this;
    }
    public function view(): View {
        return view('pedidos.excel.pedidosporenviar', [
            'pedidosLima'=> $this->pedidosLima
        ]);
    }

}
