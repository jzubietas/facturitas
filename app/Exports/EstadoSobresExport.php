<?php

namespace App\Exports;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EstadoSobresExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidosLima($request) {


        $pedidos=null;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')

                ->select(
                    'pedidos.id',
                    'pedidos.cliente_id',
                    // 'c.nombre as nombres',
                    // 'c.celular as celulares',
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
                    DB::raw("DATEDIFF(DATE(NOW()), DATE(pedidos.created_at)) AS dias")
                )
                ->where('pedidos.estado', '1')
                //->whereIn('pedidos.envio', [Pedido::ENVIO_CONFIRMAR_RECEPCION,Pedido::ENVIO_RECIBIDO]) // ENVIADO CONFIRMAR RECEPCION Y ENVIADO RECIBIDO
                ->whereIn('pedidos.condicion_envio_code', [Pedido::RECEPCION_COURIER_INT]) // ENVIADO CONFIRMAR RECEPCION Y ENVIADO RECIBIDO

                ->where('dp.estado', '1')->get();


        $this->pedidosLima = $pedidos;
        return $this;
    }

    /*
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
            ->whereIn('pedidos.condicion_envio', [2])//1,
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
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
    */

    public function view(): View {
        return view('envios.excel.sobresporenviar', [
            'pedidosLima'=> $this->pedidosLima,

        ]);
    }

}
