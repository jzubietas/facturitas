<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\SituacionClientes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class ChartController extends Controller
{
    //

    public function getData(Request $request)
    {

        $arregloasesores = [];
        $arreglocontador = [];
        $arreglocontadoranul = [];

        $ids_asesores=User::where('rol',User::ROL_ASESOR)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('name','asc')
            ->pluck('id');

        $pedidosActivosPorAsesores = Pedido::selectRaw('u.identificador,u.letra,pedidos.user_id, count(pedidos.user_id) as total')
            ->leftJoin('users as u','pedidos.user_id','u.id')
            ->groupBy('pedidos.user_id','u.identificador','u.letra')
            ->whereIn('pedidos.user_id',$ids_asesores)
            ->where('pedidos.estado',1)
            ->where('pedidos.pendiente_anulacion',0)
            ->whereMonth('pedidos.created_at', 3)
            ->whereYear('pedidos.created_at',2023)
            ->get();

        foreach ($pedidosActivosPorAsesores as $item => $asslst){
            $arregloasesores[$item] =($asslst->identificador)."::".($asslst->identificador)."-".($asslst->letra);
            $arreglocontador[$item] =$asslst->total;

        }

        return response()->json([
            'labels' => $arregloasesores,
            'datasets' => [
                [
                    'label'                => 'Situacion',
                    'backgroundColor'      => 'rgba(105, 214, 222, 1)',
                    'borderColor'          => 'rgba(210, 214, 222, 1)',
                    'pointRadius'          => false,
                    'pointColor'           => 'rgba(210, 214, 222, 1)',
                    'pointStrokeColor'     => '#c1c7d1',
                    'pointHighlightFill'   => '#fff',
                    'pointHighlightStroke' => 'rgba(220,220,220,1)',
                    'data'                 => $arreglocontador
                ]
            ],
        ]);
    }

    public function getPedidosAsesores(Request $request)
    {
        $arregloasesores = [];
        $arreglocontador = [];

        $gDataporc1 = [];
        $oDataporc2 = [];
        $rDataporc3 = [];

        $totalasesores=User::where('rol',User::ROL_ASESOR)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('id','desc')->count();
        $totalasesores=ceil($totalasesores/2);
        $ids_asesores=User::where('rol',User::ROL_ASESOR)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('id','desc')->limit($totalasesores)
            ->get();

        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;
        foreach ($ids_asesores as $item => $asslst){
            $arregloasesores[$item] =($asslst->identificador)."-".($asslst->letra);

            $pedidosActivosPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion','<>',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosPendAnulPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosAnuladosPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',0)
                ->where('pedidos.pendiente_anulacion',0)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $totalfila=$pedidosActivosPorAsesores+$pedidosPendAnulPorAsesores+$pedidosAnuladosPorAsesores;

            $pedidosActivosPorAsesores=round($pedidosActivosPorAsesores,1);
            $pedidosPendAnulPorAsesores=round($pedidosPendAnulPorAsesores,1);
            $pedidosAnuladosPorAsesores=round($pedidosAnuladosPorAsesores,1);

            $pedidosAnuladosPorAsesores=$totalfila-$pedidosPendAnulPorAsesores-$pedidosActivosPorAsesores;

            if ($pedidosActivosPorAsesores != 0.0 || $totalfila!=0.0) {
                $gDataporc1[$item]=round((($pedidosActivosPorAsesores /$totalfila)*100),1);
            } else {
                $gDataporc1[$item]=0;
            }

            if ($pedidosPendAnulPorAsesores != 0.0 || $totalfila!=0.0) {
                $oDataporc2[$item]=round((($pedidosPendAnulPorAsesores /$totalfila)*100),1);
            } else {
                $oDataporc2[$item]=0;
            }

            if ($pedidosAnuladosPorAsesores != 0.0 || $totalfila!=0.0) {
                $rDataporc3[$item]=round((($pedidosAnuladosPorAsesores /$totalfila)*100),1);
            } else {
                $rDataporc3[$item]=0;
            }
        }
        return response()->json([
            'labels' => $arregloasesores,
            'datasets' => [
                [
                    'label' => 'Activos %',
                    'data' => $gDataporc1,
                    'backgroundColor' => 'rgb(32, 201, 151)',
                    'borderColor' => 'rgb(32, 201, 151)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Pendiente Anulacion %',
                    'data' => $oDataporc2,
                    'backgroundColor' => 'rgb(253, 126, 20)',
                    'borderColor' => 'rgb(253, 126, 20)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Anulados %',
                    'data' => $rDataporc3,
                    'backgroundColor' => 'rgb(220, 53, 69)',
                    'borderColor' => 'rgb(220, 53, 69)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
            ],
        ]);
    }

    public function getPedidosAsesoresfaltantes(Request $request)
    {
        $arregloasesores = [];

        $gDataporc1 = [];
        $oDataporc2 = [];
        $rDataporc3 = [];

        $totalasesores=User::where('rol',User::ROL_ASESOR)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('id','desc')->count();
        $totalasesores=ceil($totalasesores/2);
        $idsasesores=User::where('rol',User::ROL_ASESOR)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('id','desc')->limit($totalasesores)
            ->pluck('id');

        $ids_asesores=User::where('rol',User::ROL_ASESOR)->where('estado',1)
            ->whereNotIn('id',$idsasesores)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('id','desc')
            ->get();
        /*dd($ids_asesores);*/
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;
        foreach ($ids_asesores as $item => $asslst){
            $arregloasesores[$item] =($asslst->identificador)."-".($asslst->letra);

            $pedidosActivosPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion','<>',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosPendAnulPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosAnuladosPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',0)
                ->where('pedidos.pendiente_anulacion',0)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $totalfila=$pedidosActivosPorAsesores+$pedidosPendAnulPorAsesores+$pedidosAnuladosPorAsesores;

            $pedidosActivosPorAsesores=round($pedidosActivosPorAsesores,1);
            $pedidosPendAnulPorAsesores=round($pedidosPendAnulPorAsesores,1);
            $pedidosAnuladosPorAsesores=round($pedidosAnuladosPorAsesores,1);

            $pedidosAnuladosPorAsesores=$totalfila-$pedidosPendAnulPorAsesores-$pedidosActivosPorAsesores;

            if ($pedidosActivosPorAsesores != 0.0 || $totalfila!=0.0) {
                $gDataporc1[$item]=round((($pedidosActivosPorAsesores /$totalfila)*100),1);
            } else {
                $gDataporc1[$item]=0;
            }

            if ($pedidosPendAnulPorAsesores != 0.0 || $totalfila!=0.0) {
                $oDataporc2[$item]=round((($pedidosPendAnulPorAsesores /$totalfila)*100),1);
            } else {
                $oDataporc2[$item]=0;
            }

            if ($pedidosAnuladosPorAsesores != 0.0 || $totalfila!=0.0) {
                $rDataporc3[$item]=round((($pedidosAnuladosPorAsesores /$totalfila)*100),1);
            } else {
                $rDataporc3[$item]=0;
            }
        }
        return response()->json([
            'labels' => $arregloasesores,
            'datasets' => [
                [
                    'label' => 'Activos %',
                    'data' => $gDataporc1,
                    'backgroundColor' => 'rgb(32, 201, 151)',
                    'borderColor' => 'rgb(32, 201, 151)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Pendiente Anulacion %',
                    'data' => $oDataporc2,
                    'backgroundColor' => 'rgb(253, 126, 20)',
                    'borderColor' => 'rgb(253, 126, 20)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Anulados %',
                    'data' => $rDataporc3,
                    'backgroundColor' => 'rgb(220, 53, 69)',
                    'borderColor' => 'rgb(220, 53, 69)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
            ],
        ]);
    }

    public function getPedidosEncargadosfaltantes(Request $request)
    {
        $arregloasesores = [];

        $gDataporc1 = [];
        $oDataporc2 = [];
        $rDataporc3 = [];

        $totalasesores=User::where('rol',User::ROL_ENCARGADO)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('id','desc')->count();


        $arrayasesores=User::where('rol',User::ROL_ENCARGADO)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->pluck('id');


        /*dd($ids_asesores);*/
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;
        foreach ($arrayasesores as $item => $asslst){
            $arregloasesores[$item] =($asslst->identificador)."-".($asslst->letra);

            $pedidosActivosPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion','<>',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosPendAnulPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosAnuladosPorAsesores = Pedido::where('pedidos.user_id',$asslst->id)
                ->where('pedidos.estado',0)
                ->where('pedidos.pendiente_anulacion',0)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $totalfila=$pedidosActivosPorAsesores+$pedidosPendAnulPorAsesores+$pedidosAnuladosPorAsesores;

            $pedidosActivosPorAsesores=round($pedidosActivosPorAsesores,1);
            $pedidosPendAnulPorAsesores=round($pedidosPendAnulPorAsesores,1);
            $pedidosAnuladosPorAsesores=round($pedidosAnuladosPorAsesores,1);

            $pedidosAnuladosPorAsesores=$totalfila-$pedidosPendAnulPorAsesores-$pedidosActivosPorAsesores;

            if ($pedidosActivosPorAsesores != 0.0 || $totalfila!=0.0) {
                $gDataporc1[$item]=round((($pedidosActivosPorAsesores /$totalfila)*100),1);
            } else {
                $gDataporc1[$item]=0;
            }

            if ($pedidosPendAnulPorAsesores != 0.0 || $totalfila!=0.0) {
                $oDataporc2[$item]=round((($pedidosPendAnulPorAsesores /$totalfila)*100),1);
            } else {
                $oDataporc2[$item]=0;
            }

            if ($pedidosAnuladosPorAsesores != 0.0 || $totalfila!=0.0) {
                $rDataporc3[$item]=round((($pedidosAnuladosPorAsesores /$totalfila)*100),1);
            } else {
                $rDataporc3[$item]=0;
            }
        }
        return response()->json([
            'labels' => $arregloasesores,
            'datasets' => [
                [
                    'label' => 'Activos %',
                    'data' => $gDataporc1,
                    'backgroundColor' => 'rgb(32, 201, 151)',
                    'borderColor' => 'rgb(32, 201, 151)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Pendiente Anulacion %',
                    'data' => $oDataporc2,
                    'backgroundColor' => 'rgb(253, 126, 20)',
                    'borderColor' => 'rgb(253, 126, 20)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Anulados %',
                    'data' => $rDataporc3,
                    'backgroundColor' => 'rgb(220, 53, 69)',
                    'borderColor' => 'rgb(220, 53, 69)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
            ],
        ]);
    }

    public function getClientesActivosBloqueados(Request $request)
    {
        $arregloasesores = [];

        $gDataporc1 = [];
        $oDataporc2 = [];
        $rDataporc3 = [];

        $totalasesores=User::where('rol',User::ROL_ASESOR)
            ->where('estado',1)
            ->select(['id','clave_pedidos as identificador','name','letra',])
            ->orderBy('id','desc')->count();

        $arrayasesores=User::where('rol',User::ROL_ASESOR)
            ->where('estado',1)
            ->select(['id','clave_pedidos as identificador','name','letra',])
            ->orderBy('supervisor','asc');
            //->pluck('id');

        //dd($arrayasesores);
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;

        return response()->json(["data"=>$arrayasesores]);

        foreach ($arrayasesores as $item => $asslst)
        {
            echo "<pre>";
            print_r($item);
            echo "</pre>";

            $arregloasesores[$item] =($asslst->identificador)."-".($asslst->letra);

            $clientes_activos=Cliente:://CLIENTES SIN PEDIDOS
            join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado', '1')
                ->where('clientes.user_clavepedido',$asslst->user_clavepedido)
                ->where('clientes.tipo', '1')
                ->whereIn('clientes.situacion',
                    [
                        Cliente::RECUPERADO_ABANDONO,
                        Cliente::RECUPERADO_RECIENTE,
                        Cliente::RECUPERADO,
                        Cliente::LEVANTADO,
                        Cliente::NUEVO
                    ])
                ->whereNotIn([Cliente::PRETENDIDO])
                ->count();

            $clientes_totales=Cliente:://CLIENTES SIN PEDIDOS
            join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado', '1')
                ->where('clientes.user_clavepedido',$asslst->user_clavepedido)
                ->where('clientes.tipo', '1')
                ->whereIn('clientes.situacion',
                    [
                        Cliente::RECUPERADO_ABANDONO,
                        Cliente::RECUPERADO_RECIENTE,
                        Cliente::RECUPERADO,
                        Cliente::LEVANTADO,
                        Cliente::NUEVO
                    ])
                ->whereNotIn([Cliente::PRETENDIDO])
                ->count();

            $totalfila=$clientes_activos+$clientes_totales;

            if ($clientes_activos != 0.0 || $totalfila!=0.0) {
                $gDataporc1[$item]=round((($clientes_activos /$totalfila)*100),1);
            } else {
                $gDataporc1[$item]=0;
            }

            if ($clientes_totales != 0.0 || $totalfila!=0.0) {
                $oDataporc2[$item]=round((($clientes_totales /$totalfila)*100),1);
            } else {
                $oDataporc2[$item]=0;
            }

        }
        return response()->json([
            'labels' => $arregloasesores,
            'datasets' => [
                [
                    'label' => 'Activos %',
                    'data' => $gDataporc1,
                    'backgroundColor' => 'rgb(32, 201, 151)',
                    'borderColor' => 'rgb(32, 201, 151)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Pendiente Anulacion %',
                    'data' => $oDataporc2,
                    'backgroundColor' => 'rgb(253, 126, 20)',
                    'borderColor' => 'rgb(253, 126, 20)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
                [
                    'label' => 'Anulados %',
                    'data' => $rDataporc3,
                    'backgroundColor' => 'rgb(220, 53, 69)',
                    'borderColor' => 'rgb(220, 53, 69)',
                    'borderWidth' => '1',
                    'datalabels'  => [
                        'display'=> 'true'
                    ]
                ],
            ],
        ]);
    }

    public function caidosDeudaConSin(Request $request)
    {
        $caidos=Cliente::where('situacion','=','CAIDO')->activo()->where('tipo','=','1')
        ->whereNotIn('user_clavepedido',['17','18','19','21','B']);
        $caidos_total=$caidos->count();

        $caidos_deben=$caidos->clone()->where('deuda','1')->count();
        $caidos_no_deben=$caidos->clone()->where('deuda','0')->count();

        //dd($caidos_total,$caidos_deben,$caidos_no_deben);

        /*dd($ids_asesores);*/
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;

        $labels=['Con deuda','Sin deuda'];
        $datas=[$caidos_deben, $caidos_no_deben];
        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Caidos con o sin deuda',
                    'data' => $datas,
                    'backgroundColor' => [
                        'rgb(32, 201, 151)','rgb(54, 162, 235)'
                    ],
                    'borderColor' => ['rgb(32, 201, 151)','rgb(32, 201, 151)'],
                    'borderWidth' => '1',
                    'hoverOffset'=>4,
                ],
            ],
            'title'=>'Total caidos: '.$caidos_total
        ]);
    }

    public function caidosVienenDe(Request $request)
    {
        $caidos=Cliente::where('situacion','=','CAIDO')->activo()->where('tipo','=','1')
            ->whereNotIn('user_clavepedido',['17','18','19','21','B']);
        $caidos_total=$caidos->count();

        $periodo_antes = Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m');
        $periodo_actual = Carbon::now()->clone()->startOfMonth()->format('Y-m');

        $situaciones_clientes = SituacionClientes::leftJoin('situacion_clientes as a', 'a.cliente_id', 'situacion_clientes.cliente_id')
            ->join('clientes as c','c.id','situacion_clientes.cliente_id')
            ->join('users as u','u.id','c.user_id')
            ->Where([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->groupBy([
                //'situacion_clientes.situacion',
                'a.situacion',
                //'situacion_clientes.user_clavepedido'
            ])
            ->orderBy('a.situacion','asc')
            ->select([
                //'situacion_clientes.situacion',
                'a.situacion  as situacion_anterior',
                //'situacion_clientes.user_clavepedido as user_identificador',
                DB::raw('count(situacion_clientes.situacion) as total')
            ])
            ->get();

        $_resultado_grafico=[];
        /*foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            echo "<br>Caidos -- ".$situaciones_clientes_->situacion_anterior." -- ".$situaciones_clientes_->user_identificador." -- ".$situaciones_clientes_->total;
        }*/

        /*dd($ids_asesores);*/
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;

        $labels=[];
        $datas=[];
        $backgroundColor=['rgb(30, 144, 255, 1)','rgb(178, 34, 34, 1 )','rgb(0, 128, 0, 1)','rgb(255, 160, 122, 1)'];
        foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            $labels[]='Caidos de '.$situaciones_clientes_->situacion_anterior;
            $datas[]=$situaciones_clientes_->total;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Caidos con o sin deuda',
                    'data' => $datas,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => ['rgb(32, 201, 151)','rgb(32, 201, 151)'],
                    'borderWidth' => '1',
                    'hoverOffset'=>4,
                ],
            ],
            'title'=>'Total caidos: '.$caidos_total
        ]);
    }

    public function caidosVienenDeBarra(Request $request)
    {
        $caidos=Cliente::where('situacion','=','CAIDO')->activo()->where('tipo','=','1')
            ->whereNotIn('user_clavepedido',['17','18','19','21','B']);
        $caidos_total=$caidos->count();

        $periodo_antes = Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m');
        $periodo_actual = Carbon::now()->clone()->startOfMonth()->format('Y-m');

        $situaciones_clientes_a = SituacionClientes::
            join('clientes as c','c.id','situacion_clientes.cliente_id')
            ->join('users as u','u.id','c.user_id')
            ->Where([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'NULO'],
                ['situacion_clientes.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'PRETENDIDO'],
                ['situacion_clientes.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->groupBy([
                'situacion_clientes.situacion',
            ])
            ->orderBy('situacion_clientes.situacion','asc')
            ->select([
                'situacion_clientes.situacion  as situacion_anterior',
                DB::raw('count(situacion_clientes.situacion) as total_antes')
            ])
            ->get();

        $datas_a=[];

        foreach($situaciones_clientes_a as $item)
        {
            $datas_a[]=$item->total_antes;
        }
        //LEVANTADO - 12798<br>NUEVO - 5274<br>RECUPERADO ABANDONO - 4158<br>RECUPERADO RECIENTE - 2898<br>
        //LEVANTADO - 431<br>NUEVO - 211<br>RECUPERADO ABANDONO - 169<br>RECUPERADO RECIENTE - 101<br>

        $situaciones_clientes = SituacionClientes::leftJoin('situacion_clientes as a', 'a.cliente_id', 'situacion_clientes.cliente_id')
            ->join('clientes as c','c.id','situacion_clientes.cliente_id')
            ->join('users as u','u.id','c.user_id')
            ->Where([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'NULO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'PRETENDIDO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->groupBy([
                'a.situacion',
            ])
            ->orderBy('a.situacion','asc')
            ->select([
                //'situacion_clientes.situacion',
                'a.situacion  as situacion_anterior',
                //'situacion_clientes.user_clavepedido as user_identificador',
                DB::raw('count(situacion_clientes.situacion) as total')
            ])
            ->get();
        $datas=[];
        foreach($situaciones_clientes as $item_)
        {
            $datas[]=$item_->total;
        }

        $_resultado_grafico=[];
        /*foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            echo "<br>Caidos -- ".$situaciones_clientes_->situacion_anterior." -- ".$situaciones_clientes_->user_identificador." -- ".$situaciones_clientes_->total;
        }*/

        /*dd($ids_asesores);*/
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;

        $labels=[];

        //$datas_a=[];
        $backgroundColor=['rgb(30, 144, 255, 1)','rgb(178, 34, 34, 1 )','rgb(0, 128, 0, 1)','rgb(255, 160, 122, 1)'];
        foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            $labels[]=$situaciones_clientes_->situacion_anterior;
            //$datas[]=$situaciones_clientes_->total;
            //$datas_a[]=$situaciones_clientes_->total_antes;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => strtoupper(Carbon::now()->startOfMonth()->subMonth()->translatedFormat('F')),
                    'data' => $datas_a,
                    'backgroundColor' => 'rgb(30, 144, 255, 1)',
                    'borderColor' => 'rgb(32, 201, 151)',
                    'borderWidth' => '1',
                    'stack'=>'Stack 0',
                    'order' =>0
                ],
                [
                    'label' => strtoupper(Carbon::now()->startOfMonth()->translatedFormat('F')),
                    'data' => $datas,
                    'backgroundColor' => 'rgb(178, 34, 34, 1 )',
                    'borderColor' => 'rgb(32, 201, 151)',
                    'borderWidth' => '1',
                    'stack'=>'Stack 1',
                    'order' => 1
                ],
                [
                    'label' => 'Derivada',
                    'borderColor' => 'rgba(33,104,163,1)',
                    'data' => $datas_a,
                    'type' => 'line',
                    'order' => 2,
                ]
            ],
            'title'=>'Total caidos: '.$caidos_total
        ]);
    }

    public function metasAsesores(Request $request)
    {
        $caidos=Cliente::where('situacion','=','CAIDO')->activo()->where('tipo','=','1')
            ->whereNotIn('user_clavepedido',['17','18','19','21','B']);
        $caidos_total=$caidos->count();

        $periodo_antes = Carbon::now()->clone()->startOfMonth()->subMonth()->format('Y-m');
        $periodo_actual = Carbon::now()->clone()->startOfMonth()->format('Y-m');

        $situaciones_clientes = SituacionClientes::leftJoin('situacion_clientes as a', 'a.cliente_id', 'situacion_clientes.cliente_id')
            ->join('clientes as c','c.id','situacion_clientes.cliente_id')
            ->join('users as u','u.id','c.user_id')
            ->Where([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_clavepedido', '<>', 'B'],
                ['situacion_clientes.user_clavepedido', '<>', '17'],
                ['situacion_clientes.user_clavepedido', '<>', '18'],
                ['situacion_clientes.user_clavepedido', '<>', '19'],
                ['situacion_clientes.user_clavepedido', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->groupBy([
                //'situacion_clientes.situacion',
                'a.situacion',
                //'situacion_clientes.user_clavepedido'
            ])
            ->orderBy('a.situacion','asc')
            ->select([
                //'situacion_clientes.situacion',
                'a.situacion  as situacion_anterior',
                //'situacion_clientes.user_clavepedido as user_identificador',
                DB::raw('count(situacion_clientes.situacion) as total')
            ])
            ->get();

        $_resultado_grafico=[];
        /*foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            echo "<br>Caidos -- ".$situaciones_clientes_->situacion_anterior." -- ".$situaciones_clientes_->user_identificador." -- ".$situaciones_clientes_->total;
        }*/

        /*dd($ids_asesores);*/
        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;

        $labels=[];
        $datas=[];
        $backgroundColor=['rgb(30, 144, 255, 1)','rgb(178, 34, 34, 1 )','rgb(0, 128, 0, 1)','rgb(255, 160, 122, 1)'];
        foreach ($situaciones_clientes as $situaciones_clientes_)
        {
            $labels[]='Caidos de '.$situaciones_clientes_->situacion_anterior;
            $datas[]=$situaciones_clientes_->total;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Caidos con o sin deuda',
                    'data' => $datas,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => ['rgb(32, 201, 151)','rgb(32, 201, 151)'],
                    'borderWidth' => '1',
                    'hoverOffset'=>4,
                ],
            ],
            'title'=>'Total caidos: '.$caidos_total
        ]);
    }

}
