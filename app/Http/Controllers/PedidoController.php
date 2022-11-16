<?php

namespace App\Http\Controllers;

use App\Events\PedidoAtendidoEvent;
use App\Events\PedidoEntregadoEvent;
use App\Events\PedidoEvent;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\DireccionEnvio;
use App\Models\DireccionPedido;
use App\Models\Distrito;
use App\Models\GastoEnvio;
use App\Models\GastoPedido;
use App\Models\ImagenAtencion;
use App\Models\ImagenPedido;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Porcentaje;
use App\Models\Provincia;
use App\Models\Ruc;
use App\Notifications\PedidoNotification;
use Carbon\Carbon;
use Exception;
use Facade\FlareClient\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PedidoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $mirol=Auth::user()->rol;
        $miidentificador=Auth::user()->name;

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.index', compact('dateMin', 'dateMax', 'superasesor','mirol','miidentificador'));
    }

    public function indextablahistorial(Request $request)
    {
        //return $request->buscarpedidocliente;
        if (!$request->buscarpedidocliente && !$request->buscarpedidoruc ) {
            $pedidos=Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->join('imagen_pedidos as ip', 'pedidos.id', 'ip.pedido_id')
                ->select(
                    'pedidos.id',
                    'dp.descripcion',
                    'dp.nota',
                    'ip.adjunto'
                )
                ->where('dp.estado', '3')
                ->where('pedidos.estado', '1')
                //->where('pedidos.cliente_id',$request->buscarpedidocliente)
                //->where('dp.ruc',$request->buscarpedidoruc)
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
                return Datatables::of($pedidos)
                ->addIndexColumn()
                ->make(true);
        }else{
            $pedidos=Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('imagen_pedidos as ip', 'pedidos.id', 'ip.pedido_id')
            ->select(
                'pedidos.id',
                'dp.descripcion',
                'dp.nota',
                'ip.adjunto'
            )
            ->where('dp.estado', '1')
            ->where('pedidos.estado', '1')
            ->where('pedidos.cliente_id',$request->buscarpedidocliente)
            ->where('dp.ruc',$request->buscarpedidoruc)
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        return Datatables::of($pedidos)
                ->addIndexColumn()
                ->make(true);
        }
    }

    

    public function indextabla(Request $request)
    {
        $mirol=Auth::user()->rol;
        //return Auth::user()->rol ;
        //return Auth::user()->id;
        if(Auth::user()->rol == "Llamadas"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');            
            

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
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
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',
                'pedidos.estado',
                'pedidos.envio',
            )
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
            ->WhereIn('pedidos.user_id',$usersasesores)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.condicion_envio',
                'pedidos.pagado',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'dp.saldo',
                'pedidos.estado',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }
        else if(Auth::user()->rol == "Jefe de llamadas"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
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
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',
                'pedidos.estado',
                'pedidos.envio',
            )
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
            ->WhereIn('pedidos.user_id',$usersasesores)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.condicion_envio',
                'pedidos.pagado',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'dp.saldo',
                'pedidos.estado',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }
        else if(Auth::user()->rol == "Asesor"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            //->leftjoin('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',//para pedido con pago
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                /*'pedidos.created_at as fecha',*/
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',//'pa.diferencia',//para pedido con pago
                'pedidos.estado',
                'pedidos.envio',
            )
            /* ->where('pedidos.estado', '1') */
            /* ->where('dp.estado', '1') */
            //->where('pp.estado', '1')
            //->where('pedidos.pago', '1') 0 sin pago  1 con pago
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])//agregado para regularizar
            //->where('pa.estado', '1') 0 sin pago 1 con pago
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.condicion_envio',
                //'pa.condicion',
                'pedidos.pagado',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                //'pa.diferencia',
                'dp.saldo',
                'pedidos.estado',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }else if(Auth::user()->rol == "Super asesor"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            //->leftjoin('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',//para pedido con pago
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                /*'pedidos.created_at as fecha',*/
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',//'pa.diferencia',//para pedido con pago
                'pedidos.estado',
                'pedidos.envio'
            )
            /* ->where('pedidos.estado', '1') */
            /* ->where('dp.estado', '1') */
            //->where('pedidos.pago', '1') 0 sin pago  1 con pago
            //->where('pp.estado', '1')
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])//agregado para regularizar
            //->where('pa.estado', '1') 0 sin pago 1 con pago
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.condicion_envio',
                //'pa.condicion',
                'pedidos.pagado',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                //'pa.diferencia',
                'dp.saldo',
                'pedidos.estado',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        }else if(Auth::user()->rol == "Encargado"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');  

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
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
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',
                'pedidos.estado',
                'pedidos.envio'
            )
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
            ->WhereIn('pedidos.user_id',$usersasesores)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.condicion_envio',
                'pedidos.pagado',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'dp.saldo',
                'pedidos.estado',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        }else{
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            //->leftjoin('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',//para pedido con pago
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                /*'pedidos.created_at as fecha',*/
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',//'pa.diferencia',//para pedido con pago
                'pedidos.estado',
                'pedidos.envio'
            )
            /* ->where('pedidos.estado', '1') */
            /* ->where('dp.estado', '1') */
            //->where('pedidos.pago', '1') 0 sin pago  1 con pago
            //->where('pp.estado', '1')
            ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])//agregado para regularizar
            //->where('pa.estado', '1') 0 sin pago 1 con pago
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'pedidos.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.condicion_envio',
                //'pa.condicion',
                'pedidos.pagado',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                //'pa.diferencia',
                'dp.saldo',
                'pedidos.estado',
                'pedidos.envio'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        }

        return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){     
                        $btn='';
                        //$btn='<a href="'.route('pedidosPDF', $pedido).'" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                        //$btn=$btn.'<a href="'.route('pedidos.show', $pedido).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> VER</a>';

                        /*if($pedido->estado>0){

                            if(Auth::user()->rol == "Super asesor" || Auth::user()->rol =="Administrador" || Auth::user()->rol == "Encargado")
                            {
                                $btn=$btn.'<a href="'.route('pedidos.edit', $pedido->id).'" class="btn btn-warning btn-sm">Editar</a>';
                            }                            

                                if(Auth::user()->rol =='Administrador')
                                {
                                    $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pedido->id.'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>';
                                }
                                                    
                        }*/

                        /*if($pedido->estado==0){
                            if(Auth::user()->rol =='Administrador')
                            {
                                $btn = $btn.'<a href="" data-target="#modal-restaurar" data-toggle="modal" data-restaurar="'.$pedido->id.'"><button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Restaurar</button></a>';
                            }
                            

                        }*/
                        
                        /*if($pedido->estado==1){
                            if($pedido->diferencia <= 3)
                            {
                                if(Auth::user()->rol =='Administrador')
                                {
                                    $btn = $btn.'<a href="" data-target="#modal-anularysaldo" data-toggle="modal" data-saldo="'.$pedido->id.'"><button class="btn btn-secondary btn-sm"><i class="fas fa-check"></i>Convertir a Saldo</button></a>';
                                }
                            }
                        }*/
                        
                        //$btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-opcion="'.$pedido->id.'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                           
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function deudoresoncreate(Request $request){
        $deudores = Cliente::where('estado', '1')
                                //->where('user_id', Auth::user()->id)
                                ->where('tipo', '1')
                                ->where('deuda', '1')
                                ->get();

        return Datatables::of($deudores)
            ->addIndexColumn()
            ->make(true);
        
        //return response()->json($deudores);                                
    }

    public function clientesenpedidos(Request $request){       
        $clientes1 = Cliente::
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')              
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.pidio',
                    'clientes.deuda'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        'clientes.deuda',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);

        return response()->json($clientes1);
    }

    public function clientesenruconcreate(Request $request){
        $clientes_ruc = Cliente::
                        where('clientes.estado','1')
                        ->where('clientes.tipo','1')
                        ->where('clientes.user_id', Auth::user()->id)
                        ->groupBy(
                            'clientes.id',
                            'clientes.nombre',
                            'clientes.celular', 
                            'clientes.estado'
                        )
                        ->get(['clientes.id', 
                                'clientes.nombre', 
                                'clientes.celular', 
                                'clientes.estado'
                                ]);

        return response()->json($clientes_ruc);
    }

    public function pedidostiempo(Request $request)
    {
        //cliente_id_tiempo//pcantidad_tiempo//pcantidad_pedido
        if (!$request->cliente_id_tiempo) {
            $html="";

        }else{
            if (!$request->pcantidad_tiempo && !$request->pcantidad_pedido) 
            {

            }else{

                $cliente_id_tiempo=$request->cliente_id_tiempo;
                $pcantidad_tiempo=$request->pcantidad_tiempo;
                $pcantidad_pedido=$request->pcantidad_pedido;

            }
          

            $html=$cliente_id_tiempo."|".$pcantidad_tiempo."|".$pcantidad_pedido;
            $cliente=Cliente::find($cliente_id_tiempo);
            $cliente->update([
                'crea_temporal' => '1',
                'activado_tiempo' => $pcantidad_tiempo,
                'activado_pedido' => $pcantidad_pedido,
                'temporal_update' => Carbon::now()
            ]);

            /*$jefe = User::find($request->asesor, ['jefe']);
            $user->update([
                'operario' => $request->asesor,
                'jefe' => $jefe->jefe
            ]);*/

        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function asesortiempo(Request $request)//clientes
    {
        $mirol=Auth::user()->rol;
        $html = '<option value="">' . trans('---- SELECCIONE ASESOR ----') . '</option>';

        if($mirol=='Llamadas')
        {   
            $asesores = Users::where('users.rol', "Asesor")
                                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)  
                ->get();
        }else if($mirol=='Jefe de llamadas'){
            $asesores = User:: where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->get();
        }else if($mirol=='Asesor'){
            $asesores = User:: where('users.rol', 'Asesor')
                    -> where('users.estado', '1')
                    -> where('users.id', Auth::user()->id)
                    ->get();
        }else{
            $asesores=User:: where('users.rol', 'Asesor')
                    -> where('users.estado', '1')
                    ->get();
        }
    
        foreach ($asesores as $asesor) {
            $html .= '<option style="color:#fff" value="' . $asesor->id . '">' . $asesor->identificador. '</option>';
        }
        
        return response()->json(['html' => $html]);
    }



    public function create()
    {   
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $mirol=Auth::user()->rol;//
        if($mirol=='Llamadas')
        {
            $users = User:: where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->pluck('users.identificador', 'users.id');

        }else if($mirol=='Jefe de llamadas'){

            $users = User:: where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->pluck('users.identificador', 'users.id');
        }else if($mirol=='Asesor'){

                $users = User:: where('users.rol', 'Asesor')
                    -> where('users.estado', '1')
                    -> where('users.id', Auth::user()->id)
                    ->pluck('users.identificador', 'users.id');
        }else{
            $users = User::where('estado', '1')->pluck('identificador', 'id');
        }

        $meses = [
            "ENERO" => 'ENERO',
            "FEBRERO" => 'FEBRERO',
            "MARZO" => 'MARZO',
            "ABRIL" => 'ABRIL',
            "MAYO" => 'MAYO',
            "JUNIO" => 'JUNIO',
            "JULIO" => 'JULIO',
            "AGOSTO" => 'AGOSTO',
            "SEPTIEMBRE" => 'SEPTIEMBRE',
            "OCTUBRE" => 'OCTUBRE',
            "NOVIEMBRE" => 'NOVIEMBRE',
            "DICIEMBRE" => 'DICIEMBRE',
        ];

        $anios = [
            "2020" => '2020',
            "2021" => '2021',
            "2022" => '2022',
            "2023" => '2023',
            "2024" => '2024',
            "2025" => '2025',
            "2026" => '2026',
            "2027" => '2027',
            "2028" => '2028',
            "2029" => '2029',
            "2030" => '2030',
            "2031" => '2031',
        ];

        /*$rucs = Ruc::where('user_id', Auth::user()->id)
                    ->where('estado', '1')
                    ->pluck('num_ruc', 'num_ruc');*/

        $fecha = Carbon::now()->format('dm');
        $dia = Carbon::now()->toDateString();

        $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
                    ->where('user_id', Auth::user()->id)
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->count();
        $numped = $numped + 1;

        $mirol=Auth::user()->rol;
        
        return view('pedidos.create', compact('users', 'dateM', 'dateY', 'meses', 'anios',  'fecha', 'numped','mirol'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function validarrelacionruc(Request $request)
    {
        $ruc_registrar=$request->agregarruc;
        $cliente_registrar=$request->cliente_id_ruc;
        $nombreruc_registrar=$request->pempresaruc;
        $asesor_registrar=$request->user_id;

        $ruc_repetido=Ruc::where('rucs.num_ruc',$ruc_registrar)->count();

        if($ruc_repetido>0)
        {
            //ya existe, actualizar y buscar relacion
            //busco relacion si es correcta
            $ruc = Ruc::where('num_ruc', $request->agregarruc)->first();//ruc ya exisste entoces busco al asesor//buscar si corresponde al cliente
            if($cliente_registrar==$ruc->cliente_id_ruc)
            {
                //verificar el asesor
                $asesordelruc= User::where("users.id",$ruc->user_id)->first();
                if($asesor_registrar==$asesordelruc->id)
                {
                    $html="1";
                    return response()->json(['html' => $html]);
                }else{
                    $html="0|A|".$asesordelruc->name;
                    return response()->json(['html' => $html]);
                }
                //$html="1";                
            }else{
                //$asesordelruc= User::where("users.id",$ruc->user_id)->first();
                $cliente=Cliente::where("clientes.id",$ruc->cliente_id)->first();
                //$html="0|C|RUC YA EXISTE PERO NO CORRESPONDE AL CLIENTE";
                $html="0|C|".$cliente->nombre;
                return response()->json(['html' => $html]);
            }
        }else{
            //no existe ,registrare
            $html="1";
            return response()->json(['html' => $html]);
        }
        
    }

    public function pedidoobteneradjuntoRequest(Request $request)
    {
        $buscar_pedido=$request->pedido;
        
        $cont_imagen=ImagenPedido::where('pedido_id',$buscar_pedido)->count();
        $array_html=[];
        if($cont_imagen>0)
        {            
            $imagenes=ImagenPedido::where('pedido_id',$buscar_pedido)
                ->where("estado","1")
                ->whereNotIn("adjunto",['logo_facturas.png'])
                ->orderBy('created_at', 'DESC')->get();
            foreach ($imagenes as $imagen) {
                $array_html[]=$imagen->adjunto;
            }
            $html=implode("|",$array_html);
            return response()->json(['html' => $html,'cantidad'=>$cont_imagen]);
        }else{
            $html="0";
            return response()->json(['html' => $html,'cantidad'=>$cont_imagen]);
        }
    }

    public function ruc(Request $request)//rucs
    {
        if (!$request->cliente_id || $request->cliente_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::join('clientes as c', 'rucs.cliente_id', 'c.id')
                ->select('rucs.num_ruc as num_ruc','rucs.empresa')
                ->where('rucs.cliente_id', $request->cliente_id)
                ->get();
            foreach ($rucs as $ruc) {
                $html .= '<option value="' . $ruc->num_ruc . '">' . $ruc->num_ruc."  ".$ruc->empresa . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function rucnombreempresa(Request $request)//rucs
    {
        if (!$request->ruc || $request->ruc=='') {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::where('rucs.num_ruc', $request->ruc)
                ->first();
            $html=$rucs->empresa;
            
        }
        return response()->json(['html' => $html]);
    }

    public function infopdf(Request $request)//rucs
    {
        if (!$request->infocopiar) {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $pedido="";
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $pedido = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                        ->select(
                            'pedidos.id',
                            'dp.cantidad',
                            'dp.porcentaje',
                            'dp.ft',
                            'dp.courier',
                            'dp.total',
                        )
                        ->where('pedidos.id', $request->infocopiar)
                        ->first();
            //$html=$pedido->id;
            
        }
        return response()->json($pedido);
    }

    /*
    public function ruc  vantigua(Request $request)//rucs
    {
        if (!$request->cliente_id) {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::where('rucs.cliente_id', $request->cliente_id)
                ->get();        
            foreach ($rucs as $ruc) {
                $html .= '<option value="' . $ruc->num_ruc . '">' . $ruc->num_ruc . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    } */

    public function cliente()//clientes
    {        
        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        $clientes = Cliente::where('clientes.user_id', Auth::user()->id)
                            ->where('clientes.tipo', '1')
                            ->get();        
        foreach ($clientes as $cliente) {
            $html .= '<option value="' . $cliente->id . '">' . $cliente->celular. '-' . $cliente->nombre . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function clientedeasesor(Request $request)//clientes
    {
        
        if (!$request->user_id  || $request->user_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        }else{

            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';

            $clientes = Cliente::where('clientes.user_id', $request->user_id)
                            ->where('clientes.tipo', '1')
                            //->where('clientes.celular','967767676')
                            ->get([
                                'clientes.id',
                                'clientes.deuda',
                                'clientes.crea_temporal',
                                'clientes.activado_tiempo',
                                'clientes.activado_pedido',
                                'clientes.temporal_update',
                                'clientes.celular',
                                'clientes.nombre',
                                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00' and ped.estado=1) as pedidos_mes_deuda "),
                                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00' and ped2.estado=1) as pedidos_mes_deuda_antes "),
                            ]);
                                //->get();  
                                //'deuda' => "0",
                /*'crea_temporal' => "1",
                'activado_tiempo' => $pcantidad_tiempo,
                'activado_pedido' => $pcantidad_pedido*/  
            
            foreach ($clientes as $cliente) {
                
                if($cliente->crea_temporal==1)
                {
                    if($cliente->activado_tiempo>0)
                    {
                        {
                            //$fecha_compa = Carbon::parse($cliente->temporal_update)->timestamp
                            //$fecha_compa = date('Y-m-d H:i:s', strtotime($cliente->temporal_update));
                            //$fecha_compa = Carbon::createFromFormat('d/m/Y H:i:s',  $cliente->temporal_update)->addMinutes($cliente->activado_tiempo);
                            //$dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
                            //$fecha_compa= Carbon($cliente->temporal_update.tostring())->addMinutes($cliente->activado_tiempo);                            
                            //$ahora = Carbon::now();
                            /*if($fecha_compa<$ahora)*/
                            {
                                $html .= '<option style="color:white" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                            }/*else{
                                
                                $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                            }*/
                        }
                    }
                }else{
                    if($cliente->pedidos_mes_deuda>0 && $cliente->pedidos_mes_deuda_antes==0)
                    {
                        $html .= '<option style="color:lightblue" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
    
                    }else if($cliente->pedidos_mes_deuda>0 && $cliente->pedidos_mes_deuda_antes>0)
                    {
                        $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if($cliente->pedidos_mes_deuda==0 && $cliente->pedidos_mes_deuda_antes>0)
                    {
                        $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else{
                        $html .= '<option style="color:white" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                    }
                }
                

                /*if($cliente->deuda=="0")
                {
                    $html .= '<option style="color:#000" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre. '</option>';
                }else{
                    if( Auth::user()->rol=='Asesor' )
                    {
                        $html .= '<option disabled style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if( Auth::user()->rol=='Llamadas' ){
                        $html .= '<option disabled style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre. '**CLIENTE CON DEUDA**</option>';
                    }else if( Auth::user()->rol=='Jefe de lamadas' ){
                        $html .= '<option disabled style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre  . '**CLIENTE CON DEUDA**</option>';
                    }else{
                        $html .= '<option disabled style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                    }

                }*/
                
            }
        }
        
        return response()->json(['html' => $html]);
    }

    public function clientedeasesorparapagos(Request $request)//clientes
    {
        if (!$request->user_id  || $request->user_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        }else{

            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.user_id', $request->user_id)
                                ->where('clientes.tipo', '1')
                                ->get();        
            foreach ($clientes as $cliente) {
                if($cliente->deuda=="0")
                {
                    $html .= '<option disabled style="color:#000" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                }else{
                    if( Auth::user()->rol=='Asesor' )
                    {
                        $html .= '<option   style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if( Auth::user()->rol=='Llamadas' ){
                        $html .= '<option   style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if( Auth::user()->rol=='Jefe de lamadas' ){
                        $html .= '<option  style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else{
                        $html .= '<option  style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                    }

                }
                //$html .= '<option value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
            }
        }
        
        return response()->json(['html' => $html]);
    }

    public function clientedeudaparaactivar(Request $request)//clientes
    {
        if (!$request->user_id  || $request->user_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        }else{
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.tipo', '1')
                ->where('clientes.user_id', $request->user_id)
                ->where('clientes.deuda', '1')
                ->where('clientes.estado', '1')
                ->get(); 
            foreach ($clientes as $cliente) {
                $html .= '<option value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
            }

        }        
        
        return response()->json(['html' => $html]);
    }

    public function clientedeasesordeuda(Request $request)//clientes
    {
        if (!$request->user_id  || $request->user_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        }else{
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.user_id', $request->user_id)
                                ->where('clientes.tipo', '1')
                                ->where('clientes.deuda', '1')
                                ->where('clientes.estado', '1')
                                ->get();        
            foreach ($clientes as $cliente) {
                $html .= '<option value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
            }

        }
        
        return response()->json(['html' => $html]);
    }

    public function tipobanca(Request $request)//pedidoscliente
    {
        if (!$request->cliente_id || $request->cliente_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $porcentajes = Porcentaje::where('porcentajes.cliente_id', $request->cliente_id)->get();        
            foreach ($porcentajes as $porcentaje) {
                $html .= '<option value="' . $porcentaje->nombre . '_' . $porcentaje->porcentaje . '">' . $porcentaje->nombre . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function AgregarRuc(Request $request)
    {
        $ruc = Ruc::where('num_ruc', $request->agregarruc)->first();
    
        if($ruc !== null){
            $user = User::where('id', $ruc->user_id)->first();
            
            $messages = [
                'required' => 'EL RUC INGRESADO ESTA ASIGNADO AL ASESOR '.$user->identificador,
            ];
    
            $validator = Validator::make($request->all(), [
                'num_ruc' => 'required|unique:rucs',
            ], $messages);
     
            /*if ($validator->fails()) {
                return redirect('pedidos/create')
                            ->withErrors($validator)
                            ->withInput();
            }*/
            $ruc->update([
                'empresa' => $request->pempresaruc
            ]);

            $html="false";
        }else{
            $ruc = Ruc::create([
                'num_ruc' => $request->agregarruc,
                'user_id' => Auth::user()->id,
                'cliente_id' => $request->cliente_id_ruc,
                'empresa' => $request->pempresaruc,
                'estado' => '1'
            ]);
            $html="true";
        }
        

        return response()->json(['html' => $html]);
       
    }
    public function pedidosstore(Request $request)
    {
        $numped="";
        $mirol=Auth::user()->rol;//
        $codigo="";
        if($mirol=='Llamadas')
        {
            $identi_asesor=User::where("id",$request->user_id)->first();
            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now()->toDateString();
            $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
                    ->where('user_id', $request->user_id)//identificador de asesor relacionado a este usuario llamada
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->count();
            $numped=$numped+1;

            $codigo=$identi_asesor->identificador."-".$fecha."-".$numped;
        }else{
            $identi_=User::where("id", Auth::user()->id)->first();
            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now()->toDateString();
            $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
                    ->where('user_id', Auth::user()->id)
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->count();
            $numped=$numped+1;

            $codigo=$identi_->identificador."-".$fecha."-".$numped;
        }
        //return $codigo;
        $request->validate([
            'cliente_id' => 'required',
        ]);
        //validar
        ///
        //$request->cliente_id

        //$cliente = Cliente::find($request->cliente_id);

        $cliente_deuda=Cliente::where("id",$request->cliente_id)
            ->get([
                'clientes.id',
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00'  and ped2.estado=1) as pedidos_mes_deuda_antes ")
                ]
            )->first();
        

        //return $cliente_deuda->pedidos_mes_deuda;

        if($cliente_deuda->pedidos_mes_deuda>0 && $cliente_deuda->pedidos_mes_deuda_antes==0)
        {
            if($cliente_deuda->pedidos_mes_deuda>2){
                $html="|2";
                if($mirol!='Administrador')
                {
                    //return response()->json(['html' => $html]); 
                }                
            }else{

            }

        }else if($cliente_deuda->pedidos_mes_deuda>0 && $cliente_deuda->pedidos_mes_deuda_antes>0)
        {
           $html="|0";
           //return response()->json(['html' => $html]); 

        }else if($cliente_deuda->pedidos_mes_deuda==0 && $cliente_deuda->pedidos_mes_deuda_antes>0)
        {
            $html="|0";
            //return response()->json(['html' => $html]); 
        }


        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => $request->user_id, //usuario que registra
                'creador' => 'USER0'.Auth::user()->id,//aqui una observacion, en el migrate la columna en tabla pedido tenia nombre creador y resulto ser creador_id
                'condicion' => 'POR ATENDER',
                'pago' => '0',
                'envio' => '0',
                'condicion_envio' => 'PENDIENTE DE ENVIO',
                'estado' => '1',
                'codigo' => $codigo,
                'notificacion' => 'Nuevo pedido creado',
                'modificador' => 'USER0'.Auth::user()->id,
                'pagado' => '0',
                'direccion' => '0'
            ]);

            // ALMACENANDO DETALLES
            $codigo = $codigo;//$request->codigo; actualizado para codigo autogenerado
            $codigo_generado=$codigo;
            $nombre_empresa = $request->nombre_empresa;
            $mes = $request->mes;
            $anio = $request->anio;
            $ruc = $request->ruc;
            $cantidad = $request->cantidad;
            $tipo_banca = $request->tipo_banca;
            $porcentaje = $request->porcentaje;
            $courier = $request->courier;
            $descripcion = $request->descripcion;
            $nota = $request->nota;

            $files = $request->file('adjunto');
            //return $files;
            //$files = $request->adjunto;
            $destinationPath = base_path('public/storage/adjuntos/');

            $cont = 0;
            $fileList = [];

            /*if(isset($file))
            {
                $destinationPath = base_path('public/storage/adjuntos/');
                $cont = 0;
                $file_name = Carbon::now()->second.$file->getClientOriginalName();
                $fileList[$cont] = array(
                    'file_name' => $file_name,
                );
                $file->move($destinationPath , $file_name);
            }*/

            if(isset($files)){
                $destinationPath = base_path('public/storage/adjuntos/');
                $cont = 0;
                $file_name = Carbon::now()->second.$files->getClientOriginalName();
                $fileList[$cont] = array(
                    'file_name' => $file_name,
                );
                $files->move($destinationPath , $file_name);

                ImagenPedido::create([
                    'pedido_id' => $pedido->id,
                    'adjunto' => $file_name,
                    'estado' => '1'
                ]);

                    //$cont++;
                //}
            }
            else{
                ImagenPedido::create([
                    'pedido_id' => $pedido->id,
                    'adjunto' => 'logo_facturas.png',
                    'estado' => '1'
                ]);
            }
            $contP = 0;

            while ($contP < count((array)$codigo)) {

            $detallepedido = DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'codigo' => $codigo_generado,//$codigo[$contP],
                    'nombre_empresa' => $nombre_empresa[$contP],
                    'mes' => $mes[$contP],
                    'anio' => $anio[$contP],
                    'ruc' => $ruc[$contP],
                    'cantidad' => $cantidad[$contP],
                    'tipo_banca' => $tipo_banca[$contP],
                    'porcentaje' => $porcentaje[$contP],
                    'ft' => ($cantidad[$contP]*$porcentaje[$contP])/100,
                    'courier' => $courier[$contP],
                    'total' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                    'saldo' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                    'descripcion' => $descripcion[$contP],
                    'nota' => $nota[$contP],
                    'estado' => '1'
                ]);             
            
                $contP++;

                //ACTUALIZAR DEUDA
                $cliente = Cliente::find($request->cliente_id);  
                
                $fecha = Carbon::now()->format('dm');
                $dia = Carbon::now()->toDateString();
                //
                $dateMinWhere = Carbon::now()->subDays(60)->format('d/m/Y');
                $dateMin = Carbon::now()->subDays(30)->format('d/m/Y');
                $dateMax = Carbon::now()->format('d/m/Y');

                $valido_deudas_mes=Pedido::where("pedidos.cliente_id",$request->cliente_id)
                        ->where("pedidos.estado","1")
                        ->where("pedidos.pago","0")
                        //->between("pedidos.estado","1")
                        ->whereBetween('pedidos.created_at', [$dateMinWhere, $dateMax])
                        ->where("pedidos.created_at","<",$dateMin)->count();
                if($valido_deudas_mes>0)
                {
                    $cliente->update([
                        'deuda' => '1',
                        'pidio' => '1'
                    ]);

                }else{
                    $cliente->update([
                        'deuda' => '0',
                        'pidio' => '1'
                    ]);
                }


                


                
            }
            DB::commit();
            $html=$pedido->id;
        } catch (\Throwable $th) {
            throw $th;
            $html="0";
            /* DB::rollback();
            dd($th); */
        }
        return response()->json(['html' => $html]); 
        //return redirect()->route('pedidosPDF', $pedido)->with('info', 'registrado');
    }
    
    public function store(Request $request)
    {
        return $request->all();

        $files = $request->file('adjunto');

        $numped="";
        $mirol=Auth::user()->rol;
        $codigo="";
        //return $mirol;
        if($mirol=='Llamadas')
        {
            $identi_asesor=User::where("id",$request->user_id)->first();
            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now()->toDateString();
            $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
                    ->where('user_id', Auth::user()->id)
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->count();
            $numped=$numped+1;

            $codigo=$identi_asesor->identificador."-".$fecha."-".$numped;
        }else{
            $identi_asesor=User::where("id",$request->user_id)->first();
            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now()->toDateString();
            $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
                    ->where('user_id', Auth::user()->id)
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->count();
            $numped=$numped+1;

            $codigo=$identi_asesor->identificador."-".$fecha."-".$numped;

        }
        //return $codigo;//21-0311-1

        $request->validate([
            'cliente_id' => 'required',
        ]);
        
        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => $request->user_id, //usuario que registra
                'creador' => 'USER0'.Auth::user()->id,//aqui una observacion, en el migrate la columna en tabla pedido tenia nombre creador y resulto ser creador_id
                'condicion' => 'POR ATENDER',
                'pago' => '0',
                'envio' => '0',
                'condicion_envio' => 'PENDIENTE DE ENVIO',
                'estado' => '1',
                'codigo' => $codigo,
                'notificacion' => 'Nuevo pedido creado',
                'modificador' => 'USER0'.Auth::user()->id,
                'pagado' => '0',
                'direccion' => '0'
            ]);

            // ALMACENANDO DETALLES
            $codigo = $codigo;//$request->codigo; actualizado para codigo autogenerado
            $codigo_generado=$codigo;
            $nombre_empresa = $request->nombre_empresa;
            $mes = $request->mes;
            $anio = $request->anio;
            $ruc = $request->ruc;
            $cantidad = $request->cantidad;
            $tipo_banca = $request->tipo_banca;
            $porcentaje = $request->porcentaje;
            $courier = $request->courier;
            $descripcion = $request->descripcion;
            $nota = $request->nota;

            $files = $request->file('adjunto');
            $destinationPath = base_path('public/storage/adjuntos/');

            $cont = 0;
            $fileList = [];

            if(isset($files)){
                foreach ($files as $file){
                    $file_name = Carbon::now()->second.$file->getClientOriginalName(); //Get file original name
                   /*  $fileList[$cont] = array(
                        'file_name' => $file_name,
                    ); */
                    $file->move($destinationPath , $file_name);

                    ImagenPedido::create([
                        'pedido_id' => $pedido->id,
                        'adjunto' => $file_name,
                        'estado' => '1'
                    ]);

                    $cont++;
                }
            }
            else{
                ImagenPedido::create([
                    'pedido_id' => $pedido->id,
                    'adjunto' => 'logo_facturas.png',
                    'estado' => '1'
                ]);
            }
            $contP = 0;

            while ($contP < count((array)$codigo)) {

            $detallepedido = DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'codigo' => $codigo_generado,//$codigo[$contP],
                    'nombre_empresa' => $nombre_empresa[$contP],
                    'mes' => $mes[$contP],
                    'anio' => $anio[$contP],
                    'ruc' => $ruc[$contP],
                    'cantidad' => $cantidad[$contP],
                    'tipo_banca' => $tipo_banca[$contP],
                    'porcentaje' => $porcentaje[$contP],
                    'ft' => ($cantidad[$contP]*$porcentaje[$contP])/100,
                    'courier' => $courier[$contP],
                    'total' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                    'saldo' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                    'descripcion' => $descripcion[$contP],
                    'nota' => $nota[$contP],
                    'estado' => '1'
                ]);             
            
                $contP++;

                //ACTUALIZAR DEUDA
                $cliente = Cliente::find($request->cliente_id);                
                $cliente->update([
                        'deuda' => '1',
                        'pidio' => '1'
                    ]);
            }            
            DB::commit();
            $html="true";
        } catch (\Throwable $th) {
            throw $th;
            $html="false";
            /* DB::rollback();
            dd($th); */
        }
        return response()->json(['html' => $html]); 

        //NOTIFICATION
        /*event(new PedidoEvent($pedido));

        if(Auth::user()->rol == "Asesor"){
           
            return redirect()->route('pedidosPDF', $pedido)->with('info', 'registrado');
        }
        else 
            
            return redirect()->route('pedidosPDF', $pedido)->with('info', 'registrado');*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        //ver pedido anulado y activo
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
                'dp.adjunto',
                'dp.total',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
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
                'dp.adjunto',
                'dp.total',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $imagenes = ImagenPedido::where('imagen_pedidos.pedido_id', $pedido->id)->where('estado', '1')->get();
        $imagenesatencion = ImagenAtencion::where('imagen_atencions.pedido_id', $pedido->id)->where('estado', '1')->get();

        return view('pedidos.show', compact('pedidos', 'imagenes', 'imagenesatencion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        $mirol=Auth::user()->rol;
        $meses = [
            "ENERO" => 'ENERO',
            "FEBRERO" => 'FEBRERO',
            "MARZO" => 'MARZO',
            "ABRIL" => 'ABRIL',
            "MAYO" => 'MAYO',
            "JUNIO" => 'JUNIO',
            "JULIO" => 'JULIO',
            "AGOSTO" => 'AGOSTO',
            "SEPTIEMBRE" => 'SEPTIEMBRE',
            "OCTUBRE" => 'OCTUBRE',
            "NOVIEMBRE" => 'NOVIEMBRE',
            "DICIEMBRE" => 'DICIEMBRE',
        ];

        $anios = [
            "2020" => '2020',
            "2021" => '2021',
            "2022" => '2022',
            "2023" => '2023',
            "2024" => '2024',
            "2025" => '2025',
            "2026" => '2026',
            "2027" => '2027',
            "2028" => '2028',
            "2029" => '2029',
            "2030" => '2030',
            "2031" => '2031',
        ];

        $porcentajes = Porcentaje::where('porcentajes.cliente_id', $pedido->cliente_id)
                                ->get();

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
                'dp.adjunto',
                'dp.total',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('pedidos.id', $pedido->id)
            ->where('dp.estado', '1')
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
                'dp.adjunto',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        
        $imagenes = ImagenPedido::where('imagen_pedidos.pedido_id', $pedido->id)->get();

        return view('pedidos.edit', compact('pedido', 'pedidos', 'meses', 'anios','porcentajes', 'imagenes','mirol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {/*return $request->all();*/
        $detallepedido = DetallePedido::where('pedido_id', $pedido->id)->first();
        try {
            DB::beginTransaction();

            // ALMACENANDO DETALLES
            $codigo = $request->codigo;
            $nombre_empresa = $request->nombre_empresa;
            $mes = $request->mes;
            $anio = $request->anio;
            $ruc = $request->ruc;
            $cantidad = $request->cantidad;
            $tipo_banca = $request->tipo_banca;
            $porcentaje = $request->porcentaje;
            $courier = $request->courier;
            $descripcion = $request->descripcion;
            $nota = $request->nota;
            $contP = 0;

            $files = $request->file('adjunto');
            $destinationPath = base_path('public/storage/adjuntos/');
            $cont = 0;

                if (isset($files)){
                    foreach ($files as $file){
                        $file_name = Carbon::now()->second.$file->getClientOriginalName(); //Get file original name
                        $file->move($destinationPath , $file_name);

                        ImagenPedido::create([
                            'pedido_id' => $pedido->id,
                            'adjunto' => $file_name,
                            'estado' => '1'
                        ]);

                        $cont++;
                    }
                }

                while ($contP < count((array)$codigo)) {
                    $detallepedido->update([
                        'codigo' => $codigo[$contP],
                        'nombre_empresa' => $nombre_empresa[$contP],
                        'mes' => $mes[$contP],
                        'anio' => $anio[$contP],
                        'ruc' => $ruc[$contP],
                        'cantidad' => $cantidad[$contP],
                        'tipo_banca' => $tipo_banca[$contP],
                        'porcentaje' => $porcentaje[$contP],
                        'ft' => ($cantidad[$contP]*$porcentaje[$contP])/100,
                        'courier' => $courier[$contP],
                        'total' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                        'saldo' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                        'descripcion' => $descripcion[$contP],
                        'nota' => $nota[$contP]
                    ]);
                    
                    $contP++;
                }     

                //ACTUALIZAR PORCENTAJE EN CLIENTE
                $porcentaje = Porcentaje::where('cliente_id', $pedido->cliente_id)
                ->where('nombre', $detallepedido->tipo_banca);
                $porcentaje->update([
                    'porcentaje' => $detallepedido->porcentaje
                ]);

                //ACTUALIZAR MODIFICACION AL PEDIDO
                $pedido->update([
                    'modificador' => 'USER'.Auth::user()->id
                ]);
            
            DB::commit();
            }
        catch (\Throwable $th) {
            throw $th;
            /*DB::rollback();
            dd($th);*/
        }

        if(Auth::user()->rol == "Asesor"){
            return redirect()->route('pedidos.mispedidos')->with('info', 'actualizado');
        }
        else 
            return redirect()->route('pedidos.index')->with('info', 'actualizado');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::find($pedido->id);
        $pedido->update([
            'motivo' => $request->motivo,
            'responsable' => $request->responsable,
            'condicion' => 'ANULADO',
            'modificador' => 'USER'.Auth::user()->id,
            'estado' => '0'
        ]);

        $detalle_pedidos->update([
            'estado' => '0'
        ]);

        //ACTUALIZAR QUE CLIENTE NO DEBE
        $cliente = Cliente::find($pedido->cliente_id);

        $pedido_deuda = Pedido::where('cliente_id', $pedido->cliente_id)//CONTAR LA CANTIDAD DE PEDIDOS QUE DEBE
                                ->where('pagado', '0')
                                ->count();
        if($pedido_deuda == 0){//SINO DEBE NINGUN PEDIDO EL ESTADO DEL CLIENTE PASA A NO DEUDA(CERO)
            $cliente->update([
                'deuda' => '0'
            ]);
        }   

        return redirect()->route('pedidos.index')->with('info', 'eliminado');
    }

    public function destroyid(Request $request)
    {
        if (!$request->hiddenID) {
            $html='';
        } else {
            Pedido::find($request->hiddenID)->update([
                'motivo' => $request->motivo,
                'responsable' => $request->responsable,
                'condicion' => 'ANULADO',
                'modificador' => 'USER'.Auth::user()->id,
                'estado' => '0'
            ]);

            //$detalle_pedidos = DetallePedido::find($request->hiddenID);            
            $detalle_pedidos = DetallePedido::where('pedido_id',$request->hiddenID)->first() ;          
           
            $detalle_pedidos->update([
                'estado' => '0'
            ]);

            $html=$detalle_pedidos;
        }
        return response()->json(['html' => $html]);
    }

    public function Restaurar(Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();

        $pedido->update([            
            'condicion' => 'POR ATENDER',
            'modificador' => 'USER'.Auth::user()->id,
            'estado' => '1'
        ]);

        $detalle_pedidos->update([
            'estado' => '1',
        ]);

        return redirect()->route('pedidos.index')->with('info','restaurado');
    }

    public function Restaurarid(Request $request)
    {
        if (!$request->hiddenID) {
            $html='';
        } else {
            Pedido::find($request->hiddenID)->update([
                'condicion' => 'POR ATENDER',
                'modificador' => 'USER'.Auth::user()->id,
                'estado' => '1'
            ]);
            $detalle_pedidos = DetallePedido::where('pedido_id',$request->hiddenID)->first();

            $detalle_pedidos->update([
                'estado' => '1',
            ]);
            $html=$detalle_pedidos;
        }

        return response()->json(['html' => $html]);
    }

    public function viewVentas()
    {
        return view('ventas.reportes.index');
    }

    public function MisPedidos()
    {   
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $mirol=Auth::user()->rol;

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.misPedidos', compact('destinos', 'superasesor', 'dateMin', 'dateMax','mirol'));
    }
    
    public function mispedidostabla(Request $request)
    {
        if(Auth::user()->rol == "Asesor"){
            
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                //->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                //->leftjoin('pagos as pa', 'pp.pago_id', 'pa.id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',//tiene diferencia con bandeja de pedidos
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.condicion_envio as condicion_env',
                    'pedidos.condicion as condiciones',
                    //'pa.condicion as condicion_pa',//para pedido con pago
                    'pedidos.envio',
                    'pedidos.direccion',
                    'pedidos.destino',
                    'pedidos.motivo',
                    'pedidos.responsable',
                    //'pa.total_cobro',
                    //'pa.total_pagado',
                    'dp.saldo as diferencia',//'pa.diferencia',
                    'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.estado'                  
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                //->where('pedidos.pago', '1')
                ->where('u.id', Auth::user()->id)// no para administrador
                ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                //->where('pa.estado', '1') 0 sin pago 1 con pago
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion_envio',
                    'pedidos.condicion',
                    //'pa.condicion',//para pedido con pago
                    'pedidos.envio',
                    'pedidos.direccion',
                    'pedidos.destino',
                    'pedidos.motivo',
                    'pedidos.responsable',
                    //'pa.total_cobro',
                    //'pa.total_pagado',
                    //'pa.condicion',
                    'pedidos.created_at',
                    //'pa.diferencia',
                    'dp.saldo',
                    'pedidos.pagado',
                    'pedidos.estado'
                    )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else if(Auth::user()->rol == "Super asesor"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                //->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                //->leftjoin('pagos as pa', 'pp.pago_id', 'pa.id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',//tiene diferencia con bandeja de pedidos
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.condicion_envio as condicion_env',
                    'pedidos.condicion as condiciones',
                    //'pa.condicion as condicion_pa',//para pedido con pago
                    'pedidos.envio',
                    'pedidos.direccion',
                    'pedidos.destino',
                    'pedidos.motivo',
                    'pedidos.responsable',
                    //'pa.total_cobro',
                    //'pa.total_pagado',
                    'dp.saldo as diferencia',//'pa.diferencia',
                    'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.estado'                
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                //->where('pedidos.pago', '1')
                ->where('u.id', Auth::user()->id)// no para administrador
                ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                //->where('pa.estado', '1') 0 sin pago 1 con pago
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion_envio',
                    'pedidos.condicion',
                    //'pa.condicion',//para pedido con pago
                    'pedidos.envio',
                    'pedidos.direccion',
                    'pedidos.destino',
                    'pedidos.motivo',
                    'pedidos.responsable',
                    //'pa.total_cobro',
                    //'pa.total_pagado',
                    //'pa.condicion',
                    'pedidos.created_at',
                    //'pa.diferencia',
                    'dp.saldo',
                    'pedidos.pagado',
                    'pedidos.estado'
                    )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else{
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')//PEDIDOS CON PAGOS
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                //->leftjoin('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
                //->leftjoin('pagos as pa', 'pp.pago_id', 'pa.id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',//tiene diferencia con bandeja de pedidos
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.condicion_envio as condicion_env',
                    'pedidos.condicion as condiciones',
                    //'pa.condicion as condicion_pa',//para pedido con pago
                    'pedidos.envio',
                    'pedidos.direccion',
                    'pedidos.destino',
                    'pedidos.motivo',
                    'pedidos.responsable',
                    //'pa.total_cobro',
                    //'pa.total_pagado',
                    'dp.saldo as diferencia',//'pa.diferencia',
                    'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha') ,
                    'pedidos.estado'                   
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                //->where('pedidos.pago', '1')
                //->where('u.id', Auth::user()->id)// no para administrador
                ->whereIn('pedidos.condicion', ['POR ATENDER', 'EN PROCESO ATENCION', 'ATENDIDO', 'ANULADO'])
                //->where('pa.estado', '1') 0 sin pago 1 con pago
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion_envio',
                    'pedidos.condicion',
                    //'pa.condicion',//para pedido con pago
                    'pedidos.envio',
                    'pedidos.direccion',
                    'pedidos.destino',
                    'pedidos.motivo',
                    'pedidos.responsable',
                    //'pa.total_cobro',
                    //'pa.total_pagado',
                    //'pa.condicion',
                    'pedidos.created_at',
                    //'pa.diferencia',
                    'dp.saldo',
                    'pedidos.pagado',
                    'pedidos.estado'
                    )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }

        return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){     

                        $btn='<a href="'.route('pedidosPDF', $pedido).'" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                        $btn=$btn.'<a href="'.route('pedidos.show', $pedido).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> VER</a>';

                        if($pedido->estado>0){

                            if(Auth::user()->rol == "Super asesor" || Auth::user()->rol =="Administrador")
                            {
                                $btn=$btn.'<a href="'.route('pedidos.edit', $pedido->id).'" class="btn btn-warning btn-sm">Editar</a>';
                            }

                            if(Auth::user()->rol =="Administrador")
                            {
                                $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pedido->id.'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>';                                                     
                            }
                            
                            
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function Pagados()//PEDIDOS PAGADOS
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id','pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion as condiciones',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                /* 'pedidos.created_at as fecha' */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha')
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->where('pa.condicion', 'PAGO')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.pagados', compact('pedidos', 'superasesor', 'dateMin', 'dateMax'));
    }

    public function SinPagos()//PEDIDOS POR COBRAR
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.id as cliente_id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion as condiciones',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            //->where('pedidos.pago', '0')
            ->where('pedidos.pagado', '<>', '2')
            ->groupBy(
                'pedidos.id',
                'c.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.created_at',
                'pedidos.pagado'
                )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.sinPagos', compact('pedidos', 'superasesor'));
    }

    public function PorAtendertabla(Request $request)
    {
        $mirol=Auth::user()->rol;

        if(Auth::user()->rol == "Operario"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.operario', Auth::user()->id)
            //->where('pedidos.condicion', 'POR ATENDER')
            ->whereIn('pedidos.condicion', ['POR ATENDER','EN PROCESO ATENCION'])
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get(); 
        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.jefe', Auth::user()->id)
            ->whereIn('pedidos.condicion', ['POR ATENDER','EN PROCESO ATENCION'])
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get(); 
        }
        else{
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('pedidos.condicion', ['POR ATENDER','EN PROCESO ATENCION'])
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }
        
        return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){     
                        $btn='';
                        return $btn;
                    })
                    ->addColumn('action2', function($pedido){     
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action','action2'])
                    ->make(true);
        
    }

    public function PorAtender()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => 'POR ATENDER',
            "EN PROCESO ATENCION" => 'EN PROCESO ATENCION',
            "ATENDIDO" => 'ATENDIDO'
        ];

        if(Auth::user()->rol == "Operario"){
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
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.operario', Auth::user()->id)
            ->where('pedidos.condicion', 'POR ATENDER')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get(); 
        }else if(Auth::user()->rol == "Jefe de operaciones"){
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
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.jefe', Auth::user()->id)
            ->where('pedidos.condicion', 'POR ATENDER')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get(); 
        }
        else{
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
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                /* 'pedidos.created_at as fecha', */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion', 'POR ATENDER')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }        

        $imagenespedido = ImagenPedido::get();
        $imagenes = ImagenAtencion::get();        
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.porAtender', compact('dateMin', 'dateMax', 'pedidos', 'condiciones', 'imagenespedido', 'imagenes', 'superasesor'));
    }

    public function EnAtenciontabla(Request $request)
    {
        if(Auth::user()->rol == "Operario"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', 'EN PROCESO ATENCION')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', 'EN PROCESO ATENCION')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else{
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', 'EN PROCESO ATENCION')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }

        return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){     
                        $btn='';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function EnAtencion()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => 'POR ATENDER',
            "EN PROCESO ATENCION" => 'EN PROCESO ATENCION',
            "ATENDIDO" => 'ATENDIDO'
        ];

        if(Auth::user()->rol == "Operario"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', 'EN PROCESO ATENCION')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else if(Auth::user()->rol == "Jefe de operaciones"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', 'EN PROCESO ATENCION')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else{
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', 'EN PROCESO ATENCION')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }

        $imagenes = ImagenAtencion::get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.enAtencion', compact('dateMin', 'dateMax', 'pedidos', 'condiciones', 'imagenes', 'superasesor'));
    }

    public function Atendidostabla(Request $request)
    {
        
        if(Auth::user()->rol == "Operario"){
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->whereIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                /* ->take('200') */
                ->get();
        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->whereIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                /* ->take('300') */
                ->get();
        }else{
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'u.jefe',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', 'ATENDIDO')
                ->whereIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'u.jefe',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                /*->take('200')*/
                ->get();
                /*->simplePaginate(1000);*/
            }

        return Datatables::of($pedidos)
            ->addIndexColumn()
            ->addColumn('action', function($pedido){     
                $btn='';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function Atendidos()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => 'POR ATENDER',
            "EN PROCESO ATENCION" => 'EN PROCESO ATENCION',
            "ATENDIDO" => 'ATENDIDO'
        ];

        

        $imagenes = ImagenAtencion::where('estado', '1')->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.atendidos', compact('dateMin', 'dateMax', 'condiciones', 'superasesor'));//, 'imagenes'
    }

    public function Entregados()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => 'POR ATENDER',
            "EN PROCESO ATENCION" => 'EN PROCESO ATENCION',
            "ATENDIDO" => 'ATENDIDO'
        ];

        

        $imagenes = ImagenAtencion::where('estado', '1')->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.entregados', compact('dateMin', 'dateMax', 'condiciones', 'superasesor'));//, 'imagenes'
    }

    public function Entregadostabla(Request $request)
    {
        
        if(Auth::user()->rol == "Operario"){
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')//
                ->whereNotIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                /* ->take('200') */
                ->get();
        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->whereNotIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                /* ->take('300') */
                ->get();
        }else{
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'u.jefe',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', 'ATENDIDO')
                ->whereNotIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'u.jefe',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                /*->take('200')*/
                ->get();
                /*->simplePaginate(1000);*/
            }

        return Datatables::of($pedidos)
            ->addIndexColumn()
            ->addColumn('action', function($pedido){     
                $btn='';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function cargarAtendidos(Request $request)//pedidoscliente
    {   
        if(Auth::user()->rol == "Operario"){
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id as id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id as id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }else{
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id as id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion as estado',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', 'ATENDIDO')
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
            }

        //return datatables($pedidos)->toJson();
    }

    public function Atenderid(Request $request)
    {
        $hiddenAtender=$request->hiddenAtender;
        $detalle_pedidos = DetallePedido::where('pedido_id',$hiddenAtender)->first();        
        $fecha = Carbon::now();

        $pedido=Pedido::where("id",$hiddenAtender)->first();
        $pedido->update([
            'condicion' => $request->condicion,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        if ($request->condicion == "ATENDIDO")
        {
            $pedido->update([
                'notificacion' => 'Pedido atendido'
            ]);

            event(new PedidoAtendidoEvent($pedido));
        }

        $files = $request->file('adjunto');
        $destinationPath = base_path('public/storage/adjuntos/');

        $cont = 0;

        if(isset($files)){
            $destinationPath = base_path('public/storage/adjuntos/');
            $cont = 0;
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $fileList[$cont] = array(
                'file_name' => $file_name,
            );
            $files->move($destinationPath , $file_name);

            ImagenAtencion::create([
                'pedido_id' => $pedido->id,
                'adjunto' => $file_name,
                'estado' => '1'
            ]);

                //$cont++;
            //}
        }



        /*if(isset($files)){
            foreach ($files as $file){
                $file_name = Carbon::now()->second.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);

                ImagenAtencion::create([                    
                    'pedido_id' => $pedido->id,
                    'adjunto' => $file_name,
                    'estado' => '1'
                ]);

                $cont++;
            }
        }*/      

        $detalle_pedidos->update([
            'envio_doc' => '1',
            'fecha_envio_doc' => $fecha,
            'cant_compro' => $request->cant_compro,
        ]);

        /* if ($request->hasFile('envio_doc')){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);
            
            $detalle_pedidos->update([
                'envio_doc' => $file_name,
                'fecha_envio_doc' => $fecha,
                'cant_compro' => $request->cant_compro,
            ]);
        }
        else{
            $detalle_pedidos->update([
                'cant_compro' => $request->cant_compro,
            ]);
        } */        

        return redirect()->route('operaciones.poratender')->with('info','actualizado');
    }

    public function Atender(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();        
        $fecha = Carbon::now();

        $pedido->update([
            'condicion' => $request->condicion,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        if ($request->condicion == "ATENDIDO")
        {
            $pedido->update([
                'notificacion' => 'Pedido atendido'
            ]);

            event(new PedidoAtendidoEvent($pedido));
        }

        /* $files = $request->file('envio_doc'); */
        /* $destinationPath = base_path('public/storage/adjuntos/'); */

        $files = $request->file('adjunto');
        $destinationPath = base_path('public/storage/adjuntos/');

        $cont = 0;

        if(isset($files)){
            foreach ($files as $file){
                $file_name = Carbon::now()->second.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);

                ImagenAtencion::create([                    
                    'pedido_id' => $pedido->id,
                    'adjunto' => $file_name,
                    'estado' => '1'
                ]);

                $cont++;
            }
        }        

        $detalle_pedidos->update([
            'envio_doc' => '1',
            'fecha_envio_doc' => $fecha,
            'cant_compro' => $request->cant_compro,
        ]);

        /* if ($request->hasFile('envio_doc')){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);
            
            $detalle_pedidos->update([
                'envio_doc' => $file_name,
                'fecha_envio_doc' => $fecha,
                'cant_compro' => $request->cant_compro,
            ]);
        }
        else{
            $detalle_pedidos->update([
                'cant_compro' => $request->cant_compro,
            ]);
        } */        

        return redirect()->route('operaciones.poratender')->with('info','actualizado');
    }

    public function editAtender(Pedido $pedido)
    {
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
                'dp.adjunto',
                'dp.total',
                'pedidos.condicion as condiciones',
                'pedidos.envio',
                'pedidos.condicion_envio',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('pedidos.id', $pedido->id)
            ->where('dp.estado', '1')
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
                'dp.adjunto',
                'dp.total',
                'pedidos.condicion',
                'pedidos.envio',
                'pedidos.condicion_envio',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $imagenespedido = ImagenPedido::where('imagen_pedidos.pedido_id', $pedido->id)->where('estado', '1')->get();
        $imagenes = ImagenAtencion::where('imagen_atencions.pedido_id', $pedido->id)->where('estado', '1')->get();        

        return view('pedidos.editatender', compact('pedido', 'pedidos', 'imagenespedido', 'imagenes'));
    }

    public function updateAtender(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();        
        $fecha = Carbon::now();

        /* $files = $request->file('envio_doc'); */
        $files = $request->file('adjunto');
        $destinationPath = base_path('public/storage/adjuntos/');

        $cont = 0;

        //ACTUALIZAR MODIFICACION AL PEDIDO
        $pedido->update([
            'modificador' => 'USER'.Auth::user()->id
        ]);

        if ($request->hasFile('adjunto')){
            /* $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name); */

            foreach ($files as $file){
                $file_name = Carbon::now()->second.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);

                ImagenAtencion::create([                    
                    'pedido_id' => $pedido->id,
                    'adjunto' => $file_name,
                    'estado' => '1'
                ]);

                $cont++;
            }
            
            $detalle_pedidos->update([
                'envio_doc' => '1',
                'fecha_envio_doc' => $fecha,
                'cant_compro' => $request->cant_compro,
            ]);
        }
        else{
            $detalle_pedidos->update([
                'cant_compro' => $request->cant_compro,
            ]);
        }

        return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }

    public function showAtender(Pedido $pedido)
    {
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
                'dp.adjunto',
                'dp.total',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('pedidos.id', $pedido->id)
            ->where('dp.estado', '1')
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
                'dp.adjunto',
                'dp.total',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $imagenes = ImagenPedido::where('imagen_pedidos.pedido_id', $pedido->id)->get();
        $imagenesatencion = ImagenAtencion::where('imagen_atencions.pedido_id', $pedido->id)->get();

        return view('pedidos.showAtender', compact('pedidos', 'imagenes', 'imagenesatencion'));
    }

    public function eliminarAdjunto($id)
    {
        $imagenes = ImagenAtencion::find($id);
        $imagenes->update([
            'estado' => '0'
        ]);
        return redirect()->route('operaciones.atendidos')->with('info', 'actualizado');
    }

    public function Enviar(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '1',
            'modificador' => 'USER'.Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
        ]);

        return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }

    public function Destino(Request $request, Pedido $pedido)
    {
        $pedido->update([            
            'destino' => $request->destino,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        return redirect()->route('envios.index')->with('info','actualizado');
    }

    public function SinEnviar(Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '3',//SIN ENVIO
            'condicion_envio' => 'ENTREGADO',
            'modificador' => 'USER'.Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
            'fecha_recepcion' => $fecha,
            'atendido_por' => Auth::user()->name,
        ]);

        return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }

    public function DescargarAdjunto($adjunto)
    {   
        $destinationPath = base_path("public/storage/adjuntos/".$adjunto);
        /* $destinationPath = storage_path("app/public/adjuntos/".$pedido->adjunto); */

        return response()->download($destinationPath);
    }

    public function Envios()//BANDEJA DE ENVIOS
    {
        $condiciones = [
            "PENDIENTE DE ENVIO" => 'PENDIENTE DE ENVIO',
            "EN REPARTO" => 'EN REPARTO',
            "ENTREGADO" => 'ENTREGADO'
        ];

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        /* $departamentos = Departamento::where('estado', '1')->pluck('departamento', 'id');

        $provincias = Provincia::where('estado', '1')->pluck('provincia', 'id'); */

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                            ->where('estado', '1')
                            ->pluck('distrito', 'distrito');

        /* $direcciones = DireccionEnvio::
                            where('estado', '1')
                            ->get(); */

        if(Auth::user()->rol == "Asesor"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    'pedidos.created_at as fecha',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.envio', '<>', '0')
                ->where('u.id', Auth::user()->id)
                ->where('pedidos.condicion_envio', '<>', 'ENTREGADO')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }
        else if(Auth::user()->rol == "Super asesor"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    'pedidos.created_at as fecha',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.envio', '<>', '0')
                ->where('u.id', Auth::user()->id)
                ->where('pedidos.condicion_envio', '<>', 'ENTREGADO')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }
        else if(Auth::user()->rol == "Encargado"){
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
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                'pedidos.created_at as fecha',
                'pedidos.condicion_envio',
                'pedidos.envio',
                'pedidos.destino',
                'pedidos.direccion',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.envio', '<>', '0')
            ->where('u.supervisor', Auth::user()->id)
            ->where('pedidos.condicion_envio', '<>', 'ENTREGADO')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'pedidos.condicion_envio',
                'pedidos.envio',
                'pedidos.destino',
                'pedidos.direccion',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }
        else{
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
                    'dp.total as total',
                    'pedidos.condicion',
                    'pedidos.created_at as fecha',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.envio', '<>', '0')
                ->where('pedidos.condicion_envio', '<>', 'ENTREGADO')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }

        $direcciones = DireccionEnvio::join('direccion_pedidos as dp', 'direccion_envios.id', 'dp.direccion_id')
                            /* ->join('pedidos as p', 'dp.pedido_id', 'p.id') */
                            ->select('direccion_envios.id',
                                    'direccion_envios.distrito',
                                    'direccion_envios.direccion',
                                    'direccion_envios.referencia',
                                    'direccion_envios.nombre',
                                    'direccion_envios.celular',
                                    'dp.pedido_id as pedido_id',
                                    )
                            ->where('direccion_envios.estado', '1')
                            ->where('dp.estado', '1')
                            /* ->where('p.estado', '1') */
                            /* ->whereIn('pedido_id', $pedidos->id) */
                            /* ->groupBy(
                                'direccion_envios.id',
                                'dp.id',
                                'p.id'
                            ) */
                            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();
        //$superasesor = 0;
        $ver_botones_accion = 1;
        //$asesor = 0;
        if(Auth::user()->rol == "Asesor")
        {
            $ver_botones_accion = 0;
        }else if(Auth::user()->rol == "Super asesor"){
            $ver_botones_accion = 0;
        }else if(Auth::user()->rol == "Encargado"){
            $ver_botones_accion = 1;
        }else{
            $ver_botones_accion = 1;
        }

        return view('pedidos.porEnviar', compact('pedidos', 'condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor','ver_botones_accion'));
    }

    public function Recibir(Pedido $pedido)
    {
        $pedido->update([
            'envio' => '2',
            'modificador' => 'USER'.Auth::user()->id
        ]);

        return redirect()->route('envios.index')->with('info','actualizado');
    }

    public function EnviarPedido(Request $request, Pedido $pedido)//'notificacion' => 'Nuevo pedido creado'
    {
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();

        $pedido->update([
            'condicion_envio' => $request->condicion,
            'trecking' => $request->trecking,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        if ($request->condicion == "ENTREGADO")
        {
            $pedido->update([
                'notificacion' => 'Pedido entregado'
            ]);

            event(new PedidoEntregadoEvent($pedido));
        }

        $files = $request->file('foto1');
        $files2 = $request->file('foto2');

        $destinationPath = base_path('public/storage/entregas/');

        if ($request->hasFile('foto1') && $request->hasFile('foto2')){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $file_name2 = Carbon::now()->second.$files2->getClientOriginalName();
            
            $files->move($destinationPath , $file_name);
            $files2->move($destinationPath , $file_name2);
            
            $detalle_pedidos->update([
                'foto1' => $file_name,
                'foto2' => $file_name2,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
            ]);
        }
        else if ($request->hasFile('foto1') && $request->foto2 == null){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);

            $detalle_pedidos->update([
                'foto1' => $file_name,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
            ]);
        }  
        else if ($request->foto1 == null && $request->hasFile('foto2')){
            $file_name2 = Carbon::now()->second.$files2->getClientOriginalName();
            $files2->move($destinationPath , $file_name2);

            $detalle_pedidos->update([
                'foto2' => $file_name2,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
            ]);    
        }
        else
        {
            $detalle_pedidos->update([
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
            ]);
        }

        if($request->vista == 'ENTREGADOS')
        {
            return redirect()->route('envios.enviados')->with('info','actualizado');
        }

        return redirect()->route('envios.index')->with('info','actualizado');
    }
    
    public function createDireccion(Pedido $pedido)
    {
        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                            ->where('estado', '1')
                            ->pluck('distrito', 'distrito');

        $clientes = Cliente::where('estado', '1')
                            ->where('id', $pedido->user_id)
                            ->first();
        $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                            ->select('pedidos.id', 
                                    'dp.codigo')
                            ->where('pedidos.cliente_id', $pedido->cliente_id)
                            ->where('pedidos.destino', null)
                            ->where('pedidos.direccion', '0')
                            ->where('pedidos.envio', '>', '0')
                            ->where('pedidos.estado', '1')
                            ->get();

        return view('pedidos.createDireccion', compact('destinos', 'distritos', 'clientes', 'pedidos'));
    }

    public function DireccionEnvio(Request $request)
    {
        if ($request->destino == "LIMA")
        {
            try {
                DB::beginTransaction();
                
                $direccionLima = DireccionEnvio::create([
                    'cliente_id' => $request->cliente_id,
                    'distrito' => $request->distrito,
                    'direccion' => $request->direccion,
                    'referencia' => $request->referencia,
                    'nombre' => $request->nombre,
                    'celular' => $request->celular,
                    'estado' => '1'
                ]);
                    
                // ALMACENANDO DIRECCION-PEDIDOS
                $pedido_id = $request->pedido_id;
                $contPe = 0;
    
                while ($contPe < count((array)$pedido_id)) {
                    
                    $direccionPedido = DireccionPedido::create([
                            'direccion_id' => $direccionLima->id,
                            'pedido_id' => $pedido_id[$contPe],
                            'estado' => '1'
                        ]);
    
                    //INDICADOR DE DIRECCION
                    $pedido = Pedido::find($pedido_id[$contPe]);
    
                    $pedido->update([
                        'destino' => $request->destino,
                        'condicion_envio' => 'EN REPARTO',//AL REGISTRAR DIRECCION PASA A ESTADO  EN REPARTO
                        'direccion' => '1',
                    ]);
    
                    $contPe++;
                }
                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                /*DB::rollback();
                dd($th);*/
            }

        }

        if ($request->destino == "PROVINCIA")
        {
            try {
                DB::beginTransaction();

                //IMPORTE
                $importe = $request->importe;
                $importe=str_replace(',','',$importe);

                //FOTO
                $files = $request->file('foto');
                $destinationPath = base_path('public/storage/gastos/');

                if(isset($files)){
                    $file_name = Carbon::now()->second.$files->getClientOriginalName();
                    $files->move($destinationPath , $file_name);
                }
                else{
                    $file_name = 'logo_facturas.png';
                }

                $gastoProvincia = GastoEnvio::create([
                    'cliente_id' => $request->cliente_id,
                    'user_id' => Auth::user()->id,
                    'tracking' => $request->tracking,
                    'registro' => $request->registro,
                    'foto' => $file_name,
                    'importe' => $importe,
                    'estado' => '1'
                ]);

                // ALMACENANDO DIRECCION-PEDIDOS
                $pedido_id = $request->pedido_id;
                $contPe = 0;

                while ($contPe < count((array)$pedido_id)) {

                    $gastoPedido = GastoPedido::create([
                            'gasto_id' => $gastoProvincia->id,
                            'pedido_id' => $pedido_id[$contPe],
                            'estado' => '1'
                        ]);

                    //INDICADOR DE PAGOS
                    $pedido = Pedido::find($pedido_id[$contPe]);

                    $pedido->update([
                        'destino' => $request->destino,
                        'direccion' => '1',
                    ]);

                    $contPe++;
                }
                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                /*DB::rollback();
                dd($th);*/
            }
        }

        return redirect()->route('envios.index')->with('info','actualizado');
    }

    public function UpdateDireccionEnvio(Request $request, DireccionEnvio $direccion)
    {
        $direccion->update([
            /* 'departamento' => $request->departamento,
            'provincia' => $request->provincia, */            
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'referencia' => $request->referencia,
            'nombre' => $request->nombre,
            'celular' => $request->celular,
            'estado' => '1'
        ]);

        return redirect()->route('envios.index')->with('info','actualizado');
    }

    public function Enviados()//ENTREGADOS
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "PENDIENTE DE ENVIO" => 'PENDIENTE DE ENVIO',
            "EN REPARTO" => 'EN REPARTO',
            "ENTREGADO" => 'ENTREGADO'
        ];

        if(Auth::user()->rol == "Asesor"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'dp.envio_doc',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc, "%d/%m/%Y") as fecha_envio_doc'),
                    'dp.cant_compro',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.foto1',
                    'dp.foto2',
                    DB::raw('DATE_FORMAT(dp.fecha_recepcion, "%d/%m/%Y") as fecha_recepcion')
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.envio', '1')
                ->where('u.id', Auth::user()->id) 
                ->where('pedidos.condicion_envio', 'ENTREGADO')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }
        else if(Auth::user()->rol == "Super asesor"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'dp.envio_doc',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc, "%d/%m/%Y") as fecha_envio_doc'),
                    'dp.cant_compro',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.foto1',
                    'dp.foto2',
                    DB::raw('DATE_FORMAT(dp.fecha_recepcion, "%d/%m/%Y") as fecha_recepcion')
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.envio', '1')
                ->where('u.id', Auth::user()->id)
                ->where('pedidos.condicion_envio', 'ENTREGADO')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }
        else if(Auth::user()->rol == "Encargado"){
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
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'dp.envio_doc',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc, "%d/%m/%Y") as fecha_envio_doc'),
                    'dp.cant_compro',
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.foto1',
                    'dp.foto2',
                    DB::raw('DATE_FORMAT(dp.fecha_recepcion, "%d/%m/%Y") as fecha_recepcion')
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.envio', '1')
                ->where('u.supervisor', Auth::user()->id)
                ->where('pedidos.condicion_envio', 'ENTREGADO')
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }
        else{
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
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'pedidos.condicion_envio',
                'pedidos.envio',
                'pedidos.destino',
                'dp.envio_doc',
                DB::raw('DATE_FORMAT(dp.fecha_envio_doc, "%d/%m/%Y") as fecha_envio_doc'),
                'dp.cant_compro',
                DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                'dp.foto1',
                'dp.foto2',
                DB::raw('DATE_FORMAT(dp.fecha_recepcion, "%d/%m/%Y") as fecha_recepcion'),
                'pedidos.trecking'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.envio', '<>', '0')
            ->where('pedidos.condicion_envio', 'ENTREGADO')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'pedidos.condicion_envio',
                'pedidos.envio',
                'pedidos.destino',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion',
                'pedidos.trecking'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();
        }

        /* $imagenes = ImagenAtencion::where('estado', '1')->get(); */
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.enviados', compact('dateMin', 'dateMax', 'pedidos', 'condiciones', 'superasesor'));//'imagenes',
    }

    public function DescargarImagen($imagen)
    {   
        $destinationPath = base_path("public/storage/entregas/".$imagen);

        return response()->download($destinationPath);
    }

    public function eliminarFoto1(Pedido $pedido)
    {
        $detallepedido = DetallePedido::find($pedido->id);
        $detallepedido->update([
            'foto1' => null
        ]);
        return redirect()->route('envios.enviados')->with('info', 'actualizado');
    }

    public function eliminarFoto2(Pedido $pedido)
    {
        $detallepedido = DetallePedido::find($pedido->id);
        $detallepedido->update([
            'foto2' => null
        ]);
        return redirect()->route('envios.enviados')->with('info', 'actualizado');
    }
}
