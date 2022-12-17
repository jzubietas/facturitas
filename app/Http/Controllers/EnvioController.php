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

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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

    public function Enviosenreparto()//SOBRES EN REPARTO
    {

        $distribuir = [
            "NORTE" => 'NORTE',
            "CENTRO" => 'CENTRO',
            "SUR" => 'SUR',
        ];

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

        $departamento = Departamento::where('estado', "1")
                            ->pluck('departamento', 'departamento');

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

        return view('envios.porEnviar', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor','ver_botones_accion','departamento','distribuir'));
    }

    public function Enviosenrepartotabla(Request $request)
    {
        $pedidos=null;

        $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
           // ->join('pedidos as p', 'p.codigo', 'direccion_grupos.codigos')

           ->where('direccion_grupos.condicion_envio_code',Pedido::EN_REPARTO_INT)
          
            //->where('p.condicion_envio_code',Pedido::EN_REPARTO_INT)
            ->where('direccion_grupos.estado','1')
           // ->whereNull('direccion_grupos.subcondicion_envio')
            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'LIMA') as destino "),
                'de.celular',
                'de.nombre',
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.direccion',
                'de.referencia',
                'de.observacion',
                'de.distrito',
                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),

                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.correlativo as correlativo'
            );

        
        $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
          //  ->join('pedidos as p', 'p.codigo', 'direccion_grupos.codigos')

          //  ->where('p.condicion_envio_code',Pedido::EN_REPARTO_INT)
            ->where('direccion_grupos.condicion_envio_code',Pedido::EN_REPARTO_INT)
            ->where('direccion_grupos.estado','1')
         //   ->whereNull('direccion_grupos.subcondicion_envio')

            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'PROVINCIA') as destino "),
                DB::raw(" (select '') as celular "),
                DB::raw(" (select '') as nombre "),
                'de.cantidad',

                'direccion_grupos.codigos',
                'direccion_grupos.producto',

                'de.tracking as direccion',
                'de.foto as referencia',
                DB::raw(" (select '') as observacion "),
                DB::raw(" (select '') as distrito "),

                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                //'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.correlativo as correlativo',
            );

        if(Auth::user()->rol == "Asesor"){
            $pedidos_lima=$pedidos_lima->Where('u.identificador',Auth::user()->identificador);

          //  $pedidos_provincia=$pedidos_provincia->Where('u.identificador',Auth::user()->identificador);

        }else if(Auth::user()->rol == "Encargado"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos_lima=$pedidos_lima->WhereIn('u.identificador',$usersasesores);
           // $pedidos_provincia=$pedidos_provincia->WhereIn('u.identificador',$usersasesores);
        }else if(Auth::user()->rol == "Jefe de llamadas"){
            $pedidos_lima=$pedidos_lima->where('u.identificador','<>','B');
           // $pedidos_provincia=$pedidos_provincia->where('u.identificador','<>','B');
        }else if(Auth::user()->rol == "Llamadas"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos_lima=$pedidos_lima->WhereIn('u.identificador',$usersasesores);
           // $pedidos_provincia=$pedidos_provincia->WhereIn('u.identificador',$usersasesores);
        }


        //$pedidos = $pedidos_lima //->union($pedidos_provincia);
        $pedidos=$pedidos_lima->get();


        return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

    }




    public function DistribuirEnvioid(Request $request)
    {

        $envio=DireccionGrupo::where("id",$request->hiddenDistribuir)->first();

        $envio->update([
            'distribucion' => $request->distribuir,
            //'modificador' => 'USER'.Auth::user()->id
        ]);

        return response()->json(['html' => $request->hiddenEnviar]);

    }

    public function Entregados()//ENTREGADOS
    {

        $distribuir = [
            "NORTE" => 'NORTE',
            "CENTRO" => 'CENTRO',
            "SUR" => 'SUR',
        ];

        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "PENDIENTE DE ENVIO" => 'PENDIENTE DE ENVIO',
            "EN REPARTO" => 'EN REPARTO',
            "ENTREGADO" => 'ENTREGADO'
        ];

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('envios.entregados', compact('dateMin', 'dateMax', 'condiciones', 'superasesor'));
    }

    public function Entregadostabla()//ENTREGADOS
    {
        $mirol=Auth::user()->rol;


        $pedidos=null;

        $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
           // ->where('direccion_grupos.condicion_envio',DireccionGrupo::CE_ENTREGADO)
            ->where('direccion_grupos.condicion_envio_code',DireccionGrupo::CE_ENTREGADO_CODE)

            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'LIMA') as destino "),
                'de.celular',
                'de.nombre',
                'de.cantidad',
                //DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
               // DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.direccion',
                'de.referencia',
                'de.observacion',
                'de.distrito',
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.foto1',
                'direccion_grupos.foto2',
                'direccion_grupos.correlativo'
            );

        $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.condicion_envio',DireccionGrupo::CE_ENTREGADO)
            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'PROVINCIA') as destino "),
                DB::raw(" (select '') as celular "),
                DB::raw(" (select '') as nombre "),
                'de.cantidad',
                //DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
               // DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.tracking as direccion',
                'de.foto as referencia',
                DB::raw(" (select '') as observacion "),
                DB::raw(" (select '') as distrito "),
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.foto1',
                'direccion_grupos.foto2',
                'direccion_grupos.correlativo'
            );

        $pedidos = $pedidos_lima->union($pedidos_provincia);
        //$pedidos=$pedidos->where(DB::raw('DATE(direccion_grupos.created_at)'), $request->desde);
        //$pedidos=$pedidos->get();


        if(Auth::user()->rol == "Operario"){

            $asesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
                $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);

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
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
                $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);


        }else if(Auth::user()->rol == "Asesor"){

                $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);

       }else if(Auth::user()->rol == "Encargado"){

        }else{

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

    public function Enviosrutaenvio()
    {

        $rol=Auth::user()->rol;
        $distribuir = [
            "NORTE" => 'NORTE',
            "CENTRO" => 'CENTRO',
            "SUR" => 'SUR',
        ];

        $condiciones = [
            "1" => 1,
            "2" => 2,
            "3" => 3
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

        return view('envios.rutaenvio', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor','ver_botones_accion','departamento','dateMin','distribuir','rol'));
    }
 

    public function Enviosrutaenviotabla(Request $request)
    {


       


        $pedidos=null;//21/11/2022
        $pedidos_lima=null;
        $pedidos_provincia=null;

        if(!$request->general )
        {

        }
        
        else{

            if (empty($request->general))
            {
                    dd($request->general);
            }

            else {


                //busca general nada mas
            //$min = Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');
            $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
         //   ->whereNull('direccion_grupos.condicion_sobre')
            //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'LIMA') as destino "),
                'de.celular',
                'de.nombre',
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.direccion',
                'de.referencia',
                'de.observacion',
                'de.distrito',
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.correlativo',

            );
            
            
            
        
            
            
            $pedidos_lima->where( 'direccion_grupos.codigos','like','%'.$request->general.'%')
                      ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                      ->orWhere('u.identificador', 'like','%'.$request->general.'%')
                      ->orWhere('de.celular', 'like','%'.$request->general.'%')
                      ->orWhere('de.nombre', 'like','%'.$request->general.'%')
                      ->orWhere('de.cantidad', 'like','%'.$request->general.'%')
                      ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                      ->orWhere('direccion_grupos.producto','like','%'.$request->general.'%')
                      ->orWhere('de.direccion', 'like','%'.$request->general.'%')
                      ->orWhere('de.referencia', 'like','%'.$request->general.'%')
                      ->orWhere('de.observacion', 'like','%'.$request->general.'%')
                      ->orWhere('de.distrito', 'like','%'.$request->general.'%');

$pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
          //  ->whereNull('direccion_grupos.condicion_sobre')
            //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'PROVINCIA') as destino "),
                DB::raw(" (select '') as celular "),
                DB::raw(" (select '') as nombre "),
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                DB::raw(" (select '') as observacion "),
                'de.foto as referencia',
               'de.tracking as direccion',
                DB::raw(" (select '') as distrito "),
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.correlativo'
            );
            $pedidos_provincia->where( 'direccion_grupos.codigos','like','%'.$request->general.'%')
                    ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                    ->orWhere('de.cantidad', 'like','%'.$request->general.'%')
                    ->orWhere('direccion_grupos.id', 'like','%'.$request->general.'%')
                    ->orWhere('direccion_grupos.producto','like','%'.$request->general.'%')
                    ->orWhere('de.tracking', 'like','%'.$request->general.'%');


            }
           

            

        }

        if(!$request->desde)
        {


        }
        
        
        else{
            //busca solo el dia nada mas

            $min = Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');//2022-11-25
            //return $min;

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
                                        'direccion_grupos.codigos',
                                        'direccion_grupos.producto',
                                        'de.direccion',
                                        'de.referencia',
                                        'de.observacion',
                                        'de.distrito',
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion',
                                        'direccion_grupos.condicion_sobre',
                                        'direccion_grupos.correlativo',
                                        DB::raw('DATE(direccion_grupos.created_at) fecha2')
                                    );

            $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    //->whereNotIn('direccion_grupos.condicion_sobre',['SIN ENVIO'])
                                    //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'PROVINCIA') as destino "),
                                        DB::raw(" (select '') as celular "),
                                        DB::raw(" (select '') as nombre "),
                                        'de.cantidad',
                                        'direccion_grupos.codigos',
                                        'direccion_grupos.producto',
                                        'de.tracking as direccion',
                                        'de.foto as referencia',
                                        DB::raw(" (select '') as observacion "),
                                        DB::raw(" (select '') as distrito "),
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion',
                                        'direccion_grupos.condicion_sobre',
                                        'direccion_grupos.correlativo',
                                        DB::raw('DATE(direccion_grupos.created_at) fecha2')
                                    );
                                  //  $pedidos_provincia->whereNot

                                  $pedidos = $pedidos_lima->union($pedidos_provincia);
                                  //$pedidos=$pedidos->where(DB::raw('DATE(direccion_grupos.created_at)'), $request->desde);
                                  $pedidos=$pedidos->get();
                                  //$pedidos=$pedidos_provincia;

        }

       


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
            "1" => 1,
            "2" => 2,
            "3" => 3
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
        $filtros_code=[6,7];

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'pedidos.correlativo as id2',
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
                    'pedidos.estado_sobre',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                )
                  // 14-12-22 se realiza la consulta con el filtro por la columna  p.condicion_code=13
                  ->WhereIn('pedidos.condicion_envio_code',$filtros_code)
               //->where('pedidos.condicion_envio_code', Pedido::COURIER_INT);
              // ->where('pedidos.condicion_envio_code', Pedido::SOBRE_ENVIAR_INT);
              //  ->where('dp.estado', '1')
                ->where('pedidos.envio', '2')  //estado del sobre anterior
                ->where('pedidos.estado', '1'); 

           // esta query esta mal formulada ->where('pedidos.condicion_envio', '<>', 3);

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
        else if(Auth::user()->rol == "Llamadas"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos=$pedidos->WhereIn('u.identificador',$usersasesores);
        }else if(Auth::user()->rol == "Jefe de llamadas"){
            $pedidos=$pedidos->where('u.identificador','<>','B');
        }else{
            $pedidos=$pedidos;
        }
        //$pedidos=$pedidos->get();

        return Datatables::of(DB::table($pedidos))
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

    }

    public function EnviarPedidoid(Request $request)
    {
        //hiddenEnviar  pedido
        //fecha_envio_doc_fis
        //fecha_recepcion
        //adjunto
        //adjunto2
        //condicion
        //$pedido=Pedido::where("id",$request->hiddenEnviar)->first();


        $envio=DireccionGrupo::where("id",$request->hiddenEnviar)->first();

        //$detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();

        $envio->update([
            'subcondicion_envio' => $request->condicion,//entregado
            //'trecking' => $request->trecking,
            'modificador' => 'USER'.Auth::user()->id
        ]);



        if ($request->condicion == 'ENTREGADO' )
        {
            $envio->update([
                'condicion_envio' => Pedido::ENTREGADO,
                'condicion_envio_code' => Pedido::ENTREGADO_INT,
                
            ]);

        }




        $files = $request->file('foto1');
        $files2 = $request->file('foto2');

        $destinationPath = base_path('public/storage/entregas/');

        if ($request->hasFile('foto1') && $request->hasFile('foto2')){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $file_name2 = Carbon::now()->second.$files2->getClientOriginalName();

            $files->move($destinationPath , $file_name);
            $files2->move($destinationPath , $file_name2);

            $envio->update([
                'foto1' => $file_name,
                'foto2' => $file_name2,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        }
        else if ($request->hasFile('foto1') && $request->foto2 == null){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);

            $envio->update([
                'foto1' => $file_name,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        }
        else if ($request->foto1 == null && $request->hasFile('foto2')){
            $file_name2 = Carbon::now()->second.$files2->getClientOriginalName();
            $files2->move($destinationPath , $file_name2);

            $envio->update([
                'foto2' => $file_name2,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        }
        else
        {
            $envio->update([
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        }

        return response()->json(['html' => $request->hiddenEnviar]);

        /*if($request->vista == 'ENTREGADOS')
        {
            return redirect()->route('envios.enviados')->with('info','actualizado');
        }*/

        //return redirect()->route('envios.index')->with('info','actualizado');
    }

    public function changeImg(Request $request)
    {
        $item = $request->item;
        $pedido=$request->pedido;
        $file = $request->file('adjunto');

        if(isset($file)){
            $destinationPath = base_path('public/storage/entregas/');
            $cont = 0;
            $file_name = Carbon::now()->second.$file->getClientOriginalName();
            $fileList[$cont] = array(
                'file_name' => $file_name,
            );
            $file->move($destinationPath , $file_name);
            $html=$pedido.'_'.$item.'_'.$file_name;



            DireccionGrupo::where('id', $pedido)
                ->update([
                    'foto'.$item => $file_name
                ]);
        }else{
            $html="";
        }

        return response()->json(['html' => $html]);
    }



    public function Seguimientoprovincia()//SOBRES EN REPARTO
    {

        $distribuir = [
            "NORTE" => 'NORTE',
            "CENTRO" => 'CENTRO',
            "SUR" => 'SUR',
        ];

        $condiciones = [
            "1" => 1,
            "2" => 2,
            "3" => 3
        ];

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                            ->where('estado', '1')
                            ->pluck('distrito', 'distrito');

        $departamento = Departamento::where('estado', "1")
                            ->pluck('departamento', 'departamento');

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

        return view('envios.seguimientoProvincia', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor','ver_botones_accion','departamento','distribuir'));
    }

    public function Seguimientoprovinciatabla(Request $request)
    {
        $pedidos=null;


        $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.condicion_envio_code',Pedido::SEG_PROVINCIA_INT)
           // ->whereNull('direccion_grupos.subcondicion_envio')
            //->whereIn('direccion_grupos.subcondicion_envio',['REGISTRADO','EN CAMINO','EN TIENDA/AGENTE','NO ENTREGADO'])
            ->select(
                'direccion_grupos.id',
                'u.identificador as identificador',
                DB::raw(" (select 'PROVINCIA') as destino "),
                DB::raw(" (select '') as celular "),
                DB::raw(" (select '') as nombre "),
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                
                //DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
              //  DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                'de.tracking as direccion',
                'de.foto as referencia',
                DB::raw(" (select '') as observacion "),
                DB::raw(" (select '') as distrito "),
                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.condicion_sobre',
            );

        //$pedidos = $pedidos_lima->union($pedidos_provincia);
        $pedidos = $pedidos_provincia;
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

    public function Recibirid(Request $request)

    {


       // dd($request);
      //  exit;


        $pedido=Pedido::where("id",$request->hiddenRecibir)->first();
        $direccion_grupos=DireccionGrupo::where("codigos",$pedido->codigo)->first();
        $localizacion=$pedido->condicion_envio_code;
      

        /* si es lima */
        if ($localizacion==7)

        {

            $pedido->update([

                //'envio' => '1',
                'envio' => '2',
                'estado_sobre' => '1',
                'condicion_envio'=>Pedido::EN_REPARTO,
                'condicion_envio_code'=>Pedido::EN_REPARTO_INT,
                'modificador' => 'USER'.Auth::user()->id
            ]);

            $direccion_grupos->update([

                'condicion_envio'=>Pedido::EN_REPARTO,
                'condicion_envio_code'=>Pedido::EN_REPARTO_INT,
                'modificador' => 'USER'.Auth::user()->id,
                'pedido_id' => $request->hiddenRecibir
            ]);
    

        }

        /* si es provincia */

        if ($localizacion==6)

            {

                $pedido->update([

                    //'envio' => '1',
                    'envio' => '2',
                    'estado_sobre' => '1',
                    'condicion_envio'=>Pedido::SEG_PROVINCIA,
                    'condicion_envio_code'=>Pedido::SEG_PROVINCIA_INT,
                    'modificador' => 'USER'.Auth::user()->id
                ]);


                $direccion_grupos->update([

                    'condicion_envio'=>Pedido::SEG_PROVINCIA,
                    'condicion_envio_code'=>Pedido::SEG_PROVINCIA_INT,
                    'modificador' => 'USER'.Auth::user()->id,
                    'pedido_id' => $request->hiddenRecibir
                ]);

            }

        // actualizando en direccion_grupos

      


      



        return response()->json(['html' => $request->hiddenRecibir]);

        //return redirect()->route('envios.index')->with('info','actualizado');
    }

    public function DireccionEnvio(Request $request)
    {

        /*

        pruebas de actualizacion


        */

        //return $request->all();
        $pedidos=$request->pedidos;
        if(!$request->pedidos)
        {
            return '0';
        }
        else{


          

            /* actualizando el estado en la tabla pedido por id a  los nuevos estados */

            $_destino=$request->destino;
            $_pedido = Pedido::find($request->cod_pedido);
            

                    
            if ($_destino=='LIMA')

            {
                    $_pedido->update([
                        'condicion_envio' => Pedido::SOBRE_ENVIAR,
                        'condicion_envio_code' => Pedido::SOBRE_ENVIAR_INT
                    ]);

            }

            else {

                $_pedido->update([
                    'condicion_envio' => Pedido::COURIER,
                    'condicion_envio_code' => Pedido::COURIER_INT
                ]);

            }
        

            

            



             /* agregando pedidos a la tabla direccion_grupos (campos codigos, productos) */

             $lista_productos='';
             $lista_codigos='';
             $pedidos=$request->pedidos;
             $array_pedidos=explode(",",$pedidos);

             $data=DetallePedido::wherein("pedido_id",$array_pedidos)->get();
             foreach ($data as $dat ) { $lista_productos.=$dat->nombre_empresa. ", "; $lista_codigos.=$dat->codigo. ", "; }
             $lista_codigos = rtrim ($lista_codigos, ", ");
             $lista_productos = rtrim ($lista_productos, ", ");

             /* fin */

            //cliente
            $cliente=Cliente::where("id",$request->cliente_id)->first();

            // agregando nuevo correlativo en tabla

           
            $count_pedidos=count((array)$array_pedidos);

           $usuario = Cliente::find($request->cliente_id);
           $usuario_id=$usuario->user_id;

            if ($request->destino == "LIMA")
            {


                                 
                        $direccion_grupo_id=DireccionGrupo::create([
                            'estado'=>'1',
                            'destino' => $request->destino,
                            'distribucion'=> ( ($request->destino=='PROVINCIA')? 'NORTE':''),
                            'nombre_cliente'=> ( ($request->destino=='LIMA')? $request->nombre : $cliente->nombre  ),
                            'celular_cliente'=> ( ($request->destino=='LIMA')? $request->contacto : $cliente->celular."-".$cliente->icelular ),
                            'codigos'=>$lista_codigos,
                            'producto'=>$lista_productos,
                            'condicion_envio' => Pedido::SOBRE_ENVIAR ,
                            'condicion_envio_code' => Pedido::SOBRE_ENVIAR_INT ,
                            'pedido_id'=>$request->cod_pedido,
                            'cliente_id'=>$request->cliente_id,
                            'user_id'=>$usuario_id

                        ])->id;


                $direccion_grupo = DireccionGrupo::find($direccion_grupo_id);
                $direccion_grupo->correlativo = 'ENV'.$direccion_grupo_id;
                $direccion_grupo->save();


                try {
                    DB::beginTransaction();



           


            

                    $cantidad=$count_pedidos;

                    $direccionLima = DireccionEnvio::create([
                        'cliente_id' => $request->cliente_id,
                        'distrito' => $request->distrito,
                        'direccion' => $request->direccion,
                        'referencia' => $request->referencia,
                        'nombre' => $request->nombre,
                        'celular' => $request->contacto,
                        'observacion' => $request->observacion,
                        'direcciongrupo' => $direccion_grupo_id,
                        'cantidad' => $cantidad,
                        'destino'=>$request->destino,
                        'estado' => '1',
                        "salvado"=> "0"
                    ]);


                    // ALMACENANDO DIRECCION-PEDIDOS
                    $pedido_id = $request->pedido_id;
                    $contPe = 0;

                    foreach($array_pedidos as $pedido_id)
                    {
                        $pedido = Pedido::find($pedido_id);





                        $pedido->update([
                            'destino' => $request->destino,
                            'condicion_envio' => 2,//AL REGISTRAR DIRECCION PASA A ESTADO  EN REPARTO
                            'direccion' => $request->direccion,
                            'condicion_envio' => Pedido::SOBRE_ENVIAR ,
                            'condicion_envio_code' => Pedido::SOBRE_ENVIAR_INT ,

                        ]);



                        $dp_empresa=DetallePedido::where("pedido_id",$pedido_id)->first();
                            /*->update([
                                'fecha_envio_doc_fis'=>Carbon()::now()
                            ]);*/


                        $direccionPedido = DireccionPedido::create([
                                'direccion_id' => $direccionLima->id,
                                'pedido_id' => $pedido_id,
                                'codigo_pedido' => $dp_empresa->codigo,
                                'direcciongrupo' => $direccion_grupo_id,
                                'empresa' => $dp_empresa->nombre_empresa,
                                'estado' => '1'
                            ]);

                        //INDICADOR DE DIRECCION




                       // $contPe++;
                    }

                    if($request->saveHistoricoLima=="1")
                    {
                        //temporal lima
                        $direccionLima->update([
                            "salvado"=>"1"
                        ]);
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

                    
                    $direccion_grupo_id=DireccionGrupo::create([
                        'estado'=>'1',
                        'destino' => $request->destino,
                        'distribucion'=> ( ($request->destino=='PROVINCIA')? 'NORTE':''),
                        'nombre_cliente'=> ( ($request->destino=='LIMA')? $request->nombre : $cliente->nombre  ),
                        'celular_cliente'=> ( ($request->destino=='LIMA')? $request->contacto : $cliente->celular."-".$cliente->icelular ),
                        'codigos'=>$lista_codigos,
                        'producto'=>$lista_productos,
                        'condicion_envio' => Pedido::COURIER,
                        'condicion_envio_code' => Pedido::COURIER_INT,
                        'pedido_id'=>$request->cod_pedido,
                        'cliente_id'=>$request->cliente_id,
                        'user_id'=>$usuario_id
                    ])->id;


                    $direccion_grupo = DireccionGrupo::find($direccion_grupo_id);
                    $direccion_grupo->correlativo = 'ENV'.$direccion_grupo_id;
                    $direccion_grupo->save();

                    DB::beginTransaction();

                    //IMPORTE
                    //$importe = $request->importe;


                    //$importe=str_replace(',','',$importe);
                    $cantidad=$count_pedidos;
                    //FOTO
                    $files = $request->file('rotulo');
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
                        'registro' => $request->numregistro,
                        'foto' => $file_name,
                        'importe' => "0.00",
                        'cantidad' => $cantidad,
                        'direcciongrupo' => $direccion_grupo_id,
                        'destino'=>$request->destino,
                        'estado' => '1',
                        "salvado"=> "0"
                    ]);

                    // ALMACENANDO DIRECCION-PEDIDOS
                    //$pedido_id = $request->pedido_id;
                    //$contPe = 0;
                    foreach($array_pedidos as $pedido_id)
                    {
                        $pedido = Pedido::find($pedido_id);

                        $pedido->update([
                            'destino' => $request->destino,
                            'condicion_envio' => 2,//AL REGISTRAR DIRECCION PASA A ESTADO  EN REPARTO
                            'direccion' => '1',
                            'condicion_envio' => Pedido::COURIER,
                            'condicion_envio_code' => Pedido::COURIER_INT,
                        ]);

                        $dp_empresa=DetallePedido::where("pedido_id",$pedido_id)->first();

                        /*$dp_empresa=DetallePedido::where("pedido_id",$pedido_id)->first()
                            ->update([
                                'fecha_envio_doc_fis'=>Carbon()::now()
                            ]);*/

                        $gastoPedido = GastoPedido::create([
                            'gasto_id' => $gastoProvincia->id,
                            'pedido_id' => $pedido_id,
                            'codigo_pedido' => $dp_empresa->codigo,
                            'direcciongrupo' => $direccion_grupo_id,
                            'empresa' => $dp_empresa->nombre_empresa,
                            'estado' => '1'
                        ]);

                        //INDICADOR DE PAGOS
                        $pedido = Pedido::find($pedido_id);


                        //$contPe++;
                    }

                    if($request->saveHistoricoLima=="1")
                    {
                        //temporal lima
                        $gastoProvincia->update([
                            "salvado"=>"1"
                        ]);
                    }

                    DB::commit();
                } catch (\Throwable $th) {
                    throw $th;
                    /*DB::rollback();
                    dd($th);*/
                }
            }

            return response()->json(['html' => $pedidos]);


        }


        //$pedido=Pedido::where("id",$request->)




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




    public function SinEnviarid(Request $request)
    {
        //Pedido $pedido
        $pedido=Pedido::where("id",$request->hiddenSinenvio)->first();
        $data=DetallePedido::where("pedido_id",$request->hiddenSinenvio)->first();
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();






        $pedido->update([
            'envio' => '3',//SIN ENVIO
            'condicion_envio' => DireccionGrupo::CE_ENTREGADO,
            'condicion_envio_code' => DireccionGrupo::CE_ENTREGADO_CODE,

          //  'condicion_envio' => 'ENTREGADO',
          //  'condicion_envio_code' => 10 ,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
            'fecha_recepcion' => $fecha,
            'atendido_por' => Auth::user()->name,
            'atendido_por_id' => Auth::user()->id,
            'condicion_envio' => DireccionGrupo::CE_ENTREGADO,
            'condicion_envio_code' => DireccionGrupo::CE_ENTREGADO_CODE,
            'pedido_id'=>$request->hiddenSinenvio
        ]);

        /**/
        $cliente=Cliente::where("id",$pedido->cliente_id)->first();

        $data=DetallePedido::where("pedido_id",$request->hiddenSinenvio)->first();

        $direccion_grupo_id=DireccionGrupo::create([
                'estado'=>'1',
                'destino' => 'LIMA',
                'distribucion'=> '',

                'condicion_envio' => DireccionGrupo::CE_ENTREGADO,
                'condicion_envio_code' => DireccionGrupo::CE_ENTREGADO_CODE,

                'condicion_sobre' => 'SIN ENVIO',
                'codigos'=>$data->codigo,
                'producto'=>$data->nombre_empresa,

            ])->id;

         $direccion_grupo = DireccionGrupo::find($direccion_grupo_id);
         $direccion_grupo->correlativo = 'ENV'.$direccion_grupo_id;
         $direccion_grupo->save();

        $direccionLima = DireccionEnvio::create([
            'cliente_id' => $pedido->cliente_id,
            'distrito' => 'LIMA',
            'direccion' => '',
            'referencia' => '',
            'nombre' => $cliente->nombre,
            'celular' => $cliente->celular,
            'observacion' => '',
            'direcciongrupo' => $direccion_grupo_id,
            'cantidad' => 1,
            'destino'=>'LIMA',
            'estado' => '1',
            "salvado"=> "0"
        ]);


        $direccionPedido = DireccionPedido::create([
                'direccion_id' => $direccionLima->id,
                'pedido_id' => $pedido->id,
                'codigo_pedido' => $detalle_pedidos->codigo,
                'direcciongrupo' => $direccion_grupo_id,
                'empresa' => $detalle_pedidos->nombre_empresa,
                'estado' => '1'
            ]);

        return response()->json(['html' => $pedido->id]);
        //return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }


    /* Esta funciòn actualiza al estado envio 4*/


    public function Enviarid(Request $request)
    {
        $pedido=Pedido::where("id",$request->hiddenEnvio)->first();
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '1',
            'condicion_envio' => Pedido::BANCARIZACION,
            'condicion_envio_code' => Pedido::BANCARIZACION_INT,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
        ]);

        return response()->json(['html' => $pedido->id]);

        //return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }

    public function confirmarRecepcionID(Request $request)
    {
        $pedido=Pedido::where("id",$request->hiddenEnvio)->first();

        $pedido->update([
            'envio' => '2',
            'modificador' => 'USER'.Auth::user()->id,
            'condicion_envio' => Pedido::JEFE_OP,
            'condicion_envio_code' => Pedido::JEFE_OP_INT,

        ]);

        return response()->json(['html' => $pedido->id]);
    }

    public function AtenderPedidoOP(Request $request)
    {
        $pedido=Pedido::where("id",$request->hiddenEnvio)->first();

        $pedido->update([
            'envio' => '2',
            'modificador' => 'USER'.Auth::user()->id,
            'condicion_envio' => Pedido::JEFE_OP_CONF,
            'condicion_envio_code' => Pedido::JEFE_OP_CONF_INT,

        ]);

        return response()->json(['html' => $pedido->id]);
    }


}
