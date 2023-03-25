<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\Facades\Charts;
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




        $ids_encargados=User::where('rol',User::ROL_ENCARGADO)->where('estado',1)
            ->select(['id','identificador','name','letra',])
            ->orderBy('supervisor','asc')
            ->orderBy('id','desc')
            ->get();




        $mes= Carbon::now()->month;
        $anio=Carbon::now()->year;



        foreach ($ids_encargados as $item => $asslst){
            $arregloasesores[$item] =$asslst->identificador;

            $ids_asesores=User::where('rol',User::ROL_ASESOR)
                ->where('estado',1)
                ->where('supervisor',$asslst->id)
                ->select(['id','identificador','name','letra',])
                ->pluck('id');

            $pedidosActivosPorAsesores = Pedido::whereIn('pedidos.user_id',$ids_asesores)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion','<>',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosPendAnulPorAsesores = Pedido::whereIn('pedidos.user_id',$ids_asesores)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion',1)
                ->where('pedidos.codigo','not like', "%-C%")
                ->whereMonth('pedidos.created_at', $mes)
                ->whereYear('pedidos.created_at',$anio)
                ->count();

            $pedidosAnuladosPorAsesores = Pedido::whereIn('pedidos.user_id',$ids_asesores)
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
        /*dd($arregloasesores,$gDataporc1,$oDataporc2,$rDataporc3);*/
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
}
