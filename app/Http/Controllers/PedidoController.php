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

        

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.index', compact('dateMin', 'dateMax', 'superasesor'));
    }

    public function indextabla(Request $request)
    {
        if(Auth::user()->rol == "Asesor"){
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
                'pedidos.motivo',
                'pedidos.responsable',
                /*'pedidos.created_at as fecha',*/
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.saldo as diferencia',//'pa.diferencia',//para pedido con pago
                'pedidos.estado',
                'pedidos.envio',
            )
            /* ->where('pedidos.estado', '1') */
            /* ->where('dp.estado', '1') */
            ->where('pp.estado', '1')
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
                'pedidos.motivo',
                'pedidos.responsable',
                /*'pedidos.created_at as fecha',*/
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.saldo as diferencia',//'pa.diferencia',//para pedido con pago
                'pedidos.estado',
                'pedidos.envio'
            )
            /* ->where('pedidos.estado', '1') */
            /* ->where('dp.estado', '1') */
            //->where('pedidos.pago', '1') 0 sin pago  1 con pago
            ->where('pp.estado', '1')
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
                'pedidos.motivo',
                'pedidos.responsable',
                /*'pedidos.created_at as fecha',*/
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
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

                        $btn='<a href="'.route('pedidosPDF', $pedido).'" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                        $btn=$btn.'<a href="'.route('pedidos.show', $pedido).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> VER</a>';

                        if($pedido->estado>0){

                            if(Auth::user()->rol == "Super asesor" || Auth::user()->rol =="Administrador" || Auth::user()->rol == "Encargado")
                            {
                                $btn=$btn.'<a href="'.route('pedidos.edit', $pedido->id).'" class="btn btn-warning btn-sm">Editar</a>';
                            }                            

                            //if($pedido->diferencia >3)
                            //{
                                if(Auth::user()->rol =='Administrador')
                                {
                                    $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pedido->id.'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Anular</button></a>';
                                }
                            //}                            
                        }

                        if($pedido->estado==0){
                            if(Auth::user()->rol =='Administrador')
                            {
                                $btn = $btn.'<a href="" data-target="#modal-restaurar" data-toggle="modal" data-restaurar="'.$pedido->id.'"><button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Restaurar</button></a>';
                            }
                            

                        }
                        
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
    public function create()
    {   
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $users = User::where('estado', '1')->pluck('identificador', 'id');

        if(Auth::user()->rol == "Asesor"){
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
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
            /*$clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
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
                    'clientes.direccion'
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
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);*/
            /*$clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
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
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);*/
            $deudores = Cliente::where('estado', '1')
                                ->where('user_id', Auth::user()->id)
                                ->where('tipo', '1')
                                ->where('deuda', '1')
                                ->get();

            $clientes_ruc = Cliente:://TODOS LOS CLIENTES
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

        }else if(Auth::user()->rol == "Super asesor"){
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
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
            /*$clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
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
                    'clientes.direccion'
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
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);*/
            /*$clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
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
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);*/
            $deudores = Cliente::where('estado', '1')
                                ->where('user_id', Auth::user()->id)
                                ->where('tipo', '1')
                                ->where('deuda', '1')
                                ->get();

            $clientes_ruc = Cliente:://TODOS LOS CLIENTES
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
        }else{
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
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
            /*$clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
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
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);*/
            /*$clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);*/
            $deudores = Cliente::where('estado', '1')
                                ->where('tipo', '1')
                                ->where('deuda', '1')
                                ->get();
            $clientes_ruc = Cliente:://TODOS LOS CLIENTES
                            where('clientes.estado','1')
                            ->where('clientes.tipo','1')
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

        $rucs = Ruc::where('user_id', Auth::user()->id)
                    ->where('estado', '1')
                    ->pluck('num_ruc', 'num_ruc');

        $fecha = Carbon::now()->format('dm');
        $dia = Carbon::now()->toDateString();
        /* $numped = DetallePedido:://CAMBIAR A PEDIDO
                    join('pedidos as p', 'detalle_pedidos.pedido_id', 'p.id')                    
                    ->where(DB::raw('Date(detalle_pedidos.created_at)'), $dia)
                    ->where('p.user_id', Auth::user()->id)
                    ->groupBy(DB::raw('Date(detalle_pedidos.created_at)'))
                    ->count(); */
        $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
                    ->where('user_id', Auth::user()->id)
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->count();
        $numped = $numped + 1;
        
        return view('pedidos.create', compact('users', 'clientes1', 'dateM', 'dateY','deudores', 'meses', 'anios', 'rucs', 'fecha', 'numped', 'clientes_ruc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function ruc(Request $request)//rucs
    {
        if (!$request->cliente_id) {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::where('rucs.cliente_id', $request->cliente_id)->get();        
            foreach ($rucs as $ruc) {
                $html .= '<option value="' . $ruc->num_ruc . '">' . $ruc->num_ruc . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function cliente()//clientes
    {        
        $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        $clientes = Cliente::where('clientes.user_id', Auth::user()->id)
                            ->where('clientes.tipo', '1')
                            ->get();        
        foreach ($clientes as $cliente) {
            $html .= '<option value="' . $cliente->id . '">' . $cliente->celular. '-' . $cliente->nombre . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function tipobanca(Request $request)//pedidoscliente
    {
        if (!$request->cliente_id) {
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
     
            if ($validator->fails()) {
                return redirect('pedidos/create')
                            ->withErrors($validator)
                            ->withInput();
            } 
        }

            $ruc = Ruc::create([
                'num_ruc' => $request->agregarruc,
                'user_id' => Auth::user()->id,
                'cliente_id' => $request->cliente_id_ruc,
                'estado' => '1'
            ]);        

        return redirect()->route('pedidos.create')->with('info', 'registrado');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
        ]);
        
        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => $request->user_id, //usuario que registra
                'creador_id' => 'USER0'.Auth::user()->id,//aqui una observacion, en el migrate la columna en tabla pedido tenia nombre creador y resulto ser creador_id
                'condicion' => 'POR ATENDER',
                'pago' => '0',
                'envio' => '0',
                'condicion_envio' => 'PENDIENTE DE ENVIO',
                'estado' => '1',
                'codigo' => $request->codigo[0],
                'notificacion' => 'Nuevo pedido creado',
                'modificador' => 'USER0'.Auth::user()->id,
                'pagado' => '0',
                'direccion' => '0'
            ]);
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
        } catch (\Throwable $th) {
            throw $th;
            /* DB::rollback();
            dd($th); */
        }

        //NOTIFICATION
        event(new PedidoEvent($pedido));

        if(Auth::user()->rol == "Asesor"){
            /* return redirect()->route('pedidos.mispedidos')->with('info', 'registrado'); */
            return redirect()->route('pedidosPDF', $pedido)->with('info', 'registrado');
        }
        else 
            /* return redirect()->route('pedidos.index')->with('info', 'registrado'); */
            return redirect()->route('pedidosPDF', $pedido)->with('info', 'registrado');
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

        return view('pedidos.edit', compact('pedido', 'pedidos', 'meses', 'anios','porcentajes', 'imagenes'));
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

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.misPedidos', compact('destinos', 'superasesor', 'dateMin', 'dateMax'));
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

    public function Atendidos()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => 'POR ATENDER',
            "EN PROCESO ATENCION" => 'EN PROCESO ATENCION',
            "ATENDIDO" => 'ATENDIDO'
        ];

        /* $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ]; */

        if(Auth::user()->rol == "Operario"){
            $pedidos = Pedido::/* join('clientes as c', 'pedidos.cliente_id', 'c.id')
                -> */join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    /* 'c.nombre as nombres', */
                    /* 'c.celular as celulares', */
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    /* 'dp.fecha_envio_doc_fis', */
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->groupBy(
                    'pedidos.id',
                    /* 'c.nombre', */
                    /* 'c.celular', */
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
            $pedidos = Pedido::/* join('clientes as c', 'pedidos.cliente_id', 'c.id')
                -> */join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    /* 'c.nombre as nombres',
                    'c.celular as celulares', */
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    /* 'dp.total as total', */
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    /* 'dp.fecha_envio_doc_fis', */
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', 'ATENDIDO')
                ->groupBy(
                    'pedidos.id',
                    /* 'c.nombre',
                    'c.celular', */
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    /* 'dp.total', */
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
                    /* 'dp.fecha_envio_doc_fis', */
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
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
                /*->take('200')*/
                ->get();
                /*->simplePaginate(1000);*/
            } 

        $imagenes = ImagenAtencion::where('estado', '1')->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.atendidos', compact('dateMin', 'dateMax', 'pedidos', 'condiciones', 'superasesor'));//, 'imagenes'
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
