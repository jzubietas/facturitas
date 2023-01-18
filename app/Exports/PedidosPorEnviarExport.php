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
            ->select([
                'pedidos.id',
                'pedidos.cliente_id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'u.id as user_id',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion',
                'pedidos.created_at as fecha',
                'pedidos.condicion_envio',
                'pedidos.envio',
                'pedidos.codigo',
                'pedidos.destino',
                'pedidos.direccion',
                'pedidos.da_confirmar_descarga',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion',
                'pedidos.devuelto',
                'pedidos.cant_devuelto',
                'pedidos.returned_at',
                'pedidos.observacion_devuelto',
                'pedidos.estado_sobre',
                'pedidos.estado_ruta',
                'pedidos.pendiente_anulacion',
                'pedidos.estado',
            ])
            ->where('pedidos.estado', '1')
            ->whereIn('pedidos.condicion_envio_code', [
                Pedido::EN_ATENCION_OPE_INT,
                Pedido::POR_ATENDER_OPE_INT,Pedido::ATENDIDO_OPE_INT,Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                Pedido::RECIBIDO_JEFE_OPE_INT,
                Pedido::RECEPCION_COURIER_INT,
            ])
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta])
            ->sinDireccionEnvio()
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
