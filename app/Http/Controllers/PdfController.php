<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Meta;
use App\Models\Pago;
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
            ->join('users as u','u.id','c.user_id')
            ->where([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['a.situacion', '=', 'ABANDONO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['a.situacion', '=', 'ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO ABANDONO'],
                ['a.situacion', '=', 'NULO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO RECIENTE'],
                ['a.situacion', '=', 'CAIDO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'RECUPERADO RECIENTE'],
                ['a.situacion', '=', 'NULO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'NUEVO'],
                ['a.situacion', '=', 'BASE FRIA'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'NUEVO'],
                ['a.situacion', '=', 'BASE FRIA'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '=', '21'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'LEVANTADO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'LEVANTADO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO ABANDONO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'RECUPERADO RECIENTE'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->orWhere([
                ['situacion_clientes.situacion', '=', 'CAIDO'],
                ['a.situacion', '=', 'NUEVO'],
                ['situacion_clientes.periodo', '=', $periodo_actual],
                ['a.periodo', '=', $periodo_antes],
                ['situacion_clientes.user_identificador', '<>', 'B'],
                ['situacion_clientes.user_identificador', '<>', '15'],
                ['situacion_clientes.user_identificador', '<>', '16'],
                ['c.estado', '=', '1'],
                ['c.tipo', '=', '1']
            ])
            ->groupBy([
                'situacion_clientes.situacion',
                'situacion_clientes.user_identificador'
            ])
            ->select([
                'situacion_clientes.situacion',
                'situacion_clientes.user_identificador',
                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                    THEN (select sum(m.meta_quincena_recuperado_abandono) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.meta_quincena_recuperado_reciente) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.meta_quincena_nuevo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.meta_quincena_activo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas') end) as meta_quincena "),

                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                  THEN (select sum(m.cliente_recuperado_abandono) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.cliente_recuperado_reciente) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.cliente_nuevo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.cliente_activo) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas') end) as meta_1 "),

                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                    THEN (select sum(m.cliente_recuperado_abandono_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.cliente_recuperado_reciente_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.cliente_nuevo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.cliente_activo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas') end) as meta_2 "),
                DB::raw(" (CASE WHEN situacion_clientes.situacion='RECUPERADO ABANDONO'
                                                    THEN (select sum(m.cliente_recuperado_abandono_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='RECUPERADO RECIENTE'
                                                    THEN (select sum(m.cliente_recuperado_reciente_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='NUEVO'
                                                    THEN (select sum(m.cliente_nuevo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas')
                                                    WHEN situacion_clientes.situacion='LEVANTADO'
                                                    THEN (select sum(m.cliente_activo_2) from metas m where m.anio='" . $anio_w . "' and m.mes='" . $mes_w . "' and m.rol='Jefe de llamadas') end) as meta_2 "),

                DB::raw('count(situacion_clientes.situacion) as total')
            ])
            ->get();
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
                if($situaciones_clientes_->user_identificador=='21')
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
        /*foreach ($_estados as $_estados_)
        {
            {

                if($_resultado_grafico[$_estado_]['meta_quincena']==0)
                {
                    if($_resultado_grafico[$_estado_]['meta_1']==0)
                    {
                        if($_resultado_grafico[$_estado_]['meta_2']==0)
                        {

                        }
                        else{

                        }
                    }
                    else{

                    }
                }else{
                    $_resultado_grafico[$_estado_]['porcentaje']=$_resultado_grafico[$_estado_]['dividendo']/$_resultado_grafico[$_estado_]['meta_quincena'];
                }

            }

        }*/


        /*echo "<pre>";
        print_r($_resultado_grafico);
        echo "</pre>";*/
        //dd($_resultado_grafico);
        $activos_cuenta=0;
        $recurrentes_cuenta=0;
        $html = [];
        $html[] = '<table class="table table-situacion-clientes align-self-center" style="background: #ade0db; color: #0a0302">';

        /*echo "<pre>";
        print_r($situaciones_clientes);
        echo "</pre>";*/
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
        }
//dd($activos_cuenta,$recurrentes_cuenta);//14//51//307/1006

        foreach($_resultado_grafico as $_resultado_grafico_k=>$_resultado_grafico_v)
        {
            //var_dump($_resultado_grafico_);

            if($_resultado_grafico_k=='LEVANTADO' || $_resultado_grafico_k=='CAIDO')
                continue;

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
                $porcentaje = round(($_resultado_grafico_v["dividendo"] / $_resultado_grafico_v["meta_quincena"]) * 100, 2);
                $diferenciameta = $_resultado_grafico_v["meta_quincena"] - $_resultado_grafico_v["dividendo"];
                if ($diferenciameta < 0) $diferenciameta = 0;
                $valor_meta = $_resultado_grafico_v["meta_quincena"];
                if($porcentaje < 90){
                    $color_progress = '#FFD4D4';  /*ROSADO*/
                }else{
                    $color_progress = 'linear-gradient(90deg, #FFD4D4 0%, #d08585 89%, #dc3545 100%)';   /*ROSADO-ROJO*/
                }
            }
            else if ($_resultado_grafico_v["dividendo"] < $_resultado_grafico_v["meta_1"]) {
                //meta 1
                $porcentaje = round(($_resultado_grafico_v["dividendo"] / $_resultado_grafico_v["meta_1"]) * 100, 2);
                $diferenciameta = $_resultado_grafico_v["meta_1"] - $_resultado_grafico_v["dividendo"];
                if ($diferenciameta < 0) $diferenciameta = 0;
                $valor_meta = $_resultado_grafico_v["meta_1"];
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
                    $porcentaje = round(($_resultado_grafico_v["dividendo"] / $_resultado_grafico_v["meta_2"]) * 100, 2);
                }else{
                    $porcentaje = round(0, 2);
                }
                $diferenciameta = $_resultado_grafico_v["meta_2"] - $_resultado_grafico_v["dividendo"];
                if ($diferenciameta < 0) $diferenciameta = 0;
                $valor_meta = $_resultado_grafico_v["meta_2"];
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
                                            <div class="progress bg-white rounded" style="height: 40px">
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
                                      <div class="progress bg-white rounded" style="height: 40px">
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
                                      <div class="progress bg-white rounded" style="height: 40px">
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
                                      <div class="progress bg-white rounded" style="height: 40px">
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

        /*echo "<pre>";
        print_r($_resultado_grafico);
        echo "</pre>";*/


        foreach ($_resultado_grafico as $_resultado_grafico_k2=>$_resultado_grafico_v2)
        {

            if($_resultado_grafico_k2=='LEVANTADO')
            {
                $html[] = '<tr>';
                $html[] = '<td style="width:20%;height:150px;" class="text-center">';
                $html[] = '<span class="px-4 pt-1 pb-1 bg-info text-center w-20 rounded font-weight-bold"
                                    style="align-items: center;height: 40px !important; color: black !important;">' .
                    $_resultado_grafico_k2 .
                    '</span>';
                $html[] = '</td>';
                $html[] = '<td style="width:80%">';
                $porcentaje = 0;

                $diferenciameta=0;

                $porcentaje = round(($activos_cuenta / ($activos_cuenta+$recurrentes_cuenta) ) * 100, 2);
                $diferenciameta = ($activos_cuenta+$recurrentes_cuenta)*(70/100) - $activos_cuenta;

                $diferenciameta=round($diferenciameta);
                if($diferenciameta<0)$diferenciameta=0;
                $color_progress = '#FFD4D4';  /*ROSADO*/

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
            continue;

        }

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
            if($mes_artificio->year=='2022' && $mes_artificio->month=='12')
            {
                //solo considerar pagos de dia 17 en adelante
                continue;
            }else if($mes_artificio->year=='2022' && $mes_artificio->month=='11'){
                continue;
            }/*else{

            }*/

            $total_pagado_mespasado = Pedido::query()
                ->join("pago_pedidos", "pago_pedidos.pedido_id", "pedidos.id")
                //->where('pedidos.codigo', 'not like', "%-C%")
                ->whereNotIn('pedidos.user_id',[51,77,75])
                ->where('pedidos.estado', '1')
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
                ->where('pedidos.pago','1')
                ->where('pedidos.pagado','2')
                ->whereBetween(DB::raw('CAST(pedidos.created_at as date)'), [$mes_artificio->clone()->startOfMonth()->startOfDay(), $mes_artificio->clone()->endOfMonth()->endOfDay()])
                //->where(DB::raw('CAST(pago_pedidos.created_at as date)'), '<=', $mes_actual_artificio->clone()->endOfDay())
                ->where('pago_pedidos.estado', 1)
                ->where('pago_pedidos.pagado', 2)
                ->count();

            $total_pedido_mespasado = Pedido::query()
                //->where('pedidos.codigo', 'not like', "%-C%")
                ->whereNotIn('pedidos.user_id',[51,77,75])
                ->where('pedidos.estado', '1')
                ->where('pedidos.estado_correccion','0')
                ->where('pedidos.pendiente_anulacion', '<>', '1')
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
                                            <div class="progress bg-white rounded" style="height: 40px">
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
                                      <div class="progress bg-white rounded" style="height: 40px">
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
                                      <div class="progress bg-white rounded" style="height: 40px">
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
                                      <div class="progress bg-white rounded" style="height: 40px">
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



