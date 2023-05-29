<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Meta;
use App\Models\Pago;
use App\Models\Publicidad;
use App\Models\SituacionClientes;
use App\Models\User;
use App\Models\Pedido;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class PdfController extends Controller
{
    public function index()
    {
        $users = User::where('estado', '1')->pluck('name', 'id');

        return view('reportes.index', compact('users'));
    }

    public function MisAsesores()
    {
        $users = User::where('estado', '1')
            ->where('supervisor', Auth::user()->id)
            ->pluck('name', 'id');

        return view('reportes.misasesores', compact('users'));
    }

    public function Operaciones()
    {
        $users = User::where('estado', '1')->pluck('name', 'id');

        return view('reportes.operaciones', compact('users'));
    }

    public function Analisis()
    {
        $users = User::where('estado', '1')->pluck('name', 'id');

        $anios = [
            "2020" => '2020 - 2021',
            "2021" => '2021 - 2022',
            "2022" => '2022 - 2023',
            "2023" => '2023 - 2024',
            "2024" => '2024 - 2025',
            "2025" => '2025 - 2026',
            "2026" => '2026 - 2027',
            "2027" => '2027 - 2028',
            "2028" => '2028 - 2029',
            "2029" => '2029 - 2030',
            "2030" => '2030 - 2031',
            "2031" => '2031 - 2032',
        ];

        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $mes_month = Carbon::now()->startOfMonth()->subMonth(1)->format('Y_m');
        $mes_anio = Carbon::now()->startOfMonth()->subMonth()->format('Y');
        $mes_mes = Carbon::now()->startOfMonth()->subMonth()->format('m');

        $_pedidos_mes_pasado = User::select([
            'users.id', 'users.name', 'users.email'
            , DB::raw(" (select count( c.id) from clientes c inner join users a  on c.user_id=a.id where a.rol='Asesor' and a.llamada=users.id and c.situacion='RECUPERADO RECIENTE' ) recuperado_reciente")
            , DB::raw(" (select count( c.id) from clientes c inner join users a  on c.user_id=a.id where a.rol='Asesor' and a.llamada=users.id and c.situacion='RECUPERADO ABANDONO' ) recuperado_abandono")
            , DB::raw(" (select count( c.id) from clientes c inner join users a  on c.user_id=a.id where a.rol='Asesor' and a.llamada=users.id and c.situacion='NUEVO' ) nuevo")
        ])
            ->whereIn('users.rol', ['Llamadas']);


        $_pedidos_mes_pasado = $_pedidos_mes_pasado->get();

        return view('reportes.analisis', compact('users', '_pedidos_mes_pasado', 'mes_month', 'mes_anio', 'mes_mes', 'anios', 'dateM', 'dateY'));
    }

    public function AnalisisRendimiento(Request $request)
    {
        $total_asesor = User::query()->activo()->rolAsesor()->count();
        if (auth()->user()->rol == User::ROL_ASESOR) {
            $asesores = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->get();
            $total_asesor = User::query()->activo()->rolAsesor()->where('clave_pedidos', auth()->user()->clave_pedidos)->where('excluir_meta', '<>', '1')->count();
        }
        else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_LLAMADAS) {
            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->where('excluir_meta', '<>', '1')
                ->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->where('excluir_meta', '<>', '1')
                ->count();
        }
        else if (auth()->user()->rol == User::ROL_FORMACION) {
            $encargado = null;

            if($request->ii==1)$encargado=24;
            if($request->ii==2)$encargado=46;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->whereIn('clave_pedidos',['01','01.5','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->whereIn('clave_pedidos',['01','01.5','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();

        }
        else if (auth()->user()->rol == User::ROL_PRESENTACION) {
            $encargado = null;

            if($request->ii==1)$encargado=24;
            if($request->ii==2)$encargado=46;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->whereIn('clave_pedidos',['01','01.5','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();
            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->whereIn('clave_pedidos',['01','01.5','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();
        }
        else if (auth()->user()->rol == User::ROL_ADMIN) {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            if($request->ii==1)$encargado=24;
            if($request->ii==2)$encargado=46;

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->whereIn('clave_pedidos',['01','01.5','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                //->whereIn('clave_pedidos',['01','01.5','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->count();

        }
        else{
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }

            $asesores = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })->get();

            $total_asesor = User::query()->activo()->rolAsesor()
                ->whereIn('clave_pedidos',['18','19','20','21','22','23'])
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

        //$fechaInicio=strtotime("25-02-2008");
        $fechaInicio=strtotime(Carbon::now()->startOfMonth()->startOfDay()->format('d-m-Y'));
        //$fechaFin=strtotime("01-04-2008");
        $fechaFin=strtotime(Carbon::now()->startOfDay()->format('d-m-Y'));

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
                , User::ROL_ASISTENTE_PUBLICIDAD
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
                ->where('clientes.congelado','<>',1)
                ->activo()
                ->count();

            $clientes_situacion_recurrente = Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->join('situacion_clientes as cv','cv.cliente_id','clientes.id')
                ->where('cv.periodo',Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m'))
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                ->where('clientes.situacion','=','CAIDO')
                ->where('clientes.congelado','<>',1)
                ->activo()
                ->count();

            $clientes_actuales=Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                //->where('clientes.tipo','=','0')
                ->where('clientes.congelado','<>',1)
                ->activo()
                ->count();

            $encargado_asesor = $asesor->supervisor;

            $item = [
                "identificador" => $asesor->clave_pedidos,
                "code" => "{$asesor->name}",
                "inicio" => Carbon::parse($asesor->created_at)->format('d-m-Y'),
                "chats" => $clientes_actuales,
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



            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                //echo date("d-m-Y", $i)."<br>";

                $clientes_actuales_dia=Cliente::query()->join('users as u', 'u.id', 'clientes.user_id')
                    ->where('clientes.user_clavepedido', $asesor->clave_pedidos)
                    //->where('clientes.tipo','=','0')
                    ->where('clientes.congelado','<>',1)
                    ->whereDate('clientes.created_at', date("Y-m-d", $i))
                    ->activo()
                    ->count();

                $item[date("Y-m-d", $i)]=$clientes_actuales_dia;
            }
            /*echo "<pre>";
            print_r($item);
            echo "</pre>";*/

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
                if ($allmeta__quincena > 0) {
                    $p_quincena = round(($all / $allmeta__quincena) * 100, 2);
                } else {
                    $p_quincena = 0;
                }
                $meta_new = 0;
                $item['progress_pedidos'] = $p_quincena;
            } else if ($all>=$allmeta__quincena  &&  $all < $allmeta_intermedia) {
                if ($allmeta_intermedia > 0) {
                    $p_intermedia = round(($all / $allmeta_intermedia) * 100, 2);
                } else {
                    $p_intermedia = 0;
                }
                $meta_new = 0.5;
                $item['progress_pedidos'] = $p_intermedia;
            }else */if ($all>=0  && $all < $allmeta) {
                if ($allmeta > 0) {
                    $p_pedidos = round(($all / $allmeta) * 100, 2);
                } else {
                    $p_pedidos = 0;
                }
                $meta_new = 1;
                $item['progress_pedidos'] = $p_pedidos;
            }
            else if($all>=$allmeta){
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

            if($meta_new==0)
            {
                $item['meta_combinar']=$item['meta_quincena'];
            }
            else if($meta_new==1)
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

        if ($request->ii == 1) {
            $progressData->all();
            /*if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();*/
        }
        else if ($request->ii == 2) {
            $progressData->all();
            /*if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();*/
        }
        else if ($request->ii == 17) {
            $progressData->all();
            /*if ($total_asesor % 2 == 0) {
                $skip = 0;
                $take = intval($total_asesor / 2);
            } else {
                $skip = 0;
                $take = intval($total_asesor / 2) + 1;
            }
            $progressData->splice($skip, $take)->all();*/
        }
        else if ($request->ii == 37) {
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
        $loschats = collect($progressData)->pluck('chats')->sum();
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
            "progress_pedidos"=>$progress_pedidos,
            "chats"=>$loschats
        ];

        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
        {
            $object_totales[date("Y-m-d", $i)]=collect($progressData)->pluck(date("Y-m-d", $i))->sum();
        }


        $html = '';

        /*TOTAL*/

        if ($request->ii == 1 || $request->ii == 2 || $request->ii == 17 )
        {
            if ($request->ii == 1 )
            {
                $html .= '<div class="row">';
                $html .= '<div class="col-12 text-center b">';

                $html .='<b class="font-16 text-success">PAOLA</b>';

                $html .= '</div>';
                $html .= '</div>';
            }
            else if ($request->ii == 2 )
            {
                $html .= '<div class="row">';
                $html .= '<div class="col-12 text-center">';

                $html .='<b class="font-16 text-success">ALEXANDRA</b>';

                $html .= '</div>';
                $html .= '</div>';
            }
            else if ($request->ii == 17 )
            {
                $html .= '<div class="row">';
                $html .= '<div class="col-12 text-center">';

                $html .='<b class="font-16 text-success">Publicidad - Calendario</b>';

                $html .= '</div>';
                $html .= '</div>';
            }

            $html .= '<div class="row">';
            $html .= '<div class="col-3">';

            $html .='<h5 class="card-title text-uppercase font-weight-bold">Total de cobranzas :</h5>';
            $html .= '<p class="porcentaje_cobranzas_metas" class="card-text font-weight-bold" style="font-size: 25px">'.$object_totales['progress_pagos'].'%</p>';

            $html .= '</div>';

            $html .= '<div class="col-6 d-flex justify-content-center align-items-center">';
            $html .= '<h2 class="card-title text-uppercase h1-change-day text-center font-weight-bold">METAS DEL MES DE '.Carbon::now()->startOfMonth()->translatedFormat('F').' :</h2>';
            $html .= '<buton style="background: none; border: none;" onclick="openFullscreen();">';
            $html .= '<i class="fas fa-expand-arrows-alt ml-3" style="font-size: 20px"></i>';
            $html .= '</button>';
            $html .= '</div>';

            $html .= '<div class="col-3">';

            $html .='<h5 class="card-title text-uppercase font-weight-bold">Total de pedidos :</h5>';
            $html .= '<p class="porcentaje_pedidos_metas" class="card-text font-weight-bold" style="font-size: 25px">'.$object_totales['progress_pedidos'].'%</p>';

            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121; margin-bottom: 3px !important;">';
            $html .= '<thead>
                <tr>
                    <th width="8%">Publicidad</th>
                    <th width="11%">Asesor</th>
                    <th width="6%" style="font-weight: bold;color:blue;">Chats Diarios</th>
                    <th width="6%" style="font-weight: bold;color:blue;">Llamadas Diarias</th>
                    <th width="6%" style="font-weight: bold;color:blue;">Clientes en el dia</th>';

            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
            {
                $html .= '<th width="6%">'.date("d-m", $i).'</th>';
            }

            $html .='</tr>
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
             <td class="font-weight-bold"><span class="d-inline-block">'. $data["name"] . '</span></td>
             <td class="font-weight-bold">' . $data["identificador"] . ' ';

                if ($data["supervisor"] == 46) {
                    $html .= '- A';
                } else {
                    $html .= '- B';
                }
                $html .= '
             </td>
             <td style="font-weight: bold;color:blue;">'.$data["inicio"].'</td>
             <td style="font-weight: bold;color:blue;">'.$data["chats"].'</td>';

                for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
                {
                    $html .= '<td>'. $data[date("Y-m-d", $i)].'</td>';

                }


                $font_size_sub=12;

                $sub_html='<sub class="top-visible" style="display: block !important;">
                                      <span style="background:#FFD4D4  !important;" class="badge font-'.$font_size_sub.'">Qui. . '.$data["meta_quincena"].'</span>
                                      <span class="badge bg-warning font-'.$font_size_sub.'">Int. . '.$data["meta_intermedia"].'</span>
                                      <span class="badge bg-success text-dark font-'.$font_size_sub.'"">Pri. . '.$data["meta"].'</span>
                                      <span class="badge bg-primary text-dark font-'.$font_size_sub.'"">Seg. . '.$data["meta_2"].'</span>
                                  </sub>';
                $sub_html='';

                $html .= '</tr> ';
            }

            $html .= '<tr>';
            $html .= '<td class="text-center font-weight-bold" colspan="3">TOTAL NETO</td>';

            $html .= '<td class="bg-warning font-weight-bold">'.$object_totales['chats'].'</td>';

            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
            {
                $html .= '<td class="bg-warning font-weight-bold">'.$object_totales[date("Y-m-d", $i)].'</td>';
                //$object_totales[date("Y-m-d", $i)]=collect($progressData)->pluck(date("Y-m-d", $i))->sum();
            }

            $html .= '</tr>';

            $html .= '</tbody>';

            $html .= '<tfoot>';

            //72
            $html .= '<tr>';
            $html .= '<th colspan="2">Total Publicidad</th>';
            $html .= '<th></th>';
            $html .= '<th>Chats</th>';
            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
            {
                $count_1 = Publicidad::query()->where('name','TOTAL PUBLICIDAD')->where('cargado',date("Y-m-d", $i))->count();
                if($count_1==1)
                {
                    $count_1=Publicidad::query()->where('name','TOTAL PUBLICIDAD')->where('cargado',date("Y-m-d", $i))->first();
                    $html .= '<th>'.$count_1->total.'</th>';
                }else{
                    $html .= '<th>0</th>';
                }

            }
            $html .= '</tr>';

            //95
            $html .= '<tr>';
            $html .= '<th colspan="2">Total Dante</th>';
            $html .= '<th></th>';
            $html .= '<th>Chats</th>';
            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
            {
                $count_2 = Publicidad::query()->where('name','TOTAL DANTE')->where('cargado',date("Y-m-d", $i))->count();
                if($count_2==1)
                {
                    $count_2=Publicidad::query()->where('name','TOTAL DANTE')->where('cargado',date("Y-m-d", $i))->first();
                    $html .= '<th>'.$count_2->total.'</th>';
                }else{
                    $html .= '<th>0</th>';
                }

            }
            $html .= '</tr>';

            $html .= '</tfoot>';

            $html .= '</table>';
            $html .= '</div>';
        }

        return $html;
    }

    public function SituacionClientes(Request $request)
    {
        $inicio_s = Carbon::now()->clone()->startOfMonth()->format('Y-m-d');
        $inicio_f = Carbon::now()->clone()->endOfMonth()->format('Y-m-d');
        $periodo_antes = Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m');
        $periodo_actual = Carbon::now()->clone()->startOfMonth()->format('Y-m');

        $mes_w = Carbon::now()->clone()->startOfMonth()->format('m');
        $anio_w = Carbon::now()->clone()->startOfMonth()->format('Y');

        $situaciones_clientes = SituacionClientes::leftJoin('situacion_clientes as a', 'a.cliente_id', 'situacion_clientes.cliente_id')
            ->join('clientes as c','c.id','situacion_clientes.cliente_id')
            //->join('users as u','u.id','c.user_id')
            ->where([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['a.situacion', '=', 'ABANDONO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['a.situacion', '=', 'ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['a.situacion', '=', 'NULO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO RECIENTE'],
                ['a.situacion', '=', 'CAIDO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'NUEVO'],
                ['a.situacion', '=', 'BASE FRIA'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],

                ['situacion_clientes.user_clavepedido', '<>', '02'],
                ['situacion_clientes.user_clavepedido', '<>', '03'],
                ['situacion_clientes.user_clavepedido', '<>', '04'],

                ['situacion_clientes.user_clavepedido', '<>', '07'],
                ['situacion_clientes.user_clavepedido', '<>', '08'],
                ['situacion_clientes.user_clavepedido', '<>', '10'],
                ['situacion_clientes.user_clavepedido', '<>', '11'],
                ['situacion_clientes.user_clavepedido', '<>', '13'],
                ['situacion_clientes.user_clavepedido', '<>', '16'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            /*->orWhere([
                ['situacion_clientes.situacion', '=', 'NUEVO'],
                ['a.situacion', '=', 'NULO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                //['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])*/
            /*->orWhere([
                ['situacion_clientes.situacion', '=', 'NUEVO'],
                ['a.situacion', '=', 'BASE FRIA'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '=', '99'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])*/
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '20'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['situacion_clientes.user_clavepedido', '<>', '22'],
                ['situacion_clientes.user_clavepedido', '<>', '23'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1'],
                ['c.congelado', '=', '0']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'ABANDONO RECIENTE'],
                ['a.situacion', '=', 'CAIDO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '99'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->groupBy([
                'situacion_clientes.situacion',
                'situacion_clientes.user_clavepedido'
            ])
            ->orderBy('situacion_clientes.situacion','asc')
            ->select([
                'situacion_clientes.situacion',
                'situacion_clientes.user_clavepedido as user_identificador',
                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                    THEN (select sum(m.meta_quincena_recuperado_abandono) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                   WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.meta_quincena_recuperado_reciente) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                   WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.meta_quincena_nuevo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                   WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.meta_quincena_activo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                   ELSE 0 end) as meta_quincena "),

                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                  THEN (select sum(m.cliente_recuperado_abandono) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.cliente_recuperado_reciente) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.cliente_nuevo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.cliente_activo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    ELSE 0 end) as meta_1 "),

                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                    THEN (select sum(m.cliente_recuperado_abandono_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.cliente_recuperado_reciente_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.cliente_nuevo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.cliente_activo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    ELSE 0 end) as meta_2 "),
                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                    THEN (select sum(m.cliente_recuperado_abandono_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.cliente_recuperado_reciente_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.cliente_nuevo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.cliente_activo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                    ELSE 0 end) as meta_2 "),

                DB::raw('count(situacion_clientes.situacion) as total')
            ])
            ->get();

        /*foreach($situaciones_clientes as $recorer)
        {
            if($recorer->situacion=='CAIDO' || $recorer->situacion=='LEVANTADO')
            echo "<br>".$recorer->user_identificador." -- ".$recorer->situacion."--".$recorer->total."<br>";
        }*/
        //exit;
        $_estados=['RECUPERADO ABANDONO','RECUPERADO RECIENTE','NUEVO','LEVANTADO','CAIDO'];
        $_resultado_grafico=[];

        $metas_llamadas=Meta::where('rol','=','Jefe de llamadas')->where('mes','=',$mes_w)->where('anio','=',$anio_w)->first();

        //inicializacion
        foreach ($_estados as $_estado_)
        {
            $_resultado_grafico[$_estado_]=[
                'label'=>$_estado_,
                'dividendo'=>0,
                'divisor'=>0,
                'restante'=>0,
                'meta_quincena'=>0,
                'meta_1_'=>0,
                'meta_2'=>0,
                'porcentaje'=>0
            ];
            if($_estado_=='RECUPERADO ABANDONO')
            {
                $_resultado_grafico[$_estado_]['meta_quincena']=$metas_llamadas->meta_quincena_recuperado_abandono;
                $_resultado_grafico[$_estado_]['meta_1']=$metas_llamadas->cliente_recuperado_abandono;
                $_resultado_grafico[$_estado_]['meta_2']=$metas_llamadas->cliente_recuperado_abandono_2;
            }
            else if($_estado_=='RECUPERADO RECIENTE')
            {
                $_resultado_grafico[$_estado_]['meta_quincena']=$metas_llamadas->meta_quincena_recuperado_reciente;
                $_resultado_grafico[$_estado_]['meta_1']=$metas_llamadas->cliente_recuperado_reciente;
                $_resultado_grafico[$_estado_]['meta_2']=$metas_llamadas->cliente_recuperado_reciente_2;
            }
            else if($_estado_=='NUEVO')
            {
                $_resultado_grafico[$_estado_]['meta_quincena']=$metas_llamadas->meta_quincena_nuevo;
                $_resultado_grafico[$_estado_]['meta_1']=$metas_llamadas->cliente_nuevo;
                $_resultado_grafico[$_estado_]['meta_2']=$metas_llamadas->cliente_nuevo_2;
            }
        }

        foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            if($situaciones_clientes_->situacion=='LEVANTADO' || $situaciones_clientes_->situacion=='CAIDO')continue;

            if($situaciones_clientes_->situacion=='RECUPERADO ABANDONO')
            {
                $_resultado_grafico[$situaciones_clientes_->situacion]['dividendo']=($_resultado_grafico[$situaciones_clientes_->situacion]['dividendo']+$situaciones_clientes_->total);
            }
            else if($situaciones_clientes_->situacion=='RECUPERADO RECIENTE')
            {
                $_resultado_grafico[$situaciones_clientes_->situacion]['dividendo']=($_resultado_grafico[$situaciones_clientes_->situacion]['dividendo']+$situaciones_clientes_->total);
            }
            else if($situaciones_clientes_->situacion=='NUEVO')
            {
                /*echo "<pre>";
                print_r($situaciones_clientes_);
                echo "</pre>";*/
                if($situaciones_clientes_->user_identificador=='99')
                {

                    $_resultado_grafico['RECUPERADO ABANDONO']['dividendo']=($_resultado_grafico['RECUPERADO ABANDONO']['dividendo']+$situaciones_clientes_->total);
                    /*echo "<pre>";
                    print_r($_resultado_grafico);
                    echo "</pre>";*/
                }
                else{
                    $_resultado_grafico[$situaciones_clientes_->situacion]['dividendo']=($_resultado_grafico[$situaciones_clientes_->situacion]['dividendo']+$situaciones_clientes_->total);
                }
            }
        }

        $activos_cuenta=0;
        $recurrentes_cuenta=0;
        $r_abandono_cuenta=0;
        $r_reciente_cuenta=0;
        $nuevos_cuenta=0;
        $a_reciente_cuenta=0;
        $html = [];
        $html[] = '<h3 class="text-uppercase justify-center text-center">Metas Asesores de Llamadas</h3>';
        $html[] = '<table class="table table-situacion-clientes align-self-center" style="background: #ade0db; color: #0a0302">';

        foreach ($situaciones_clientes as $situacion_cliente_3)
        {
            if($situacion_cliente_3->situacion=='LEVANTADO')
            {
                $activos_cuenta=$situacion_cliente_3->total+$activos_cuenta;
            }
            else if($situacion_cliente_3->situacion=='CAIDO')
            {
                $recurrentes_cuenta=$situacion_cliente_3->total+$recurrentes_cuenta;
            }
            else if($situacion_cliente_3->situacion=='RECUPERADO ABANDONO')
            {
                $r_abandono_cuenta=$situacion_cliente_3->total+$r_abandono_cuenta;
            }
            else if($situacion_cliente_3->situacion=='RECUPERADO RECIENTE')
            {
                $r_reciente_cuenta=$situacion_cliente_3->total+$r_reciente_cuenta;
            }
            else if($situacion_cliente_3->situacion=='ABANDONO RECIENTE')
            {
                $a_reciente_cuenta=$situacion_cliente_3->total+$a_reciente_cuenta;
            }
            else if($situacion_cliente_3->situacion=='NUEVO')
            {
                $nuevos_cuenta=$situacion_cliente_3->total+$nuevos_cuenta;
            }
        }


        /*echo "activo :".$activos_cuenta."<br>";
        echo "recurrente :".$recurrentes_cuenta."<br>";
        echo "abandono :".$r_abandono_cuenta."<br>";
        echo "reciente :".$r_reciente_cuenta."<br>";
        echo "nuevos :".$nuevos_cuenta."<br>";*/

        $suma=intval($activos_cuenta)+intval($recurrentes_cuenta)+intval($r_abandono_cuenta)+intval($r_reciente_cuenta)+intval($nuevos_cuenta);


//dd($activos_cuenta,$recurrentes_cuenta);//14//51//307/1006

        foreach($_resultado_grafico as $_resultado_grafico_k=>$_resultado_grafico_v)
        {
            //var_dump($_resultado_grafico_);

            if($_resultado_grafico_k=='LEVANTADO' || $_resultado_grafico_k=='CAIDO' || $_resultado_grafico_k=='RECUPERADO RECIENTE')
                continue;

            if($_resultado_grafico_k=='NUEVO')
            {
                $_resultado_grafico_v["label"]='BASE FRIA';
            }

            $html[] = '<tr>';
            $html[] = '<td style="width:20%;" class="text-center">';
            $html[] = '<span class="px-4 pt-1 pb-1 bg-info text-center w-20 rounded font-weight-bold"
                                    style="align-items: center;height: 40px !important; color: black !important;">' .
                $_resultado_grafico_v["label"] .
                '</span>';
            $html[] = '</td>';

            $html[] = '<td style="width:80%">';
            $porcentaje = 0;
            $diferenciameta = 0;
            $valor_meta = 0;
            $color_progress = '';
            $color_degradado = 0;
            if ($_resultado_grafico_v["dividendo"] < $_resultado_grafico_v["meta_quincena"])
            {
                //meta quincena
                $porcentaje = round(($_resultado_grafico_v["dividendo"] / ($_resultado_grafico_v["meta_quincena"])  ) * 100, 2);
                $diferenciameta = $_resultado_grafico_v["meta_quincena"] - $_resultado_grafico_v["dividendo"];
                if ($diferenciameta < 0) $diferenciameta = 0;
                $valor_meta = ($_resultado_grafico_v["meta_quincena"]);
                if($porcentaje < 90){
                    $color_progress = '#FFD4D4';  /*ROSADO*/
                }else{
                    $color_progress = 'linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%)';   /*ROSADO-ROJO*/
                }
            }
            else if ($_resultado_grafico_v["dividendo"] < $_resultado_grafico_v["meta_1"]) {
                //meta 1
                $porcentaje = round(($_resultado_grafico_v["dividendo"] / (($_resultado_grafico_v["meta_1"])) ) * 100, 2);
                $diferenciameta = $_resultado_grafico_v["meta_1"] - $_resultado_grafico_v["dividendo"];
                if ($diferenciameta < 0) $diferenciameta = 0;
                $valor_meta = ($_resultado_grafico_v["meta_1"]);
                if($porcentaje < 45){
                    $color_progress = '#DC3545FF';  /*ROJO*/
                }
                else if($porcentaje < 50){
                    $color_progress = 'linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%)';  /*ROJO-AMARILLO*/
                }else if($porcentaje < 95){
                    $color_progress = '#ffc107';  /*AMARILLO*/
                }else{
                    $color_progress= '#8ec117';  /*AMARILLO-VERDE*/
                }
            }
            else {
                $valor_mayor_cero=intval($_resultado_grafico_v["meta_2"]);
                if ($valor_mayor_cero>0){
                    $porcentaje = round(($_resultado_grafico_v["dividendo"] / ($_resultado_grafico_v["meta_2"])  ) * 100, 2);
                }else{
                    $porcentaje = round(0, 2);
                }
                $diferenciameta = $_resultado_grafico_v["meta_2"] - $_resultado_grafico_v["dividendo"];
                if ($diferenciameta < 0) $diferenciameta = 0;
                $valor_meta = ($_resultado_grafico_v["meta_2"]);
                if ($porcentaje < 99){
                    $color_progress = '#008ffb';  /*VERDE*/
                }else if ($porcentaje < 98){
                    $color_progress = 'linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%)';  /*VERDE-AZUL*/
                }else {
                    $color_progress = '#008ffb'; /*AZUL*/
                }

            }
            //
            if ($porcentaje >= 90) {
                $html[] = '<div class=" w-100 bg-white rounded">
                                        <div class="position-relative rounded">
                                            <div class="progress bg-white rounded" style="height: 30px">
                                                    <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                             </div>
                                             <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                    <span style="font-weight: lighter">
                                                              <b style="font-weight: bold !important; font-size: 18px">
                                                                ' . $porcentaje . '% </b>
                                                               - ' . $_resultado_grafico_v["dividendo"] . ' /  ' . $valor_meta . '
                                                                   <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                                   ' . $diferenciameta . '
                                                                  </p>
                                                    </span>
                                             </div>
                                         </div>
                                        <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
            }
            else if ($porcentaje > 75)
            {
                $html[] = '<div class=" w-100 bg-white rounded">
                                  <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 30px">
                                              <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                       </div>
                                       <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                              <span style="font-weight: lighter">
                                                        <b style="font-weight: bold !important; font-size: 18px">
                                                          ' . $porcentaje . '% </b>
                                                         - ' . $_resultado_grafico_v["dividendo"] . ' /  ' . $valor_meta . '
                                                             <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                             ' . $diferenciameta . '
                                                            </p>
                                              </span>
                                       </div>
                                   </div>
                                  <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
            }
            else if ($porcentaje > 50)
            {
                $html[] = '<div class=" w-100 bg-white rounded">
                                  <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 30px">
                                              <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                       </div>
                                       <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                              <span style="font-weight: lighter">
                                                        <b style="font-weight: bold !important; font-size: 18px">
                                                          ' . $porcentaje . '% </b>
                                                         - ' . $_resultado_grafico_v["dividendo"] . ' /  ' . $valor_meta . '
                                                             <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                             ' . $diferenciameta . '
                                                            </p>
                                              </span>
                                       </div>
                                   </div>
                                  <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
            }
            else {
                $html[] = '<div class=" w-100 bg-white rounded">
                                  <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 30px">
                                              <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                       </div>
                                       <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                              <span style="font-weight: lighter">
                                                        <b style="font-weight: bold !important; font-size: 18px">
                                                          ' . $porcentaje . '% </b>
                                                         - ' . $_resultado_grafico_v["dividendo"] . ' /  ' . $valor_meta . '
                                                             <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                             ' . $diferenciameta . '
                                                            </p>
                                              </span>
                                       </div>
                                   </div>
                                  <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
            }
            //
            $html[] = '</td>';
            $html[] = '</tr>';

        }

        //recuperado reciente
        foreach ($_resultado_grafico as $_resultado_grafico_k2=>$_resultado_grafico_v2)
        {

            if($_resultado_grafico_k2=='RECUPERADO RECIENTE')
            {

                $html[] = '<tr>';
                $html[] = '<td style="width:20%;" class="text-center">';
                $html[] = '<span class="px-4 pt-1 pb-1 bg-info text-center w-20 rounded font-weight-bold"
                                    style="align-items: center;height: 40px !important; color: black !important;">' .
                    $_resultado_grafico_k2 .
                    '</span>';
                $html[] = '</td>';
                $html[] = '<td style="width:80%">';

                $porcentaje = round(($r_reciente_cuenta / (($a_reciente_cuenta+$r_reciente_cuenta)*0.75) ) * 100, 2);
                // $porcentaje = 96;
                $diferenciameta = (($a_reciente_cuenta+$r_reciente_cuenta)*0.75)-$r_reciente_cuenta;

                $diferenciameta=round($diferenciameta);
                if($diferenciameta<0)$diferenciameta=0;
                $color_progress = '';

                // var_dump($_resultado_grafico_v2);
                if($porcentaje < 45){
                    $color_progress = '#DC3545FF';  /*ROJO*/
                }
                else if($porcentaje < 50){
                    $color_progress = 'linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%)';  /*ROJO-AMARILLO*/
                }else if($porcentaje < 90){
                    $color_progress = '#ffc107';  /*AMARILLO*/
                }else if($porcentaje < 95){
                     /*AMARILLO-VERDE GRADIENTE*/
                    $color_progress = 'linear-gradient(90deg, #ffc107 0%, #ffc107 89%, #8ec117 100%)';
                }else{
                    $color_progress= '#8ec117';  /*AMARILLO-VERDE*/
                }

                if ($porcentaje >= 0)
                {
                    $html[] = '<div class="w-100 bg-white rounded">
                                        <div class="position-relative rounded">
                                            <div class="progress bg-white rounded" style="height: 30px">
                                                    <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                             </div>
                                             <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                    <span style="font-weight: lighter">
                                                              <b style="font-weight: bold !important; font-size: 18px">
                                                                ' . $porcentaje . '% </b>- '
                        . $_resultado_grafico_v2["dividendo"] .
                        ' /  (A.R .'.$a_reciente_cuenta. ' + R.R.'. ((($r_reciente_cuenta))). ')*0.75
                                                                   <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                                   '.$diferenciameta.'
                                                                  </p>
                                                    </span>
                                             </div>
                                         </div>
                                        <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }

                $html[] = '</td>';
                $html[] = '</tr>';

                break;
            }


        }

        foreach ($_resultado_grafico as $_resultado_grafico_k2=>$_resultado_grafico_v2)
        {

            if($_resultado_grafico_k2=='LEVANTADO')
            {
                $html[] = '<tr>';
                $html[] = '<td style="width:20%;" class="text-center">';
                $html[] = '<span class="px-4 pt-1 pb-1 bg-info text-center w-20 rounded font-weight-bold"
                                    style="align-items: center;height: 40px !important; color: black !important;">' .
                    $_resultado_grafico_k2 .
                    '</span>';
                $html[] = '</td>';
                $html[] = '<td style="width:80%">';

                $porcentaje = round(($activos_cuenta / (($activos_cuenta+$recurrentes_cuenta)*0.75) ) * 100, 2);
                $diferenciameta = ($activos_cuenta+$recurrentes_cuenta)*(0.75) - $activos_cuenta;
                $diferenciameta = round($diferenciameta,2,PHP_ROUND_HALF_UP);

                $diferenciameta=round($diferenciameta);
                if($diferenciameta<0)$diferenciameta=0;
                $color_progress = '';

                // var_dump($_resultado_grafico_v2);
                if($porcentaje < 45){
                    $color_progress = '#DC3545FF';  /*ROJO*/
                }
                else if($porcentaje < 50){
                    $color_progress = 'linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%)';  /*ROJO-AMARILLO*/
                }else if($porcentaje < 90){
                    $color_progress = '#ffc107';  /*AMARILLO*/
                }else if($porcentaje < 95){
                     /*AMARILLO-VERDE GRADIENTE*/
                    $color_progress = 'linear-gradient(90deg, #ffc107 0%, #ffc107 89%, #8ec117 100%)';
                }else{
                    $color_progress= '#8ec117';  /*AMARILLO-VERDE*/
                }

                if ($porcentaje >= 0)
                {
                    $html[] = '<div class="w-100 bg-white rounded">
                                        <div class="position-relative rounded">
                                            <div class="progress bg-white rounded" style="height: 30px">
                                                    <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                             </div>
                                             <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                    <span style="font-weight: lighter">
                                                              <b style="font-weight: bold !important; font-size: 18px">
                                                                ' . $porcentaje . '% </b>- '
                        . $activos_cuenta .
                        ' /  (levantados. ' . ($activos_cuenta).'   + caidos. '.($recurrentes_cuenta) . ')
                                                                   <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                                   '.$diferenciameta.'
                                                                  </p>
                                                    </span>
                                             </div>
                                         </div>
                                        <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }

                $html[] = '</td>';
                $html[] = '</tr>';

                break;
            }


        }

        /*
         * $activos_cuenta=0;
        $recurrentes_cuenta=0;
        $r_abandono_cuenta=0;
        $r_reciente_cuenta=0;
        $nuevos_cuenta=0;
         * */
        /*foreach ($_resultado_grafico as $_resultado_grafico_k2=>$_resultado_grafico_v2)
        {

            if($_resultado_grafico_k2=='LEVANTADO')
            {
                $html[] = '<tr>';
                $html[] = '<td style="width:20%;" class="text-center">';
                $html[] = '<span class="px-4 pt-1 pb-1 bg-info text-center w-20 rounded font-weight-bold"
                                    style="align-items: center;height: 40px !important; color: black !important;">' .
                    'GLOBAL' .
                    '</span>';
                $html[] = '</td>';
                $html[] = '<td style="width:80%">';

                $porcentaje = round(( ($suma) / (1625) ) * 100, 2);
                $diferenciameta = ((1625)) - ($suma);

                $diferenciameta=round($diferenciameta);
                if($diferenciameta<0)$diferenciameta=0;
                $color_progress = '#FFD4D4';

                if ($porcentaje >= 0)
                {
                    $html[] = '<div class="w-100 bg-white rounded">
                                        <div class="position-relative rounded">
                                            <div class="progress bg-white rounded" style="height: 40px">
                                                    <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                             </div>
                                             <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                    <span style="font-weight: lighter">
                                                              <b style="font-weight: bold !important; font-size: 18px">
                                                                ' . $porcentaje . '% </b>- '
                        . ($suma) .
                        ' /  (Meta total. ' . (1625) .')
                                                                   <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                                   '.$diferenciameta.'
                                                                  </p>
                                                    </span>
                                             </div>
                                         </div>
                                        <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
                }

                $html[] = '</td>';
                $html[] = '</tr>';

                break;
            }

        }*/

        $html[] = '</table>';
        $html = join('', $html);
        return $html;

    }

    public function CobranzasGeneral(Request $request)
    {
        $fp=Pedido::orderBy('created_at','asc')->limit(1)->first();
        $periodo_origen=Carbon::parse($fp->created_at)->startOfMonth();
        $periodo_actual=Carbon::parse(now())->endOfMonth();
        $diferenciameses = ($periodo_origen->diffInMonths($periodo_actual));
        $mes_artificio=null;

        //Carbon::setLocale('es');
        setlocale(LC_ALL, 'es_ES');
        $html = [];
        $html[] = '<table class="table table-situacion-clientes" style="background: #ade0db; color: #0a0302">';
        //$html="";
        for($i=1;$i<=$diferenciameses;$i++)
        {
            $periodo_origen=Carbon::parse($fp->created_at)->startOfMonth();
            //$html_mes=$periodo_origen->addMonths($i)->format('Y-M');
            $periodo_origen=Carbon::parse($fp->created_at)->startOfMonth();
            $mes_artificio=$periodo_origen->addMonths($i)->subMonth();
            //$mes_actual_artificio=Carbon::now();

            //saer si es mes diciembre 2022
            if($mes_artificio->year=='2023' && $mes_artificio->month=='01')
            {
                //solo considerar pagos de dia 17 en adelante
                continue;
            }
            else if($mes_artificio->year=='2022' && $mes_artificio->month=='12')
            {
                //solo considerar pagos de dia 17 en adelante
                continue;
            }
            else if($mes_artificio->year=='2022' && $mes_artificio->month=='11'){
                continue;
            }/*else{

            }*/

            $total_pagado_mespasado_a = Pedido::query()
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->whereNotIn('pedidos.user_clavepedido',['B','99','17','18','19'])
                //->where('pedidos.estado_correccion','0')
                ->where('pedidos.codigo','not like','%-C%')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','C'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$mes_artificio->clone()->startOfMonth()->startOfDay(), $mes_artificio->clone()->endOfMonth()->endOfDay()])
                ->count();

            $total_pagado_mespasado_b = Pedido::query()
                ->leftjoin("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                ->whereNotIn('pedidos.user_clavepedido',['B','99','17','18','19'])
                //->where('pedidos.estado_correccion','0')
                ->where('pedidos.codigo','not like','%-C%')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos.pago','=','1'],
                    ['pedidos.pagado','=','2'],
                    ['pago_pedidos.estado','=',1],
                    ['pago_pedidos.pagado','=',2]
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$mes_artificio->clone()->startOfMonth()->startOfDay(), $mes_artificio->clone()->endOfMonth()->endOfDay()])
                ->count();

            $total_pagado_mespasado_c = Pedido::query()
                ->leftjoin("pedidos_anulacions", "pedidos_anulacions.pedido_id", "pedidos.id")
                ->whereNotIn('pedidos.user_clavepedido',['B','99','17','18','19'])
                //->where('pedidos.estado_correccion','0')
                ->where('pedidos.codigo','not like','%-C%')
                ->where('pedidos.estado', '1')
                ->where([
                    ['pedidos_anulacions.state_solicitud','=','1'],
                    ['pedidos_anulacions.tipo','=','Q'],
                ])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$mes_artificio->clone()->startOfMonth()->startOfDay(), $mes_artificio->clone()->endOfMonth()->endOfDay()])
                ->count();

            $total_pagado_mespasado=$total_pagado_mespasado_a+$total_pagado_mespasado_b+$total_pagado_mespasado_c;


            $total_pedido_mespasado = Pedido::query()
                //->where('pedidos.codigo', 'not like', "%-C%")
                ->whereNotIn('pedidos.user_clavepedido',['B','99','17','18','19'])
                ->where('pedidos.estado', '1')
                //->where('pedidos.estado_correccion','0')
                ->where('pedidos.codigo','not like','%-C%')
                ->whereIn('pedidos.pendiente_anulacion',  ['0','1'])
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$mes_artificio->clone()->startOfMonth()->startOfDay(), $mes_artificio->clone()->endOfMonth()->endOfDay()])
                ->count();

            $porcentaje = 0;
            $diferenciameta = 0;
            $valor_meta = 0;
            $color_progress = '';
            $color_degradado = 0;
            if ($total_pagado_mespasado   < $total_pedido_mespasado) {
                //meta 1
                $porcentaje = round(($total_pagado_mespasado / $total_pedido_mespasado) * 100, 2);
                $diferenciameta = $total_pedido_mespasado - $total_pagado_mespasado;
                if ($diferenciameta < 0) $diferenciameta = 0;
                if($porcentaje < 45){
                    $color_progress = '#DC3545FF';  /*ROJO*/
                }else if($porcentaje < 50){
                    $color_progress = 'linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%)';  /*ROJO-AMARILLO*/
                }else if($porcentaje < 95){
                    $color_progress = '#ffc107';  /*AMARILLO*/
                }else{
                    $color_progress= '#8ec117';  /*AMARILLO-VERDE*/
                }
            }

            if ($porcentaje == 0){
                continue;
            }


            $title_mes_artificio=$mes_artificio->translatedFormat('F - Y');
            //$title_mes_artificio=$title_mes_artificio->formatLocalized('%B');
            $html[] = '<tr>';
            $html[] = '<td style="width:20%;" class="text-center">';
            $html[] = '<span class="px-4 pt-1 pb-1 bg-info text-center w-20 rounded font-weight-bold"
                                    style="align-items: center;height: 40px !important; color: black !important;">' .
                $title_mes_artificio.
                '</span>';
            $html[] = '</td>';

            $html[] = '<td style="width:80%">';



            if ($porcentaje >= 90) {
                $html[] = '<div class="w-100 bg-white rounded">
                                        <div class="position-relative rounded">
                                            <div class="progress bg-white rounded" style="height: 30px">
                                                    <div class="rounded" role="progressbar" style="background: ' . $color_progress . ' !important; width: ' . $porcentaje . '%" ></div>
                                             </div>
                                             <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                                    <span style="font-weight: lighter">
                                                              <b style="font-weight: bold !important; font-size: 18px">
                                                                ' . $porcentaje . '% </b>
                                                               - ' . $total_pagado_mespasado . ' /  ' . $total_pedido_mespasado . '
                                                                   <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                                   ' . $diferenciameta . '
                                                                  </p>
                                                    </span>
                                             </div>
                                         </div>

                                  </div>
                                  <sub class="">Cobranzas: ' . $total_pagado_mespasado . '</sub>';
            }
            else if ($porcentaje > 75)
            {
                $html[] = '<div class="w-100 bg-white rounded">
                                  <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 30px">
                                              <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                       </div>
                                       <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                              <span style="font-weight: lighter">
                                                        <b style="font-weight: bold !important; font-size: 18px">
                                                          ' . $porcentaje . '% </b>
                                                         - ' . $total_pagado_mespasado . ' /  ' . $total_pedido_mespasado . '
                                                             <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                             ' . $diferenciameta . '
                                                            </p>
                                              </span>
                                       </div>
                                   </div>

                            </div>
                            <sub class="">Cobranzas: '.$total_pagado_mespasado.'</sub>';
            }
            else if ($porcentaje > 50)
            {
                $html[] = '<div class="w-100 bg-white rounded">
                                  <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 30px">
                                              <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                       </div>
                                       <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                              <span style="font-weight: lighter">
                                                        <b style="font-weight: bold !important; font-size: 18px">
                                                          ' . $porcentaje . '% </b>
                                                         - ' . $total_pagado_mespasado . ' /  ' . $total_pedido_mespasado . '
                                                             <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                             ' . $diferenciameta . '
                                                            </p>
                                              </span>
                                       </div>
                                   </div>

                            </div>
                            <sub class="">Cobranzas '.$total_pagado_mespasado.'</sub>';
            }
            else {
                $html[] = '<div class="w-100 bg-white rounded">
                                  <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 30px">
                                              <div class="rounded" role="progressbar" style="background: '.$color_progress.' !important; width: ' . $porcentaje . '%" ></div>
                                       </div>
                                       <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                              <span style="font-weight: lighter">
                                                        <b style="font-weight: bold !important; font-size: 18px">
                                                          ' . $porcentaje . '% </b>
                                                         - ' . $total_pagado_mespasado . ' /  ' . $total_pedido_mespasado . '
                                                             <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d96866 !important">
                                                             ' . $diferenciameta . '
                                                            </p>
                                              </span>
                                       </div>
                                   </div>

                            </div>
                            <sub class="">Cobranzas '.$total_pagado_mespasado.'</sub>';
            }

            $html[] = '</td>';
            $html[] = '</tr>';

        }
        $html[] = '</table>';
        $html = join('', $html);
        return $html;
    }

    public function Analisisgrafico(Request $request)
    {
        /*      return $request->all();*/
        $_pedidos_mes_pasado = User::select([
            'users.id', 'users.name', 'users.email'
            , DB::raw(" (select count( c.id) from clientes c inner join users a  on c.user_id=a.id where a.rol='Asesor' and a.llamada=users.id and c.situacion='RECUPERADO RECIENTE' ) recuperado_reciente")
            , DB::raw(" (select count( c.id) from clientes c inner join users a  on c.user_id=a.id where a.rol='Asesor' and a.llamada=users.id and c.situacion='RECUPERADO ABANDONO' ) recuperado_abandono")
            , DB::raw(" (select count( c.id) from clientes c inner join users a  on c.user_id=a.id where a.rol='Asesor' and a.llamada=users.id and c.situacion='NUEVO' ) nuevo")
        ])
            ->whereIn('users.rol', ['Llamadas']);

        $_pedidos_mes_pasado = $_pedidos_mes_pasado->get();
        $p_recuperado_reciente = 0;
        $p_recuperado_abandono = 0;
        $p_recuperado_nuevo = 0;
        $p_total = 0;
        $p_total_cruzado = 0;
        $html = [];
        $html[] = '<div class="row table-total">';
        $html[] = '<div class="col-md-12 scrollbar-x">';
        $html[] = '<div class="table_analisis" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr;">';
        foreach ($_pedidos_mes_pasado as $pedido) {
            //$p_total=0;
            //$p_recuperado_reciente=$p_recuperado_reciente+intval($pedido->recuperado_reciente);
            //$p_recuperado_abandono=$p_recuperado_abandono+intval($pedido->recuperado_abandono);
            //$p_recuperado_nuevo=$p_recuperado_nuevo+intval($pedido->nuevo);
            $p_total = intval($pedido->recuperado_reciente) + intval($pedido->recuperado_abandono) + intval($pedido->nuevo);
            $p_total_cruzado = $p_total_cruzado + $p_total;
        }
        /*CABECERA*/
        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">ASESORES DE LLAMADA</h5></div>';
        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">RECUPERADO RECIENTE</h5></div>';
        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">RECUPERADO ABANDONO</h5></div>';
        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">NUEVO</h5></div>';
        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">TOTAL</h5></div>';

        foreach ($_pedidos_mes_pasado as $pedido) {
            /*CUERPO*/
            $p_total = 0;
            $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5  class="rounded p-2 font-weight-bold" style="background: ' . Pedido::color_skype_blue . '; color: black;"> ' . explode(' ', $pedido->name)[0] . '</h5></div>';

            $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6">';
            $html[] = '<h5 class="rounded p-4 font-weight-bold" style=" background: ' . Pedido::color_skype_blue . '; color: black;">' . $pedido->recuperado_reciente . '</h5>';
            $html[] = '</div>';

            $p_recuperado_reciente = $p_recuperado_reciente + intval($pedido->recuperado_reciente);
            $html[] = '<div  class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-4 font-weight-bold" style="background: ' . Pedido::color_skype_blue . '; color: black;">' . $pedido->recuperado_abandono . '</h5></div>';
            $p_recuperado_abandono = $p_recuperado_abandono + intval($pedido->recuperado_abandono);
            $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-4 font-weight-bold" style="background: ' . Pedido::color_skype_blue . '; color: black;">' . $pedido->nuevo . '</h5></div>';
            $p_recuperado_nuevo = $p_recuperado_nuevo + intval($pedido->nuevo);
            $p_total = intval($pedido->recuperado_reciente) + intval($pedido->recuperado_abandono) + intval($pedido->nuevo);

            $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6">';
            $html[] = '<div class="w-100 bg-white rounded">
                    <div class="position-relative rounded">
                      <div class="progress bg-white rounded" style="height: 40px">
                          <div class="rounded" role="progressbar" style="background: ' . Pedido::colo_progress_bar . ' !important; width: ' . number_format((($p_total / $p_total_cruzado) * 100), 2) . '%" ></div>
                          </div>
                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                            <span style="font-weight: lighter; font-size: 16px"> <b style="font-weight: bold !important; font-size: 18px">  ' . number_format((($p_total / $p_total_cruzado) * 100), 2) . '% </b> - ' . $p_total . ' / ' . $p_total_cruzado . '</span>
                        </div>
                    </div>
                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                  </div>';
            $html[] = '</div>';
            //$p_total_cruzado=$p_total_cruzado+intval($p_total);
        }

        //totales
        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">TOTALES</h5></div>';

        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6">';
        $html[] = '<div class="w-100 bg-white rounded">
                    <div class="position-relative rounded">
                      <div class="progress bg-white rounded" style="height: 40px">
                          <div class="rounded" role="progressbar" style="background: ' . Pedido::colo_progress_bar . ' !important; width: ' . number_format((($p_recuperado_reciente / $p_total_cruzado) * 100), 2) . '%" ></div>
                          </div>
                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                            <span style="font-weight: lighter; font-size: 16px"> <b style="font-weight: bold !important; font-size: 18px">  ' . number_format((($p_recuperado_reciente / $p_total_cruzado) * 100), 2) . '% </b> - ' . $p_recuperado_reciente . ' / ' . $p_total_cruzado . '</span>
                        </div>
                    </div>
                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                  </div>';
        $html[] = '</div>';


        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6">';
        $html[] = '<div class="w-100 bg-white rounded">
                    <div class="position-relative rounded">
                      <div class="progress bg-white rounded" style="height: 40px">
                          <div class="rounded" role="progressbar" style="background: ' . Pedido::colo_progress_bar . ' !important; width: ' . number_format((($p_recuperado_abandono / $p_total_cruzado) * 100), 2) . '%" ></div>
                          </div>
                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                            <span style="font-weight: lighter; font-size: 16px"> <b style="font-weight: bold !important; font-size: 18px">  ' . number_format((($p_recuperado_abandono / $p_total_cruzado) * 100), 2) . '% </b> - ' . $p_recuperado_abandono . ' / ' . $p_total_cruzado . '</span>
                        </div>
                    </div>
                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                  </div>';
        $html[] = '</div>';

        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6">';
        $html[] = '<div class="w-100 bg-white rounded">
                    <div class="position-relative rounded">
                      <div class="progress bg-white rounded" style="height: 40px">
                          <div class="rounded" role="progressbar" style="background: ' . Pedido::colo_progress_bar . ' !important; width: ' . number_format((($p_recuperado_nuevo / $p_total_cruzado) * 100), 2) . '%" ></div>
                          </div>
                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                            <span style="font-weight: lighter; font-size: 16px"> <b style="font-weight: bold !important; font-size: 18px">  ' . number_format((($p_recuperado_nuevo / $p_total_cruzado) * 100), 2) . '% </b> - ' . $p_recuperado_nuevo . ' / ' . $p_total_cruzado . '</span>
                        </div>
                    </div>
                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                  </div>';
        $html[] = '</div>';

        $html[] = '<div class="p-2 text-center d-flex align-items-center justify-content-center" style="border: black 1px solid; background: #e4dbc6"><h5 class="rounded p-3 font-weight-bold" style="background: ' . Pedido::color_blue . '; color: #ffffff;">' . $p_total_cruzado . ' - 100.00%</h5></div>';

        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</div>';

        $html = join('', $html);
        return $html;
        //return view('reportes.analisis', compact('users','_pedidos_mes_pasado','mes_month','mes_anio','mes_mes','anios','dateM','dateY'));
    }


    public function PedidosPorFechas(Request $request)
    {
        $fecha = Carbon::now('America/Lima')->format('d-m-Y');
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.cantidad*dp.porcentaje) as total'),*/
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('pedidos.condicion', [1, 2, 3])
            ->where('pedidos.pago', '0')
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pdf = PDF::loadView('reportes.PedidosPorFechasPDF', compact('pedidos', 'pedidos2', 'fecha', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pedidos desde ' . $request->desde . ' hasta ' . $request->hasta . '.pdf');
    }

    public function PedidosPorAsesor(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', $request->user_id)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', $request->user_id)
            ->whereIn('pedidos.condicion', [1, 2, 3])
            ->where('pedidos.pago', '0')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pdf = PDF::loadView('reportes.PedidosPorAsesorPDF', compact('pedidos', 'pedidos2', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pedidos del asesor' . $request->desde . '.pdf');
    }

    public function PedidosPorAsesores(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pa.condicion as condicion_pa',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('u.id', [$request->user_id1, $request->user_id2, $request->user_id3, $request->user_id4])
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pedidos2 = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                DB::raw('sum(dp.total) as total'),
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('u.id', [$request->user_id1, $request->user_id2, $request->user_id3, $request->user_id4])
            ->whereIn('pedidos.condicion', [1, 2, 3])
            ->where('pedidos.pago', '0')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'pedidos.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $pdf = PDF::loadView('reportes.PedidosPorAsesoresPDF', compact('pedidos', 'pedidos2', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pedidos del asesor' . $request->desde . '.pdf');
    }

    public function PagosPorFechas(Request $request)
    {
        $fecha = Carbon::now('America/Lima')->format('d-m-Y');
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->rightjoin('pedidos as p', 'pp.pedido_id', 'p.id')
            ->rightjoin('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id',
                'dpe.codigo as codigos',
                'u.name as users',
                'pagos.observacion',
                'dpe.total as total_deuda',
                'pagos.total_cobro',
                DB::raw('sum(dpa.monto) as total_pago'),
                'pagos.condicion',
                'pagos.created_at as fecha'
            )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->whereBetween(DB::raw('DATE(pagos.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
            ->groupBy('pagos.id',
                'dpe.codigo',
                'u.name',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion',
                'pagos.created_at')
            ->get();

        $pdf = PDF::loadView('reportes.PagosPorFechasPDF', compact('pagos', 'fecha', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pagos desde ' . $request->desde . ' hasta ' . $request->hasta . '.pdf');
    }

    public function PagosPorAsesor(Request $request)
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id',
                'dpe.codigo as codigos',
                'u.name as users',
                'pagos.observacion',
                'dpe.total as total_deuda',
                DB::raw('sum(dpa.monto) as total_pago'),
                'pagos.condicion',
                'pagos.created_at as fecha'
            )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->where('u.id', $request->user_id)
            ->groupBy('pagos.id',
                'dpe.codigo',
                'u.name',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion',
                'pagos.created_at')
            ->get();

        $pdf = PDF::loadView('reportes.PagosPorAsesorPDF', compact('pagos', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pago por asesor.pdf');
    }

    public function PagosPorAsesores(Request $request)
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id',
                'dpe.codigo as codigos',
                'u.name as users',
                'pagos.observacion',
                'dpe.total as total_deuda',
                DB::raw('sum(dpa.monto) as total_pago'),
                'pagos.condicion',
                'pagos.created_at as fecha'
            )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->whereIn('u.id', [$request->user_id1, $request->user_id2, $request->user_id3, $request->user_id4])
            ->groupBy('pagos.id',
                'dpe.codigo',
                'u.name',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion',
                'pagos.created_at')
            ->get();

        $pdf = PDF::loadView('reportes.PagosPorAsesoresPDF', compact('pagos', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('Pago por asesores.pdf');
    }

    public function ticketVentaPDF(Pedido $venta)
    {
        $fecha = Carbon::now();
        $ventas = Pedido::join('clientes as c', 'ventas.cliente_id', 'c.id')
            ->join('users as u', 'ventas.user_id', 'u.id')
            ->join('detalle_ventas as dv', 'ventas.id', 'dv.venta_id')
            ->select(
                'ventas.id',
                'c.nombre as clientes',
                'u.name as users',
                'ventas.tipo_comprobante',
                DB::raw('sum(dv.cantidad*dv.precio) as total'),
                'ventas.created_at as fecha',
                'ventas.estado'
            )
            ->where('ventas.id', $venta->id)
            ->groupBy(
                'ventas.id',
                'c.nombre',
                'u.name',
                'ventas.tipo_comprobante',
                'ventas.created_at',
                'ventas.estado'
            )
            ->get();
        $detalleVentas = DetallePedido::join('articulos as a', 'detalle_ventas.articulo_id', 'a.id')
            ->select(
                'detalle_ventas.id',
                'a.nombre as articulos',
                'detalle_ventas.cantidad',
                'detalle_ventas.precio',
                DB::raw('detalle_ventas.cantidad*detalle_ventas.precio as subtotal'),
                'detalle_ventas.estado'
            )
            ->where('detalle_ventas.estado', '1')
            ->where('detalle_ventas.venta_id', $venta->id)
            ->get();

        /* $pdf = PDF::loadView('ventas.reportes.ticketPDF', compact('ventas', 'detalleVentas', 'fecha'))->setPaper('a4')/* ->setPaper(array(0,0,220,500), 'portrait') ;*/
        /* return $pdf->stream('productos ingresados.pdf'); */
        return view('ventas.reportes.ticketPDF', compact('ventas', 'detalleVentas', 'fecha'));
    }

    public function pedidosPDFpreview(Request $request)
    {
        $mirol = Auth::user()->rol;
        $identificador = Auth::user()->identificador;
        $fecha = Carbon::now('America/Lima')->format('Y-m-d');

        $pruc = $request->pruc;
        $pempresa = $request->pempresa;
        $pmes = $request->pmes;
        $panio = $request->panio;
        $pcantidad = $request->pcantidad;
        $ptipo_banca = $request->ptipo_banca;
        $pdescripcion = $request->pdescripcion;
        $pnota = $request->pnota;

        $pdf = PDF::loadView('pedidos.reportes.pedidosPDFpreview', compact('fecha', 'mirol', 'identificador', 'pruc', 'pempresa', 'pmes', 'panio', 'pcantidad', 'ptipo_banca', 'pdescripcion', 'pnota'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream('pedido ' . 'id' . '.pdf');

    }

    public function pedidosPDF(Pedido $pedido)
    {
        $mirol = Auth::user()->rol;
        $identificador = Auth::user()->identificador;

        //para pedidos anulados y activos
        $fecha = Carbon::now('America/Lima')->format('Y-m-d');

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
                'dp.total',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha',
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
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();


        $codigo_barras = Pedido::find($pedido->id)->codigo;
        $codigo_barras_img = generate_bar_code($codigo_barras);

        $funcion_qr = route('envio.escaneoqr', $codigo_barras);
        $codigo_qr_img = generate_bar_code($codigo_barras, 10, 10, 'black', true, "QRCODE");


        $pdf = PDF::loadView('pedidos.reportes.pedidosPDF', compact('pedidos', 'fecha', 'mirol', 'identificador', 'codigo_barras_img', 'codigo_qr_img'))
            ->setPaper('a4', 'portrait');
        //$canvas = PDF::getDomPDF();
        //return $canvas;
        return $pdf->stream('pedido ' . $pedido->id . '.pdf');
    }

    public function correccionPDF(Pedido $pedido)
    {
        $mirol = Auth::user()->rol;
        $identificador = Auth::user()->identificador;

        //para pedidos anulados y activos
        $fecha = Carbon::now('America/Lima')->format('Y-m-d');

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            //->join('corrections as cc','pedidos.codigo','cc.code')
            ->select([
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
                //'cc.motivo descripcion',
                DB::raw(' (select cc.motivo from corrections cc where cc.code=pedidos.codigo and cc.estado=1 order by cc.created_at desc limit 1) as descripcion'),
                DB::raw(' (select cc.detalle from corrections cc where cc.code=pedidos.codigo and cc.estado=1 order by cc.created_at desc limit 1) as nota'),
                DB::raw(' (select cc.type from corrections cc where cc.code=pedidos.codigo and cc.estado=1 order by cc.created_at desc limit 1) as type_correccion'),
                //'dp.nota',
                'dp.total',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            ])
            ->where('pedidos.id', $pedido->id)
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();


        $codigo_barras = Pedido::find($pedido->id)->codigo;
        $codigo_barras_img = generate_bar_code($codigo_barras);

        $funcion_qr = route('envio.escaneoqr', $codigo_barras);
        $codigo_qr_img = generate_bar_code($codigo_barras, 10, 10, 'black', true, "QRCODE");


        $pdf = PDF::loadView('pedidos.reportes.correccionPDF', compact('pedidos', 'fecha', 'mirol', 'identificador', 'codigo_barras_img', 'codigo_qr_img'))
            ->setPaper('a4', 'portrait');
        //$canvas = PDF::getDomPDF();
        //return $canvas;
        return $pdf->stream('pedido ' . $pedido->id . '.pdf');
    }

    public static function applyFilterPersonalizable($query, CarbonInterface $date = null, $column = 'created_at')
    {
        if ($date == null) {
            $date = now();
        }
        return $query->whereBetween($column, [
            $date->clone()->startOfMonth(),
            $date->clone()->endOfMonth()->endOfDay()
        ]);
    }

}



