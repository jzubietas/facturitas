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

    public function Envios()//SOBRES EN REPARTO
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

    public function Enviostabla(Request $request)
    {
        $pedidos=null;

        $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.condicion_envio',2)
            ->whereNotIn('direccion_grupos.condicion_envio',['REGISTRADO','EN CAMINO','EN TIENDA/AGENTE','NO ENTREGADO'])
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
                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),

                //'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.condicion_sobre',
            );

        $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.condicion_envio',2)
            ->whereNotIn('direccion_grupos.condicion_envio',['REGISTRADO','EN CAMINO','EN TIENDA/AGENTE','NO ENTREGADO'])
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

                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                //'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.condicion_sobre',
            );

        if(Auth::user()->rol == "Asesor"){
            $pedidos_lima=$pedidos_lima->Where('u.identificador',Auth::user()->identificador);

            $pedidos_provincia=$pedidos_provincia->Where('u.identificador',Auth::user()->identificador);

        }else if(Auth::user()->rol == "Encargado"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos_lima=$pedidos_lima->WhereIn('u.identificador',$usersasesores);
            $pedidos_provincia=$pedidos_provincia->WhereIn('u.identificador',$usersasesores);
        }else if(Auth::user()->rol == "Jefe de llamadas"){
            $pedidos_lima=$pedidos_lima->where('u.identificador','<>','B');
            $pedidos_provincia=$pedidos_provincia->where('u.identificador','<>','B');
        }else if(Auth::user()->rol == "Llamadas"){
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos_lima=$pedidos_lima->WhereIn('u.identificador',$usersasesores);
            $pedidos_provincia=$pedidos_provincia->WhereIn('u.identificador',$usersasesores);
        }


        $pedidos = $pedidos_lima->union($pedidos_provincia);
        //$pedidos = $pedidos->where("direccion_grupos.condicion_envio","2");
        //$pedidos=$pedidos->where(DB::raw('DATE(direccion_grupos.created_at)'), $request->desde);
        $pedidos=$pedidos->get();


        /*$pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->select(
                    'pedidos.id',
                    'pedidos.cliente_id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    DB::raw(" (select dp.codigo from detalle_pedidos dp where dp.pedido_id=pedidos.id) as codigos "),
                    DB::raw(" (select dp.nombre_empresa from detalle_pedidos dp where dp.pedido_id=pedidos.id) as empresas "),
                    DB::raw(" (select dp.total from detalle_pedidos dp where dp.pedido_id=pedidos.id) as total "),
                    'pedidos.condicion',
                    'pedidos.created_at as fecha',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    DB::raw(" (select dp.envio_doc from detalle_pedidos dp where dp.pedido_id=pedidos.id) as envio_doc "),
                    DB::raw(" (select dp.fecha_envio_doc from detalle_pedidos dp where dp.pedido_id=pedidos.id) as fecha_envio_doc "),
                    DB::raw(" (select dp.cant_compro from detalle_pedidos dp where dp.pedido_id=pedidos.id) as cant_compro "),
                    DB::raw(" (select dp.fecha_envio_doc_fis from detalle_pedidos dp where dp.pedido_id=pedidos.id) as fecha_envio_doc_fis "),
                    DB::raw(" (select dp.foto1 from detalle_pedidos dp where dp.pedido_id=pedidos.id) as foto1 "),
                    DB::raw(" (select dp.foto2 from detalle_pedidos dp where dp.pedido_id=pedidos.id) as foto2 "),
                    DB::raw(" (select dp.fecha_recepcion from detalle_pedidos dp where dp.pedido_id=pedidos.id) as fecha_recepcion "),
                )
                ->where('pedidos.estado', '1')
                ->where('pedidos.envio', '<>', '1')
                ->where('pedidos.condicion_envio', '<>', 3)
                ->where('pedidos.condicion_envio', 2);
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
        }*/
        //$pedidos=$pedidos->get();

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

    public function Enviados()//ENTREGADOS
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

        return view('envios.enviados', compact('dateMin', 'dateMax', 'condiciones', 'superasesor'));
    }

    public function Enviadostabla()//ENTREGADOS
    {
        $mirol=Auth::user()->rol;


        $pedidos=null;

        $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.condicion_envio',3)
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
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.foto1',
                'direccion_grupos.foto2',
            );

        $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.condicion_envio',3)
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
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.destino as destino2',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_envio',
                'direccion_grupos.condicion_sobre',
                'direccion_grupos.subcondicion_envio',
                'direccion_grupos.foto1',
                'direccion_grupos.foto2',
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

        if(!$request->general)
        {

        }else{

            //busca general nada mas
            //$min = Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');
            $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->whereNull('direccion_grupos.condicion_sobre')
                                    //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'LIMA') as destino "),
                                        'de.celular',
                                        'de.nombre',
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id and dp.estado = 1) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.direccion',
                                        'de.referencia',
                                        'de.observacion',
                                        'de.distrito',
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion',
                                        'direccion_grupos.condicion_sobre',
                                    );

            $pedidos_lima->where( DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id and dp.estado = 1 ) "),'like','%'.$request->general.'%')
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
                                    ->whereNull('direccion_grupos.condicion_sobre')
                                    //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'PROVINCIA') as destino "),
                                        DB::raw(" (select '') as celular "),
                                        DB::raw(" (select '') as nombre "),
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id and dp.estado = 1) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.tracking as direccion',
                                        'de.foto as referencia',
                                        DB::raw(" (select '') as observacion "),
                                        DB::raw(" (select '') as distrito "),
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion',
                                        'direccion_grupos.condicion_sobre',
                                    );

            $pedidos_provincia->where(DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id and dp.estado = 1) "),'like','%'.$request->general.'%')
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

            $min = Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');//2022-11-25
            //return $min;

            $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->whereNull('direccion_grupos.condicion_sobre')
                                    //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'LIMA') as destino "),
                                        'de.celular',
                                        'de.nombre',
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id and dp.estado = 1) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.direccion',
                                        'de.referencia',
                                        'de.observacion',
                                        'de.distrito',
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion',
                                        'direccion_grupos.condicion_sobre',
                                        DB::raw('DATE(direccion_grupos.created_at) fecha2')
                                    );

            $pedidos_provincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where('direccion_grupos.estado','1')
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->whereNull('direccion_grupos.condicion_sobre')
                                    //->whereNotIn('direccion_grupos.condicion_sobre',['SIN ENVIO'])
                                    //->where('direccion_grupos.condicion_sobre', '<>', 'SIN ENVIO')
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador as identificador',
                                        DB::raw(" (select 'PROVINCIA') as destino "),
                                        DB::raw(" (select '') as celular "),
                                        DB::raw(" (select '') as nombre "),
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id and dp.estado = 1) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.tracking as direccion',
                                        'de.foto as referencia',
                                        DB::raw(" (select '') as observacion "),
                                        DB::raw(" (select '') as distrito "),
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion',
                                        'direccion_grupos.condicion_sobre',
                                        DB::raw('DATE(direccion_grupos.created_at) fecha2')
                                    );
                                  //  $pedidos_provincia->whereNot

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
                ->where('pedidos.envio', '1');
                //->where('pedidos.condicion_envio', '<>', 3);

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



        if ($request->condicion == "3")
        {
            $envio->update([
                'condicion_envio' => 3
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
            ->where('direccion_grupos.condicion_envio',2)
            ->whereIn('direccion_grupos.subcondicion_envio',['REGISTRADO','EN CAMINO','EN TIENDA/AGENTE','NO ENTREGADO'])
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



}
