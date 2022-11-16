<?php

namespace App\Exports;

use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PedidosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidos($request) {       
        $mirol=Auth::user()->rol;

        if(Auth::user()->rol == "Llamadas")
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.ft',
                'dp.courier',
                'pedidos.condicion_envio as condicion_env',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                'pedidos.modificador',
                'dp.saldo as diferencia',//'pa.diferencia',
                'pedidos.estado',
                'pedidos.envio',
                )
            /* ->where('pedidos.estado', '1')
            ->where('dp.estado', '1') 
            ->where('pedidos.pago', '1') */
            //->where('pa.estado', '1')
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
            //->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->WhereIn('pedidos.user_id',$usersasesores)
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.ft',
                'dp.courier',
                'pedidos.condicion_envio',
                'pedidos.condicion',
                //'pa.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'pedidos.updated_at',
                'pedidos.modificador',
                //'pa.diferencia',
                'pedidos.estado',
                'pedidos.pagado',
                'dp.saldo',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }
        else if(Auth::user()->rol == "Jefe de llamadas")
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                    -> where('users.estado', '1')
                    -> where('users.llamada', Auth::user()->id)
                    ->select(
                        DB::raw("users.id as id")
                    )
                    ->pluck('users.id');

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                    ->join('users as u', 'pedidos.user_id', 'u.id')
                    ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                    ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                    ->select(
                        'pedidos.id',
                        'c.nombre as nombres',
                        'c.celular as celulares',
                        'u.identificador as users',
                        'pedidos.codigo as codigos',
                        'dp.nombre_empresa as empresas',
                        'dp.total as total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio as condicion_env',
                        'pedidos.condicion as condiciones',
                        'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                        DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                        'pedidos.motivo',
                        'pedidos.responsable',
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                        //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                        'pedidos.modificador',
                        'dp.saldo as diferencia',//'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.envio',
                        )
                    /* ->where('pedidos.estado', '1')
                    ->where('dp.estado', '1') 
                    ->where('pedidos.pago', '1') */
                    //->where('pa.estado', '1')
                    ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                    //->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->WhereIn('pedidos.user_id',$usersasesores)
                    ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->groupBy(
                        'pedidos.id',
                        'c.nombre',
                        'c.celular',
                        'u.identificador',
                        'pedidos.codigo',
                        'dp.nombre_empresa',
                        'dp.total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio',
                        'pedidos.condicion',
                        //'pa.condicion',
                        'pedidos.motivo',
                        'pedidos.responsable',
                        'pedidos.created_at',
                        'pedidos.updated_at',
                        'pedidos.modificador',
                        //'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.pagado',
                        'dp.saldo',
                        'pedidos.envio'
                        )
                    ->orderBy('pedidos.created_at', 'DESC')
                    ->get();
        }
        else if(Auth::user()->rol == "Asesor")
        {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                    ->join('users as u', 'pedidos.user_id', 'u.id')
                    ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                    ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                    ->select(
                        'pedidos.id',
                        'c.nombre as nombres',
                        'c.celular as celulares',
                        'u.identificador as users',
                        'pedidos.codigo as codigos',
                        'dp.nombre_empresa as empresas',
                        'dp.total as total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio as condicion_env',
                        'pedidos.condicion as condiciones',
                        'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                        DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                        'pedidos.motivo',
                        'pedidos.responsable',
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                        //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                        'pedidos.modificador',
                        'dp.saldo as diferencia',//'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.envio',
                        )
                    /* ->where('pedidos.estado', '1')
                    ->where('dp.estado', '1') 
                    ->where('pedidos.pago', '1') */
                    //->where('pa.estado', '1')
                    ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                    //->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->WhereIn('pedidos.user_id',Auth::user()->id)
                    ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->groupBy(
                        'pedidos.id',
                        'c.nombre',
                        'c.celular',
                        'u.identificador',
                        'pedidos.codigo',
                        'dp.nombre_empresa',
                        'dp.total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio',
                        'pedidos.condicion',
                        //'pa.condicion',
                        'pedidos.motivo',
                        'pedidos.responsable',
                        'pedidos.created_at',
                        'pedidos.updated_at',
                        'pedidos.modificador',
                        //'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.pagado',
                        'dp.saldo',
                        'pedidos.envio'
                        )
                    ->orderBy('pedidos.created_at', 'DESC')
                    ->get();

        }
        else if(Auth::user()->rol == "Super asesor")
        {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                    ->join('users as u', 'pedidos.user_id', 'u.id')
                    ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                    ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                    ->select(
                        'pedidos.id',
                        'c.nombre as nombres',
                        'c.celular as celulares',
                        'u.identificador as users',
                        'pedidos.codigo as codigos',
                        'dp.nombre_empresa as empresas',
                        'dp.total as total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio as condicion_env',
                        'pedidos.condicion as condiciones',
                        'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                        DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                        'pedidos.motivo',
                        'pedidos.responsable',
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                        //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                        'pedidos.modificador',
                        'dp.saldo as diferencia',//'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.envio',
                        )
                    /* ->where('pedidos.estado', '1')
                    ->where('dp.estado', '1') 
                    ->where('pedidos.pago', '1') */
                    //->where('pa.estado', '1')
                    ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                    //->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->WhereIn('pedidos.user_id',Auth::user()->id)
                    ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->groupBy(
                        'pedidos.id',
                        'c.nombre',
                        'c.celular',
                        'u.identificador',
                        'pedidos.codigo',
                        'dp.nombre_empresa',
                        'dp.total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio',
                        'pedidos.condicion',
                        //'pa.condicion',
                        'pedidos.motivo',
                        'pedidos.responsable',
                        'pedidos.created_at',
                        'pedidos.updated_at',
                        'pedidos.modificador',
                        //'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.pagado',
                        'dp.saldo',
                        'pedidos.envio'
                        )
                    ->orderBy('pedidos.created_at', 'DESC')
                    ->get();

        }
        else if(Auth::user()->rol == "Encargado")
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                    ->join('users as u', 'pedidos.user_id', 'u.id')
                    ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                    ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                    ->select(
                        'pedidos.id',
                        'c.nombre as nombres',
                        'c.celular as celulares',
                        'u.identificador as users',
                        'pedidos.codigo as codigos',
                        'dp.nombre_empresa as empresas',
                        'dp.total as total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio as condicion_env',
                        'pedidos.condicion as condiciones',
                        'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                        DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                        'pedidos.motivo',
                        'pedidos.responsable',
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                        //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                        'pedidos.modificador',
                        'dp.saldo as diferencia',//'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.envio',
                        )
                    /* ->where('pedidos.estado', '1')
                    ->where('dp.estado', '1') 
                    ->where('pedidos.pago', '1') */
                    //->where('pa.estado', '1')
                    ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                    //->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->WhereIn('pedidos.user_id',$usersasesores)
                    ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->groupBy(
                        'pedidos.id',
                        'c.nombre',
                        'c.celular',
                        'u.identificador',
                        'pedidos.codigo',
                        'dp.nombre_empresa',
                        'dp.total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio',
                        'pedidos.condicion',
                        //'pa.condicion',
                        'pedidos.motivo',
                        'pedidos.responsable',
                        'pedidos.created_at',
                        'pedidos.updated_at',
                        'pedidos.modificador',
                        //'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.pagado',
                        'dp.saldo',
                        'pedidos.envio'
                        )
                    ->orderBy('pedidos.created_at', 'DESC')
                    ->get();
        }else{
            
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                    ->join('users as u', 'pedidos.user_id', 'u.id')
                    ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                    ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                    ->select(
                        'pedidos.id',
                        'c.nombre as nombres',
                        'c.celular as celulares',
                        'u.identificador as users',
                        'pedidos.codigo as codigos',
                        'dp.nombre_empresa as empresas',
                        'dp.total as total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio as condicion_env',
                        'pedidos.condicion as condiciones',
                        'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                        DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                        'pedidos.motivo',
                        'pedidos.responsable',
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                        //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha_mod'),
                        'pedidos.modificador',
                        'dp.saldo as diferencia',//'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.envio',
                        )
                    /* ->where('pedidos.estado', '1')
                    ->where('dp.estado', '1') 
                    ->where('pedidos.pago', '1') */
                    //->where('pa.estado', '1')
                    ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                    //->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    //->WhereIn('pedidos.user_id',$usersasesores)
                    ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
                    ->groupBy(
                        'pedidos.id',
                        'c.nombre',
                        'c.celular',
                        'u.identificador',
                        'pedidos.codigo',
                        'dp.nombre_empresa',
                        'dp.total',
                        'dp.cantidad',
                        'dp.tipo_banca',
                        'dp.porcentaje',
                        'dp.ft',
                        'dp.courier',
                        'pedidos.condicion_envio',
                        'pedidos.condicion',
                        //'pa.condicion',
                        'pedidos.motivo',
                        'pedidos.responsable',
                        'pedidos.created_at',
                        'pedidos.updated_at',
                        'pedidos.modificador',
                        //'pa.diferencia',
                        'pedidos.estado',
                        'pedidos.pagado',
                        'dp.saldo',
                        'pedidos.envio'
                        )
                    ->orderBy('pedidos.created_at', 'DESC')
                    ->get();
            
        }

        $this->pedidos = $pedidos;
        return $this;
    }
    


    public function view(): View {
        return view('pedidos.excel.pedidos', [
            'pedidos'=> $this->pedidos/* ,
            'pedidos2' => $this->pedidos2 */
        ]);
    }

}