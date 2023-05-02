<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\DireccionGrupo;
use App\Models\Meta;
use App\Models\Pedido;
use App\Models\Ruc;
use App\Models\SituacionClientes;
use App\Models\User;
use App\View\Components\dashboard\graficos\borras\PedidosPorDia;
use App\View\Components\dashboard\graficos\PedidosMesCountProgressBar;
use Blade;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->rol == 'MOTORIZADO') {
            return redirect()->route('envios.motorizados.index'); //->with('info', 'registrado');
        }

        $mirol = Auth::user()->rol;
        $idEncargado = Auth::user()->id;

        $lst_users_vida = User::where('estado', '1');

        if ($mirol == User::ROL_JEFE_LLAMADAS) {
            $lst_users_vida = $lst_users_vida->where(function ($query) {
                $query->where('jefe', '=', Auth::user()->id)
                    ->orWhereNull('jefe');
            })->whereIn("rol", [User::ROL_LLAMADAS, User::ROL_COBRANZAS,User::ROL_ASISTENTE_PUBLICIDAD]);
            $lst_users_vida = $lst_users_vida->orderBy('name', 'ASC');
        } else if ($mirol == User::ROL_JEFE_OPERARIO) {
            $lst_users_vida = $lst_users_vida->where('jefe', Auth::user()->id)->where("rol", User::ROL_OPERARIO);
            $lst_users_vida = $lst_users_vida->orderBy('name', 'ASC');
        } else if ($mirol == User::ROL_ENCARGADO) {
            $lst_users_vida = $lst_users_vida->where('supervisor', Auth::user()->id)->where("rol", User::ROL_ASESOR);
            $lst_users_vida = $lst_users_vida->orderBy('exidentificador', 'ASC');
        } else {
            $lst_users_vida = $lst_users_vida->orderBy('name', 'ASC');
        }
        $lst_users_vida = $lst_users_vida->get();

        /*----- DIAS POR FECHA -----*/
        $primer_dia = Carbon::now()->clone()->startOfMonth()->startOfDay();
        $fecha_anterior = Carbon::now()->clone()->subMonth()->endOfDay(); // dia actual

        $primer_dia_anterior = Carbon::now()->clone()->subMonth()->startOfMonth()->startOfDay();
        $fecha_actual = Carbon::now()->clone()->endOfDay(); // dia actual

        $arr = [];
        $diff = 10;
        //dd($fecha_actual);
        $diff = ($primer_dia->diffInDays($fecha_actual)) + 1;
        //dd($diff);

        for ($i = 1; $i <= $diff; $i++) {
            $arr[$i] = (string)($i);
        }

        $contadores_arr = implode(',', $arr);

        $pedido_del_mes_anterior = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')
            ->where('u.rol', '=', User::ROL_ASESOR)
            ->where('pedidos.codigo', 'not like', "%-C%")->activo()
            ->where('pendiente_anulacion', '<>', '1')
            ->whereBetween(DB::raw('Date(pedidos.created_at)'), [$primer_dia_anterior, $fecha_anterior])
            ->groupBy(DB::raw('Date(pedidos.created_at)'))
            ->select([
                DB::raw('Date(pedidos.created_at) as fecha'),
                DB::raw('count(pedidos.created_at) as total')
            ])->get()->map(function ($pedidoanterior) {
                return ["fecha" => $pedidoanterior->fecha, "total" => $pedidoanterior->total];
            })->toArray();

        for ($i = 1; $i <= count(($arr)); $i++) {
            $dia_calculado = Carbon::parse(now())->clone()->subMonth()->setUnitNoOverflow('day', $i, 'month')->format('Y-m-d');
            $id = in_array($dia_calculado, array_column($pedido_del_mes_anterior, 'fecha'));
            if ($id === false) {
                $pedido_del_mes_anterior[] = ["fecha" => $dia_calculado, "total" => 0];
            }
        }

        array_multisort(array_column($pedido_del_mes_anterior, "fecha"), SORT_ASC, $pedido_del_mes_anterior);

        $contadores_mes_anterior = implode(",", array_column($pedido_del_mes_anterior, 'total'));

        $pedido_del_mes = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')
            ->where('u.rol', '=', User::ROL_ASESOR)
            ->where('pedidos.codigo', 'not like', "%-C%")->activo()
            ->where('pendiente_anulacion', '<>', '1')
            ->whereBetween(DB::raw('Date(pedidos.created_at)'), [$primer_dia, $fecha_actual])
            ->groupBy(DB::raw('Date(pedidos.created_at)'))
            ->select([
                DB::raw('Date(pedidos.created_at) as fecha'),
                DB::raw('count(pedidos.created_at) as total')
            ])
            ->get()
            ->map(function ($pedido) {
                return ["fecha" => $pedido->fecha, "total" => $pedido->total];
            })->toArray();

        $gasto_total_olva = Pedido::query()
            ->where('env_direccion', 'OLVA')
            ->where('estado', 1)
            ->whereBetween('updated_at', [$primer_dia, $fecha_actual])
            ->count();

        $gasto_por_dia_olva = Pedido::query()
            ->where('pedidos.env_direccion', 'OLVA')
            ->where('pedidos.estado', 1)
            ->whereBetween(DB::raw('Date(pedidos.updated_at)'), [$primer_dia, $fecha_actual])
            ->groupBy(DB::raw('Date(pedidos.updated_at)'))
            ->select([
                DB::raw('Date(pedidos.updated_at) as fecha'),
                DB::raw('sum(pedidos.env_importe) as total'),
            ])
            ->get()
            ->map(function ($pedido) {
                return ["fecha" => $pedido->fecha, "total" => $pedido->total];
            })->toArray();

        /*OLVA*/
        for ($i = 1; $i <= count(($arr)); $i++) {
            $dia_calculado_olva = Carbon::parse(now())->setUnitNoOverflow('day', $i, 'month')->format('Y-m-d');
            $idOlva = in_array($dia_calculado_olva, array_column($gasto_por_dia_olva, 'fecha'));
            if ($idOlva === false) {
                $gasto_por_dia_olva[] = ["fecha" => $dia_calculado_olva, "total" => 0];
            }
        }

        /*PEDIDOS POR DIA*/
        for ($i = 1; $i <= count(($arr)); $i++) {
            $dia_calculado = Carbon::parse(now())->setUnitNoOverflow('day', $i, 'month')->format('Y-m-d');
            $id = in_array($dia_calculado, array_column($pedido_del_mes, 'fecha'));
            if ($id === false) {
                $pedido_del_mes[] = ["fecha" => $dia_calculado, "total" => 0];
            }
        }

        array_multisort(array_column($pedido_del_mes, "fecha"), SORT_ASC, $pedido_del_mes);
        $contadores_mes_actual = implode(",", array_column($pedido_del_mes, 'total'));

        array_multisort(array_column($gasto_por_dia_olva, "fecha"), SORT_ASC, $gasto_por_dia_olva);
        $contadores_mes_actual_olva = implode(",", array_column($gasto_por_dia_olva, 'total'));

        $fechametames = Carbon::now();
        $asesor_pedido_dia = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')
            ->where('pedidos.codigo', 'not like', "%-C%")->activo()
            ->whereDate('pedidos.created_at', $fechametames)
            ->where('pendiente_anulacion', '<>', '1')->count();

        $fechametames = Carbon::now()->format('Y-m-d');

        $arregloasesores = [];
        $asesores_list=User::where('rol',User::ROL_ASESOR)->where('estado',1)->orderBy('supervisor','asc')->orderBy('name','asc')->limit(7)->get();
        foreach ($asesores_list as $item => $asslst){
            $arregloasesores[$item] =(string)($asslst->identificador);
        }

        $labelasesores=implode(',', $arregloasesores);
        /*dd($asesores_list,$arregloasesores);*/
        return view('dashboard.dashboard', compact(
            'fechametames', 'lst_users_vida', 'mirol', 'idEncargado'
            , 'contadores_arr', 'contadores_mes_anterior', 'contadores_mes_actual', 'asesor_pedido_dia', 'fechametames', 'gasto_total_olva', 'contadores_mes_actual_olva','labelasesores'
        ));

    }

    public function widgets(Request $request)
    {
        $widget1 = new PedidosMesCountProgressBar();
        $widget2 = new PedidosPorDia(\auth()->user()->rol, 'Cantidad de pedidos de los asesores por dia', 'Asesores', 'Cant. Pedidos', '370', true);
        $widget3 = new PedidosPorDia(\auth()->user()->rol, 'Cantidad de pedidos de los asesores por mes', 'Asesores', 'Cant. Pedidos');
        $widget2->renderData();
        $widget3->renderData();
        return response()->json([
            "widgets" =>
                [
                    [
                        "data" => [],
                        "html" => Blade::renderComponent($widget1)
                    ],
                    [
                        "data" => $widget2->getData(),
                        "html" => Blade::renderComponent($widget2)
                    ],
                    [
                        "chart" => true,
                        "data" => $widget3->getData(),
                        "html" => Blade::renderComponent($widget3)
                    ],
                ]
        ]);
    }

    public function searchCliente(Request $request)
    {
        $q = $request->get("q");//915722331
        $q =trim($q);
        if(is_numeric($q))
        {
            //celular
            $clientes = Cliente::query()
                ->with(['user', 'rucs', 'porcentajes'])
                ->where('celular', 'like', '%' . $q . '%')
                //->orwhere(DB::raw("concat(clientes.celular,'-',clientes.icelular)"), 'like', '%' . $q . '%')
                //->orWhere('nombre', 'like', '%' . join("%", explode(" ", trim($q))) . '%')
                //->orWhere('dni', 'like', '%' . $q . '%')
                ->where('estado',1)
                ->limit(10)
                ->get()
                ->map(function (Cliente $cliente) {
                    $cliente->deuda_total = DetallePedido::query()->whereIn('pedido_id', $cliente->pedidos()->where('estado', '1')->pluck("id"))->sum("saldo");
                    return $cliente;
                });
        }else{
            $nrocel = str_replace(' ', '', $q);
            $clientes = Cliente::query()
                ->with(['user', 'rucs', 'porcentajes'])
                //->where('celular', 'like', '%' . $nrocel . '%')
                //->orwhere(DB::raw("concat(clientes.celular,'-',clientes.icelular)"), 'like', '%' . $q . '%')
                ->Where('nombre', 'like', '%' . join("%", explode(" ", trim($q))) . '%')
                //->orWhere('dni', 'like', '%' . $q . '%')
                ->where('estado',1)
                ->limit(10)
                ->get()
                ->map(function (Cliente $cliente) {
                    $cliente->deuda_total = DetallePedido::query()->whereIn('pedido_id', $cliente->pedidos()->where('estado', '1')->pluck("id"))->sum("saldo");
                    return $cliente;
                });
        }

        /*$nrocel = str_replace(' ', '', $q);
        $clientes = Cliente::query()
            ->with(['user', 'rucs', 'porcentajes'])
            ->where('celular', 'like', '%' . $nrocel . '%')
            //->orwhere(DB::raw("concat(clientes.celular,'-',clientes.icelular)"), 'like', '%' . $q . '%')
            ->orWhere('nombre', 'like', '%' . join("%", explode(" ", trim($q))) . '%')
            ->orWhere('dni', 'like', '%' . $q . '%')
            ->where('estado',1)
            ->limit(10)
            ->get()
            ->map(function (Cliente $cliente) {
                $cliente->deuda_total = DetallePedido::query()->whereIn('pedido_id', $cliente->pedidos()->where('estado', '1')->pluck("id"))->sum("saldo");
                return $cliente;
            });*/

        return view('dashboard.searchs.search_cliente', compact('clientes'));
    }

    public function searchRuc(Request $request)
    {
        $q = $request->get("q");
        $rucs = Ruc::query()
            ->with(['cliente', 'user'])
            ->where('num_ruc',   $q )
            ->where('estado',1)
            ->limit(10)
            ->get()
            ->map(function (Ruc $ruc) {
                $ruc->cliente->deuda_total = DetallePedido::query()->activo()->whereIn('pedido_id', $ruc->cliente->pedidos()->activo()->pluck("id"))->sum("saldo");
                $ruc->cliente->deuda_total_ruc = DetallePedido::query()->activo()->where('ruc', $ruc->num_ruc)->sum("saldo");
                return $ruc;
            });
        return view('dashboard.searchs.search_rucs', compact('rucs'));
    }

    /**
     * @throws Exception
     */

    public function viewMetaTableGeneral(Request $request)
    {
        DB::setDefaultConnection('reports');

        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        }
        else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_FORMACION) {
            //$asesores = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->get();
            //$total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->count();

            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19','21'])
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19','21'])
                ->count();

        }
        else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }
        else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            $asesores = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereNotIn('clave_pedidos',['17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereNotIn('clave_pedidos',['17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }

        $supervisores_array = User::query()->activo()->rolSupervisor()->get();
        $count_asesor = [];
        foreach ($supervisores_array as $supervisor) {
            $count_asesor[$supervisor->id] =
                [
                    'pedidos_totales' => 0,
                    'total_pedido_mespasado' => 0,
                    'meta_quincena' => 0,
                    'meta_intermedia' => 0,
                    'meta' => 0,
                    'meta_2' => 0,
                    'total_pagado' => 0,
                    'progress_pagos' => 0,
                    'progress_pedidos' => 0,
                    'total_pedido' => 0,
                    'pedidos_dia' => 0,
                    'all_situacion_activo' => 0,
                    'all_situacion_recurrente' => 0,
                    'meta_new'=>0,
                    'name'=>$supervisor->name
                ];
        }

        $clientes_situacion_activo_mayor=0;
        foreach ($asesores as $asesori)
        {
            $clientes_situacion_activo_mayor_ = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('user_id', $asesori->id)
                ->where('clientes.situacion', '=', 'ACTIVO')
                ->activo()
                ->count();
            if($clientes_situacion_activo_mayor_>=$clientes_situacion_activo_mayor_)
            {
                $clientes_situacion_activo_mayor=$clientes_situacion_activo_mayor_;
            }
        }

        //dd($progressData);
        foreach ($asesores as $asesor)
        {
            /*echo "<pre>";
            print_r($asesor);
            echo "</pre>";*/
            if (in_array(auth()->user()->rol, [
                User::ROL_FORMACION
                , User::ROL_ADMIN
                , User::ROL_PRESENTACION
                , User::ROL_ASESOR
                , User::ROL_LLAMADAS
                , User::ROL_JEFE_LLAMADAS
            ])) {
            } else {
                if (auth()->user()->rol != User::ROL_ADMIN) {
                    if (auth()->user()->rol != User::ROL_ENCARGADO) {
                        if (auth()->user()->id != $asesor->id) {
                            continue;
                        }
                    } else {
                        if (auth()->user()->id != $asesor->supervisor) {
                            continue;
                        }
                    }
                }
            }

            /*CONSULTAS PARA MOSTRAR INFO EN TABLA*/
            $date_pagos = Carbon::parse(now())->subMonth()->startOfMonth();
            $fechametames = Carbon::now()->clone();

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $asesor_pedido_dia = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->whereDate('pedidos.created_at', $fechametames)
                ->where('pendiente_anulacion', '<>', '1')->count();

            $meta_calculo_row = Meta::where('rol', User::ROL_ASESOR)
                ->where('user_id', $asesor->id)
                ->where('anio', $fechametames->format('Y'))
                ->where('mes', $fechametames->format('m'))->first();


            if($meta_calculo_row==null)
            {
                \Log::info("Error en meta_dashboard para asesor id -> " . $asesor->id." en periodo ".$fechametames);
            }

            $metatotal_quincena = (float)$meta_calculo_row->meta_quincena;
            $metatotal_intermedia = (float)$meta_calculo_row->meta_intermedia;
            $metatotal_1 = (float)$meta_calculo_row->meta_pedido;
            $metatotal_2 = (float)$meta_calculo_row->meta_pedido_2;

            $asesorid = User::where('rol', User::ROL_ASESOR)->where('id', $asesor->id)->pluck('id');

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $total_pedido = Pedido::query()->where('user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$fechametames->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()])
                ->count();

            $total_pagado_a = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','C'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                //->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado_b = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos.pago','=','1'],
                    ['pedidos.pagado','=','2'],
                    ['pago_pedidos.estado','=',1],
                    ['pago_pedidos.pagado','=',2]
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado=$total_pagado_a+$total_pagado_b;

            $total_pedido_mespasado = Pedido::query()
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->count();

            $supervisor = User::where('rol', User::ROL_ASESOR)->where('clave_pedidos', $asesor->clave_pedidos)->activo()->first()->supervisor;
            $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->where('pendiente_anulacion', '<>', '1')
                ->whereDate('pedidos.created_at', $fechametames)->count();

            $clientes_situacion_activo = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','LEVANTADO')
                ->activo()
                ->count();

            $clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
                ->where('cv.periodo',Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m'))
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','CAIDO')
                ->activo()
                ->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta_quincena" => $metatotal_quincena,
                "meta_intermedia" => $metatotal_intermedia,
                "meta" => $metatotal_1,
                "meta_2" => $metatotal_2,
                "pedidos_totales" => $pedidos_totales,
                "clientes_situacion_activo" => $clientes_situacion_activo,
                "clientes_situacion_recurrente" => $clientes_situacion_recurrente,
                "supervisor" => $supervisor,
            ];

            if (array_key_exists($encargado_asesor, $count_asesor)) {
                /*if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else*/ if ($encargado_asesor == 24) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];

                }
            }

            if ($asesor->excluir_meta)
            {
                if ($total_pedido_mespasado > 0)
                {
                    $p_pagos = round(($total_pagado / $total_pedido_mespasado) * 100, 2);
                }
                else {
                    $p_pagos = 0;
                }

                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }
                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }

                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }
                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }

                if ($metatotal_1 > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }
                if ($metatotal_1 > 0)
                {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }

                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                }
                else {
                    $p_pedidos_2 = 0;
                }
                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }

                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                if ($metatotal_intermedia > 0) {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                    $item['meta_new'] = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }
            }
            else */
                if ($total_pedido>=0 && $total_pedido < $metatotal_1) {
                    if ($metatotal_1 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;
                if ($total_pedido>=0 && $total_pedido < $metatotal_1)
                {
                    if ($metatotal_1 > 0)
                    {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    }
                    else
                    {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0)
                    {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    }
                    else
                    {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;

            }
            else {
                $progressData[] = $item;
            }


        }

        //dd($progressData);
        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    /*echo "<pre>";
                    print_r($item);
                    echo "</pre>";*/
                    $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                    $newData[$identificador]['total_pedido_mespasado'] += data_get($item, 'total_pedido_mespasado');
                    $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                    $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
                    $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');
                    $newData[$identificador]['meta_new'] += data_get($item, 'meta_new');//0 quincena //0.5 intermedia //1 meta1//2 meta2
                    $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');//todo el mes
                    $newData[$identificador]['clientes_situacion_recurrente'] += data_get($item, 'clientes_situacion_recurrente');//todo el mes
                    $newData[$identificador]['clientes_situacion_activo'] += data_get($item, 'clientes_situacion_activo');//todo el mes
                    $newData[$identificador]['meta_quincena'] += data_get($item, 'meta_quincena');
                    $newData[$identificador]['meta_intermedia'] += data_get($item, 'meta_intermedia');
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total_pedido');
            $all_mespasado = data_get($item, 'total_pedido_mespasado');
            $pay = data_get($item, 'total_pagado');
            $allmeta__quincena = data_get($item, 'meta_quincena');//15
            $allmeta_intermedia = data_get($item, 'meta_intermedia');//in
            $allmeta = data_get($item, 'meta');//meta 1
            $allmeta_2 = data_get($item, 'meta_2');//meta 2
            $pedidos_dia = data_get($item, 'pedidos_dia');//pedidos diario
            $pedidos_totales = data_get($item, 'pedidos_totales');//pedidos de todo el mes
            $clientes_situacion_recurrente = data_get($item, 'clientes_situacion_recurrente');//pedidos de todo el mes
            $clientes_situacion_activo = data_get($item, 'clientes_situacion_activo');//pedidos de todo el mes
            $supervisor = data_get($item, 'supervisor');
            $meta_new = data_get($item, 'meta_new');

            if ($all_mespasado == 0) {
                $p_pagos = 0;
            } else {
                if ($pay > 0) {
                    $p_pagos = round(($pay / $all_mespasado) * 100, 2);
                } else {
                    $p_pagos = 0;
                }
            }

            /*meta quincena = 0*/
            /*if ($all>=0 && $all < $allmeta__quincena) {
                //meta quincena
                if ($allmeta__quincena > 0) {
                    $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                } else {
                    $p_quincena = 0;
                }
                $meta_new = 0;
                $item['progress_pedidos'] = $p_quincena;
            } else *//*if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                if ($allmeta_intermedia > 0) {
                    $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                }
                $meta_new = 0.5;
                $item['progress_pedidos'] = $p_intermedia;
            }else*/ if ($all>=0  && $all < $allmeta) {
                if ($allmeta > 0) {
                    $p_pedidos = round(($all / $allmeta) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $meta_new = 1;
                $item['progress_pedidos'] = $p_pedidos;
            } else if($all>=$allmeta){
                if ($allmeta_2 > 0) {
                    $p_pedidos_2 = round(($all / $allmeta_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }
                $meta_new = 2;
                $item['progress_pedidos'] = $p_pedidos_2;
            }

            $item['progress_pagos'] = $p_pagos;
            $item['total_pedido'] = $all;
            $item['total_pedido_pasado'] = $all_mespasado;
            $item['pedidos_dia'] = $pedidos_dia;
            $item['pedidos_totales'] = $pedidos_totales;
            $item['all_situacion_recurrente'] = $clientes_situacion_recurrente;
            $item['all_situacion_activo'] = $clientes_situacion_activo;
            $item['meta_new'] = $meta_new;

            if($meta_new==1)
            {
                $item['meta_combinar']=$item['meta'];
            }else if($meta_new==2)
            {
                $item['meta_combinar']=$item['meta_2'];
            }

            if($allmeta_2==0)
                $item['porcentaje_general']=0;
            else
            {
                $item['porcentaje_general']=($all/$allmeta_2);
            }

            return $item;
        })->sortBy('meta_new', SORT_NUMERIC, true)
            ->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

        if ($request->ii == 17) {
            $progressData->all();
        }
        else if ($request->ii == 18) {
            $progressData->all();
        }
        else if ($request->ii == 1) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 8) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 2) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 9) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 3) {
            $progressData->all();
        }
        else if ($request->ii == 0) {
            $progressData->all();
        }

        //aqui la division de  1  o 2
        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_situacion_recurrente = collect($progressData)->pluck('all_situacion_recurrente')->sum();
        $all_situacion_activo = collect($progressData)->pluck('all_situacion_activo')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta_quincena = collect($progressData)->pluck('meta_quincena')->sum();
        $meta_intermedia = collect($progressData)->pluck('meta_intermedia')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $meta_combinar = collect($progressData)->pluck('meta_combinar')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        $supervisor = collect($progressData)->pluck('supervisor')->sum();
        $meta_new=0;
        $progress_pedidos=0;

        foreach ($supervisores_array as $supervisor_2)
        {

            //dd($count_asesor);
            if ($count_asesor[$supervisor_2->id]['total_pedido'] >= 0 && $count_asesor[$supervisor_2->id]['total_pedido'] < $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['total_pedido']>0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round(($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=1;
            }else if($count_asesor[$supervisor_2->id]['total_pedido'] >= $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['meta_2'] > 0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round( ($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta_2'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=2;
            }
        }

        //verificar totales

        if ($all >= 0 && $all < $meta)
        {
            if($all>0)
            {
                $progress_pedidos=round(($all/$meta)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=1;
        }else if($all >= $meta)
        {
            if($meta_2 > 0)
            {
                $progress_pedidos=round( ($all/$meta_2)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=2;
        }

        if ($all_mespasado == 0) {
            $p_pagos = 0;
        } else {
            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }
        }

        $object_totales = [
            //"progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "all_situacion_recurrente" => $all_situacion_recurrente,
            "all_situacion_activo" => $all_situacion_activo,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "meta_combinar" => $meta_combinar,
            "pedidos_dia" => $pedidos_dia,
            "supervisor" => $supervisor,
            "meta_new"=>$meta_new,
            "progress_pedidos"=>$progress_pedidos
        ];

        $html = '';

        /*TOTAL*/

        if ($request->ii == 0)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #ade0db; color: #0a0302">';
            $html .= '<tbody>
              <tr class="responsive-table">
                  <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($object_totales['pedidos_dia'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    TOTAL DE PEDIDOS DEL DIA: ' . $object_totales['pedidos_dia'] . ' </span>';

            $html .= '
                  </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded h-40 h-60-res height-bar-progress" style="height: 25px !important;">';

            $round=$object_totales['progress_pagos'];

            if(0<$round && $round<=40)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(40<$round && $round<=50)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar h-60-res" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos'] - 40) . '%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="' . ($object_totales['progress_pagos'] - 40) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(50<$round && $round<=70)
            {
                $html .= '<div class="progress-bar bg-warning" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(70<$round && $round<=80)
            {
                $html .= '<div class="progress-bar bg-warning rounded  h-60-res height-bar-progress" role="progressbar"
                         style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: ' . ($object_totales['progress_pagos'] - 70) . '%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="' . ($object_totales['progress_pagos'] - 70) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }
            else if(80<$round && $round<=100)
            {
                $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                 style="height: 25px !important;width: ' . $object_totales['progress_pagos'] . '%;background: #03af03;"
                 aria-valuenow="' . $object_totales['progress_pagos'] . '"
                 aria-valuemin="0" aria-valuemax="100"></div>';
            }
            else
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }

            $html .= '</div>
    <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 25px !important;font-size: 12px;">
<span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  ' . $object_totales['progress_pagos'] . '%</b> - ' . $object_totales['total_pagado'] . '/' . $object_totales['total_pedido_mespasado'] . '</span></div>';

            $html .= ' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 25px !important;">';

            //40 50 70 80 100 <

            $round=$object_totales['progress_pedidos'];

            if ($object_totales['meta'] == 0)
            {

            }
            else if ($object_totales['meta_new'] == 1)
            {
                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round-40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round-40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round-70) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round-70) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
            }
            else if ($object_totales['meta_new'] == 2)
            {
                $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }



            if ($object_totales['meta'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta'] . '</span>
    </div>';
            } else {

                if ($object_totales['meta_new'] == 1)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }else if ($object_totales['meta_new'] == 2)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }

            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        }

        return $html;
    }

    public function viewMetaTable(Request $request)
    {
        //DB::setDefaultConnection('metatable1');
        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        }
        else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_FORMACION) {
            //$asesores = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->get();
            //$total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->count();

            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->count();

        }
        else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }
        else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            $asesores = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }

        $supervisores_array = User::query()->activo()->rolSupervisor()->get();
        $count_asesor = [];
        foreach ($supervisores_array as $supervisor) {
            $count_asesor[$supervisor->id] =
                [
                    'pedidos_totales' => 0,
                    'total_pedido_mespasado' => 0,
                    'meta_quincena' => 0,
                    'meta_intermedia' => 0,
                    'meta' => 0,
                    'meta_2' => 0,
                    'total_pagado' => 0,
                    'progress_pagos' => 0,
                    'progress_pedidos' => 0,
                    'total_pedido' => 0,
                    'pedidos_dia' => 0,
                    'all_situacion_activo' => 0,
                    'all_situacion_recurrente' => 0,
                    'meta_new'=>0,
                    'name'=>$supervisor->name
                ];
        }

        $clientes_situacion_activo_mayor=0;
        foreach ($asesores as $asesori)
        {
            $clientes_situacion_activo_mayor_ = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('user_id', $asesori->id)
                ->where('clientes.situacion', '=', 'ACTIVO')
                ->activo()
                ->count();
            if($clientes_situacion_activo_mayor_>=$clientes_situacion_activo_mayor_)
            {
                $clientes_situacion_activo_mayor=$clientes_situacion_activo_mayor_;
            }
        }

        //dd($progressData);
        foreach ($asesores as $asesor)
        {
            /*echo "<pre>";
            print_r($asesor);
            echo "</pre>";*/
            if (in_array(auth()->user()->rol, [
                User::ROL_FORMACION
                , User::ROL_ADMIN
                , User::ROL_PRESENTACION
                , User::ROL_ASESOR
                , User::ROL_LLAMADAS
                , User::ROL_JEFE_LLAMADAS
            ])) {
            } else {
                if (auth()->user()->rol != User::ROL_ADMIN) {
                    if (auth()->user()->rol != User::ROL_ENCARGADO) {
                        if (auth()->user()->id != $asesor->id) {
                            continue;
                        }
                    } else {
                        if (auth()->user()->id != $asesor->supervisor) {
                            continue;
                        }
                    }
                }
            }

            /*CONSULTAS PARA MOSTRAR INFO EN TABLA*/
            $date_pagos = Carbon::parse(now())->subMonth()->startOfMonth();
            $fechametames = Carbon::now()->clone();

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            //dd($fechametames,$date_pagos);


            $asesor_pedido_dia = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->whereDate('pedidos.created_at', $fechametames)
                ->where('pendiente_anulacion', '<>', '1')->count();

            $meta_calculo_row = Meta::where('rol', User::ROL_ASESOR)
                ->where('user_id', $asesor->id)
                ->where('anio', $fechametames->format('Y'))
                ->where('mes', $fechametames->format('m'))->first();


            if($meta_calculo_row==null)
            {
                \Log::info("Error en meta_dashboard para asesor id -> " . $asesor->id." en periodo ".$fechametames);
            }

            $metatotal_quincena = (float)$meta_calculo_row->meta_quincena;
            $metatotal_intermedia = (float)$meta_calculo_row->meta_intermedia;
            $metatotal_1 = (float)$meta_calculo_row->meta_pedido;
            $metatotal_2 = (float)$meta_calculo_row->meta_pedido_2;

            $asesorid = User::where('rol', User::ROL_ASESOR)->where('id', $asesor->id)->pluck('id');

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $total_pedido = Pedido::query()->where('user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$fechametames->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()])
                ->count();

            $total_pagado_a = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','C'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                //->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado_b = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos.pago','=','1'],
                    ['pedidos.pagado','=','2'],
                    ['pago_pedidos.estado','=',1],
                    ['pago_pedidos.pagado','=',2]
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado=$total_pagado_a+$total_pagado_b;

            $total_pedido_mespasado = Pedido::query()
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->count();

            $supervisor = User::where('rol', User::ROL_ASESOR)->where('clave_pedidos', $asesor->clave_pedidos)->activo()->first()->supervisor;
            $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->where('pendiente_anulacion', '<>', '1')
                ->whereDate('pedidos.created_at', $fechametames)->count();

            $clientes_situacion_activo = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','LEVANTADO')
                ->activo()
                ->count();

            $clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
                ->where('cv.periodo',Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m'))
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','CAIDO')
                ->activo()
                ->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta_quincena" => $metatotal_quincena,
                "meta_intermedia" => $metatotal_intermedia,
                "meta" => $metatotal_1,
                "meta_2" => $metatotal_2,
                "pedidos_totales" => $pedidos_totales,
                "clientes_situacion_activo" => $clientes_situacion_activo,
                "clientes_situacion_recurrente" => $clientes_situacion_recurrente,
                "supervisor" => $supervisor,
            ];

            if (array_key_exists($encargado_asesor, $count_asesor)) {
                /*if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else*/ if ($encargado_asesor == 24) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];

                }
            }

            if ($asesor->excluir_meta)
            {
                if ($total_pedido_mespasado > 0)
                {
                    $p_pagos = round(($total_pagado / $total_pedido_mespasado) * 100, 2);
                }
                else {
                    $p_pagos = 0;
                }

                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }
                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }

                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }
                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }

                if ($metatotal_1 > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }
                if ($metatotal_1 > 0)
                {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }

                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                }
                else {
                    $p_pedidos_2 = 0;
                }
                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }

                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                if ($metatotal_intermedia > 0) {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                    $item['meta_new'] = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }
            }
            else */
                if ($total_pedido>=0 && $total_pedido < $metatotal_1) {
                    if ($metatotal_1 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;


            }
            else {
                $progressData[] = $item;
            }


        }

        //dd($progressData);
        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    /*echo "<pre>";
                    print_r($item);
                    echo "</pre>";*/
                    $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                    $newData[$identificador]['total_pedido_mespasado'] += data_get($item, 'total_pedido_mespasado');
                    $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                    $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
                    $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');
                    $newData[$identificador]['meta_new'] += data_get($item, 'meta_new');//0 quincena //0.5 intermedia //1 meta1//2 meta2
                    $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');//todo el mes
                    $newData[$identificador]['clientes_situacion_recurrente'] += data_get($item, 'clientes_situacion_recurrente');//todo el mes
                    $newData[$identificador]['clientes_situacion_activo'] += data_get($item, 'clientes_situacion_activo');//todo el mes
                    $newData[$identificador]['meta_quincena'] += data_get($item, 'meta_quincena');
                    $newData[$identificador]['meta_intermedia'] += data_get($item, 'meta_intermedia');
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total_pedido');
            $all_mespasado = data_get($item, 'total_pedido_mespasado');
            $pay = data_get($item, 'total_pagado');
            $allmeta__quincena = data_get($item, 'meta_quincena');//15
            $allmeta_intermedia = data_get($item, 'meta_intermedia');//in
            $allmeta = data_get($item, 'meta');//meta 1
            $allmeta_2 = data_get($item, 'meta_2');//meta 2
            $pedidos_dia = data_get($item, 'pedidos_dia');//pedidos diario
            $pedidos_totales = data_get($item, 'pedidos_totales');//pedidos de todo el mes
            $clientes_situacion_recurrente = data_get($item, 'clientes_situacion_recurrente');//pedidos de todo el mes
            $clientes_situacion_activo = data_get($item, 'clientes_situacion_activo');//pedidos de todo el mes
            $supervisor = data_get($item, 'supervisor');
            $meta_new = data_get($item, 'meta_new');

            if ($all_mespasado == 0) {
                $p_pagos = 0;
            } else {
                if ($pay > 0) {
                    $p_pagos = round(($pay / $all_mespasado) * 100, 2);
                } else {
                    $p_pagos = 0;
                }
            }

            /*meta quincena = 0*/
            /*if ($all>=0 && $all < $allmeta__quincena) {
                //meta quincena
                if ($allmeta__quincena > 0) {
                    $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                } else {
                    $p_quincena = 0;
                }
                $meta_new = 0;
                $item['progress_pedidos'] = $p_quincena;
            } else *//*if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                if ($allmeta_intermedia > 0) {
                    $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                }
                $meta_new = 0.5;
                $item['progress_pedidos'] = $p_intermedia;
            }else*/ if ($all>=0  && $all < $allmeta) {
                if ($allmeta > 0) {
                    $p_pedidos = round(($all / $allmeta) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $meta_new = 1;
                $item['progress_pedidos'] = $p_pedidos;
            } else if($all>=$allmeta){
                if ($allmeta_2 > 0) {
                    $p_pedidos_2 = round(($all / $allmeta_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }
                $meta_new = 2;
                $item['progress_pedidos'] = $p_pedidos_2;
            }

            $item['progress_pagos'] = $p_pagos;
            $item['total_pedido'] = $all;
            $item['total_pedido_pasado'] = $all_mespasado;
            $item['pedidos_dia'] = $pedidos_dia;
            $item['pedidos_totales'] = $pedidos_totales;
            $item['all_situacion_recurrente'] = $clientes_situacion_recurrente;
            $item['all_situacion_activo'] = $clientes_situacion_activo;
            $item['meta_new'] = $meta_new;

            if($meta_new==1)
            {
                $item['meta_combinar']=$item['meta'];
            }else if($meta_new==2)
            {
                $item['meta_combinar']=$item['meta_2'];
            }

            if($allmeta_2==0)
                $item['porcentaje_general']=0;
            else
            {
                $item['porcentaje_general']=($all/$allmeta_2);
            }

            return $item;
        })->sortBy('meta_new', SORT_NUMERIC, true)
            ->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

        if ($request->ii == 17) {
            $progressData->all();
        }
        else if ($request->ii == 18) {
            $progressData->all();
        }
        else if ($request->ii == 1) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 8) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 2) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 9) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 3) {
            $progressData->all();
        }

        //aqui la division de  1  o 2
        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_situacion_recurrente = collect($progressData)->pluck('all_situacion_recurrente')->sum();
        $all_situacion_activo = collect($progressData)->pluck('all_situacion_activo')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta_quincena = collect($progressData)->pluck('meta_quincena')->sum();
        $meta_intermedia = collect($progressData)->pluck('meta_intermedia')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $meta_combinar = collect($progressData)->pluck('meta_combinar')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        $supervisor = collect($progressData)->pluck('supervisor')->sum();
        $meta_new=0;
        $progress_pedidos=0;

        foreach ($supervisores_array as $supervisor_2)
        {

            //dd($count_asesor);
            if ($count_asesor[$supervisor_2->id]['total_pedido'] >= 0 && $count_asesor[$supervisor_2->id]['total_pedido'] < $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['total_pedido']>0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round(($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=1;
            }else if($count_asesor[$supervisor_2->id]['total_pedido'] >= $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['meta_2'] > 0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round( ($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta_2'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=2;
            }
        }

        //verificar totales

        if ($all >= 0 && $all < $meta)
        {
            if($all>0)
            {
                $progress_pedidos=round(($all/$meta)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=1;
        }else if($all >= $meta)
        {
            if($meta_2 > 0)
            {
                $progress_pedidos=round( ($all/$meta_2)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=2;
        }

        if ($all_mespasado == 0) {
            $p_pagos = 0;
        } else {
            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }
        }

        $object_totales = [
            //"progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "all_situacion_recurrente" => $all_situacion_recurrente,
            "all_situacion_activo" => $all_situacion_activo,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "meta_combinar" => $meta_combinar,
            "pedidos_dia" => $pedidos_dia,
            "supervisor" => $supervisor,
            "meta_new"=>$meta_new,
            "progress_pedidos"=>$progress_pedidos
        ];

        $html = '';

        /*TOTAL*/

        if ($request->ii == 17) {

            $html .= '<table class="table tabla-metas_pagos_pedidos_17 table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%" style="font-weight: bold;color:blue;">Asesor</th>
                    <th width="11%" style="font-weight: bold;color:blue;">Id</th>
                    <th width="8%"><span style="font-size:10px;font-weight: bold;color:blue;">Día ' . Carbon::now()->day . '  </span></th>
                    <th width="33%" style="font-weight: bold;color:blue;">Cobranza  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                    <th width="40%" style="font-weight: bold;color:blue;">Pedidos  ' . Carbon::parse($fechametames)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            foreach ($progressData as $data) {
                $html .= '<tr>
             <td class="name-size" style="font-weight: bold;color:blue;">' . $data["name"] . '</td>
             <td style="font-weight: bold;color:blue;">' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             <td>';
                if ($data["pedidos_dia"] > 0) {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" > ' . $data["pedidos_dia"] . '</span> ';
                } else {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold"> ' . $data["pedidos_dia"] . ' </span> ';
                }
                $html .= '</td>';
                $html .= '<td>';

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                if ($data["progress_pagos"] >= 100) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p> </span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] >= 80 && $data["progress_pagos"] < 100) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(3,175,3,1) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b  class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 70 && $data["progress_pagos"] < 80) {
                    $html .= '
                    <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 60 && $data["progress_pagos"] <= 70) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 50 && $data["progress_pagos"] <= 60) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                else {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">   ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>';
                $html .= '   <td>';

                /* META - QUINCENA */
                /*                if ($data["meta_new"] == 0) {

                                }

                                else*/
                /*META-1*/
                $font_size_sub=12;

                $sub_html='<sub class="top-visible" style="display: block !important;">
                                      <span style="background:#FFD4D4  !important;" class="badge font-'.$font_size_sub.'">Qui. . '.$data["meta_quincena"].'</span>
                                      <span class="badge bg-warning font-'.$font_size_sub.'">Int. . '.$data["meta_intermedia"].'</span>
                                      <span class="badge bg-success text-dark font-'.$font_size_sub.'"">Pri. . '.$data["meta"].'</span>
                                      <span class="badge bg-primary text-dark font-'.$font_size_sub.'"">Seg. . '.$data["meta_2"].'</span>
                                  </sub>';
                $sub_html='';

                /*calculo para la diferencia en color rojo a la derecha*/
                $diferencia_mostrar=0;
                if($data["meta_quincena"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_quincena"] - $data["total_pedido"]);
                }else if($data["meta_intermedia"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_intermedia"] - $data["total_pedido"]);
                }
                else if($data["meta"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta"] - $data["total_pedido"]);
                }
                else if($data["meta_2"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_2"] - $data["total_pedido"]);
                }else{
                    $diferencia_mostrar=0;
                }


                /**/

                if($data["meta_new"]=='0')
                {
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }

                }
                else if($data["meta_new"]=='0.5')
                {
                    //intermedio
                    //$html .=' el progreso de pedidos  0.5 '.$data["progress_pedidos"];
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        //rojo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 80){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (80<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #59db35 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                }
                if ($data["meta_new"] == '1') {

                    if (  0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 34)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                      <div class="rounded" role="progressbar" style="background: #FFD4D4;width: ' . $data["progress_pedidos"] . '%" ></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                  </div>
                              </div>
                            </div>
                            '.$sub_html;
                    }
                    else if (34<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important;">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 55)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(220,53,69,1) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (55<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 75)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (75<=$data["progress_pedidos"] && $data["progress_pedidos"] < 85)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (85<=$data["progress_pedidos"] && $data["progress_pedidos"] < 90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background:rgba(3,175,3,1) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }else{
                        $html .=' el progreso de pedidos '.$data["progress_pedidos"];
                    }

                } /*META-2*/
                else if ($data["meta_new"] == '2') {
                    if ($data["progress_pedidos"] <= 100) {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                }

                $html .= '  </td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }
        else if ($request->ii == 3)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #ade0db; color: #0a0302">';
            $html .= '<tbody>
              <tr class="responsive-table">
                  <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($object_totales['pedidos_dia'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    TOTAL DE PEDIDOS DEL DIA: ' . $object_totales['pedidos_dia'] . ' </span>';

            $html .= '
                  </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded h-40 h-60-res height-bar-progress" style="height: 25px !important;">';

            $round=$object_totales['progress_pagos'];

            if(0<$round && $round<=40)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(40<$round && $round<=50)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar h-60-res" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos'] - 40) . '%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="' . ($object_totales['progress_pagos'] - 40) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(50<$round && $round<=70)
            {
                $html .= '<div class="progress-bar bg-warning" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(70<$round && $round<=80)
            {
                $html .= '<div class="progress-bar bg-warning rounded  h-60-res height-bar-progress" role="progressbar"
                         style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: ' . ($object_totales['progress_pagos'] - 70) . '%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="' . ($object_totales['progress_pagos'] - 70) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }
            else if(80<$round && $round<=100)
            {
                $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                 style="height: 25px !important;width: ' . $object_totales['progress_pagos'] . '%;background: #03af03;"
                 aria-valuenow="' . $object_totales['progress_pagos'] . '"
                 aria-valuemin="0" aria-valuemax="100"></div>';
            }
            else
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }

            $html .= '</div>
    <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 25px !important;font-size: 12px;">
<span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  ' . $object_totales['progress_pagos'] . '%</b> - ' . $object_totales['total_pagado'] . '/' . $object_totales['total_pedido_mespasado'] . '</span></div>';

            $html .= ' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 25px !important;">';

            //40 50 70 80 100 <

            $round=$object_totales['progress_pedidos'];

            if ($object_totales['meta'] == 0)
            {

            }
            else if ($object_totales['meta_new'] == 1)
            {
                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round-40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round-40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round-70) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round-70) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
            }
            else if ($object_totales['meta_new'] == 2)
            {
                $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }



            if ($object_totales['meta'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta'] . '</span>
    </div>';
            } else {

                if ($object_totales['meta_new'] == 1)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }else if ($object_totales['meta_new'] == 2)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }

            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        }
        else if ($request->ii == 6) {
            $html.=$object_totales['progress_pagos'].'%';
        }
        else if ($request->ii == 7) {
            $html.=$object_totales['progress_pedidos'].'%';
        }
        /*LUISSSSSSSSSSSSSSSSSSSSSSSSSSSSS ----- 46   */
        else if ($request->ii == 4 || $request->ii==5) {

            $idencargado=0;
            if($request->ii==4){$idencargado=46;}
            else if($request->ii==5){$idencargado=24;}

            /*if ($count_asesor[$idencargado]['total_pedido']>=0 && $count_asesor[$idencargado]['total_pedido'] < $count_asesor[$idencargado]['meta']) {
                if ($count_asesor[$idencargado]['meta'] > 0) {
                    $p_pedidos = round(($count_asesor[$idencargado]['total_pedido'] / $count_asesor[$idencargado]['meta']) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $count_asesor[$idencargado]['meta_new'] = 1;
            }
            else if ($count_asesor[$idencargado]['total_pedido']>=$count_asesor[$idencargado]['meta']) {
                if ($count_asesor[$idencargado]['meta_2'] > 0) {
                    $p_pedidos = round(($count_asesor[46]['total_pedido'] / $count_asesor[$idencargado]['meta_2']) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $count_asesor[$idencargado]['meta_new'] = 2;
            }*/

            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #e4dbc6; color: #0a0302">';
            $html .= '<tbody>
                    <tr class="responsive-table">
                        <th class="col-lg-4 col-md-12 col-sm-12">';
            if (($count_asesor[$idencargado]['pedidos_dia']) == 0) {
                $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress" style="height: 30px !important;display:flex; align-items: center; color: black !important;">  PEDIDOS DE ENCARGADO '.strtoupper(explode(' ',$count_asesor[$idencargado]['name'])[0]).': ' . $count_asesor[$idencargado]['pedidos_dia'] . ' </span>';
            } else {
                $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress" style="height: 30px !important;display:flex; align-items: center; color: black !important;">  PEDIDOS DE ENCARGADO '.strtoupper(explode(' ',$count_asesor[$idencargado]['name'])[0]).': ' . $count_asesor[$idencargado]['pedidos_dia'] . ' </span>';
            }
            $html .= '        </th>
                        <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                         <div class="progress rounded h-40 h-60-res height-bar-progress" style="height: 30px !important;">';

            if ($count_asesor[$idencargado]['total_pedido_mespasado'] == 0) {
                $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                         style="height: 30px !important;width: 0%;background: #03af03;"
                         aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100"></div>';
            }
            else {

                if (($count_asesor[$idencargado]['total_pedido_mespasado']) >0)
                {
                    $round=round( ( ($count_asesor[$idencargado]['total_pagado'])/$count_asesor[$idencargado]['total_pedido_mespasado'] )*100 ,2);
                }else{
                    $round=0.00;
                }

                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                       style="height: 30px !important;width: ' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2)) . '%"
                       aria-valuenow="' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado']), 2)) . '"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                       style="height: 30px !important;width: ' . round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2) . '%"
                       aria-valuenow="70"
                       aria-valuemin
                       aria-valuemax="100"></div>
                      <div class="progress-bar h-60-res" role="progressbar"
                           style="width: ' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2) - 40) . '%;
                       background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                           aria-valuenow="' . (round(($count_asesor[$idencargado]['total_pagado'] / (($count_asesor[$idencargado]['total_pedido_mespasado'] > 0) ? $count_asesor[$idencargado]['total_pedido_mespasado'] : '')), 2) - 40) . '"
                           aria-valuemin="0"
                           aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                       style="height: 30px !important;width: ' . round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2) . '%"
                       aria-valuenow="70"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded  h-60-res height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2) . '%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: ' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2) - 70) . '%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado']), 2) - 70) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                         style="height: 30px !important;width: ' . round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado']) * 100, 2) . '%;background: #03af03;"
                         aria-valuenow="' . round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado']), 2) . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                       style="height: 30px !important;width: ' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado'] * 100), 2)) . '%"
                       aria-valuenow="' . (round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado']), 2)) . '"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
                }

            }

            if ($count_asesor[$idencargado]['total_pedido_mespasado'] == 0) {
                $html .= '</div>
                      <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  %</b> - ' . $count_asesor[$idencargado]['total_pagado'] . '/' . $count_asesor[$idencargado]['total_pedido_mespasado'] . '</span>
                      </div>
                    </div>';
            }
            else {
                $html .= '</div>
                      <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  ' . round(($count_asesor[$idencargado]['total_pagado'] / $count_asesor[$idencargado]['total_pedido_mespasado']) * 100, 2) . '%</b> - ' . $count_asesor[$idencargado]['total_pagado'] . '/' . $count_asesor[$idencargado]['total_pedido_mespasado'] . '</span>
                      </div>
                    </div>';
            }

            $html .= ' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 30px !important;">';

            if ($count_asesor[$idencargado]['meta'] == 0) {
                $html .= '<div class="progress-bar bg-danger" role="progressbar"
                 style="width: ' . 0 . '%"
                 aria-valuenow="' . (round(0, 2)) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else {

                if($count_asesor[$idencargado]['total_pedido']>0)
                {
                    $round=round( ( ($count_asesor[$idencargado]['total_pedido'])/$count_asesor[$idencargado]['meta'] )*100 ,2);
                }else{
                    $round=0.00;
                    //cuando pedidos es 0
                }

                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round - 40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round - 40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }

            }

            if ($count_asesor[$idencargado]['meta'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' . $count_asesor[$idencargado]['total_pedido'] . '/' . $count_asesor[$idencargado]['meta'] . '</span>
    </div>';
            } else {

                if ($count_asesor[$idencargado]['meta_new'] == 1)
                {
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(($count_asesor[$idencargado]['total_pedido'] / $count_asesor[$idencargado]['meta']) * 100, 2) . '%</b> - ' . $count_asesor[$idencargado]['total_pedido'] . '/' . $count_asesor[$idencargado]['meta'] . '</span>
    </div>';
                }else if ($count_asesor[$idencargado]['meta_new'] == 2)
                {
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(($count_asesor[$idencargado]['total_pedido'] / $count_asesor[$idencargado]['meta_2']) * 100, 2) . '%</b> - ' . $count_asesor[$idencargado]['total_pedido'] . '/' . $count_asesor[$idencargado]['meta_2'] . '</span>
    </div>';
                }


            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        } /*PAOLAAAAAAAAAAAAAAAAAAAAAAAAAAAAA ----- 24*/

        else if ($request->ii == 1 || $request->ii == 2) {

            //dd($count_asesor);
            //total_pedido
            //meta
            //meta_2


            $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%">Asesor</th>
                    <th width="11%">Id</th>
                    <th width="8%"><span style="font-size:10px;">Día ' . Carbon::now()->day . '  </span></th>
                    <th width="33%">Cobranza  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                    <th width="40%">Pedidos  ' . Carbon::parse($fechametames)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            $medall_icon='';
            foreach ($progressData as $data) {

                if($data["meta_new"]=='0')
                {
                    //bronce
                    $medall_icon='bron<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';

                }
                else if($data["meta_new"]=='0.5')
                {
                    //bronce
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';

                }
                else if($data["meta_new"]=='1')
                {
                    //plata
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:silver;"></i>';
                    $medall_icon='';
                }
                else if($data["meta_new"]=='2')
                {
                    //oro
                    $medall_icon='';
                    $medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';
                    $medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:silver;"></i>';
                    //$medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                    $medall_icon=$medall_icon.'<i class="fas fa-trophy fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                }else{
                    //nada
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                    $medall_icon='';
                }

                $html .= '<tr>
             <td class=""><span class="d-inline-block">'. $data["name"] . '</span></td>
             <td>' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             <td>';
                if ($data["pedidos_dia"] > 0) {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" > ' . $data["pedidos_dia"] . '</span> ';
                } else {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold"> ' . $data["pedidos_dia"] . ' </span> ';
                }
                $html .= '</td>';
                $html .= '<td>';

                /*inicio pagos*/

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                if ($data["progress_pagos"] >= 100) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p> </span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] >= 80 && $data["progress_pagos"] < 100) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(3,175,3,1) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b  class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 70 && $data["progress_pagos"] < 80) {
                    $html .= '
                    <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 60 && $data["progress_pagos"] <= 70) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 50 && $data["progress_pagos"] <= 60) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                else {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size">   ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>';
                $html .= '   <td>';

                /* META - QUINCENA */
                /*                if ($data["meta_new"] == 0) {

                                }

                                else*/
                /*META-1*/
                $font_size_sub=12;

                $sub_html='<sub class="top-visible" style="display: block !important;">
                                      <span style="background:#FFD4D4  !important;" class="badge font-'.$font_size_sub.'">Qui. . '.$data["meta_quincena"].'</span>
                                      <span class="badge bg-warning font-'.$font_size_sub.'">Int. . '.$data["meta_intermedia"].'</span>
                                      <span class="badge bg-success text-dark font-'.$font_size_sub.'"">Pri. . '.$data["meta"].'</span>
                                      <span class="badge bg-primary text-dark font-'.$font_size_sub.'"">Seg. . '.$data["meta_2"].'</span>
                                  </sub>';
                $sub_html='';

                /*calculo para la diferencia en color rojo a la derecha*/
                $diferencia_mostrar=0;
                if($data["meta_quincena"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_quincena"] - $data["total_pedido"]);
                }else if($data["meta_intermedia"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_intermedia"] - $data["total_pedido"]);
                }
                else if($data["meta"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta"] - $data["total_pedido"]);
                }
                else if($data["meta_2"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_2"] - $data["total_pedido"]);
                }else{
                    $diferencia_mostrar=0;
                }


                /**/

                if($data["meta_new"]=='0')
                {
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }

                }
                else if($data["meta_new"]=='0.5')
                {
                    //intermedio
                    //$html .=' el progreso de pedidos  0.5 '.$data["progress_pedidos"];
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        //rojo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 80){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (80<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #59db35 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }

                    /*if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #e35260 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_intermedia"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }*/
                }
                if ($data["meta_new"] == '1') {

                    if (  0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 34)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #FFD4D4;width: ' . $data["progress_pedidos"] . '%" ></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                  </div>
                              </div>
                            </div>
                            '.$sub_html;
                    }
                    else if (34<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important;">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 55)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(220,53,69,1) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (55<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 75)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (75<=$data["progress_pedidos"] && $data["progress_pedidos"] < 85)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (85<=$data["progress_pedidos"] && $data["progress_pedidos"] < 90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background:rgba(3,175,3,1) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }else{
                        $html .=' el progreso de pedidos '.$data["progress_pedidos"];
                    }

                } /*META-2*/
                else if ($data["meta_new"] == '2') {
                    if ($data["progress_pedidos"] <= 100) {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                }

                $html .= '  </td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }

        else if ($request->ii == 8 || $request->ii == 9) {

            $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%">Asesor</th>
                    <th width="11%">Id</th>

                    <th width="33%">ACTIVOS/RECURRENTES (%)  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            foreach ($progressData as $data) {
                $html .= '<tr>
             <td class="name-size">' . $data["name"] . '</td>
             <td>' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             ';
                $html .= '<td>';

                /*inicio pagos*/

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                /*if($data["all_situacion"]==0)
                {
                    $division=0;
                }else{
                    $division=$data["all_situacion_activo"] / $data["all_situacion"];
                }*/

                //$data["all_situacion_activo"];
                if($data["all_situacion_recurrente"]==0)
                {
                    $porcentaje=0.00;
                    $diferencia=0;
                }else{
                    $porcentaje=round(($data["all_situacion_activo"] / ($data["all_situacion_recurrente"]+$data["all_situacion_recurrente"]) )*100,2);
                    $diferencia= ($data["all_situacion_activo"]+$data["all_situacion_recurrente"])-$data["all_situacion_activo"];
                }


                {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #ff7d7d !important; width: '.$porcentaje.'%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 16px;">
                                      <span style="font-weight: bold;"> <b class="bold-size" style="color:#001253;">   ' . $porcentaje . '% - <span style="font-size:11px;color:grey;">'.$data["all_situacion_activo"].'/ Recurrentes.'.($data["all_situacion_recurrente"]).' + activos'.($data["all_situacion_activo"]).'</span> </b>  <p class="text-red d-inline format-size" style="color: #d9686!important">'.$diferencia.' </p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }
        else if ($request->ii == 14)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #e4dbc6; color: #0a0302">';
            $html .= '<tbody>
                    <tr class="responsive-table">
                    <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($count_asesor[46]['all_situacion_activo'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    LUIS - TOTAL DE LEVANTADOS: ' . $count_asesor[46]['all_situacion_activo'] . ' </span>';

            $html.='</th>
                        <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 30px !important;">';

            if($count_asesor[46]['all_situacion_activo']>0)
            {
                $round=round( ( ($count_asesor[46]['all_situacion_activo'])/ ($count_asesor[46]['all_situacion_recurrente']+$count_asesor[46]['all_situacion_activo']) )*100 ,2);
            }else{
                $round=0.00;
            }

            if ($count_asesor[46]['all_situacion_recurrente'] == 0) {
                $html .= '<div class="progress-bar bg-danger" role="progressbar"
                 style="width: ' . $round . '%"
                 aria-valuenow="' . (round(0, 2)) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            } else {

                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round - 40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round - 40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }

            }

            if ($count_asesor[46]['all_situacion_recurrente'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total"
                style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">
             LUIS: TOTAL DEJARON DE PEDIR -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $round . '%</b> - '
                    . $count_asesor[46]['all_situacion_activo'] . '/ Levantado. ' . $count_asesor[46]['all_situacion_activo'].' + Caido. '.$count_asesor[46]['all_situacion_recurrente'] . '</span>
    </div>';
            } else {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total"
                style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">
             LUIS: TOTAL DEJARON DE PEDIR -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $round . '%</b> - '
                    . $count_asesor[46]['all_situacion_activo'] . '/ Levantado. ' . $count_asesor[46]['all_situacion_activo'].' + Caido. '.$count_asesor[46]['all_situacion_recurrente'] . '</span>
    </div>';
            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        } /*PAOLAAAAAAAAAAAAAAAAAAAAAAAAAAAAA ----- 24*/
        else if ($request->ii == 15)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #e4dbc6; color: #0a0302">';
            $html .= '<tbody>
                    <tr class="responsive-table">
                    <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($count_asesor[24]['all_situacion_activo'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    PAOLA - TOTAL DE LEVANTADOS: ' . $count_asesor[24]['all_situacion_activo'] . ' </span>';

            $html.='</th>
                        <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 30px !important;">';

            if ($count_asesor[24]['all_situacion_recurrente'] == 0) {
                $html .= '<div class="progress-bar bg-danger" role="progressbar"
                 style="width: ' . 0 . '%"
                 aria-valuenow="' . (round(0, 2)) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            } else {

                if($count_asesor[24]['all_situacion_activo']>0)
                {
                    $round=round( ( ($count_asesor[24]['all_situacion_activo'])/($count_asesor[24]['all_situacion_recurrente'] +$count_asesor[24]['all_situacion_activo'] ) )*100 ,2);
                }else{
                    $round=0.00;
                    //cuando pedidos es 0
                }

                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round - 40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round - 40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }

            }

            if ($count_asesor[24]['all_situacion_recurrente'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total"
                style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">
             PAOLA: TOTAL DEJARON DE PEDIR -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' .
                    $count_asesor[24]['all_situacion_activo'] . '/ Activos. ' . ($count_asesor[24]['all_situacion_activo']).' + Recurrentes. '.($count_asesor[24]['all_situacion_recurrente']) . '</span>
    </div>';
            } else {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total"
                style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">
             PAOLA: TOTAL DEJARON DE PEDIR -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(($count_asesor[24]['all_situacion_activo'] / ($count_asesor[24]['all_situacion_recurrente']+$count_asesor[24]['all_situacion_activo']) ) * 100, 2) . '%</b> - '
                    . $count_asesor[24]['all_situacion_activo'] . '/ Activos. ' . ($count_asesor[24]['all_situacion_activo']).' + Recurrentes. '.($count_asesor[24]['all_situacion_recurrente']) . '</span>
    </div>';
            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        } /*PAOLAAAAAAAAAAAAAAAAAAAAAAAAAAAAA ----- 24*/

        return $html;
    }

    public function viewMetaTableG2(Request $request)
    {
        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        }
        else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_FORMACION) {

            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->count();

        }
        else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['15','16','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['15','16','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }
        else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            $asesores = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereIn('clave_pedidos',['15','16','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereIn('clave_pedidos',['15','16','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();

        }

        $supervisores_array = User::query()->activo()->rolSupervisor()->get();
        $count_asesor = [];
        foreach ($supervisores_array as $supervisor) {
            $count_asesor[$supervisor->id] =
                [
                    'pedidos_totales' => 0,
                    'total_pedido_mespasado' => 0,
                    'meta_quincena' => 0,
                    'meta_intermedia' => 0,
                    'meta' => 0,
                    'meta_2' => 0,
                    'total_pagado' => 0,
                    'progress_pagos' => 0,
                    'progress_pedidos' => 0,
                    'total_pedido' => 0,
                    'pedidos_dia' => 0,
                    'all_situacion_activo' => 0,
                    'all_situacion_recurrente' => 0,
                    'meta_new'=>0,
                    'name'=>$supervisor->name
                ];
        }

        $clientes_situacion_activo_mayor=0;
        foreach ($asesores as $asesori)
        {
            $clientes_situacion_activo_mayor_ = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('user_id', $asesori->id)
                ->where('clientes.situacion', '=', 'ACTIVO')
                ->activo()
                ->count();
            if($clientes_situacion_activo_mayor_>=$clientes_situacion_activo_mayor_)
            {
                $clientes_situacion_activo_mayor=$clientes_situacion_activo_mayor_;
            }
        }

        //dd($progressData);
        foreach ($asesores as $asesor)
        {
            /*echo "<pre>";
            print_r($asesor);
            echo "</pre>";*/
            if (in_array(auth()->user()->rol, [
                User::ROL_FORMACION
                , User::ROL_ADMIN
                , User::ROL_PRESENTACION
                , User::ROL_ASESOR
                , User::ROL_LLAMADAS
                , User::ROL_JEFE_LLAMADAS
            ])) {
            } else {
                if (auth()->user()->rol != User::ROL_ADMIN) {
                    if (auth()->user()->rol != User::ROL_ENCARGADO) {
                        if (auth()->user()->id != $asesor->id) {
                            continue;
                        }
                    } else {
                        if (auth()->user()->id != $asesor->supervisor) {
                            continue;
                        }
                    }
                }
            }

            /*CONSULTAS PARA MOSTRAR INFO EN TABLA*/
            $date_pagos = Carbon::parse(now())->subMonth()->startOfMonth();
            $fechametames = Carbon::now()->clone();

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            //dd($fechametames,$date_pagos);


            $asesor_pedido_dia = Pedido::query()->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->whereDate('pedidos.created_at', $fechametames)
                ->where('pendiente_anulacion', '<>', '1')->count();

            $meta_calculo_row = Meta::where('rol', User::ROL_ASESOR)
                ->where('user_id', $asesor->id)
                ->where('anio', $fechametames->format('Y'))
                ->where('mes', $fechametames->format('m'))->first();


            if($meta_calculo_row==null)
            {
                \Log::info("Error en meta_dashboard para asesor id -> " . $asesor->id." en periodo ".$fechametames);
            }

            $metatotal_quincena = (float)$meta_calculo_row->meta_quincena;
            $metatotal_intermedia = (float)$meta_calculo_row->meta_intermedia;
            $metatotal_1 = (float)$meta_calculo_row->meta_pedido;
            $metatotal_2 = (float)$meta_calculo_row->meta_pedido_2;

            $asesorid = User::where('rol', User::ROL_ASESOR)->where('id', $asesor->id)->pluck('id');

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $total_pedido = Pedido::query()->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$fechametames->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()])
                ->count();

            $total_pagado_a = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','C'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                //->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado_b = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos.pago','=','1'],
                    ['pedidos.pagado','=','2'],
                    ['pago_pedidos.estado','=',1],
                    ['pago_pedidos.pagado','=',2]
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado=$total_pagado_a+$total_pagado_b;

            $total_pedido_mespasado = Pedido::query()
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->count();

            $supervisor = User::where('rol', User::ROL_ASESOR)->where('clave_pedidos', $asesor->clave_pedidos)->activo()->first()->supervisor;
            $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->where('pendiente_anulacion', '<>', '1')
                ->whereDate('pedidos.created_at', $fechametames)->count();

            $clientes_situacion_activo = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','LEVANTADO')
                ->activo()
                ->count();

            $clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
                ->where('cv.periodo',Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m'))
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','CAIDO')
                ->activo()
                ->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta_quincena" => $metatotal_quincena,
                "meta_intermedia" => $metatotal_intermedia,
                "meta" => $metatotal_1,
                "meta_2" => $metatotal_2,
                "pedidos_totales" => $pedidos_totales,
                "clientes_situacion_activo" => $clientes_situacion_activo,
                "clientes_situacion_recurrente" => $clientes_situacion_recurrente,
                "supervisor" => $supervisor,
            ];

            if (array_key_exists($encargado_asesor, $count_asesor)) {
                /*if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else*/ if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];

                }
            }

            if ($asesor->excluir_meta)
            {
                if ($total_pedido_mespasado > 0)
                {
                    $p_pagos = round(($total_pagado / $total_pedido_mespasado) * 100, 2);
                }
                else {
                    $p_pagos = 0;
                }

                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }
                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }

                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }
                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }

                if ($metatotal_1 > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }
                if ($metatotal_1 > 0)
                {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }

                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                }
                else {
                    $p_pedidos_2 = 0;
                }
                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }

                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                if ($metatotal_intermedia > 0) {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                    $item['meta_new'] = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }
            }
            else */
                if ($total_pedido>=0 && $total_pedido < $metatotal_1) {
                    if ($metatotal_1 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;


            }
            else {
                $progressData[] = $item;
            }


        }

        //dd($progressData);
        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            //var_dump($identificador);
            //if($identificador==21)continue;
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    //if($identificador!=21)
                    {
                        /*echo "<pre>";
                    print_r($item);
                    echo "</pre>";*/
                        $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                        $newData[$identificador]['total_pedido_mespasado'] += data_get($item, 'total_pedido_mespasado');
                        $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                        $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
                        $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');
                        $newData[$identificador]['meta_new'] += data_get($item, 'meta_new');//0 quincena //0.5 intermedia //1 meta1//2 meta2
                        $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');//todo el mes
                        $newData[$identificador]['clientes_situacion_recurrente'] += data_get($item, 'clientes_situacion_recurrente');//todo el mes
                        $newData[$identificador]['clientes_situacion_activo'] += data_get($item, 'clientes_situacion_activo');//todo el mes
                        $newData[$identificador]['meta_quincena'] += data_get($item, 'meta_quincena');
                        $newData[$identificador]['meta_intermedia'] += data_get($item, 'meta_intermedia');
                        $newData[$identificador]['meta'] += data_get($item, 'meta');
                        $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                    }
                }
            }
            //if($identificador!=21)
            {
                $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                    return explode(" ", data_get($item, 'name'))[0];
                })->first();
            }
        }
        //dd($newData);
        $progressData = collect($newData)->values()
            ->map(function ($item) {
                //if(data_get($item, 'identificador') == "21") return false;;

                $all = data_get($item, 'total_pedido');
                $all_mespasado = data_get($item, 'total_pedido_mespasado');
                $pay = data_get($item, 'total_pagado');
                $allmeta__quincena = data_get($item, 'meta_quincena');//15
                $allmeta_intermedia = data_get($item, 'meta_intermedia');//in
                $allmeta = data_get($item, 'meta');//meta 1
                $allmeta_2 = data_get($item, 'meta_2');//meta 2
                $pedidos_dia = data_get($item, 'pedidos_dia');//pedidos diario
                $pedidos_totales = data_get($item, 'pedidos_totales');//pedidos de todo el mes
                $clientes_situacion_recurrente = data_get($item, 'clientes_situacion_recurrente');//pedidos de todo el mes
                $clientes_situacion_activo = data_get($item, 'clientes_situacion_activo');//pedidos de todo el mes
                $supervisor = data_get($item, 'supervisor');
                $meta_new = data_get($item, 'meta_new');

                if ($all_mespasado == 0) {
                    $p_pagos = 0;
                } else {
                    if ($pay > 0) {
                        $p_pagos = round(($pay / $all_mespasado) * 100, 2);
                    } else {
                        $p_pagos = 0;
                    }
                }

                /*meta quincena = 0*/
                /*if ($all>=0 && $all < $allmeta__quincena) {
                    //meta quincena
                    if ($allmeta__quincena > 0) {
                        $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                    }
                    $meta_new = 0;
                    $item['progress_pedidos'] = $p_quincena;
                } else *//*if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                    if ($allmeta_intermedia > 0) {
                        $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                    } else {
                        $p_intermedia = 0;
                    }
                    $meta_new = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }else*/ if ($all>=0  && $all < $allmeta) {
                    if ($allmeta > 0) {
                        $p_pedidos = round(($all / $allmeta) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $meta_new = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                } else if($all>=$allmeta){
                    if ($allmeta_2 > 0) {
                        $p_pedidos_2 = round(($all / $allmeta_2) * 100, 2);
                    } else {
                        $p_pedidos_2 = 0;
                    }
                    $meta_new = 2;
                    $item['progress_pedidos'] = $p_pedidos_2;
                }

                $item['progress_pagos'] = $p_pagos;
                $item['total_pedido'] = $all;
                $item['total_pedido_pasado'] = $all_mespasado;
                $item['pedidos_dia'] = $pedidos_dia;
                $item['pedidos_totales'] = $pedidos_totales;
                $item['all_situacion_recurrente'] = $clientes_situacion_recurrente;
                $item['all_situacion_activo'] = $clientes_situacion_activo;
                $item['meta_new'] = $meta_new;

                if($meta_new==1)
                {

                    $item['meta_combinar']=$item['meta'];
                }else if($meta_new==2)
                {
                    $item['meta_combinar']=$item['meta_2'];
                }

                if($allmeta_2==0)
                    $item['porcentaje_general']=0;
                else
                {
                    $item['porcentaje_general']=($all/$allmeta_2);
                }

                return $item;
            })
            ->reject(function ($value) {
                return $value === false;
            })->sortBy('meta_new', SORT_NUMERIC, true)
            ->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

        if ($request->ii == 21) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 22) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 23) {
            $progressData->all();
        }

        //aqui la division de  1  o 2
        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_situacion_recurrente = collect($progressData)->pluck('all_situacion_recurrente')->sum();
        $all_situacion_activo = collect($progressData)->pluck('all_situacion_activo')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta_quincena = collect($progressData)->pluck('meta_quincena')->sum();
        $meta_intermedia = collect($progressData)->pluck('meta_intermedia')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $meta_combinar = collect($progressData)->pluck('meta_combinar')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        $supervisor = collect($progressData)->pluck('supervisor')->sum();
        $meta_new=0;
        $progress_pedidos=0;

        foreach ($supervisores_array as $supervisor_2)
        {

            //dd($count_asesor);
            if ($count_asesor[$supervisor_2->id]['total_pedido'] >= 0 && $count_asesor[$supervisor_2->id]['total_pedido'] < $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['total_pedido']>0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round(($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=1;
            }else if($count_asesor[$supervisor_2->id]['total_pedido'] >= $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['meta_2'] > 0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round( ($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta_2'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=2;
            }
        }

        //verificar totales

        if ($all >= 0 && $all < $meta)
        {
            if($all>0)
            {
                $progress_pedidos=round(($all/$meta)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=1;
        }
        else if($all >= $meta)
        {
            if($meta_2 > 0)
            {
                $progress_pedidos=round( ($all/$meta_2)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=2;
        }

        if ($all_mespasado == 0) {
            $p_pagos = 0;
        }
        else {
            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }
        }

        $object_totales = [
            //"progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "all_situacion_recurrente" => $all_situacion_recurrente,
            "all_situacion_activo" => $all_situacion_activo,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "meta_combinar" => $meta_combinar,
            "pedidos_dia" => $pedidos_dia,
            "supervisor" => $supervisor,
            "meta_new"=>$meta_new,
            "progress_pedidos"=>$progress_pedidos
        ];

        $html = '';

        /*TOTAL*/

        if ($request->ii == 23)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #ade0db; color: #0a0302">';
            $html .= '<tbody>
              <tr class="responsive-table">
                  <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($object_totales['pedidos_dia'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    TOTAL DE PEDIDOS DEL DIA: ' . $object_totales['pedidos_dia'] . ' </span>';

            $html .= '
                  </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded h-40 h-60-res height-bar-progress" style="height: 25px !important;">';

            $round=$object_totales['progress_pagos'];

            if(0<$round && $round<=40)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(40<$round && $round<=50)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar h-60-res" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos'] - 40) . '%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="' . ($object_totales['progress_pagos'] - 40) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(50<$round && $round<=70)
            {
                $html .= '<div class="progress-bar bg-warning" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(70<$round && $round<=80)
            {
                $html .= '<div class="progress-bar bg-warning rounded  h-60-res height-bar-progress" role="progressbar"
                         style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: ' . ($object_totales['progress_pagos'] - 70) . '%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="' . ($object_totales['progress_pagos'] - 70) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }
            else if(80<$round && $round<=100)
            {
                $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                 style="height: 25px !important;width: ' . $object_totales['progress_pagos'] . '%;background: #03af03;"
                 aria-valuenow="' . $object_totales['progress_pagos'] . '"
                 aria-valuemin="0" aria-valuemax="100"></div>';
            }
            else
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }

            $html .= '</div>
    <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 25px !important;font-size: 12px;">
<span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  ' . $object_totales['progress_pagos'] . '%</b> - ' . $object_totales['total_pagado'] . '/' . $object_totales['total_pedido_mespasado'] . '</span></div>';

            $html .= ' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 25px !important;">';

            //40 50 70 80 100 <

            $round=$object_totales['progress_pedidos'];

            if ($object_totales['meta'] == 0)
            {

            }
            else if ($object_totales['meta_new'] == 1)
            {
                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round-40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round-40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round-70) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round-70) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
            }
            else if ($object_totales['meta_new'] == 2)
            {
                $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }



            if ($object_totales['meta'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta'] . '</span>
    </div>';
            } else {

                if ($object_totales['meta_new'] == 1)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }else if ($object_totales['meta_new'] == 2)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }

            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        }
        else if ($request->ii == 21 || $request->ii == 22) {

            $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%">Asesor</th>
                    <th width="11%">Id</th>
                    <th width="8%"><span style="font-size:10px;">Día ' . Carbon::now()->day . '  </span></th>
                    <th width="33%">Cobranza  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                    <th width="40%">Pedidos  ' . Carbon::parse($fechametames)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            $medall_icon='';
            foreach ($progressData as $data) {

                if($data["meta_new"]=='0')
                {
                    //bronce
                    $medall_icon='bron<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';

                }
                else if($data["meta_new"]=='0.5')
                {
                    //bronce
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';

                }
                else if($data["meta_new"]=='1')
                {
                    //plata
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:silver;"></i>';
                    $medall_icon='';
                }
                else if($data["meta_new"]=='2')
                {
                    //oro
                    $medall_icon='';
                    $medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';
                    $medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:silver;"></i>';
                    //$medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                    $medall_icon=$medall_icon.'<i class="fas fa-trophy fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                }else{
                    //nada
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                    $medall_icon='';
                }

                if($data["identificador"]==21)
                {
                    $data["identificador"]='Z';
                }

                $html .= '<tr>
             <td class=""><span class="d-inline-block">'. $data["name"] . '</span></td>
             <td>' . $data["identificador"] . ' ';

                if($data["identificador"]=='Z')
                {
                    $html .= '';
                }else
                {
                    if ($data["supervisor"] == 46) {
                        $html .= '- A';
                    } else {
                        $html .= '- B';
                    }
                }

                $html .= '
             </td>
             <td>';
                if ($data["pedidos_dia"] > 0) {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" > ' . $data["pedidos_dia"] . '</span> ';
                } else {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold"> ' . $data["pedidos_dia"] . ' </span> ';
                }
                $html .= '</td>';
                $html .= '<td>';

                /*inicio pagos*/

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                if ($data["progress_pagos"] >= 100) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p> </span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] >= 80 && $data["progress_pagos"] < 100) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(3,175,3,1) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b  class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 70 && $data["progress_pagos"] < 80) {
                    $html .= '
                    <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 60 && $data["progress_pagos"] <= 70) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 50 && $data["progress_pagos"] <= 60) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                else {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size">   ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>';
                $html .= '   <td>';

                /* META - QUINCENA */
                /*                if ($data["meta_new"] == 0) {

                                }

                                else*/
                /*META-1*/
                $font_size_sub=12;

                $sub_html='<sub class="top-visible" style="display: block !important;">
                                      <span style="background:#FFD4D4  !important;" class="badge font-'.$font_size_sub.'">Qui. . '.$data["meta_quincena"].'</span>
                                      <span class="badge bg-warning font-'.$font_size_sub.'">Int. . '.$data["meta_intermedia"].'</span>
                                      <span class="badge bg-success text-dark font-'.$font_size_sub.'"">Pri. . '.$data["meta"].'</span>
                                      <span class="badge bg-primary text-dark font-'.$font_size_sub.'"">Seg. . '.$data["meta_2"].'</span>
                                  </sub>';
                $sub_html='';

                /*calculo para la diferencia en color rojo a la derecha*/
                $diferencia_mostrar=0;
                if($data["meta_quincena"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_quincena"] - $data["total_pedido"]);
                }else if($data["meta_intermedia"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_intermedia"] - $data["total_pedido"]);
                }
                else if($data["meta"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta"] - $data["total_pedido"]);
                }
                else if($data["meta_2"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_2"] - $data["total_pedido"]);
                }else{
                    $diferencia_mostrar=0;
                }


                /**/

                if($data["meta_new"]=='0')
                {
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }

                }
                else if($data["meta_new"]=='0.5')
                {
                    //intermedio
                    //$html .=' el progreso de pedidos  0.5 '.$data["progress_pedidos"];
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        //rojo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 80){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (80<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #59db35 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }

                    /*if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #e35260 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_intermedia"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }*/
                }
                if ($data["meta_new"] == '1') {

                    if (  0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 34)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #FFD4D4;width: ' . $data["progress_pedidos"] . '%" ></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                  </div>
                              </div>
                            </div>
                            '.$sub_html;
                    }
                    else if (34<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important;">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 55)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(220,53,69,1) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (55<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 75)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (75<=$data["progress_pedidos"] && $data["progress_pedidos"] < 85)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (85<=$data["progress_pedidos"] && $data["progress_pedidos"] < 90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background:rgba(3,175,3,1) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }else{
                        $html .=' el progreso de pedidos '.$data["progress_pedidos"];
                    }

                } /*META-2*/
                else if ($data["meta_new"] == '2') {
                    if ($data["progress_pedidos"] <= 100) {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                }

                $html .= '  </td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }
        else if ($request->ii == 26) {
            $html.=$object_totales['progress_pagos'].'%';
        }
        else if ($request->ii == 27) {
            $html.=$object_totales['progress_pedidos'].'%';
        }

        return $html;
    }

    public function viewMetaTableG3(Request $request)
    {
        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        }
        else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_FORMACION) {

            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['15','16','17','18','19','21'])
                ->count();

        }
        else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['15','16'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['15','16'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }
        else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            $asesores = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereIn('clave_pedidos',['15','16','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereIn('clave_pedidos',['15','16','21'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();

        }

        $supervisores_array = User::query()->activo()->rolSupervisor()->get();
        $count_asesor = [];
        foreach ($supervisores_array as $supervisor) {
            $count_asesor[$supervisor->id] =
                [
                    'pedidos_totales' => 0,
                    'total_pedido_mespasado' => 0,
                    'meta_quincena' => 0,
                    'meta_intermedia' => 0,
                    'meta' => 0,
                    'meta_2' => 0,
                    'total_pagado' => 0,
                    'progress_pagos' => 0,
                    'progress_pedidos' => 0,
                    'total_pedido' => 0,
                    'pedidos_dia' => 0,
                    'all_situacion_activo' => 0,
                    'all_situacion_recurrente' => 0,
                    'meta_new'=>0,
                    'name'=>$supervisor->name
                ];
        }

        $clientes_situacion_activo_mayor=0;
        foreach ($asesores as $asesori)
        {
            $clientes_situacion_activo_mayor_ = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('user_id', $asesori->id)
                ->where('clientes.situacion', '=', 'ACTIVO')
                ->activo()
                ->count();
            if($clientes_situacion_activo_mayor_>=$clientes_situacion_activo_mayor_)
            {
                $clientes_situacion_activo_mayor=$clientes_situacion_activo_mayor_;
            }
        }

        //dd($progressData);
        foreach ($asesores as $asesor)
        {
            /*echo "<pre>";
            print_r($asesor);
            echo "</pre>";*/
            if (in_array(auth()->user()->rol, [
                User::ROL_FORMACION
                , User::ROL_ADMIN
                , User::ROL_PRESENTACION
                , User::ROL_ASESOR
                , User::ROL_LLAMADAS
                , User::ROL_JEFE_LLAMADAS
            ])) {
            } else {
                if (auth()->user()->rol != User::ROL_ADMIN) {
                    if (auth()->user()->rol != User::ROL_ENCARGADO) {
                        if (auth()->user()->id != $asesor->id) {
                            continue;
                        }
                    } else {
                        if (auth()->user()->id != $asesor->supervisor) {
                            continue;
                        }
                    }
                }
            }

            /*CONSULTAS PARA MOSTRAR INFO EN TABLA*/
            $date_pagos = Carbon::parse(now())->subMonth()->startOfMonth();
            $fechametames = Carbon::now()->clone();

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            //dd($fechametames,$date_pagos);


            $asesor_pedido_dia = Pedido::query()->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->whereDate('pedidos.created_at', $fechametames)
                ->where('pendiente_anulacion', '<>', '1')->count();

            $meta_calculo_row = Meta::where('rol', User::ROL_ASESOR)
                ->where('user_id', $asesor->id)
                ->where('anio', $fechametames->format('Y'))
                ->where('mes', $fechametames->format('m'))->first();


            if($meta_calculo_row==null)
            {
                \Log::info("Error en meta_dashboard para asesor id -> " . $asesor->id." en periodo ".$fechametames);
            }

            $metatotal_quincena = (float)$meta_calculo_row->meta_quincena;
            $metatotal_intermedia = (float)$meta_calculo_row->meta_intermedia;
            $metatotal_1 = (float)$meta_calculo_row->meta_pedido;
            $metatotal_2 = (float)$meta_calculo_row->meta_pedido_2;

            $asesorid = User::where('rol', User::ROL_ASESOR)->where('id', $asesor->id)->pluck('id');

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $total_pedido = Pedido::query()->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$fechametames->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()])
                ->count();

            $total_pagado_a = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','C'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                //->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado_b = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos.pago','=','1'],
                    ['pedidos.pagado','=','2'],
                    ['pago_pedidos.estado','=',1],
                    ['pago_pedidos.pagado','=',2]
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado=$total_pagado_a+$total_pagado_b;

            $total_pedido_mespasado = Pedido::query()
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->count();

            $supervisor = User::where('rol', User::ROL_ASESOR)->where('clave_pedidos', $asesor->clave_pedidos)->activo()->first()->supervisor;
            $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->where('pendiente_anulacion', '<>', '1')
                ->whereDate('pedidos.created_at', $fechametames)->count();

            $clientes_situacion_activo = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','LEVANTADO')
                ->activo()
                ->count();

            $clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
                ->where('cv.periodo',Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m'))
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','CAIDO')
                ->activo()
                ->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta_quincena" => $metatotal_quincena,
                "meta_intermedia" => $metatotal_intermedia,
                "meta" => $metatotal_1,
                "meta_2" => $metatotal_2,
                "pedidos_totales" => $pedidos_totales,
                "clientes_situacion_activo" => $clientes_situacion_activo,
                "clientes_situacion_recurrente" => $clientes_situacion_recurrente,
                "supervisor" => $supervisor,
            ];

            if (array_key_exists($encargado_asesor, $count_asesor)) {
                /*if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else*/ if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];

                }
            }

            if ($asesor->excluir_meta)
            {
                if ($total_pedido_mespasado > 0)
                {
                    $p_pagos = round(($total_pagado / $total_pedido_mespasado) * 100, 2);
                }
                else {
                    $p_pagos = 0;
                }

                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }
                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }

                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }
                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }

                if ($metatotal_1 > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }
                if ($metatotal_1 > 0)
                {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }

                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                }
                else {
                    $p_pedidos_2 = 0;
                }
                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }

                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                if ($metatotal_intermedia > 0) {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                    $item['meta_new'] = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }
            }
            else */
                if ($total_pedido>=0 && $total_pedido < $metatotal_1) {
                    if ($metatotal_1 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;


            }
            else {
                $progressData[] = $item;
            }


        }

        //dd($progressData);
        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            //var_dump($identificador);
            //if($identificador==21)continue;
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    //if($identificador!=21)
                    {
                        /*echo "<pre>";
                    print_r($item);
                    echo "</pre>";*/
                        $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                        $newData[$identificador]['total_pedido_mespasado'] += data_get($item, 'total_pedido_mespasado');
                        $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                        $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
                        $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');
                        $newData[$identificador]['meta_new'] += data_get($item, 'meta_new');//0 quincena //0.5 intermedia //1 meta1//2 meta2
                        $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');//todo el mes
                        $newData[$identificador]['clientes_situacion_recurrente'] += data_get($item, 'clientes_situacion_recurrente');//todo el mes
                        $newData[$identificador]['clientes_situacion_activo'] += data_get($item, 'clientes_situacion_activo');//todo el mes
                        $newData[$identificador]['meta_quincena'] += data_get($item, 'meta_quincena');
                        $newData[$identificador]['meta_intermedia'] += data_get($item, 'meta_intermedia');
                        $newData[$identificador]['meta'] += data_get($item, 'meta');
                        $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                    }
                }
            }
            //if($identificador!=21)
            {
                $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                    return explode(" ", data_get($item, 'name'))[0];
                })->first();
            }
        }
        //dd($newData);
        $progressData = collect($newData)->values()
            ->map(function ($item) {
                //if(data_get($item, 'identificador') == "21") return false;;

                $all = data_get($item, 'total_pedido');
                $all_mespasado = data_get($item, 'total_pedido_mespasado');
                $pay = data_get($item, 'total_pagado');
                $allmeta__quincena = data_get($item, 'meta_quincena');//15
                $allmeta_intermedia = data_get($item, 'meta_intermedia');//in
                $allmeta = data_get($item, 'meta');//meta 1
                $allmeta_2 = data_get($item, 'meta_2');//meta 2
                $pedidos_dia = data_get($item, 'pedidos_dia');//pedidos diario
                $pedidos_totales = data_get($item, 'pedidos_totales');//pedidos de todo el mes
                $clientes_situacion_recurrente = data_get($item, 'clientes_situacion_recurrente');//pedidos de todo el mes
                $clientes_situacion_activo = data_get($item, 'clientes_situacion_activo');//pedidos de todo el mes
                $supervisor = data_get($item, 'supervisor');
                $meta_new = data_get($item, 'meta_new');

                if ($all_mespasado == 0) {
                    $p_pagos = 0;
                } else {
                    if ($pay > 0) {
                        $p_pagos = round(($pay / $all_mespasado) * 100, 2);
                    } else {
                        $p_pagos = 0;
                    }
                }

                /*meta quincena = 0*/
                /*if ($all>=0 && $all < $allmeta__quincena) {
                    //meta quincena
                    if ($allmeta__quincena > 0) {
                        $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                    }
                    $meta_new = 0;
                    $item['progress_pedidos'] = $p_quincena;
                } else *//*if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                    if ($allmeta_intermedia > 0) {
                        $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                    } else {
                        $p_intermedia = 0;
                    }
                    $meta_new = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }else*/ if ($all>=0  && $all < $allmeta) {
                    if ($allmeta > 0) {
                        $p_pedidos = round(($all / $allmeta) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $meta_new = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                } else if($all>=$allmeta){
                    if ($allmeta_2 > 0) {
                        $p_pedidos_2 = round(($all / $allmeta_2) * 100, 2);
                    } else {
                        $p_pedidos_2 = 0;
                    }
                    $meta_new = 2;
                    $item['progress_pedidos'] = $p_pedidos_2;
                }

                $item['progress_pagos'] = $p_pagos;
                $item['total_pedido'] = $all;
                $item['total_pedido_pasado'] = $all_mespasado;
                $item['pedidos_dia'] = $pedidos_dia;
                $item['pedidos_totales'] = $pedidos_totales;
                $item['all_situacion_recurrente'] = $clientes_situacion_recurrente;
                $item['all_situacion_activo'] = $clientes_situacion_activo;
                $item['meta_new'] = $meta_new;

                if($meta_new==1)
                {

                    $item['meta_combinar']=$item['meta'];
                }else if($meta_new==2)
                {
                    $item['meta_combinar']=$item['meta_2'];
                }


                if($allmeta_2==0)
                    $item['porcentaje_general']=0;
                else
                {
                    $item['porcentaje_general']=($all/$allmeta_2);
                }

                return $item;
            })
            ->reject(function ($value) {
                return $value === false;
            })->sortBy('meta_new', SORT_NUMERIC, true)
            ->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

        if ($request->ii == 21) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 22) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 23) {
            $progressData->all();
        }

        //aqui la division de  1  o 2
        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_situacion_recurrente = collect($progressData)->pluck('all_situacion_recurrente')->sum();
        $all_situacion_activo = collect($progressData)->pluck('all_situacion_activo')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta_quincena = collect($progressData)->pluck('meta_quincena')->sum();
        $meta_intermedia = collect($progressData)->pluck('meta_intermedia')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $meta_combinar = collect($progressData)->pluck('meta_combinar')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        $supervisor = collect($progressData)->pluck('supervisor')->sum();
        $meta_new=0;
        $progress_pedidos=0;

        foreach ($supervisores_array as $supervisor_2)
        {

            //dd($count_asesor);
            if ($count_asesor[$supervisor_2->id]['total_pedido'] >= 0 && $count_asesor[$supervisor_2->id]['total_pedido'] < $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['total_pedido']>0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round(($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=1;
            }else if($count_asesor[$supervisor_2->id]['total_pedido'] >= $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['meta_2'] > 0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round( ($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta_2'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=2;
            }
        }

        //verificar totales

        if ($all >= 0 && $all < $meta)
        {
            if($all>0)
            {
                $progress_pedidos=round(($all/$meta)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=1;
        }
        else if($all >= $meta)
        {
            if($meta_2 > 0)
            {
                $progress_pedidos=round( ($all/$meta_2)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=2;
        }

        if ($all_mespasado == 0) {
            $p_pagos = 0;
        }
        else {
            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }
        }

        $object_totales = [
            //"progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "all_situacion_recurrente" => $all_situacion_recurrente,
            "all_situacion_activo" => $all_situacion_activo,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "meta_combinar" => $meta_combinar,
            "pedidos_dia" => $pedidos_dia,
            "supervisor" => $supervisor,
            "meta_new"=>$meta_new,
            "progress_pedidos"=>$progress_pedidos
        ];

        $html = '';

        /*TOTAL*/

        if ($request->ii == 23)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #ade0db; color: #0a0302">';
            $html .= '<tbody>
              <tr class="responsive-table">
                  <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($object_totales['pedidos_dia'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    TOTAL DE PEDIDOS DEL DIA: ' . $object_totales['pedidos_dia'] . ' </span>';

            $html .= '
                  </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded h-40 h-60-res height-bar-progress" style="height: 25px !important;">';

            $round=$object_totales['progress_pagos'];

            if(0<$round && $round<=40)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(40<$round && $round<=50)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar h-60-res" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos'] - 40) . '%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="' . ($object_totales['progress_pagos'] - 40) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(50<$round && $round<=70)
            {
                $html .= '<div class="progress-bar bg-warning" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(70<$round && $round<=80)
            {
                $html .= '<div class="progress-bar bg-warning rounded  h-60-res height-bar-progress" role="progressbar"
                         style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: ' . ($object_totales['progress_pagos'] - 70) . '%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="' . ($object_totales['progress_pagos'] - 70) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }
            else if(80<$round && $round<=100)
            {
                $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                 style="height: 25px !important;width: ' . $object_totales['progress_pagos'] . '%;background: #03af03;"
                 aria-valuenow="' . $object_totales['progress_pagos'] . '"
                 aria-valuemin="0" aria-valuemax="100"></div>';
            }
            else
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }

            $html .= '</div>
    <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 25px !important;font-size: 12px;">
<span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  ' . $object_totales['progress_pagos'] . '%</b> - ' . $object_totales['total_pagado'] . '/' . $object_totales['total_pedido_mespasado'] . '</span></div>';

            $html .= ' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 25px !important;">';

            //40 50 70 80 100 <

            $round=$object_totales['progress_pedidos'];

            if ($object_totales['meta'] == 0)
            {

            }
            else if ($object_totales['meta_new'] == 1)
            {
                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round-40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round-40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round-70) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round-70) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
            }
            else if ($object_totales['meta_new'] == 2)
            {
                $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }



            if ($object_totales['meta'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta'] . '</span>
    </div>';
            } else {

                if ($object_totales['meta_new'] == 1)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }else if ($object_totales['meta_new'] == 2)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }

            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';
        }
        else if ($request->ii == 21 || $request->ii == 22) {

            $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%">Asesor</th>
                    <th width="11%">Id</th>
                    <th width="8%"><span style="font-size:10px;">Día ' . Carbon::now()->day . '  </span></th>
                    <th width="33%">Cobranza  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                    <th width="40%">Pedidos  ' . Carbon::parse($fechametames)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            $medall_icon='';
            foreach ($progressData as $data) {

                if($data["meta_new"]=='0')
                {
                    //bronce
                    $medall_icon='bron<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';

                }
                else if($data["meta_new"]=='0.5')
                {
                    //bronce
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';

                }
                else if($data["meta_new"]=='1')
                {
                    //plata
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:silver;"></i>';
                    $medall_icon='';
                }
                else if($data["meta_new"]=='2')
                {
                    //oro
                    $medall_icon='';
                    $medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:#cd7f32;"></i>';
                    $medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:silver;"></i>';
                    //$medall_icon=$medall_icon.'<i class="fas fa-medal fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                    $medall_icon=$medall_icon.'<i class="fas fa-trophy fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                }else{
                    //nada
                    $medall_icon='<i class="fas fa-medal fa-xs" style="font-size:18px;color:goldenrod;"></i>';
                    $medall_icon='';
                }

                $html .= '<tr>
             <td class=""><span class="d-inline-block">'. $data["name"] . '</span></td>
             <td>' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             <td>';
                if ($data["pedidos_dia"] > 0) {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" > ' . $data["pedidos_dia"] . '</span> ';
                } else {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold"> ' . $data["pedidos_dia"] . ' </span> ';
                }
                $html .= '</td>';
                $html .= '<td>';

                /*inicio pagos*/

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                if ($data["progress_pagos"] >= 100) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p> </span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] >= 80 && $data["progress_pagos"] < 100) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(3,175,3,1) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b  class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 70 && $data["progress_pagos"] < 80) {
                    $html .= '
                    <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 60 && $data["progress_pagos"] <= 70) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 50 && $data["progress_pagos"] <= 60) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                else {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size">   ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>';
                $html .= '   <td>';

                /* META - QUINCENA */
                /*                if ($data["meta_new"] == 0) {

                                }

                                else*/
                /*META-1*/
                $font_size_sub=12;

                $sub_html='<sub class="top-visible" style="display: block !important;">
                                      <span style="background:#FFD4D4  !important;" class="badge font-'.$font_size_sub.'">Qui. . '.$data["meta_quincena"].'</span>
                                      <span class="badge bg-warning font-'.$font_size_sub.'">Int. . '.$data["meta_intermedia"].'</span>
                                      <span class="badge bg-success text-dark font-'.$font_size_sub.'"">Pri. . '.$data["meta"].'</span>
                                      <span class="badge bg-primary text-dark font-'.$font_size_sub.'"">Seg. . '.$data["meta_2"].'</span>
                                  </sub>';
                $sub_html='';

                /*calculo para la diferencia en color rojo a la derecha*/
                $diferencia_mostrar=0;
                if($data["meta_quincena"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_quincena"] - $data["total_pedido"]);
                }else if($data["meta_intermedia"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_intermedia"] - $data["total_pedido"]);
                }
                else if($data["meta"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta"] - $data["total_pedido"]);
                }
                else if($data["meta_2"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_2"] - $data["total_pedido"]);
                }else{
                    $diferencia_mostrar=0;
                }


                /**/

                if($data["meta_new"]=='0')
                {
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }

                }
                else if($data["meta_new"]=='0.5')
                {
                    //intermedio
                    //$html .=' el progreso de pedidos  0.5 '.$data["progress_pedidos"];
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        //rojo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 80){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (80<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #59db35 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }

                    /*if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                      <div class="rounded" role="progressbar" style="background: #e35260 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_intermedia"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }*/
                }
                if ($data["meta_new"] == '1') {

                    if (  0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 34)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #FFD4D4;width: ' . $data["progress_pedidos"] . '%" ></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                  </div>
                              </div>
                            </div>
                            '.$sub_html;
                    }
                    else if (34<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important;">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 55)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(220,53,69,1) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (55<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 75)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (75<=$data["progress_pedidos"] && $data["progress_pedidos"] < 85)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (85<=$data["progress_pedidos"] && $data["progress_pedidos"] < 90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background:rgba(3,175,3,1) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }else{
                        $html .=' el progreso de pedidos '.$data["progress_pedidos"];
                    }

                } /*META-2*/
                else if ($data["meta_new"] == '2') {
                    if ($data["progress_pedidos"] <= 100) {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                }

                $html .= '  </td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }
        else if ($request->ii == 26) {
            $html.=$object_totales['progress_pagos'].'%';
        }
        else if ($request->ii == 27) {
            $html.=$object_totales['progress_pedidos'].'%';
        }

        return $html;
    }

    public function viewMetaTableG17(Request $request)
    {
        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        }
        else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereNotIn('clave_pedidos',['17','18','19'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_FORMACION) {
            //$asesores = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->get();
            //$total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->count();

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['17','18','19'])
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['17','18','19'])
                ->count();

        }
        else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['17','18','19'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['17','18','19'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();

        }
        else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            $asesores = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereIn('clave_pedidos',['17','18','19'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                //->where('excluir_meta', '<>', '1')
                ->whereIn('clave_pedidos',['17','18','19'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();

        }

        $supervisores_array = User::query()->activo()->rolSupervisor()->get();
        $count_asesor = [];
        foreach ($supervisores_array as $supervisor) {
            $count_asesor[$supervisor->id] =
                [
                    'pedidos_totales' => 0,
                    'total_pedido_mespasado' => 0,
                    'meta_quincena' => 0,
                    'meta_intermedia' => 0,
                    'meta' => 0,
                    'meta_2' => 0,
                    'total_pagado' => 0,
                    'progress_pagos' => 0,
                    'progress_pedidos' => 0,
                    'total_pedido' => 0,
                    'pedidos_dia' => 0,
                    'all_situacion_activo' => 0,
                    'all_situacion_recurrente' => 0,
                    'meta_new'=>0,
                    'name'=>$supervisor->name
                ];
        }

        $clientes_situacion_activo_mayor=0;
        foreach ($asesores as $asesori)
        {
            $clientes_situacion_activo_mayor_ = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('user_id', $asesori->id)
                ->where('clientes.situacion', '=', 'ACTIVO')
                ->activo()
                ->count();
            if($clientes_situacion_activo_mayor_>=$clientes_situacion_activo_mayor_)
            {
                $clientes_situacion_activo_mayor=$clientes_situacion_activo_mayor_;
            }
        }

        //dd($progressData);
        foreach ($asesores as $asesor)
        {
            /*echo "<pre>";
            print_r($asesor);
            echo "</pre>";*/
            if (in_array(auth()->user()->rol, [
                User::ROL_FORMACION
                , User::ROL_ADMIN
                , User::ROL_PRESENTACION
                , User::ROL_ASESOR
                , User::ROL_LLAMADAS
                , User::ROL_JEFE_LLAMADAS
            ])) {
            } else {
                if (auth()->user()->rol != User::ROL_ADMIN) {
                    if (auth()->user()->rol != User::ROL_ENCARGADO) {
                        if (auth()->user()->id != $asesor->id) {
                            continue;
                        }
                    } else {
                        if (auth()->user()->id != $asesor->supervisor) {
                            continue;
                        }
                    }
                }
            }

            /*CONSULTAS PARA MOSTRAR INFO EN TABLA*/
            $date_pagos = Carbon::parse(now())->subMonth()->startOfMonth();
            $fechametames = Carbon::now()->clone();

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            //dd($fechametames,$date_pagos);


            $asesor_pedido_dia = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->whereDate('pedidos.created_at', $fechametames)
                ->where('pendiente_anulacion', '<>', '1')->count();

            $meta_calculo_row = Meta::where('rol', User::ROL_ASESOR)
                ->where('user_id', $asesor->id)
                ->where('anio', $fechametames->format('Y'))
                ->where('mes', $fechametames->format('m'))->first();


            if($meta_calculo_row==null)
            {
                \Log::info("Error en meta_dashboard para asesor id -> " . $asesor->id." en periodo ".$fechametames);
            }

            $metatotal_quincena = (float)$meta_calculo_row->meta_quincena;
            $metatotal_intermedia = (float)$meta_calculo_row->meta_intermedia;
            $metatotal_1 = (float)$meta_calculo_row->meta_pedido;
            $metatotal_2 = (float)$meta_calculo_row->meta_pedido_2;

            $asesorid = User::where('rol', User::ROL_ASESOR)->where('id', $asesor->id)->pluck('id');

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $total_pedido = Pedido::query()->where('user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$fechametames->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()])
                ->count();

            $total_pagado_a = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','C'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                //->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado_b = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos.pago','=','1'],
                    ['pedidos.pagado','=','2'],
                    ['pago_pedidos.estado','=',1],
                    ['pago_pedidos.pagado','=',2]
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->count();

            $total_pagado=$total_pagado_a+$total_pagado_b;

            $total_pedido_mespasado = Pedido::query()
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.estado', '1')
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->count();

            $supervisor = User::where('rol', User::ROL_ASESOR)->where('clave_pedidos', $asesor->clave_pedidos)->activo()->first()->supervisor;
            $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->where('pendiente_anulacion', '<>', '1')
                ->whereDate('pedidos.created_at', $fechametames)->count();

            $clientes_situacion_activo = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','LEVANTADO')
                ->activo()
                ->count();

            $clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
                ->where('cv.periodo',Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m'))
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','CAIDO')
                ->activo()
                ->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta_quincena" => $metatotal_quincena,
                "meta_intermedia" => $metatotal_intermedia,
                "meta" => $metatotal_1,
                "meta_2" => $metatotal_2,
                "pedidos_totales" => $pedidos_totales,
                "clientes_situacion_activo" => $clientes_situacion_activo,
                "clientes_situacion_recurrente" => $clientes_situacion_recurrente,
                "supervisor" => $supervisor,
            ];

            if (array_key_exists($encargado_asesor, $count_asesor)) {
                /*if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else*/ if ($encargado_asesor == 24) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];

                }
            }

            if ($asesor->excluir_meta)
            {
                if ($total_pedido_mespasado > 0)
                {
                    $p_pagos = round(($total_pagado / $total_pedido_mespasado) * 100, 2);
                }
                else {
                    $p_pagos = 0;
                }

                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }
                if ($metatotal_quincena > 0)
                {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                }
                else
                {
                    $p_quincena = 0;
                }

                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }
                if ($metatotal_intermedia > 0)
                {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                }
                else
                {
                    $p_intermedia = 0;
                }

                if ($metatotal_1 > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }
                if ($metatotal_1 > 0)
                {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                }
                else {
                    $p_pedidos = 0;
                }

                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                }
                else {
                    $p_pedidos_2 = 0;
                }
                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }

                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                if ($metatotal_intermedia > 0) {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                    $item['meta_new'] = 0.5;
                    $item['progress_pedidos'] = $p_intermedia;
                }
            }
            else */
                if ($total_pedido>=0 && $total_pedido < $metatotal_1) {
                    if ($metatotal_1 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;
                if ($total_pedido>=0 && $total_pedido < $metatotal_1)
                {
                    if ($metatotal_1 > 0)
                    {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    }
                    else
                    {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1)
                {
                    if ($metatotal_2 > 0)
                    {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    }
                    else
                    {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;

            }
            else {
                $progressData[] = $item;
            }


        }

        //dd($progressData);
        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    /*echo "<pre>";
                    print_r($item);
                    echo "</pre>";*/
                    $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                    $newData[$identificador]['total_pedido_mespasado'] += data_get($item, 'total_pedido_mespasado');
                    $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                    $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
                    $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');
                    $newData[$identificador]['meta_new'] += data_get($item, 'meta_new');//0 quincena //0.5 intermedia //1 meta1//2 meta2
                    $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');//todo el mes
                    $newData[$identificador]['clientes_situacion_recurrente'] += data_get($item, 'clientes_situacion_recurrente');//todo el mes
                    $newData[$identificador]['clientes_situacion_activo'] += data_get($item, 'clientes_situacion_activo');//todo el mes
                    $newData[$identificador]['meta_quincena'] += data_get($item, 'meta_quincena');
                    $newData[$identificador]['meta_intermedia'] += data_get($item, 'meta_intermedia');
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total_pedido');
            $all_mespasado = data_get($item, 'total_pedido_mespasado');
            $pay = data_get($item, 'total_pagado');
            $allmeta__quincena = data_get($item, 'meta_quincena');//15
            $allmeta_intermedia = data_get($item, 'meta_intermedia');//in
            $allmeta = data_get($item, 'meta');//meta 1
            $allmeta_2 = data_get($item, 'meta_2');//meta 2
            $pedidos_dia = data_get($item, 'pedidos_dia');//pedidos diario
            $pedidos_totales = data_get($item, 'pedidos_totales');//pedidos de todo el mes
            $clientes_situacion_recurrente = data_get($item, 'clientes_situacion_recurrente');//pedidos de todo el mes
            $clientes_situacion_activo = data_get($item, 'clientes_situacion_activo');//pedidos de todo el mes
            $supervisor = data_get($item, 'supervisor');
            $meta_new = data_get($item, 'meta_new');

            if ($all_mespasado == 0) {
                $p_pagos = 0;
            } else {
                if ($pay > 0) {
                    $p_pagos = round(($pay / $all_mespasado) * 100, 2);
                } else {
                    $p_pagos = 0;
                }
            }

            /*meta quincena = 0*/
            /*if ($all>=0 && $all < $allmeta__quincena) {
                //meta quincena
                if ($allmeta__quincena > 0) {
                    $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                } else {
                    $p_quincena = 0;
                }
                $meta_new = 0;
                $item['progress_pedidos'] = $p_quincena;
            } else *//*if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                if ($allmeta_intermedia > 0) {
                    $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                }
                $meta_new = 0.5;
                $item['progress_pedidos'] = $p_intermedia;
            }else*/ if ($all>=0  && $all < $allmeta) {
                if ($allmeta > 0) {
                    $p_pedidos = round(($all / $allmeta) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $meta_new = 1;
                $item['progress_pedidos'] = $p_pedidos;
            } else if($all>=$allmeta){
                if ($allmeta_2 > 0) {
                    $p_pedidos_2 = round(($all / $allmeta_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }
                $meta_new = 2;
                $item['progress_pedidos'] = $p_pedidos_2;
            }

            $item['progress_pagos'] = $p_pagos;
            $item['total_pedido'] = $all;
            $item['total_pedido_pasado'] = $all_mespasado;
            $item['pedidos_dia'] = $pedidos_dia;
            $item['pedidos_totales'] = $pedidos_totales;
            $item['all_situacion_recurrente'] = $clientes_situacion_recurrente;
            $item['all_situacion_activo'] = $clientes_situacion_activo;
            $item['meta_new'] = $meta_new;

            if($meta_new==1)
            {
                $item['meta_combinar']=$item['meta'];
            }else if($meta_new==2)
            {
                $item['meta_combinar']=$item['meta_2'];
            }

            if($allmeta_2==0)
                $item['porcentaje_general']=0;
            else
            {
                $item['porcentaje_general']=($all/$allmeta_2);
            }

            return $item;
        })->sortBy('meta_new', SORT_NUMERIC, true)
            ->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

        if ($request->ii == 17) {
            $progressData->all();
        }else if ($request->ii == 37) {
        $progressData->all();
    }


        //aqui la division de  1  o 2
        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_situacion_recurrente = collect($progressData)->pluck('all_situacion_recurrente')->sum();
        $all_situacion_activo = collect($progressData)->pluck('all_situacion_activo')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta_quincena = collect($progressData)->pluck('meta_quincena')->sum();
        $meta_intermedia = collect($progressData)->pluck('meta_intermedia')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        $supervisor = collect($progressData)->pluck('supervisor')->sum();
        $meta_combinar = collect($progressData)->pluck('meta_combinar')->sum();
        $meta_new=0;
        $progress_pedidos=0;

        foreach ($supervisores_array as $supervisor_2)
        {

            //dd($count_asesor);
            if ($count_asesor[$supervisor_2->id]['total_pedido'] >= 0 && $count_asesor[$supervisor_2->id]['total_pedido'] < $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['total_pedido']>0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round(($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=1;
            }else if($count_asesor[$supervisor_2->id]['total_pedido'] >= $count_asesor[$supervisor_2->id]['meta'])
            {
                if($count_asesor[$supervisor_2->id]['meta_2'] > 0)
                {
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=round( ($count_asesor[$supervisor_2->id]['total_pedido']/$count_asesor[$supervisor_2->id]['meta_2'])*100,2 );
                }else{
                    $count_asesor[$supervisor_2->id]['progress_pedidos']=0;
                }
                $count_asesor[$supervisor_2->id]['meta_new']=2;
            }
        }

        //verificar totales

        if ($all >= 0 && $all < $meta)
        {
            if($all>0)
            {
                $progress_pedidos=round(($all/$meta)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=1;
        }else if($all >= $meta)
        {
            if($meta_2 > 0)
            {
                $progress_pedidos=round( ($all/$meta_2)*100,2 );
            }else{
                $progress_pedidos=0;
            }
            $meta_new=2;
        }

        if ($all_mespasado == 0) {
            $p_pagos = 0;
        } else {
            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }
        }

        $object_totales = [
            //"progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "all_situacion_recurrente" => $all_situacion_recurrente,
            "all_situacion_activo" => $all_situacion_activo,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "pedidos_dia" => $pedidos_dia,
            "supervisor" => $supervisor,
            "meta_new"=>$meta_new,
            "meta_combinar" => $meta_combinar,
            "progress_pedidos"=>$progress_pedidos
        ];

        $html = '';

        /*TOTAL*/

        if ($request->ii == 17) {

            $html .= '<table class="table tabla-metas_pagos_pedidos_17 table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%" style="font-weight: bold;color:blue;">Asesor</th>
                    <th width="11%" style="font-weight: bold;color:blue;">Id</th>
                    <th width="8%"><span style="font-size:10px;font-weight: bold;color:blue;">Día ' . Carbon::now()->day . '  </span></th>
                    <th width="33%" style="font-weight: bold;color:blue;">Cobranza  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                    <th width="40%" style="font-weight: bold;color:blue;">Pedidos  ' . Carbon::parse($fechametames)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            foreach ($progressData as $data) {
                $html .= '<tr>
             <td class="name-size" style="font-weight: bold;color:blue;">' . $data["name"] . '</td>
             <td style="font-weight: bold;color:blue;">' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             <td>';
                if ($data["pedidos_dia"] > 0) {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" > ' . $data["pedidos_dia"] . '</span> ';
                } else {
                    $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold"> ' . $data["pedidos_dia"] . ' </span> ';
                }
                $html .= '</td>';
                $html .= '<td>';

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                if ($data["progress_pagos"] >= 100) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p> </span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] >= 80 && $data["progress_pagos"] < 100) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(3,175,3,1) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b  class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 70 && $data["progress_pagos"] < 80) {
                    $html .= '
                    <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 60 && $data["progress_pagos"] <= 70) {
                    $html .= ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                elseif ($data["progress_pagos"] > 50 && $data["progress_pagos"] <= 60) {
                    $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }
                else {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">   ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red d-inline format-size" style="color: #d9686!important"> ' . ((($data["total_pedido_mespasado"] - $data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"] - $data["total_pagado"]) : '0') . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>';
                $html .= '   <td>';

                /* META - QUINCENA */
                /*                if ($data["meta_new"] == 0) {

                                }

                                else*/
                /*META-1*/
                $font_size_sub=12;

                $sub_html='<sub class="top-visible" style="display: block !important;">
                                      <span style="background:#FFD4D4  !important;" class="badge font-'.$font_size_sub.'">Qui. . '.$data["meta_quincena"].'</span>
                                      <span class="badge bg-warning font-'.$font_size_sub.'">Int. . '.$data["meta_intermedia"].'</span>
                                      <span class="badge bg-success text-dark font-'.$font_size_sub.'"">Pri. . '.$data["meta"].'</span>
                                      <span class="badge bg-primary text-dark font-'.$font_size_sub.'"">Seg. . '.$data["meta_2"].'</span>
                                  </sub>';
                $sub_html='';

                /*calculo para la diferencia en color rojo a la derecha*/
                $diferencia_mostrar=0;
                if($data["meta_quincena"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_quincena"] - $data["total_pedido"]);
                }else if($data["meta_intermedia"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_intermedia"] - $data["total_pedido"]);
                }
                else if($data["meta"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta"] - $data["total_pedido"]);
                }
                else if($data["meta_2"]-$data["total_pedido"]>0)
                {
                    $diferencia_mostrar=($data["meta_2"] - $data["total_pedido"]);
                }else{
                    $diferencia_mostrar=0;
                }


                /**/

                if($data["meta_new"]=='0')
                {
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"]<90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"] && $data["progress_pedidos"]<99)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                          <div class="rounded" role="progressbar" style="background: #FFD4D4 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                          <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                              <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                          </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }
                    else if(99<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                                    <div class="position-relative rounded">
                                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                          </div>
                                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_quincena"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_quincena"] - $data["total_pedido"]) > 0) ? ($data["meta_quincena"] - $data["total_pedido"]) : '0') . '</p></span>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  '.$sub_html;
                    }

                }
                else if($data["meta_new"]=='0.5')
                {
                    //intermedio
                    //$html .=' el progreso de pedidos  0.5 '.$data["progress_pedidos"];
                    if (0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        //rojo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 80){
                        //amarillo
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }else if (80<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                              <div class="position-relative rounded">
                                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                                      <div class="rounded" role="progressbar" style="background: #59db35 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                                      </div>
                                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta_intermedia"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . ((($data["meta_intermedia"] - $data["total_pedido"]) > 0) ? ($data["meta_intermedia"] - $data["total_pedido"]) : '0') . '</p></span>
                                                  </div>
                                              </div>
                                            </div>
                                            '.$sub_html;

                    }
                }
                if ($data["meta_new"] == '1') {

                    if (  0<=$data["progress_pedidos"] && $data["progress_pedidos"] < 34)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                      <div class="rounded" role="progressbar" style="background: #FFD4D4;width: ' . $data["progress_pedidos"] . '%" ></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                  </div>
                              </div>
                            </div>
                            '.$sub_html;
                    }
                    else if (34<=$data["progress_pedidos"] && $data["progress_pedidos"] < 37)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important;">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (37<=$data["progress_pedidos"] && $data["progress_pedidos"] < 55)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: rgba(220,53,69,1) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;

                    }
                    else if (55<=$data["progress_pedidos"] && $data["progress_pedidos"] < 60)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (60<=$data["progress_pedidos"] && $data["progress_pedidos"] < 75)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (75<=$data["progress_pedidos"] && $data["progress_pedidos"] < 85)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (85<=$data["progress_pedidos"] && $data["progress_pedidos"] < 90)
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background:rgba(3,175,3,1) ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else if (90<=$data["progress_pedidos"])
                    {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . ' <p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }else{
                        $html .=' el progreso de pedidos '.$data["progress_pedidos"];
                    }

                } /*META-2*/
                else if ($data["meta_new"] == '2') {
                    if ($data["progress_pedidos"] <= 100) {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="color: #d9686!important"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                    else {
                        $html .= '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded height-bar-progress" style="height: 25px !important">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important;font-size: 18px;color:blue;">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta_2"] . '<p class="text-red d-inline format-size" style="font-size: 18px; color: #d9686!important;font-weight: bold;"> ' . $diferencia_mostrar . '</p></span>
                                        </div>
                                    </div>
                                  </div>
                                  '.$sub_html;
                    }
                }

                $html .= '  </td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }
        if ($request->ii == 37)
        {
            $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #ade0db; color: #0a0302">';
            $html .= '<tbody>
              <tr class="responsive-table">
                  <th class="col-lg-4 col-md-12 col-sm-12">';

            $html .= '<span class="px-4 pt-1 pb-1 ' . (($object_totales['pedidos_dia'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    TOTAL DE PEDIDOS DEL DIA: ' . $object_totales['pedidos_dia'] . ' </span>';

            $html .= '
                  </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded h-40 h-60-res height-bar-progress" style="height: 25px !important;">';

            $round=$object_totales['progress_pagos'];

            if(0<$round && $round<=40)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(40<$round && $round<=50)
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar h-60-res" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos'] - 40) . '%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="' . ($object_totales['progress_pagos'] - 40) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(50<$round && $round<=70)
            {
                $html .= '<div class="progress-bar bg-warning" role="progressbar"
                 style="width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }
            else if(70<$round && $round<=80)
            {
                $html .= '<div class="progress-bar bg-warning rounded  h-60-res height-bar-progress" role="progressbar"
                         style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: ' . ($object_totales['progress_pagos'] - 70) . '%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="' . ($object_totales['progress_pagos'] - 70) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }
            else if(80<$round && $round<=100)
            {
                $html .= '<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                 style="height: 25px !important;width: ' . $object_totales['progress_pagos'] . '%;background: #03af03;"
                 aria-valuenow="' . $object_totales['progress_pagos'] . '"
                 aria-valuemin="0" aria-valuemax="100"></div>';
            }
            else
            {
                $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                 style="height: 25px !important;width: ' . ($object_totales['progress_pagos']) . '%"
                 aria-valuenow="' . ($object_totales['progress_pagos']) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
            }

            $html .= '</div>
    <div class="position-absolute w-100 text-center rounded height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 25px !important;font-size: 12px;">
<span style="font-weight: lighter"> <b class="bold-size" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - ' . Carbon::parse($date_pagos)->monthName . ' :  ' . $object_totales['progress_pagos'] . '%</b> - ' . $object_totales['total_pagado'] . '/' . $object_totales['total_pedido_mespasado'] . '</span></div>';

            $html .= ' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
            $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 25px !important;">';

            //40 50 70 80 100 <

            $round=$object_totales['progress_pedidos'];

            if ($object_totales['meta'] == 0)
            {

            }
            else if ($object_totales['meta_new'] == 1)
            {
                if(0<$round && $round<=40)
                {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
                else if(40<$round && $round<=50)
                {
                    $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round-40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round-40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                }
                else if(50<$round && $round<=70)
                {
                    $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                }
                else if(70<$round && $round<=80)
                {
                    $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round-70) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round-70) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                }
                else if(80<$round && $round<=100)
                {
                    $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                }
                else
                {
                    $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                }
            }
            else if ($object_totales['meta_new'] == 2)
            {
                $html .= '<div class="progress-bar bg-primary" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
            }

            if ($object_totales['meta'] == 0) {
                $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . round(0 * 100, 2) . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta'] . '</span>
    </div>';
            } else {

                if ($object_totales['meta_new'] == 1)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">  TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }else if ($object_totales['meta_new'] == 2)
                {
                    $object_totales['progress_pedidos']=round(($object_totales['total_pedido']/$object_totales['meta_combinar'])*100,2);
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total" style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b class="bold-size-total" style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">   TOTAL PEDIDOS -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $object_totales['progress_pedidos'] . '%</b> - ' . $object_totales['total_pedido'] . '/' . $object_totales['meta_combinar'] . '</span>    </div>';
                }

            }

            $html .= '</th>
              </tr>
              </tbody>';
            $html .= '</table>';

        }

        return $html;
    }

    public function viewMetaTableRecurrenteActivo(Request $request)
    {
        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        } else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->count();
        } else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->count();
        } else if (auth()->user()->rol == User::ROL_FORMACION) {
            $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta', '<>', '1')->count();
        } else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            if($request->ii==13)
            {
                $asesores = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->get();
                $total_asesor = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->count();
            }else{
                $asesores = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->get();
                $total_asesor = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->count();
            }
        } else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            if($request->ii==13)
            {
                $asesores = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->get();
                $total_asesor = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->count();
            }else{
                $asesores = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->get();
                $total_asesor = User::query()->activo()->rolAsesor()
                    //->where('excluir_meta', '<>', '1')
                    ->whereNotIn('clave_pedidos',['17','18','19','21'])
                    ->when($encargado != null, function ($query) use ($encargado) {
                        return $query->where('supervisor', '=', $encargado);
                    })->count();
            }
        }

        $supervisores_array = User::query()->activo()->rolSupervisor()->get();
        $count_asesor = [];
        foreach ($supervisores_array as $supervisor) {
            $count_asesor[$supervisor->id] =
                ['pedidos_totales' => 0,
                    'total_pedido_mespasado' => 0,
                    'meta_quincena' => 0,
                    'meta_intermedia' => 0,
                    'meta_new' => 0,
                    'meta' => 0,
                    'meta_2' => 0,
                    'total_pagado' => 0,
                    'progress_pagos' => 0,
                    'progress_pedidos' => 0,
                    'total_pedido' => 0,
                    'pedidos_dia' => 0,
                    'all_situacion_activo' => 0,
                    'all_situacion_recurrente' => 0,
                ];
        }

        $clientes_situacion_activo_mayor=0;
        foreach ($asesores as $asesori)
        {
            $clientes_situacion_activo_mayor_ = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesori->clave_pedidos)
                ->where('clientes.situacion', '=', 'ACTIVO')
                ->activo()
                ->count();
            if($clientes_situacion_activo_mayor_>=$clientes_situacion_activo_mayor_)
            {
                $clientes_situacion_activo_mayor=$clientes_situacion_activo_mayor_;
            }
        }


        foreach ($asesores as $asesor) {
            /*if (!$asesor->identificador == '01') continue;*/
            if (in_array(auth()->user()->rol, [User::ROL_FORMACION, User::ROL_ADMIN, User::ROL_PRESENTACION, User::ROL_ASESOR, User::ROL_LLAMADAS, User::ROL_JEFE_LLAMADAS])) {
            } else {
                if (auth()->user()->rol != User::ROL_ADMIN) {
                    if (auth()->user()->rol != User::ROL_ENCARGADO) {
                        if (auth()->user()->id != $asesor->id) {
                            continue;
                        }
                    } else {
                        if (auth()->user()->id != $asesor->supervisor) {
                            continue;
                        }
                    }
                }
            }

            /*CONSULTAS PARA MOSTRAR INFO EN TABLA*/
            $date_pagos = Carbon::parse(now())->startOfMonth()->subMonth();
            $fechametames = Carbon::now()->clone();

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }


            $asesor_pedido_dia = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.clave_pedidos', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->whereDate('pedidos.created_at', $fechametames)
                ->where('pendiente_anulacion', '<>', '1')->count();

            $meta_calculo_row = Meta::where('rol', User::ROL_ASESOR)
                ->where('identificador', $asesor->clave_pedidos)
                ->where('anio', $fechametames->format('Y'))
                ->where('mes', $fechametames->format('m'))->first();


            $metatotal_quincena = (float)$meta_calculo_row->meta_quincena;
            $metatotal_intermedia = (float)$meta_calculo_row->meta_intermedia;
            $metatotal_1 = (float)$meta_calculo_row->meta_pedido;
            $metatotal_2 = (float)$meta_calculo_row->meta_pedido_2;

            //$asesorid = User::where('rol', User::ROL_ASESOR)->where('id', $asesor->id)->pluck('id');

            if (!request()->has("fechametames")) {
                $fechametames = Carbon::now()->clone();
                $date_pagos = Carbon::parse(now())->clone()->startOfMonth()->subMonth();
            } else {
                $fechametames = Carbon::parse($request->fechametames)->clone();
                $date_pagos = Carbon::parse($request->fechametames)->clone()->startOfMonth()->subMonth();
            }

            $total_pedido = Pedido::query()->where('user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$fechametames->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()])
                ->count();

            $total_pagado = Pedido::query()
                ->join("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->where('pedidos.pago', '1')
                ->where('pedidos.pagado', '2')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $fechametames->clone()->endOfDay())
                ->where('pago_pedidos.estado', 1)
                ->where('pago_pedidos.pagado', 2)
                ->count();

            $total_pedido_mespasado = Pedido::query()
                ->where('pedidos.user_clavepedido', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()])
                ->count();

            $supervisor = User::where('rol', User::ROL_ASESOR)->where('clave_pedidos', $asesor->clave_pedidos)->activo()->first()->supervisor;
            $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('pedidos.identificador', $asesor->clave_pedidos)
                ->where('pedidos.codigo', 'not like', "%-C%")->activo()
                ->where('pendiente_anulacion', '<>', '1')
                ->whereDate('pedidos.created_at', $fechametames)->count();

            $periodo_antes = Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m');
            $periodo_actual = Carbon::now()->clone()->startOfMonth()->format('Y-m');

            /*$clientes_situacion_activo = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_id', $asesor->id)
                ->where('clientes.situacion','=','ACTIVO')
                ->activo()
                ->count();*/

            /*$clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_id', $asesor->id)
                ->where('clientes.situacion','=','RECURRENTE')
                ->activo()
                ->count();*/

            $clientes_situacion_activo = SituacionClientes::leftJoin('situacion_clientes as a', 'a.cliente_id', 'situacion_clientes.cliente_id')
                ->join('clientes as c','c.id','situacion_clientes.cliente_id')
                ->join('users as u','u.id','c.user_id')
                ->Where([
                    ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                    ['a.situacion', '=', 'LEVANTADO'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->orWhere([
                    ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                    ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->orWhere([
                    ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                    ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->orWhere([
                    ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                    ['a.situacion', '=', 'NUEVO'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->count();



            $clientes_situacion_recurrente = SituacionClientes::leftJoin('situacion_clientes as a', 'a.cliente_id', 'situacion_clientes.cliente_id')
                ->join('clientes as c','c.id','situacion_clientes.cliente_id')
                ->join('users as u','u.id','c.user_id')
                ->Where([
                    ['situacion_clientes.situacion', '=', 'CAIDO'],
                    ['a.situacion', '=', 'LEVANTADO'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->orWhere([
                    ['situacion_clientes.situacion', '=', 'CAIDO'],
                    ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->orWhere([
                    ['situacion_clientes.situacion', '=', 'CAIDO'],
                    ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->orWhere([
                    ['situacion_clientes.situacion', '=', 'CAIDO'],
                    ['a.situacion', '=', 'NUEVO'],
                    ['situacion_clientes.periodo', '=', $periodo_actual],
                    ['a.periodo', '=', $periodo_antes],
                    ['c.user_clavepedido', $asesor->clave_pedidos],
                    ['c.estado', '=', '1'],
                    ['c.tipo', '=', '1']
                ])
                ->count();

            /*Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
            ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
            ->where('cv.periodo',Carbon::now()->clone()->subMonth()->format('Y-m'))
            ->where('clientes.user_id', $asesor->id)
            ->where('clientes.situacion','=','RECURRENTE')
            ->activo()
            ->count();*/

            //recurrente mes pasado
            //$clientes_situacion_recurrente = Clientes::query()
            //   ->join('users as u','u.id','clientes.user_id')
            // ->where('u.id', $asesor->id)
            //->whereIn('situacion_clientes.situacion',['RECUPERADO ABANDONO','RECUPERADO RECIENTE','NUEVO','ACTIVO'])
            //->where('situacion_clientes.periodo',Carbon::now()->clone()->subMonth()->format('Y-m'))
            //->where('clientes.tipo','=','1')->where('clientes.estado','=','1')
            //->where( DB::raw(" (select count(p.id) from pedidos p where p.cliente_id=clientes.id and cast(p.created_at as date) between ".Carbon::now()->firstOfMonth()->subMonth()->format('Y-m-d')." and ".Carbon::now()->endOfMonth()->subMonth()->format('Y-m-d')." and p.estado='1'
            //   and p.codigo not like '%-C%' and p.pendiente_anulacion <>'1')'"),'>','0')
            //->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "pedidos_dia" => $asesor_pedido_dia,
                "name" => $asesor->name,
                "total_pedido" => $total_pedido,
                "total_pedido_mespasado" => $total_pedido_mespasado,
                "total_pagado" => $total_pagado,
                "meta_quincena" => $metatotal_quincena,
                "meta_intermedia" => $metatotal_intermedia,
                "meta_new" => 0,
                "meta" => $metatotal_1,
                "meta_2" => $metatotal_2,
                "pedidos_totales" => $pedidos_totales,
                "clientes_situacion_activo" => $clientes_situacion_activo,
                "clientes_situacion_recurrente" => $clientes_situacion_recurrente,
                "supervisor" => $supervisor,
            ];

            if (array_key_exists($encargado_asesor, $count_asesor)) {
                if ($encargado_asesor == 46) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['meta_new'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else if ($encargado_asesor == 24) {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales + $count_asesor[$encargado_asesor]['pedidos_totales'];
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = $clientes_situacion_recurrente + $count_asesor[$encargado_asesor]['all_situacion_recurrente'];
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = $clientes_situacion_activo + $count_asesor[$encargado_asesor]['all_situacion_activo'];
                    $count_asesor[$encargado_asesor]['meta_new'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];
                } else {
                    $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_recurrente'] = 0;
                    $count_asesor[$encargado_asesor]['all_situacion_activo'] = 0;
                    $count_asesor[$encargado_asesor]['meta_new'] = 0;
                    $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado + $count_asesor[$encargado_asesor]['total_pagado'];
                    $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado + $count_asesor[$encargado_asesor]['total_pedido_mespasado'];
                    $count_asesor[$encargado_asesor]['meta_quincena'] = $metatotal_quincena + $count_asesor[$encargado_asesor]['meta_quincena'];
                    $count_asesor[$encargado_asesor]['meta_intermedia'] = $metatotal_intermedia + $count_asesor[$encargado_asesor]['meta_intermedia'];
                    $count_asesor[$encargado_asesor]['meta'] = $metatotal_1 + $count_asesor[$encargado_asesor]['meta'];
                    $count_asesor[$encargado_asesor]['meta_2'] = $metatotal_2 + $count_asesor[$encargado_asesor]['meta_2'];
                    $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido + $count_asesor[$encargado_asesor]['total_pedido'];
                    $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia + $count_asesor[$encargado_asesor]['pedidos_dia'];

                }
            }

            if ($asesor->excluir_meta) {
                if ($total_pedido_mespasado > 0) {
                    $p_pagos = round(($total_pagado / $total_pedido_mespasado) * 100, 2);
                } else {
                    $p_pagos = 0;
                }

                if ($metatotal_quincena > 0) {
                    $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                } else {
                    $p_quincena = 0;
                }

                if ($metatotal_intermedia > 0) {
                    $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                }

                if ($metatotal_1 > 0) {
                    $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }

                if ($metatotal_2 > 0) {
                    $p_pedidos_2 = round(($total_pedido / $metatotal_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }

                /*-----------------------*/
                /*if ($total_pedido>=0 && $total_pedido < $metatotal_quincena) {
                    if ($metatotal_quincena > 0) {
                        $p_quincena = round(($total_pedido / $metatotal_quincena) * 100, 2);
                    } else {
                        $p_quincena = 0;
                        $item['meta_new'] = 0;
                        $item['progress_pedidos'] = $p_quincena;
                    }
                }
                else *//*if ($total_pedido>=$metatotal_quincena && $total_pedido < $metatotal_intermedia) {
                    if ($metatotal_intermedia > 0) {
                        $p_intermedia = round(($total_pedido / $metatotal_intermedia) * 100, 2);
                    } else {
                        $p_intermedia = 0;
                        $item['meta_new'] = 0.5;
                        $item['progress_pedidos'] = $p_intermedia;
                    }
                }
                else */
                if ($total_pedido>=0 && $total_pedido < $metatotal_1) {
                    if ($metatotal_1 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_1) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 1;
                    $item['progress_pedidos'] = $p_pedidos;
                    /*meta 2*/
                }
                else if ($total_pedido>=$metatotal_1) {
                    if ($metatotal_2 > 0) {
                        $p_pedidos = round(($total_pedido / $metatotal_2) * 100, 2);
                    } else {
                        $p_pedidos = 0;
                    }
                    $item['meta_new'] = 2;
                    $item['progress_pedidos'] = $p_pedidos;
                }
                /*-----------------------*/
                $item['progress_pagos'] = $p_pagos;
                $item['progress_pedidos'] = $p_pedidos;
                $item['meta_quincena'] = $p_quincena;
                $item['meta_intermedia'] = $p_intermedia;
                $item['meta'] = $p_pedidos;
                $item['meta_2'] = $p_pedidos_2;

            } else {
                $progressData[] = $item;
            }
        }

        $newData = [];
        $union = collect($progressData)->groupBy('identificador');
        //dd($union);
        foreach ($union as $identificador => $items) {
            foreach ($items as $item) {
                if (!isset($newData[$identificador])) {
                    $newData[$identificador] = $item;
                } else {
                    $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
                    $newData[$identificador]['total_pedido_mespasado'] += data_get($item, 'total_pedido_mespasado');
                    $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
                    $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
                    $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');
                    $newData[$identificador]['meta_new'] += data_get($item, 'meta_new');//0 quincena //0.5 intermedia //1 meta1//2 meta2
                    $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');//todo el mes
                    $newData[$identificador]['clientes_situacion_recurrente'] += data_get($item, 'clientes_situacion_recurrente');//todo el mes
                    $newData[$identificador]['clientes_situacion_activo'] += data_get($item, 'clientes_situacion_activo');//todo el mes
                    $newData[$identificador]['meta_quincena'] += data_get($item, 'meta_quincena');
                    $newData[$identificador]['meta_intermedia'] += data_get($item, 'meta_intermedia');
                    $newData[$identificador]['meta'] += data_get($item, 'meta');
                    $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
                }
            }
            $newData[$identificador]['name'] = collect($items)->map(function ($item) {
                return explode(" ", data_get($item, 'name'))[0];
            })->first();
        }
        $progressData = collect($newData)->values()->map(function ($item) {
            $all = data_get($item, 'total_pedido');
            $all_mespasado = data_get($item, 'total_pedido_mespasado');
            $pay = data_get($item, 'total_pagado');
            $allmeta__quincena = data_get($item, 'meta_quincena');//15
            $allmeta_intermedia = data_get($item, 'meta_intermedia');//in
            $allmeta = data_get($item, 'meta');//meta 1
            $allmeta_2 = data_get($item, 'meta_2');//meta 2
            $pedidos_dia = data_get($item, 'pedidos_dia');//pedidos diario
            $pedidos_totales = data_get($item, 'pedidos_totales');//pedidos de todo el mes
            $clientes_situacion_recurrente = data_get($item, 'clientes_situacion_recurrente');//pedidos de todo el mes
            $clientes_situacion_activo = data_get($item, 'clientes_situacion_activo');//pedidos de todo el mes
            $supervisor = data_get($item, 'supervisor');
            $meta_new = data_get($item, 'meta_new');

            if ($all_mespasado == 0) {
                $p_pagos = 0;
            } else {
                if ($pay > 0) {
                    $p_pagos = round(($pay / $all_mespasado) * 100, 2);
                } else {
                    $p_pagos = 0;
                }
            }

            /*meta quincena = 0*/
            /*if ($all>=0 && $all < $allmeta__quincena) {
                //meta quincena
                if ($allmeta__quincena > 0) {
                    $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                } else {
                    $p_quincena = 0;
                }
                $meta_new = 0;
                $item['progress_pedidos'] = $p_quincena;
            } else *//*if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                if ($allmeta_intermedia > 0) {
                    $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                }
                $meta_new = 0.5;
                $item['progress_pedidos'] = $p_intermedia;
            }else*/ if ($all>=0  && $all < $allmeta) {
                if ($allmeta > 0) {
                    $p_pedidos = round(($all / $allmeta) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $meta_new = 1;
                $item['progress_pedidos'] = $p_pedidos;
            } else if($all>=$allmeta){
                if ($allmeta_2 > 0) {
                    $p_pedidos_2 = round(($all / $allmeta_2) * 100, 2);
                } else {
                    $p_pedidos_2 = 0;
                }
                $meta_new = 2;
                $item['progress_pedidos'] = $p_pedidos_2;
            }

            $item['progress_pagos'] = $p_pagos;
            $item['total_pedido'] = $all;
            $item['total_pedido_pasado'] = $all_mespasado;
            $item['pedidos_dia'] = $pedidos_dia;
            $item['pedidos_totales'] = $pedidos_totales;
            $item['all_situacion_recurrente'] = $clientes_situacion_recurrente;
            $item['all_situacion_activo'] = $clientes_situacion_activo;
            $item['meta_new'] = $meta_new;
            if($allmeta_2==0)
            {
                $item['porcentaje_general']=0;
            }else{
                $item['porcentaje_general']=($all/$allmeta_2);
            }

            return $item;
        })->sortBy('identificador', SORT_NUMERIC, false);
        //->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

        if ($request->ii == 8) {
            if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();
        }
        else if ($request->ii == 9) {
            if ($total_asesor % 2 == 0) {
                $skip = intval($total_asesor / 2);
                $take = intval($total_asesor / 2);
            } else {
                $skip = intval($total_asesor / 2) + 1;
                $take = intval($total_asesor / 2);
            }
            $progressData->splice($skip, $take)->all();
        }

        //aqui la division de  1  o 2
        $all = collect($progressData)->pluck('total_pedido')->sum();
        $all_situacion_recurrente = collect($progressData)->pluck('all_situacion_recurrente')->sum();
        $all_situacion_activo = collect($progressData)->pluck('all_situacion_activo')->sum();
        $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
        $pay = collect($progressData)->pluck('total_pagado')->sum();
        $meta_quincena = collect($progressData)->pluck('meta_quincena')->sum();
        $meta_intermedia = collect($progressData)->pluck('meta_intermedia')->sum();
        $meta = collect($progressData)->pluck('meta')->sum();
        $meta_2 = collect($progressData)->pluck('meta_2')->sum();
        $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
        $supervisor = collect($progressData)->pluck('supervisor')->sum();

        //verificar totales
        if ($meta > 0) {
            $p_pedidos = round(($all / $meta) * 100, 2);
        } else {
            $p_pedidos = 0;
        }
        if ($all_mespasado == 0) {
            $p_pagos = 0;
        } else {
            if ($pay > 0) {
                $p_pagos = round(($pay / $all_mespasado) * 100, 2);
            } else {
                $p_pagos = 0;
            }
        }

        $object_totales = [
            "progress_pedidos" => $p_pedidos,
            "progress_pagos" => $p_pagos,
            "total_pedido" => $all,
            "all_situacion_recurrente" => $all_situacion_recurrente,
            "all_situacion_activo" => $all_situacion_activo,
            "total_pedido_mespasado" => $all_mespasado,
            "total_pagado" => $pay,
            "meta" => $meta,
            "meta_2" => $meta_2,
            "pedidos_dia" => $pedidos_dia,
            "supervisor" => $supervisor,
        ];

        $html = '';

        /*TOTAL*/
        if ($request->ii == 8 || $request->ii == 9) {

            $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%">Asesor</th>
                    <th width="11%">Id</th>

                    <th width="33%">LEVANTADOS/(LEVANTADOS+CAIDOS) (%)  ' . Carbon::parse($date_pagos)->monthName . ' </th>
                </tr>
                </thead>
                <tbody>';
            foreach ($progressData as $data) {
                $html .= '<tr>
             <td class="name-size">' . $data["name"] . '</td>
             <td>' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             ';
                $html .= '<td>';

                /*inicio pagos*/

                //$html.='<br> '.$data["progress_pagos"].' : '.$data["total_pagado"].' - '.$data["total_pedido_mespasado"].' <br>';
                //continue;

                /*if($data["all_situacion"]==0)
                {
                    $division=0;
                }else{
                    $division=$data["all_situacion_activo"] / $data["all_situacion"];
                }*/


                //$data["all_situacion_activo"];
                if($data["all_situacion_recurrente"]==0)
                {
                    $porcentaje=0.00;
                    $diferencia=0;
                }else{
                    $porcentaje=round(($data["all_situacion_activo"] / ($data["all_situacion_recurrente"]+$data["all_situacion_activo"]) )*100,2);
                    $diferencia= ($data["all_situacion_activo"]+$data["all_situacion_recurrente"])-$data["all_situacion_activo"];
                }


                {
                    $html .= '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded height-bar-progress" style="height: 30px !important">
                                      <div class="rounded" role="progressbar" style="background: #ff7d7d !important; width: '.$porcentaje.'%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 16px;">
                                      <span style="font-weight: bold;"> <b class="bold-size" style="color:#001253;">   ' . $porcentaje . '% - <span style="font-size:11px;color:grey;">'.$data["all_situacion_activo"].'/ LEVANTADOS.'.($data["all_situacion_activo"]).' + CAIDOS'.($data["all_situacion_recurrente"]).'</span> </b>  <p class="text-red d-inline format-size" style="color: #d9686!important">'.$diferencia.' </p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
                }
                /*fin pagos*/

                $html .= '</td>
      </tr> ';
            }

            $html .= '</tbody>';

            $html .= '</table>';
        }
        else if ($request->ii == 13)
        {
            $object_totales['all_situacion_activo']=0;
            $object_totales['all_situacion_recurrente']=0;
            foreach ($progressData as $data)
            {
                if($data["all_situacion_recurrente"]>0)
                {
                    $object_totales["all_situacion_recurrente"]+=$data["all_situacion_recurrente"];
                }
                if($data["all_situacion_activo"]>0)
                {
                    $object_totales["all_situacion_activo"]+=$data["all_situacion_activo"];
                }
            }

                $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #e4dbc6; color: #0a0302">';
                $html .= '<tbody>
                    <tr class="responsive-table">
                    <th class="col-lg-4 col-md-12 col-sm-12">';

                $html .= '<span class="px-4 pt-1 pb-1 ' . (($object_totales['all_situacion_activo'] == 0) ? 'bg-red' : 'bg-white') . ' text-center justify-content-center w-100 rounded font-weight-bold height-bar-progress"
                    style="height: 30px !important;display:flex; align-items: center; color: black !important;">
                    TOTAL DE LEVANTADOS: ' . $object_totales['all_situacion_activo'] . ' </span>';

                $html.='</th>
                        <th class="col-lg-4 col-md-12 col-sm-12">';
                $html .= '<div class="position-relative rounded">
                <div class="progress rounded height-bar-progress" style="height: 30px !important;">';

                if($object_totales['all_situacion_activo']>0)
                {
                    $diferencia= ($object_totales['all_situacion_activo']+$object_totales['all_situacion_recurrente']) - $object_totales['all_situacion_activo'];
                    $round=round( ( ($object_totales['all_situacion_activo'])/ ($object_totales['all_situacion_recurrente']+$object_totales['all_situacion_activo'] ) )*100 ,2);
                }else{
                    $round=0.00;
                    $diferencia=0;
                    //cuando pedidos es 0
                }

                if ($object_totales['all_situacion_recurrente'] == 0) {
                    $html .= '<div class="progress-bar bg-danger" role="progressbar"
                 style="width: ' . 0 . '%"
                 aria-valuenow="' . (round(0, 2)) . '"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                } else {

                    if(0<$round && $round<=40)
                    {
                        $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . $round . '%"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                    }
                    else if(40<$round && $round<=50)
                    {
                        $html .= '<div class="progress-bar bg-danger h-60-res height-bar-progress" role="progressbar"
                           style="height: 30px !important;width: ' . $round . '%"
                           aria-valuenow="70"
                           aria-valuemin
                           aria-valuemax="100"></div>
                          <div class="progress-bar h-60-res" role="progressbar"
                               style="width: ' . ($round - 40) . '%;
                           background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                               aria-valuenow="' . ($round - 40) . '"
                               aria-valuemin="0"
                               aria-valuemax="100"></div>';
                    }
                    else if(50<$round && $round<=70)
                    {
                        $html .= '<div class="progress-bar bg-warning height-bar-progress" role="progressbar"
                 style="height: 30px !important;width: ' . ($round) . '%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
                    }
                    else if(70<$round && $round<=80)
                    {
                        $html .= '<div class="progress-bar bg-warning rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%"
                             aria-valuenow="70"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                        <div class="progress-bar rounded height-bar-progress" role="progressbar"
                             style="height: 30px !important;width: ' . ($round) . '%;
                         background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                             aria-valuenow="' . ($round) . '"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>';
                    }
                    else if(80<$round && $round<=100)
                    {
                        $html .= '<div class="progress-bar bg-success rounded height-bar-progress" role="progressbar"
                         style="height: 30px !important;width: ' . $round . '%;background: #03af03;"
                         aria-valuenow="' . $round . '"
                         aria-valuemin="0" aria-valuemax="100"></div>';
                    }
                    else
                    {
                        $html .= '<div class="progress-bar bg-danger" role="progressbar"
                         style="width: ' . ($round) . '%"
                         aria-valuenow="' . ($round) . '"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
                    }

                }

                if ($object_totales['all_situacion_recurrente'] == 0) {
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total"
                style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">
             TOTAL DEJARON DE PEDIR -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $round . '%</b> - ' .
                        $object_totales['all_situacion_activo'] . '/ ( Levantados. ' . $object_totales['all_situacion_activo'] . ' + Caidos.'.$object_totales['all_situacion_recurrente'].'</span>
    </div>';
                } else {
                    $html .= '</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res height-bar-progress top-progress-bar-total"
                style="top: 3px !important;height: 30px !important;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;">
             TOTAL DEJARON DE PEDIR -  ' . Carbon::parse($fechametames)->monthName . ' : ' . $round . '%</b> - '
                        . $object_totales['all_situacion_activo'] . '/ (Levantados ' . $object_totales['all_situacion_activo'] . ' + Caidos.'.$object_totales['all_situacion_recurrente'].'</span>
    </div>';
                }

                $html .= '</th>
              </tr>
              </tbody>';
                $html .= '</table>';

        }
        return $html;
    }

    public static function applyFilterCustom($query, CarbonInterface $date = null, $column = 'created_at')
    {
        if ($date == null) {
            $date = now();
        }
        return $query->whereBetween($column, [
            $date->clone()->startOfMonth(),
            $date->clone()->endOfMonth()->endOfDay()
        ]);
    }

    /*public static function applyFilterCustomMetas($query, CarbonInterface $date_pagos = null, $column = 'created_at', $tipo = 1, CarbonInterface $fechametames = null)
    {
        if ($date_pagos == null) {
            $date_pagos = now();
        }
        if ($tipo == 1) {
            return $query->whereBetween($column, [$date_pagos->clone()->startOfMonth()->startOfDay(), $fechametames->clone()->endOfDay()]);
        } else {
            return $query->whereBetween($column, [$date_pagos->clone()->startOfMonth()->startOfDay(), $date_pagos->clone()->endOfMonth()->endOfDay()]);
        }
    }*/

    /*    public static  function sparkline(Request $request){

            $fecha_actual = Carbon::now()->endOfDay()->format('d'); // dia actual
            $primer_dia = Carbon::now()->startOfMonth()->startOfDay()->format('d'); //primer dia del mes
            $diff = abs(diff($fecha_actual, $primer_dia));
            $arr = [];

            for ($i=1; $i<=$diff; $i++){
                $arr[] = $i;
            }

            dd($arr);
        }*/


}
