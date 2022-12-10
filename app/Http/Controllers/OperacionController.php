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

class OperacionController extends Controller
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

    public function PorAtender()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => 'POR ATENDER',
            "EN PROCESO ATENCION" => 'EN PROCESO ATENCION',
            "ATENDIDO" => 'ATENDIDO'
        ];



        $imagenespedido = ImagenPedido::get();
        $imagenes = ImagenAtencion::get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('operaciones.porAtender', compact('dateMin', 'dateMax',  'condiciones', 'imagenespedido', 'imagenes', 'superasesor'));
    }


    public function PorAtendertabla(Request $request)
    {
        $mirol=Auth::user()->rol;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                DB::raw(" (CASE WHEN pedidos.id<10 THEN concat('PED000',pedidos.id)
                                WHEN pedidos.id<100 THEN concat('PED00',pedidos.id)
                                WHEN pedidos.id<1000 THEN concat('PED0',pedidos.id)
                                ELSE concat('PED',pedidos.id) END) AS id2"),
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion',
                DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                //DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%Y-%m-%d")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'dp.tipo_banca',
                DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('pedidos.condicion', [Pedido::POR_ATENDER,Pedido::EN_PROCESO_ATENCION]);

        if(Auth::user()->rol == "Operario"){

            $asesores = User::whereIN('users.rol', ['Asesor','Administrador'])
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )/*->union(
                    User::where("id","33")
                        ->select(
                            DB::raw("users.identificador as identificador")
                        ) )*/
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

            $asesores = User::whereIN('users.rol', ['Asesor','Administrador'])
                -> where('users.estado', '1')
                ->WhereIn('users.operario',$operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )/*->union(
                    User::where("id","33")
                        ->select(
                            DB::raw("users.identificador as identificador")
                        ) )*/
                ->pluck('users.identificador');

                $pedidos=$pedidos->WhereIn('u.identificador',$asesores);
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
                    ->addColumn('action2', function($pedido){
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action','action2'])
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

        return view('operaciones.atendidos', compact('dateMin', 'dateMax', 'condiciones', 'superasesor'));//, 'imagenes'
    }

    public function Atendidostabla(Request $request)
    {
        $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    DB::raw(" (CASE WHEN pedidos.id<10 THEN concat('PED000',pedidos.id)
                                    WHEN pedidos.id<100 THEN concat('PED00',pedidos.id)
                                    WHEN pedidos.id<1000 THEN concat('PED0',pedidos.id)
                                    ELSE concat('PED',pedidos.id)  end ) as id2 "),
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    DB::raw('(DATE_FORMAT(dp.fecha_envio_doc, "%Y-%m-%d %h:%i:%s")) as fecha_envio_doc'),
                    'dp.cant_compro',
                    'dp.atendido_por',
                    //'u.jefe',
                    DB::raw(" (select u2.name from users u2 where u2.id=u.jefe) as jefe "),
                    DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                //->WhereIn('u.identificador',$asesores)
                //->where('u.operario', Auth::user()->id)
                //->where('pedidos.condicion', 'ATENDIDO')
                ->where('pedidos.condicion', Pedido::ATENDIDO)
                ->whereIn('pedidos.envio', ['0'])
                ->groupBy(
                    'pedidos.id',
                    'u.identificador',
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
                ->orderBy('pedidos.created_at', 'DESC');
                /* ->take('200') */
                //->get();
        if(Auth::user()->rol == "Operario"){

            $asesores = User::whereIN('users.rol', ['Asesor','Administrador'])
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )/*->union(
                    User::where("id","33")
                        ->select(
                            DB::raw("users.identificador as identificador")
                        ) )*/
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

            $asesores = User::whereIN('users.rol', ['Asesor','Administrador'])
                -> where('users.estado', '1')
                ->WhereIn('users.operario',$operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )/*->union(
                    User::where("id","33")
                        ->select(
                            DB::raw("users.identificador as identificador")
                        ) )*/
                ->pluck('users.identificador');

            $pedidos=$pedidos->WhereIn('u.identificador',$asesores);


        }else{
            $pedidos=$pedidos;

                /*->simplePaginate(1000);*/
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

        return view('operaciones.entregados', compact('dateMin', 'dateMax', 'condiciones', 'superasesor'));//, 'imagenes'
    }

    public function Entregadostabla(Request $request)
    {
        $min = Carbon::createFromFormat('d/m/Y', $request->min)->format('Y-m-d');
        $max = Carbon::createFromFormat('d/m/Y', $request->max)->format('Y-m-d');
        $pedidos=null;

        $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.atendido_por',
                    'dp.atendido_por_id',
                    DB::raw(" (select u2.name from users u2 where u2.id=u.jefe limit 1) as jefe "),
                    DB::raw(' (select DATE_FORMAT(dp1.fecha_envio_doc_fis, "%d/%m/%Y")  from detalle_pedidos dp1 where dp1.id=dp.id limit 1) as fecha_envio_doc_fis'),
                    'dp.fecha_recepcion',
                    DB::raw("  (select IFNULL(count(b1.pedido_id),0) from direccion_pedidos b1 where b1.pedido_id=pedidos.id limit 1) as envios_lima "),
                    DB::raw("  (select IFNULL(count(b2.pedido_id),0) from gasto_pedidos b2 where b2.pedido_id=pedidos.id limit 1) as envios_provincia "),
                    DB::raw("  (CASE  when ((select IFNULL(count(b1.pedido_id),0) from direccion_pedidos b1 where b1.pedido_id=pedidos.id limit 1)+(select IFNULL(count(b2.pedido_id),0) from gasto_pedidos b2 where b2.pedido_id=pedidos.id limit 1))>0 then '1' else '0' end  )  as revierte "),
                    DB::raw("  (CASE  when pedidos.destino='LIMA' then (select gg.created_at from direccion_pedidos gg where gg.pedido_id=pedidos.id limit 1) ".
                                    "when pedidos.destino='PROVINCIA' then (select g.created_at from gasto_pedidos g where g.pedido_id=pedidos.id limit 1) ".
                                    "else '' end) as fecha_envio_sobre "),

                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', Pedido::ATENDIDO)
                ->whereIn('pedidos.envio', ['1','2','3'])
                //->whereIn('pedidos.envio', ['0'])
                ->whereBetween( 'pedidos.created_at', [$min, $max]);

        if(Auth::user()->rol == "Operario"){

            $asesores = User::whereIN('users.rol', ['Asesor','Administrador'])
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )/*->union(
                    User::where("id","33")
                        ->select(
                            DB::raw("users.identificador as identificador")
                        ) )*/
                ->pluck('users.identificador');

            $pedidos->WhereIn('u.identificador',$asesores);


        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $operarios = User::where('users.rol', 'Operario')
                -> where('users.estado', '1')
                -> where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::whereIN('users.rol', ['Asesor','Administrador'])
                -> where('users.estado', '1')
                ->WhereIn('users.operario',$operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )/*->union(
                    User::where("id","33")
                        ->select(
                            DB::raw("users.identificador as identificador")
                        ) )*/
                ->pluck('users.identificador');

            $pedidos->WhereIn('u.identificador',$asesores);


        }else{
            $pedidos=$pedidos;
        }
        //$pedidos=$pedidos->get();

        return Datatables::of(DB::table($pedidos))//Datatables::of($pedidos)
            ->addIndexColumn()
            ->addColumn('action', function($pedido){
                $btn='';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function Atenderid(Request $request)
    {
        $hiddenAtender=$request->hiddenAtender;
        $detalle_pedidos = DetallePedido::where('pedido_id',$hiddenAtender)->first();
        $fecha = Carbon::now();
        //sds

        $pedido=Pedido::where("id",$hiddenAtender)->first();
        $pedido->update([
            'condicion' => $request->condicion,
            'modificador' => 'USER'.Auth::user()->id
        ]);



        if ($request->condicion == "3")
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
            'atendido_por' => Auth::user()->name,
            'atendido_por_id' => Auth::user()->id,
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

        return view('operaciones.editatender', compact('pedido', 'pedidos', 'imagenespedido', 'imagenes'));
    }


    public function editAtencion(Pedido $pedido)
    {

        //dd('editando pedido: ' . $pedido);
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

        return view('pedidos.modal.ContenidoModal.ListadoAdjuntos',compact('imagenes', 'pedido'));
        //return response()->json(compact('pedido', 'pedidos', 'imagenespedido', 'imagenes'));
    }

    public function eliminarAdjuntoOperaciones(Request $request)
    {
        $id = $request->eliminar_pedido_id;
        $imagen = $request->eliminar_pedido_id_imagen;
        $imagenatencion = ImagenAtencion::where("pedido_id",$id)
            ->where("adjunto",$imagen)->first();

        if($imagenatencion != NULL){
            $imagenatencion->update([
                'estado' => '0'
            ]);
        }
/*
        $pedido = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
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

        $imagenes = ImagenAtencion::where('imagen_atencions.pedido_id', $pedido->id)->where('estado', '1')->get();


        return view('pedidos.modal.ContenidoModal.ListadoAdjuntos',compact('imagenes', 'pedido')); */

        return response()->json(['html' => $imagenatencion]);
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
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        }
        else{
            $detalle_pedidos->update([
                'cant_compro' => $request->cant_compro,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
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

        return view('operaciones.showAtender', compact('pedido','pedidos', 'imagenes', 'imagenesatencion'));
    }


    public function SinEnviarid(Request $request)
    {
        //Pedido $pedido
        $pedido=Pedido::where("id",$request->hiddenSinenvio)->first();
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '3',//SIN ENVIO
            'condicion_envio' => 3,
            'modificador' => 'USER'.Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
            'fecha_recepcion' => $fecha,
            'atendido_por' => Auth::user()->name,
            'atendido_por_id' => Auth::user()->id,
        ]);

        /**/
        $cliente=Cliente::where("id",$pedido->cliente_id)->first();

        $direcciongrupo=DireccionGrupo::create([
                'estado'=>'1',
                'destino' => 'LIMA',
                'distribucion'=> '',
                'condicion_envio' => 3,
                'condicion_sobre' => 'SIN ENVIO',
            ]);

        $direccionLima = DireccionEnvio::create([
            'cliente_id' => $pedido->cliente_id,
            'distrito' => 'LIMA',
            'direccion' => '',
            'referencia' => '',
            'nombre' => $cliente->nombre,
            'celular' => $cliente->celular,
            'observacion' => '',
            'direcciongrupo' => $direcciongrupo->id,
            'cantidad' => 1,
            'destino'=>'LIMA',
            'estado' => '1',
            "salvado"=> "0"
        ]);


        $direccionPedido = DireccionPedido::create([
                'direccion_id' => $direccionLima->id,
                'pedido_id' => $pedido->id,
                'codigo_pedido' => $detalle_pedidos->codigo,
                'direcciongrupo' => $direcciongrupo->id,
                'empresa' => $detalle_pedidos->nombre_empresa,
                'estado' => '1'
            ]);

        return response()->json(['html' => $pedido->id]);
        //return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }

    public function Enviarid(Request $request)
    {
        $pedido=Pedido::where("id",$request->hiddenEnvio)->first();
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '1',
            'modificador' => 'USER'.Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
        ]);

        return response()->json(['html' => $pedido->id]);

        //return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }

    public function Revertirenvio(Request $request)
    {
        $pedido=Pedido::where("id",$request->hiddenRevertirpedido)->first();
        $detalle_pedidos = DetallePedido::where('pedido_id',$pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '0',
            'modificador' => 'USER'.Auth::user()->id
        ]);

        /*$detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
        ]);*/

        return response()->json(['html' => $pedido->id]);

        //return redirect()->route('operaciones.atendidos')->with('info','actualizado');
    }


}
