<?php

namespace App\Http\Controllers\Pedidos;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!\auth()->user()->can('pedidos.mispedidos')) {
            abort(401);
        }
        $pedidos_atendidos = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])
            ->atendidos()
            ->noPendingAnulation()
            ->where('da_confirmar_descarga', '0')
            ->count();
        $pedidos_atendidos_total = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])->atendidos()->noPendingAnulation()->count();
        $pedidos_por_atender = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])->porAtender()->noPendingAnulation()->count();
        if ($request->has('ajax-datatable')) {

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
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
                    'pedidos.pendiente_anulacion',
                    'pedidos.condicion',
                    'pedidos.condicion_code',
                   // DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                   
                   DB::raw(" (CASE WHEN pedidos.condicion_code=1 THEN pedidos.created_at 
                                WHEN pedidos.condicion_code=2 THEN pedidos.updated_at
                                WHEN pedidos.condicion_code=3 THEN pedidos.updated_at
                                ELSE pedidos.created_at END) AS fecha"),
                   
                   
                   'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion',
                    'dp.tipo_banca',
                    'pedidos.motivo',
                    'c.icelular as icelulares',
                    DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
                ])
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1');


            if (Auth::user()->rol == "Operario") {

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->Where('users.operario', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');
                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);


            } else if (Auth::user()->rol == "Jefe de operaciones") {

                $operarios = User::where('users.rol', 'Operario')
                    ->where('users.estado', '1')
                    ->where('users.jefe', Auth::user()->id)
                    ->select(
                        DB::raw("users.id as id")
                    )
                    ->pluck('users.id');

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->WhereIn('users.operario', $operarios)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
            }elseif (Auth::user()->rol == "Encargado") {
                $usersasesores = User::whereIn('users.rol', ['Asesor',User::ROL_ADMIN])
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            }
            else if (Auth::user()->rol == "Asesor") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.identificador', Auth::user()->identificador)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            }
       

            if ($request->get('load_data') == 'por_atender') {
                $pedidos->whereIn('pedidos.condicion_code', [Pedido::POR_ATENDER_INT, Pedido::EN_PROCESO_ATENCION_INT]);
            } else {
                $pedidos->where('pedidos.da_confirmar_descarga', '0');
                $pedidos->whereIn('pedidos.condicion_code', [Pedido::ATENDIDO_INT]);
            }



            return datatables()->query(DB::table($pedidos))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) use ($request) {
                    $btn = '';
                    if ($request->get('load_data') == 'por_atender') {
                        if (\auth()->user()->can('operacion.atender')) {
                            $btn .= '<a href="" data-target="#modal-atender" data-atender=' . $pedido->id . ' data-toggle="modal" ><button class="btn btn-success btn-sm">Atender</button></a>';
                        }
                        if (\auth()->user()->can('operacion.PDF')) {
                            $btn .= '<a href="' . route("pedidosPDF", $pedido->id) . '" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                        }
                    } else {
                        $btn .= '<button data-toggle="jqConfirm" data-target="' . route("pedidos.estados.detalle-atencion", $pedido->id) . '"
                                    data-idc="' . generate_correlativo('PED', $pedido->id, 4) . '"
                                    data-codigo="' . $pedido->codigos . '"
                                    class="btn btn-outline-dark btn-sm mx-2">
                                    <i class="fa fa-eye"></i> Detalle Atención
                                </button>';

                    }
                    return $btn;
                })
                ->rawColumns(['action', 'action2'])
                ->toJson();
        }

        PedidoMovimientoEstado::where('condicion_envio_code',Pedido::ATENDIDO_INT)->update([
            'notificado' => 1,
        ]);

        return view('pedidos.status.index', compact('pedidos_atendidos', 'pedidos_por_atender', 'pedidos_atendidos_total'));
    }

    public function PorAtender(Request $request)
    {
        if (!\auth()->user()->can('pedidos.estados.poratender')) {
            abort(401);
        }
        $pedidos_atendidos = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])
            ->atendidos()
            ->noPendingAnulation()
            ->where('da_confirmar_descarga', '0')
            ->count();
        $pedidos_atendidos_total = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])->atendidos()->noPendingAnulation()->count();
        $pedidos_por_atender = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])->porAtender()->noPendingAnulation()->count();
        if ($request->has('ajax-datatable')) {

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
                    'pedidos.id',
                    'pedidos.correlativo as id2',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.pendiente_anulacion',
                    'pedidos.condicion',
                    'pedidos.condicion_code',
                   DB::raw(" (CASE WHEN pedidos.condicion_code=1 THEN pedidos.created_at 
                                WHEN pedidos.condicion_code=2 THEN pedidos.updated_at
                                WHEN pedidos.condicion_code=3 THEN pedidos.updated_at
                                ELSE pedidos.created_at END) AS fecha"),
                   'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion',
                    'dp.tipo_banca',
                    'pedidos.motivo',
                    'c.icelular as icelulares',
                    DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
                ])
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1');


            if (Auth::user()->rol == "Operario") {

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->Where('users.operario', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');
                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);


            } else if (Auth::user()->rol == "Jefe de operaciones") {

                $operarios = User::where('users.rol', 'Operario')
                    ->where('users.estado', '1')
                    ->where('users.jefe', Auth::user()->id)
                    ->select(
                        DB::raw("users.id as id")
                    )
                    ->pluck('users.id');

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->WhereIn('users.operario', $operarios)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
            }elseif (Auth::user()->rol == "Encargado") {
                $usersasesores = User::whereIn('users.rol', ['Asesor',User::ROL_ADMIN])
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            }
            else if (Auth::user()->rol == "Asesor") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.identificador', Auth::user()->identificador)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            }
       

            if ($request->get('load_data') == 'por_atender') {
                $pedidos->whereIn('pedidos.condicion_code', [Pedido::POR_ATENDER_INT, Pedido::EN_PROCESO_ATENCION_INT]);
            } /*else {
                $pedidos->where('pedidos.da_confirmar_descarga', '0');
                $pedidos->whereIn('pedidos.condicion_code', [Pedido::ATENDIDO_INT]);
            }*/

            return datatables()->query(DB::table($pedidos))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) use ($request) {
                    $btn = '';
                    if ($request->get('load_data') == 'por_atender') {
                        if (\auth()->user()->can('operacion.atender')) {
                            $btn .= '<a href="" data-target="#modal-atender" data-atender=' . $pedido->id . ' data-toggle="modal" ><button class="btn btn-success btn-sm">Atender</button></a>';
                        }
                        if (\auth()->user()->can('operacion.PDF')) {
                            $btn .= '<a href="' . route("pedidosPDF", $pedido->id) . '" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                        }
                    } /*else {
                        $btn .= '<button data-toggle="jqConfirm" data-target="' . route("pedidos.estados.detalle-atencion", $pedido->id) . '"
                                    data-idc="' . $pedido->id2. '"
                                    data-codigo="' . $pedido->codigos . '"
                                    class="btn btn-outline-dark btn-sm mx-2">
                                    <i class="fa fa-eye"></i> Detalle Atención
                                </button>';
                    }*/
                    return $btn;
                })
                ->rawColumns(['action', 'action2'])
                ->toJson();
        }

        /*PedidoMovimientoEstado::whereIn('condicion_code', [Pedido::POR_ATENDER_INT, Pedido::EN_PROCESO_ATENCION_INT])->update([
            'notificado' => 1,
        ]);*/

        return view('pedidos.status.poratender', compact('pedidos_atendidos', 'pedidos_atendidos_total','pedidos_por_atender'));//'pedidos_atendidos', 
    }

    public function Atendidos(Request $request)
    {
        if (!\auth()->user()->can('pedidos.estados.atendidos')) {
            abort(401);
        }
        $pedidos_atendidos = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])
            ->atendidos()
            ->noPendingAnulation()
            ->where('da_confirmar_descarga', '0')
            ->count();
        $pedidos_atendidos_total = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])->atendidos()->noPendingAnulation()->count();
        $pedidos_por_atender = Pedido::query()->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR])->porAtender()->noPendingAnulation()->count();
        if ($request->has('ajax-datatable')) {

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
                    'pedidos.id',
                    'pedidos.correlativo as id2',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.pendiente_anulacion',
                    'pedidos.condicion',
                    'pedidos.condicion_code',
                   DB::raw(" (CASE WHEN pedidos.condicion_code=1 THEN pedidos.created_at 
                                WHEN pedidos.condicion_code=2 THEN pedidos.updated_at
                                WHEN pedidos.condicion_code=3 THEN pedidos.updated_at
                                ELSE pedidos.created_at END) AS fecha"),
                   'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion',
                    'dp.tipo_banca',
                    'pedidos.motivo',
                    'c.icelular as icelulares',
                    DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
                ])
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1');


            if (Auth::user()->rol == "Operario") {

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->Where('users.operario', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');
                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);


            } else if (Auth::user()->rol == "Jefe de operaciones") {

                $operarios = User::where('users.rol', 'Operario')
                    ->where('users.estado', '1')
                    ->where('users.jefe', Auth::user()->id)
                    ->select(
                        DB::raw("users.id as id")
                    )
                    ->pluck('users.id');

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->WhereIn('users.operario', $operarios)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
            }elseif (Auth::user()->rol == "Encargado") {
                $usersasesores = User::whereIn('users.rol', ['Asesor',User::ROL_ADMIN])
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            }
            else if (Auth::user()->rol == "Asesor") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.identificador', Auth::user()->identificador)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            }
       

            /*if ($request->get('load_data') == 'por_atender') {
                $pedidos->whereIn('pedidos.condicion_code', [Pedido::POR_ATENDER_INT, Pedido::EN_PROCESO_ATENCION_INT]);
            } *//*else*/ {
                $pedidos->where('pedidos.da_confirmar_descarga', '0');
                $pedidos->whereIn('pedidos.condicion_code', [Pedido::ATENDIDO_INT]);
            }

            return datatables()->query(DB::table($pedidos))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) use ($request) {
                    $btn = '';
                    /*if ($request->get('load_data') == 'por_atender') {
                        if (\auth()->user()->can('operacion.atender')) {
                            $btn .= '<a href="" data-target="#modal-atender" data-atender=' . $pedido->id . ' data-toggle="modal" ><button class="btn btn-success btn-sm">Atender</button></a>';
                        }
                        if (\auth()->user()->can('operacion.PDF')) {
                            $btn .= '<a href="' . route("pedidosPDF", $pedido->id) . '" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>';
                        }
                    } else*/ {
                        $btn .= '<button data-toggle="jqConfirm" data-target="' . route("pedidos.estados.detalle-atencion", $pedido->id) . '"
                                    data-idc="' . $pedido->id2. '"
                                    data-codigo="' . $pedido->codigos . '"
                                    class="btn btn-outline-dark btn-sm mx-2">
                                    <i class="fa fa-eye"></i> Detalle Atención
                                </button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'action2'])
                ->toJson();
        }

        /*PedidoMovimientoEstado::where('condicion_envio_code',Pedido::ATENDIDO_INT)->update([
            'notificado' => 1,
        ]);*/

        return view('pedidos.status.atendidos', compact('pedidos_atendidos', 'pedidos_atendidos_total','pedidos_por_atender'));//'pedidos_atendidos', 
    }

    public function pedidoDetalleAtencion(Pedido $pedido)
    {
        if (!\auth()->user()->can('pedidos.mispedidos')) {
            abort(401);
        }
        return response()->json([
            "data" => $pedido->imagenAtencion()->activo()->get()
        ]);
    }

    public function pedidoDetalleAtencionConfirm(Request $request, Pedido $pedido)
    {
        if (!\auth()->user()->can('pedidos.mispedidos')) {
            abort(401);
        }
        if ($request->get('action') == 'confirm_download') {
            $pedido->update([
                'da_confirmar_descarga' => 1
            ]);
        }
        return response()->json([
            "success" => false
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anulados(Request $request)
    {
        if (!\auth()->user()->can('pedidos.pendiente.anulacion')) {//if (!in_array(\auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_OPERARIO])) {
            abort(401, 'No autorizado');
        }
        if ($request->has('ajax-datatable')) {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
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
                    'pedidos.pendiente_anulacion',
                    'pedidos.condicion',
                    'pedidos.condicion_code',
                    DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                    //DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%Y-%m-%d")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                    //DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion',
                    'dp.tipo_banca',
                    'pedidos.motivo',
                    DB::raw(" ( select count(ip.id) from imagen_pedidos ip inner join pedidos pedido on pedido.id=ip.pedido_id and pedido.id=pedidos.id where ip.estado=1 and ip.adjunto not in ('logo_facturas.png') ) as imagenes ")
                ])
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.pendiente_anulacion', '1');


            if (Auth::user()->rol == "Operario") {

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->Where('users.operario', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');
                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);

            } else if (Auth::user()->rol == "Jefe de operaciones") {

                $operarios = User::where('users.rol', 'Operario')
                    ->where('users.estado', '1')
                    ->where('users.jefe', Auth::user()->id)
                    ->select(
                        DB::raw("users.id as id")
                    )
                    ->pluck('users.id');

                $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador'])
                    ->where('users.estado', '1')
                    ->WhereIn('users.operario', $operarios)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $pedidos = $pedidos->WhereIn('u.identificador', $asesores);
            }

            return datatables()->query(DB::table($pedidos))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) {
                    $btn = '';
                    if ($pedido->pendiente_anulacion == 1) {
                        $btn .= '<button data-toggle="modal" data-target="#modal_confirmar_anular" data-confirm_anular_pedido="' . $pedido->id . '"  data-pedido_id="' . $pedido->id . '" data-pedido_motivo="' . $pedido->motivo . '" data-pedido_id_code="' . Pedido::generateIdCode($pedido->id) . '" type="button" class="btn btn-danger btn-sm" >EMITIR N/C</button>';                        
                    }
                    $btn .= '<a href="' . route('pedidosPDF', data_get($pedido, 'id')) . '" class="btn-sm dropdown-item" target="_blank"><i class="fa fa-file-pdf text-primary"></i> Ver PDF</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'action2'])
                ->toJson();
        }

        return view('pedidos.status.anulados');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
