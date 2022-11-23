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
use App\Models\DireccionGrupo;
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

class EnvioController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function Enviosrutaenvio()
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

        $departamento = Departamento::where('estado', "1")
                ->pluck('departamento', 'departamento');    

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                            ->where('estado', '1')
                            ->pluck('distrito', 'distrito');

        $direcciones = DireccionEnvio::join('direccion_pedidos as dp', 'direccion_envios.id', 'dp.direccion_id')
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
                           
                            ->get();        
           
        $superasesor = User::where('rol', 'Super asesor')->count();
        
        $ver_botones_accion = 1;
        
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

        $dateMin = Carbon::now()/*->subDays(4)*/->format('d/m/Y');

        return view('envios.rutaenvio', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor','ver_botones_accion','departamento','dateMin'));
    }
    public function Enviosrutaenviotabla(Request $request)
    {
        $pedidos=null;//21/11/2022

        $pedidos_lima=null;
        $pedidos_provincia=null;
        
        if(!$request->general)
        {

        }else{

            //busca general nada mas
            //$min = Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');
            $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->where(function ($query) {
                                        
                                    })
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'LIMA') as destino "),
                                        'de.celular',
                                        'de.nombre',
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.direccion',
                                        'de.referencia',
                                        'de.observacion',                                        
                                        'de.distrito',
                                        'direccion_grupos.created_at as fecha'
                                    );

            $pedidos_lima->where( DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id) "),'like','%'.$request->general.'%')
                                              ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                                              ->orWhere('u.identificador', 'like','%'.$request->general.'%')
                                              ->orWhere('de.celular', 'like','%'.$request->general.'%')
                                              ->orWhere('de.nombre', 'like','%'.$request->general.'%')
                                              ->orWhere('de.cantidad', 'like','%'.$request->general.'%')
                                              ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                                              ->orWhere( DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) "),'like','%'.$request->general.'%')
                                              ->orWhere('de.direccion', 'like','%'.$request->general.'%')
                                              ->orWhere('de.referencia', 'like','%'.$request->general.'%')
                                              ->orWhere('de.observacion', 'like','%'.$request->general.'%')
                                              ->orWhere('de.distrito', 'like','%'.$request->general.'%');

            $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')                                    
                                    ->where(DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) "),'like','%'.$request->general.'%')
                                    //->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'PROVINCIA') as destino "),
                                        DB::raw(" (select '') as celular "),
                                        DB::raw(" (select '') as nombre "),
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.tracking as direccion',
                                        'de.foto as referencia',
                                        DB::raw(" (select '') as observacion "),
                                        DB::raw(" (select '') as distrito "),
                                        'direccion_grupos.created_at as fecha'
                                    );

            $pedidos_provincia->where(DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) "),'like','%'.$request->general.'%')
                                            ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                                            ->orWhere('de.cantidad', 'like','%'.$request->general.'%')
                                            ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                                            ->orWhere(DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) "),'like','%'.$request->general.'%')
                                            ->orWhere('de.tracking', 'like','%'.$request->general.'%');

        }

        if(!$request->desde)
        {

        }else{
            //busca solo el dia nada mas

            $min = Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');

            $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'LIMA') as destino "),
                                        'de.celular',
                                        'de.nombre',
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.direccion',
                                        'de.referencia',
                                        'de.observacion',                                        
                                        'de.distrito',
                                        'direccion_grupos.created_at as fecha'
                                    );

            $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'PROVINCIA') as destino "),
                                        DB::raw(" (select '') as celular "),
                                        DB::raw(" (select '') as nombre "),
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.tracking as direccion',
                                        'de.foto as referencia',
                                        DB::raw(" (select '') as observacion "),
                                        DB::raw(" (select '') as distrito "),
                                        'direccion_grupos.created_at as fecha'
                                    );                                    

        }

        
           
        
        

        $pedidos = $pedidos_lima->union($pedidos_provincia);
        //$pedidos=$pedidos->where(DB::raw('DATE(direccion_grupos.created_at)'), $request->desde);
        $pedidos=$pedidos->get();
        //$pedidos=$pedidos_provincia;


        return Datatables::of($pedidos_lima)
            ->addIndexColumn()
            ->addColumn('action', function($pedido){     
                $btn='';                         
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);



    }


    public function Enviosporrecibir()
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

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                            ->where('estado', '1')
                            ->pluck('distrito', 'distrito');

        $direcciones = DireccionEnvio::join('direccion_pedidos as dp', 'direccion_envios.id', 'dp.direccion_id')
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
                           
                            ->get();        
        $departamento = Departamento::where('estado', "1")
                            ->pluck('departamento', 'departamento');   
        $superasesor = User::where('rol', 'Super asesor')->count();
        
        $ver_botones_accion = 1;
        
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

        return view('envios.porRecibir', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor','ver_botones_accion','departamento'));
    }

    public function Enviosporrecibirtabla(Request $request)
    {
        $pedidos=null;

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
                ->where('pedidos.envio',  '1')
                ->where('pedidos.condicion_envio', '<>', 'ENTREGADO')
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
                );
        if(Auth::user()->rol == "Operario"){
            $asesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos=$pedidos->WhereIn('u.identificador',$asesores);
            
        }else if(Auth::user()->rol == "Jefe de operaciones"){
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

        }else if(Auth::user()->rol == "Asesor"){
            $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);
            
        }
        else if(Auth::user()->rol == "Super asesor"){
            $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);

        }
        else if(Auth::user()->rol == "Encargado"){
            $pedidos=$pedidos->Where('u.supervisor',Auth::user()->identificador);
        }
        else{
            $pedidos=$pedidos;
        }
        $pedidos=$pedidos->get();
        
        return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){     
                        $btn='';                         
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

    }
}