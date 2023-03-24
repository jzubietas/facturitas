<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\Facades\Charts;
use function PHPUnit\Framework\isNull;

class ChartController extends Controller
{
    //

    public function getData(Request $request)
    {
        $labels = ['Jan', 'Feb', 'Mar'];
        $values = [10, 20, 30];

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function getPedidosAsesores(Request $request)
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
        /*
        hacer un select por cada asesor y retornar el total
        */

        $contador=0;
        foreach ($pedidosActivosPorAsesores as $item => $asslst){
            $arregloasesores[$item] =($asslst->identificador)."-".($asslst->letra);
            $arreglocontador[$item] =$asslst->total;

            $pedidosInactivosPorAsesores = Pedido::selectRaw('pedidos.user_id, count(pedidos.user_id) as totalanul')
                ->where('pedidos.user_id',$asslst->user_id)
                ->where('pedidos.estado',1)
                ->where('pedidos.pendiente_anulacion',0)
                ->whereMonth('pedidos.created_at', 3)
                ->whereYear('pedidos.created_at',2023)
                ->groupBy('pedidos.user_id')
                ->get();
            dd($pedidosInactivosPorAsesores);
            if (!isset($pedidosInactivosPorAsesores)){
                foreach ($pedidosInactivosPorAsesores as $item2 => $asslst2){
                    $arreglocontadoranul[$contador] =$asslst2->totalanul;
                }
            }else{
                $arreglocontadoranul[$contador] =0;
            }
            $contador++;
                /*((isset($pedidosInactivosPorAsesores->totalanul))?$pedidosInactivosPorAsesores->totalanul:0);*/

        }
        /*dd($pedidosInactivosPorAsesores,$arreglocontadoranul);*/

        return response()->json([
            'labels' => $arregloasesores,
            'datasets' => [
                [
                    'label'               => 'Pedidos Inactivos',
                    'backgroundColor'     => 'rgba(60,141,188,0.9)',
                    'borderColor'         => 'rgba(60,141,188,0.8)',
                    'pointRadius'         => false,
                    'pointColor'          => '#3b8bba',
                    'pointStrokeColor'    => 'rgba(60,141,188,1)',
                    'pointHighlightFill'  => '#fff',
                    'pointHighlightStroke'=> 'rgba(60,141,188,1)',
                    'data'                => $arreglocontadoranul
                ],
                [
                    'label'                => 'Pedidos Activos ',
                    'backgroundColor'      => 'rgba(210, 214, 222, 1)',
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
}
