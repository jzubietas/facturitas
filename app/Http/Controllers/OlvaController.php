<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\DireccionEnvio;
use App\Models\DireccionGrupo;
use App\Models\Distrito;
use App\Models\Media;
use App\Models\OlvaMovimiento;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OlvaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            ->select([
                'direccion_envios.id',
                'direccion_envios.distrito',
                'direccion_envios.direccion',
                'direccion_envios.referencia',
                'direccion_envios.nombre',
                'direccion_envios.celular',
                'dp.pedido_id as pedido_id',
            ])
            ->where('direccion_envios.estado', '1')
            ->where('dp.estado', '1')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        if (Auth::user()->rol == "Asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Super asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Encargado") {
            $ver_botones_accion = 1;
        } else {
            $ver_botones_accion = 1;
        }
        return view('envios.olva.index', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor', 'ver_botones_accion', 'departamento', 'distribuir'));
    }

    public function table()
    {
        $pedidos_provincia = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            ->whereIn('direccion_grupos.courier_estado', ['CONFIRMACION EN TIENDA','EN ALMACEN','DESPACHADO','REGISTRADO',])
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
                "direccion_grupos.id as idDireccionGrupo",
                DB::raw("(CASE
                        WHEN courier_estado='OTROS' THEN '01'
                        WHEN courier_estado='OTROS2' THEN '02'
                        WHEN courier_estado='CONFIRMACION EN TIENDA' THEN '03'
                        WHEN courier_estado='EN ALMACEN' THEN '04'
                        WHEN courier_estado='DESPACHADO' THEN '05'
                        WHEN courier_estado='REGISTRADO' THEN '06'
                        WHEN courier_estado='ASIGNADO' THEN '07'
                        WHEN courier_estado='MOTIVADO' THEN '08'
                        WHEN courier_estado='NO ENTREGADO' THEN '09'
                        WHEN courier_estado='SINIESTRADO' THEN '10'
                        WHEN courier_estado='' THEN '11'
                            ELSE 'OTROS'
                        END ) as courier_estado_nueva"),
            ]);


        add_query_filtros_por_roles_pedidos($pedidos_provincia, 'users.identificador');

        $query = DB::table($pedidos_provincia);
        return datatables()->query($query)
            ->addIndexColumn()
            ->editColumn('created_at_format', function ($pedido) {
                if ($pedido->created_at != null) {
                    return Carbon::parse($pedido->created_at)->format('d-m-Y h:i A');
                } else {
                    return '';
                }
            })
            ->editColumn('direccion_format', function ($pedido) {
                return collect(explode(',', $pedido->direccion))->trim()
                    ->map(function ($f) use ($pedido) {
                        if ($pedido->courier_failed_sync_at != null) {
                            return '<b class="d-flex">' . $f . '<i data-jqconfirm="edit_tracking" data-action="' . route('envios.seguimientoprovincia.update', [
                                    'direccion_grupo_id' => $pedido->id,
                                    'action' => 'update_tracking',
                                ]) . '" data-code="' . $f . '" role="button" class="fa fa-pencil-alt rounded p-1 bg-info"></i></b>';
                        }
                        return '<b>' . $f . '</b>';
                    })->join('<br>');
            })
            ->editColumn('referencia_format', function ($pedido) {
                $html = collect(explode(',', $pedido->referencia))->trim()->map(fn($f) => '<b>' . $f . '</b>')->join('<br>') . '<br>';


                $html .= collect(explode(',', $pedido->observacion))->trim()->map(fn($f) => '<a target="_blank" href="' . \Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rutulo</a>')->join('<br>');

                $html .= '<p>';
                return $html;
            })
            ->addColumn('condicion_envio_format', function ($pedido) {
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $html = '<a href="" data-target="#modal-ver_timeline-estado" data-iddirecciongrupo="' . $pedido->idDireccionGrupo . '"  data-toggle="modal" title="Ver Detalles"><span class="badge badge-success" style="background-color: '.$color.' !important;">' . $pedido->courier_estado . '</span></a>  ';
                return $html;
            })
            ->addColumn('action', function ($pedido) {
                $brnOlva=[];
                if (user_rol(User::ROL_ADMIN) || user_rol(User::ROL_ENCARGADO)) {
                    $pintar = 'info';
                    if ($pedido->add_screenshot_at == null) {
                        $pintar = 'danger';
                    } elseif (Carbon::parse($pedido->add_screenshot_at) < now()->startOfDay()) {
                        $pintar = 'danger';
                    } elseif (Carbon::parse($pedido->add_screenshot_at)->isToday()) {
                        $pintar = 'success';
                    }
                    $brnOlva[]= '<button data-target="' . route('envios.seguimientoprovincia.history_encargado', $pedido->id) . '" data-toggle="jqconfirmencargado" class="btn btn-' . $pintar . ' btn-sm "><i class="fa fa-history"></i> <b class="' . ($pedido->add_screenshot_at == null ? 'text-dark' : '') . '">Ver Historial</b></button>';
                }
                if ($pedido->courier_estado =="CONFIRMACION EN TIENDA" || $pedido->courier_estado=="EN ALMACEN"){
                    $brnOlva[]= '<button data-action="' . route('envios.olva.store', $pedido->id) . '" data-jqconfirm="notificado" class="btn btn-warning">Notificado</button>';
                }
                return join('', $brnOlva);
            })
            ->rawColumns(['action', 'referencia_format', 'condicion_envio_format', 'direccion_format'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DireccionGrupo $grupo)
    {
        $this->validate($request, [
            'file' => 'required|file'
        ]);
        if ($grupo->add_screenshot_at == null || $grupo->add_screenshot_at <= now()->startOfDay()) {
            $file = $request->file('file');
            $filename = now()->format('d-m-Y') . '.' . $file->getClientOriginalExtension();
            $exists = $grupo->getMedia('tienda_olva_notificado')->filter(fn(Media $media) => $media->file_name == $filename);
            foreach ($exists as $media) {
                $media->delete();
            }
            $grupo->addMedia($file)
                ->usingFileName($filename)
                ->toMediaCollection('tienda_olva_notificado');

            $grupo->update([
                'add_screenshot_at' => now()
            ]);
        } else {
            return response()->json([
                'data' => $grupo,
                'success' => false
            ]);
        }
        return response()->json([
            'data' => $grupo,
            'success' => true
        ]);
    }


    public function Seguimientoprovincia()
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
            ->select([
                'direccion_envios.id',
                'direccion_envios.distrito',
                'direccion_envios.direccion',
                'direccion_envios.referencia',
                'direccion_envios.nombre',
                'direccion_envios.celular',
                'dp.pedido_id as pedido_id',
            ])
            ->where('direccion_envios.estado', '1')
            ->where('dp.estado', '1')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        if (Auth::user()->rol == "Asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Super asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Encargado") {
            $ver_botones_accion = 1;
        } else {
            $ver_botones_accion = 1;
        }

        return view('envios.seguimientoProvincia', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor', 'ver_botones_accion', 'departamento', 'distribuir'));
    }

    public function SeguimientoprovinciaUpdate(Request $request)
    {
        $action = $request->action;
        $grupo = DireccionGrupo::findOrFail($request->direccion_grupo_id);
        if ($action == 'update_tracking') {
            $this->validate($request, [
                'tracking' => 'required',
                'numregistro' => 'required',
            ]);
            $pedido_exists = Pedido::query()->activo()
                ->whereNotIn('pedidos.id', $grupo->pedidos()->pluck('id'))
                ->where(function ($query) use ($request) {
                    $query->where('env_tracking', '=', trim($request->tracking))
                        ->orWhere('env_numregistro', '=', trim($request->numregistro));
                })
                ->pluck('codigo');
            if ($pedido_exists->count() > 0) {
                return response()->json([
                    'success' => false,
                    'existencias' => true,
                    'codigos' => $pedido_exists
                ]);
            }
            $grupo->update([
                'direccion' => trim($request->tracking),
                'referencia' => trim($request->numregistro),
                'courier_failed_sync_at' => null
            ]);
            $grupo->pedidos()->update([
                'env_tracking' => trim($request->tracking),
                'env_numregistro' => trim($request->numregistro)
            ]);
            return response()->json([
                'success' => true,
                'existencias' => false,
                'codigos' => []
            ]);
        } else {
            if (in_array($request->condicion_envio_code, [Pedido::ENTREGADO_PROVINCIA_INT, Pedido::NO_ENTREGADO_OLVA_INT])) {
                $collectionName = 'subcondicion_envio.' . Str::slug(Pedido::$estadosCondicionEnvioCode[$request->condicion_envio_code]);
                $medias = $grupo->getMedia($collectionName);
                foreach ($medias as $media) {
                    $grupo->deleteMedia($media);
                }
                $grupo->addMedia($request->file('file'))
                    ->toMediaCollection($collectionName, 'pstorage');

                if ($request->condicion_envio_code == Pedido::ENTREGADO_PROVINCIA_INT) {
                    DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::ENTREGADO_PROVINCIA_INT);
                } else {
                    DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::NO_ENTREGADO_OLVA_INT);
                    $grupo->update([
                        'motorizado_status' => Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO,
                        'motorizado_sustento_text' => 'No entregado olva'
                    ]);
                }

            } else {
                DireccionGrupo::cambiarCondicionEnvio($grupo, $request->condicion_envio_code);
            }
        }
        return $grupo;
    }

    public function Seguimientoprovinciatabla(Request $request)
    {
        $pedidos_provincia = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'clientes.user_id')
            ->activo()
            ->inOlva()
            ->where('direccion_grupos.distribucion', 'OLVA')
            ->where('direccion_grupos.motorizado_status', '0')
            ->where('direccion_grupos.direccion', 'not like', '%-%')
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
                DB::raw("(select env_tracking from pedidos pe where pe.direccion_grupo=direccion_grupos.id limit 1) as pe_env_tracking"),
            ]);

        return Datatables::of(
            DB::table($pedidos_provincia)
                ->orderByDesc('courier_failed_sync_at')
                ->orderByDesc('id')
        )
            ->addIndexColumn()
            ->editColumn('created_at_format', function ($pedido) {
                if ($pedido->created_at != null) {
                    return Carbon::parse($pedido->created_at)->format('d-m-Y h:i A');
                } else {
                    return '';
                }
            })
            ->editColumn('direccion_format', function ($pedido) {
                return collect(explode(',', $pedido->direccion))->trim()
                    ->map(function ($f) use ($pedido) {
                        $verBoton=0;
                        $trackings= collect(explode(',', $pedido->pe_env_tracking))->trim()->filter()->values();
                        foreach ($trackings as $item =>  $tracking) {
                            $tracking = explode('-', $tracking);
                        }
                        if (count($tracking) != 2) {
                            $verBoton=1;
                        }
                        if ($pedido->courier_failed_sync_at != null || $verBoton==1) {
                            return '<b class="d-flex">' . $f . '<i data-jqconfirm="edit_tracking" data-action="' . route('envios.seguimientoprovincia.update', [
                                    'direccion_grupo_id' => $pedido->id,
                                    'action' => 'update_tracking',
                                ]) . '" data-code="' . $f . '" role="button" class="fa fa-pencil-alt rounded p-1 bg-info"></i></b>';
                        }
                        return '<b>' . $f . '</b>';
                    })->join('<br>');
            })
            ->editColumn('referencia_format', function ($pedido) {
                $html = collect(explode(',', $pedido->referencia))->trim()->map(fn($f) => '<b>' . $f . '</b>')->join('<br>') . '<br>';


                $html .= collect(explode(',', $pedido->observacion))->trim()->map(fn($f) => '<a target="_blank" class="btn-fontsize text-blue" href="' . \Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rutulo</a>')->join('<br>');

                $html .= '<p>';
                return $html;
            })
            ->addColumn('condicion_envio_format', function ($pedido) {
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $html = '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->courier_estado . '</span>';
                return $html;
            })
            ->addColumn('action', function ($pedido) {
                $btnAdd = [];
                /*switch ($pedido->condicion_envio_code) {
                    case Pedido::RECEPCIONADO_OLVA_INT:
                        $btnType = [
                            'icon' => 'fas fa-car',
                            'btnClass' => 'btn-primary',
                        ];
                        break;
                    case Pedido::EN_CAMINO_OLVA_INT:
                        $btnType = [
                            'icon' => 'fas fa-home',
                            'btnClass' => 'btn-dark',
                        ];
                        break;
                    case Pedido::EN_TIENDA_AGENTE_OLVA_INT:
                        $btnType = [
                            'icon' => 'fas fa-envelope',
                            'btnClass' => 'btn-warning',
                        ];
                        break;
                    case Pedido::ENTREGADO_PROVINCIA_INT:
                    case Pedido::NO_ENTREGADO_OLVA_INT:
                        $btnType = [];
                        break;
                    default:
                        $btnType = [];
                }*/
                $btn = '';
                /*if (!empty($btnType)) {
                    $btn = '<button style="font-size:9px" data-target="" data-toggle="jqconfirm" class="btn ' . $btnType['btnClass'] . ' btn-sm"><i class="' . $btnType['icon'] . '"></i> <b>ACTUALIZAR ESTADO</b></button>';
                }
                if (!in_array(\auth()->user()->rol, [User::ROL_JEFE_COURIER, User::ROL_ADMIN])) {
                    $btn = '';
                }*/
                return '<div class="d-flex" style="flex-direction: column; gap:0.5rem">' . $btn . join('', $btnAdd) . '</div>';
            })
            ->rawColumns(['action', 'referencia_format', 'condicion_envio_format', 'direccion_format'])
            ->make(true);

    }

    public function SeguimientoprovinciaHistoryEncargado(DireccionGrupo $grupo)
    {
        return response()->json([
            'grupo' => $grupo,
            'data' => collect($grupo->getMedia('tienda_olva_notificado')->sortByDesc('created_at_format')->toArray())->values(),
        ]);
    }

    public function volvanoentregado()
    {
        return view('envios.olva.olvanoentregado');
    }
    public function volvaextraviado()
    {
        return view('envios.olva.olvaextraviado');
    }

    public function tblolvanoentregado()
    {
        $pedidos_provincia = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            /*->whereNull('direccion_grupos.courier_failed_sync_at')
            ->where('direccion_grupos.distribucion', 'OLVA')
            ->where('direccion_grupos.motorizado_status', '0')*/
            ->whereIn('direccion_grupos.courier_estado', ['ASIGNADO','MOTIVADO','NO ENTREGADO',])
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
            ]);
        /*if (user_rol(User::ROL_ASESOR) || user_rol(User::ROL_ASESOR_ADMINISTRATIVO)) {
            $pedidos_provincia->where(function ($query) {
                $query->whereNull('direccion_grupos.add_screenshot_at');
                $query->orWhereDate('direccion_grupos.add_screenshot_at', '<', now());
            });
        }*/

        add_query_filtros_por_roles_pedidos($pedidos_provincia, 'users.identificador');

        $query = DB::table($pedidos_provincia);
        /*if (!user_rol(User::ROL_ASESOR) && !user_rol(User::ROL_ASESOR_ADMINISTRATIVO)) {
            $query->orderBy('add_screenshot_at');
        }*/
        $query->orderByDesc('id');

        return datatables()->query($query)
            ->addIndexColumn()
            ->editColumn('created_at_format', function ($pedido) {
                if ($pedido->created_at != null) {
                    return Carbon::parse($pedido->created_at)->format('d-m-Y h:i A');
                } else {
                    return '';
                }
            })
            ->editColumn('direccion_format', function ($pedido) {
                return collect(explode(',', $pedido->direccion))->trim()
                    ->map(function ($f) use ($pedido) {
                        if ($pedido->courier_failed_sync_at != null) {
                            return '<b class="d-flex">' . $f . '<i data-jqconfirm="edit_tracking" data-action="' . route('envios.seguimientoprovincia.update', [
                                    'direccion_grupo_id' => $pedido->id,
                                    'action' => 'update_tracking',
                                ]) . '" data-code="' . $f . '" role="button" class="fa fa-pencil-alt rounded p-1 bg-info"></i></b>';
                        }
                        return '<b>' . $f . '</b>';
                    })->join('<br>');
            })
            ->editColumn('referencia_format', function ($pedido) {
                $html = collect(explode(',', $pedido->referencia))->trim()->map(fn($f) => '<b>' . $f . '</b>')->join('<br>') . '<br>';


                $html .= collect(explode(',', $pedido->observacion))->trim()->map(fn($f) => '<a target="_blank" href="' . \Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rutulo</a>')->join('<br>');

                $html .= '<p>';
                return $html;
            })
            /*->addColumn('condicion_envio_format', function ($pedido) {
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $html = '<span class="badge badge-success" style="background-color: '.$color.' !important;">' . $pedido->courier_estado . '</span>';
                return $html;
            })*/
            ->addColumn('condicion_envio_format', function ($pedido) {
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $html = '<a href="" data-target="#modal-ver_timeline-estado" data-iddirecciongrupo="' . $pedido->idDireccionGrupo . '"  data-toggle="modal" title="Ver Detalles"><span class="badge badge-success" style="background-color: '.$color.' !important;">' . $pedido->courier_estado . '</span></a>  ';
                return $html;
            })
            ->addColumn('action', function ($pedido) {
                if (user_rol(User::ROL_ADMIN) || user_rol(User::ROL_ENCARGADO)) {
                    $pintar = 'info';
                    if ($pedido->add_screenshot_at == null) {
                        $pintar = 'danger';
                    } elseif (Carbon::parse($pedido->add_screenshot_at) < now()->startOfDay()) {
                        $pintar = 'danger';
                    } elseif (Carbon::parse($pedido->add_screenshot_at)->isToday()) {
                        $pintar = 'success';
                    }
                    return '<button data-target="' . route('envios.seguimientoprovincia.history_encargado', $pedido->id) . '" data-toggle="jqconfirmencargado" class="btn btn-' . $pintar . ' btn-sm "><i class="fa fa-history"></i> <b class="' . ($pedido->add_screenshot_at == null ? 'text-dark' : '') . '">Ver Historial</b></button>';
                }
                return '<button data-action="' . route('envios.olva.store', $pedido->id) . '" data-jqconfirm="notificado" class="btn btn-warning">Notificado</button>';
            })
            ->rawColumns(['action', 'referencia_format', 'condicion_envio_format', 'direccion_format'])
            ->make(true);
    }

    public function tblolvaextraviado()
    {
        $pedidos_provincia = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            /*->whereNull('direccion_grupos.courier_failed_sync_at')
            ->where('direccion_grupos.distribucion', 'OLVA')
            ->where('direccion_grupos.motorizado_status', '0')*/
            ->whereIn('direccion_grupos.courier_estado', ['SINIESTRADO',])
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
            ]);

        add_query_filtros_por_roles_pedidos($pedidos_provincia, 'users.identificador');

        $query = DB::table($pedidos_provincia);
        /*if (!user_rol(User::ROL_ASESOR) && !user_rol(User::ROL_ASESOR_ADMINISTRATIVO)) {
            $query->orderBy('add_screenshot_at');
        }*/
        $query->orderByDesc('id');

        return datatables()->query($query)
            ->addIndexColumn()
            ->editColumn('created_at_format', function ($pedido) {
                if ($pedido->created_at != null) {
                    return Carbon::parse($pedido->created_at)->format('d-m-Y h:i A');
                } else {
                    return '';
                }
            })
            ->editColumn('direccion_format', function ($pedido) {
                return collect(explode(',', $pedido->direccion))->trim()
                    ->map(function ($f) use ($pedido) {
                        if ($pedido->courier_failed_sync_at != null) {
                            return '<b class="d-flex">' . $f . '<i data-jqconfirm="edit_tracking" data-action="' . route('envios.seguimientoprovincia.update', [
                                    'direccion_grupo_id' => $pedido->id,
                                    'action' => 'update_tracking',
                                ]) . '" data-code="' . $f . '" role="button" class="fa fa-pencil-alt rounded p-1 bg-info"></i></b>';
                        }
                        return '<b>' . $f . '</b>';
                    })->join('<br>');
            })
            ->editColumn('referencia_format', function ($pedido) {
                $html = collect(explode(',', $pedido->referencia))->trim()->map(fn($f) => '<b>' . $f . '</b>')->join('<br>') . '<br>';


                $html .= collect(explode(',', $pedido->observacion))->trim()->map(fn($f) => '<a target="_blank" href="' . \Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rutulo</a>')->join('<br>');

                $html .= '<p>';
                return $html;
            })
            ->addColumn('condicion_envio_format', function ($pedido) {
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $html = '<span class="badge badge-success" style="background-color: '.$color.' !important;">' . $pedido->courier_estado . '</span>';
                return $html;
            })
            ->addColumn('action', function ($pedido) {
                if (user_rol(User::ROL_ADMIN) || user_rol(User::ROL_ENCARGADO)) {
                    $pintar = 'info';
                    if ($pedido->add_screenshot_at == null) {
                        $pintar = 'danger';
                    } elseif (Carbon::parse($pedido->add_screenshot_at) < now()->startOfDay()) {
                        $pintar = 'danger';
                    } elseif (Carbon::parse($pedido->add_screenshot_at)->isToday()) {
                        $pintar = 'success';
                    }
                    return '<button data-target="' . route('envios.seguimientoprovincia.history_encargado', $pedido->id) . '" data-toggle="jqconfirmencargado" class="btn btn-' . $pintar . ' btn-sm "><i class="fa fa-history"></i> <b class="' . ($pedido->add_screenshot_at == null ? 'text-dark' : '') . '">Ver Historial</b></button>';
                }
                return '<button data-action="' . route('envios.olva.store', $pedido->id) . '" data-jqconfirm="notificado" class="btn btn-warning">Notificado</button>';
            })
            ->rawColumns(['action', 'referencia_format', 'condicion_envio_format', 'direccion_format'])
            ->make(true);
    }
    public  function gettimelineestadosolva(Request  $request){
        $grupo_courier_data_s = DireccionGrupo::where('id',$request->id_direcciongrupo)->first()->direccion;

        $grupo_courier_data_track=explode('-',$grupo_courier_data_s);
        $num=$grupo_courier_data_track[0];
        $anio=$grupo_courier_data_track[1];

        $grupo_courier_data=OlvaMovimiento::where('numerotrack',$num)->where('aniotrack',$anio)
            ->orderBy('id_rpt_envio_ruta','desc')
            ->get();

        $json_data=[];
        foreach($grupo_courier_data as $item)
        {
            //dd($item->fecha_creacion);
            $ejecution = \Str::lower($item->estado_tracking ?? '');
            $ejecution_2 = \Str::lower($item->obs ?? '');
            if(!(\Str::contains($ejecution, "valija")) && !(\Str::contains($ejecution_2, "valija")) )
            {
                $json_data[]=$item;
            }
            //if( $item->estado_tracking)
            //dd($item->estado_tracking);
            $item->fecha_creacion=Carbon::parse($item->fecha_creacion)->format('d-m-Y');
        }
        //dd($json_data);
        //dd($grupo_courier_data);
        $grupo_courier_data=collect($json_data)->toArray();
        //dd($grupo_courier_data);


        return view('envios.olva.modal.onlytimeline',compact('grupo_courier_data'));
    }
}
